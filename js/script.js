$(document).ready(function () {

    var request;

    var App = {
        init: function () {
            var urls = [
                '/scoreboard/controller.php?config_get=init',
                '/scoreboard/controller.php?config_get=setup',
            ];
            var actNames = [
                'CONFIG_GET',
                'CONFIG_SET',
            ];
            App.ajaxGetReq(urls, actNames);
            Team.init();
            Player.init();
            GameDraw.init();
            GameSet.init();
            FormScoreboard.init();
            ScoreboardStyle.init();
        },
        ajaxGetReq: function (urls, actNames) {
            if (urls.length > 0 && actNames.length > 0) {
                var act = actNames.pop();
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: urls.pop(),
                    success: function (data) {
                        if (data.status) {
                            if (act == 'CONFIG_GET') {
                                FormScoreboard.loadForm(data.form_scoreboard);
                                GameStatus.loadOption(data.gamestatuses['options']);
                                GameMode.loadRadio(data.gamemodes['radios']);
                                BowStyle.loadRadio(data.bowstyles['radios']);
                                ScoreboardStyle.loadOption(data.scoreboard_styles['options']);
                                ScoreboardStyle.loadInfo(data.scoreboard_styles['info']);
                                ScoreboardStyle.loadPreview(data.scoreboard_styles['preview']);
                                ScoreboardStyle.loadConfig(data.scoreboard_styles['config']);
                                Team.loadTable(data.teams['table']);
                                Team.loadOption(data.teams['options']);
                                Player.loadTable(data.players['table']);
                                Player.loadOption(data.players['options']);
                                GameDraw.loadOption(data.gamedraws['options']);
                                GameDraw.loadTable(data.gamedraws['table']);
                                GameSet.loadTable(data.gamesets['table']);
                            }
                            else if (act == 'LIVESCORE_GET') {
                                FormScoreboard.loadForm(data.form_scoreboard);
                                Team.loadTable(data.teams['table']);
                                Team.loadOption(data.teams['options']);
                                Player.loadTable(data.players['table']);
                                Player.loadOption(data.players['options']);
                                GameDraw.loadOption(data.gamedraws['options']);
                                GameDraw.loadTable(data.gamedraws['table']);
                                GameSet.loadTable(data.gamesets['table']);
                            }
                            else if (act == 'LIVEGAME_START' || act == 'LIVEGAME_STOP') {
                                FormScoreboard.loadForm(data.form_scoreboard);
                                GameSet.loadTable(data.gamesets['table']);
                                ScoreboardStyle.selectedOption( $("#scoreboard-style-bowstyle-select"), data.scoreboard_styles['options']['bowstyle_selected']);
                                ScoreboardStyle.renderOption( $("#scoreboard-style-style-select"), data.scoreboard_styles['options']['style']);
                                ScoreboardStyle.loadInfo(data.scoreboard_styles['info']);
                                ScoreboardStyle.loadPreview(data.scoreboard_styles['preview']);
                                ScoreboardStyle.loadConfig(data.scoreboard_styles['config']);
                            }else if ( act == 'SCORE_UPDATE' ) {
                                ScoreboardStyle.loadPreview(data.scoreboard_styles['preview']);
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
        parseNum: function (str) {
            if (str == '') {
                return 0;
            }
            return parseInt(str);
        },
        setHide: function (element, toggleClass) {
            if (toggleClass == 'hide') {
                if (!element.hasClass('hide')) {
                    element.addClass('hide');
                }
            } else {
                if (element.hasClass('hide')) {
                    element.removeClass('hide');
                }
            }
        }
    };

    var FormScoreboard = {
        init: function () {
            FormScoreboard.bindEvents();
        },
        bindEvents: function () {
            $(document).on('submit', '#form-scoreboard-a', FormScoreboard.submitForm);
            $(document).on('submit', '#form-scoreboard-b', FormScoreboard.submitForm);
            $(document).on('input', '#score-a-setpoints', FormScoreboard.calculateGamePoints);
            $(document).on('input', '#score-b-setpoints', FormScoreboard.calculateGamePoints);
            $(document).on('input', '.score-a-input-cls', FormScoreboard.calculateScoreA);
            $(document).on('input', '.score-b-input-cls', FormScoreboard.calculateScoreB);
        },
        calculateScoreA: function() {
            FormScoreboard.calculate('a');
        },
        calculateScoreB: function() {
            FormScoreboard.calculate('b');
        },
        calculate: function(owner) {
            var prev_total = parseNum($("#score-" + owner + "-total").val());
            var prev_game_total_points = parseNum($("#score-" + owner + "-gametotal").val());
            var total_point = parseNum($("#score-" + owner + "-pt1").val()) + parseNum($("#score-" + owner + "-pt2").val()) + parseNum($("#score-" + owner + "-pt3").val()) + parseNum($("#score-" + owner + "-pt4").val()) + parseNum($("#score-" + owner + "-pt5").val()) + parseNum($("#score-" + owner + "-pt6").val());
            $("#score-" + owner + "-total").val(total_point);
            var game_total_points = prev_game_total_points + (total_point - prev_total);
            $("#score-" + owner + "-gametotal").val(game_total_points);
        },
        loadForm: function (form_scoreboard) {
            $("#form-scoreboard-wrapper").html(form_scoreboard);
        },
        calculateGamePoints: function () {
            if (this.id == "score-a-setpoints") {
                FormScoreboard.setGamePoints($("#score-a-setpoints"), $("#score-a-gamepoints"));
            } else if (this.id == "score-b-setpoints") {
                FormScoreboard.setGamePoints($("#score-b-setpoints"), $("#score-b-gamepoints"));
            }
        },
        setGamePoints: function (sender, elementTarget) {
            var prev_setpoints = parseNum(sender.attr("data-setpoints"));
            var prev_gamepoints = parseNum(elementTarget.attr("data-gamepoints"));
            var result = prev_gamepoints - prev_setpoints + parseNum(sender.val());
            elementTarget.val(result);
        },
        resetForm: function () {

            $("#score-a-logo").attr("src", "uploads/no-image.png");
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

            $("#score-b-logo").attr("src", "uploads/no-image.png");
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
        },
        submitForm: function (e) {
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
                        var urls = [
                            // '/scoreboard/controller.php?gameset_get=list',
                            // '/scoreboard/controller.php?gamedraw_get=list',
                            // '/scoreboard/controller.php?player_get=list',
                            // '/scoreboard/controller.php?team_get=list',
                            '/scoreboard/controller.php?score_get=live&gameset=1&preview=1',
                        ];
                        var actNames = [
                            // 'GAMESET_LIST',
                            // 'GAMEDRAW_LIST',
                            // 'PLAYER_LIST',
                            // 'TEAM_LIST',
                            'SCORE_UPDATE',
                        ];
                        App.ajaxGetReq(urls, actNames);
                    }
                }
            });
        }
    };

    var GameStatus = {
        loadOption: function (gamestatus_options) {
            $("#gameset-status").html(gamestatus_options);
        }
    };

    var GameMode = {
        loadRadio: function (gamemode_radios) {
            $("#gamedraw-radio-area").html(gamemode_radios);
        }
    };

    var BowStyle = {
        loadRadio: function (bowstyle_radios) {
            $("#gamedraw-radio-bowstyle-area").html(bowstyle_radios);
        }
    };

    var ScoreboardStyle = {
        init: function () {
            ScoreboardStyle.bindEvents();
        },
        bindEvents: function () {
            $("#form-scoreboard-style").on('submit', ScoreboardStyle.submitForm);
            $("#ssv-collective-cb").change(ScoreboardStyle.toggleCollectiveCheckBox);
            $(document).on("change", "input.ssv-cb", ScoreboardStyle.toggleCheckBox);
            $("#scoreboard-style-activate").click(ScoreboardStyle.activateStyle);
            $("#scoreboard-style-btn-create").click(ScoreboardStyle.setupCreateForm);
            $("#scoreboard-style-btn-edit").click(ScoreboardStyle.setupUpdateForm);
            $("#scoreboard-style-btn-delete").click(ScoreboardStyle.setupDeleteForm);
            $("#scoreboard-style-cancel").click(ScoreboardStyle.cancelFormSetup);
            $(document).on("change", "#scoreboard-style-bowstyle-select", ScoreboardStyle.loadStyleOption);
            $(document).on("change", "#scoreboard-style-style-select", ScoreboardStyle.setupPreview);
        },
        activateStyle: function (e) {
            e.preventDefault();

            $.post("controller.php", {
                scoreboard_style_action: 'set-scoreboard-style',
                style: $("#scoreboard-style-style-select").val()
            },
                function (data, status) {
                    var result = $.parseJSON(data);
                    if (result.status) {
                        var style_info = $("#scoreboard-style-style-select option:selected").text();
                        var bowstyle_info = $("#scoreboard-style-bowstyle-select option:selected").text();
                        var info = [];
                        info['bowstyle'] = bowstyle_info;
                        info['style'] = style_info;
                        ScoreboardStyle.loadInfo(info)
                        // $("#scoreboard-style-active-bowstyle-style-info").html(style_info);
                        // $("#scoreboard-style-active-bowstyle-info").html(bowstyle_info);
                    }
                }
            );
        },
        toggleCheckBox: function () {
            var cls = $(this).attr('data-class');
            var status = $(this).prop("checked");
            if (status) {
                $("." + cls).removeClass('hide');
                $(this).val(true);
            } else {
                $("." + cls).addClass("hide");
                $(this).val(false);
                $("#ssv-collective-cb").prop("checked", false);
            }
        },
        toggleCollectiveCheckBox: function () {
            var target = $("input.ssv-cb");
            var status = $(this).prop("checked");
            if (status) {
                target.prop("checked", true).val(true);
                $("#scoreboard-style-preview table td").removeClass('hide');
            } else {
                target.prop("checked", false).val(false);
                $("#scoreboard-style-preview table td").addClass("hide");
            }
        },
        setupCreateForm: function (e) {
            e.preventDefault();

            var form_mode = $("#form-scoreboard-style-mode");
            var form_action = $("#scoreboard-style-action");

            var bowstyle_select = $("#scoreboard-style-bowstyle-select");
            var bowstyle_value = bowstyle_select.val();
            var style_select = $("#scoreboard-style-style-select");

            var activate_button = $("#scoreboard-style-activate");

            var submit_button = $("#scoreboard-style-submit");
            var cancel_button = $("#scoreboard-style-cancel");

            var create_button = $(this);
            var edit_button = $("#scoreboard-style-btn-edit");
            var delete_button = $("#scoreboard-style-btn-delete");

            var form_visibility_option_container = $("#form-scoreboard-style-visibility");

            var scoreboard_style_preview = $("#scoreboard-style-preview");
            var scoreboard_style_preview_table = $("#scoreboard-style-preview table");

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?scoreboard_style_get=new_num&bowstyle_id=' + bowstyle_value,
                success: function (data) {
                    if (data.status) {
                        form_mode.val("create");
                        form_action.val("create");

                        style_select.html('<option value="' + data.new_num + '">' + data.new_num + '</option>');

                        Helper.setHide(activate_button, 'hide');

                        submit_button.val('Save').removeClass('btn-danger').addClass('btn-primary');
                        Helper.setHide(submit_button, '');
                        Helper.setHide(cancel_button, '');

                        Helper.setHide(create_button, 'hide');
                        Helper.setHide(edit_button, 'hide');
                        Helper.setHide(delete_button, 'hide');

                        $("#table-style-visibility").html(data.checkbox);
                        Helper.setHide(form_visibility_option_container, '');

                        scoreboard_style_preview_table.html(data.style_preview);
                        Helper.setHide(scoreboard_style_preview, '');

                    } else {
                    }
                }
            });
        },
        setupUpdateForm: function (e) {
            e.preventDefault();

            var form_mode = $("#form-scoreboard-style-mode");
            var form_action = $("#scoreboard-style-action");

            var bowstyle_select = $("#scoreboard-style-bowstyle-select");
            var style_select = $("#scoreboard-style-style-select");

            var activate_button = $("#scoreboard-style-activate");

            var submit_button = $("#scoreboard-style-submit");
            var cancel_button = $("#scoreboard-style-cancel");

            var create_button = $("#scoreboard-style-btn-create");
            var delete_button = $("#scoreboard-style-btn-delete");

            var form_visibility_option_container = $("#form-scoreboard-style-visibility");

            // table-style-visibility

            var style_id = style_select.val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?style_config_get=checkbox&style_id=' + style_id,
                success: function (data) {
                    if (data.status) {
                        $("#table-style-visibility").html(data.checkbox);

                        form_mode.val('update');
                        form_action.val('update');

                        bowstyle_select.attr('disabled', 'disabled');
                        style_select.attr('disabled', 'disabled');

                        Helper.setHide(activate_button, 'hide');

                        submit_button.val('Save').removeClass('btn-danger').addClass('btn-primary');
                        Helper.setHide(submit_button, '');
                        Helper.setHide(cancel_button, '');

                        Helper.setHide(create_button, 'hide');
                        Helper.setHide(delete_button, 'hide');
                        Helper.setHide($("#scoreboard-style-btn-edit"), 'hide');

                        Helper.setHide(form_visibility_option_container, '');
                    }
                }
            });
        },
        setupDeleteForm: function (e) {
            e.preventDefault();

            var form_mode = $("#form-scoreboard-style-mode");
            var form_action = $("#scoreboard-style-action");

            var bowstyle_select = $("#scoreboard-style-bowstyle-select");
            var style_select = $("#scoreboard-style-style-select");

            var activate_button = $("#scoreboard-style-activate");

            var submit_button = $("#scoreboard-style-submit");
            var cancel_button = $("#scoreboard-style-cancel");

            var create_button = $("#scoreboard-style-btn-create");
            var edit_button = $("#scoreboard-style-btn-edit");

            form_mode.val('delete');
            form_action.val('delete');

            bowstyle_select.attr('disabled', 'disabled');
            style_select.attr('disabled', 'disabled');

            Helper.setHide(activate_button, 'hide');

            submit_button.val('Delete').removeClass('btn-primary').addClass('btn-danger');
            Helper.setHide(submit_button, '');
            Helper.setHide(cancel_button, '');

            Helper.setHide(create_button, 'hide');
            Helper.setHide(edit_button, 'hide');
            Helper.setHide($("#scoreboard-style-btn-delete"), 'hide');
        },
        cancelFormSetup: function (e) {
            e.preventDefault();

            var form_mode = $("#form-scoreboard-style-mode");
            var form_mode_value = $("#form-scoreboard-style-mode").val();
            var form_action = $("#scoreboard-style-action");

            var bowstyle_select = $("#scoreboard-style-bowstyle-select");
            var bowstyle_value = bowstyle_select.val();
            var bowstyle_has_value = bowstyle_value > 0;
            var style_select = $("#scoreboard-style-style-select");
            var style_value = style_select.val();

            var activate_button = $("#scoreboard-style-activate");

            var submit_button = $("#scoreboard-style-submit");
            var cancel_button = $("#scoreboard-style-cancel");

            var create_button = $("#scoreboard-style-btn-create");
            var edit_button = $("#scoreboard-style-btn-edit");
            var delete_button = $("#scoreboard-style-btn-delete");

            var form_visibility_option_container = $("#form-scoreboard-style-visibility");

            var scoreboard_style_preview = $("#scoreboard-style-preview");
            var scoreboard_style_preview_table = $("#scoreboard-style-preview table");

            if (form_mode_value == 'create') {

                form_mode.val('view');
                form_action.val('view');

                Helper.setHide(scoreboard_style_preview, 'hide');

                Helper.setHide(form_visibility_option_container, 'hide');

                Helper.setHide(create_button, '');

                Helper.setHide(cancel_button, 'hide');

                if (bowstyle_has_value) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=' + bowstyle_value,
                        success: function (data) {
                            if (data.status) {

                                Helper.setHide(submit_button, 'hide');

                                style_select
                                    .removeAttr('disabled')
                                    .html(data.scoreboard_style_option)
                                    .val(0);
                            }
                        }
                    });
                }
                else {
                    submit_button.removeAttr('disabled');
                    Helper.setHide(submit_button, 'hide');
                }
            }
            else if (form_mode_value == 'update') {

                form_mode.val('view');
                form_action.val('view');

                bowstyle_select.removeAttr('disabled');
                style_select.removeAttr('disabled');

                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/scoreboard/controller.php?scoreboard_style_get=single&id=' + style_value,
                    success: function (data) {
                        if (data.status) {

                            // var logo_vis = data.style_config['logo']['visibility'],
                            //     team_vis = data.style_config['team']['visibility'],
                            //     player_vis = data.style_config['player']['visibility'],
                            //     timer_vis = data.style_config['timer']['visibility'],
                            //     score1_vis = data.style_config['score1']['visibility'],
                            //     score2_vis = data.style_config['score2']['visibility'],
                            //     score3_vis = data.style_config['score3']['visibility'],
                            //     score4_vis = data.style_config['score4']['visibility'],
                            //     score5_vis = data.style_config['score5']['visibility'],
                            //     score6_vis = data.style_config['score6']['visibility'],
                            //     setscore_vis = data.style_config['setscore']['visibility'],
                            //     gamescore_vis = data.style_config['gamescore']['visibility'],
                            //     setpoint_vis = data.style_config['setpoint']['visibility'],
                            //     gamepoint_vis = data.style_config['gamepoint']['visibility'],
                            //     description_vis = data.style_config['description']['visibility'];

                            // $("#ssv-logo-cb").prop('checked', logo_vis).val(logo_vis);
                            // $("#ssv-team-cb").prop('checked', team_vis).val(team_vis);
                            // $("#ssv-player-cb").prop('checked', player_vis).val(player_vis);
                            // $("#ssv-timer-cb").prop('checked', timer_vis).val(timer_vis);
                            // $("#ssv-score1-cb").prop('checked', score1_vis).val(score1_vis);
                            // $("#ssv-score2-cb").prop('checked', score2_vis).val(score2_vis);
                            // $("#ssv-score3-cb").prop('checked', score3_vis).val(score3_vis);
                            // $("#ssv-score4-cb").prop('checked', score4_vis).val(score4_vis);
                            // $("#ssv-score5-cb").prop('checked', score5_vis).val(score5_vis);
                            // $("#ssv-score6-cb").prop('checked', score6_vis).val(score6_vis);
                            // $("#ssv-setscore-cb").prop('checked', setscore_vis).val(setscore_vis);
                            // $("#ssv-gamescore-cb").prop('checked', gamescore_vis).val(gamescore_vis);
                            // $("#ssv-setpoint-cb").prop('checked', setpoint_vis).val(setpoint_vis);
                            // $("#ssv-gamepoint-cb").prop('checked', gamepoint_vis).val(gamepoint_vis);
                            // $("#ssv-description-cb").prop('checked', description_vis).val(description_vis);

                            $("#table-style-visibility").html(data.style_preview_checkbox);
                            scoreboard_style_preview_table.html(data.style_preview);

                            Helper.setHide(activate_button, '');

                            Helper.setHide(submit_button, 'hide');
                            Helper.setHide(cancel_button, 'hide');

                            Helper.setHide(create_button, '');
                            Helper.setHide(edit_button, '');
                            Helper.setHide(delete_button, '');

                            Helper.setHide(form_visibility_option_container, 'hide');
                        }
                    }
                });
            }
            else if (form_mode_value == 'delete') {

                form_mode.val('view');
                form_action.val('view');

                bowstyle_select.removeAttr('disabled');
                style_select.removeAttr('disabled');

                Helper.setHide(activate_button, '');

                Helper.setHide(submit_button, 'hide');
                Helper.setHide(cancel_button, 'hide');

                Helper.setHide(create_button, '');
                Helper.setHide(edit_button, '');
                Helper.setHide(delete_button, '');
            }
        },
        loadStyleOption: function () {
            var form_mode_value = $("#form-scoreboard-style-mode").val();

            var value = $(this).val();
            var has_value = $(this).val() > 0;
            var style_select = $("#scoreboard-style-style-select");
            var style_has_value = style_select.val() > 0;

            var submit_button = $("#scoreboard-style-submit");

            var create_button = $("#scoreboard-style-btn-create");

            if (form_mode_value == 'view') {
                if (has_value) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=' + value,
                        success: function (data) {
                            if (data.status) {
                                style_select
                                    .removeAttr('disabled')
                                    .html(data.scoreboard_style_option)
                                    .val(0);

                                Helper.setHide(create_button, '');

                                if (data.has_warning) {
                                    $("#scoreboard-style-message").html(data.message);
                                } else {
                                    $("#scoreboard-style-message").html('');
                                }
                            }
                        }
                    });
                }
                else {
                    if (style_has_value) {
                        style_select
                            .val(0)
                            .html('<option value="0">Choose</option>');
                    }
                    style_select.attr('disabled', 'disabled');

                    Helper.setHide(create_button, 'hide');
                }
            }
            else if (form_mode_value == 'create') {
                if (has_value) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/scoreboard/controller.php?scoreboard_style_get=new_num&bowstyle_id=' + value,
                        success: function (data) {
                            if (data.status) {
                                style_select
                                    .removeAttr('disabled')
                                    .html('<option value="' + data.new_num + '">' + data.new_num + '</option>');

                                submit_button.removeAttr('disabled');
                            }
                        }
                    });
                }
                else {
                    if (style_has_value) {
                        style_select
                            .val(0)
                            .html('<option value="0">Choose</option>');
                    }
                    style_select.attr('disabled', 'disabled');

                    submit_button.attr('disabled', 'disabled');
                }
            }
            else if (form_mode_value == 'update') { }
            else if (form_mode_value == 'delete') { }
        },
        loadConfig: function (cfg) {
            Helper.setHide($("#scoreboard-style-activate"), cfg['visibility_class']['activate_btn']);
            Helper.setHide($("#scoreboard-style-submit"), cfg['visibility_class']['save_btn']);
            Helper.setHide($("#scoreboard-style-cancel"), cfg['visibility_class']['cancel_btn']);
            Helper.setHide($("#scoreboard-style-btn-create"), cfg['visibility_class']['new_btn']);
            Helper.setHide($("#scoreboard-style-btn-edit"), cfg['visibility_class']['edit_btn']);
            Helper.setHide($("#scoreboard-style-btn-delete"), cfg['visibility_class']['delete_btn']);
        },
        loadPreview: function (preview) {
            $("#scoreboard-style-preview table").html(preview.view);
            // $("#table-style-visibility").html(preview.checkbox);
            if (preview.view == '') {
                $("#scoreboard-style-preview").addClass('hide');
            } else {
                $("#scoreboard-style-preview").removeClass('hide');
            }
        },
        setupPreview: function () {
            var form_mode_value = $("#form-scoreboard-style-mode").val();

            var value = $(this).val();
            var has_value = $(this).val() > 0;
            var bowstyle_select = $("#scoreboard-style-bowstyle-select");

            var activate_button = $("#scoreboard-style-activate");

            var edit_button = $("#scoreboard-style-btn-edit");
            var delete_button = $("#scoreboard-style-btn-delete");

            var scoreboard_style_preview = $("#scoreboard-style-preview");
            var scoreboard_style_preview_table = $("#scoreboard-style-preview table");

            if (form_mode_value == 'view') {
                if (has_value) {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/scoreboard/controller.php?scoreboard_style_get=single&id=' + value,
                        success: function (data) {
                            if (data.status) {

                                Helper.setHide(activate_button, '');

                                Helper.setHide(edit_button, '');
                                Helper.setHide(delete_button, '');

                                // var logo_vis = data.style_config['logo']['visibility'],
                                //     team_vis = data.style_config['team']['visibility'],
                                //     player_vis = data.style_config['player']['visibility'],
                                //     timer_vis = data.style_config['timer']['visibility'],
                                //     score1_vis = data.style_config['score1']['visibility'],
                                //     score2_vis = data.style_config['score2']['visibility'],
                                //     score3_vis = data.style_config['score3']['visibility'],
                                //     score4_vis = data.style_config['score4']['visibility'],
                                //     score5_vis = data.style_config['score5']['visibility'],
                                //     score6_vis = data.style_config['score6']['visibility'],
                                //     setscore_vis = data.style_config['setscore']['visibility'],
                                //     gamescore_vis = data.style_config['gamescore']['visibility'],
                                //     setpoint_vis = data.style_config['setpoint']['visibility'],
                                //     gamepoint_vis = data.style_config['gamepoint']['visibility'],
                                //     description_vis = data.style_config['description']['visibility'];

                                // $("#ssv-logo-cb").prop('checked', logo_vis).val(logo_vis);
                                // $("#ssv-team-cb").prop('checked', team_vis).val(team_vis);
                                // $("#ssv-player-cb").prop('checked', player_vis).val(player_vis);
                                // $("#ssv-timer-cb").prop('checked', timer_vis).val(timer_vis);
                                // $("#ssv-score1-cb").prop('checked', score1_vis).val(score1_vis);
                                // $("#ssv-score2-cb").prop('checked', score2_vis).val(score2_vis);
                                // $("#ssv-score3-cb").prop('checked', score3_vis).val(score3_vis);
                                // $("#ssv-score4-cb").prop('checked', score4_vis).val(score4_vis);
                                // $("#ssv-score5-cb").prop('checked', score5_vis).val(score5_vis);
                                // $("#ssv-score6-cb").prop('checked', score6_vis).val(score6_vis);
                                // $("#ssv-setscore-cb").prop('checked', setscore_vis).val(setscore_vis);
                                // $("#ssv-gamescore-cb").prop('checked', gamescore_vis).val(gamescore_vis);
                                // $("#ssv-setpoint-cb").prop('checked', setpoint_vis).val(setpoint_vis);
                                // $("#ssv-gamepoint-cb").prop('checked', gamepoint_vis).val(gamepoint_vis);
                                // $("#ssv-description-cb").prop('checked', description_vis).val(description_vis);

                                $("#table-style-visibility").html(data.style_preview_checkbox);
                                scoreboard_style_preview_table.html(data.style_preview);
                                Helper.setHide(scoreboard_style_preview, '');
                            }
                        }
                    });
                }
                else {
                    Helper.setHide(activate_button, 'hide');

                    Helper.setHide(edit_button, 'hide');
                    Helper.setHide(delete_button, 'hide');

                    Helper.setHide(scoreboard_style_preview, 'hide');
                }
            }
            else if (form_mode_value == 'create') { }
            else if (form_mode_value == 'update') { }
            else if (form_mode_value == 'delete') { }
        },
        loadCheckbox: function () { },
        renderOption: function (element, options){
            element.html(options);
        },
        loadOption: function (options) {
            $("#scoreboard-style-style-select").html(options['style']);
            $("#scoreboard-style-bowstyle-select").html(options['bowstyle']);
        },
        selectedOption: function( element, val) {
            element.val(val);
        },
        loadInfo: function (info) {
            var infoBowstyle = 'None',
                infoStyle = '';
            if (info['bowstyle'] != '') {
                infoBowstyle = info['bowstyle'] + ' - ';
                infoStyle = info['style']
            }
            $("#scoreboard-style-active-bowstyle-info").html(infoBowstyle);
            $("#scoreboard-style-active-bowstyle-style-info").html(infoStyle);
        },
        submitForm: function (e) {
            e.preventDefault();

            if (request) {
                request.abort();
            }

            var form_mode = $("#form-scoreboard-style-mode");
            var form_action = $("#scoreboard-style-action");

            var bowstyle_select = $("#scoreboard-style-bowstyle-select");
            var bowstyle_value = bowstyle_select.val();
            var style_select = $("#scoreboard-style-style-select");

            var activate_button = $("#scoreboard-style-activate");

            var submit_button = $("#scoreboard-style-submit");
            var cancel_button = $("#scoreboard-style-cancel");

            var create_button = $("#scoreboard-style-btn-create");
            var edit_button = $("#scoreboard-style-btn-edit");
            var delete_button = $("#scoreboard-style-btn-delete");

            var form_visibility_option_container = $("#form-scoreboard-style-visibility");

            var scoreboard_style_preview = $("#scoreboard-style-preview");

            var $form = $(this);

            $form.find('select').removeAttr('disabled');
            // var $input = $form.find("input, select, button, textarea");
            // var serializedData = $form.serialize();
            var serializedData = $form.serializeArray();

            $('#form-scoreboard-style input.ssv-cb:not(:checked)').each(function (i, e) {
                serializedData.push({ name: this.name, value: this.checked ? this.value : 'false' });
            });

            request = $.ajax({
                url: "/scoreboard/controller.php",
                type: "POST",
                data: serializedData,
            });

            request.done(function (response, textStatus, jqXHR) {
                var data = $.parseJSON(response);
                if (data.status) {
                    form_mode.val('view');
                    form_action.val('view');

                    if (data.action == 'create') {
                        $.ajax({
                            type: 'GET',
                            dataType: 'json',
                            url: '/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=' + bowstyle_value,
                            success: function (data2) {
                                if (data2.status) {

                                    Helper.setHide(form_visibility_option_container, 'hide');

                                    style_select
                                        .html(data2.scoreboard_style_option)
                                        .val(data.latest_id);

                                    Helper.setHide(submit_button, 'hide');
                                    Helper.setHide(cancel_button, 'hide');

                                    Helper.setHide(create_button, '');
                                    Helper.setHide(edit_button, '');
                                    Helper.setHide(delete_button, '');

                                    Helper.setHide(activate_button, '');
                                }
                            }
                        });
                    }
                    else if (data.action == 'update') {

                        Helper.setHide(form_visibility_option_container, 'hide');

                        Helper.setHide(submit_button, 'hide');
                        Helper.setHide(cancel_button, 'hide');

                        Helper.setHide(create_button, '');
                        Helper.setHide(edit_button, '');
                        Helper.setHide(delete_button, '');

                        Helper.setHide(activate_button, '');
                    }
                    else if (data.action == 'delete') {
                        $.ajax({
                            type: 'GET',
                            dataType: 'json',
                            url: '/scoreboard/controller.php?scoreboard_style_get=group&bowstyle_id=' + bowstyle_value,
                            success: function (data2) {
                                if (data2.status) {

                                    style_select
                                        .html(data2.scoreboard_style_option)
                                        .val(0);

                                    Helper.setHide(submit_button, 'hide');
                                    Helper.setHide(cancel_button, 'hide');

                                    Helper.setHide(create_button, '');

                                    Helper.setHide(scoreboard_style_preview, 'hide');

                                    var info = [];
                                    info['bowstyle'] = '';
                                    info['style'] = '';
                                    ScoreboardStyle.loadInfo(info)
                                }
                            }
                        });
                    }
                } else {

                }
            });
        },
    };

    var Team = {
        init: function () {
            Team.bindEvents();
        },
        bindEvents: function () {
            $("#create-team-button").click(Team.setupCreateForm);
            $(document).on('click', '.team-delete-btn-cls', Team.setupDeleteForm);
            $(document).on('click', '.team-update-btn-cls', Team.setupUpdateForm);
            $("#form-team").on('submit', Team.submitForm);
        },
        loadTable: function (team_table) {
            if (team_table != '') $("#team-table tbody").html(team_table);
        },
        loadOption: function (options) {
            if (options != '') {
                $("#player-team").html(options);
                $("#gamedraw-team-a").html(options);
                $("#gamedraw-team-b").html(options);
            }
        },
        loadForm: function (teamdata, modeget) {
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
        },
        setupCreateForm: function () {
            Team.loadForm(null, 'create');
        },
        setupUpdateForm: function (e) {
            e.preventDefault();
            var teamid = $(this).attr("data-teamid");
            Team.ajaxSetupForm(teamid, 'update');
        },
        setupDeleteForm: function (e) {
            e.preventDefault();
            var teamid = $(this).attr("data-teamid");
            Team.ajaxSetupForm(teamid, 'delete');
        },
        ajaxSetupForm: function (teamid, modeget) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?team_get=single&id=' + teamid,
                success: function (data) {
                    if (data.status) {
                        Team.loadForm(data.team, modeget);
                    }
                }
            });
        },
        submitForm: function (e) {
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
                        var urls = [
                            // '/scoreboard/controller.php?gameset_get=list',
                            // '/scoreboard/controller.php?gamedraw_get=list',
                            // '/scoreboard/controller.php?player_get=list',
                            // '/scoreboard/controller.php?team_get=list',
                            '/scoreboard/controller.php?score_get=live&team=1&player=1&gamedraw=1&gameset=1',
                        ];
                        var actNames = [
                            // 'GAMESET_LIST',
                            // 'GAMEDRAW_LIST',
                            // 'PLAYER_LIST',
                            // 'TEAM_LIST',
                            'LIVESCORE_GET',
                        ];
                        App.ajaxGetReq(urls, actNames);
                        if (result.action == 'update') {
                            if (result.new_logo != '') {
                                $("#team-modal-image").attr("src", "uploads/" + result.new_logo).removeClass("hide");
                            }
                        }
                        if (result.action == 'delete') {
                            $("#form-team-modal").modal("hide");
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
        }

    };

    var Player = {
        init: function () {
            Player.bindEvents();
        },
        bindEvents: function () {
            $("#create-player-button").click(Player.setupCreateForm);
            $(document).on('click', '.player-delete-btn-cls', Player.setupDeleteForm);
            $(document).on('click', '.player-update-btn-cls', Player.setupUpdateForm);
            $("#form-player").on('submit', Player.submitForm);
        },
        setupCreateForm: function () {
            Player.loadForm(false, 'create');
        },
        setupDeleteForm: function (e) {
            e.preventDefault();
            var playerid = $(this).attr("data-playerid");
            Player.ajaxSetupForm(playerid, 'delete');
        },
        setupUpdateForm: function (e) {
            e.preventDefault();
            var playerid = $(this).attr("data-playerid");
            Player.ajaxSetupForm(playerid, 'update');
        },
        loadTable: function (player_table) {
            if (player_table != '') $("#player-table tbody").html(player_table);
        },
        loadOption: function (options) {
            if (options != '') {
                $("#gamedraw-player-a").html(options);
                $("#gamedraw-player-b").html(options);
            }
        },
        loadForm: function (playerdata, modeget) {
            var modalTitle = "";
            if (modeget == 'update') {
                modalTitle += "Update";
                $("#player-name").val(playerdata.name).removeAttr("disabled");
                $("#player-team").val(playerdata.team_id).removeAttr("disabled");
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
                $("#player-team").val(playerdata.team_id).attr("disabled", "disabled");
                $("#player-id").val(playerdata.id);
                $("#player-action").val("delete");
                $("#player-submit").val("Delete");
            }
            modalTitle += " Player";
            $("#player-modal-title").html(modalTitle);
            $("#form-player-modal").modal();
        },
        ajaxSetupForm: function (playerid, modeget) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?player_get=single&id=' + playerid,
                success: function (data) {
                    if (data.status) {
                        Player.loadForm(data.player, modeget);
                    }
                }
            });
        },
        submitForm: function (e) {
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

                    var urls = [
                        '/scoreboard/controller.php?score_get=live&team=1&player=1&gamedraw=1&gameset=1',
                    ];
                    var actNames = [
                        'LIVESCORE_GET',
                    ];
                    App.ajaxGetReq(urls, actNames);
                    if (data.action == 'delete') {
                        $("#form-player-modal").modal("hide");
                    }
                }
            });
        }
    };

    var GameDraw = {
        init: function () {
            GameDraw.bindEvents();
        },
        bindEvents: function () {
            $("#create-gamedraw-button").click(GameDraw.setupCreateForm);
            $(document).on('click', '.gamedraw-delete-btn-cls', GameDraw.setupDeleteForm);
            $(document).on('click', '.gamedraw-update-btn-cls', GameDraw.setupUpdateForm);
            $(document).on('click', '.gamedraw-view-btn-cls', GameDraw.viewSummary);
            $("#form-gamedraw").on('submit', GameDraw.submitForm);
            $(document).on('click', '#gamedraw-summary-print', GameDraw.printSummary);
            $(document).on('change', '.gamedraw-gamemode-cls', GameDraw.setupOption);
            $(document).on('change', '.gamedraw-team-cls', GameDraw.filterTeamOption);
            $(document).on('change', '.gamedraw-player-cls', GameDraw.filterPlayerOption);
        },
        filterPlayerOption: function () {
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
        filterTeamOption: function () {
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
        setupOption: function () {

            $("select.gamedraw-team-cls").prop("selectedIndex", 0);
            $("select.gamedraw-player-cls").prop("selectedIndex", 0);
            if (this.id == "gamedraw-gamemode-beregu") {
                $(".gamedraw-player-opt-area-cls").addClass("hide");
                $(".gamedraw-team-opt-area-cls").removeClass("hide").addClass("show");
            }
            else if (this.id == "gamedraw-gamemode-individu") {
                $(".gamedraw-team-opt-area-cls").addClass("hide");
                $(".gamedraw-player-opt-area-cls").removeClass("hide").addClass("show");
            }
        },
        setupCreateForm: function () {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?gamedraw_get=new_num',
                success: function (data) {
                    if (data.status) {
                        $("#gamedraw-num").val(data.new_num);
                    } else {
                        $("#gamedraw-num").val(1);
                    }
                    GameDraw.loadForm(false, 'create');
                }
            });
        },
        setupDeleteForm: function (e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            GameDraw.ajaxSetupForm(gamedrawid, 'delete');
        },
        setupUpdateForm: function (e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            GameDraw.ajaxSetupForm(gamedrawid, 'update');
        },
        viewSummary: function (e) {
            e.preventDefault();
            var gamedrawid = $(this).attr("data-gamedrawid");
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?gamedraw_get=summary&id=' + gamedrawid,
                success: function (data) {
                    if (data.status) {
                        $("#gamedraw-summary").html(data.summaries);
                        $("#gamedraw-summary-print").removeAttr('disabled');
                    } else {
                        $("#gamedraw-summary").html('<h3 class="text-center text-light">-</h3>');
                        $("#gamedraw-summary-print").attr('disabled', 'disabled');
                    }
                    $("#gamedraw-info-modal").modal();
                }
            });
        },
        printSummary: function () {
            printJS({
                printable: 'gamedraw-summary',
                type: 'html',
                //scanStyles: false,
                css: ['http://localhost/scoreboard/bootstrap/css/bootstrap.min.css', 'http://localhost/scoreboard/css/style.css']
            });
        },
        loadTable: function (table) {
            if (table != '') $("#gamedraw-table tbody").html(table);
        },
        loadOption: function (options) {
            if (options != '') $("#gameset-gamedraw").html(options);
        },
        loadForm: function (gamedrawdata, modeget) {
            var modalTitle = "";
            if (modeget == 'update') {
                modalTitle += "Update";
                // $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");
                $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");

                if (gamedrawdata.bowstyle_id == 1) {
                    $("#gamedraw-bowstyle-recurve").prop("checked", true);
                } else if (gamedrawdata.bowstyle_id == 2) {
                    $("#gamedraw-bowstyle-compound").prop("checked", true);
                }
                $(".gamedraw-bowstyle-cls").attr('disabled', 'disabled');
                /*
                * TO-DO: radio game mode dinamic
                */
                if (gamedrawdata.gamemode_id == 1) {
                    $("#gamedraw-gamemode-beregu").prop("checked", true);
                    $(".gamedraw-player-opt-area-cls").addClass("hide");
                    $(".gamedraw-team-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id).removeAttr("disabled");
                    // $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id).removeAttr("disabled");
                    $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id);
                    $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id);
                    // addAttribute($(".gamedraw-team-cls"), 'disabled', 'disabled');
                    // } else if (gamedrawdata.gamemode['id'] == 2) {
                } else if (gamedrawdata.gamemode_id == 2) {
                    $("#gamedraw-gamemode-individu").prop("checked", true);
                    $(".gamedraw-team-opt-area-cls").addClass("hide");
                    $(".gamedraw-player-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id).removeAttr("disabled");
                    // $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id).removeAttr("disabled");
                    $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id);
                    $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id);
                    // addAttribute($(".gamedraw-player-cls"), 'disabled', 'disabled');
                }
                // $(".gamedraw-bowstyle-cls").removeAttr("disabled");
                // $(".gamedraw-gamemode-cls").removeAttr("disabled");
                // addAttribute($(".gamedraw-gamemode-cls"), 'disabled', 'disabled');
                $("#gamedraw-id").val(gamedrawdata.id);
                $("#gamedraw-action").val("update");
                $("#gamedraw-submit").val("Update");
            } else if (modeget == 'create') {
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
            } else if (modeget == 'delete') {
                modalTitle += "Delete";
                // $("#gamedraw-num").val(gamedrawdata.num).attr("disabled", "disabled");
                $("#gamedraw-num").val(gamedrawdata.num).attr('disabled', 'disabled');

                if (gamedrawdata.bowstyle_id == 1) {
                    $("#gamedraw-bowstyle-recurve").prop("checked", true);
                } else if (gamedrawdata.bowstyle_id == 2) {
                    $("#gamedraw-bowstyle-compound").prop("checked", true);
                }
                /*
                * TO-DO: radio game mode dinamic
                */
                if (gamedrawdata.gamemode_id == 1) {
                    $("#gamedraw-gamemode-beregu").prop("checked", true);
                    $(".gamedraw-player-opt-area-cls").addClass("hide");
                    $(".gamedraw-team-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id).attr("disabled", "disabled");
                    // $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id).attr("disabled", "disabled");
                    $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id);
                    $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id);
                    $(".gamedraw-team-cls").attr('disabled', 'disabled');
                } else if (gamedrawdata.gamemode_id == 2) {
                    $("#gamedraw-gamemode-individu").prop("checked", true);
                    $(".gamedraw-team-opt-area-cls").addClass("hide");
                    $(".gamedraw-player-opt-area-cls").removeClass("hide");
                    // $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id).attr("disabled", "disabled");
                    // $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id).attr("disabled", "disabled");
                    $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id);
                    $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id);
                    $(".gamedraw-player-cls").attr('disabled', 'disabled');
                }
                // $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
                // $(".gamedraw-gamemode-cls").attr("disabled", "disabled");
                $(".gamedraw-bowstyle-cls").attr('disabled', 'disabled');
                $(".gamedraw-gamemode-cls").attr('disabled', 'disabled');
                $("#gamedraw-id").val(gamedrawdata.id);
                $("#gamedraw-action").val("delete");
                $("#gamedraw-submit").val("Delete");
            }
            modalTitle += " Game Draw";
            $("#gamedraw-modal-title").html(modalTitle);
            $("#form-gamedraw-modal").modal();
        },
        ajaxSetupForm: function (gamedrawid, modeget) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?gamedraw_get=single&id=' + gamedrawid,
                success: function (data) {
                    if (data.status) {
                        GameDraw.loadForm(data.gamedraw, modeget);
                    }
                }
            });
        },
        submitForm: function (e) {
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
                        $("select.gamedraw-team-cls").prop("selectedIndex", 0);
                        $("select.gamedraw-player-cls").prop("selectedIndex", 0);
                        $("#form-gamedraw input#gamedraw-num").val(response.next_num);
                    }
                    var urls = [
                        '/scoreboard/controller.php?score_get=live&team=1&player=1&gamedraw=1&gameset=1',
                    ];
                    var actNames = [
                        'LIVESCORE_GET',
                    ];
                    App.ajaxGetReq(urls, actNames);
                    if (response.action == 'delete') {
                        $("#form-gamedraw-modal").modal("hide");
                    }
                }
            });
        }
    };

    var GameSet = {
        init: function () {
            GameSet.bindEvents();
        },
        bindEvents: function () {
            $("#create-gameset-button").click(GameSet.setupCreateForm);
            $(document).on('click', '.gameset-delete-btn-cls', GameSet.setupDeleteForm);
            $(document).on('click', '.gameset-update-btn-cls', GameSet.setupUpdateForm);
            $("#form-gameset").on('submit', GameSet.submitForm);
            $(document).on('click', '.gameset-live-btn-cls', GameSet.startGame);
            $(document).on('click', '.gameset-stoplive-btn-cls', GameSet.stopGame);
            $(document).on('click', '.gameset-view-btn-cls', GameSet.viewSummary);
            $("#gameset-gamedraw").on("change", GameSet.setNum);
            $(document).on('click', '#gameset-summary-print', GameSet.printSummary);
        },
        setupCreateForm: function () {
            GameSet.loadForm(false, 'create');
        },
        setupDeleteForm: function (e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            GameSet.ajaxSetupForm(gamesetid, 'delete');
        },
        setupUpdateForm: function (e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            GameSet.ajaxSetupForm(gamesetid, 'update');
        },
        setNum: function () {
            var gamedraw_id = $(this).val();
            if (gamedraw_id == 0) {
                $("#gameset-setnum").val(1);
            } else {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/scoreboard/controller.php?gameset_get=new_num&gamedraw_id=' + gamedraw_id,
                    success: function (data) {
                        if (data.status) {
                            $("#gameset-setnum").val(data.new_set);
                        } else {
                            $("#gameset-setnum").val(1);
                        }
                    }
                });
            }
        },
        viewSummary: function (e) {
            e.preventDefault();
            var gamesetid = $(this).attr("data-gamesetid");
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?GetGameSetInfo=' + gamesetid,
                success: function (data) {
                    if (data.status) {
                        if (data.has_value) {
                            GameSet.loadInfoTable(data.gameset);
                            $("#gameset-info-modal").modal();
                        }
                    }
                }
            });
        },
        loadInfoTable: function (gameset) {
            var tdText = "<thead class='bg-dark text-white'><tr class='bg-gray-2'><th class='text-gray-4 font-weight-normal border-0'>Point</th><th class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_a['name'] + "</th><th class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_b['name'] + "</th></tr></thead>";
            tdText += "<tbody>";
            tdText += "<tr><td class='text-gray-4 font-weight-bold border-0'>1</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_a['score']['score_1'] + "</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_b['score']['score_1'] + "</td></tr>";
            tdText += "<tr><td class='text-gray-4 font-weight-bold border-0'>2</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_a['score']['score_2'] + "</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_b['score']['score_2'] + "</td></tr>";
            tdText += "<tr class='text-gray-4 font-weight-normal border-0'><td class='text-gray-4 font-weight-bold border-0'>3</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_a['score']['score_3'] + "</td>";
            tdText += "<td class='text-gray-4 font-weight-normal border-0'>" + gameset.contestant_b['score']['score_3'] + "</td></tr>";
            tdText += "<tr class='d-none'><td class='text-gray-4 font-weight-normal border-0'>4</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_4'] + "</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_4'] + "</td></tr>";
            tdText += "<tr class='d-none'><td class='text-gray-4 font-weight-normal border-0'>5</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_5'] + "</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_5'] + "</td></tr>";
            tdText += "<tr class='d-none'><td class='text-gray-4 font-weight-normal border-0'>6</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_6'] + "</td>";
            tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_6'] + "</td></tr>";
            tdText += "<tr class='bg-gray-3'><td class='text-warning font-weight-bold border-gray-3'>Total</td>";
            tdText += "<td class='text-warning font-weight-bold border-gray-3'>" + gameset.contestant_a['score']['total'] + "</td>";
            tdText += "<td class='text-warning font-weight-bold border-gray-3'>" + gameset.contestant_b['score']['total'] + "</td></tr>";
            tdText += "<tr class='bg-gray-3'><td class='text-success font-weight-bold border-gray-3'>Set Points</td>";
            tdText += "<td class='text-success font-weight-bold border-gray-3'>" + gameset.contestant_a['score']['point'] + "</td>";
            tdText += "<td class='text-success font-weight-bold border-gray-3'>" + gameset.contestant_b['score']['point'] + "</td></tr>";
            tdText += "</tbody>";
            $("#gameset-info-modal-table").html(tdText);
        },
        loadTable: function (gameset_table) {
            if (gameset_table != '') $("#gameset-table tbody").html(gameset_table);
        },
        loadForm: function (gamesetdata, modeget) {
            var modalTitle = "";
            if (modeget == 'update') {
                modalTitle += "Update";
                $("#gameset-status-area").removeClass("hide"); $("#gameset-gamedraw").val(gamesetdata.gamedraw_id).removeAttr("disabled");
                $("#gameset-setnum").val(gamesetdata.num).removeAttr("disabled");
                $("#gameset-status").val(gamesetdata.gameset_status).removeAttr("disabled");
                $("#gameset-prev-status").val(gamesetdata.gameset_status);
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
                $("#gameset-gamedraw").val(gamesetdata.gamedraw_id).attr("disabled", "disabled");
                $("#gameset-setnum").val(gamesetdata.num).attr("disabled", "disabled");
                $("#gameset-status").val(gamesetdata.gameset_status).attr("disabled", "disabled");
                $("#gameset-prev-status").val(gamesetdata.gameset_status);
                $("#gameset-id").val(gamesetdata.id);
                $("#gameset-action").val("delete");
                $("#gameset-submit").val("Delete");
            }
            modalTitle += " Game Set";
            $("#gameset-modal-title").html(modalTitle);
            $("#form-gameset-modal").modal();
        },
        ajaxSetupForm: function (gamesetid, modeget) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/scoreboard/controller.php?gameset_get=single&id=' + gamesetid,
                success: function (data) {
                    if (data.status) {
                        GameSet.loadForm(data.gameset, modeget);
                    }
                }
            });
        },
        printSummary: function () {
            printJS({
                printable: 'gameset-info-modal-table',
                type: 'html',
                //scanStyles: false,
                css: ['http://localhost/scoreboard/bootstrap/css/bootstrap.min.css', 'http://localhost/scoreboard/css/style.css']
            });
        },
        startGame: function (e) {
            e.preventDefault();
            if ($("#score-a-timer-play").hasClass("play-on-cls") || $("#score-b-timer-play").hasClass("play-on-cls")) {
                alert("Please pause/stop timer");
            } else {
                var gameset = $(this).attr("data-gamesetid");
                $.post("controller.php", {
                    livegame_action: 'set-live-game',
                    gamesetid: gameset
                },
                    function (data, status) {
                        var result = $.parseJSON(data);
                        if (result.status) {
                            var urls = [
                                // '/scoreboard/controller.php?gameset_get=list',
                                '/scoreboard/controller.php?score_get=live&gameset=1&preview=1',
                            ];
                            var actNames = [
                                // 'GAMESET_LIST',
                                'LIVEGAME_START',
                            ];
                            App.ajaxGetReq(urls, actNames);
                        }
                    }
                );
            }
        },
        stopGame: function (e) {
            e.preventDefault();
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
                            FormScoreboard.resetForm();
                            var urls = [
                                // '/scoreboard/controller.php?gameset_get=list',
                                '/scoreboard/controller.php?score_get=live&gameset=1&preview=1',
                            ];
                            var actNames = [
                                // 'GAMESET_LIST',
                                'LIVEGAME_STOP',
                            ];
                            App.ajaxGetReq(urls, actNames);
                        }
                    }
                );
            }
        },
        submitForm: function (e) {
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
                    var urls = [
                        '/scoreboard/controller.php?score_get=live&team=1&player=1&gamedraw=1&gameset=1',
                    ];
                    var actNames = [
                        'LIVESCORE_GET',
                    ];
                    App.ajaxGetReq(urls, actNames);
                    if (data.action == 'delete') {
                        $("#form-gameset-modal").modal("hide");
                    }
                }
            });
        }
    };

    // InitSetup();
    App.init();

    function parseNum(str) {
        if (str == '') {
            return 0;
        }
        return parseInt(str);
    }

    // $('.score-a-input-cls').on('input', function (e) {
    //     var prev_total = parseNum($("#score-a-total").val());
    //     var prev_game_total_points = parseNum($("#score-a-gametotal").val());
    //     var total_point = parseNum($("#score-a-pt1").val()) + parseNum($("#score-a-pt2").val()) + parseNum($("#score-a-pt3").val()) + parseNum($("#score-a-pt4").val()) + parseNum($("#score-a-pt5").val()) + parseNum($("#score-a-pt6").val());
    //     $("#score-a-total").val(total_point);
    //     var game_total_points = prev_game_total_points + (total_point - prev_total);
    //     $("#score-a-gametotal").val(game_total_points);
    // });

    // $('.score-b-input-cls').on('input', function (e) {
    //     var prev_total = parseNum($("#score-b-total").val());
    //     var prev_game_total_points = parseNum($("#score-b-gametotal").val());
    //     var total_point = parseNum($("#score-b-pt1").val()) + parseNum($("#score-b-pt2").val()) + parseNum($("#score-b-pt3").val()) + parseNum($("#score-b-pt4").val()) + parseNum($("#score-b-pt5").val()) + parseNum($("#score-b-pt6").val());
    //     $("#score-b-total").val(total_point);
    //     var game_total_points = prev_game_total_points + (total_point - prev_total);
    //     $("#score-b-gametotal").val(game_total_points);
    // });

    // function InitSetup() {
    //     var urls = [
    //         // '/scoreboard/controller.php?gameset_get=list',
    //         // '/scoreboard/controller.php?gamedraw_get=list',
    //         // '/scoreboard/controller.php?player_get=list',
    //         // '/scoreboard/controller.php?team_get=list',
    //         // '/scoreboard/controller.php?GetLiveScore=1',
    //         // '/scoreboard/controller.php?bowstyle_get=list',
    //         // '/scoreboard/controller.php?gamemode_get=list',
    //         // '/scoreboard/controller.php?gamestatus_get=list',
    //         // '/scoreboard/controller.php?InitSetup=1',
    //         '/scoreboard/controller.php?config_get=init',
    //         '/scoreboard/controller.php?config_get=setup',
    //     ];
    //     var actNames = [
    //         // 'GAMESET_LIST',
    //         // 'GAMEDRAW_LIST',
    //         // 'PLAYER_LIST',
    //         // 'TEAM_LIST',
    //         // 'GetLiveScore',
    //         // 'BOWSTYLE_LIST',
    //         // 'GAMEMODE_LIST',
    //         // 'GAMESTATUS_LIST',
    //         // 'InitSetup',
    //         'CONFIG_GET',
    //         'CONFIG_SET',
    //     ];
    //     ajaxGetReq(urls, actNames);
    // }

    // function GetTeamByID(teamid, modeget) {
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: '/scoreboard/controller.php?GetTeam=' + teamid,
    //         success: function (data) {
    //             if (data.status) {
    //                 Form_Load_Team(data.team, modeget);
    //             }
    //         }
    //     });
    // }

    // function GetPlayerByID(playerid, modeget) {
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: '/scoreboard/controller.php?GetPlayer=' + playerid,
    //         success: function (data) {
    //             if (data.status) {
    //                 Form_Load_Player(data.player, modeget);
    //             }
    //         }
    //     });
    // }

    // function GetGameSetByID(gamesetid, modeget) {
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: '/scoreboard/controller.php?gameset_get=single&id=' + gamesetid,
    //         success: function (data) {
    //             if (data.status) {
    //                 Form_Load_GameSet(data.gameset, modeget);
    //             }
    //         }
    //     });
    // }

    // function GetGameDrawByID(gamedrawid, modeget) {
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: '/scoreboard/controller.php?gamedraw_get=single&id=' + gamedrawid,
    //         success: function (data) {
    //             if (data.status) {
    //                 Form_Load_GameDraw(data.gamedraw, modeget);
    //             }
    //         }
    //     });
    // }

    // function Form_Load_Team(teamdata, modeget) {
    //     var modalTitle = "";
    //     if (modeget == 'update') {
    //         modalTitle += "Update";
    //         $("#team-modal-image").attr("src", "uploads/" + teamdata.logo).removeClass("hide");
    //         $("#team-name").val(teamdata.name).removeAttr("disabled");
    //         $("#team-desc").val(teamdata.desc).removeAttr("disabled");
    //         $("#team-logo").val('');
    //         $("#team-logo").removeAttr("disabled");
    //         $("#team-id").val(teamdata.id);
    //         $("#team-action").val("update");
    //         $("#team-submit").val("Save");
    //     } else if (modeget == 'create') {
    //         modalTitle += "New";
    //         $("#team-modal-image").attr("src", "").addClass("hide");
    //         $("#team-name").val("").removeAttr("disabled");
    //         $("#team-desc").val("").removeAttr("disabled");
    //         $("#team-logo").val("");
    //         $("#team-logo").removeAttr("disabled");
    //         $("#team-id").val(0);
    //         $("#team-action").val("create");
    //         $("#team-submit").val("Create");
    //     } else if (modeget == 'delete') {
    //         modalTitle += "Delete";
    //         $("#team-modal-image").attr("src", "uploads/" + teamdata.logo).removeClass("hide");
    //         $("#team-name").val(teamdata.name).attr("disabled", "disabled");
    //         $("#team-desc").val(teamdata.desc).attr("disabled", "disabled");
    //         $("#team-logo").attr("disabled", "disabled");
    //         $("#team-id").val(teamdata.id);
    //         $("#team-action").val("delete");
    //         $("#team-submit").val("Delete");
    //     }
    //     modalTitle += " Team";
    //     $("#team-modal-title").html(modalTitle);
    //     $("#form-team-modal").modal();
    // }

    // function Form_Load_Player(playerdata, modeget) {
    //     var modalTitle = "";
    //     if (modeget == 'update') {
    //         modalTitle += "Update";
    //         $("#player-name").val(playerdata.name).removeAttr("disabled");
    //         $("#player-team").val(playerdata.team['id']).removeAttr("disabled");
    //         $("#player-id").val(playerdata.id);
    //         $("#player-action").val("update");
    //         $("#player-submit").val("Save");
    //     } else if (modeget == 'create') {
    //         modalTitle += "New";
    //         $("#player-name").val("").removeAttr("disabled");
    //         $("#player-team").val(0).removeAttr("disabled");
    //         $("#player-id").val(0);
    //         $("#player-action").val("create");
    //         $("#player-submit").val("Create");
    //     } else if (modeget == 'delete') {
    //         modalTitle += "Delete";
    //         $("#player-name").val(playerdata.name).attr("disabled", "disabled");
    //         $("#player-team").val(playerdata.team['id']).attr("disabled", "disabled");
    //         $("#player-id").val(playerdata.id);
    //         $("#player-action").val("delete");
    //         $("#player-submit").val("Delete");
    //     }
    //     modalTitle += " Player";
    //     $("#player-modal-title").html(modalTitle);
    //     $("#form-player-modal").modal();
    // }

    // function Form_Load_Scoreboard_UI_Config(uidata, modeget) {
    //     var modalTitle = "";
    //     if (modeget == 'update') {
    //         modalTitle += "Update";
    //         // $("#player-name").val(playerdata.name).removeAttr("disabled");
    //         // $("#player-team").val(playerdata.team['id']).removeAttr("disabled");
    //         // $("#player-id").val(playerdata.id);
    //         // $("#player-action").val("update");
    //         // $("#player-submit").val("Save");
    //     } else if (modeget == 'create') {
    //         modalTitle += "New";
    //         // $("#player-name").val("").removeAttr("disabled");
    //         // $("#player-team").val(0).removeAttr("disabled");
    //         // $("#player-id").val(0);
    //         // $("#player-action").val("create");
    //         // $("#player-submit").val("Create");
    //     } else if (modeget == 'delete') {
    //         modalTitle += "Delete";
    //         // $("#player-name").val(playerdata.name).attr("disabled", "disabled");
    //         // $("#player-team").val(playerdata.team['id']).attr("disabled", "disabled");
    //         // $("#player-id").val(playerdata.id);
    //         // $("#player-action").val("delete");
    //         // $("#player-submit").val("Delete");
    //     }
    //     modalTitle += " UI Config";
    //     $("#scoreboard-ui-modal-title").html(modalTitle);
    //     $("#form-scoreboard-ui-modal").modal();
    // }

    // function Form_Load_GameSet(gamesetdata, modeget) {
    //     var modalTitle = "";
    //     if (modeget == 'update') {
    //         modalTitle += "Update";
    //         $("#gameset-status-area").removeClass("hide"); $("#gameset-gamedraw").val(gamesetdata.gamedraw_id).removeAttr("disabled");
    //         $("#gameset-setnum").val(gamesetdata.num).removeAttr("disabled");
    //         $("#gameset-status").val(gamesetdata.gameset_status).removeAttr("disabled");
    //         $("#gameset-prev-status").val(gamesetdata.gameset_status);
    //         $("#gameset-id").val(gamesetdata.id);
    //         $("#gameset-action").val("update");
    //         $("#gameset-submit").val("Save");
    //     } else if (modeget == 'create') {
    //         modalTitle += "New";
    //         $("#gameset-status-area").addClass("hide");
    //         $("#gameset-gamedraw").val(0).removeAttr("disabled");
    //         $("#gameset-setnum").val(1).removeAttr("disabled");
    //         $("#gameset-status").val(0);
    //         $("#gameset-prev-status").val(0);
    //         $("#gameset-id").val(0);
    //         $("#gameset-action").val("create");
    //         $("#gameset-submit").val("Create");
    //     } else if (modeget == 'delete') {
    //         modalTitle += "Delete";
    //         $("#gameset-status-area").removeClass("hide");
    //         $("#gameset-gamedraw").val(gamesetdata.gamedraw_id).attr("disabled", "disabled");
    //         $("#gameset-setnum").val(gamesetdata.num).attr("disabled", "disabled");
    //         $("#gameset-status").val(gamesetdata.gameset_status).attr("disabled", "disabled");
    //         $("#gameset-prev-status").val(gamesetdata.gameset_status);
    //         $("#gameset-id").val(gamesetdata.id);
    //         $("#gameset-action").val("delete");
    //         $("#gameset-submit").val("Delete");
    //     }
    //     modalTitle += " Game Set";
    //     $("#gameset-modal-title").html(modalTitle);
    //     $("#form-gameset-modal").modal();
    // }

    // function Form_Load_GameDraw(gamedrawdata, modeget) {
    //     var modalTitle = "";
    //     if (modeget == 'update') {
    //         modalTitle += "Update";
    //         // $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");
    //         $("#gamedraw-num").val(gamedrawdata.num).removeAttr("disabled");

    //         if (gamedrawdata.bowstyle_id == 1) {
    //             $("#gamedraw-bowstyle-recurve").prop("checked", true);
    //         } else if (gamedrawdata.bowstyle_id == 2) {
    //             $("#gamedraw-bowstyle-compound").prop("checked", true);
    //         }
    //         $(".gamedraw-bowstyle-cls").attr('disabled', 'disabled');
    //         /*
    //         * TO-DO: radio game mode dinamic
    //         */
    //         if (gamedrawdata.gamemode_id == 1) {
    //             $("#gamedraw-gamemode-beregu").prop("checked", true);
    //             $(".gamedraw-player-opt-area-cls").addClass("hide");
    //             $(".gamedraw-team-opt-area-cls").removeClass("hide");
    //             // $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id).removeAttr("disabled");
    //             // $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id).removeAttr("disabled");
    //             $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id);
    //             $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id);
    //             // addAttribute($(".gamedraw-team-cls"), 'disabled', 'disabled');
    //             // } else if (gamedrawdata.gamemode['id'] == 2) {
    //         } else if (gamedrawdata.gamemode_id == 2) {
    //             $("#gamedraw-gamemode-individu").prop("checked", true);
    //             $(".gamedraw-team-opt-area-cls").addClass("hide");
    //             $(".gamedraw-player-opt-area-cls").removeClass("hide");
    //             // $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id).removeAttr("disabled");
    //             // $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id).removeAttr("disabled");
    //             $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id);
    //             $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id);
    //             // addAttribute($(".gamedraw-player-cls"), 'disabled', 'disabled');
    //         }
    //         // $(".gamedraw-bowstyle-cls").removeAttr("disabled");
    //         // $(".gamedraw-gamemode-cls").removeAttr("disabled");
    //         // addAttribute($(".gamedraw-gamemode-cls"), 'disabled', 'disabled');
    //         $("#gamedraw-id").val(gamedrawdata.id);
    //         $("#gamedraw-action").val("update");
    //         $("#gamedraw-submit").val("Update");
    //     } else if (modeget == 'create') {
    //         modalTitle += "New";
    //         $("#gamedraw-num").removeAttr("disabled");
    //         /*
    //         * TO-DO: radio game mode dinamic
    //         */
    //         $("#gamedraw-bowstyle-recurve").prop("checked", true);
    //         $("#gamedraw-bowstyle-compound").prop("checked", false);
    //         $("#gamedraw-gamemode-beregu").prop("checked", true);
    //         $("#gamedraw-gamemode-individu").prop("checked", false);
    //         $(".gamedraw-player-opt-area-cls").addClass("hide");
    //         $(".gamedraw-team-opt-area-cls").removeClass("hide");

    //         // $("#gamedraw-team-a").val(0).removeAttr("disabled");
    //         // $("#gamedraw-team-b").val(0).removeAttr("disabled");
    //         // $("#gamedraw-player-a").val(0).removeAttr("disabled");
    //         // $("#gamedraw-player-b").val(0).removeAttr("disabled");
    //         // $(".gamedraw-bowstyle-cls").removeAttr("disabled");
    //         // $(".gamedraw-gamemode-cls").removeAttr("disabled");
    //         $("#gamedraw-team-a").removeAttr("disabled");
    //         $("#gamedraw-team-b").removeAttr("disabled");
    //         $("#gamedraw-player-a").removeAttr("disabled");
    //         $("#gamedraw-player-b").removeAttr("disabled");
    //         $(".gamedraw-bowstyle-cls").removeAttr("disabled");
    //         $(".gamedraw-gamemode-cls").removeAttr("disabled");

    //         $("#gamedraw-id").val(0);
    //         $("#gamedraw-action").val("create");
    //         $("#gamedraw-submit").val("Save");
    //     } else if (modeget == 'delete') {
    //         modalTitle += "Delete";
    //         // $("#gamedraw-num").val(gamedrawdata.num).attr("disabled", "disabled");
    //         $("#gamedraw-num").val(gamedrawdata.num).attr('disabled', 'disabled');

    //         if (gamedrawdata.bowstyle_id == 1) {
    //             $("#gamedraw-bowstyle-recurve").prop("checked", true);
    //         } else if (gamedrawdata.bowstyle_id == 2) {
    //             $("#gamedraw-bowstyle-compound").prop("checked", true);
    //         }
    //         /*
    //         * TO-DO: radio game mode dinamic
    //         */
    //         if (gamedrawdata.gamemode_id == 1) {
    //             $("#gamedraw-gamemode-beregu").prop("checked", true);
    //             $(".gamedraw-player-opt-area-cls").addClass("hide");
    //             $(".gamedraw-team-opt-area-cls").removeClass("hide");
    //             // $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id).attr("disabled", "disabled");
    //             // $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id).attr("disabled", "disabled");
    //             $("#gamedraw-team-a").val(gamedrawdata.contestant_a_id);
    //             $("#gamedraw-team-b").val(gamedrawdata.contestant_b_id);
    //             $(".gamedraw-team-cls").attr('disabled', 'disabled');
    //         } else if (gamedrawdata.gamemode_id == 2) {
    //             $("#gamedraw-gamemode-individu").prop("checked", true);
    //             $(".gamedraw-team-opt-area-cls").addClass("hide");
    //             $(".gamedraw-player-opt-area-cls").removeClass("hide");
    //             // $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id).attr("disabled", "disabled");
    //             // $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id).attr("disabled", "disabled");
    //             $("#gamedraw-player-a").val(gamedrawdata.contestant_a_id);
    //             $("#gamedraw-player-b").val(gamedrawdata.contestant_b_id);
    //             $(".gamedraw-player-cls").attr('disabled', 'disabled');
    //         }
    //         // $(".gamedraw-bowstyle-cls").attr("disabled", "disabled");
    //         // $(".gamedraw-gamemode-cls").attr("disabled", "disabled");
    //         $(".gamedraw-bowstyle-cls").attr('disabled', 'disabled');
    //         $(".gamedraw-gamemode-cls").attr('disabled', 'disabled');
    //         $("#gamedraw-id").val(gamedrawdata.id);
    //         $("#gamedraw-action").val("delete");
    //         $("#gamedraw-submit").val("Delete");
    //     }
    //     modalTitle += " Game Draw";
    //     $("#gamedraw-modal-title").html(modalTitle);
    //     $("#form-gamedraw-modal").modal();
    // }

    // function Form_Load_Score(scoredata) {
    //     var contestant_a = scoredata.contestant_a;
    //     var contestant_b = scoredata.contestant_b;
    //     var score_a = scoredata.score_a;
    //     var score_b = scoredata.score_b;

    //     // $("#score-a-logo").attr("src", "uploads/" + contestant_a['logo']);
    //     $("#score-team-a-title").html(contestant_a['name']);
    //     $("#score-a-timer").val(score_a['timer'] + "s");
    //     $("#score-a-gamedraw-id").val(scoredata.gamedraw['id']);
    //     $("#score-a-gameset-id").val(scoredata.gameset_id);
    //     $("#score-a-id").val(score_a['id']);
    //     $("#score-a-pt1").val(score_a['score_1']);
    //     $("#score-a-pt2").val(score_a['score_2']);
    //     $("#score-a-pt3").val(score_a['score_3']);
    //     $("#score-a-pt4").val(score_a['score_4']);
    //     $("#score-a-pt5").val(score_a['score_5']);
    //     $("#score-a-pt6").val(score_a['score_6']);

    //     var total_point = parseNum(score_a['score_1']) + parseNum(score_a['score_2']) + parseNum(score_a['score_3']) + parseNum(score_a['score_4']) + parseNum(score_a['score_5']) + parseNum(score_a['score_6']);
    //     $("#score-a-total").val(total_point);
    //     $("#score-a-gametotal").val(score_a['game_total_points']);
    //     $("#score-a-setpoints").val(score_a['point']).attr("data-setpoints", score_a['point']);
    //     $("#score-a-gamepoints").val(score_a['game_points']).attr("data-gamepoints", score_a['game_points']); // setpoint + all
    //     $("#score-a-desc").val(score_a['desc']);

    //     // $("#score-b-logo").attr("src", "uploads/" + contestant_b['logo']);
    //     $("#score-team-b-title").html(contestant_b['name']);
    //     $("#score-b-timer").val(score_b['timer'] + "s");
    //     $("#score-b-gamedraw-id").val(scoredata.gamedraw['id']);
    //     $("#score-b-gameset-id").val(scoredata.gameset_id);
    //     $("#score-b-id").val(score_b['id']);
    //     $("#score-b-pt1").val(score_b['score_1']);
    //     $("#score-b-pt2").val(score_b['score_2']);
    //     $("#score-b-pt3").val(score_b['score_3']);
    //     $("#score-b-pt4").val(score_b['score_4']);
    //     $("#score-b-pt5").val(score_b['score_5']);
    //     $("#score-b-pt6").val(score_b['score_6']);

    //     var total_point = parseNum(score_b['score_1']) + parseNum(score_b['score_2']) + parseNum(score_b['score_3']) + parseNum(score_b['score_4']) + parseNum(score_b['score_5']) + parseNum(score_b['score_6']);
    //     $("#score-b-total").val(total_point);
    //     $("#score-b-gametotal").val(score_b['game_total_points']);
    //     $("#score-b-setpoints").val(score_b['point']).attr("data-setpoints", score_b['point']);
    //     $("#score-b-gamepoints").val(score_b['game_points']).attr("data-gamepoints", score_b['game_points']);
    //     $("#score-b-desc").val(score_b['desc']);
    // }

    // function Form_Reset_Score() {

    //     $("#score-a-logo").attr("src", "uploads/no-image.png");
    //     $("#score-team-a-title").html("Team A");
    //     $("#score-a-timer").val("0s");
    //     $("#score-a-gamedraw-id").val(0);
    //     $("#score-a-gameset-id").val(0);
    //     $("#score-a-id").val(0);
    //     $("#score-a-pt1").val(0);
    //     $("#score-a-pt2").val(0);
    //     $("#score-a-pt3").val(0);
    //     $("#score-a-pt4").val(0);
    //     $("#score-a-pt5").val(0);
    //     $("#score-a-pt6").val(0);

    //     $("#score-a-total").val(0);
    //     $("#score-a-gametotal").val(0);
    //     $("#score-a-setpoints").val(0);
    //     $("#score-a-gamepoints").val(0);
    //     $("#score-a-desc").val("");


    //     $("#score-b-logo").attr("src", "uploads/no-image.png");
    //     $("#score-team-b-title").html("Team B");
    //     $("#score-b-timer").val("0s");
    //     $("#score-b-gamedraw-id").val(0);
    //     $("#score-b-gameset-id").val(0);
    //     $("#score-b-id").val(0);
    //     $("#score-b-pt1").val(0);
    //     $("#score-b-pt2").val(0);
    //     $("#score-b-pt3").val(0);
    //     $("#score-b-pt4").val(0);
    //     $("#score-b-pt5").val(0);
    //     $("#score-b-pt6").val(0);

    //     $("#score-b-total").val(0);
    //     $("#score-b-gametotal").val(0);
    //     $("#score-b-setpoints").val(0);
    //     $("#score-b-gamepoints").val(0);
    //     $("#score-b-desc").val("");
    // }

    // function Load_Config(configData) {
    //     Form_Load_Config(configData);
    //     // console.log(livegame);
    //     Scoreboard_Config(configData.scoreboard);
    //     loadPreviewScoreboard(configData.preview_scoreboard);
    // }

    // function Scoreboard_Config(config) {
    //     // console.log(config['logo']);
    //     // $("#sb-logo").attr("checked", config['logo']['visibility']).val(config['logo']['visibility']);
    //     SetScoreboardVisibility($(".sb-logo-cls"), config['logo']['visibility']);
    //     // $("#sb-team").attr("checked", config['team']['visibility']).val(config['team']['visibility']);
    //     SetScoreboardVisibility($(".sb-team-cls"), config['team']['visibility']);
    //     // $("#sb-player").attr("checked", config['player']['visibility']).val(config['player']['visibility']);
    //     SetScoreboardVisibility($(".sb-player-cls"), config['player']['visibility']);
    //     // $("#sb-timer").attr("checked", config['timer']['visibility']).val(config['timer']['visibility']);
    //     SetScoreboardVisibility($(".sb-timer-cls"), config['timer']['visibility'], "timer");
    //     // $("#sb-p1").attr("checked", config['p1']['visibility']).val(config['p1']['visibility']);
    //     SetScoreboardVisibility($(".sb-p1-cls"), config['p1']['visibility']);
    //     // $("#sb-p2").attr("checked", config['p2']['visibility']).val(config['p2']['visibility']);
    //     SetScoreboardVisibility($(".sb-p2-cls"), config['p2']['visibility']);
    //     // $("#sb-p3").attr("checked", config['p3']['visibility']).val(config['p3']['visibility']);
    //     SetScoreboardVisibility($(".sb-p3-cls"), config['p3']['visibility']);
    //     // $("#sb-p4").attr("checked", config['p4']['visibility']).val(config['p4']['visibility']);
    //     SetScoreboardVisibility($(".sb-p4-cls"), config['p4']['visibility']);
    //     // $("#sb-p5").attr("checked", config['p5']['visibility']).val(config['p5']['visibility']);
    //     SetScoreboardVisibility($(".sb-p5-cls"), config['p5']['visibility']);
    //     // $("#sb-p6").attr("checked", config['p6']['visibility']).val(config['p6']['visibility']);
    //     SetScoreboardVisibility($(".sb-p6-cls"), config['p6']['visibility']);
    //     // $("#sb-set-total-points").attr("checked", config['set_total_points']['visibility']).val(config['set_total_points']['visibility']);
    //     SetScoreboardVisibility($(".sb-set-total-points-cls"), config['set_total_points']['visibility']);
    //     // $("#sb-game-total-points").attr("checked", config['game_total_points']['visibility']).val(config['game_total_points']['visibility']);
    //     SetScoreboardVisibility($(".sb-game-total-points-cls"), config['game_total_points']['visibility']);
    //     // $("#sb-set-points").attr("checked", config['set_points']['visibility']).val(config['set_points']['visibility']);
    //     SetScoreboardVisibility($(".sb-set-points-cls"), config['set_points']['visibility']);
    //     // $("#sb-game-points").attr("checked", config['game_points']['visibility']).val(config['game_points']['visibility']);
    //     SetScoreboardVisibility($(".sb-game-points-cls"), config['game_points']['visibility']);
    //     // $("#sb-description").attr("checked", config['description']['visibility']).val(config['description']['visibility']);
    //     SetScoreboardVisibility($(".sb-description-cls"), config['description']['visibility']);
    // }

    // function SetScoreboardVisibility(elementTarget, visibility, exc = "") {
    //     if (visibility == false) {
    //         elementTarget.addClass("d-none");
    //         if (exc == "timer") {
    //             $("#score-a-control").addClass("d-none");
    //             $("#score-b-control").addClass("d-none");
    //         }
    //     } else {
    //         if (exc == "timer") {
    //             $("#score-a-control").removeClass("d-none");
    //             $("#score-b-control").removeClass("d-none");
    //         }
    //         elementTarget.removeClass("d-none");
    //     }
    // }

    // function Form_Load_Config(configdata) {
    //     $("#form-config #config-id").val(configdata.id);
    //     $("#form-config #config-time-interval").val(configdata.time_interval);
    //     $("#activated-mode").text(configdata.active_mode);
    //     $("#form-config #config-active-mode").val(configdata.active_mode);
    // }

    // function renderBowstyleRadio(bowstyle_radio) {
    //     $("#gamedraw-radio-bowstyle-area").html(bowstyle_radio);
    // }

    // function renderBowstyleOption(bowstyle_option) {
    //     $("#scoreboard-style-bowstyle-select").html(bowstyle_option);
    // }

    // function Radio_Load_GameMode(elemTarget, gamemodesdata) {
    //     var radioTxt = "";
    //     for (i = 0; i < gamemodesdata.length; i++) {
    //         radioTxt += "<div class='form-check form-check-inline'>";
    //         radioTxt += "<input type='radio'";
    //         if (i == 0) {
    //             radioTxt += " checked='checked' ";
    //         }
    //         radioTxt += "name='gamedraw_gamemode' class='gamedraw-gamemode-cls form-check-input' value='" + gamemodesdata[i].id + "' id='gamedraw-gamemode-" + (gamemodesdata[i].name).toLowerCase() + "'><label for='gamedraw-gamemode-" + (gamemodesdata[i].name).toLowerCase() + "' class='form-check-label text-gray-4'>" + gamemodesdata[i].name + "</label>";
    //         radioTxt += "</div>";
    //     }
    //     elemTarget.html(radioTxt);
    // }

    // function renderTeamList(teams) {
    //     $("#team-table tbody").html(teams);
    // }

    // function renderPlayerList(players) {
    //     $("#player-table tbody").html(players);
    // }

    // function renderGamedrawList(gamedrawItem) {
    //     $("#gamedraw-table tbody").html(gamedrawItem);
    // }

    // function renderGamesetList(gamesetItem) {
    //     $("#gameset-table tbody").html(gamesetItem);
    // }

    // function Table_Load_GameSetInfo(elemTarget, gameset) {
    //     var tdText = "<thead class='bg-dark text-white'><tr><th>Point</th><th>" + gameset.contestant_a['name'] + "</th><th>" + gameset.contestant_b['name'] + "</th></tr></thead>";
    //     tdText += "<tbody>";
    //     tdText += "<tr><td>1</td>";
    //     tdText += "<td>" + gameset.contestant_a['score']['score_1'] + "</td>";
    //     tdText += "<td>" + gameset.contestant_b['score']['score_1'] + "</td></tr>";
    //     tdText += "<tr><td>2</td>";
    //     tdText += "<td>" + gameset.contestant_a['score']['score_2'] + "</td>";
    //     tdText += "<td>" + gameset.contestant_b['score']['score_2'] + "</td></tr>";
    //     tdText += "<tr><td>3</td>";
    //     tdText += "<td>" + gameset.contestant_a['score']['score_3'] + "</td>";
    //     tdText += "<td>" + gameset.contestant_b['score']['score_3'] + "</td></tr>";
    //     tdText += "<tr class='d-none'><td>4</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_4'] + "</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_4'] + "</td></tr>";
    //     tdText += "<tr class='d-none'><td>5</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_5'] + "</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_5'] + "</td></tr>";
    //     tdText += "<tr class='d-none'><td>6</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_a['score']['score_6'] + "</td>";
    //     tdText += "<td class='d-none'>" + gameset.contestant_b['score']['score_6'] + "</td></tr>";
    //     tdText += "<tr><td>Total</td>";
    //     tdText += "<td>" + gameset.contestant_a['score']['total'] + "</td>";
    //     tdText += "<td>" + gameset.contestant_b['score']['total'] + "</td></tr>";
    //     tdText += "<tr><td>Set Points</td>";
    //     tdText += "<td>" + gameset.contestant_a['score']['point'] + "</td>";
    //     tdText += "<td>" + gameset.contestant_b['score']['point'] + "</td></tr>";
    //     tdText += "</tbody>";
    //     elemTarget.html(tdText);
    // }

    // function Option_Load_Team(elemTarget, teamsdata) {
    //     elemTarget.html(teamsdata);
    // }

    // function Option_Load_Player(elemTarget, playersdata) {
    //     elemTarget.html(playersdata);
    // }

    // function Option_Load_GameDraw(elemTarget, gamesdraws_data) {
    //     $(elemTarget).html(gamesdraws_data);
    // }

    // function Option_Load_GameStatus(elemTarget, gamestatuses) {
    //     elemTarget.html(gamestatuses);
    // }

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

    // function ajaxGetReq(urls, actNames) {
    //     if (urls.length > 0 && actNames.length > 0) {
    //         var act = actNames.pop();
    //         $.ajax({
    //             type: 'GET',
    //             dataType: 'json',
    //             url: urls.pop(),
    //             success: function (data) {
    //                 if (data.status) {
    //                     if (act == 'CONFIG_GET') {
    //                         // Load_Config(data.config);
    //                         FormScoreboard.loadForm(data.form_scoreboard);
    //                         GameStatus.loadOption(data.gamestatus_options);
    //                         GameMode.loadRadio(data.gamemode_radios);
    //                         BowStyle.loadRadio(data.bowstyles['radios']);
    //                         BowStyle.loadOption(data.bowstyles['options']);
    //                         ScoreboardStyle.loadOption(data.scoreboard_style_options);
    //                         Team.loadTable(data.teams['table']);
    //                         Team.loadOption(data.teams['options']);
    //                         Player.loadTable(data.players['table']);
    //                         Player.loadOption(data.players['options']);
    //                         GameDraw.loadOption(data.gamedraws['options']);
    //                         GameDraw.loadTable(data.gamedraws['table']);
    //                         GameSet.loadTable(data.gameset_table);
    //                     }
    //                     // else if (act == 'GAMESTATUS_LIST') {
    //                     //     Option_Load_GameStatus($("#gameset-status"), data.gamestatuses);
    //                     // }
    //                     // else if (act == 'GAMEMODE_LIST') {
    //                     //     Radio_Load_GameMode($("#gamedraw-radio-area"), data.gamemodes);
    //                     // }
    //                     // else if (act == 'BOWSTYLE_LIST') {
    //                     //     // Radio_Load_Bowstyle($("#gamedraw-radio-bowstyle-area"), data.bowstyles);
    //                     //     renderBowstyleRadio(data.bowstyle_radio);
    //                     //     renderBowstyleOption(data.bowstyle_option);
    //                     // }
    //                     // else if (act == 'GetLiveScore') {
    //                     //     livegame = data.live_game;
    //                     // }
    //                     // else if (act == 'TEAM_LIST') {
    //                     //     renderTeamList(data.teams);
    //                     //     Option_Load_Team($("#player-team"), data.team_option);
    //                     //     Option_Load_Team($("#gamedraw-team-a"), data.team_option);
    //                     //     Option_Load_Team($("#gamedraw-team-b"), data.team_option);
    //                     // }
    //                     // else if (act == 'PLAYER_LIST') {
    //                     //     renderPlayerList(data.players);
    //                     //     Option_Load_Player($("#gamedraw-player-a"), data.player_option);
    //                     //     Option_Load_Player($("#gamedraw-player-b"), data.player_option);
    //                     // }
    //                     // else if (act == 'GAMEDRAW_LIST') {
    //                     //     Option_Load_GameDraw($("#gameset-gamedraw"), data.gamedraw_option);
    //                     //     renderGamedrawList(data.gamedraws);
    //                     // }
    //                     else if (act == 'LIVESCORE_GET') {
    //                         //renderGamesetList(data.gamesets);
    //                         FormScoreboard.loadForm(data.form_scoreboard);
    //                         GameSet.loadTable(data.gameset_table);
    //                     }
    //                     // if (data.has_value) {

    //                     //     if (act == 'GetLiveScore') {
    //                     //         //Scoreboard_Config(data.config['scoreboard']);
    //                     //         Form_Load_Score(data.score);
    //                     //         EnableScoreboard();
    //                     //     }

    //                     // } else {
    //                     //     if (act == 'GetLiveScore') {
    //                     //         //Scoreboard_Config(data.config['scoreboard']);
    //                     //         Form_Reset_Score();
    //                     //         DisableScoreboard();
    //                     //     }
    //                     // }
    //                 } else {
    //                 }
    //                 ajaxGetReq(urls, actNames);
    //             }
    //         });
    //     }
    // }

    // function Set_GamePoints(sender, elementTarget) {
    //     var prev_setpoints = parseNum(sender.attr("data-setpoints"));
    //     var prev_gamepoints = parseNum(elementTarget.attr("data-gamepoints"));
    //     var result = prev_gamepoints - prev_setpoints + parseNum(sender.val());
    //     elementTarget.val(result);
    // }

    // function Load_Table_Preview_Scoreboard(mode) {
    //     var table_preview = $("#table-prev-scoreboard");
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: '/scoreboard/controller.php?config_get=scoreboard&mode=' + mode,
    //         success: function (data) {
    //             if (data.status) {
    //                 if (mode == 0) {
    //                     table_preview.hide();
    //                 } else {
    //                     var config = data.config;
    //                     /* if (mode == 1 || mode == 2 || mode == 3) {
    //                         config = data.config[0];
    //                     } else if (mode == 4 || mode == 5 || mode == 6) {
    //                         config = data.config[1];
    //                     } else if (mode == 7 || mode == 8 || mode == 9) {
    //                         config = data.config[2];
    //                     } */
    //                     SetScoreboardVisibility($(".prev-score-logo"), config.logo['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-team"), config.team['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-player"), config.player['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-timer"), config.timer['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-1"), config.p1['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-2"), config.p2['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-3"), config.p3['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-4"), config.p4['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-5"), config.p5['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-point-6"), config.p6['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-total"), config.set_total_points['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-gametotal"), config.game_total_points['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-setpoint"), config.set_points['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-gamepoint"), config.game_points['visibility']);
    //                     SetScoreboardVisibility($(".prev-score-desc"), config.description['visibility']);

    //                     SetPreviewScoreboard(mode);
    //                     table_preview.show();
    //                 }
    //             } else {
    //                 table_preview.hide();
    //             }
    //         }
    //     });
    // }

    // function loadPreviewScoreboard(preview_scoreboard) {
    //     var table_preview = $("#table-prev-scoreboard");
    //     if (preview_scoreboard != null) {
    //         SetScoreboardVisibility($(".prev-score-logo"), preview_scoreboard.cfg['logo']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-team"), preview_scoreboard.cfg['team']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-player"), preview_scoreboard.cfg['player']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-timer"), preview_scoreboard.cfg['timer']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-1"), preview_scoreboard.cfg['p1']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-2"), preview_scoreboard.cfg['p2']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-3"), preview_scoreboard.cfg['p3']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-4"), preview_scoreboard.cfg['p4']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-5"), preview_scoreboard.cfg['p5']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-point-6"), preview_scoreboard.cfg['p6']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-total"), preview_scoreboard.cfg['set_total_points']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-gametotal"), preview_scoreboard.cfg['game_total_points']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-setpoint"), preview_scoreboard.cfg['set_points']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-gamepoint"), preview_scoreboard.cfg['game_points']['visibility']);
    //         SetScoreboardVisibility($(".prev-score-desc"), preview_scoreboard.cfg['description']['visibility']);

    //         SetPreviewScoreboard(preview_scoreboard.mode);
    //         table_preview.show();
    //     } else {
    //         table_preview.hide();
    //     }
    // }

    // function SetPreviewScoreboard(mode) {
    //     $(".prev-score-logo").show();
    //     $(".prev-score-team").show();
    //     $(".prev-score-player").show();
    //     $(".prev-score-point-4").hide();
    //     $(".prev-score-point-5").hide();
    //     $(".prev-score-point-6").hide();
    //     if (mode == 1) {
    //         $(".prev-score-timer").show();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").show();
    //     } else if (mode == 2) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").show();
    //         $(".prev-score-point-2").show();
    //         $(".prev-score-point-3").show();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").show();
    //     } else if (mode == 3) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").hide();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").show();
    //     } else if (mode == 4) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").hide();
    //     } else if (mode == 5) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").show();
    //         $(".prev-score-point-2").show();
    //         $(".prev-score-point-3").show();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").hide();
    //     } else if (mode == 6) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").hide();
    //         $(".prev-score-gametotal").hide();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").show();
    //         $(".prev-score-desc").hide();
    //     } else if (mode == 7) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").show();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").hide();
    //         $(".prev-score-desc").hide();
    //     } else if (mode == 8) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").show();
    //         $(".prev-score-point-2").show();
    //         $(".prev-score-point-3").show();
    //         $(".prev-score-total").show();
    //         $(".prev-score-gametotal").show();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").hide();
    //         $(".prev-score-desc").hide();
    //     } else if (mode == 9) {
    //         $(".prev-score-timer").hide();
    //         $(".prev-score-point-1").hide();
    //         $(".prev-score-point-2").hide();
    //         $(".prev-score-point-3").hide();
    //         $(".prev-score-total").hide();
    //         $(".prev-score-gametotal").show();
    //         $(".prev-score-setpoint").hide();
    //         $(".prev-score-gamepoint").hide();
    //         $(".prev-score-desc").hide();
    //     }
    // }

    // $("#scoreboard-ui-config-btn").click(function (e) {
    //     Form_Load_Scoreboard_UI_Config(false, 'update');
    // });

    // $("#scoreboard-filter-lock").click(function (e) {
    //     e.preventDefault();
    //     var attr = $(this).attr('data-lock');

    //     if (attr == 1) {
    //         $(this).html("<i class='fas fa-unlock text-white'></i>");
    //         UnlockScoreboardFilter();
    //         $(this).attr('data-lock', 0);
    //         $(this).removeClass('btn-danger');
    //         $(this).addClass('btn-warning');
    //     } else {
    //         $(this).html("<i class='fas fa-lock'></i>");
    //         LockScoreboardFilter();
    //         $(this).attr('data-lock', 1);
    //         $(this).addClass('btn-danger');
    //         $(this).removeClass('btn-warning');
    //     }
    // });

    // $('#score-a-setpoints, #score-b-setpoints').on('input', function (e) {
    //     if (this.id == "score-a-setpoints") {
    //         Set_GamePoints($("#score-a-setpoints"), $("#score-a-gamepoints"));
    //     } else if (this.id == "score-b-setpoints") {
    //         Set_GamePoints($("#score-b-setpoints"), $("#score-b-gamepoints"));
    //     }
    // });

    // $("input.ssv-cb").change(function () {
    //     var cls = $(this).attr('data-class');
    //     var status = $(this).prop("checked");
    //     if (status) {
    //         $("." + cls).removeClass('hide');
    //         $(this).val(true);
    //     } else {
    //         $("." + cls).addClass("hide");
    //         $(this).val(false);
    //         $("#ssv-collective-cb").prop("checked", false);
    //     }
    // });

    // $("#ssv-collective-cb").change(function () {
    //     var target = $("input.ssv-cb");
    //     var status = $(this).prop("checked");
    //     if (status) {
    //         target.prop("checked", true).val(true);
    //         $("#scoreboard-style-preview table td").removeClass('hide');
    //     } else {
    //         target.prop("checked", false).val(false);
    //         $("#scoreboard-style-preview table td").addClass("hide");
    //     }
    // });

    // $("#config-active-mode").on("change", function () {
    //     var mode = $(this).val();
    //     Load_Table_Preview_Scoreboard(mode);
    // });

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



    // $("#form-config").on('submit', function (e) {
    //     e.preventDefault();

    //     if (request) {
    //         request.abort();
    //     }

    //     var $form = $(this);
    //     // var $input = $form.find("input, select, button, textarea");
    //     var serializedData = $form.serialize();

    //     // $input.prop("disabled", true);

    //     request = $.ajax({
    //         url: "/scoreboard/controller.php",
    //         type: "POST",
    //         data: serializedData,
    //     });

    //     request.done(function (response, textStatus, jqXHR) {
    //         var data = $.parseJSON(response);
    //         if (data.status) {
    //             $("#activated-mode").text(data.activated_mode);
    //         }
    //     });
    // });

    //     request.done(function (response, textStatus, jqXHR) {
    //         var data = $.parseJSON(response);
    //         if (data.status) {
    //             if (data.action == 'create') {
    //                 $("#gameset-gamedraw").val(0).removeAttr("disabled");
    //                 $("#gameset-setnum").val(1).removeAttr("disabled");
    //                 $("#gameset-id").val(0);
    //                 $("#gameset-action").val("create");
    //             }
    //             var urls = [
    //                 '/scoreboard/controller.php?gameset_get=list',
    //                 '/scoreboard/controller.php?gamedraw_get=list',
    //                 '/scoreboard/controller.php?player_get=list',
    //                 '/scoreboard/controller.php?team_get=list',
    //                 '/scoreboard/controller.php?GetLiveScore=1',
    //             ];
    //             var actNames = [
    //                 'GAMESET_LIST',
    //                 'GAMEDRAW_LIST',
    //                 'PLAYER_LIST',
    //                 'TEAM_LIST',
    //                 'GetLiveScore',
    //             ];
    //             ajaxGetReq(urls, actNames);
    //             if (data.action == 'delete') {
    //                 $("#form-gameset-modal").modal("hide");
    //             }
    //         }
    //     });
    // });

    // $("#form-score-a").on('submit', function (e) {
    //     e.preventDefault();

    //     if (request) {
    //         request.abort();
    //     }

    //     var $form = $(this);
    //     // var $input = $form.find("input, select, button, textarea");
    //     var serializedData = $form.serialize();

    //     // $input.prop("disabled", true);

    //     request = $.ajax({
    //         url: "/scoreboard/controller.php",
    //         type: "POST",
    //         data: serializedData,
    //     });

    //     request.done(function (response, textStatus, jqXHR) {
    //         var data = $.parseJSON(response);
    //         if (data.status) {
    //             if (data.lock_gameset) {
    //             }
    //         }
    //     });
    // });

    // $("#form-score-b").on('submit', function (e) {
    //     e.preventDefault();

    //     if (request) {
    //         request.abort();
    //     }

    //     var $form = $(this);
    //     // var $input = $form.find("input, select, button, textarea");
    //     var serializedData = $form.serialize();

    //     // $input.prop("disabled", true);

    //     request = $.ajax({
    //         url: "/scoreboard/controller.php",
    //         type: "POST",
    //         data: serializedData,
    //     });

    //     request.done(function (response, textStatus, jqXHR) {
    //         var data = $.parseJSON(response);
    //         if (data.status) {
    //             if (data.lock_gameset) {
    //             }
    //         }
    //     });
    // });
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