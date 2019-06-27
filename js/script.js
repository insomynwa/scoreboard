$(document).ready(function () {

    var request;
    var livegame = 0;

    // InitScoreboard();
    // DisableScoreboard();
    InitSetup();
    /* GetLiveScore();
    LoadData();
    GetGameModes(); */
    // GetGameStatus();
    // TestLoadGames();
    /* GetTeam();
    GetPlayer();
    GetGameDraw();
    GetGameModes();
    GetGameSet(); */

    /* function TestLoadGames(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?TestLoadGames=all',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    var gamedrawTxt = "";
                    for(var i=0; i<(data.gamedraws).length; i++){
                        var gamedraw = data.gamedraws[i];
                        var gamesets = gamedraw['gamesets'];
                        var contestant_a = gamedraw['contestant_a'];
                        var contestant_b = gamedraw['contestant_b'];
                        gamedrawTxt += "<div class='card'>";
                        gamedrawTxt += "<div class='card-header'>";
                        gamedrawTxt += "<a class='collapsed card-link' data-toggle='collapse' href='#collapse-" + gamedraw['id'] + "'>";
                        gamedrawTxt += contestant_a['name'] + " vs " + contestant_b['name'];
                        gamedrawTxt += "</a>";
                        gamedrawTxt += "</div>";
                        gamedrawTxt += "<div id='collapse-" + gamedraw['id'] + "' class='collapse' data-parent='#gamedraw-accordion'>";
                        gamedrawTxt += "<div class='card-body'>";
                        gamedrawTxt += "<ul class='list-group'>";
                        for( var j=0; j<gamesets.length; j++){
                            gamedrawTxt += "<li class='list-group-item " + gamesets[j]['active'] + "'>Set-" + gamesets[j]['num'] + "</li>";
                        }
                        gamedrawTxt += "</ul>";
                        gamedrawTxt += "</div>";
                        gamedrawTxt += "</div>";
                        gamedrawTxt += "</div>";
                    }
                    $("#gamedraw-accordion").html(gamedrawTxt);
                } else {
                }
            }
        });
    } */

    /* function InitScoreboard(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?InitScoreboard=live',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    if(data.has_live_game){
                        GetScore(data.gamedraw_id, data.gameset_id);
                        // $("#scoreboard-gamedraw").val(data.gamedraw_id);
                        // $("#scoreboard-gameset").val(data.gameset_id);
                        EnableScoreboard();
                        $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                        $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                    }else{
                        DisableScoreboard();
                    }
                } else {
                }
            }
        });
    } */

    function InitSetup() {
        var urls = [
            '/scoreboard/controller.php?GetGameSet=all',
            '/scoreboard/controller.php?GetGameDraw=all',
            '/scoreboard/controller.php?GetPlayer=all',
            '/scoreboard/controller.php?GetTeam=all',
            '/scoreboard/controller.php?GetLiveScore=1',
            '/scoreboard/controller.php?GetBowstyles=all',
            '/scoreboard/controller.php?GetGameModes=all',
            '/scoreboard/controller.php?InitSetup=1',
            '/scoreboard/controller.php?GetConfig=all',
        ];
        var actNames = [
            'GetGameSet',
            'GetGameDraw',
            'GetPlayer',
            'GetTeam',
            'GetLiveScore',
            'GetBowstyles',
            'GetGameModes',
            'InitSetup',
            'GetConfig'
        ];
        ajaxGetReq(urls, actNames);

        /* $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?InitSetup=1',
            success: function (data) {
                if (data.status) {
                    GetLiveScore();
                    LoadData();
                    GetGameModes();
                }
            }
        }); */
    }

    function GetLiveScore() {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetLiveScore=1',
            success: function (data) {
                if (data.status) {
                    if (data.has_value) {
                        Form_Load_Score(data.score);
                        EnableScoreboard();
                    } else {
                        Form_Reset_Score();
                        DisableScoreboard();
                    }
                    livegame = data.live_game;
                }
            }
        });
    }

    function LoadData() {
        GetTeam();
        GetPlayer();
        GetGameDraw();
        GetGameSet();
        /* $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?LoadData=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    Table_Load_Team($("#tbl-team"), data.teams);
                    Option_Load_Team($("#player-team"), data.teams);
                    Option_Load_Team($("#gamedraw-team-a"), data.teams);
                    Option_Load_Team($("#gamedraw-team-b"), data.teams);

                    Table_Load_Player($("#tbl-player"), data.players);
                    Option_Load_Player($("#gamedraw-player-a"), data.players);
                    Option_Load_Player($("#gamedraw-player-b"), data.players);

                    Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraws);
                    Option_Load_GameDraw($("#scoreboard-gamedraw"), data.gamedraws);
                    Table_Load_GameDraw($("#tbl-gamedraw"), data.gamedraws);

                    Table_Load_GameSet($("#gameset-table"), data.gamesets);
                } else {
                }
            }
        }); */
    }

    function GetTeam() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetTeam=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    if (data.has_value) {
                        Table_Load_Team($("#tbl-team"), data.teams);
                        Option_Load_Team($("#player-team"), data.teams);
                        Option_Load_Team($("#gamedraw-team-a"), data.teams);
                        Option_Load_Team($("#gamedraw-team-b"), data.teams);
                    } else {
                        $("#tbl-team").html("<tr><td>0 team. buat dulu!</td></tr>");
                    }
                } else {
                }
            }
        });
    }

    function GetPlayer() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetPlayer=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    if (data.has_value) {
                        Table_Load_Player($("#tbl-player"), data.players);
                        Option_Load_Player($("#gamedraw-player-a"), data.players);
                        Option_Load_Player($("#gamedraw-player-b"), data.players);
                    } else {
                        $("#tbl-player").html("<tr><td>0 player. buat dulu!</td></tr>");
                    }
                } else {
                }
            }
        });
    }

    function GetGameDraw() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameDraw=all',
            success: function (data) {
                var msg = "";
                if (data.status) {
                    if (data.has_value) {
                        Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraws);
                        // Option_Load_GameDraw($("#scoreboard-gamedraw"), data.gamedraws);
                        Table_Load_GameDraw($("#tbl-gamedraw"), data.gamedraws);
                    } else {
                        $("#tbl-gamedraw").html("<tr><td>0 game draw. buat dulu!</td></tr>");
                    }
                } else {
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
                    if (data.has_value) {
                        Table_Load_GameSet($("#gameset-table"), data.gamesets);
                    } else {
                        $("#gameset-table").html("<tr><td>0 game set. buat dulu!</td></tr>");
                    }
                } else {
                }
            }
        });
    }

    /* function GetScore(gamedraw, gameset) {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?Score=get&draw=' + gamedraw + '&set=' + gameset,
            success: function (data) {
                if (data.status) {
                    Form_Load_Score(data.score);
                    EnableScoreboard();
                    $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                    $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                    // TestLoadGames();
                    // LoadData();
                }
            }
        });
    } */

    function GetGameModes() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameModes=all',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    Radio_Load_GameMode($("#gamedraw-radio-area"), data.gamemodes);
                } else {
                }
            }
        });
    }

    function GetGameStatus() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameStatus=all',
            success: function (data) {
                if (data.status) {
                    if (data.has_value) {
                        Option_Load_GameStatus($("#gameset-status"), data.gamestatuses);
                    }
                } else {
                }
            }
        });
    }

    /* function GetPlayersByTeam(playerOptionObject, teamid) {

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
    } */

    /* function GetGameSetsByGameDraw(gameSetOptionObject, gamedrawid) {

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
    } */

    function GetTeamByID(teamid, modeget) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetTeam=' + teamid,
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
            url: '/scoreboard/controller.php?GetPlayer=' + playerid,
            success: function (data) {
                if (data.status) {
                    Form_Load_Player(data.player, modeget);
                }
            }
        });
    }

    function GetGameSetByID(gamesetid, modeget) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameSet=' + gamesetid,
            success: function (data) {
                if (data.status) {
                    Form_Load_GameSet(data.gameset, modeget);
                }
            }
        });
    }

    function GetGameDrawByID(gamedrawid, modeget) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameDraw=' + gamedrawid,
            success: function (data) {
                if (data.status) {
                    Form_Load_GameDraw(data.gamedraw, modeget);
                }
            }
        });
    }

    function Form_Load_Team(teamdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#team-modal-image").attr("src", "uploads/" + teamdata.logo).removeClass("hide");
            $("#team-name").val(teamdata.name).removeAttr("disabled");
            $("#team-desc").val(teamdata.desc).removeAttr("disabled");
            $("#team-logo").val('');
            $("#team-logo").removeAttr("disabled");
            $("#team-id").val(teamdata.id);
            $("#team-action").val("update");
            $("#team-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#team-modal-image").attr("src", "").addClass("hide");
            $("#team-name").val("").removeAttr("disabled");
            $("#team-desc").val("").removeAttr("disabled");
            $("#team-logo").val("");
            $("#team-logo").removeAttr("disabled");
            $("#team-id").val(0);
            $("#team-action").val("create");
            $("#team-submit").val("Create");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#team-modal-image").attr("src", "uploads/" + teamdata.logo).removeClass("hide");
            $("#team-name").val(teamdata.name).attr("disabled", "disabled");
            $("#team-desc").val(teamdata.desc).attr("disabled", "disabled");
            $("#team-logo").attr("disabled", "disabled");
            $("#team-id").val(teamdata.id);
            $("#team-action").val("delete");
            $("#team-submit").val("Delete");
        }
        modalTitle += " Team";
        $("#team-modal-title").html(modalTitle);
        $("#form-team-modal").modal();
    }

    function Form_Load_Player(playerdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#player-name").val(playerdata.name).removeAttr("disabled");
            $("#player-team").val(playerdata.team['id']).removeAttr("disabled");
            $("#player-id").val(playerdata.id);
            $("#player-action").val("update");
            $("#player-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#player-name").val("").removeAttr("disabled");
            $("#player-team").val(0).removeAttr("disabled");
            $("#player-id").val(0);
            $("#player-action").val("create");
            $("#player-submit").val("Create");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#player-name").val(playerdata.name).attr("disabled", "disabled");
            $("#player-team").val(playerdata.team['id']).attr("disabled", "disabled");
            $("#player-id").val(playerdata.id);
            $("#player-action").val("delete");
            $("#player-submit").val("Delete");
        }
        modalTitle += " Player";
        $("#player-modal-title").html(modalTitle);
        $("#form-player-modal").modal();
    }

    function Form_Load_Scoreboard_UI_Config(uidata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            // $("#player-name").val(playerdata.name).removeAttr("disabled");
            // $("#player-team").val(playerdata.team['id']).removeAttr("disabled");
            // $("#player-id").val(playerdata.id);
            // $("#player-action").val("update");
            // $("#player-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            // $("#player-name").val("").removeAttr("disabled");
            // $("#player-team").val(0).removeAttr("disabled");
            // $("#player-id").val(0);
            // $("#player-action").val("create");
            // $("#player-submit").val("Create");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            // $("#player-name").val(playerdata.name).attr("disabled", "disabled");
            // $("#player-team").val(playerdata.team['id']).attr("disabled", "disabled");
            // $("#player-id").val(playerdata.id);
            // $("#player-action").val("delete");
            // $("#player-submit").val("Delete");
        }
        modalTitle += " UI Config";
        $("#scoreboard-ui-modal-title").html(modalTitle);
        $("#form-scoreboard-ui-modal").modal();
    }

    function Form_Load_GameSet(gamesetdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#gameset-status-area").removeClass("hide");
            $("#gameset-gamedraw").val(gamesetdata.gamedraw['id']).removeAttr("disabled");
            $("#gameset-setnum").val(gamesetdata.num).removeAttr("disabled");
            $("#gameset-status").val(gamesetdata.gamestatus['id']).removeAttr("disabled");
            $("#gameset-prev-status").val(gamesetdata.gamestatus['id']);
            $("#gameset-id").val(gamesetdata.id);
            $("#gameset-action").val("update");
            $("#gameset-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#gameset-status-area").addClass("hide");
            $("#gameset-gamedraw").val(0).removeAttr("disabled");
            $("#gameset-setnum").val(1).removeAttr("disabled");
            $("#gameset-status").val(0);
            $("#gameset-prev-status").val(0);
            $("#gameset-id").val(0);
            $("#gameset-action").val("create");
            $("#gameset-submit").val("Create");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#gameset-status-area").removeClass("hide");
            $("#gameset-gamedraw").val(gamesetdata.gamedraw['id']).attr("disabled", "disabled");
            $("#gameset-setnum").val(gamesetdata.num).attr("disabled", "disabled");
            $("#gameset-status").val(gamesetdata.gamestatus['id']).attr("disabled", "disabled");
            $("#gameset-prev-status").val(gamesetdata.gamestatus['id']);
            $("#gameset-id").val(gamesetdata.id);
            $("#gameset-action").val("delete");
            $("#gameset-submit").val("Delete");
        }
        modalTitle += " Game Set";
        $("#gameset-modal-title").html(modalTitle);
        $("#form-gameset-modal").modal();
    }

    function Form_Load_GameDraw(gamedrawdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");

            if (gamedrawdata.bowstyle['id'] == 1) {
                $("#gamedraw-bowstyle-recurve").prop("checked", true);
            } else if (gamedrawdata.bowstyle['id'] == 2) {
                $("#gamedraw-bowstyle-compound").prop("checked", true);
            }
            /*
            * TO-DO: radio game mode dinamic
            */
            if (gamedrawdata.gamemode['id'] == 1) {
                $("#gamedraw-gamemode-beregu").prop("checked", true);
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                $(".gamedraw-team-opt-area-cls").removeClass("hide");
                $("#gamedraw-team-a").val(gamedrawdata.contestant_a['id']).removeAttr("disabled");
                $("#gamedraw-team-b").val(gamedrawdata.contestant_b['id']).removeAttr("disabled");
            } else if (gamedrawdata.gamemode['id'] == 2) {
                $("#gamedraw-gamemode-individu").prop("checked", true);
                $(".gamedraw-team-opt-area-cls").addClass("hide");
                $(".gamedraw-player-opt-area-cls").removeClass("hide");
                $("#gamedraw-player-a").val(gamedrawdata.contestant_a['id']).removeAttr("disabled");
                $("#gamedraw-player-b").val(gamedrawdata.contestant_b['id']).removeAttr("disabled");
            }
            $(".gamedraw-bowstyle-cls").removeAttr("disabled");
            $(".gamedraw-gamemode-cls").removeAttr("disabled");
            $("#gamedraw-id").val(gamedrawdata.id);
            $("#gamedraw-action").val("update");
            $("#gamedraw-submit").val("Update");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#gamedraw-num").val(1).removeAttr("disabled");
            /*
            * TO-DO: radio game mode dinamic
            */
            $("#gamedraw-bowstyle-recurve").prop("checked", true);
            $("#gamedraw-bowstyle-compound").prop("checked", false);
            $("#gamedraw-gamemode-beregu").prop("checked", true);
            $("#gamedraw-gamemode-individu").prop("checked", false);
            $(".gamedraw-player-opt-area-cls").addClass("hide");
            $(".gamedraw-team-opt-area-cls").removeClass("hide");
            $("#gamedraw-team-a").val(0).removeAttr("disabled");
            $("#gamedraw-team-b").val(0).removeAttr("disabled");
            $("#gamedraw-player-a").val(0).removeAttr("disabled");
            $("#gamedraw-player-b").val(0).removeAttr("disabled");
            $(".gamedraw-bowstyle-cls").removeAttr("disabled");
            $(".gamedraw-gamemode-cls").removeAttr("disabled");
            $("#gamedraw-id").val(0);
            $("#gamedraw-action").val("create");
            $("#gamedraw-submit").val("Save");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#gamedraw-num").val(gamedrawdata.num).attr("disabled", "disabled");

            if (gamedrawdata.bowstyle['id'] == 1) {
                $("#gamedraw-bowstyle-recurve").prop("checked", true);
            } else if (gamedrawdata.gamemode['id'] == 2) {
                $("#gamedraw-bowstyle-compound").prop("checked", true);
            }
            /*
            * TO-DO: radio game mode dinamic
            */
            if (gamedrawdata.gamemode['id'] == 1) {
                $("#gamedraw-gamemode-beregu").prop("checked", true);
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                $(".gamedraw-team-opt-area-cls").removeClass("hide");
                $("#gamedraw-team-a").val(gamedrawdata.contestant_a['id']).attr("disabled", "disabled");
                $("#gamedraw-team-b").val(gamedrawdata.contestant_b['id']).attr("disabled", "disabled");
            } else if (gamedrawdata.gamemode['id'] == 2) {
                $("#gamedraw-gamemode-individu").prop("checked", true);
                $(".gamedraw-team-opt-area-cls").addClass("hide");
                $(".gamedraw-player-opt-area-cls").removeClass("hide");
                $("#gamedraw-player-a").val(gamedrawdata.contestant_a['id']).attr("disabled", "disabled");
                $("#gamedraw-player-b").val(gamedrawdata.contestant_b['id']).attr("disabled", "disabled");
            }
            $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
            $(".gamedraw-gamemode-cls").attr("disabled", "disabled");
            $("#gamedraw-id").val(gamedrawdata.id);
            $("#gamedraw-action").val("delete");
            $("#gamedraw-submit").val("Delete");
        }
        modalTitle += " Game Draw";
        $("#gamedraw-modal-title").html(modalTitle);
        $("#form-gamedraw-modal").modal();
    }

    function Form_Load_Score(scoredata) {
        var contestant_a = scoredata.contestant_a;
        var contestant_b = scoredata.contestant_b;
        var score_a = scoredata.score_a;
        var score_b = scoredata.score_b;

        $("#score-team-a-title").html(contestant_a['name']);
        $("#score-a-timer").val(score_a['timer'] + "s");
        $("#score-a-gamedraw-id").val(scoredata.gamedraw['id']);
        $("#score-a-gameset-id").val(scoredata.gameset_id);
        $("#score-a-id").val(score_a['id']);
        $("#score-a-pt1").val(score_a['score_1']);
        $("#score-a-pt2").val(score_a['score_2']);
        $("#score-a-pt3").val(score_a['score_3']);
        $("#score-a-pt4").val(score_a['score_4']);
        $("#score-a-pt5").val(score_a['score_5']);
        $("#score-a-pt6").val(score_a['score_6']);

        var total_point = parseNum(score_a['score_1']) + parseNum(score_a['score_2']) + parseNum(score_a['score_3']) + parseNum(score_a['score_4']) + parseNum(score_a['score_5']) + parseNum(score_a['score_6']);
        $("#score-a-total").val(total_point);
        $("#score-a-gametotal").val(score_a['game_total_points']);
        $("#score-a-setpoints").val(score_a['point']);
        $("#score-a-gamepoints").val(score_a['game_points']); // setpoint + all
        $("#score-a-desc").val(score_a['desc']);


        $("#score-team-b-title").html(contestant_b['name']);
        $("#score-b-timer").val(score_b['timer'] + "s");
        $("#score-b-gamedraw-id").val(scoredata.gamedraw['id']);
        $("#score-b-gameset-id").val(scoredata.gameset_id);
        $("#score-b-id").val(score_b['id']);
        $("#score-b-pt1").val(score_b['score_1']);
        $("#score-b-pt2").val(score_b['score_2']);
        $("#score-b-pt3").val(score_b['score_3']);
        $("#score-b-pt4").val(score_b['score_4']);
        $("#score-b-pt5").val(score_b['score_5']);
        $("#score-b-pt6").val(score_b['score_6']);

        var total_point = parseNum(score_b['score_1']) + parseNum(score_b['score_2']) + parseNum(score_b['score_3']) + parseNum(score_b['score_4']) + parseNum(score_b['score_5']) + parseNum(score_b['score_6']);
        $("#score-b-total").val(total_point);
        $("#score-b-gametotal").val(score_b['game_total_points']);
        $("#score-b-setpoints").val(score_b['point']);
        $("#score-b-gamepoints").val(score_b['game_points']);
        $("#score-b-desc").val(score_b['desc']);
    }

    function Form_Reset_Score() {

        $("#score-team-a-title").html("Team A");
        $("#score-a-timer").val("0s");
        $("#score-a-gamedraw-id").val(0);
        $("#score-a-gameset-id").val(0);
        $("#score-a-id").val(0);
        $("#score-a-pt1").val(0);
        $("#score-a-pt2").val(0);
        $("#score-a-pt3").val(0);
        $("#score-a-pt4").val(0);
        $("#score-a-pt5").val(0);
        $("#score-a-pt6").val(0);

        $("#score-a-total").val(0);
        $("#score-a-gametotal").val(0);
        $("#score-a-setpoints").val(0);
        $("#score-a-gamepoints").val(0);
        $("#score-a-desc").val("");


        $("#score-team-b-title").html("Team B");
        $("#score-b-timer").val("0s");
        $("#score-b-gamedraw-id").val(0);
        $("#score-b-gameset-id").val(0);
        $("#score-b-id").val(0);
        $("#score-b-pt1").val(0);
        $("#score-b-pt2").val(0);
        $("#score-b-pt3").val(0);
        $("#score-b-pt4").val(0);
        $("#score-b-pt5").val(0);
        $("#score-b-pt6").val(0);

        $("#score-b-total").val(0);
        $("#score-b-gametotal").val(0);
        $("#score-b-setpoints").val(0);
        $("#score-b-gamepoints").val(0);
        $("#score-b-desc").val("");
    }

    function Load_Config(configData) {
        Form_Load_Config(configData);
        // console.log(livegame);
        Scoreboard_Config(configData.scoreboard);
        Load_Table_Preview_Scoreboard(configData.active_mode);
    }

    function Scoreboard_Config(config) {
        // console.log(config['logo']);
        $("#sb-logo").attr("checked", config['logo']['visibility']).val(config['logo']['visibility']);
        SetScoreboardVisibility($(".sb-logo-cls"), config['logo']['visibility']);
        $("#sb-team").attr("checked", config['team']['visibility']).val(config['team']['visibility']);
        SetScoreboardVisibility($(".sb-team-cls"), config['team']['visibility']);
        $("#sb-player").attr("checked", config['player']['visibility']).val(config['player']['visibility']);
        SetScoreboardVisibility($(".sb-player-cls"), config['player']['visibility']);
        $("#sb-timer").attr("checked", config['timer']['visibility']).val(config['timer']['visibility']);
        SetScoreboardVisibility($(".sb-timer-cls"), config['timer']['visibility'], "timer");
        $("#sb-p1").attr("checked", config['p1']['visibility']).val(config['p1']['visibility']);
        SetScoreboardVisibility($(".sb-p1-cls"), config['p1']['visibility']);
        $("#sb-p2").attr("checked", config['p2']['visibility']).val(config['p2']['visibility']);
        SetScoreboardVisibility($(".sb-p2-cls"), config['p2']['visibility']);
        $("#sb-p3").attr("checked", config['p3']['visibility']).val(config['p3']['visibility']);
        SetScoreboardVisibility($(".sb-p3-cls"), config['p3']['visibility']);
        $("#sb-p4").attr("checked", config['p4']['visibility']).val(config['p4']['visibility']);
        SetScoreboardVisibility($(".sb-p4-cls"), config['p4']['visibility']);
        $("#sb-p5").attr("checked", config['p5']['visibility']).val(config['p5']['visibility']);
        SetScoreboardVisibility($(".sb-p5-cls"), config['p5']['visibility']);
        $("#sb-p6").attr("checked", config['p6']['visibility']).val(config['p6']['visibility']);
        SetScoreboardVisibility($(".sb-p6-cls"), config['p6']['visibility']);
        $("#sb-set-total-points").attr("checked", config['set_total_points']['visibility']).val(config['set_total_points']['visibility']);
        SetScoreboardVisibility($(".sb-set-total-points-cls"), config['set_total_points']['visibility']);
        $("#sb-game-total-points").attr("checked", config['game_total_points']['visibility']).val(config['game_total_points']['visibility']);
        SetScoreboardVisibility($(".sb-game-total-points-cls"), config['game_total_points']['visibility']);
        $("#sb-set-points").attr("checked", config['set_points']['visibility']).val(config['set_points']['visibility']);
        SetScoreboardVisibility($(".sb-set-points-cls"), config['set_points']['visibility']);
        $("#sb-game-points").attr("checked", config['game_points']['visibility']).val(config['game_points']['visibility']);
        SetScoreboardVisibility($(".sb-game-points-cls"), config['game_points']['visibility']);
        $("#sb-description").attr("checked", config['description']['visibility']).val(config['description']['visibility']);
        SetScoreboardVisibility($(".sb-description-cls"), config['description']['visibility']);
    }

    function SetScoreboardVisibility(elementTarget, visibility, exc = "") {
        if (visibility == false) {
            elementTarget.addClass("d-none");
            if (exc == "timer") {
                $("#score-a-control").addClass("d-none");
                $("#score-b-control").addClass("d-none");
            }
        } else {
            if (exc == "timer") {
                $("#score-a-control").removeClass("d-none");
                $("#score-b-control").removeClass("d-none");
            }
            elementTarget.removeClass("d-none");
        }
    }

    function Form_Load_Config(configdata) {
        $("#config-id").val(configdata.id);
        $("#config-time-interval").val(configdata.time_interval);
        $("#config-active-mode").val(configdata.active_mode);
        $("#config-img").attr("src", "images/mode_" + configdata.active_mode + ".png");
    }

    function Radio_Load_Bowstyle(elemTarget, bowstylesdata) {
        var radioTxt = "";
        for (i = 0; i < bowstylesdata.length; i++) {
            radioTxt += "<div class='form-check form-check-inline'>";
            radioTxt += "<input type='radio'";
            if (i == 0) {
                radioTxt += " checked='checked' ";
            }
            radioTxt += "name='gamedraw_bowstyle' class='gamedraw-bowstyle-cls form-check-input' value='" + bowstylesdata[i].id + "' id='gamedraw-bowstyle-" + (bowstylesdata[i].name).toLowerCase() + "'><label for='gamedraw-bowstyle-" + (bowstylesdata[i].name).toLowerCase() + "' class='form-check-label'>" + bowstylesdata[i].name + "</label>";
            radioTxt += "</div>";
        }
        elemTarget.html(radioTxt);
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
        var tdText = "<thead><tr><th class='w-25 text-info font-weight-normal border-secondary'></th><th class='w-50 text-info font-weight-normal border-secondary'>Team</th><th class='w-25 text-info font-weight-normal border-secondary'><i class='fas fa-tasks'></i></th></tr></thead>";
        tdText += "<tbody>";
        for (var i = 0; i < teamsdata.length; i++) {
            tdText += "<tr><td class='text-white font-weight-light border-secondary'><img src='uploads/" + teamsdata[i].logo + "'></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + teamsdata[i].name + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 team-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-danger team-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_Player(elemTarget, playersdata) {
        var tdText = "<thead><tr><th class='w-50 text-info font-weight-normal border-secondary'>Name</th><th class='w-25 text-info font-weight-normal border-secondary'>Team</th><th class='w-25 text-info font-weight-normal border-secondary'><i class='fas fa-tasks'></i></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < playersdata.length; i++) {
            tdText += "<tr><td class='text-white font-weight-light border-secondary'><span>" + playersdata[i].name + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + playersdata[i].team['name'] + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 player-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-danger player-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameDraw(elemTarget, gamesdraws_data) {
        var tdText = "<thead><tr><th class='text-info font-weight-normal border-secondary'>Game</th><th class='text-info font-weight-normal border-secondary'></th><th class='text-info font-weight-normal border-secondary'>Draw</th><th class='text-info font-weight-normal border-secondary'></th><th class='text-info font-weight-normal border-secondary'><i class='fas fa-tasks'></i></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < gamesdraws_data.length; i++) {
            tdText += "<tr><td class='text-white font-weight-light border-secondary'><span>" + gamesdraws_data[i].num + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + gamesdraws_data[i].bowstyle['name'] + " - " + gamesdraws_data[i].gamemode['name'] + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + gamesdraws_data[i].contestant_a['name'] + " vs " + gamesdraws_data[i].contestant_b['name'] + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamedrawid='" + gamesdraws_data[i].id + "' class='btn btn-sm btn-primary gamedraw-view-btn-cls mr-3'><i class='fas fa-eye'></i></button></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamedrawid='" + gamesdraws_data[i].id + "' class='btn btn-sm btn-outline-warning mr-2 gamedraw-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-gamedrawid='" + gamesdraws_data[i].id + "' class='btn btn-sm btn-outline-danger gamedraw-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameDrawInfo(elemTarget, gamedraw) {
        var total_setpoint_a = 0;
        var total_setpoint_b = 0;
        var gameWinnerAClass = "";
        var gameWinnerBClass = "";
        var tdText = "<thead class='bg-dark text-white'><tr><th>Set</th><th>" + gamedraw.contestant_a['name'] + "</th><th>" + gamedraw.contestant_b['name'] + "</th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < (gamedraw.gamesets).length; i++) {
            tdText += "<tr><td><span>" + gamedraw.gamesets[i]['num'] + "</span></td>";
            var winnerACSS = "";
            var winnerBCSS = winnerACSS;
            var setpoint_a = gamedraw.gamesets[i]['score_a']['point'];
            var setpoint_b = gamedraw.gamesets[i]['score_b']['point'];
            total_setpoint_a += parseInt(setpoint_a);
            total_setpoint_b += parseInt(setpoint_b);
            if (setpoint_a > setpoint_b) {
                winnerACSS = "class='text-success font-weight-bold'";
            } else if (setpoint_a < setpoint_b) {
                winnerBCSS = "class='text-success font-weight-bold'";
            }
            tdText += "<td " + winnerACSS + "><span>" + setpoint_a + "</span></td>";
            tdText += "<td " + winnerBCSS + "><span>" + setpoint_b + "</span></td>";
            tdText += "</tr>";
        }
        if (total_setpoint_a > total_setpoint_b) {
            gameWinnerAClass = "bg-success text-white";
        } else if (total_setpoint_a < total_setpoint_b) {
            gameWinnerBClass = "bg-success text-white";
        }
        tdText += "<tr><td class='font-weight-bold'>Total</td>";
        tdText += "<td class='font-weight-bold " + gameWinnerAClass + "'><span>" + total_setpoint_a + "</span></td>";
        tdText += "<td class='font-weight-bold " + gameWinnerBClass + "'><span>" + total_setpoint_b + "</span></td>";
        tdText += "</tr>";
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameSet(elemTarget, gamesetsdata) {
        var tdText = "<thead><tr><th class='text-info font-weight-normal border-secondary'>Game</th><th class='text-info font-weight-normal border-secondary'>Draw</th><th class='text-info font-weight-normal border-secondary'>Set</th><th class='text-info font-weight-normal border-secondary'>Status</th><th class='text-info font-weight-normal border-secondary'><i class='fas fa-tasks'></i></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < gamesetsdata.length; i++) {
            tdText += "<tr><td class='text-white font-weight-light border-secondary'><span>" + gamesetsdata[i].gamedraw['num'] + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + gamesetsdata[i].gamedraw['contestant_a']['name'] + " vs " + gamesetsdata[i].gamedraw['contestant_b']['name'] + "</span></td>";
            tdText += "<td class='text-white font-weight-light border-secondary'><span>" + gamesetsdata[i].num + "</span></td>";
            if (gamesetsdata[i].gamestatus['id'] < 3) {
                if (gamesetsdata[i].id == livegame) {
                    // tdText += "<td><span>" + gamesetsdata[i].gamestatus['name'] + "</span></td>";
                    tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamedrawid='" + gamesetsdata[i].gamedraw['id'] + "' data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-danger gameset-stoplive-btn-cls mr-3'><i class='fas fa-stop-circle'></i></button>";
                } else {
                    tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamedrawid='" + gamesetsdata[i].gamedraw['id'] + "' data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-success gameset-live-btn-cls mr-3'><i class='fas fa-play-circle'></i></button>";
                }
            } else {
                tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamedrawid='" + gamesetsdata[i].gamedraw['id'] + "' data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-primary gameset-view-btn-cls mr-3'><i class='fas fa-eye'></i></button>";
            }
            tdText += "<span>" + gamesetsdata[i].gamestatus['name'] + "</span>";
            tdText += "</td>";
            var statusTxt = "";
            if (gamesetsdata[i].gameset_status == 2) {
                statusTxt += "disabled='disabled'";
            }
            tdText += "<td class='text-white font-weight-light border-secondary'><button data-gamesetid='" + gamesetsdata[i].id + "' " + statusTxt + " class='btn btn-sm btn-outline-warning mr-2 gameset-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-gamesetid='" + gamesetsdata[i].id + "' " + statusTxt + " class='btn btn-sm btn-outline-danger gameset-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameSetInfo(elemTarget, gameset) {
        var tdText = "<thead class='bg-dark text-white'><tr><th>Point</th><th>" + gameset.contestant_a['name'] + "</th><th>" + gameset.contestant_b['name'] + "</th></tr></thead>";
        tdText += "<tbody>";
        tdText += "<tr><td>1</td>";
        tdText += "<td>" + gameset.contestant_a['score']['score_1'] + "</td>";
        tdText += "<td>" + gameset.contestant_b['score']['score_1'] + "</td></tr>";
        tdText += "<tr><td>2</td>";
        tdText += "<td>" + gameset.contestant_a['score']['score_2'] + "</td>";
        tdText += "<td>" + gameset.contestant_b['score']['score_2'] + "</td></tr>";
        tdText += "<tr><td>3</td>";
        tdText += "<td>" + gameset.contestant_a['score']['score_3'] + "</td>";
        tdText += "<td>" + gameset.contestant_b['score']['score_3'] + "</td></tr>";
        tdText += "<tr class='d-none'><td>4</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_4'] + "</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_4'] + "</td></tr>";
        tdText += "<tr class='d-none'><td>5</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_5'] + "</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_5'] + "</td></tr>";
        tdText += "<tr class='d-none'><td>6</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_6'] + "</td>";
        tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_6'] + "</td></tr>";
        tdText += "<tr><td>Total</td>";
        tdText += "<td>" + gameset.contestant_a['score']['total'] + "</td>";
        tdText += "<td>" + gameset.contestant_b['score']['total'] + "</td></tr>";
        tdText += "<tr><td>Set Points</td>";
        tdText += "<td>" + gameset.contestant_a['score']['point'] + "</td>";
        tdText += "<td>" + gameset.contestant_b['score']['point'] + "</td></tr>";
        tdText += "</tbody>";
        elemTarget.html(tdText);
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

    function Option_Load_GameDraw(elemTarget, gamesdraws_data) {
        var opText = "<option value='0'>Select a game draw</option>";
        for (i = 0; i < gamesdraws_data.length; i++) {
            opText += "<option value='" + gamesdraws_data[i].id + "'>" + gamesdraws_data[i].num + ". " + gamesdraws_data[i].contestant_a['name'] + " vs " + gamesdraws_data[i].contestant_b['name'] + "" + "</option>";
        }
        $(elemTarget).html(opText);
    }

    function Option_Load_GameStatus(elemTarget, gamestatuses) {
        var opText = "<option value='0'>Select a status</option>";
        for (i = 0; i < gamestatuses.length; i++) {
            opText += "<option value='" + gamestatuses[i].id + "'>" + gamestatuses[i].name + "</option>";
        }
        elemTarget.html(opText);
    }

    /* function Option_Load_GameSet(gameSetOptionObject, gamesets) {
        var opText = "<option value='0'>Select a game set</option>";
        if (gamesets.length > 0) {
            for (i = 0; i < gamesets.length; i++) {
                opText += "<option value='" + gamesets[i].id + "'>" + gamesets[i].num + "</option>";
            }
        } else {
            opText += "";
        }
        gameSetOptionObject.html(opText);
    } */

    function DisableScoreboard() {
        DisableScoreA();
        DisableScoreB();
    }

    function EnableScoreboard() {
        EnableScoreA();
        EnableScoreB();
    }

    function EnableScoreA() {
        $("#form-score-a :input").prop("disabled", false);
        $("#score-a-timer-pause").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
        $("#score-a-timer-play").removeClass("btn-outline-primary").addClass("btn-primary").removeAttr("disabled").removeClass("play-on-cls");
    }

    function EnableScoreB() {
        $("#form-score-b :input").prop("disabled", false);
        $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
        $("#score-b-timer-play").removeClass("btn-outline-success").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    }

    function DisableScoreA() {
        $("#form-score-a :input").prop("disabled", true);
        $("#score-a-timer-play").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
        $("#score-a-timer-pause").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
    }

    function DisableScoreB() {
        $("#form-score-b :input").prop("disabled", true);
        $("#score-b-timer-play").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
        $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
    }

    function parseNum(str) {
        if (str == '') {
            return 0;
        }
        return parseInt(str);
    }

    function ajaxGetReq(urls, actNames) {
        if (urls.length > 0 && actNames.length > 0) {
            var act = actNames.pop();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: urls.pop(),
                success: function (data) {
                    if (data.status) {
                        if (act == 'GetGameModes') {
                            Radio_Load_GameMode($("#gamedraw-radio-area"), data.gamemodes);
                        }
                        else if (act == 'GetBowstyles') {
                            Radio_Load_Bowstyle($("#gamedraw-radio-bowstyle-area"), data.bowstyles);
                        }
                        else if (act == 'GetLiveScore') {
                            livegame = data.live_game;
                        }
                        else if (act == 'GetConfig') {
                            Load_Config(data.config);
                        }
                        if (data.has_value) {
                            if (act == 'GetGameModes') {

                            } else if (act == 'GetBowstyles') {

                            } else if (act == 'GetTeam') {
                                Table_Load_Team($("#tbl-team"), data.teams);
                                Option_Load_Team($("#player-team"), data.teams);
                                Option_Load_Team($("#gamedraw-team-a"), data.teams);
                                Option_Load_Team($("#gamedraw-team-b"), data.teams);
                            }
                            else if (act == 'GetPlayer') {
                                Table_Load_Player($("#tbl-player"), data.players);
                                Option_Load_Player($("#gamedraw-player-a"), data.players);
                                Option_Load_Player($("#gamedraw-player-b"), data.players);
                            }
                            else if (act == 'GetGameDraw') {
                                Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraws);
                                // Option_Load_GameDraw($("#scoreboard-gamedraw"), data.gamedraws);
                                Table_Load_GameDraw($("#tbl-gamedraw"), data.gamedraws);
                            }
                            else if (act == 'GetGameSet') {
                                Table_Load_GameSet($("#gameset-table"), data.gamesets);
                            } else if (act == 'GetLiveScore') {
                                Scoreboard_Config(data.config['scoreboard']);
                                Form_Load_Score(data.score);
                                EnableScoreboard();
                            }
                        } else {
                            if (act == 'GetGameModes') {

                            } else if (act == 'GetBowstyles') {

                            } else if (act == 'GetTeam') {
                                $("#tbl-team").html("<tr><td>0 team. buat dulu!</td></tr>");
                            }
                            else if (act == 'GetPlayer') {
                                $("#tbl-player").html("<tr><td>0 player. buat dulu!</td></tr>");
                            }
                            else if (act == 'GetGameDraw') {
                                $("#tbl-gamedraw").html("<tr><td>0 game draw. buat dulu!</td></tr>");
                            }
                            else if (act == 'GetGameSet') {
                                $("#gameset-table").html("<tr><td>0 game set. buat dulu!</td></tr>");
                            }
                            else if (act == 'GetLiveScore') {
                                Scoreboard_Config(data.config['scoreboard']);
                                Form_Reset_Score();
                                DisableScoreboard();
                            }
                        }
                    } else {
                    }
                    ajaxGetReq(urls, actNames);
                }
            });
        }
    }

    $(document).on('click', '.team-update-btn-cls', function (e) {
        e.preventDefault();
        var teamid = $(this).attr("data-teamid");
        GetTeamByID(teamid, 'update');
    });

    $(document).on('click', '.team-delete-btn-cls', function (e) {
        e.preventDefault();
        var teamid = $(this).attr("data-teamid");
        GetTeamByID(teamid, 'delete');
    });

    $(document).on('click', '.player-update-btn-cls', function (e) {
        e.preventDefault();
        var playerid = $(this).attr("data-playerid");
        GetPlayerByID(playerid, 'update');
    });

    $(document).on('click', '.player-delete-btn-cls', function (e) {
        e.preventDefault();
        var playerid = $(this).attr("data-playerid");
        GetPlayerByID(playerid, 'delete');
    });

    $(document).on('click', '.gamedraw-view-btn-cls', function (e) {
        e.preventDefault();
        var gamedrawid = $(this).attr("data-gamedrawid");
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameDrawInfo=' + gamedrawid,
            success: function (data) {
                if (data.status) {
                    if (data.has_value) {
                        Table_Load_GameDrawInfo($("#gamedraw-info-modal-table"), data.gamedraw);
                        $("#gamedraw-info-modal").modal();
                    }
                    // Form_Load_GameDraw(data.gamedraw, modeget);
                }
            }
        });
    });

    $(document).on('click', '.gameset-view-btn-cls', function (e) {
        e.preventDefault();
        var gamesetid = $(this).attr("data-gamesetid");
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetGameSetInfo=' + gamesetid,
            success: function (data) {
                if (data.status) {
                    if (data.has_value) {
                        Table_Load_GameSetInfo($("#gameset-info-modal-table"), data.gameset);
                        $("#gameset-info-modal").modal();
                    }
                    // Form_Load_GameDraw(data.gameset, modeget);
                }
            }
        });
    });

    $(document).on('click', '.gamedraw-update-btn-cls', function (e) {
        e.preventDefault();
        var gamedrawid = $(this).attr("data-gamedrawid");
        GetGameDrawByID(gamedrawid, 'update');
    });

    $(document).on('click', '.gamedraw-delete-btn-cls', function (e) {
        e.preventDefault();
        var gamedrawid = $(this).attr("data-gamedrawid");
        GetGameDrawByID(gamedrawid, 'delete');
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

    $(document).on('click', '.gameset-update-btn-cls', function (e) {
        e.preventDefault();
        var gamesetid = $(this).attr("data-gamesetid");
        GetGameStatus();
        GetGameSetByID(gamesetid, 'update');
    });

    $(document).on('click', '.gameset-delete-btn-cls', function (e) {
        e.preventDefault();
        var gamesetid = $(this).attr("data-gamesetid");
        GetGameStatus();
        GetGameSetByID(gamesetid, 'delete');
    });

    $(document).on('click', '.gameset-stoplive-btn-cls', function (e) {
        e.preventDefault();
        // var gamedrawid = $(this).attr("data-gamedrawid");
        // var gamesetid = $(this).attr("data-gamesetid");
        // GetGameSetByID(gamesetid, 'delete');
        if ($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")) {
            alert("Please pause/stop timer");
        } else {
            var gameset = $(this).attr("data-gamesetid");
            $.post("controller.php", {
                livegame_action: 'stop-live-game',
                gamesetid: gameset
            },
                function (data, status) {
                    var result = $.parseJSON(data);
                    if (result.status) {
                        Form_Reset_Score();
                        DisableScoreboard();
                        // GetLiveScore();
                        // GetGameSet();
                        var urls = [
                            '/scoreboard/controller.php?GetGameSet=all',
                            '/scoreboard/controller.php?GetLiveScore=1',
                        ];
                        var actNames = [
                            'GetGameSet',
                            'GetLiveScore',
                        ];
                        ajaxGetReq(urls, actNames);
                    }
                }
            );
        }
    });

    $(document).on('click', '.gameset-live-btn-cls', function (e) {
        e.preventDefault();
        if ($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")) {
            alert("Please pause/stop timer");
        } else {
            // var gamedraw = $(this).attr("data-gamedrawid");
            var gameset = $(this).attr("data-gamesetid");
            $.post("controller.php", {
                livegame_action: 'set-live-game',
                gamesetid: gameset
            },
                function (data, status) {
                    var result = $.parseJSON(data);
                    if (result.status) {
                        // GetLiveScore();
                        // GetGameSet();
                        var urls = [
                            '/scoreboard/controller.php?GetGameSet=all',
                            '/scoreboard/controller.php?GetLiveScore=1',
                        ];
                        var actNames = [
                            'GetGameSet',
                            'GetLiveScore',
                        ];
                        ajaxGetReq(urls, actNames);
                    }
                }
            );
        }
    });

    $("#team-create-btn").click(function (e) {
        Form_Load_Team(false, 'create');
    });

    $("#player-create-btn").click(function (e) {
        Form_Load_Player(false, 'create');
    });

    $("#gamedraw-create-btn").click(function (e) {
        Form_Load_GameDraw(false, 'create');
    });

    $("#gameset-create-btn").click(function (e) {
        Form_Load_GameSet(false, 'create');
    });

    $("#scoreboard-ui-config-btn").click(function (e) {
        Form_Load_Scoreboard_UI_Config(false, 'update');
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
        var prev_total = parseNum($("#score-a-total").val());
        var prev_game_total_points = parseNum($("#score-a-gametotal").val());
        var total_point = parseNum($("#score-a-pt1").val()) + parseNum($("#score-a-pt2").val()) + parseNum($("#score-a-pt3").val()) + parseNum($("#score-a-pt4").val()) + parseNum($("#score-a-pt5").val()) + parseNum($("#score-a-pt6").val());
        $("#score-a-total").val(total_point);
        var game_total_points = prev_game_total_points + (total_point - prev_total);
        $("#score-a-gametotal").val(game_total_points);
    });

    $('.score-b-input-cls').on('input', function (e) {
        var prev_total = parseNum($("#score-b-total").val());
        var prev_game_total_points = parseNum($("#score-b-gametotal").val());
        var total_point = parseNum($("#score-b-pt1").val()) + parseNum($("#score-b-pt2").val()) + parseNum($("#score-b-pt3").val()) + parseNum($("#score-b-pt4").val()) + parseNum($("#score-b-pt5").val()) + parseNum($("#score-b-pt6").val());
        $("#score-b-total").val(total_point);
        var game_total_points = prev_game_total_points + (total_point - prev_total);
        $("#score-b-gametotal").val(game_total_points);
    });

    $("#gameset-gamedraw").on("change", function () {
        var gamedraw_id = $(this).val();
        if (gamedraw_id == 0) {
            $("#gameset-setnum").val(1);
        } else {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?GetGameSetLastNum=' + gamedraw_id,
                success: function (data) {
                    if (data.status) {
                        if (data.has_value) {
                            $("#gameset-setnum").val(data.next_num);
                        } else {
                            $("#gameset-setnum").val(1);
                        }
                    } else {
                        $("#gameset-setnum").val(1);
                    }
                }
            });
        }
    });

    $("#config-active-mode").on("change", function () {
        var mode = $(this).val();
        Load_Table_Preview_Scoreboard(mode);
        // $("#config-img").attr("src", "images/mode_" + mode + ".png");
    });

    function Load_Table_Preview_Scoreboard(mode) {
        var table_preview = $("#table-prev-scoreboard");
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetConfig=scoreboard',
            success: function (data) {
                if (data.status) {
                    if (mode == 0) {
                        table_preview.hide();
                    } else {
                        var config = null;
                        if (mode == 1 || mode == 2 || mode == 3) {
                            config = data.config[0];
                        } else if (mode == 4 || mode == 5 || mode == 6) {
                            config = data.config[1];
                        } else if (mode == 7 || mode == 8 || mode == 9) {
                            config = data.config[2];
                        }
                        SetScoreboardVisibility($(".prev-score-logo"), config.logo['visibility']);
                        SetScoreboardVisibility($(".prev-score-team"), config.team['visibility']);
                        SetScoreboardVisibility($(".prev-score-player"), config.player['visibility']);
                        SetScoreboardVisibility($(".prev-score-timer"), config.timer['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-1"), config.p1['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-2"), config.p2['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-3"), config.p3['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-4"), config.p4['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-5"), config.p5['visibility']);
                        SetScoreboardVisibility($(".prev-score-point-6"), config.p6['visibility']);
                        SetScoreboardVisibility($(".prev-score-total"), config.set_total_points['visibility']);
                        SetScoreboardVisibility($(".prev-score-gametotal"), config.game_total_points['visibility']);
                        SetScoreboardVisibility($(".prev-score-setpoint"), config.set_points['visibility']);
                        SetScoreboardVisibility($(".prev-score-gamepoint"), config.game_points['visibility']);
                        SetScoreboardVisibility($(".prev-score-desc"), config.description['visibility']);

                        SetPreviewScoreboard(mode);
                        table_preview.show();
                    }
                } else {
                    table_preview.hide();
                }
            }
        });
    }

    function SetPreviewScoreboard(mode) {
        $(".prev-score-logo").show();
        $(".prev-score-team").show();
        $(".prev-score-player").show();
        $(".prev-score-point-4").hide();
        $(".prev-score-point-5").hide();
        $(".prev-score-point-6").hide();
        if (mode == 1) {
            $(".prev-score-timer").show();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").show();
        } else if (mode == 2) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").show();
            $(".prev-score-point-2").show();
            $(".prev-score-point-3").show();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").show();
        } else if (mode == 3) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").hide();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").show();
        } else if (mode == 4) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").hide();
        } else if (mode == 5) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").show();
            $(".prev-score-point-2").show();
            $(".prev-score-point-3").show();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").hide();
        } else if (mode == 6) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").hide();
            $(".prev-score-gametotal").hide();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").show();
            $(".prev-score-desc").hide();
        } else if (mode == 7) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").show();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").hide();
            $(".prev-score-desc").hide();
        } else if (mode == 8) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").show();
            $(".prev-score-point-2").show();
            $(".prev-score-point-3").show();
            $(".prev-score-total").show();
            $(".prev-score-gametotal").show();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").hide();
            $(".prev-score-desc").hide();
        } else if (mode == 9) {
            $(".prev-score-timer").hide();
            $(".prev-score-point-1").hide();
            $(".prev-score-point-2").hide();
            $(".prev-score-point-3").hide();
            $(".prev-score-total").hide();
            $(".prev-score-gametotal").show();
            $(".prev-score-setpoint").hide();
            $(".prev-score-gamepoint").hide();
            $(".prev-score-desc").hide();
        }
    }

    $("#form-team").on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/scoreboard/controller.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                // console.log('before send');
            },
            success: function (response) {
                var result = $.parseJSON(response);
                if (result.status) {
                    if (result.action == 'create') {
                        $("#team-logo").val("");
                        $("#team-name").val("");
                        $("#team-desc").val("");
                        $("#team-modal-image").attr("src", "").addClass("hide");
                    }

                    // GetLiveScore();
                    // LoadData();
                    var urls = [
                        '/scoreboard/controller.php?GetGameSet=all',
                        '/scoreboard/controller.php?GetGameDraw=all',
                        '/scoreboard/controller.php?GetPlayer=all',
                        '/scoreboard/controller.php?GetTeam=all',
                        '/scoreboard/controller.php?GetLiveScore=1',
                    ];
                    var actNames = [
                        'GetGameSet',
                        'GetGameDraw',
                        'GetPlayer',
                        'GetTeam',
                        'GetLiveScore',
                    ];
                    ajaxGetReq(urls, actNames);
                    if (result.action == 'update') {
                        if (result.new_logo != '') {
                            $("#team-modal-image").attr("src", "uploads/" + result.new_logo).removeClass("hide");
                        }
                    }
                    if (result.action == 'delete') {
                        $("#form-team-modal").modal("hide");
                        // GetLiveScore();
                    }
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

                // GetLiveScore();
                // LoadData();
                var urls = [
                    '/scoreboard/controller.php?GetGameSet=all',
                    '/scoreboard/controller.php?GetGameDraw=all',
                    '/scoreboard/controller.php?GetPlayer=all',
                    '/scoreboard/controller.php?GetTeam=all',
                    '/scoreboard/controller.php?GetLiveScore=1',
                ];
                var actNames = [
                    'GetGameSet',
                    'GetGameDraw',
                    'GetPlayer',
                    'GetTeam',
                    'GetLiveScore',
                ];
                ajaxGetReq(urls, actNames);
                if (data.action == 'delete') {
                    $("#form-player-modal").modal("hide");
                }
            }
        });
    });

    $("#form-config").on('submit', function (e) {
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
                if (response.action == 'create') {
                    // getGames();
                    $("select.gamedraw-team-cls").prop("selectedIndex", 0);
                    $("select.gamedraw-player-cls").prop("selectedIndex", 0);
                    /* $( "#gamedraw-gamemode-team" ).prop("checked", true);
                    $(".gamedraw-player-opt-area-cls").addClass("hide"); */
                    // GetPlayersByTeam(  $("#gamedraw-player-a, #gamedraw-player-b"), 0 );
                    $("#form-gamedraw input#gamedraw-num").val(response.next_num);
                }
                // GetLiveScore();
                // LoadData();
                var urls = [
                    '/scoreboard/controller.php?GetGameSet=all',
                    '/scoreboard/controller.php?GetGameDraw=all',
                    '/scoreboard/controller.php?GetPlayer=all',
                    '/scoreboard/controller.php?GetTeam=all',
                    '/scoreboard/controller.php?GetLiveScore=1',
                ];
                var actNames = [
                    'GetGameSet',
                    'GetGameDraw',
                    'GetPlayer',
                    'GetTeam',
                    'GetLiveScore',
                ];
                ajaxGetReq(urls, actNames);
                if (response.action == 'delete') {
                    $("#form-gamedraw-modal").modal("hide");
                    // GetLiveScore();
                }
            }
        });
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
                if (data.action == 'create') {
                    $("#gameset-gamedraw").val(0).removeAttr("disabled");
                    $("#gameset-setnum").val(1).removeAttr("disabled");
                    $("#gameset-id").val(0);
                    $("#gameset-action").val("create");
                }
                // GetLiveScore();
                // LoadData();
                var urls = [
                    '/scoreboard/controller.php?GetGameSet=all',
                    '/scoreboard/controller.php?GetGameDraw=all',
                    '/scoreboard/controller.php?GetPlayer=all',
                    '/scoreboard/controller.php?GetTeam=all',
                    '/scoreboard/controller.php?GetLiveScore=1',
                ];
                var actNames = [
                    'GetGameSet',
                    'GetGameDraw',
                    'GetPlayer',
                    'GetTeam',
                    'GetLiveScore',
                ];
                ajaxGetReq(urls, actNames);
                if (data.action == 'delete') {
                    $("#form-gameset-modal").modal("hide");
                    // GetLiveScore();
                }
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
            var data = $.parseJSON(response);
            if (data.status) {
                if (data.lock_gameset) {
                    // GetLiveScore();
                    // GetGameSet();
                }
            }
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
            var data = $.parseJSON(response);
            if (data.status) {
                if (data.lock_gameset) {
                    // GetLiveScore();
                    // GetGameSet();
                }
            }
        });
    });
})

var timer = null,
    timerB = null,
    isPlayA = false,
    isPlayB = false,
    interval = 1000,
    counterA = 0,
    counterB = 0;
function PauseTimerA() {
    clearInterval(timer);
    timer = null;
    isPlayA = false;
}
function PauseTimerB() {
    clearInterval(timerB);
    timerB = null;
    isPlayB = false;
}
function PlayTimerA() {
    if (timer !== null) return;
    timer = setInterval(function () {
        if (counterA < 0) counterA = 0;
        $("#score-a-timer").val(counterA + "s");
        $.post("controller.php", {
            score_timer_action: 'update-timer-a',
            score_a_id: $("#score-a-id").val(),
            timer_a: counterA
        },
            function (data, status) {
                // console.log(data);
            });
        counterA--;
    }, interval);
}
function PlayTimerB() {
    if (timerB !== null) return;
    timerB = setInterval(function () {
        if (counterB < 0) counterB = 0;
        $("#score-b-timer").val(counterB + "s");
        $.post("controller.php", {
            score_timer_action: 'update-timer-b',
            score_b_id: $("#score-b-id").val(),
            timer_b: counterB
        },
            function (data, status) {
                // console.log(data);
            }
        );
        counterB--;
    }, interval);
}
$("#score-a-timer-pause").click(function (e) {
    e.preventDefault();
    $("#score-a-submit").removeAttr("disabled");
    $(this).removeClass("btn-primary").addClass("btn-outline-dark").removeClass('text-light').addClass('text-primary').addClass('border-primary').attr("disabled", "disabled");
    $("#score-a-timer-play").removeClass("btn-outline-dark").addClass('text-light').addClass("btn-primary").removeAttr("disabled").removeClass("play-on-cls");
    PauseTimerA();
});
$("#score-a-timer-play").click(function (e) {
    e.preventDefault();
    $("#score-a-submit").attr("disabled", "disabled");
    counterA = parseInt(($("#score-a-timer").val()).replace("s", ""));
    $(this).removeClass("btn-primary").addClass("btn-outline-dark").removeClass('text-light').addClass('text-primary').addClass('border-primary').attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-a-timer-pause").removeClass("btn-outline-dark").addClass('text-light').addClass("btn-primary").removeAttr("disabled");
    PlayTimerA();
});
$("#score-b-timer-pause").click(function (e) {
    e.preventDefault();
    $("#score-b-submit").removeAttr("disabled");
    $(this).removeClass("btn-success").addClass("btn-outline-dark").removeClass('text-light').addClass('text-success').addClass('border-success').attr("disabled", "disabled");
    $("#score-b-timer-play").removeClass("btn-outline-dark").addClass('text-light').addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    PauseTimerB();
});
$("#score-b-timer-play").click(function (e) {
    e.preventDefault();
    $("#score-b-submit").attr("disabled", "disabled");
    $(this).removeClass("btn-success").addClass("btn-outline-dark").removeClass('text-light').addClass('text-success').addClass('border-success').attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-b-timer-pause").removeClass("btn-outline-dark").addClass('text-light').addClass("btn-success").removeAttr("disabled");
    counterB = parseInt(($("#score-b-timer").val()).replace("s", ""));
    PlayTimerB();
});