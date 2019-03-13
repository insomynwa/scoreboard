$(document).ready(function(){

    var request;
    // jQuery methods go here
    /*
    getGames(); */
    getTeams();
    getGameDraws();
    getPlayers();
    // getGameset();
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

    $("select.scoreboard-team-cls").on("change", function(){
        var teamid = "", playerOptionTagID = "";
        if(this.id == "scoreboard-team-a"){
            teamid = $("#scoreboard-team-a").val();
            playerOptionObject = $("#scoreboard-player-a");
        }
        else if(this.id == "scoreboard-team-b"){
            teamid = $("#scoreboard-team-b").val();
            playerOptionObject = $("#scoreboard-player-b");
        }
        // console.log($("#game-teamgame-single").is(":checked"));
        if( $("#scoreboard-gamemode-single").is(":checked") ){
            GetPlayersByTeam(  playerOptionObject, teamid );
        }
    });

    $("#form-scoreboard").on('submit', function(e){
        e.preventDefault();

        if( request){
            request.abort();
        }

        var $form = $(this);
        var serializedData = $form.serialize();

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done( function( response, textStatus, jqXHR) {
            var data = $.parseJSON(response);
            if( data.status ){
            }
        });
    });

    function getGameDraws(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGameDraws=all',
            success: function(data){
                var msg = "";
                if( data.status ){
                    // Scoreboard Form
                    EnableFormScoreboard();
                    RefreshGameDrawOption( $("#scoreboard-games"), data.draws );
                    RefreshGameDrawTable( $("#tbl-gamedraw"), data.draws );
                }else{
                    if( data.count > 0){
                        msg = "error on load game draws";

                    // Scoreboard Form
                        DisableFormScoreboard();
                        alert("error on load game draws");
                    }else{
                        msg = "Create a game draws!";
                        // Scoreboard Form
                        DisableFormScoreboard();
                    }

                    ErrorMessage( $("#form-scoreboard-msg"), msg );
                }
            }
        });
    }

    function getTeams(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getTeam=all',
            success: function(data){
                var msg = "";
                if( data.status ){
                    EnableFormPlayer();
                    RefreshTeamsTable( $("#tbl-team"), data.teams);
                    RefreshTeamsOption( $("#player-team"), data.teams);
                    if( data.count > 1 ){
                        EnableFormGameDraws();
                        RefreshTeamsOption( $("#gamedraw-team-a"), data.teams);
                        RefreshTeamsOption( $("#gamedraw-team-b"), data.teams);
                    }else{
                        DisableFormGameDraws();
                    }
                    // refreshTeamList(data.teams);
                    // refreshPlayerTeamOption(data.teams);
                    // refreshDrawingOption(data.teams);
                    // RefreshTeamsOption( "#scoreboard-team-a", data.teams );
                    // RefreshTeamsOption( "#scoreboard-team-b", data.teams );
                }else{
                    if( data.count > 0){
                        msg = "error on load teams!";

                        // Scoreboard Form
                        DisableFormPlayer();
                        DisableFormGameDraws();
                        alert("error on load teams!");
                    }else{
                        msg = "Create a teams";
                        // Scoreboard Form
                        DisableFormPlayer();
                        DisableFormGameDraws();
                    }

                    ErrorMessage( $("#form-gamedraw-msg"), msg );
                }
            }
        });
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
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getPlayer=all',
            success: function(data){
                var msg = "";
                if( data.status ){
                    // EnableFormPlayer();
                    RefreshPlayerTable( $("#tbl-player"), data.players);
                    // RefreshTeamsOption( $("#player-team"), data.teams);

                    if( data.count > 1 ){
                        $( "#gamedraw-gamemode-single" ).removeAttr( "disabled" );
                        if( $("#gamedraw-gamemode-single").is(":checked") ){
                            var teamAid = $("#gamedraw-team-a option:selected").val();
                            var teamBid = $("#gamedraw-team-b option:selected").val();
                            GetPlayersByTeam(  $("#gamedraw-player-a"), teamAid );
                            GetPlayersByTeam(  $("#gamedraw-player-b"), teamBid );
                        }
                        // EnableFormGameDraws();
                        // RefreshTeamsOption( $("#gamedraw-team-a"), data.teams);
                        // RefreshTeamsOption( $("#gamedraw-team-b"), data.teams);
                    }else{
                        // DisableFormGameDraws();
                        $( "#gamedraw-gamemode-team" ).prop("checked", true);
                        $( "#gamedraw-gamemode-single" ).attr( "disabled", "disabled" );
                    }
                }else{
                    if( data.count > 0){
                        msg = "error on load players!";

                        // DisableFormPlayer();
                        // DisableFormGameDraws();
                        alert("error on load players!");
                    }else{
                        // msg = "Create a teams";

                        // DisableFormPlayer();
                        // DisableFormGameDraws();
                    }
                    if( data.count > 1 ){
                        $( "#gamedraw-gamemode-single" ).removeAttr( "disabled" );
                        if( $("#gamedraw-gamemode-single").is(":checked") ){
                            var teamAid = $("#gamedraw-team-a option:selected").val();
                            var teamBid = $("#gamedraw-team-b option:selected").val();
                            GetPlayersByTeam(  $("#gamedraw-player-a"), teamAid );
                            GetPlayersByTeam(  $("#gamedraw-player-b"), teamBid );
                        }
                        // EnableFormGameDraws();
                        // RefreshTeamsOption( $("#gamedraw-team-a"), data.teams);
                        // RefreshTeamsOption( $("#gamedraw-team-b"), data.teams);
                    }else{
                        // DisableFormGameDraws();
                        $( "#gamedraw-gamemode-team" ).prop("checked", true);
                        $( "#gamedraw-gamemode-single" ).attr( "disabled", "disabled" );
                    }

                    // ErrorMessage( $("#form-gamedraw-msg"), msg );
                }
            }
        });
    }

    function GetPlayersByTeam( playerOptionObject, teamid){

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetPlayersByTeam=' + teamid,
            success: function(data){
                if( data.status ){
                    RefreshPlayerOption( playerOptionObject, data.players );
                }
            }
        });
        // return result;
    }

    $("select.gamedraw-team-cls").on("change", function(){
        var teamid = "", playerOptionObject = null;
        if(this.id == "gamedraw-team-a"){
            teamcat = "A";
            teamid = $("#form-gamedraw #gamedraw-team-a").val();
            playerOptionObject = $("#gamedraw-player-a");
        }
        else if(this.id == "gamedraw-team-b"){
            teamcat = "B";
            teamid = $("#form-gamedraw #gamedraw-team-b").val();
            playerOptionObject = $("#gamedraw-player-b");
        }

        if( $("#gamedraw-gamemode-single").is(":checked") ){console.log(teamid);
            GetPlayersByTeam(playerOptionObject, teamid);
        }
    });

    function getGames(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGames=all',
            success: function(data){
                if( data.status ){
                    // refreshGamesDrawingList(data.games);
                }
            }
        });
    }

    /* function getGameset(){
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
    } */

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

    function RefreshTeamsOption( elemTarget, teamsdata){
        var opText = "<option value='0'>Select a team</option>";
        for( i=0; i<teamsdata.length; i++){
            opText += "<option value='" + teamsdata[i].id +"'>" + teamsdata[i].name +"</option>";
        }
        elemTarget.html(opText);
    }

    function RefreshTeamsTable( elemTarget, teamsdata){
        var tdText = "<tr><th></th><th>Team</th></tr>";
        for( i=0; i<teamsdata.length; i++){
            tdText += "<tr><td><img src='uploads/" + teamsdata[i].logo +"'></td><td><span>" + teamsdata[i].name +"</span></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function RefreshPlayerTable( elemTarget, playersdata){
        var tdText = "<tr><th>Player</th><th>Team</th></tr>";
        for( i=0; i<playersdata.length; i++){
            var teamname = playersdata[i].team_id==0? "-" : playersdata[i].team_name;
            tdText += "<tr><td><span>" + playersdata[i].name +"</span></td><td><span>" + teamname +"</span></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function EnableFormScoreboard(){
        $( "#scoreboard-games" ).removeAttr( "disabled" );
        $( "#scoreboard-set" ).removeAttr( "disabled" );
        $( "#scoreboard-submit" ).removeAttr( "disabled" );
    }

    function DisableFormScoreboard(){
        $( "#scoreboard-games" ).attr( "disabled", "disabled" );
        $( "#scoreboard-set" ).attr( "disabled", "disabled" );
        $( "#scoreboard-submit" ).attr( "disabled", "disabled" );
    }

    function EnableFormGameDraws(){
        $( "#gamedraw-num" ).removeAttr( "disabled" );
        $( "#gamedraw-gamemode-team" ).removeAttr( "disabled" );
        $( "#gamedraw-gamemode-single" ).removeAttr( "disabled" );
        $( ".gamedraw-team-cls" ).removeAttr( "disabled" );
        $( ".gamedraw-player-cls" ).removeAttr( "disabled" );
        $( "#gamedraw-submit" ).removeAttr( "disabled" );
    }

    function DisableFormGameDraws(){
        $( "#gamedraw-num" ).attr( "disabled", "disabled" );
        $( "#gamedraw-gamemode-team" ).attr( "disabled", "disabled" );
        $( "#gamedraw-gamemode-single" ).attr( "disabled", "disabled" );
        $( ".gamedraw-team-cls" ).attr( "disabled", "disabled" );
        $( ".gamedraw-player-cls" ).attr( "disabled", "disabled" );
        $( "#gamedraw-submit" ).attr( "disabled", "disabled" );
    }

    function EnableFormPlayer(){
        $( "#player-team" ).removeAttr( "disabled" );
    }

    function DisableFormPlayer(){
        $( "#player-team" ).attr( "disabled", "disabled" );
    }

    function ErrorMessage( elemTarget, message ){
        elemTarget.html(message);
    }

    function RefreshGameDrawOption( elemTarget, gamesdrawing_data ){
        var opText = "<option value='0'>Select a game</option>";
        // if(gamesdrawing_data.length > 0){
            for( i=0; i<gamesdrawing_data.length; i++){
                /* var teamAName = (gamesdrawing_data[i].teamA_name == "" ) ? "" : "(" + gamesdrawing_data[i].teamA_name + ")";
                var teamBName = (gamesdrawing_data[i].teamB_name == "" ) ? "" : "(" + gamesdrawing_data[i].teamB_name + ")";
                var playerAName = (gamesdrawing_data[i].playerA_name == "" ) ? "" : "" + gamesdrawing_data[i].playerA_name ;
                var playerBName = (gamesdrawing_data[i].playerB_name == "" ) ? "" : "" + gamesdrawing_data[i].playerB_name ; */
                opText += "<option value='" + gamesdrawing_data[i].id + "'>" + gamesdrawing_data[i].num + ". " + gamesdrawing_data[i].teamA_name + "" + gamesdrawing_data[i].playerA_name + " vs "+ gamesdrawing_data[i].teamB_name + "" + gamesdrawing_data[i].playerB_name + "</option>";
            }
        // }else{
        //     opText += "";
        // }
        $(elemTarget).html(opText);
    }

    function RefreshGameDrawTable( elemTarget, gamesdrawing_data ){
        var tdText = "<tr><th>Game</th><th>Team A</th><th></th><th>Team B</th><th>Status</th></tr>";
        // if( gamesdrawing_data.length > 0 ){
            for( i=0; i<gamesdrawing_data.length; i++){
                /* var teamAName = (gamesdrawing_data[i].teamA_name == "" ) ? "" : "(" + gamesdrawing_data[i].teamA_name + ")";
                var teamBName = (gamesdrawing_data[i].teamB_name == "" ) ? "" : "(" + gamesdrawing_data[i].teamB_name + ")";
                var playerAName = (gamesdrawing_data[i].playerA_name == "" ) ? "" : "" + gamesdrawing_data[i].playerA_name;
                var playerBName = (gamesdrawing_data[i].playerB_name == "" ) ? "" : "" + gamesdrawing_data[i].playerB_name; */
                tdText += "<tr><td><span>" + gamesdrawing_data[i].num +"</span></td><td><span>" + gamesdrawing_data[i].teamA_name + "" + gamesdrawing_data[i].playerA_name + "</span></td><td>vs</td><td><span>"+ gamesdrawing_data[i].teamB_name + "" + gamesdrawing_data[i].playerB_name + "</span></td><td><span>"+ gamesdrawing_data[i].status +"</span></td></tr>";
            }
        // }else{
        //     tdText += "<tr><td>0 games</td></tr>";
        // }
        elemTarget.html(tdText);
    }

    /* function refreshGamesDrawingList(games){
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
    } */

    function RefreshTeamsOption( elemTarget, teams){
        var opText = "<option value='0'>Select team</option>";
        if(teams.length > 0){
            for( i=0; i<teams.length; i++){
                opText += "<option value='" + teams[i].id +"'>" + teams[i].name +"</option>";
            }
        }else{
            opText += "";
        }
        $(elemTarget).html(opText);
    }

    function refreshDrawingOption(teams){
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

    /* function refreshGamesetGameOption(games){//console.log(games);
        var opText = "<option value='0'>Select games</option>";
        if(games.length > 0){
            for( i=0; i<games.length; i++){
                opText += "<option value='" + games[i].id +"'>" + games[i].num +"</option>";
            }
        }else{
            opText += "";
        }
        $("#gameset-games").html(opText);
    } */

    function RefreshPlayerOption( playerOptionObject, players){
        var opText = "<option value='0'>Select player</option>";
        if(players.length > 0){
            for( i=0; i<players.length; i++){
                opText += "<option value='" + players[i].id +"'>" + players[i].name +"</option>";
            }
        }else{
            opText += "";
        }
        playerOptionObject.html(opText);
        /* if( team=="A" ){
            $("#game-playerA").html(opText);
        }else{
            $("#game-playerB").html(opText);
        } */
    }

    /* function refreshGamePlayerOption(team,players){
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
    } */

    /* function refreshGamesetTable(gameset){
        var tdText = "<tr><th>Game</th><th>Set</th><th>Status</th></tr>";
        if( gameset.length > 0 ){
            for( i=0; i<gameset.length; i++){
                tdText += "<tr><td><span>" + gameset[i].game +"</span></td><td><span>" + gameset[i].num +"</span></td><td><span>"+ gameset[i].status_desc +"</span></td></tr>";
            }
        }else{
            tdText += "<tr><td>0 gameset</td></tr>";
        }
        $("#tbl-gameset").html(tdText);
    } */

    $(".gamedraw-gamemode-cls").change( function(){
        $("select.gamedraw-team-cls").prop("selectedIndex", 0);
        $("select.gamedraw-player-cls").html("");
        if( $(this).val()==0 ){
            $(".gamedraw-player-opt-area-cls").addClass("hide");
        }
        else if( $(this).val()==1 ){
            GetPlayersByTeam(  $("#gamedraw-player-a, #gamedraw-player-b"), 0 );
            $(".gamedraw-player-opt-area-cls").removeClass("hide").addClass("show");
        }
    });

    $("#form-gamedraw").on('submit', function(e){
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
            // console.log(response);
            response = $.parseJSON(response);
            if( response.status ){
                getGameDraws();
                // getGames();
                $("select.gamedraw-team-cls").prop("selectedIndex", 0);
                $("select.gamedraw-player-cls").html("");
                $( "#gamedraw-gamemode-team" ).prop("checked", true);
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                // GetPlayersByTeam(  $("#gamedraw-player-a, #gamedraw-player-b"), 0 );
                $("#form-gamedraw input#gamedraw-num").val(response.next_num);
            }
        });
    });

    /* $("#form-gameset").on('submit', function(e){
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
    }); */

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
            //  console.log('before send');
            },
            success: function(data)
            {
                // data = $.parseJSON(data);
                if(data.status=='false')
                {
                    alert("error create a team!");
                }
                else
                {
                    $("#team-logo").val("");
                    $("#team-name").val("");
                    getTeams();
                }
            },
            error: function(e)
            {
                // console.log('error');
            }
        });
    });

    /* $("#form-score-a").on('submit', function(e){
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
    }); */

    /* $("#form-score-b").on('submit', function(e){
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
    }); */

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
                $("#player-name").val("");
                $("#player-team")[0].selectedIndex = 0;
                getPlayers();
            }
        });
    });
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