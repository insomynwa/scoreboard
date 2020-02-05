$(document).ready(function() {
    var request;

    var App = {
        init: function() {
            var urls = [
                "/scoreboard/controller.php?config_get=init",
                "/scoreboard/controller.php?config_get=setup"
            ];
            var actNames = ["CONFIG_GET", "CONFIG_SET"];
            App.ajaxGetReq(urls, actNames);
            Team.init();
            Player.init();
            GameDraw.init();
            GameSet.init();
            FormScoreboard.init();
            ScoreboardStyle.init();
        },
        ajaxGetReq: function(urls, actNames) {
            if (urls.length > 0 && actNames.length > 0) {
                var act = actNames.pop();
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: urls.pop(),
                    success: function(data) {
                        if (data.status) {
                            if (act == "CONFIG_GET") {
                                FormScoreboard.loadForm(data.scoreboard_form);

                                GameStatus.loadOption(
                                    data.game_status["option"]
                                );
                                GameMode.loadRadio(data.game_mode["radio"]);
                                BowStyle.loadRadio(data.bowstyle["radio"]);

                                var scoreboard_styles = data.scoreboard_styles,
                                bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                style_name = scoreboard_styles.info['style_name'];
                                ScoreboardStyle.setBowstyleOption(scoreboard_styles["bowstyle"]['option']);
                                ScoreboardStyle.setStyleOption(scoreboard_styles["option"]);
                                ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                ScoreboardStyle.loadPreview(
                                    scoreboard_styles["preview"]
                                );
                                ScoreboardStyle.configBtn(
                                    scoreboard_styles["config"]
                                );
                                ScoreboardStyle.live_style = data.live_style;

                                Team.loadTable(data.team["table"]);
                                Team.loadOption(data.team["option"]);
                                Player.loadTable(data.player["table"]);
                                Player.loadOption(data.player["option"]);
                                GameDraw.loadOption(data.gamedraw["option"]);
                                GameDraw.loadTable(data.gamedraw["table"]);
                                GameSet.loadTable(data.gameset["table"]);
                            } else if (act == "SCORE_UPDATE") {
                                var scoreboard_styles = data.scoreboard_styles;
                                ScoreboardStyle.loadPreview(
                                    scoreboard_styles["preview"]
                                );
                            }
                        } else {
                        }
                        App.ajaxGetReq(urls, actNames);
                    }
                });
            }
        }
    };

    var Helper = {
        parseNum: function(str) {
            str = str.replace('*', '');
            if (str == "" || isNaN(str)) {
                return 0;
            }
            return parseInt(str);
        },
        setHide: function(element, toggleClass) {
            if (toggleClass == "hide") {
                if (!element.hasClass("hide")) {
                    element.addClass("hide");
                }
            } else {
                if (element.hasClass("hide")) {
                    element.removeClass("hide");
                }
            }
        }
    };

    var FormScoreboard = {
        init: function() {
            FormScoreboard.bindEvents();
        },
        bindEvents: function() {
            $(document)
                .on("submit", "#form-scoreboard-a", this.submitForm)
                .on("submit", "#form-scoreboard-b", this.submitForm)
                .on("input", "#score-a-setpoints", this.calculateGamePointsA)
                .on("input", "#score-b-setpoints", this.calculateGamePointsB)
                .on("input", ".score-a-input-cls", this.calculateScoreA)
                .on("input", ".score-b-input-cls", this.calculateScoreB);
        },
        calculateScoreA: function() {
            FormScoreboard.setScore("a");
        },
        calculateScoreB: function() {
            FormScoreboard.setScore("b");
        },
        setScore: function(owner) {
            var el_setscores = $("#score-" + owner + "-setscores");
            var el_gamescores = $("#score-" + owner + "-gamescores");
            var el_score1 = $("#score-" + owner + "-score1");
            var el_score2 = $("#score-" + owner + "-score2");
            var el_score3 = $("#score-" + owner + "-score3");
            var el_score4 = $("#score-" + owner + "-score4");
            var el_score5 = $("#score-" + owner + "-score5");
            var el_score6 = $("#score-" + owner + "-score6");

            var prev_total = Helper.parseNum(el_setscores.val());
            var prev_game_total_points = Helper.parseNum(el_gamescores.val());
            var total_point =
                Helper.parseNum(el_score1.val()) +
                Helper.parseNum(el_score2.val()) +
                Helper.parseNum(el_score3.val()) +
                Helper.parseNum(el_score4.val()) +
                Helper.parseNum(el_score5.val()) +
                Helper.parseNum(el_score6.val());
            el_setscores.val(total_point);
            var game_total_points =
                prev_game_total_points + (total_point - prev_total);
            el_gamescores.val(game_total_points);
        },
        loadForm: function(scoreboard_form) {
            var form_data = scoreboard_form.form , str = '';
            if( form_data.length != 0 ){
                var data = form_data.data,
                    cfg = form_data.config,
                    sets = data.sets,
                    cont_a = data.contestants[0],
                    cont_b = data.contestants[1],
                    cont_a_score1 = cont_a.score_1 == 0 ? '' : cont_a.score_1;
                    cont_a_score2 = cont_a.score_2 == 0 ? '' : cont_a.score_2;
                    cont_a_score3 = cont_a.score_3 == 0 ? '' : cont_a.score_3;
                    cont_a_score4 = cont_a.score_4 == 0 ? '' : cont_a.score_4;
                    cont_a_score5 = cont_a.score_5 == 0 ? '' : cont_a.score_5;
                    cont_a_score6 = cont_a.score_6 == 0 ? '' : cont_a.score_6;

                    cont_b_score1 = cont_b.score_1 == 0 ? '' : cont_b.score_1;
                    cont_b_score2 = cont_b.score_2 == 0 ? '' : cont_b.score_2;
                    cont_b_score3 = cont_b.score_3 == 0 ? '' : cont_b.score_3;
                    cont_b_score4 = cont_b.score_4 == 0 ? '' : cont_b.score_4;
                    cont_b_score5 = cont_b.score_5 == 0 ? '' : cont_b.score_5;
                    cont_b_score6 = cont_b.score_6 == 0 ? '' : cont_b.score_6;

                    set_str = 'Set X',
                    set_str_helper = '';

                if( data.bowstyle_id == 1 ) { // Recurve
                    set_str = `Set ${sets.curr_set}`;
                }else if( data.bowstyle_id == 2 ) { // Compound
                    set_str = `Set ${sets.curr_set} of ${sets.end_set}`;
                }
                if( cfg.team_vc == '' ) {
                    set_str_helper = 'hide';
                }
                str = `<form id="form-scoreboard-a" action="controller.php" method="post" class="form form-scoreboard">
                <table class="table table-sm">
                    <thead class="">
                        <tr class="">
                            <th class="form-scoreboard-logo text-light small ${cfg.logo_vc}"></td>
                            <th class="td-w form-scoreboard-team ${cfg.team_vc}"><span>${set_str}</span></td>
                            <th class="td-w form-scoreboard-player ${cfg.player_vc}"><span class="${set_str_helper}">${set_str}</span></td>
                            <th class="form-scoreboard-timer ${cfg.timer_vc}">${cfg.timer_label}</td>
                            <th class="form-scoreboard-score1 score-field ${cfg.score1_vc}">${cfg.score1_label}</td>
                            <th class="form-scoreboard-score2 score-field ${cfg.score2_vc}">${cfg.score2_label}</td>
                            <th class="form-scoreboard-score3 score-field ${cfg.score3_vc}">${cfg.score3_label}</td>
                            <th class="form-scoreboard-score4 score-field ${cfg.score4_vc}">${cfg.score4_label}</td>
                            <th class="form-scoreboard-score5 score-field ${cfg.score5_vc}">${cfg.score5_label}</td>
                            <th class="form-scoreboard-score6 score-field ${cfg.score6_vc}">${cfg.score6_label}</td>
                            <th class="form-scoreboard-setpoint ${cfg.setpoint_vc}">${cfg.setpoint_label}</td>
                            <th class="form-scoreboard-setscore ${cfg.setscore_vc}">${cfg.setscore_label}</td>
                            <th class="form-scoreboard-gamepoint ${cfg.gamepoint_vc}">${cfg.gamepoint_label}</td>
                            <th class="form-scoreboard-gamescore ${cfg.gamescore_vc}">${cfg.gamescore_label}</td>
                            <th class="td-w form-scoreboard-desc ${cfg.description_vc}">${cfg.description_label}</td>
                            <th class="form-scoreboard-btn"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="form-scoreboard-logo text-light small ${cfg.logo_vc}"><div><img src="${cont_a.logo}"></div></td>
                            <td class="form-scoreboard-team ${cfg.team_vc}"><div><span>${cont_a.team}</span></div></td>
                            <td class="form-scoreboard-player ${cfg.player_vc}"><div><span>${cont_a.player}</span></div></td>
                            <td class="form-scoreboard-timer ${cfg.timer_vc}"><input type="text" readonly class="form-control font-italic " name="score_a_timer" id="score-a-timer" value="${cont_a.score_timer}">s</td>
                            <td class="form-scoreboard-score1 score-field ${cfg.score1_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score1" id="score-a-score1" value="${cont_a_score1}"></td>
                            <td class="form-scoreboard-score2 score-field ${cfg.score2_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score2" id="score-a-score2" value="${cont_a_score2}"></td>
                            <td class="form-scoreboard-score3 score-field ${cfg.score3_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score3" id="score-a-score3" value="${cont_a_score3}"></td>
                            <td class="form-scoreboard-score4 score-field ${cfg.score4_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score4" id="score-a-score4" value="${cont_a_score4}"></td>
                            <td class="form-scoreboard-score5 score-field ${cfg.score5_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score5" id="score-a-score5" value="${cont_a_score5}"></td>
                            <td class="form-scoreboard-score6 score-field ${cfg.score6_vc}"><input type="text" class="form-control score-a-input-cls" name="score_a_score6" id="score-a-score6" value="${cont_a_score6}"></td>
                            <td class="form-scoreboard-setpoint ${cfg.setpoint_vc}"><input type="text" class="form-control " name="score_a_setpoints" id="score-a-setpoints" data-setpoints="${cont_a.set_points}" value="${cont_a.set_points}"></td>
                            <td class="form-scoreboard-setscore ${cfg.setscore_vc}"><input type="text" class="form-control " readonly name="score_a_setscores" id="score-a-setscores" value="${cont_a.set_scores}"></td>
                            <td class="form-scoreboard-gamepoint ${cfg.gamepoint_vc}"><input type="text" class="form-control " readonly name="score_a_gamepoints" id="score-a-gamepoints" data-gamepoints="${cont_a.game_points}" value="${cont_a.game_points}"></td>
                            <td class="form-scoreboard-gamescore ${cfg.gamescore_vc}"><input type="text" class="form-control " readonly name="score_a_gamescores" id="score-a-gamescores" value="${cont_a.game_scores}"></td>
                            <td class="form-scoreboard-desc ${cfg.description_vc}"><input type="text" class="form-control " name="score_a_desc" id="score-a-desc" value="${cont_a.desc}"></td>
                            <td class="">
                                <input type="hidden" name="score_a_gamedraw_id" id="score-a-gamedraw-id" value="${data.gamedraw_id}">
                                <input type="hidden" name="score_a_gameset_id" id="score-a-gameset-id" value="${data.gameset_id}">
                                <input type="hidden" name="score_a_id" id="score-a-id" value="${cont_a.score_id}">
                                <input type="hidden" name="score_action" value="update-a">
                                <input type="submit" value="UPDATE" id="score-a-submit" class="btn btn-primary no-boradius form-scoreboard-submit-btn">
                            </td>
                        </tr>
                    </tbody>
                </table>
                </form>
                <form id="form-scoreboard-b" action="controller.php" method="post" class="form form-scoreboard">
                <table class="table table-sm">
                    <thead class="">
                        <tr class="">
                            <th class="form-scoreboard-logo text-light small ${cfg.logo_vc}"></td>
                            <th class="td-w form-scoreboard-team ${cfg.team_vc}"></td>
                            <th class="td-w form-scoreboard-player ${cfg.player_vc}"></td>
                            <th class="form-scoreboard-timer ${cfg.timer_vc}">${cfg.timer_label}</td>
                            <th class="form-scoreboard-score1 score-field ${cfg.score1_vc}">${cfg.score1_label}</td>
                            <th class="form-scoreboard-score2 score-field ${cfg.score2_vc}">${cfg.score2_label}</td>
                            <th class="form-scoreboard-score3 score-field ${cfg.score3_vc}">${cfg.score3_label}</td>
                            <th class="form-scoreboard-score4 score-field ${cfg.score4_vc}">${cfg.score4_label}</td>
                            <th class="form-scoreboard-score5 score-field ${cfg.score5_vc}">${cfg.score5_label}</td>
                            <th class="form-scoreboard-score6 score-field ${cfg.score6_vc}">${cfg.score6_label}</td>
                            <th class="form-scoreboard-setpoint ${cfg.setpoint_vc}">${cfg.setpoint_label}</td>
                            <th class="form-scoreboard-setscore ${cfg.setscore_vc}">${cfg.setscore_label}</td>
                            <th class="form-scoreboard-gamepoint ${cfg.gamepoint_vc}">${cfg.gamepoint_label}</td>
                            <th class="form-scoreboard-gamescore ${cfg.gamescore_vc}">${cfg.gamescore_label}</td>
                            <th class="td-w form-scoreboard-desc ${cfg.description_vc}">${cfg.description_label}</td>
                            <th class="form-scoreboard-btn"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="form-scoreboard-logo text-light small ${cfg.logo_vc}"><div><img src="${cont_b.logo}"></div></td>
                            <td class="form-scoreboard-team ${cfg.team_vc}"><div><span>${cont_b.team}</span></div></td>
                            <td class="form-scoreboard-player ${cfg.player_vc}"><div><span>${cont_b.player}</span></div></td>
                            <td class="form-scoreboard-timer ${cfg.timer_vc}"><input type="text" readonly class="form-control font-italic " name="score_b_timer" id="score-b-timer" value="${cont_b.score_timer}">s</td>
                            <td class="form-scoreboard-score1 ${cfg.score1_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score1" id="score-b-score1" value="${cont_b_score1}"></td>
                            <td class="form-scoreboard-score2 ${cfg.score2_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score2" id="score-b-score2" value="${cont_b_score2}"></td>
                            <td class="form-scoreboard-score3 ${cfg.score3_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score3" id="score-b-score3" value="${cont_b_score3}"></td>
                            <td class="form-scoreboard-score4 ${cfg.score4_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score4" id="score-b-score4" value="${cont_b_score4}"></td>
                            <td class="form-scoreboard-score5 ${cfg.score5_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score5" id="score-b-score5" value="${cont_b_score5}"></td>
                            <td class="form-scoreboard-score6 ${cfg.score6_vc}"><input type="text" class="form-control score-b-input-cls" name="score_b_score6" id="score-b-score6" value="${cont_b_score6}"></td>
                            <td class="form-scoreboard-setpoint ${cfg.setpoint_vc}"><input type="text" class="form-control " name="score_b_setpoints" id="score-b-setpoints" data-setpoints="${cont_b.set_points}" value="${cont_b.set_points}"></td>
                            <td class="form-scoreboard-setscore ${cfg.setscore_vc}"><input type="text" class="form-control " readonly name="score_b_setscores" id="score-b-setscores" value="${cont_b.set_scores}"></td>
                            <td class="form-scoreboard-gamepoint ${cfg.gamepoint_vc}"><input type="text" class="form-control " readonly name="score_b_gamepoints" id="score-b-gamepoints" data-gamepoints="${cont_b.game_points}" value="${cont_b.game_points}"></td>
                            <td class="form-scoreboard-gamescore ${cfg.gamescore_vc}"><input type="text" class="form-control " readonly name="score_b_gamescores" id="score-b-gamescores" value="${cont_b.game_scores}"></td>
                            <td class="form-scoreboard-desc ${cfg.description_vc}"><input type="text" class="form-control " name="score_b_desc" id="score-b-desc" value="${cont_b.desc}"></td>
                            <td class="">
                                <input type="hidden" name="score_b_gamedraw_id" id="score-b-gamedraw-id" value="${data.gamedraw_id}">
                                <input type="hidden" name="score_b_gameset_id" id="score-b-gameset-id" value="${data.gameset_id}">
                                <input type="hidden" name="score_b_id" id="score-b-id" value="${cont_b.score_id}">
                                <input type="hidden" name="score_action" value="update-b">
                                <input type="submit" value="UPDATE" id="score-b-submit" class="btn btn-primary no-boradius form-scoreboard-submit-btn">
                            </td>
                        </tr>
                    </tbody>
                </table>
                </form>`;
            }else{
                str = `<h4 class="text-gray-4 text-center font-weight-light">Start Game</h4>`;
            }


            $("#form-scoreboard-wrapper").html(str);
            // $("#form-scoreboard-wrapper").html(scoreboard_form);
        },
        calculateGamePointsA: function() {
            FormScoreboard.setGamePoints(
                $("#score-a-setpoints"),
                $("#score-a-gamepoints")
            );
        },
        calculateGamePointsB: function() {
            FormScoreboard.setGamePoints(
                $("#score-b-setpoints"),
                $("#score-b-gamepoints")
            );
        },
        setGamePoints: function(sender, elementTarget) {
            var prev_setpoints = Helper.parseNum(sender.attr("data-setpoints"));
            var prev_gamepoints = Helper.parseNum(
                elementTarget.attr("data-gamepoints")
            );
            var result =
                prev_gamepoints -
                prev_setpoints +
                Helper.parseNum(sender.val());
            elementTarget.val(result);
        },
        resetForm: function() {
            $("#score-a-logo").attr("src", "uploads/no-image.png");
            $("#score-team-a-title").html("Team A");
            $("#score-a-timer").val("0s");
            $("#score-a-gamedraw-id").val(0);
            $("#score-a-gameset-id").val(0);
            $("#score-a-id").val(0);
            $("#score-a-score1").val(0);
            $("#score-a-score2").val(0);
            $("#score-a-score3").val(0);
            $("#score-a-score4").val(0);
            $("#score-a-score5").val(0);
            $("#score-a-score6").val(0);

            $("#score-a-setscores").val(0);
            $("#score-a-gamescores").val(0);
            $("#score-a-setpoints").val(0);
            $("#score-a-gamepoints").val(0);
            $("#score-a-desc").val("");

            $("#score-b-logo").attr("src", "uploads/no-image.png");
            $("#score-team-b-title").html("Team B");
            $("#score-b-timer").val("0s");
            $("#score-b-gamedraw-id").val(0);
            $("#score-b-gameset-id").val(0);
            $("#score-b-id").val(0);
            $("#score-b-score1").val(0);
            $("#score-b-score2").val(0);
            $("#score-b-score3").val(0);
            $("#score-b-score4").val(0);
            $("#score-b-score5").val(0);
            $("#score-b-score6").val(0);

            $("#score-b-setscores").val(0);
            $("#score-b-gamescores").val(0);
            $("#score-b-setpoints").val(0);
            $("#score-b-gamepoints").val(0);
            $("#score-b-desc").val("");
        },
        submitForm: function(e) {
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
                data: serializedData
            });

            request.done(function(response, textStatus, jqXHR) {
                var data = $.parseJSON(response);
                if (data.status) {

                    var scoreboard_styles = data.scoreboard_styles;
                    ScoreboardStyle.loadPreview(
                        scoreboard_styles["preview"]
                    );
                }
            });
        }
    };

    var GameStatus = {
        loadOption: function(opt) {
            var str = ``;
            if(opt.length != 0){
                for(i=0; i<opt.length; i++){
                    str += `<option value="${opt[i]['id']}">${opt[i]['name']}</option>`;
                }
            }
            $("#gameset-status").html(str);
            // $("#gameset-status").html(gamestatus_options);
        }
    };

    var GameMode = {
        loadRadio: function(radios) {
            var str = ``;
            if(radios.length != 0){
                for(i=0; i<radios.length; i++){
                    str += `<div class="form-check form-check-inline">
                    <input type="radio" name="gamedraw_gamemode" class="gamedraw-gamemode-cls form-check-input" value="${radios[i]['id']}" id="gamedraw-gamemode-${(radios[i]['name']).toLowerCase()}"><label for="gamedraw-gamemode-${(radios[i]['name']).toLowerCase()}" class="form-check-label text-gray-4">${radios[i]['name']}</label>
                    </div>`;
                }
            }
            $("#gamedraw-radio-area").html(str);
            // $("#gamedraw-radio-area").html(gamemode_radios);
        }
    };

    var BowStyle = {
        loadRadio: function(radios) {
            var str = ``;
            if(radios.length != 0){
                for(i=0; i<radios.length; i++){
                    str += `<div class="form-check form-check-inline">
                    <input type="radio" name="gamedraw_bowstyle" class="gamedraw-bowstyle-cls form-check-input" value="${radios[i]['id']}" id="gamedraw-bowstyle-${(radios[i]['name']).toLowerCase()}"><label for="gamedraw-bowstyle-${(radios[i]['name']).toLowerCase()}" class="form-check-label text-gray-4">${radios[i]['name']}</label>
                    </div>`;
                }
            }
            $("#gamedraw-radio-bowstyle-area").html(str);
            // $("#gamedraw-radio-bowstyle-area").html(bowstyle_radios);
        }
    };

    var ScoreboardStyle = {
        styleNameInput: $("#scoreboard-style-style-name"),
        styleNameInputWrapper: $("#scoreboard-style-style-name-wrapper"),
        activateBtn: $("#scoreboard-style-activate"),
        deactivateBtn: $("#scoreboard-style-deactivate"),

        createBtn: $("#scoreboard-style-btn-create"),
        editBtn: $("#scoreboard-style-btn-edit"),
        deleteBtn: $("#scoreboard-style-btn-delete"),

        saveBtn: $("#scoreboard-style-btn-save"),
        cancelBtn: $("#scoreboard-style-cancel"),

        formStyle: $("#form-scoreboard-style"),
        formStyleMode: $("#form-scoreboard-style-mode"),
        formStyleAction: $("#scoreboard-style-action"),

        visibilityContainer: $("#form-scoreboard-style-visibility"),
        markCBox: $("#ssv-collective-cb"),
        visibilityTable: $("#table-style-visibility"),

        previewWrapper: $("#scoreboard-style-preview"),
        previewTableID: "#scoreboard-style-preview table",
        previewTable: $("#scoreboard-style-preview table"),

        bowstyleSelectID: "#scoreboard-style-bowstyle-select",
        bowstyleSelect: $("#scoreboard-style-bowstyle-select"),
        bowstyleInfo: $("#scoreboard-style-active-bowstyle-info"),
        styleSelectID: "#scoreboard-style-style-select",
        styleSelect: $("#scoreboard-style-style-select"),
        styleInfo: $("#scoreboard-style-active-bowstyle-style-info"),
        styleSelectWrapper: $("#scoreboard-style-style-select-wrapper"),

        fieldCBoxClass: "input.ssv-cb",

        selectedBowstyle: 0,
        selectedStyle: 0,
        live_style: 0,

        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            this.formStyle.on("submit", this.submitForm);
            this.markCBox.change(this.toggleAllColumn);
            this.activateBtn.click(this.activateStyle);
            this.deactivateBtn.click(this.deactivateStyle);
            this.createBtn.click(this.configCreateForm);
            this.editBtn.click(this.setupUpdateForm);
            this.deleteBtn.click(this.setupDeleteForm);
            this.cancelBtn.click(this.cancelFormSetup);
            $(document)
                .on("change", this.fieldCBoxClass, this.toggleColumn)
                .on("change", this.bowstyleSelectID, this.loadStyleOption)
                .on("change", this.styleSelectID, this.setupPreview);
        },
        configBtn: function(cfg) {
            Helper.setHide(
                ScoreboardStyle.activateBtn,
                cfg.activate_btn
            );
            Helper.setHide(
                ScoreboardStyle.deactivateBtn,
                cfg.deactivate_btn
            );
            Helper.setHide(
                ScoreboardStyle.saveBtn,
                cfg.save_btn
            );
            Helper.setHide(
                ScoreboardStyle.cancelBtn,
                cfg.cancel_btn
            );
            Helper.setHide(
                ScoreboardStyle.createBtn,
                cfg.new_btn
            );
            Helper.setHide(
                ScoreboardStyle.editBtn,
                cfg.edit_btn
            );
            Helper.setHide(
                ScoreboardStyle.deleteBtn,
                cfg.delete_btn
            );
        },
        toggleColumn: function() {
            var cls = $(this).attr("data-class");
            var status = $(this).prop("checked");
            if (status) {
                if( cls == 'scoreboard-style-preview-team') {
                    Helper.setHide($("#preview-set-info"),'hide');
                }
                $("." + cls).removeClass("hide");
                $(this).val(true);
            } else {
                if( cls == 'scoreboard-style-preview-team') {
                    Helper.setHide($("#preview-set-info"),'');
                }
                $("." + cls).addClass("hide");
                $(this).val(false);
                ScoreboardStyle.markCBox.prop("checked", false);
            }
        },
        toggleAllColumn: function() {
            var target = $(ScoreboardStyle.fieldCBoxClass);
            var status = $(this).prop("checked");
            if (status) {
                target.prop("checked", true).val(true);
                // $(ScoreboardStyle.previewTableID + " td").removeClass('hide');
                Helper.setHide($(ScoreboardStyle.previewTableID + " td"), "");
            } else {
                target.prop("checked", false).val(false);
                // $(ScoreboardStyle.previewTableID + " td").addClass("hide");
                Helper.setHide(
                    $(ScoreboardStyle.previewTableID + " td"),
                    "hide"
                );
            }
        },
        toggleBtnVisibility: function(toggleClass) {
            Helper.setHide(ScoreboardStyle.createBtn, toggleClass);
            Helper.setHide(ScoreboardStyle.editBtn, toggleClass);
            Helper.setHide(ScoreboardStyle.deleteBtn, toggleClass);
            Helper.setHide(ScoreboardStyle.activateBtn, toggleClass);
            Helper.setHide(ScoreboardStyle.styleSelectWrapper, toggleClass);

            var contra_class = "";
            if (toggleClass != "hide") {
                contra_class = "hide";
            }
            Helper.setHide(ScoreboardStyle.saveBtn, contra_class);
            Helper.setHide(ScoreboardStyle.cancelBtn, contra_class);
            Helper.setHide(ScoreboardStyle.styleNameInputWrapper, contra_class);
        },
        loadPreview: function(preview) {
            var style_config = preview.style_config,
                score_data = preview.score_data,
                sets = score_data.sets,
                cont_a = score_data.contestants[0],
                cont_b = score_data.contestants[1],
                set_str = 'Set X',
                set_str_helper = '';

            if( score_data.bowstyle_id == 1 ) { // Recurve
                set_str = `Set ${sets.curr_set}`;
            }else if( score_data.bowstyle_id == 2 ) { // Compound
                set_str = `Set ${sets.curr_set} of ${sets.end_set}`;
            }
            // if( ( cont_a.player == '' | cont_b.player == '' ) && score_data.gamemode_id == 1 ) {
            //     style_config.player_vc = 'hide';
            // }
            // if( ( cont_a.team == '' | cont_b.team == '' ) && score_data.gamemode_id == 2 ) {
            //     style_config.team_vc = 'hide';
            // }
            // if( cont_a.team['visibility_class'] == '' && cont_b.team['visibility_class'] == '' ) {
            //     style_config.team_vc = 'hide';
            // }
            if( style_config.team_vc == '' ) {
                set_str_helper = 'hide';
            }

            var str = `<thead>
            <tr>
                <td class="scoreboard-style-preview-logo text-light small ${style_config.logo_vc}"></td>
                <td class="td-w scoreboard-style-preview-team ${style_config.team_vc}"><span>${set_str}</span></td>
                <td class="td-w scoreboard-style-preview-player ${style_config.player_vc}"><span id="preview-set-info" class="${set_str_helper}">${set_str}</span></td>
                <td class="scoreboard-style-preview-timer ${style_config.timer_vc}"></td>
                <td class="scoreboard-style-preview-score1 ${style_config.score1_vc}"></td>
                <td class="scoreboard-style-preview-score2 ${style_config.score2_vc}"></td>
                <td class="scoreboard-style-preview-score3 ${style_config.score3_vc}"></td>
                <td class="scoreboard-style-preview-score4 ${style_config.score4_vc}"></td>
                <td class="scoreboard-style-preview-score5 ${style_config.score5_vc}"></td>
                <td class="scoreboard-style-preview-score6 ${style_config.score6_vc}"></td>
                <td class="scoreboard-style-preview-setpoint ${style_config.setpoint_vc}"></td>
                <td class="scoreboard-style-preview-setscore ${style_config.setscore_vc}"></td>
                <td class="scoreboard-style-preview-gamepoint ${style_config.gamepoint_vc}"><span>Set pts</span></td>
                <td class="scoreboard-style-preview-gamescore ${style_config.gamescore_vc}>"><span>Total</span></td>
                <td class="td-w scoreboard-style-preview-desc ${style_config.description_vc}"></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-toggle="tooltip" title="logo" class="scoreboard-style-preview-logo text-light small ${style_config.logo_vc}"><div><img src="${cont_a.logo}" width="36"></td>
                <td data-toggle="tooltip" title="team" class="scoreboard-style-preview-team ${style_config.team_vc}"><div><span>${cont_a.team}</span></div></td>
                <td data-toggle="tooltip" title="player" class="scoreboard-style-preview-player ${style_config.player_vc}"><div><span>${cont_a.player}</span></div></td>
                <td data-toggle="tooltip" title="timer" class="scoreboard-style-preview-timer ${style_config.timer_vc}"><div><span>${cont_a.score_timer}s</span></div></td>
                <td data-toggle="tooltip" title="score 1" class="scoreboard-style-preview-score1 ${style_config.score1_vc}"><div><span>${cont_a.score_1}</span></div></td>
                <td data-toggle="tooltip" title="score 2" class="scoreboard-style-preview-score2 ${style_config.score2_vc}"><div><span>${cont_a.score_2}</span></div></td>
                <td data-toggle="tooltip" title="score 3" class="scoreboard-style-preview-score3 ${style_config.score3_vc}"><div><span>${cont_a.score_3}</span></div></td>
                <td data-toggle="tooltip" title="score 4" class="scoreboard-style-preview-score4 ${style_config.score4_vc}"><div><span>${cont_a.score_4}</span></div></td>
                <td data-toggle="tooltip" title="score 5" class="scoreboard-style-preview-score5 ${style_config.score5_vc}"><div><span>${cont_a.score_5}</span></div></td>
                <td data-toggle="tooltip" title="score 6" class="scoreboard-style-preview-score6 ${style_config.score6_vc}"><div><span>${cont_a.score_6}</span></div></td>
                <td data-toggle="tooltip" title="set point" class="scoreboard-style-preview-setpoint ${style_config.setpoint_vc}"><div><span>${cont_a.set_points}</span></div></td>
                <td data-toggle="tooltip" title="set score" class="scoreboard-style-preview-setscore ${style_config.setscore_vc}"><div><span>${cont_a.set_scores}</span></div></td>
                <td data-toggle="tooltip" title="game point" class="scoreboard-style-preview-gamepoint ${style_config.gamepoint_vc}"><div><span>${cont_a.game_points}</span></div></td>
                <td data-toggle="tooltip" title="game score" class="scoreboard-style-preview-gamescore ${style_config.gamescore_vc}>"><div><span>${cont_a.game_scores}</span></div></td>
                <td data-toggle="tooltip" title="description" class="scoreboard-style-preview-desc ${style_config.description_vc}"><div><span>${cont_a.desc}</span></div></td>
            </tr>
            <tr>
                <td data-toggle="tooltip" title="logo" class="scoreboard-style-preview-logo text-light small ${style_config.logo_vc}"><div><img src="${cont_b.logo}" width="36"></div></td>
                <td data-toggle="tooltip" title="team" class="scoreboard-style-preview-team ${style_config.team_vc}"><div><span>${cont_b.team}</span></div></td>
                <td data-toggle="tooltip" title="player" class="scoreboard-style-preview-player ${style_config.player_vc}"><div><span>${cont_b.player}</span></div></td>
                <td data-toggle="tooltip" title="timer" class="scoreboard-style-preview-timer ${style_config.timer_vc}"><div><span>${cont_b.score_timer}s</span></div></td>
                <td data-toggle="tooltip" title="score 1" class="scoreboard-style-preview-score1 ${style_config.score1_vc}"><div><span>${cont_b.score_1}</span></div></td>
                <td data-toggle="tooltip" title="score 2" class="scoreboard-style-preview-score2 ${style_config.score2_vc}"><div><span>${cont_b.score_2}</span></div></td>
                <td data-toggle="tooltip" title="score 3" class="scoreboard-style-preview-score3 ${style_config.score3_vc}"><div><span>${cont_b.score_3}</span></div></td>
                <td data-toggle="tooltip" title="score 4" class="scoreboard-style-preview-score4 ${style_config.score4_vc}"><div><span>${cont_b.score_4}</span></div></td>
                <td data-toggle="tooltip" title="score 5" class="scoreboard-style-preview-score5 ${style_config.score5_vc}"><div><span>${cont_b.score_5}</span></div></td>
                <td data-toggle="tooltip" title="score 6" class="scoreboard-style-preview-score6 ${style_config.score6_vc}"><div><span>${cont_b.score_6}</span></div></td>
                <td data-toggle="tooltip" title="set point" class="scoreboard-style-preview-setpoint ${style_config.setpoint_vc}"><div><span>${cont_b.set_points}</span></div></td>
                <td data-toggle="tooltip" title="set score" class="scoreboard-style-preview-setscore ${style_config.setscore_vc}"><div><span>${cont_b.set_scores}</span></div></td>
                <td data-toggle="tooltip" title="game point" class="scoreboard-style-preview-gamepoint ${style_config.gamepoint_vc}"><div><span>${cont_b.game_points}</span></div></td>
                <td data-toggle="tooltip" title="game score" class="scoreboard-style-preview-gamescore ${style_config.gamescore_vc}>"><div><span>${cont_b.game_scores}</span></div></td>
                <td data-toggle="tooltip" title="description" class="scoreboard-style-preview-desc ${style_config.description_vc}"><div><span>${cont_b.desc}</span></div></td>
            </tr>
            </tbody>`;
            ScoreboardStyle.previewTable.html(str);
            if (str == "") {
                Helper.setHide(ScoreboardStyle.previewWrapper, "hide");
            } else {
                Helper.setHide(ScoreboardStyle.previewWrapper, "");
            }
            // ScoreboardStyle.previewTable.html(preview);
            // if (preview == "") {
            //     Helper.setHide(ScoreboardStyle.previewWrapper, "hide");
            // } else {
            //     Helper.setHide(ScoreboardStyle.previewWrapper, "");
            // }
        },
        setBowstyleOption: function(options){
            var str = '';
            if(options.length > 0){
                for(i=0; i<options.length; i++){
                    var item = `<option value="${options[i]['id']}" ${options[i]['selected']}>${options[i]['name']}</option>`;
                    str += item;
                }
            }
            ScoreboardStyle.bowstyleSelect.html(str);
        },
        setStyleOption: function(options){
            var str = '';
            if(options.length > 0){
                for(i=0; i<options.length; i++){
                    var item = `<option value="${options[i]['id']}" ${options[i]['selected']}>${options[i]['name']}</option>`;
                    str += item;
                }
            }
            ScoreboardStyle.styleSelect.html(str);
        },
        setOption: function(element, options) {
            element.html(options);
        },
        setCheckboxes: function(cb){
            var str = `<tr>
            <td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-logo" class="ssv-cb" ${cb.logo_checked} name="logo" id="ssv-logo-cb"> logo</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score1" class="ssv-cb" ${cb.score1_checked} name="score1" id="ssv-score1-cb"> score 1</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score4" class="ssv-cb" ${cb.score4_checked} name="score4" id="ssv-score4-cb"> score 4</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setpoint" class="ssv-cb" ${cb.setpoint_checked} name="setpoint" id="ssv-setpoint-cb"> set point</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamepoint" class="ssv-cb" ${cb.gamepoint_checked} name="gamepoint" id="ssv-gamepoint-cb"> game point (Set pts)</td>
            </tr>
            <tr>
            <td></td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-team" class="ssv-cb" ${cb.team_checked} name="team" id="ssv-team-cb"> team</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score2" class="ssv-cb" ${cb.score2_checked} name="score2" id="ssv-score2-cb"> score 2</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score5" class="ssv-cb" ${cb.score5_checked} name="score5" id="ssv-score5-cb"> score 5</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setscore" class="ssv-cb" ${cb.setscore_checked} name="setscore" id="ssv-setscore-cb"> set score</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamescore" class="ssv-cb" ${cb.gamescore_checked} name="gamescore" id="ssv-gamescore-cb"> game score (Total)</td>
            </tr>
            <tr>
            <td></td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-player" class="ssv-cb" ${cb.player_checked} name="player" id="ssv-player-cb"> player</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score3" class="ssv-cb" ${cb.score3_checked} name="score3" id="ssv-score3-cb"> score 3</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score6" class="ssv-cb" ${cb.score6_checked} name="score6" id="ssv-score6-cb"> score 6</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-timer" class="ssv-cb" ${cb.timer_checked} name="timer" id="ssv-timer-cb"> timer</td>
            <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-desc" class="ssv-cb" ${cb.description_checked} name="description" id="ssv-description-cb"> description</td>
            </tr>`;
            ScoreboardStyle.visibilityTable.html(str);
        },
        setInfo: function(bowstyle_info, style_info) {
            if (bowstyle_info != "") {
                ScoreboardStyle.bowstyleInfo.html(bowstyle_info + " - ");
                ScoreboardStyle.styleInfo.html(style_info);
            } else {
                ScoreboardStyle.bowstyleInfo.html("None");
                ScoreboardStyle.styleInfo.html("");
            }
        },
        activateStyle: function(e) {
            e.preventDefault();

            $.post(
                "controller.php",
                {
                    scoreboard_style_action: "set-scoreboard-style",
                    style: ScoreboardStyle.styleSelect.val()
                },
                function(data, status) {
                    var result = $.parseJSON(data);
                    if (result.status) {
                        var style_info = $(
                            ScoreboardStyle.styleSelectID + " option:selected"
                        ).text();
                        var bowstyle_info = $(
                            ScoreboardStyle.bowstyleSelectID +
                                " option:selected"
                        ).text();

                        ScoreboardStyle.setInfo(bowstyle_info, style_info);
                        ScoreboardStyle.live_style = ScoreboardStyle.styleSelect.val();
                        Helper.setHide(ScoreboardStyle.activateBtn, 'hide');
                        Helper.setHide(ScoreboardStyle.deactivateBtn, '');
                    }else{
                        alert(result.message);
                    }
                }
            );
        },
        deactivateStyle: function(e) {
            e.preventDefault();

            $.post(
                "controller.php",
                {
                    scoreboard_style_action: "deactivate-style",
                    style_id: ScoreboardStyle.styleSelect.val()
                },
                function(data, status) {
                    var result = $.parseJSON(data);
                    if (result.status) {
                        ScoreboardStyle.live_style = 0;
                        Helper.setHide(ScoreboardStyle.activateBtn, '');
                        Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                        ScoreboardStyle.setInfo('','');
                    }else{
                        alert(result.message);
                    }
                }
            );
        },
        setFormView: function(mode) {
            ScoreboardStyle.formStyleMode.val(mode);
            ScoreboardStyle.formStyleAction.val(mode);

            if (mode == "view") {
                Helper.setHide(ScoreboardStyle.styleNameInputWrapper, "hide");
                Helper.setHide(ScoreboardStyle.styleSelectWrapper, "");
                if (ScoreboardStyle.styleSelect.val() > 0) {
                    if( ScoreboardStyle.styleSelect.val() == ScoreboardStyle.live_style ){
                        Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                        Helper.setHide(ScoreboardStyle.deactivateBtn, '');
                    }else{
                        Helper.setHide(ScoreboardStyle.activateBtn, "");
                        Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                    }
                    Helper.setHide(ScoreboardStyle.editBtn, "");
                    Helper.setHide(ScoreboardStyle.deleteBtn, "");
                    Helper.setHide(ScoreboardStyle.previewWrapper, "");
                } else {
                    Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                    Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                    Helper.setHide(ScoreboardStyle.editBtn, "hide");
                    Helper.setHide(ScoreboardStyle.deleteBtn, "hide");
                    Helper.setHide(ScoreboardStyle.previewWrapper, "hide");
                }
                Helper.setHide(ScoreboardStyle.saveBtn, "hide");
                Helper.setHide(ScoreboardStyle.cancelBtn, "hide");
                if (ScoreboardStyle.bowstyleSelect.val() > 0) {
                    Helper.setHide(ScoreboardStyle.createBtn, "");
                } else {
                    Helper.setHide(ScoreboardStyle.createBtn, "hide");
                }
                Helper.setHide(ScoreboardStyle.visibilityContainer, "hide");
            } else if (mode == "create") {
                Helper.setHide(ScoreboardStyle.styleNameInputWrapper, "");
                Helper.setHide(ScoreboardStyle.styleSelectWrapper, "hide");
                Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                Helper.setHide(ScoreboardStyle.saveBtn, "");
                Helper.setHide(ScoreboardStyle.cancelBtn, "");
                Helper.setHide(ScoreboardStyle.createBtn, "hide");
                Helper.setHide(ScoreboardStyle.editBtn, "hide");
                Helper.setHide(ScoreboardStyle.deleteBtn, "hide");
                Helper.setHide(ScoreboardStyle.visibilityContainer, "");
                Helper.setHide(ScoreboardStyle.previewWrapper, "");
                ScoreboardStyle.styleNameInput.focus();
            } else if (mode == "update") {
                Helper.setHide(ScoreboardStyle.styleNameInputWrapper, "");
                Helper.setHide(ScoreboardStyle.styleSelectWrapper, "hide");
                Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                Helper.setHide(ScoreboardStyle.saveBtn, "");
                Helper.setHide(ScoreboardStyle.cancelBtn, "");
                Helper.setHide(ScoreboardStyle.createBtn, "hide");
                Helper.setHide(ScoreboardStyle.editBtn, "hide");
                Helper.setHide(ScoreboardStyle.deleteBtn, "hide");
                Helper.setHide(ScoreboardStyle.visibilityContainer, "");
                Helper.setHide(ScoreboardStyle.previewWrapper, "");
                ScoreboardStyle.styleNameInput.focus();
            } else if (mode == "delete") {
                Helper.setHide(ScoreboardStyle.styleNameInputWrapper, "");
                Helper.setHide(ScoreboardStyle.styleSelectWrapper, "hide");
                Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                Helper.setHide(ScoreboardStyle.deactivateBtn, 'hide');
                Helper.setHide(ScoreboardStyle.saveBtn, "");
                Helper.setHide(ScoreboardStyle.cancelBtn, "");
                Helper.setHide(ScoreboardStyle.createBtn, "hide");
                Helper.setHide(ScoreboardStyle.editBtn, "hide");
                Helper.setHide(ScoreboardStyle.deleteBtn, "hide");
                Helper.setHide(ScoreboardStyle.visibilityContainer, "hide");
                Helper.setHide(ScoreboardStyle.previewWrapper, "");
            }
        },
        configCreateForm: function(e) {
            e.preventDefault();
            ScoreboardStyle.selectedStyle = ScoreboardStyle.styleSelect.val();
            // Set Style Option to new Number
            // Show Checkboxes
            // Show Preview

            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?scoreboard_style_get=new_form_data&bowstyle_id=" +
                    ScoreboardStyle.bowstyleSelect.val(),
                success: function(data) {
                    if (data.status) {
                        var ss = ScoreboardStyle,
                        preview = data.scoreboard_styles['preview'],
                        checkboxes = data.scoreboard_styles['checkbox'];
                        ss.markCBox.prop("checked", true).val(true);

                        ss.styleNameInput.val("My Custom Style");
                        ss.styleNameInput.removeAttr("disabled");
                        ss.saveBtn
                            .val("Save")
                            .removeClass("btn-danger")
                            .addClass("btn-primary");

                        ss.setCheckboxes(checkboxes);

                        // ss.visibilityTable.html(
                        //     data.scoreboard_styles["checkbox"]
                        // );
                        ss.loadPreview(preview);
                        // ss.previewTable.html(data.scoreboard_styles["preview"]);
                        ss.setFormView("create");
                    }
                }
            });
        },
        setupUpdateForm: function(e) {
            e.preventDefault();

            ScoreboardStyle.selectedStyle = ScoreboardStyle.styleSelect.val();

            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?style_config_get=checkbox&style_id=" +
                    ScoreboardStyle.styleSelect.val(),
                success: function(data) {
                    if (data.status) {
                        var ss = ScoreboardStyle;
                        ss.setCheckboxes(data.scoreboard_styles['checkbox']);
                        // ss.visibilityTable.html(
                        //     data.scoreboard_styles["checkbox"]
                        // );

                        ss.styleNameInput.val(
                            $(ss.styleSelectID + " option:selected").text()
                        );
                        ss.styleNameInput.removeAttr("disabled");
                        ss.bowstyleSelect.attr("disabled", "disabled");
                        ss.styleSelect.attr("disabled", "disabled");

                        ss.saveBtn
                            .val("Save")
                            .removeClass("btn-danger")
                            .addClass("btn-primary");
                        ss.setFormView("update");
                    }
                }
            });
        },
        setupDeleteForm: function(e) {
            e.preventDefault();

            ScoreboardStyle.selectedStyle = ScoreboardStyle.styleSelect.val();

            ScoreboardStyle.bowstyleSelect.attr("disabled", "disabled");
            ScoreboardStyle.styleSelect.attr("disabled", "disabled");
            ScoreboardStyle.styleNameInput.val(
                $(ScoreboardStyle.styleSelectID + " option:selected").text()
            );
            ScoreboardStyle.styleNameInput.attr("disabled", "disabled");

            ScoreboardStyle.saveBtn
                .val("Delete")
                .removeClass("btn-primary")
                .addClass("btn-danger");

            ScoreboardStyle.setFormView("delete");
        },
        cancelFormSetup: function(e) {
            e.preventDefault();

            if (ScoreboardStyle.formStyleMode.val() == "create") {
                if (ScoreboardStyle.bowstyleSelect.val() > 0) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url:
                            "/scoreboard/controller.php?scoreboard_style_get=cancel_form&style_id=" +
                            ScoreboardStyle.selectedStyle +
                            "&bowstyle_id=" +
                            ScoreboardStyle.bowstyleSelect.val(),
                        success: function(data) {
                            if (data.status) {
                                var ss = ScoreboardStyle;

                                ss.loadStyleOption(data.scoreboard_styles['option']);
                                ss.styleSelect.removeAttr('disabled').val(ss.selectedStyle);
                                // ss.styleSelect
                                //     .removeAttr("disabled")
                                //     .html(data.scoreboard_styles["option"]);

                                // ss.styleSelect.val(
                                //     ScoreboardStyle.selectedStyle
                                // );

                                ss.loadPreview(
                                    data.scoreboard_styles["preview"]
                                );

                                ss.setFormView("view");
                            }
                        }
                    });
                }
            } else if (ScoreboardStyle.formStyleMode.val() == "update") {
                ScoreboardStyle.bowstyleSelect.removeAttr("disabled");
                ScoreboardStyle.styleSelect.removeAttr("disabled");

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url:
                        "/scoreboard/controller.php?scoreboard_style_get=cancel_form&style_id=" +
                        ScoreboardStyle.selectedStyle +
                        "&bowstyle_id=" +
                        ScoreboardStyle.bowstyleSelect.val(),
                    success: function(data) {
                        if (data.status) {
                            var ss = ScoreboardStyle;

                            ss.loadStyleOption(data.scoreboard_styles['option']);
                            ss.styleSelect.removeAttr('disabled').val(ss.selectedStyle);
                            // ss.styleSelect
                            //     .removeAttr("disabled")
                            //     .html(data.scoreboard_styles["option"]);

                            // ss.styleSelect.val(ScoreboardStyle.selectedStyle);

                            ss.loadPreview(data.scoreboard_styles["preview"]);
                            ss.setFormView("view");
                        }
                    }
                });
            } else if (ScoreboardStyle.formStyleMode.val() == "delete") {
                var ss = ScoreboardStyle;
                ss.bowstyleSelect.removeAttr("disabled");
                ss.styleSelect.removeAttr("disabled");

                ss.setFormView("view");
            }
        },
        loadStyleOption: function() {
            var ss = ScoreboardStyle;
            if (ss.formStyleMode.val() == "view") {
                if (ss.bowstyleSelect.val() > 0) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url:
                            "/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=" +
                            ss.bowstyleSelect.val(),
                        success: function(data) {
                            if (data.status) {
                                ss.setStyleOption(data.scoreboard_styles["option"]);
                                ss.styleSelect.removeAttr("disabled");
                                // ss.styleSelect
                                //     .removeAttr("disabled")
                                //     .html(data.scoreboard_styles["option"])
                                //     .val(0);

                                ss.setFormView("view");
                            }
                        }
                    });
                } else {
                    if (ss.styleSelect.val() > 0) {
                        ss.styleSelect
                            .html('<option value="0">Choose</option>')
                            .val(0);
                    }
                    ss.styleSelect.attr("disabled", "disabled");
                    ss.setFormView("view");
                }
            }
        },
        setupPreview: function() {
            var value = $(this).val();
            var has_value = $(this).val() > 0;

            if (ScoreboardStyle.formStyleMode.val() == "view") {
                if (has_value) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url:
                            "/scoreboard/controller.php?scoreboard_style_get=single&id=" +
                            value,
                        success: function(data) {
                            if (data.status) {
                                if( data.is_live){
                                    Helper.setHide(ScoreboardStyle.activateBtn, "hide");
                                    Helper.setHide(ScoreboardStyle.deactivateBtn, "");
                                }else{
                                    Helper.setHide(ScoreboardStyle.activateBtn, "");
                                    Helper.setHide(ScoreboardStyle.deactivateBtn, "hide");
                                }
                                Helper.setHide(ScoreboardStyle.editBtn, "");
                                Helper.setHide(ScoreboardStyle.deleteBtn, "");
                                // ScoreboardStyle.visibilityTable.html(
                                //     data.style_preview_checkbox
                                // );
                                ScoreboardStyle.loadPreview(data.scoreboard_styles['preview']);
                                // ScoreboardStyle.previewTable.html(
                                //     data.scoreboard_styles["preview"]
                                // );
                                Helper.setHide(
                                    ScoreboardStyle.previewWrapper,
                                    ""
                                );
                            }
                        }
                    });
                } else {
                    Helper.setHide(ScoreboardStyle.activateBtn, "hide");

                    Helper.setHide(ScoreboardStyle.editBtn, "hide");
                    Helper.setHide(ScoreboardStyle.deleteBtn, "hide");

                    Helper.setHide(ScoreboardStyle.previewWrapper, "hide");
                }
            }
        },
        submitForm: function(e) {
            e.preventDefault();

            if (request) {
                request.abort();
            }

            var $form = $(this);

            $form.find("select").removeAttr("disabled");
            // var $input = $form.find("input, select, button, textarea");
            // var serializedData = $form.serialize();
            var serializedData = $form.serializeArray();

            $("#form-scoreboard-style input.ssv-cb:not(:checked)").each(
                function(i, e) {
                    serializedData.push({
                        name: this.name,
                        value: this.checked ? this.value : "false"
                    });
                }
            );

            request = $.ajax({
                url: "/scoreboard/controller.php",
                type: "POST",
                data: serializedData
            });

            request.done(function(response, textStatus, jqXHR) {
                var data = $.parseJSON(response);
                if (data.status) {
                    if (data.action == "create") {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url:
                                "/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=" +
                                ScoreboardStyle.bowstyleSelect.val(),
                            success: function(data2) {
                                if (data2.status) {

                                    ScoreboardStyle.setStyleOption(data2.scoreboard_styles["option"]);
                                    ScoreboardStyle.styleSelect.val(data.latest_id);
                                    // ScoreboardStyle.styleSelect
                                    //     .html(data2.scoreboard_styles["option"])
                                    //     .val(data.latest_id);

                                    ScoreboardStyle.setFormView("view");
                                }
                            }
                        });
                    } else if (data.action == "update") {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url:
                                "/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=" +
                                ScoreboardStyle.bowstyleSelect.val(),
                            success: function(data2) {
                                if (data2.status) {
                                    ScoreboardStyle.setStyleOption(data2.scoreboard_styles["option"]);
                                    ScoreboardStyle.styleSelect.val(ScoreboardStyle.selectedStyle);
                                    // ScoreboardStyle.styleSelect.html(
                                    //     data2.scoreboard_styles["option"]
                                    // );
                                    // ScoreboardStyle.styleSelect.val(
                                    //     ScoreboardStyle.selectedStyle
                                    // );

                                    ScoreboardStyle.setFormView("view");
                                }
                            }
                        });
                    } else if (data.action == "delete") {
                        if (data.selected > 0) {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url:
                                    "/scoreboard/controller.php?scoreboard_style_get=live",
                                success: function(data2) {
                                    if (data2.status) {
                                        ScoreboardStyle.loadStyleOption(data2.scoreboard_styles['option']);

                                        ScoreboardStyle.styleSelect.removeAttr("disabled");

                                        ScoreboardStyle.styleSelect.val(
                                            data2.live_style
                                        );
                                        ScoreboardStyle.live_style = data2.live_style;

                                        ScoreboardStyle.loadPreview(
                                            data2.scoreboard_styles["preview"]
                                        );
                                        ScoreboardStyle.setFormView("view");
                                    }
                                }
                            });
                        } else {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url:
                                    "/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=" +
                                    ScoreboardStyle.bowstyleSelect.val(),
                                success: function(data2) {
                                    if (data2.status) {
                                        ScoreboardStyle.setStyleOption(data2.scoreboard_styles["option"]);
                                        ScoreboardStyle.styleSelect.val(0);
                                        // ScoreboardStyle.styleSelect
                                        //     .html(
                                        //         data2.scoreboard_styles[
                                        //             "option"
                                        //         ]
                                        //     )
                                        //     .val(0);

                                        ScoreboardStyle.setInfo("", "");

                                        ScoreboardStyle.loadPreview("");
                                        ScoreboardStyle.setFormView("view");
                                    }
                                }
                            });
                        }
                    }
                } else {
                }
            });
        }
    };

    var Team = {
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            $("#create-team-button").click(this.setupCreateForm);
            $("#form-team").on("submit", this.submitForm);
            $(document)
                .on("click", ".team-delete-btn-cls", this.setupDeleteForm)
                .on("click", ".team-update-btn-cls", this.setupUpdateForm);
        },
        loadTable: function(table) {
            var str = `<td class="text-white font-weight-light border-info">-</td>
            <td class="text-white font-weight-light border-info">-</td>
            <td class="text-white font-weight-light border-info">-</td>`;
            if(table.length != 0){
                str = '';
                for(i=0; i<table.length; i++){
                    str += `<tr>
                    <td class="text-gray-4 border-gray-3 pl-0">
                        <button data-teamid="${table[i]['id']}" class="btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder team-delete-btn-cls">X</button>
                        <button data-teamid="${table[i]['id']}" class="btn btn-sm btn-outline-warning-2 border-0 rounded-circle team-update-btn-cls"><i class="fas fa-pen"></i></button>
                    </td>
                    <td class="text-gray-4 font-weight-light border-gray-3">
                        <img style="max-height:24px;" src="uploads/${table[i]['logo']}">
                    </td>
                    <td class="text-gray-4 font-weight-light border-gray-3">
                        <span>${table[i]['name']}</span>
                    </td>
                    </tr>`;
                }

            }
            $("#team-table tbody").html(str);
            // if (table != "") $("#team-table tbody").html(table);
        },
        loadOption: function(teams) {
            var str = `<option value="0">Choose</option>`;
            if(teams.length != 0){
                for(i=0; i<teams.length; i++){
                    str += `<option value="${teams[i]['id']}">${teams[i]['name']}</option>`;
                }
            }
            $("#player-team").html(str);
            $("#gamedraw-team-a").html(str);
            $("#gamedraw-team-b").html(str);
            // if (options != "") {
            //     $("#player-team").html(options);
            //     $("#gamedraw-team-a").html(options);
            //     $("#gamedraw-team-b").html(options);
            // }
        },
        loadForm: function(teamdata, modeget) {
            var modalTitle = "";
            if (modeget == "update") {
                modalTitle += "Update";
                $("#team-modal-image")
                    .attr("src", "uploads/" + teamdata.logo)
                    .removeClass("hide");
                $("#team-name")
                    .val(teamdata.name)
                    .removeAttr("disabled");
                $("#team-desc")
                    .val(teamdata.desc)
                    .removeAttr("disabled");
                $("#team-logo").val("").removeAttr("disabled");
                $("#team-id").val(teamdata.id);
                $("#team-action").val("update");
                $("#team-submit").val("Save");
            } else if (modeget == "create") {
                modalTitle += "New";
                $("#team-modal-image")
                    .attr("src", "")
                    .addClass("hide");
                $("#team-name")
                    .val("")
                    .removeAttr("disabled");
                $("#team-desc")
                    .val("")
                    .removeAttr("disabled");
                $("#team-logo").val("").removeAttr("disabled");
                $("#team-id").val(0);
                $("#team-action").val("create");
                $("#team-submit").val("Create");
            } else if (modeget == "delete") {
                modalTitle += "Delete";
                $("#team-modal-image")
                    .attr("src", "uploads/" + teamdata.logo)
                    .removeClass("hide");
                $("#team-name")
                    .val(teamdata.name)
                    .attr("disabled", "disabled");
                $("#team-desc")
                    .val(teamdata.desc)
                    .attr("disabled", "disabled");
                $("#team-logo").attr("disabled", "disabled");
                $("#team-id").val(teamdata.id);
                $("#team-action").val("delete");
                $("#team-submit").val("Delete");
            }
            modalTitle += " Team";
            $("#team-modal-title").html(modalTitle);
            $("#form-team-modal").modal();
        },
        setupCreateForm: function() {
            Team.loadForm(null, "create");
        },
        setupUpdateForm: function(e) {
            e.preventDefault();
            var teamid = $(this).attr("data-teamid");
            Team.ajaxSetupForm(teamid, "update");
        },
        setupDeleteForm: function(e) {
            e.preventDefault();
            var teamid = $(this).attr("data-teamid");
            Team.ajaxSetupForm(teamid, "delete");
        },
        ajaxSetupForm: function(teamid, modeget) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/scoreboard/controller.php?team_get=single&id=" + teamid,
                success: function(data) {
                    if (data.status) {
                        Team.loadForm(data.team['modal_form'], modeget);
                    }
                }
            });
        },
        submitForm: function(e) {
            e.preventDefault();
            $.ajax({
                url: "/scoreboard/controller.php",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    // console.log('before send');
                },
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status) {
                        if (result.action == "create") {
                            $("#team-logo").val("");
                            $("#team-name").val("");
                            $("#team-desc").val("");
                            $("#team-modal-image")
                                .attr("src", "")
                                .addClass("hide");

                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/scoreboard/controller.php?team_get=new_list",
                                success: function(data) {
                                    if (data.status) {
                                        Team.loadTable(data.team["table"]);
                                        Team.loadOption(data.team["option"]);
                                    }
                                }
                            });
                        } else if (
                            result.action == "update" ||
                            result.action == "delete"
                        ) {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url:
                                    "/scoreboard/controller.php?config_get=init",
                                success: function(data) {
                                    if (data.status) {
                                        FormScoreboard.loadForm(
                                            data.scoreboard_form
                                        );

                                        var scoreboard_styles =
                                            data.scoreboard_styles,
                                            bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                            style_name = scoreboard_styles.info['style_name'];
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.bowstyleSelect,
                                        //     scoreboard_styles["bowstyle"][
                                        //         "option"
                                        //     ]
                                        // );
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.styleSelect,
                                        //     scoreboard_styles["option"]
                                        // );
                                        ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                        ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                        ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                        ScoreboardStyle.loadPreview(
                                            scoreboard_styles["preview"]
                                        );
                                        ScoreboardStyle.configBtn(
                                            scoreboard_styles["config"]
                                        );

                                        Team.loadTable(data.team["table"]);
                                        Team.loadOption(data.team["option"]);
                                        Player.loadTable(data.player["table"]);
                                        Player.loadOption(
                                            data.player["option"]
                                        );
                                        GameDraw.loadOption(
                                            data.gamedraw["option"]
                                        );
                                        GameDraw.loadTable(
                                            data.gamedraw["table"]
                                        );
                                        GameSet.loadTable(
                                            data.gameset["table"]
                                        );

                                        $("#form-team-modal").modal("hide");
                                    }
                                }
                            });
                        }
                    } else {
                        alert(result.message);
                    }
                },
                error: function(e) {
                    // console.log('error');
                }
            });
        }
    };

    var Player = {
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            $("#create-player-button").click(this.setupCreateForm);
            $("#form-player").on("submit", this.submitForm);
            $(document)
                .on("click", ".player-delete-btn-cls", this.setupDeleteForm)
                .on("click", ".player-update-btn-cls", this.setupUpdateForm);
        },
        setupCreateForm: function() {
            Player.loadForm(false, "create");
        },
        setupDeleteForm: function(e) {
            e.preventDefault();
            var playerid = $(this).attr("data-playerid");
            Player.ajaxSetupForm(playerid, "delete");
        },
        setupUpdateForm: function(e) {
            e.preventDefault();
            var playerid = $(this).attr("data-playerid");
            Player.ajaxSetupForm(playerid, "update");
        },
        loadTable: function(table) {
            var str = `<td class="text-white font-weight-light border-info">-</td>
            <td class="text-white font-weight-light border-info">-</td>
            <td class="text-white font-weight-light border-info">-</td>`;
            if(table.length != 0){
                str = '';
                for(i=0; i<table.length; i++){
                    str += `<tr>
                    <td class="text-gray-4 border-gray-3 pl-0">
                        <button data-playerid="${table[i]['id']}" class="btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder player-delete-btn-cls">X</button>
                        <button data-playerid="${table[i]['id']}" class="btn btn-sm btn-outline-warning-2 border-0 rounded-circle player-update-btn-cls"><i class="fas fa-pen"></i></button>
                    </td>
                    <td class="text-info font-weight-light border-gray-3">
                        <span>${table[i]['team_name']}</span>
                    </td>
                    <td class="text-gray-4 font-weight-light border-gray-3">
                        <span>${table[i]['name']}</span>
                    </td>
                    </tr>`;
                }

            }
            $("#player-table tbody").html(str);
            // if (player_table != "") $("#player-table tbody").html(player_table);
        },
        loadOption: function(players) {
            var str = `<option value="0">Choose</option>`;
            if(players.length != 0){
                for(i=0; i<players.length; i++){
                    str += `<option value="${players[i]['id']}">${players[i]['name']}</option>`;
                }
            }
            $("#gamedraw-player-a").html(str);
            $("#gamedraw-player-b").html(str);
            // if (options != "") {
            //     $("#gamedraw-player-a").html(options);
            //     $("#gamedraw-player-b").html(options);
            // }
        },
        loadForm: function(playerdata, modeget) {
            var modalTitle = "";
            if (modeget == "update") {
                modalTitle += "Update";
                $("#player-name")
                    .val(playerdata.name)
                    .removeAttr("disabled");
                $("#player-team")
                    .val(playerdata.team_id)
                    .removeAttr("disabled");
                $("#player-id").val(playerdata.id);
                $("#player-action").val("update");
                $("#player-submit").val("Save");
            } else if (modeget == "create") {
                modalTitle += "New";
                $("#player-name")
                    .val("")
                    .removeAttr("disabled");
                $("#player-team")
                    .val(0)
                    .removeAttr("disabled");
                $("#player-id").val(0);
                $("#player-action").val("create");
                $("#player-submit").val("Create");
            } else if (modeget == "delete") {
                modalTitle += "Delete";
                $("#player-name")
                    .val(playerdata.name)
                    .attr("disabled", "disabled");
                $("#player-team")
                    .val(playerdata.team_id)
                    .attr("disabled", "disabled");
                $("#player-id").val(playerdata.id);
                $("#player-action").val("delete");
                $("#player-submit").val("Delete");
            }
            modalTitle += " Player";
            $("#player-modal-title").html(modalTitle);
            $("#form-player-modal").modal();
        },
        ajaxSetupForm: function(playerid, modeget) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?player_get=single&id=" +
                    playerid,
                success: function(data) {
                    if (data.status) {
                        Player.loadForm(data.player['modal_form'], modeget);
                    }
                }
            });
        },
        submitForm: function(e) {
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
                data: serializedData
            });

            request.done(function(response, textStatus, jqXHR) {
                var data = $.parseJSON(response);
                if (data.status) {
                    if (data.action == "create") {
                        $("#player-name").val("");
                        $("#player-team").val(0);
                        $("#player-id").val(0);
                        $("#player-action").val("create");

                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?player_get=new_list",
                            success: function(data) {
                                if (data.status) {
                                    Player.loadTable(data.player["table"]);
                                    Player.loadOption(data.player["option"]);
                                }
                            }
                        });
                    } else if (
                        data.action == "update" ||
                        data.action == "delete"
                    ) {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?config_get=init",
                            success: function(data) {
                                if (data.status) {
                                    FormScoreboard.loadForm(
                                        data.scoreboard_form
                                    );

                                    var scoreboard_styles =
                                        data.scoreboard_styles,
                                        bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                        style_name = scoreboard_styles.info['style_name'];
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.bowstyleSelect,
                                    //     scoreboard_styles["bowstyle"]["option"]
                                    // );
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.styleSelect,
                                    //     scoreboard_styles["option"]
                                    // );
                                    ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                    ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                    ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                    ScoreboardStyle.loadPreview(
                                        scoreboard_styles["preview"]
                                    );
                                    ScoreboardStyle.configBtn(
                                        scoreboard_styles["config"]
                                    );
                                    Player.loadTable(data.player["table"]);
                                    Player.loadOption(data.player["option"]);
                                    GameDraw.loadOption(
                                        data.gamedraw["option"]
                                    );
                                    GameDraw.loadTable(data.gamedraw["table"]);
                                    GameSet.loadTable(data.gameset["table"]);

                                    $("#form-player-modal").modal("hide");
                                }
                            }
                        });
                    }
                }
            });
        }
    };

    var GameDraw = {
        btnCreate: $("#create-gamedraw-button"),
        formGameDraw: $("#form-gamedraw"),

        btnDeleteCls: ".gamedraw-delete-btn-cls",
        btnUpdateCls: ".gamedraw-update-btn-cls",
        btnViewCls: ".gamedraw-view-btn-cls",

        btnPrintSummaryID: "#gamedraw-summary-print",
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            this.btnCreate.click(this.setupCreateForm);
            this.formGameDraw.on("submit", this.submitForm);
            $(document)
                .on("click", this.btnDeleteCls, this.setupDeleteForm)
                .on("click", this.btnUpdateCls, this.setupUpdateForm)
                .on("click", this.btnViewCls, this.viewSummary)
                .on("click", this.btnPrintSummaryID, this.printSummary)
                .on("change", ".gamedraw-gamemode-cls", this.setupOption)
                .on("change", ".gamedraw-team-cls", this.filterTeamOption)
                .on("change", ".gamedraw-player-cls", this.filterPlayerOption);
        },
        filterPlayerOption: function() {
            var val = $(this).val();
            if (val != 0) {
                if (this.id == "gamedraw-player-a") {
                    $("#gamedraw-player-b > option").show();
                    $("#gamedraw-player-b option[value=" + val + "]").hide();
                } else if (this.id == "gamedraw-player-b") {
                    $("#gamedraw-player-a > option").show();
                    $("#gamedraw-player-a option[value=" + val + "]").hide();
                }
            } else {
                if (this.id == "gamedraw-player-a") {
                    $("#gamedraw-player-b > option").show();
                } else if (this.id == "gamedraw-player-b") {
                    $("#gamedraw-player-a > option").show();
                }
            }
        },
        filterTeamOption: function() {
            var val = $(this).val();
            if (val != 0) {
                if (this.id == "gamedraw-team-a") {
                    $("#gamedraw-team-b > option").show();
                    $("#gamedraw-team-b option[value=" + val + "]").hide();
                } else if (this.id == "gamedraw-team-b") {
                    $("#gamedraw-team-a > option").show();
                    $("#gamedraw-team-a option[value=" + val + "]").hide();
                }
            } else {
                if (this.id == "gamedraw-team-a") {
                    $("#gamedraw-team-b > option").show();
                } else if (this.id == "gamedraw-team-b") {
                    $("#gamedraw-team-a > option").show();
                }
            }
        },
        setupOption: function() {
            $("select.gamedraw-team-cls").prop("selectedIndex", 0);
            $("select.gamedraw-player-cls").prop("selectedIndex", 0);
            if (this.id == "gamedraw-gamemode-beregu") {
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                $(".gamedraw-team-opt-area-cls")
                    .removeClass("hide")
                    .addClass("show");
            } else if (this.id == "gamedraw-gamemode-individu") {
                $(".gamedraw-team-opt-area-cls").addClass("hide");
                $(".gamedraw-player-opt-area-cls")
                    .removeClass("hide")
                    .addClass("show");
            }
        },
        setupCreateForm: function() {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/scoreboard/controller.php?gamedraw_get=new_num",
                success: function(data) {
                    if (data.status) {
                        $("#gamedraw-num").val(data.gamedraw['new_num']);
                    } else {
                        $("#gamedraw-num").val(1);
                    }
                    GameDraw.loadForm(false, "create");
                }
            });
        },
        setupDeleteForm: function(e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            GameDraw.ajaxSetupForm(gamedrawid, "delete");
        },
        setupUpdateForm: function(e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            GameDraw.ajaxSetupForm(gamedrawid, "update");
        },
        viewSummary: function(e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?gamedraw_get=summary&id=" +
                    gamedrawid,
                success: function(data) {
                    if (data.status) {
                        var str = '';
                        var summary = data.gamedraw['summary'];
                        if(summary.length > 0){
                            str = `<h5 class="text-light font-weight-light">${summary[0]['player_a']} vs ${summary[0]['player_b']}</h5>
                            <p class="text-info">[<span class="small">${(summary[0]['style']).toUpperCase()}</span> - <span class="small">${(summary[0]['gamemode']).toLowerCase()}</span>]</p>
                            <table class="table table-sm" id="gamedraw-info-modal-table">
                                <thead>
                                    <tr class="bg-gray-2">
                                        <th class="text-gray-4 font-weight-normal border-0">Set</th>
                                        <th class="text-gray-4 font-weight-normal border-0">${summary[0]['player_a']}</th>
                                        <th class="text-gray-4 font-weight-normal border-0">${summary[0]['player_b']}</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                            var total_score_a = 0,
                                total_score_b = 0,
                                gameWinnerAClass = "text-gray-4",
                                gameWinnerBClass = "text-gray-4";
                            for(i=0; i<summary.length; i++){
                                var winnerACSS = 'text-gray-4',
                                winnerBCSS = winnerACSS,
                                score_a = summary[i]['score_a'],
                                score_b = summary[i]['score_b'];
                                total_score_a += Helper.parseNum(summary[i]['score_a']);
                                total_score_b += Helper.parseNum(summary[i]['score_b']);
                                if (score_a > score_b) {
                                    winnerACSS = 'text-success';
                                } else if (score_a < score_b) {
                                    winnerBCSS = 'text-success';
                                }
                                var item = `<tr>
                                <td class="text-gray-4 font-weight-bold border-gray-2">${summary[i]['sets']}</td>
                                <td class="font-weight-bold border-gray-2 ${winnerACSS}"><span>${summary[i]['score_a']}</span></td>
                                <td class="font-weight-bold border-gray-2 ${winnerBCSS}"><span>${summary[i]['score_b']}</span></td>
                                </tr>`;
                                str += item;
                            }
                            if (total_score_a > total_score_b) {
                                gameWinnerAClass = "text-success";
                            } else if (total_score_a < total_score_b) {
                                gameWinnerBClass = "text-success";
                            }
                            str += `<tr class='bg-gray-3'>
                                <td class="text-warning font-weight-bold border-gray-3">TOTAL</td>
                                <td class="font-weight-bold border-gray-3 bg-gray-3 ${gameWinnerAClass}"><span>${total_score_a}</span></td>
                                <td class="font-weight-bold border-gray-3 bg-gray-3 ${gameWinnerBClass}"><span>${total_score_b}</span></td>
                            </tr>`;
                        }
                        $("#gamedraw-summary").html(str);
                        $("#gamedraw-summary-print").removeAttr("disabled");
                        // $("#gamedraw-summary").html(data.gamedraw["summary"]);
                        // $("#gamedraw-summary-print").removeAttr("disabled");
                    } else {
                        $("#gamedraw-summary").html(
                            '<h3 class="text-center text-light">-</h3>'
                        );
                        $("#gamedraw-summary-print").attr(
                            "disabled",
                            "disabled"
                        );
                    }
                    $("#gamedraw-info-modal").modal();
                }
            });
        },
        printSummary: function() {
            printJS({
                printable: "gamedraw-summary",
                type: "html",
                //scanStyles: false,
                css: [
                    "http://localhost/scoreboard/bootstrap/css/bootstrap.min.css",
                    "http://localhost/scoreboard/css/style.css"
                ]
            });
        },
        loadTable: function(table) {
            var str = '';
            if(table.length > 0){
                for(i=0; i<table.length; i++){
                    var item = `<tr>
                    <td class="text-gray-4 border-gray-3 pl-0">
                        <button data-gamedrawid="${table[i]['id']}" class="btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder gamedraw-delete-btn-cls">X</button>
                        <button data-gamedrawid="${table[i]['id']}" class="btn btn-sm btn-outline-warning-2 border-0 rounded-circle gamedraw-update-btn-cls"><i class="fas fa-pen"></i></button>
                    </td>
                    <td class="text-info font-weight-light border-gray-3">
                        [<span class="small">${(table[i]['bowstyle_name']).toUpperCase()}</span> - <span class="small">${(table[i]['gamemode_name']).toLowerCase()}</span>]
                    </td>
                    <td class="text-gray-4 font-weight-normal border-gray-3">${table[i]['num']}</td>
                    <td class="text-gray-4 font-weight-light border-gray-3">${table[i]['contestant_a_name']} vs ${table[i]['contestant_b_name']}</td>
                    <td class="text-gray-4 font-weight-light border-gray-3">
                        <button data-gamedrawid="${table[i]['id']}" class="btn btn-sm btn-outline-success rounded-circle border-0 gamedraw-view-btn-cls mr-3"><i class="fas fa-eye"></i></button>
                    </td>
                    </tr>`;
                    str += item;
                }
            }else{
                str = `<td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>`;
            }
            $("#gamedraw-table tbody").html(str);
            // if (table != "") $("#gamedraw-table tbody").html(table);
        },
        loadOption: function(options) {
            var str = '<option value="0">Choose</option>';
            if(options.length > 0){
                for(i=0; i<options.length; i++){
                    var item = `<option value="${options[i]['id']}">${options[i]['num']}. ${options[i]['contestant_a_name']} vs ${options[i]['contestant_b_name']}</option>`;
                    str += item;
                }
            }
            $("#gameset-gamedraw").html(str);
            // if (options != "") $("#gameset-gamedraw").html(options);
        },
        loadForm: function(gamedrawdata, modeget) {
            var modalTitle = "", modal_form = gamedrawdata['modal_form'];
            if (modeget == "update") {
                modalTitle += "Update";
                // $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");
                $("#gamedraw-num")
                    .val(modal_form.num)
                    .removeAttr("disabled");

                if (modal_form.bowstyle_id == 1) {
                    $("#gamedraw-bowstyle-recurve").prop("checked", true);
                } else if (modal_form.bowstyle_id == 2) {
                    $("#gamedraw-bowstyle-compound").prop("checked", true);
                }
                $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
                /*
                 * TO-DO: radio game mode dinamic
                 */
                if (modal_form.gamemode_id == 1) {
                    $("#gamedraw-gamemode-beregu").prop("checked", true);
                    $(".gamedraw-player-opt-area-cls").addClass("hide");
                    $(".gamedraw-team-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-team-a").val(modal_form.contestant_a_id).removeAttr("disabled");
                    // $("#gamedraw-team-b").val(modal_form.contestant_b_id).removeAttr("disabled");
                    $("#gamedraw-team-a").val(modal_form.contestant_a_id);
                    $("#gamedraw-team-b").val(modal_form.contestant_b_id);
                    // addAttribute($(".gamedraw-team-cls"), 'disabled', 'disabled');
                    // } else if (modal_form.gamemode['id'] == 2) {
                } else if (modal_form.gamemode_id == 2) {
                    $("#gamedraw-gamemode-individu").prop("checked", true);
                    $(".gamedraw-team-opt-area-cls").addClass("hide");
                    $(".gamedraw-player-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-player-a").val(modal_form.contestant_a_id).removeAttr("disabled");
                    // $("#gamedraw-player-b").val(modal_form.contestant_b_id).removeAttr("disabled");
                    $("#gamedraw-player-a").val(modal_form.contestant_a_id);
                    $("#gamedraw-player-b").val(modal_form.contestant_b_id);
                    // addAttribute($(".gamedraw-player-cls"), 'disabled', 'disabled');
                }
                // $(".gamedraw-bowstyle-cls").removeAttr("disabled");
                // $(".gamedraw-gamemode-cls").removeAttr("disabled");
                // addAttribute($(".gamedraw-gamemode-cls"), 'disabled', 'disabled');
                $("#gamedraw-id").val(modal_form.id);
                $("#gamedraw-action").val("update");
                $("#gamedraw-submit").val("Update");
            } else if (modeget == "create") {
                modalTitle += "New";
                $("#gamedraw-num").removeAttr("disabled");
                /*
                 * TO-DO: radio game mode dinamic
                 */
                $("#gamedraw-bowstyle-recurve").prop("checked", true);
                $("#gamedraw-bowstyle-compound").prop("checked", false);
                $("#gamedraw-gamemode-beregu").prop("checked", true);
                $("#gamedraw-gamemode-individu").prop("checked", false);
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                $(".gamedraw-team-opt-area-cls").removeClass("hide");

                // $("#gamedraw-team-a").val(0).removeAttr("disabled");
                // $("#gamedraw-team-b").val(0).removeAttr("disabled");
                // $("#gamedraw-player-a").val(0).removeAttr("disabled");
                // $("#gamedraw-player-b").val(0).removeAttr("disabled");
                // $(".gamedraw-bowstyle-cls").removeAttr("disabled");
                // $(".gamedraw-gamemode-cls").removeAttr("disabled");
                $("#gamedraw-team-a").removeAttr("disabled");
                $("#gamedraw-team-b").removeAttr("disabled");
                $("#gamedraw-player-a").removeAttr("disabled");
                $("#gamedraw-player-b").removeAttr("disabled");
                $(".gamedraw-bowstyle-cls").removeAttr("disabled");
                $(".gamedraw-gamemode-cls").removeAttr("disabled");

                $("#gamedraw-id").val(0);
                $("#gamedraw-action").val("create");
                $("#gamedraw-submit").val("Save");
            } else if (modeget == "delete") {
                modalTitle += "Delete";
                // $("#gamedraw-num").val(gamedrawdata.num).attr("disabled", "disabled");
                $("#gamedraw-num")
                    .val(modal_form.num)
                    .attr("disabled", "disabled");

                if (modal_form.bowstyle_id == 1) {
                    $("#gamedraw-bowstyle-recurve").prop("checked", true);
                } else if (modal_form.bowstyle_id == 2) {
                    $("#gamedraw-bowstyle-compound").prop("checked", true);
                }
                /*
                 * TO-DO: radio game mode dinamic
                 */
                if (modal_form.gamemode_id == 1) {
                    $("#gamedraw-gamemode-beregu").prop("checked", true);
                    $(".gamedraw-player-opt-area-cls").addClass("hide");
                    $(".gamedraw-team-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-team-a").val(modal_form.contestant_a_id).attr("disabled", "disabled");
                    // $("#gamedraw-team-b").val(modal_form.contestant_b_id).attr("disabled", "disabled");
                    $("#gamedraw-team-a").val(modal_form.contestant_a_id);
                    $("#gamedraw-team-b").val(modal_form.contestant_b_id);
                    $(".gamedraw-team-cls").attr("disabled", "disabled");
                } else if (modal_form.gamemode_id == 2) {
                    $("#gamedraw-gamemode-individu").prop("checked", true);
                    $(".gamedraw-team-opt-area-cls").addClass("hide");
                    $(".gamedraw-player-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-player-a").val(modal_form.contestant_a_id).attr("disabled", "disabled");
                    // $("#gamedraw-player-b").val(modal_form.contestant_b_id).attr("disabled", "disabled");
                    $("#gamedraw-player-a").val(modal_form.contestant_a_id);
                    $("#gamedraw-player-b").val(modal_form.contestant_b_id);
                    $(".gamedraw-player-cls").attr("disabled", "disabled");
                }
                // $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
                // $(".gamedraw-gamemode-cls").attr("disabled", "disabled");
                $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
                $(".gamedraw-gamemode-cls").attr("disabled", "disabled");
                $("#gamedraw-id").val(modal_form.id);
                $("#gamedraw-action").val("delete");
                $("#gamedraw-submit").val("Delete");
            }
            modalTitle += " Game Draw";
            $("#gamedraw-modal-title").html(modalTitle);
            $("#form-gamedraw-modal").modal();
        },
        ajaxSetupForm: function(gamedrawid, modeget) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?gamedraw_get=modal_data&id=" +
                    gamedrawid,
                success: function(data) {
                    if (data.status) {
                        GameDraw.loadForm(data.gamedraw, modeget);
                    }
                }
            });
        },
        submitForm: function(e) {
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
                data: serializedData
            });

            request.done(function(response, textStatus, jqXHR) {
                // console.log(response);
                response = $.parseJSON(response);
                if (response.status) {
                    if (response.action == "create") {
                        $("select.gamedraw-team-cls").prop("selectedIndex", 0);
                        $("select.gamedraw-player-cls").prop(
                            "selectedIndex",
                            0
                        );
                        $("#form-gamedraw input#gamedraw-num").val(
                            response.next_num
                        );

                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?gamedraw_get=new",
                            success: function(data) {
                                if (data.status) {
                                    GameDraw.loadOption(
                                        data.gamedraw["option"]
                                    );
                                    GameDraw.loadTable(data.gamedraw["table"]);
                                }
                            }
                        });
                    } else if (
                        response.action == "update" ||
                        response.action == "delete"
                    ) {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?config_get=init",
                            success: function(data) {
                                if (data.status) {
                                    FormScoreboard.loadForm(
                                        data.scoreboard_form
                                    );

                                    var scoreboard_styles =
                                        data.scoreboard_styles,
                                        bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                        style_name = scoreboard_styles.info['style_name'];
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.bowstyleSelect,
                                    //     scoreboard_styles["bowstyle"]["option"]
                                    // );
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.styleSelect,
                                    //     scoreboard_styles["option"]
                                    // );
                                        ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                        ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                    ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                    ScoreboardStyle.loadPreview(
                                        scoreboard_styles["preview"]
                                    );
                                    ScoreboardStyle.configBtn(
                                        scoreboard_styles["config"]
                                    );
                                    GameDraw.loadOption(
                                        data.gamedraw["option"]
                                    );
                                    GameDraw.loadTable(data.gamedraw["table"]);
                                    GameSet.loadTable(data.gameset["table"]);

                                    $("#form-gamedraw-modal").modal("hide");
                                }
                            }
                        });
                    }
                }
            });
        }
    };

    var GameSet = {
        init: function() {
            GameSet.bindEvents();
        },
        bindEvents: function() {
            $("#create-gameset-button").click(GameSet.setupCreateForm);
            $("#gameset-gamedraw").on("change", GameSet.setNum);
            $(document)
                .on("click", ".gameset-delete-btn-cls", GameSet.setupDeleteForm)
                .on("click", ".gameset-update-btn-cls", GameSet.setupUpdateForm)
                .on("submit", "#form-gameset", GameSet.submitForm)
                .on("click", ".gameset-live-btn-cls", GameSet.startGame)
                .on("click", ".gameset-stoplive-btn-cls", GameSet.stopGame)
                .on("click", ".gameset-view-btn-cls", GameSet.viewSummary)
                .on("click", "#gameset-summary-print", GameSet.printSummary);
        },
        setupCreateForm: function() {
            GameSet.loadForm(false, "create");
        },
        setupDeleteForm: function(e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            GameSet.ajaxSetupForm(gamesetid, "delete");
        },
        setupUpdateForm: function(e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            GameSet.ajaxSetupForm(gamesetid, "update");
        },
        setNum: function() {
            var gamedraw_id = $(this).val();
            if (gamedraw_id == 0) {
                $("#gameset-setnum").val(1);
            } else {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url:
                        "/scoreboard/controller.php?gameset_get=new_num&gamedraw_id=" +
                        gamedraw_id,
                    success: function(data) {
                        if (data.status) {
                            $("#gameset-setnum").val(data.gameset['new_num']);
                        } else {
                            $("#gameset-setnum").val(1);
                        }
                    }
                });
            }
        },
        viewSummary: function(e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?gameset_get=summary&id=" +
                    gamesetid,
                success: function(data) {
                    if (data.status) {
                        var str = '', summary = data.gameset['summary'];
                        if(summary.length > 0){
                            var contestant_a = summary[0], contestant_b = summary[1];
                            str = `<thead class="bg-dark text-white">
                            <tr class="bg-gray-2">
                                <th class="text-gray-4 font-weight-normal border-0">Point</th>
                                <th class="text-gray-4 font-weight-normal border-0">${contestant_a['contestant_name']}</th>
                                <th class="text-gray-4 font-weight-normal border-0">${contestant_b['contestant_name']}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-gray-4 font-weight-bold border-0">1</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_a['score_1']}</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_b['score_1']}</td>
                            </tr>
                            <tr>
                                <td class="text-gray-4 font-weight-bold border-0">2</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_a['score_2']}</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_b['score_2']}</td>
                            </tr>
                            <tr class="text-gray-4 font-weight-normal border-0">
                                <td class="text-gray-4 font-weight-bold border-0">3</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_a['score_3']}</td>
                                <td class="text-gray-4 font-weight-normal border-0">${contestant_b['score_3']}</td>
                            </tr>
                            <tr class="d-none">
                                <td class="text-gray-4 font-weight-normal border-0">4</td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                            </tr>
                            <tr class="d-none">
                                <td class="text-gray-4 font-weight-normal border-0">5</td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                            </tr>
                            <tr class="d-none">
                                <td class="text-gray-4 font-weight-normal border-0">6</td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                            </tr>
                            <tr class="bg-gray-3">
                                <td class="text-warning font-weight-bold border-gray-3">Set Scores</td>
                                <td class="text-warning font-weight-bold border-gray-3">${contestant_a['setscores']}</td>
                                <td class="text-warning font-weight-bold border-gray-3">${contestant_b['setscores']}</td>
                            </tr>
                            <tr class="bg-gray-3">
                                <td class="text-success font-weight-bold border-gray-3">Set Points</td>
                                <td class="text-success font-weight-bold border-gray-3">${contestant_a['setpoints']}</td>
                                <td class="text-success font-weight-bold border-gray-3">${contestant_b['setpoints']}</td>
                            </tr>
                            </tbody>`;
                        }else{
                            str = `<h3 class="text-center text-light">-</h3>`;
                        }
                    } else {
                        str = `<h3 class="text-center text-light">-</h3>`;
                    }
                    $("#gameset-info-modal-table").html(str);
                    $("#gameset-info-modal").modal();
                    // if (data.status) {
                    //     $("#gameset-info-modal-table").html(
                    //         data.gameset["summary"]
                    //     );
                    //     $("#gameset-info-modal").modal();
                    // }
                }
            });
        },
        loadTable: function(table) {
            var str = '';
            if(table.length > 0){
                for(i=0; i<table.length; i++){
                    var statusTxt = "", gamestatus_id = table[i]['gamestatus_id'];
                    if (gamestatus_id == 2) {
                        statusTxt += `disabled="disabled"`;
                    }

                    var item = `<tr>
                    <td class="text-gray-4 border-gray-3 pl-0">
                    <button data-gamesetid="${table[i]['id']}" ${statusTxt} class="btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder gameset-delete-btn-cls">X</button>
                    <button data-gamesetid="${table[i]['id']}" ${statusTxt} class="btn btn-sm btn-outline-warning-2 border-0 rounded-circle gameset-update-btn-cls"><i class="fas fa-pen"></i></button>
                    </td>
                    <td class="text-info font-weight-light border-gray-3">
                        ${table[i]['game_num']}. <span class="small">[${(table[i]['bowstyle_name']).toUpperCase()}]</span>
                    </td>
                    <td class="text-gray-4 font-weight-light border-gray-3">
                        ${table[i]['contestant_a_name']} vs ${table[i]['contestant_b_name']}
                    </td>
                    <td class="text-gray-4 font-weight-light border-gray-3">${table[i]['set_num']}</td>
                    <td class="text-gray-4 font-weight-light border-gray-3">`;
                    if ( gamestatus_id < 3) {
                        if ( table[i]['gamestatus_name'] == "Live") {
                            item += `<button data-gamesetid="${table[i]['id']}" class="btn btn-sm btn-danger rounded-circle gameset-stoplive-btn-cls mr-3"><i class="fas fa-stop-circle"></i></button>`;
                        } else {
                            item += `<button data-gamesetid="${table[i]['id']}" class="btn btn-sm btn-success rounded-circle gameset-live-btn-cls mr-3"><i class="fas fa-play-circle"></i></button>`;
                        }
                    } else {
                        item += `<button data-gamesetid="${table[i]['id']}" class="btn btn-sm btn-primary rounded-circle gameset-view-btn-cls mr-3"><i class="fas fa-eye"></i></button>`;
                    }
                    item += `<span class="small">${table[i]['gamestatus_name']}</span>
                    </td>
                    </tr>`;

                    str += item;
                }
            }else{
                str = `<td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>
                <td class="text-white font-weight-light border-info">-</td>`;
            }
            $("#gameset-table tbody").html(str);
            // if (gameset_table != "")
            //     $("#gameset-table tbody").html(gameset_table);
        },
        loadForm: function(gamesetdata, modeget) {
            var modalTitle = "";
            if (modeget == "update") {
                modalTitle += "Update";
                $("#gameset-status-area").removeClass("hide");
                $("#gameset-gamedraw")
                    .val(gamesetdata.gamedraw_id)
                    .removeAttr("disabled");
                $("#gameset-setnum")
                    .val(gamesetdata.num)
                    .removeAttr("disabled");
                $("#gameset-status")
                    .val(gamesetdata.gameset_status)
                    .removeAttr("disabled");
                $("#gameset-prev-status").val(gamesetdata.gameset_status);
                $("#gameset-id").val(gamesetdata.id);
                $("#gameset-action").val("update");
                $("#gameset-submit").val("Save");
            } else if (modeget == "create") {
                modalTitle += "New";
                $("#gameset-status-area").addClass("hide");
                $("#gameset-gamedraw")
                    .val(0)
                    .removeAttr("disabled");
                $("#gameset-setnum")
                    .val(1)
                    .removeAttr("disabled");
                $("#gameset-status").val(0);
                $("#gameset-prev-status").val(0);
                $("#gameset-id").val(0);
                $("#gameset-action").val("create");
                $("#gameset-submit").val("Create");
            } else if (modeget == "delete") {
                modalTitle += "Delete";
                $("#gameset-status-area").removeClass("hide");
                $("#gameset-gamedraw")
                    .val(gamesetdata.gamedraw_id)
                    .attr("disabled", "disabled");
                $("#gameset-setnum")
                    .val(gamesetdata.num)
                    .attr("disabled", "disabled");
                $("#gameset-status")
                    .val(gamesetdata.gameset_status)
                    .attr("disabled", "disabled");
                $("#gameset-prev-status").val(gamesetdata.gameset_status);
                $("#gameset-id").val(gamesetdata.id);
                $("#gameset-action").val("delete");
                $("#gameset-submit").val("Delete");
            }
            modalTitle += " Game Set";
            $("#gameset-modal-title").html(modalTitle);
            $("#form-gameset-modal").modal();
        },
        ajaxSetupForm: function(gamesetid, modeget) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url:
                    "/scoreboard/controller.php?gameset_get=modal_data&id=" +
                    gamesetid,
                success: function(data) {
                    if (data.status) {
                        GameSet.loadForm(data.gameset['modal_form'], modeget);
                    }
                }
            });
        },
        printSummary: function() {
            printJS({
                printable: "gameset-info-modal-table",
                type: "html",
                //scanStyles: false,
                css: [
                    "http://localhost/scoreboard/bootstrap/css/bootstrap.min.css",
                    "http://localhost/scoreboard/css/style.css"
                ]
            });
        },
        startGame: function(e) {
            e.preventDefault();
            if (
                $("#score-a-timer-play").hasClass("play-on-cls") ||
                $("#score-b-timer-play").hasClass("play-on-cls")
            ) {
                alert("Please pause/stop timer");
            } else {
                var gameset = $(this).attr("data-gamesetid");
                $.post(
                    "controller.php",
                    {
                        livegame_action: "set-live-game",
                        gamesetid: gameset
                    },
                    function(data, status) {
                        var result = $.parseJSON(data);
                        if (result.status) {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url:
                                    "/scoreboard/controller.php?config_get=init",
                                success: function(data) {
                                    if (data.status) {
                                        FormScoreboard.loadForm(
                                            data.scoreboard_form
                                        );

                                        var scoreboard_styles =
                                            data.scoreboard_styles,
                                            bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                            style_name = scoreboard_styles.info['style_name'];
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.bowstyleSelect,
                                        //     scoreboard_styles["bowstyle"][
                                        //         "option"
                                        //     ]
                                        // );
                                        ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                        ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.styleSelect,
                                        //     scoreboard_styles["option"]
                                        // );
                                        ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                        ScoreboardStyle.loadPreview(
                                            scoreboard_styles["preview"]
                                        );
                                        ScoreboardStyle.configBtn(
                                            scoreboard_styles["config"]
                                        );
                                        GameSet.loadTable(
                                            data.gameset["table"]
                                        );
                                    }
                                }
                            });
                        }
                    }
                );
            }
        },
        stopGame: function(e) {
            e.preventDefault();
            if (
                $("#score-a-timer-play").hasClass("play-on-cls") ||
                $("#score-b-timer-play").hasClass("play-on-cls")
            ) {
                alert("Please pause/stop timer");
            } else {
                var gameset = $(this).attr("data-gamesetid");
                $.post(
                    "controller.php",
                    {
                        livegame_action: "stop-live-game",
                        gamesetid: gameset
                    },
                    function(data, status) {
                        var result = $.parseJSON(data);
                        if (result.status) {
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url:
                                    "/scoreboard/controller.php?config_get=init",
                                success: function(data) {
                                    if (data.status) {
                                        FormScoreboard.loadForm(
                                            data.scoreboard_form
                                        );

                                        var scoreboard_styles =
                                            data.scoreboard_styles,
                                            bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                            style_name = scoreboard_styles.info['style_name'];
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.bowstyleSelect,
                                        //     scoreboard_styles["bowstyle"][
                                        //         "option"
                                        //     ]
                                        // );
                                        // ScoreboardStyle.setOption(
                                        //     ScoreboardStyle.styleSelect,
                                        //     scoreboard_styles["option"]
                                        // );
                                        ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                        ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                        ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                        ScoreboardStyle.loadPreview(
                                            scoreboard_styles["preview"]
                                        );
                                        ScoreboardStyle.configBtn(
                                            scoreboard_styles["config"]
                                        );
                                        GameSet.loadTable(
                                            data.gameset["table"]
                                        );
                                    }
                                }
                            });
                        }
                    }
                );
            }
        },
        submitForm: function(e) {
            e.preventDefault();

            if (request) {
                request.abort();
            }

            var $form = $(this);
            var serializedData = $form.serialize();

            request = $.ajax({
                url: "/scoreboard/controller.php",
                type: "POST",
                data: serializedData
            });

            request.done(function(response, textStatus, jqXHR) {
                var data = $.parseJSON(response);
                if (data.status) {
                    if (data.action == "create") {
                        $("#gameset-gamedraw")
                            .val(0)
                            .removeAttr("disabled");
                        $("#gameset-setnum")
                            .val(1)
                            .removeAttr("disabled");
                        $("#gameset-id").val(0);
                        $("#gameset-action").val("create");

                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?gameset_get=new",
                            success: function(data) {
                                if (data.status) {
                                    GameSet.loadTable(data.gameset["table"]);
                                }
                            }
                        });
                    } else if (
                        data.action == "update" ||
                        data.action == "delete"
                    ) {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "/scoreboard/controller.php?config_get=init",
                            success: function(data) {
                                if (data.status) {
                                    FormScoreboard.loadForm(
                                        data.scoreboard_form
                                    );

                                    var scoreboard_styles =
                                        data.scoreboard_styles,
                                        bowstyle_name = scoreboard_styles.info['bowstyle_name'],
                                        style_name = scoreboard_styles.info['style_name'];
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.bowstyleSelect,
                                    //     scoreboard_styles["bowstyle"]["option"]
                                    // );
                                    // ScoreboardStyle.setOption(
                                    //     ScoreboardStyle.styleSelect,
                                    //     scoreboard_styles["option"]
                                    // );
                                    ScoreboardStyle.setBowstyleOption(scoreboard_styles.bowstyle['option']);
                                    ScoreboardStyle.setStyleOption(scoreboard_styles.option);
                                    ScoreboardStyle.setInfo(bowstyle_name,style_name);
                                    ScoreboardStyle.loadPreview(
                                        scoreboard_styles["preview"]
                                    );
                                    ScoreboardStyle.configBtn(
                                        scoreboard_styles["config"]
                                    );
                                    GameSet.loadTable(data.gameset["table"]);

                                    $("#form-gameset-modal").modal("hide");
                                }
                            }
                        });
                    }
                }
            });
        }
    };

    // InitSetup();
    App.init();

    // function DisableScoreboard() {
    //     DisableScoreA();
    //     DisableScoreB();
    // }

    // function EnableScoreboard() {
    //     EnableScoreA();
    //     EnableScoreB();
    // }

    // function EnableScoreA() {
    //     $("#form-score-a :input").prop("disabled", false);
    //     $("#score-a-timer-pause").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
    //     $("#score-a-timer-play").removeClass("btn-outline-primary").addClass("btn-primary").removeAttr("disabled").removeClass("play-on-cls");
    // }

    // function EnableScoreB() {
    //     $("#form-score-b :input").prop("disabled", false);
    //     $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
    //     $("#score-b-timer-play").removeClass("btn-outline-success").addClass("btn-success").removeAttr("disabled").removeClass("play-on-cls");
    // }

    // function DisableScoreA() {
    //     $("#form-score-a :input").prop("disabled", true);
    //     $("#score-a-timer-play").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
    //     $("#score-a-timer-pause").removeClass("btn-primary").addClass("btn-outline-primary").attr("disabled", "disabled");
    // }

    // function DisableScoreB() {
    //     $("#form-score-b :input").prop("disabled", true);
    //     $("#score-b-timer-play").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
    //     $("#score-b-timer-pause").removeClass("btn-success").addClass("btn-outline-success").attr("disabled", "disabled");
    // }

    // $("a.btn-menu").click(function (e) {
    //     var coll_id = $(this).attr("aria-controls");
    //     if ($("#" + coll_id).hasClass("show")) {
    //         $(this).children().children(".caret-cls").removeClass('fa-caret-up');
    //         $(this).children().children(".caret-cls").addClass('fa-caret-down');
    //     } else {
    //         $(this).children().children(".caret-cls").removeClass('fa-caret-down');
    //         $(this).children().children(".caret-cls").addClass('fa-caret-up');
    //     }
    // });
});

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
    timer = setInterval(function() {
        if (counterA < 0) counterA = 0;
        $("#score-a-timer").val(counterA + "s");
        $.post(
            "controller.php",
            {
                score_timer_action: "update-timer-a",
                score_a_id: $("#score-a-id").val(),
                timer_a: counterA
            },
            function(data, status) {
                // console.log(data);
            }
        );
        counterA--;
    }, interval);
}

function PlayTimerB() {
    if (timerB !== null) return;
    timerB = setInterval(function() {
        if (counterB < 0) counterB = 0;
        $("#score-b-timer").val(counterB + "s");
        $.post(
            "controller.php",
            {
                score_timer_action: "update-timer-b",
                score_b_id: $("#score-b-id").val(),
                timer_b: counterB
            },
            function(data, status) {
                // console.log(data);
            }
        );
        counterB--;
    }, interval);
}
$("#score-a-timer-pause").click(function(e) {
    e.preventDefault();
    $("#score-a-submit").removeAttr("disabled");
    $(this)
        .removeClass("btn-primary")
        .addClass("btn-outline-dark")
        .removeClass("text-light")
        .addClass("text-primary")
        .addClass("border-primary")
        .attr("disabled", "disabled");
    $("#score-a-timer-play")
        .removeClass("btn-outline-dark")
        .addClass("text-light")
        .addClass("btn-primary")
        .removeAttr("disabled")
        .removeClass("play-on-cls");
    PauseTimerA();
});
$("#score-a-timer-play").click(function(e) {
    e.preventDefault();
    $("#score-a-submit").attr("disabled", "disabled");
    counterA = parseInt(
        $("#score-a-timer")
            .val()
            .replace("s", "")
    );
    $(this)
        .removeClass("btn-primary")
        .addClass("btn-outline-dark")
        .removeClass("text-light")
        .addClass("text-primary")
        .addClass("border-primary")
        .attr("disabled", "disabled")
        .addClass("play-on-cls");
    $("#score-a-timer-pause")
        .removeClass("btn-outline-dark")
        .addClass("text-light")
        .addClass("btn-primary")
        .removeAttr("disabled");
    PlayTimerA();
});
$("#score-b-timer-pause").click(function(e) {
    e.preventDefault();
    $("#score-b-submit").removeAttr("disabled");
    $(this)
        .removeClass("btn-success")
        .addClass("btn-outline-dark")
        .removeClass("text-light")
        .addClass("text-success")
        .addClass("border-success")
        .attr("disabled", "disabled");
    $("#score-b-timer-play")
        .removeClass("btn-outline-dark")
        .addClass("text-light")
        .addClass("btn-success")
        .removeAttr("disabled")
        .removeClass("play-on-cls");
    PauseTimerB();
});
$("#score-b-timer-play").click(function(e) {
    e.preventDefault();
    $("#score-b-submit").attr("disabled", "disabled");
    $(this)
        .removeClass("btn-success")
        .addClass("btn-outline-dark")
        .removeClass("text-light")
        .addClass("text-success")
        .addClass("border-success")
        .attr("disabled", "disabled")
        .addClass("play-on-cls");
    $("#score-b-timer-pause")
        .removeClass("btn-outline-dark")
        .addClass("text-light")
        .addClass("btn-success")
        .removeAttr("disabled");
    counterB = parseInt(
        $("#score-b-timer")
            .val()
            .replace("s", "")
    );
    PlayTimerB();
});
