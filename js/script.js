$(document).ready(function(){

    var request;
    // jQuery methods go here
    getTeams();
    getPlayers();
    getGames();
    getGameset();
    /* getScores(); */

    $(".scoreboard-gamemode-cls").change( function(){
        $("select.scoreboard-team-cls").prop("selectedIndex", 0);
        $("select.scoreboard-player-cls").html("");
        if( $(this).val()==0 ){
            // $(".game-teams").show();
            $(".scoreboard-player-area-cls").addClass("hide");
        }
        else if( $(this).val()==1 ){
            // $(".game-teams").hide();
            $(".scoreboard-player-area-cls").removeClass("hide").addClass("show");
        }
    });

    function getTeams(){
        // var result = null;
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getTeam=all',
            success: function(data){
                if( data.status ){
                    refreshTeamList(data.teams);
                    refreshPlayerTeamOption(data.teams);
                    refreshDrawingOption(data.teams);
                    // result = data;
                    // console.log("FUCK");
                    /* refreshTeamList(data);
                    refreshDrawingOption(data); */
                }
                // console.log("DAMN");
                // console.log(data.teamA);
            }
        });
        // return result;
    }

    /* function getTeamById(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getTeamById=92',
            success: function(data){
                if( data.status ){
                    refreshTeamList(data.teams);
                    refreshPlayerTeamOption(data.teams);
                    refreshDrawingOption(data.teams);
                }
            }
        });
    } */

    function getPlayers(){
        // var result = null;
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getPlayer=all',
            success: function(data){
                // console.log(data);
                if( data.status ){
                    refreshPlayerList(data.players);
                }
            }
        });
        // return result;
    }

    function getPlayersByTeam(byteam){
        // var result = null;
        var team = "";
        if( byteam == 'A' ){
            team = $("#form-game #game-teamA").val();
        }
        else if( byteam == 'B' ){
            team = $("#form-game #game-teamB").val();
        }

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getPlayersByTeam=' + team,
            success: function(data){
                // console.log(data);
                if( data.status ){
                    // if( $("#game-teamgame-single").is("checked") ){
                        refreshGamePlayerOption( byteam, data.players );
                    // }
                }
            }
        });
        // return result;
    }

    $("select.game-teams").on("change", function(){
        var team = "";
        if(this.id == "game-teamA"){
            team = "A";
        }
        else if(this.id == "game-teamB"){
            team = "B";
        }
        console.log($("#game-teamgame-single").is(":checked"));
        if( $("#game-teamgame-single").is(":checked") ){
            getPlayersByTeam(team);
        }
    });

    function getGames(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGames=all',
            success: function(data){
                // console.log(data);
                // if( data.status ){
                    refreshGamesDrawingList(data.games);
                    refreshGamesetGameOption(data.games);
                // }
                // retGamesSet(data);
            }
        });
    }

    function getGameset(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGameset=all',
            success: function(data){
                // console.log(data.teamA);
                refreshGamesetTable(data.gameset);
                // retGamesSet(data);
            }
        });
    }

    function getScores(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?game=1&gameset=1',
            success: function(data){
                // console.log(data.teamA);
                setScoreBoard(data);
                setTeamIdForm(data);
                setScoreForm(data);
            }
        });
    }

    function setScoreBoard(data){
        $("#sb-gameset").text('Set ' + data.set);
        $("#sb-teamA-scoreid").val(data.teamA.points.score_id);

        $("#sb-teamA-logo").attr( 'src', ("uploads/" + data.teamA.logo));
        $("#sb-teamA-name").text(data.teamA.name);
        $("#sb-teamA-timer").text(data.teamA.time + "s");
        $("#sb-teamA-pt1").text( data.teamA.points.pt1 );
        $("#sb-teamA-pt2").text( data.teamA.points.pt2 );
        $("#sb-teamA-pt3").text( data.teamA.points.pt3 );
        $("#sb-teamA-pt4").text( data.teamA.points.pt4 );
        $("#sb-teamA-pt5").text( data.teamA.points.pt5 );
        $("#sb-teamA-pt6").text( data.teamA.points.pt6 );
        $("#sb-teamA-totpts").text( data.teamA.total_pts );
        $("#sb-teamA-setpts").text( data.teamA.set_pts );
        $("#sb-teamA-status").text( data.teamA.pts_stat );

        $("#sb-teamB-logo").attr( 'src', ("uploads/" + data.teamB.logo));
        $("#sb-teamB-scoreid").val(data.teamB.points.score_id);
        $("#sb-teamB-name").text(data.teamB.name);
        $("#sb-teamB-timer").text(data.teamB.time + "s");
        $("#sb-teamB-pt1").text( data.teamB.points.pt1 );
        $("#sb-teamB-pt2").text( data.teamB.points.pt2 );
        $("#sb-teamB-pt3").text( data.teamB.points.pt3 );
        $("#sb-teamB-pt4").text( data.teamB.points.pt4 );
        $("#sb-teamB-pt5").text( data.teamB.points.pt5 );
        $("#sb-teamB-pt6").text( data.teamB.points.pt6 );
        $("#sb-teamB-totpts").text( data.teamB.total_pts );
        $("#sb-teamB-setpts").text( data.teamB.set_pts );
        $("#sb-teamB-status").text( data.teamB.pts_stat );
    }

    function setTeamIdForm(data){
        $("#fti-nameA").val(data.teamA.name);
        $("#fti-nameB").val(data.teamB.name);
    }

    function setScoreForm(data){
        $("#fs-teamA-title").text(data.teamA.name);
        $("#fs-teamA-timer").val(data.teamA.time);

        $("#fs-teamA-pt1").val(data.teamA.points.pt1);
        $("#fs-teamA-pt2").val(data.teamA.points.pt2);
        $("#fs-teamA-pt3").val(data.teamA.points.pt3);
        $("#fs-teamA-pt4").val(data.teamA.points.pt4);
        $("#fs-teamA-pt5").val(data.teamA.points.pt5);
        $("#fs-teamA-pt6").val(data.teamA.points.pt6);

        $("#fs-teamA-setpts").val(data.teamA.set_pts);
        $("#fs-teamA-status").val(data.teamA.pts_stat);

        $("#fs-teamB-title").text(data.teamB.name);
        $("#fs-teamB-timer").val(data.teamB.time);

        $("#fs-teamB-pt1").val(data.teamB.points.pt1);
        $("#fs-teamB-pt2").val(data.teamB.points.pt2);
        $("#fs-teamB-pt3").val(data.teamB.points.pt3);
        $("#fs-teamB-pt4").val(data.teamB.points.pt4);
        $("#fs-teamB-pt5").val(data.teamB.points.pt5);
        $("#fs-teamB-pt6").val(data.teamB.points.pt6);

        $("#fs-teamB-setpts").val(data.teamB.set_pts);
        $("#fs-teamB-status").val(data.teamB.pts_stat);
    }

    function refreshPlayerTeamOption(teams){
        var opText = "<option value='0'>Select team</option>";
        if(teams.length > 0){
            for( i=0; i<teams.length; i++){
                opText += "<option value='" + teams[i].id +"'>" + teams[i].name +"</option>";
            }
        }else{
            opText += "";
        }
        $("#player-team").html(opText);
    }

    function refreshTeamList(teams){
        // console.log(teams);
        var tdText = "<tr><th></th><th>Team</th></tr>";
        if(teams.length > 0){
            for( i=0; i<teams.length; i++){
                tdText += "<tr><td><img src='uploads/" + teams[i].logo +"'></td><td><span>" + teams[i].name +"</span></td></tr>";
            }
        }else{
            tdText += "<tr><td>0 team</td></tr>";
        }
        $("#tbl-team").html(tdText);
    }

    function refreshPlayerList(players){
        // console.log(players);
        var tdText = "<tr><th>Player</th><th>Team</th></tr>";
        if(players.length > 0){
            for( i=0; i<players.length; i++){
                tdText += "<tr><td><span>" + players[i].name +"</span></td><td><span>" + players[i].team_name +"</span></td></tr>";
            }
        }else{
            tdText += "<tr><td>0 player</td></tr>";
        }
        $("#tbl-player").html(tdText);
    }

    function refreshGamesDrawingList(games){
        // console.log(games);
        var tdText = "<tr><th>Game ke-</th><th>Team A</th><th></th><th>Team B</th><th>Status</th></tr>";
        if( games.length > 0 ){
            for( i=0; i<games.length; i++){
                var teamAName = (games[i].teamA_name == "" ) ? "" : "(" + games[i].teamA_name + ")";
                var teamBName = (games[i].teamB_name == "" ) ? "" : "(" + games[i].teamB_name + ")";
                var playerAName = (games[i].playerA_name == "" ) ? "" : " (" + games[i].playerA_name + ")";
                var playerBName = (games[i].playerB_name == "" ) ? "" : " (" + games[i].playerB_name + ")";
                tdText += "<tr><td><span>" + games[i].num +"</span></td><td><span>" + teamAName + "" + playerAName + "</span></td><td>vs</td><td><span>"+ teamBName + "" + playerBName + "</span></td><td><span>"+ games[i].status +"</span></td></tr>";
            }
        }else{
            tdText += "<tr><td>0 games</td></tr>";
        }
        $("#tbl-game").html(tdText);
    }

    function refreshDrawingOption(teams){//console.log(teams);
        var opText = "<option value='0'>Select team</option>";
        if(teams.length > 0){
            for( i=0; i<teams.length; i++){
                opText += "<option value='" + teams[i].id +"'>" + teams[i].name +"</option>";
            }
        }else{
            opText += "";
        }
        $("select.game-teams").html(opText);
    }

    function refreshGamesetGameOption(games){//console.log(games);
        var opText = "<option value='0'>Select games</option>";
        if(games.length > 0){
            for( i=0; i<games.length; i++){
                opText += "<option value='" + games[i].id +"'>" + games[i].num +"</option>";
            }
        }else{
            opText += "";
        }
        $("#gameset-games").html(opText);
    }

    function refreshGamePlayerOption(team,players){//console.log(games);
        var opText = "<option value='0'>Select player</option>";
        if(players.length > 0){
            for( i=0; i<players.length; i++){
                opText += "<option value='" + players[i].id +"'>" + players[i].name +"</option>";
            }
        }else{
            opText += "";
        }
        if( team=="A" ){
            $("#game-playerA").html(opText);
        }else{
            $("#game-playerB").html(opText);
        }
    }

    function refreshGamesetTable(gameset){
        var tdText = "<tr><th>Game</th><th>Set</th><th>Status</th></tr>";
        if( gameset.length > 0 ){
            for( i=0; i<gameset.length; i++){
                tdText += "<tr><td><span>" + gameset[i].game +"</span></td><td><span>" + gameset[i].num +"</span></td><td><span>"+ gameset[i].status_desc +"</span></td></tr>";
            }
        }else{
            tdText += "<tr><td>0 gameset</td></tr>";
        }
        $("#tbl-gameset").html(tdText);
    }

    $(".game-teamgame-radio").change( function(){
        $("select.game-teams").prop("selectedIndex", 0);
        $("select.game-playerteam").html("");
        if( $(this).val()==0 ){
            // $(".game-teams").show();
            $(".game-player-opt-area").addClass("hide");
        }
        else if( $(this).val()==1 ){
            // $(".game-teams").hide();
            $(".game-player-opt-area").removeClass("hide").addClass("show");
        }
    });

    $("#form-game").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        // var $input = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        // $input.prop("disabled", true);

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            // console.log("DONE");
            var data = $.parseJSON(response);
            if( data.status ){
                getGames();//createScores();
                $("select.game-teams").prop("selectedIndex", 0);
                $("select.game-playerteam").html("");
                $("#form-game input#game-ke").val(data.next_gamenum);
                // $input.prop("disabled", false);
            }
        });
    });

    $("#form-gameset").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        // var $input = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        // $input.prop("disabled", true);

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            var data = $.parseJSON(response);
            if( data.status ){
                $("#gameset-games")[0].selectedIndex = 0;
                $("#gameset-set").val(0);
                getGameset();
                // console.log($.parseJSON(response));
                // console.log("DONE");//getGameset();createScores();
                // $input.prop("disabled", false);
            }
        });
    });

    $("#form-team").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "/scoreboard/tools/uploader.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend : function()
            {
             console.log('before send');
            },
            success: function(data)
            {
                data = $.parseJSON(data);
                // console.log(data);
                if(data.status=='false')
                {
                    // console.log('invalid');
                }
                else
                {
                    // console.log('view');
                    // console.log(data);
                    // $("#sb-teamA-logo").attr('src', 'ancuk' );
                    // $("#sb-teamB-logo").attr('src', ("uploads/" + data) );
                    $("#inp-team-logo").val("");
                    $("#inp-team-name").val("");
                    getTeams();
                    // var datateamlist = getTeams();
                    // console.log(datateamlist);
                    // if(datateamlist.status){
                        /* refreshTeamList(datateamlist.teams);
                        refreshPlayerTeamOption(datateamlist.teams);
                        refreshDrawingOption(datateamlist.teams); */
                    // }
                }
            },
            error: function(e)
            {
                console.log('error');
            }
        });
    });

    $("#form-score-a").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        var $input = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        // $input.prop("disabled", true);

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            console.log("DONE");
            getScores();
            //getGameset();createScores();
            // $input.prop("disabled", false);
        });
    });

    $("#form-score-b").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        var $input = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        // $input.prop("disabled", true);

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            // console.log("DONE");
            getScores();
            //getGameset();createScores();
            // $input.prop("disabled", false);
        });
    });

    $("#form-player").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        // var $input = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        // $input.prop("disabled", true);

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            var data = $.parseJSON(response);
            if( data.status ){
                $("#inp-player-name").val("");
                $("#player-team")[0].selectedIndex = 0;
                getPlayers();
            }
        });
    });

    /* $("#scoreA-timer-btnplay").click(function(e){
        e.preventDefault();
        var counterA = $("#fs-teamA-timer").val();
        var interval = setInterval(function() {
            $("#fs-teamA-timer").val(counterA);
            counterA--;
            // Display 'counter' wherever you want to display it.
            if (counterA == 0) {
                // Display a login box
                clearInterval(interval);
            }
        }, 1000);
    }); */
    /* $("#scoreA-timer-btnplay").click(function(e){
        e.preventDefault();
        var intervalA;
        // console.log(this.id);
        // if(this.id == ''){
            counterA = $("#fs-teamA-timer").val();
            playA();
        // }
        // if(this.id == 'scoreA-timer-btnpause'){
        //     pauseA();
        // }
    }); */
    /* $("#scoreA-timer-btnpause").click(function(e){
        e.preventDefault();
        pauseA();
    }); */
})

var timer = null,
    timerB = null,
    interval = 1000,
    counterA = 0,
    counterB = 0;
function pauseA(){
    clearInterval(timer);
    timer = null;
}
function playA(){
    if (timer !== null) return;
    timer = setInterval(function() {
        $("#fs-teamA-timer").val(counterA);
        counterA--;
    }, interval);
}
function pauseB(){
    clearInterval(timerB);
    timerB = null;
}
function playB(){
    if (timerB !== null) return;
    timerB = setInterval(function() {
        $("#fs-teamB-timer").val(counterB);
        counterB--;
    }, interval);
}
$("#scoreA-timer-btnpause").click(function(e){
    e.preventDefault();
    // console.log("pause");
    pauseA();
});
$("#scoreA-timer-btnplay").click(function(e){
    e.preventDefault();
    var intervalA;
    // console.log("play");
    // if(this.id == ''){
        counterA = $("#fs-teamA-timer").val();
        playA();
    // }
    // if(this.id == 'scoreA-timer-btnpause'){
    //     pauseA();
    // }
});
$("#scoreB-timer-btnpause").click(function(e){
    e.preventDefault();
    // console.log("pause");
    pauseB();
});
$("#scoreB-timer-btnplay").click(function(e){
    e.preventDefault();
    counterB = $("#fs-teamB-timer").val();
    playB();
});