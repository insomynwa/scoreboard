$(document).ready(function () {

    var request;

    GetTeams();
    GetPlayers();
    GetGameDraws();
    GetGameModes();
    GetGameSet();

    function GetGameDraws() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGameDraw=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    // Scoreboard Form
                    // EnableFormScoreboard();
                    Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraws);
                    Option_Load_GameDraw($("#scoreboard-gamedraw"), data.gamedraws);
                    Table_Load_GameDraw($("#tbl-gamedraw"), data.gamedraws);
                } else {
                    if (data.count > 0) {
                        msg = "error on load game draws";

                        // Scoreboard Form
                        // DisableFormScoreboard();
                        alert("error on load game draws");
                    } else {
                        msg = "Create a game draws!";
                        // Scoreboard Form
                        // DisableFormScoreboard();
                    }

                }
                ErrorMessage($("#form-gameset-msg"), msg);
            }
        });
    }

    function GetTeams() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getTeam=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    Table_Load_Team($("#tbl-team"), data.teams);
                    Option_Load_Team($("#player-team"), data.teams);
                    Option_Load_Team($("#gamedraw-team-a"), data.teams);
                    Option_Load_Team($("#gamedraw-team-b"), data.teams);
                    /* EnableFormPlayer();
                    if( data.count > 1 ){
                        EnableFormGameDraws();
                        Option_Load_Team( $("#gamedraw-team-a"), data.teams);
                        Option_Load_Team( $("#gamedraw-team-b"), data.teams);
                    }else{
                        DisableFormGameDraws();
                    } */
                } else {
                    /* if( data.count > 0){
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

                    ErrorMessage( $("#form-gamedraw-msg"), msg ); */
                }
            }
        });
    }

    function GetGameModes() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getGameMode=all',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    Radio_Load_GameMode($("#gamedraw-radio-area"), data.gamemodes);
                } else {
                }
            }
        });
    }

    function GetPlayers() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?getPlayer=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    // EnableFormPlayer();
                    Table_Load_Player($("#tbl-player"), data.players);
                    Option_Load_Player($("#gamedraw-player-a"), data.players);
                    Option_Load_Player($("#gamedraw-player-b"), data.players);
                    // Option_Load_Team( $("#player-team"), data.teams);

                    if (data.count > 1) {
                        /* $( "#gamedraw-gamemode-single" ).removeAttr( "disabled" );
                        if( $("#gamedraw-gamemode-single").is(":checked") ){
                            var teamAid = $("#gamedraw-team-a option:selected").val();
                            var teamBid = $("#gamedraw-team-b option:selected").val();
                            GetPlayersByTeam(  $("#gamedraw-player-a"), teamAid );
                            GetPlayersByTeam(  $("#gamedraw-player-b"), teamBid );
                        } */
                        // EnableFormGameDraws();
                        // Option_Load_Team( $("#gamedraw-team-a"), data.teams);
                        // Option_Load_Team( $("#gamedraw-team-b"), data.teams);
                    } else {
                        // DisableFormGameDraws();
                        /* $( "#gamedraw-gamemode-team" ).prop("checked", true);
                        $( "#gamedraw-gamemode-single" ).attr( "disabled", "disabled" ); */
                    }
                } else {
                    if (data.count > 0) {
                        /* msg = "error on load players!";

                        // DisableFormPlayer();
                        // DisableFormGameDraws();
                        alert("error on load players!"); */
                    } else {
                        // msg = "Create a teams";

                        // DisableFormPlayer();
                        // DisableFormGameDraws();
                    }
                    if (data.count > 1) {
                        /* $( "#gamedraw-gamemode-single" ).removeAttr( "disabled" );
                        if( $("#gamedraw-gamemode-single").is(":checked") ){
                            var teamAid = $("#gamedraw-team-a option:selected").val();
                            var teamBid = $("#gamedraw-team-b option:selected").val();
                            GetPlayersByTeam(  $("#gamedraw-player-a"), teamAid );
                            GetPlayersByTeam(  $("#gamedraw-player-b"), teamBid );
                        } */
                        // EnableFormGameDraws();
                        // Option_Load_Team( $("#gamedraw-team-a"), data.teams);
                        // Option_Load_Team( $("#gamedraw-team-b"), data.teams);
                    } else {
                        // DisableFormGameDraws();
                        /* $( "#gamedraw-gamemode-team" ).prop("checked", true);
                        $( "#gamedraw-gamemode-single" ).attr( "disabled", "disabled" ); */
                    }

                    // ErrorMessage( $("#form-gamedraw-msg"), msg );
                }
            }
        });
    }

    function GetPlayersByTeam(playerOptionObject, teamid) {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetPlayersByTeam=' + teamid,
            success: function (data) {
                if (data.status) {
                    RefreshPlayerOption(playerOptionObject, data.players);
                }
            }
        });
    }

    function GetScore(gamedraw, gameset) {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?Score=get&draw=' + gamedraw + '&set=' + gameset,
            success: function (data) {
                if (data.status) {
                    Form_Load_Score(data.score['gameset']);
                }
            }
        });
    }

    function GetGameSetsByGameDraw(gameSetOptionObject, gamedrawid) {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameSetsByGameDraw=' + gamedrawid,
            success: function (data) {
                if (data.status) {
                    Option_Load_GameSet(gameSetOptionObject, data.gamesets);
                }
            }
        });
    }

    function GetGameSet() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameSet=all',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    Table_Load_GameSet($("#gameset-table"), data.gamesets);
                } else {
                }
            }
        });
    }

    function GetTeamByID(teamid, modeget) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetTeamById=' + teamid,
            success: function (data) {
                if (data.status) {
                    Form_Load_Team(data.team, modeget);
                }
            }
        });
    }

    function GetPlayerByID(playerid, modeget) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetPlayerByID=' + playerid,
            success: function (data) {
                if (data.status) {
                    Form_Load_Player(data.player, modeget);
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

    function Form_Load_Score(score_data) {
        var contestant_a = score_data.gamedraw['contestant_a'];
        var contestant_b = score_data.gamedraw['contestant_b'];
        var score_a = score_data.score_a;
        var score_b = score_data.score_b;

        $("#score-team-a-title").html(contestant_a['name']);
        $("#score-a-timer").val(score_a['timer'] + "s");
        $("#score-a-gamedraw-id").val(score_data.gamedraw['id']);
        $("#score-a-gameset-id").val(score_data.id);
        $("#score-a-id").val(score_a['id']);
        $("#score-a-pt1").val(score_a['score_1']);
        $("#score-a-pt2").val(score_a['score_2']);
        $("#score-a-pt3").val(score_a['score_3']);
        $("#score-a-pt4").val(score_a['score_4']);
        $("#score-a-pt5").val(score_a['score_5']);
        $("#score-a-pt6").val(score_a['score_6']);

        var total_point = parseInt(score_a['score_1']) + parseInt(score_a['score_2']) + parseInt(score_a['score_3']) + parseInt(score_a['score_4']) + parseInt(score_a['score_5']) + parseInt(score_a['score_6']);
        $("#score-a-total").val(total_point);
        $("#score-a-setpoints").val(score_a['point']);
        $("#score-a-desc").val(score_a['desc']);


        $("#score-team-b-title").html(contestant_b['name']);
        $("#score-b-timer").val(score_b['timer'] + "s");
        $("#score-b-gamedraw-id").val(score_data.gamedraw['id']);
        $("#score-b-set-id").val(score_data.id);
        $("#score-b-id").val(score_b['id']);
        $("#score-b-pt1").val(score_b['score_1']);
        $("#score-b-pt2").val(score_b['score_2']);
        $("#score-b-pt3").val(score_b['score_3']);
        $("#score-b-pt4").val(score_b['score_4']);
        $("#score-b-pt5").val(score_b['score_5']);
        $("#score-b-pt6").val(score_b['score_6']);

        var total_point = parseInt(score_b['score_1']) + parseInt(score_b['score_2']) + parseInt(score_b['score_3']) + parseInt(score_b['score_4']) + parseInt(score_b['score_5']) + parseInt(score_b['score_6']);
        $("#score-b-total").val(total_point);
        $("#score-b-setpoints").val(score_b['point']);
        $("#score-b-desc").val(score_b['desc']);
    }

    function Form_Load_Team(teamdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#team-modal-image").attr("src", "uploads/" + teamdata.logo).removeClass("hide");
            $("#team-name").val(teamdata.name);
            $("#team-desc").val(teamdata.desc);
            $("#team-id").val(teamdata.id);
            $("#team-action").val("update");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#team-modal-image").attr("src", "").addClass("hide");
            $("#team-name").val("");
            $("#team-desc").val("");
            $("#team-id").val(0);
            $("#team-action").val("create");
        }
        modalTitle += " Team";
        $("#team-modal-title").html(modalTitle);
        $("#form-team-modal").modal();
    }

    function Form_Load_Player(playerdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#player-name").val(playerdata.name);
            $("#player-team").val(playerdata.team['id']);
            $("#player-id").val(playerdata.id);
            $("#player-action").val("update");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#player-name").val("");
            $("#player-team").val(0);
            $("#player-id").val(0);
            $("#player-action").val("create");
        }
        modalTitle += " Player";
        $("#player-modal-title").html(modalTitle);
        $("#form-player-modal").modal();
    }

    function Radio_Load_GameMode(elemTarget, gamemodesdata) {
        var radioTxt = "";
        for (i = 0; i < gamemodesdata.length; i++) {
            radioTxt += "<div class='form-check form-check-inline'>";
            radioTxt += "<input type='radio'";
            if (i == 0) {
                radioTxt += " checked='checked' ";
            }
            radioTxt += "name='gamedraw_gamemode' class='gamedraw-gamemode-cls form-check-input' value='" + gamemodesdata[i].id + "' id='gamedraw-gamemode-" + (gamemodesdata[i].name).toLowerCase() + "'><label for='gamedraw-gamemode-" + (gamemodesdata[i].name).toLowerCase() + "' class='form-check-label'>" + gamemodesdata[i].name + "</label>";
            radioTxt += "</div>";
        }
        elemTarget.html(radioTxt);
    }

    function Table_Load_Team(elemTarget, teamsdata) {
        var tdText = "<thead class='thead-dark'><tr><th></th><th>Team</th><th></th></tr></thead>";
        for (i = 0; i < teamsdata.length; i++) {
            tdText += "<tr><td><img src='uploads/" + teamsdata[i].logo + "'></td>";
            tdText += "<td><span>" + teamsdata[i].name + "</span></td>";
            tdText += "<td><button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 team-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function Table_Load_Player(elemTarget, playersdata) {
        var tdText = "<thead class='thead-dark'><tr><th>Player</th><th>Team</th><th></th></tr></thead>";
        for (i = 0; i < playersdata.length; i++) {
            var teamname = playersdata[i].team_id == 0 ? "-" : playersdata[i].team['name'];
            tdText += "<tr><td><span>" + playersdata[i].name + "</span></td>";
            tdText += "<td><span>" + teamname + "</span></td>";
            tdText += "<td><button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 player-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function Table_Load_GameDraw(elemTarget, gamesdraws_data) {
        var tdText = "<thead class='thead-dark'><tr><th>Game</th><th></th><th>Draw</th><th>Status</th><th></th></tr></thead>";
        for (i = 0; i < gamesdraws_data.length; i++) {
            tdText += "<tr><td><span>" + gamesdraws_data[i].num + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].gamemode['name'] + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].contestant_a['name'] + " vs " + gamesdraws_data[i].contestant_b['name'] + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].gamestatus['name'] + "</span></td>";
            tdText += "<td><button id='' class='btn btn-sm btn-outline-warning mr-2'><i class='fas fa-edit'></i></button>";
            tdText += "<button id='' class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function Table_Load_GameSet(elemTarget, gamesetsdata) {
        var tdText = "<thead class='thead-dark'><tr><th>Game</th><th>Draw</th><th>Set</th><th>Status</th><th></th><th></th></tr></thead>";
        for (i = 0; i < gamesetsdata.length; i++) {
            tdText += "<tr><td><span>" + gamesetsdata[i].gamedraw['num'] + "</span></td>";
            tdText += "<td><span>" + gamesetsdata[i].gamedraw['contestant_a']['name'] + " vs " + gamesetsdata[i].gamedraw['contestant_b']['name'] + "</span></td>";
            tdText += "<td><span>" + gamesetsdata[i].num + "</span></td>";
            tdText += "<td><span>" + gamesetsdata[i].gamestatus['name'] + "</span></td>";
            tdText += "<td><button id='' class='btn btn-sm btn-outline-warning mr-2'><i class='fas fa-edit'></i></button>";
            tdText += "<button id='' class='btn btn-sm btn-outline-danger'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        elemTarget.html(tdText);
    }

    function Option_Load_GameDraw(elemTarget, gamesdraws_data) {
        var opText = "<option value='0'>Select a game draw</option>";
        for (i = 0; i < gamesdraws_data.length; i++) {
            opText += "<option value='" + gamesdraws_data[i].id + "'>" + gamesdraws_data[i].num + ". " + gamesdraws_data[i].contestant_a['name'] + " vs " + gamesdraws_data[i].contestant_b['name'] + "" + "</option>";
        }
        $(elemTarget).html(opText);
    }

    function Option_Load_GameSet(gameSetOptionObject, gamesets) {
        var opText = "<option value='0'>Select a game set</option>";
        if (gamesets.length > 0) {
            for (i = 0; i < gamesets.length; i++) {
                opText += "<option value='" + gamesets[i].id + "'>" + gamesets[i].num + "</option>";
            }
        } else {
            opText += "";
        }
        gameSetOptionObject.html(opText);
    }

    function Option_Load_Team(elemTarget, teamsdata) {
        var opText = "<option value='0'>Select a team</option>";
        for (i = 0; i < teamsdata.length; i++) {
            opText += "<option value='" + teamsdata[i].id + "'>" + teamsdata[i].name + "</option>";
        }
        elemTarget.html(opText);
    }

    function Option_Load_Player(elemTarget, playersdata) {
        var opText = "<option value='0'>Select a player</option>";
        for (i = 0; i < playersdata.length; i++) {
            opText += "<option value='" + playersdata[i].id + "'>" + playersdata[i].name + "</option>";
        }
        elemTarget.html(opText);
    }

    function LockScoreboardFilter() {
        $("#scoreboard-gamedraw").attr("disabled", "disabled");
        $("#scoreboard-gameset").attr("disabled", "disabled");
        $("#scoreboard-render-btn").attr("disabled", "disabled");
    }

    function UnlockScoreboardFilter() {
        $("#scoreboard-gamedraw").removeAttr("disabled");
        $("#scoreboard-gameset").removeAttr("disabled");
        $("#scoreboard-render-btn").removeAttr("disabled");
    }

    function ErrorMessage(elemTarget, message) {
        elemTarget.html(message);
    }

    function RefreshPlayerOption(playerOptionObject, players) {
        var opText = "<option value='0'>Select player</option>";
        if (players.length > 0) {
            for (i = 0; i < players.length; i++) {
                opText += "<option value='" + players[i].id + "'>" + players[i].name + "</option>";
            }
        } else {
            opText += "";
        }
        playerOptionObject.html(opText);
    }

    $(document).on('click', '.team-update-btn-cls', function (e) {
        e.preventDefault();
        var teamid = $(this).attr("data-teamid");
        GetTeamByID(teamid, 'update');
    });

    $(document).on('click', '.player-update-btn-cls', function (e) {
        e.preventDefault();
        var playerid = $(this).attr("data-playerid");
        GetPlayerByID(playerid, 'update');
    });

    $(document).on('change', '.gamedraw-gamemode-cls', function () {

        $("select.gamedraw-team-cls").prop("selectedIndex", 0);
        $("select.gamedraw-player-cls").prop("selectedIndex", 0);
        if (this.id == "gamedraw-gamemode-beregu") {
            $(".gamedraw-player-opt-area-cls").addClass("hide");
            $(".gamedraw-team-opt-area-cls").removeClass("hide").addClass("show");
        }
        else if (this.id == "gamedraw-gamemode-individu") {
            $(".gamedraw-team-opt-area-cls").addClass("hide");
            $(".gamedraw-player-opt-area-cls").removeClass("hide").addClass("show");
            // GetPlayersByTeam(  $("#gamedraw-player-a, #gamedraw-player-b"), 0 );
        }

    });

    $("#team-create-btn").click(function (e) {
        Form_Load_Team(false, 'create');
    });

    $("#player-create-btn").click(function (e) {
        Form_Load_Player(false, 'create');
    });

    $("#scoreboard-render-btn").click(function (e) {
        e.preventDefault();
        if($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")){
            alert("Please pause/stop timer");
        }else{
            var gamedraw = $("#scoreboard-gamedraw").val();
            var gameset = $("#scoreboard-gameset").val();
            if (gamedraw != 0 && gameset != 0) {
                GetScore(gamedraw, gameset);
                $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
            } else {
                alert("Please select Game Draw & Set");
            }
        }
    });

    $("#scoreboard-filter-lock").click(function (e) {
        e.preventDefault();
        var attr = $(this).attr('data-lock');

        if (attr == 1) {
            $(this).html("<i class='fas fa-unlock text-white'></i>");
            UnlockScoreboardFilter();
            $(this).attr('data-lock', 0);
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-warning');
        } else {
            $(this).html("<i class='fas fa-lock'></i>");
            LockScoreboardFilter();
            $(this).attr('data-lock', 1);
            $(this).addClass('btn-danger');
            $(this).removeClass('btn-warning');
        }
    });

    $('.score-a-input-cls').on('input', function (e) {
        var total_point = parseInt($("#score-a-pt1").val()) + parseInt($("#score-a-pt2").val()) + parseInt($("#score-a-pt3").val()) + parseInt($("#score-a-pt4").val()) + parseInt($("#score-a-pt5").val()) + parseInt($("#score-a-pt6").val());
        $("#score-a-total").val(total_point);
    });

    $('.score-b-input-cls').on('input', function (e) {
        var total_point = parseInt($("#score-b-pt1").val()) + parseInt($("#score-b-pt2").val()) + parseInt($("#score-b-pt3").val()) + parseInt($("#score-b-pt4").val()) + parseInt($("#score-b-pt5").val()) + parseInt($("#score-b-pt6").val());
        $("#score-b-total").val(total_point);
    });

    $("select.gamedraw-team-cls").on("change", function () {
        var teamid = "", playerOptionObject = null;
        if (this.id == "gamedraw-team-a") {
            teamcat = "A";
            teamid = $("#form-gamedraw #gamedraw-team-a").val();
            playerOptionObject = $("#gamedraw-player-a");
        }
        else if (this.id == "gamedraw-team-b") {
            teamcat = "B";
            teamid = $("#form-gamedraw #gamedraw-team-b").val();
            playerOptionObject = $("#gamedraw-player-b");
        }

        if ($("#gamedraw-gamemode-single").is(":checked")) {//console.log(teamid);
            GetPlayersByTeam(playerOptionObject, teamid);
        }
    });

    $("#scoreboard-gamedraw").on("change", function () {
        var gameDrawID = "", gameSetOptionObject = null;
        gameDrawID = $("#scoreboard-gamedraw").val();
        gameSetOptionObject = $("#scoreboard-gameset");

        if (gameDrawID == 0) {
            var opText = "<option value='0'>Select a game set</option>";
            $("#scoreboard-gameset").html(opText);
        } else {
            GetGameSetsByGameDraw(gameSetOptionObject, gameDrawID);
        }
    });

    $("#form-gameset").on('submit', function (e) {
        e.preventDefault();

        if (request) {
            request.abort();
        }

        var $form = $(this);
        var serializedData = $form.serialize();

        request = $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: serializedData,
        });

        request.done(function (response, textStatus, jqXHR) {
            var data = $.parseJSON(response);
            if (data.status) {
                GetGameSet();
            }
        });
    });

    $("#form-gamedraw").on('submit', function (e) {
        e.preventDefault();

        if (request) {
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

        request.done(function (response, textStatus, jqXHR) {
            // console.log(response);
            response = $.parseJSON(response);
            if (response.status) {
                GetGameDraws();
                // getGames();
                $("select.gamedraw-team-cls").prop("selectedIndex", 0);
                $("select.gamedraw-player-cls").prop("selectedIndex", 0);
                /* $( "#gamedraw-gamemode-team" ).prop("checked", true);
                $(".gamedraw-player-opt-area-cls").addClass("hide"); */
                // GetPlayersByTeam(  $("#gamedraw-player-a, #gamedraw-player-b"), 0 );
                $("#form-gamedraw input#gamedraw-num").val(response.next_num);
            }
        });
    });

    $("#form-team").on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/scoreboard/tools/uploader.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                //  console.log('before send');
            },
            success: function (data) {
                data = $.parseJSON(data);
                if (data.status) {
                    if (data.action == 'create') {
                        $("#team-logo").val("");
                        $("#team-name").val("");
                        $("#team-desc").val("");
                        $("#team-modal-image").attr("src", "").addClass("hide");
                    }

                    GetTeams();
                    GetPlayers();
                    GetGameDraws();
                    GetGameSet();
                }
                else {
                    alert("error!");
                }
            },
            error: function (e) {
                // console.log('error');
            }
        });
    });

    $("#form-score-a").on('submit', function (e) {
        e.preventDefault();

        if (request) {
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

        request.done(function (response, textStatus, jqXHR) {
        });
    });

    $("#form-score-b").on('submit', function (e) {
        e.preventDefault();

        if (request) {
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

        request.done(function (response, textStatus, jqXHR) {
        });
    });

    $("#form-player").on('submit', function (e) {
        e.preventDefault();

        if (request) {
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

        request.done(function (response, textStatus, jqXHR) {
            var data = $.parseJSON(response);
            if (data.status) {
                if (data.action == 'create') {
                    $("#player-name").val("");
                    $("#player-team").val(0);
                    $("#player-id").val(0);
                    $("#player-action").val("create");
                }

                GetPlayers();
                GetGameDraws();
                GetGameSet();
            }
        });
    });

    /* $(".scoreboard-gamemode-cls").change(function () {
        $("select.scoreboard-team-cls").prop("selectedIndex", 0);
        $("select.scoreboard-player-cls").html("");
        if ($(this).val() == 0) {
            // $(".game-teams").show();
            $(".scoreboard-player-area-cls").addClass("hide");
        }
        else if ($(this).val() == 1) {
            // $(".game-teams").hide();
            $(".scoreboard-player-area-cls").removeClass("hide").addClass("show");
        }
    }); */

    /* $("select.scoreboard-team-cls").on("change", function () {
        var teamid = "", playerOptionTagID = "";
        if (this.id == "scoreboard-team-a") {
            teamid = $("#scoreboard-team-a").val();
            playerOptionObject = $("#scoreboard-player-a");
        }
        else if (this.id == "scoreboard-team-b") {
            teamid = $("#scoreboard-team-b").val();
            playerOptionObject = $("#scoreboard-player-b");
        }
        // console.log($("#game-teamgame-single").is(":checked"));
        if ($("#scoreboard-gamemode-single").is(":checked")) {
            GetPlayersByTeam(playerOptionObject, teamid);
        }
    }); */
})

var timer = null,
    timerB = null,
    isPlayA = false,
    isPlayB = false,
    interval = 1000,
    counterA = 0,
    counterB = 0;
function pauseA() {
    clearInterval(timer);
    timer = null;
    isPlayA = false;
}
function pauseB() {
    clearInterval(timerB);
    timerB = null;
    isPlayB = false;
}
function playA() {
    if (timer !== null) return;
    timer = setInterval(function () {
        $("#score-a-timer").val(counterA + "s");
        counterA--;
    }, interval);
}
function playB() {
    if (timerB !== null) return;
    timerB = setInterval(function () {
        $("#score-b-timer").val(counterB + "s");
        counterB--;
    }, interval);
}
$("#score-a-timer-pause").click(function (e) {
    e.preventDefault();
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
    $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    pauseA();
});
$("#score-a-timer-play").click(function (e) {
    e.preventDefault();
    counterA = parseInt(($("#score-a-timer").val()).replace("s", ""));
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-a-timer-pause").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
    playA();
});
$("#score-b-timer-pause").click(function (e) {
    e.preventDefault();
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
    $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    pauseB();
});
$("#score-b-timer-play").click(function (e) {
    e.preventDefault();
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-b-timer-pause").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
    counterB = parseInt(($("#score-b-timer").val()).replace("s", ""));
    playB();
});