$(document).ready(function () {

    var request;
    var livegame = 0;

    // InitScoreboard();
    // DisableScoreboard();
    GetLiveScore();
    LoadData();
    GetGameModes();
    // TestLoadGames();
    /* GetTeam();
    GetPlayer();
    GetGameDraw();
    GetGameModes();
    GetGameSet(); */

    function TestLoadGames(){
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
    }

    function InitScoreboard(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?InitScoreboard=live',
            success: function (data) {
                // var msg = "";
                if (data.status) {
                    if(data.has_live_game){
                        GetScore(data.gamedraw_id, data.gameset_id);
                        $("#scoreboard-gamedraw").val(data.gamedraw_id);
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
    }

    function GetLiveScore() {

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/scoreboard/controller.php?GetLiveScore=1',
            success: function (data) {
                if (data.status) {
                    livegame = data.live_game;
                    if(data.has_value){
                        Form_Load_Score(data.score);
                        EnableScoreboard();
                    }else{
                        Form_Reset_Score();
                        DisableScoreboard();
                    }
                }
            }
        });
    }

    function LoadData(){
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
                    if(data.has_value){
                        Table_Load_Team($("#tbl-team"), data.teams);
                        Option_Load_Team($("#player-team"), data.teams);
                        Option_Load_Team($("#gamedraw-team-a"), data.teams);
                        Option_Load_Team($("#gamedraw-team-b"), data.teams);
                    }else{
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
                    if(data.has_value){
                        Table_Load_Player($("#tbl-player"), data.players);
                        Option_Load_Player($("#gamedraw-player-a"), data.players);
                        Option_Load_Player($("#gamedraw-player-b"), data.players);
                    }else{
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
                    if(data.has_value){
                        Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraws);
                        Option_Load_GameDraw($("#scoreboard-gamedraw"), data.gamedraws);
                        Table_Load_GameDraw($("#tbl-gamedraw"), data.gamedraws);
                    }else{
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
                    if(data.has_value){
                        Table_Load_GameSet($("#gameset-table"), data.gamesets);
                    }else{
                        $("#gameset-table").html("<tr><td>0 game set. buat dulu!</td></tr>");
                    }
                } else {
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
                    Form_Load_Score(data.score);
                    EnableScoreboard();
                    $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                    $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
                    // TestLoadGames();
                    // LoadData();
                }
            }
        });
    }

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
            $("#team-logo").removeAttr("disabled");
            $("#team-id").val(teamdata.id);
            $("#team-action").val("update");
            $("#team-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#team-modal-image").attr("src", "").addClass("hide");
            $("#team-name").val("").removeAttr("disabled");
            $("#team-desc").val("").removeAttr("disabled");
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

    function Form_Load_GameSet(gamesetdata, modeget) {
        var modalTitle = "";
        if (modeget == 'update') {
            modalTitle += "Update";
            $("#gameset-gamedraw").val(gamesetdata.gamedraw['id']).removeAttr("disabled");
            $("#gameset-setnum").val(gamesetdata.num).removeAttr("disabled");
            $("#gameset-id").val(gamesetdata.id);
            $("#gameset-action").val("update");
            $("#gameset-submit").val("Save");
        } else if (modeget == 'create') {
            modalTitle += "New";
            $("#gameset-gamedraw").val(0).removeAttr("disabled");
            $("#gameset-setnum").val(1).removeAttr("disabled");
            $("#gameset-id").val(0);
            $("#gameset-action").val("create");
            $("#gameset-submit").val("Create");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#gameset-gamedraw").val(gamesetdata.gamedraw['id']).attr("disabled", "disabled");
            $("#gameset-setnum").val(gamesetdata.num).attr("disabled", "disabled");
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
            $("#gamedraw-gamemode-beregu").prop("checked", true);
            $("#gamedraw-gamemode-individu").prop("checked", false);
            $(".gamedraw-player-opt-area-cls").addClass("hide");
            $(".gamedraw-team-opt-area-cls").removeClass("hide");
            $("#gamedraw-team-a").val(0).removeAttr("disabled");
            $("#gamedraw-team-b").val(0).removeAttr("disabled");
            $("#gamedraw-player-a").val(0).removeAttr("disabled");
            $("#gamedraw-player-b").val(0).removeAttr("disabled");
            $(".gamedraw-gamemode-cls").removeAttr("disabled");
            $("#gamedraw-id").val(0);
            $("#gamedraw-action").val("create");
            $("#gamedraw-submit").val("Save");
        } else if (modeget == 'delete') {
            modalTitle += "Delete";
            $("#gamedraw-num").val(gamedrawdata.num).attr("disabled", "disabled");
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
        $("#score-a-gameset-id").val(scoredata.id);
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
        $("#score-b-gamedraw-id").val(scoredata.gamedraw['id']);
        $("#score-b-set-id").val(scoredata.id);
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

    function Form_Reset_Score(){

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
        $("#score-a-setpoints").val(0);
        $("#score-a-desc").val("");


        $("#score-team-b-title").html("Team B");
        $("#score-b-timer").val("0s");
        $("#score-b-gamedraw-id").val(0);
        $("#score-b-set-id").val(0);
        $("#score-b-id").val(0);
        $("#score-b-pt1").val(0);
        $("#score-b-pt2").val(0);
        $("#score-b-pt3").val(0);
        $("#score-b-pt4").val(0);
        $("#score-b-pt5").val(0);
        $("#score-b-pt6").val(0);

        $("#score-b-total").val(0);
        $("#score-b-setpoints").val(0);
        $("#score-b-desc").val("");
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
        var tdText = "<thead class='thead-dark'><tr><th class='w-25'></th><th class='w-50'>Team</th><th class='w-25'></th></tr></thead>";
        tdText += "<tbody>";
        for ( var i = 0; i < teamsdata.length; i++) {
            tdText += "<tr><td><img src='uploads/" + teamsdata[i].logo + "'></td>";
            tdText += "<td><span>" + teamsdata[i].name + "</span></td>";
            tdText += "<td><button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 team-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-teamid='" + teamsdata[i].id + "' class='btn btn-sm btn-outline-danger team-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html( tdText );
    }

    function Table_Load_Player(elemTarget, playersdata) {
        var tdText = "<thead class='thead-dark'><tr><th class='w-50'>Player</th><th class='w-25'>Team</th><th class='w-25'></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < playersdata.length; i++) {
            tdText += "<tr><td><span>" + playersdata[i].name + "</span></td>";
            tdText += "<td><span>" + playersdata[i].team['name'] + "</span></td>";
            tdText += "<td><button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 player-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-playerid='" + playersdata[i].id + "' class='btn btn-sm btn-outline-danger player-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameDraw(elemTarget, gamesdraws_data) {
        var tdText = "<thead class='thead-dark'><tr><th>Game</th><th></th><th>Draw</th><th>Status</th><th></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < gamesdraws_data.length; i++) {
            tdText += "<tr><td><span>" + gamesdraws_data[i].num + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].gamemode['name'] + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].contestant_a['name'] + " vs " + gamesdraws_data[i].contestant_b['name'] + "</span></td>";
            tdText += "<td><span>" + gamesdraws_data[i].gamestatus['name'] + "</span></td>";
            tdText += "<td><button data-gamedrawid='" + gamesdraws_data[i].id + "' class='btn btn-sm btn-outline-warning mr-2 gamedraw-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-gamedrawid='" + gamesdraws_data[i].id + "' class='btn btn-sm btn-outline-danger gamedraw-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
        tdText += "</tbody>";
        elemTarget.html(tdText);
    }

    function Table_Load_GameSet(elemTarget, gamesetsdata) {
        var tdText = "<thead class='thead-dark'><tr><th>Game</th><th>Draw</th><th>Set</th><th>Status</th><th></th><th></th></tr></thead>";
        tdText += "<tbody>";
        for (i = 0; i < gamesetsdata.length; i++) {
            tdText += "<tr><td><span>" + gamesetsdata[i].gamedraw['num'] + "</span></td>";
            tdText += "<td><span>" + gamesetsdata[i].gamedraw['contestant_a']['name'] + " vs " + gamesetsdata[i].gamedraw['contestant_b']['name'] + "</span></td>";
            tdText += "<td><span>" + gamesetsdata[i].num + "</span></td>";
            if(gamesetsdata[i].id==livegame){
                // tdText += "<td><span>" + gamesetsdata[i].gamestatus['name'] + "</span></td>";
                tdText += "<td><button data-gamedrawid='" + gamesetsdata[i].gamedraw['id'] + "' data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-danger gameset-stoplive-btn-cls'><i class='fas fa-stop-circle'></i></button></td>";
            }else{
                tdText += "<td><button data-gamedrawid='" + gamesetsdata[i].gamedraw['id'] + "' data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-success gameset-live-btn-cls'><i class='fas fa-play-circle'></i></button>";
            }
            tdText += "<td><button data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-outline-warning mr-2 gameset-update-btn-cls'><i class='fas fa-edit'></i></button>";
            tdText += "<button data-gamesetid='" + gamesetsdata[i].id + "' class='btn btn-sm btn-outline-danger gameset-delete-btn-cls'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }
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

    function DisableScoreboard(){
        DisableScoreA();
        DisableScoreB();
    }

    function EnableScoreboard(){
        EnableScoreA();
        EnableScoreB();
    }

    function EnableScoreA(){
        $("#form-score-a :input").prop("disabled", false);
        $("#score-a-timer-pause").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
        $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    }

    function EnableScoreB(){
        $("#form-score-b :input").prop("disabled", false);
        $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
        $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    }

    function DisableScoreA(){
        $("#form-score-a :input").prop("disabled", true);
        $("#score-a-timer-play").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
        $("#score-a-timer-pause").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
    }

    function DisableScoreB(){
        $("#form-score-b :input").prop("disabled", true);
        $("#score-b-timer-play").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
        $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
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
        GetGameSetByID(gamesetid, 'update');
    });

    $(document).on('click', '.gameset-delete-btn-cls', function (e) {
        e.preventDefault();
        var gamesetid = $(this).attr("data-gamesetid");
        GetGameSetByID(gamesetid, 'delete');
    });

    $(document).on('click', '.gameset-stoplive-btn-cls', function (e) {
        e.preventDefault();
        /* var gamedrawid = $(this).attr("data-gamedrawid");
        var gamesetid = $(this).attr("data-gamesetid");
        GetGameSetByID(gamesetid, 'delete'); */
        if ($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")) {
            alert("Please pause/stop timer");
        } else {
            $.post("controller.php",{
                    vmix_action: 'stop-live-game',
                    gamesetid: 0
                },
                function(data, status){
                    var result = $.parseJSON(data);
                    if(result.status){
                        Form_Reset_Score();
                        DisableScoreboard();
                        GetLiveScore();
                        GetGameSet();
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
            $.post("controller.php",{
                    vmix_action: 'set-live-game',
                    gamesetid: gameset
                },
                function(data, status){
                    var result = $.parseJSON(data);
                    if(result.status){
                        GetLiveScore();
                        GetGameSet();
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

    $("#scoreboard-render-btn").click(function (e) {
        e.preventDefault();
        if ($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")) {
            alert("Please pause/stop timer");
        } else {
            var gamedraw = $("#scoreboard-gamedraw").val();
            var gameset = $("#scoreboard-gameset").val();
            if (gamedraw != 0 && gameset != 0) {
                GetScore(gamedraw, gameset);
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

    /* $("select.gamedraw-team-cls").on("change", function () {
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
    }); */

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

                    GetLiveScore();
                    LoadData();
                    /* GetTeam();
                    GetPlayer();
                    GetGameDraw();
                    GetGameSet(); */
                    if (result.action == 'delete') {
                        $("#form-team-modal").modal("hide");
                        GetLiveScore();
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

                /* GetPlayer();
                GetGameDraw();
                GetGameSet(); */
                GetLiveScore();
                LoadData();
                if (data.action == 'delete') {
                    $("#form-player-modal").modal("hide");
                }
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
                /* GetGameDraw();
                GetGameSet(); */
                GetLiveScore();
                LoadData();
                if (response.action == 'delete') {
                    $("#form-gamedraw-modal").modal("hide");
                    GetLiveScore();
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
                /* GetGameSet(); */
                GetLiveScore();
                LoadData();
                if (data.action == 'delete') {
                    $("#form-gameset-modal").modal("hide");
                    GetLiveScore();
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
        if(counterA<0) counterA = 0;
        $("#score-a-timer").val(counterA + "s");
        $.post("controller.php",{
            score_timer_action: 'update-timer-a',
            score_a_id: $("#score-a-id").val(),
            timer_a: counterA
        },
        function(data, status){
            // console.log(data);
        });
        counterA--;
    }, interval);
}
function playB() {
    if (timerB !== null) return;
    timerB = setInterval(function () {
        if(counterB<0) counterB = 0;
        $("#score-b-timer").val(counterB + "s");
        $.post("controller.php",{
                score_timer_action: 'update-timer-b',
                score_b_id: $("#score-b-id").val(),
                timer_b: counterB
            },
            function(data, status){
                // console.log(data);
            }
        );
        counterB--;
    }, interval);
}
$("#score-a-timer-pause").click(function (e) {
    e.preventDefault();
    $("#score-a-submit").removeAttr("disabled");
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
    $("#score-a-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    pauseA();
});
$("#score-a-timer-play").click(function (e) {
    e.preventDefault();
    $("#score-a-submit").attr("disabled","disabled");
    counterA = parseInt(($("#score-a-timer").val()).replace("s", ""));
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-a-timer-pause").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
    playA();
});
$("#score-b-timer-pause").click(function (e) {
    e.preventDefault();
    $("#score-b-submit").removeAttr("disabled");
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled");
    $("#score-b-timer-play").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    pauseB();
});
$("#score-b-timer-play").click(function (e) {
    e.preventDefault();
    $("#score-b-submit").attr("disabled","disabled");
    $(this).removeClass("btn-success").addClass("btn-outline-dark").attr("disabled", "disabled").addClass("play-on-cls");
    $("#score-b-timer-pause").removeClass("btn-outline-dark").addClass("btn-success").removeAttr("disabled");
    counterB = parseInt(($("#score-b-timer").val()).replace("s", ""));
    playB();
});