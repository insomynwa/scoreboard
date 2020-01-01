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

                                var scoreboard_styles = data.scoreboard_styles;
                                ScoreboardStyle.setOption(
                                    ScoreboardStyle.bowstyleSelect,
                                    scoreboard_styles["bowstyle"]["option"]
                                );
                                ScoreboardStyle.setOption(
                                    ScoreboardStyle.styleSelect,
                                    scoreboard_styles["option"]
                                );
                                ScoreboardStyle.setInfo(
                                    scoreboard_styles["info"]["bowstyle"],
                                    scoreboard_styles["info"]["style"]
                                );
                                ScoreboardStyle.loadPreview(
                                    scoreboard_styles["preview"]
                                );
                                ScoreboardStyle.configBtn(
                                    scoreboard_styles["config"]
                                );

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
            if (str == "") {
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
            $("#form-scoreboard-wrapper").html(scoreboard_form);
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
        loadOption: function(gamestatus_options) {
            $("#gameset-status").html(gamestatus_options);
        }
    };

    var GameMode = {
        loadRadio: function(gamemode_radios) {
            $("#gamedraw-radio-area").html(gamemode_radios);
        }
    };

    var BowStyle = {
        loadRadio: function(bowstyle_radios) {
            $("#gamedraw-radio-bowstyle-area").html(bowstyle_radios);
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
                cfg["visibility_class"]["activate_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.deactivateBtn,
                cfg["visibility_class"]["deactivate_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.saveBtn,
                cfg["visibility_class"]["save_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.cancelBtn,
                cfg["visibility_class"]["cancel_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.createBtn,
                cfg["visibility_class"]["new_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.editBtn,
                cfg["visibility_class"]["edit_btn"]
            );
            Helper.setHide(
                ScoreboardStyle.deleteBtn,
                cfg["visibility_class"]["delete_btn"]
            );
        },
        toggleColumn: function() {
            var cls = $(this).attr("data-class");
            var status = $(this).prop("checked");
            if (status) {
                $("." + cls).removeClass("hide");
                $(this).val(true);
            } else {
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
            ScoreboardStyle.previewTable.html(preview);
            if (preview == "") {
                Helper.setHide(ScoreboardStyle.previewWrapper, "hide");
            } else {
                Helper.setHide(ScoreboardStyle.previewWrapper, "");
            }
        },
        setOption: function(element, options) {
            element.html(options);
        },
        setOptionValue: function(element, val) {
            element.val(val);
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
                    Helper.setHide(ScoreboardStyle.activateBtn, "");
                    Helper.setHide(ScoreboardStyle.editBtn, "");
                    Helper.setHide(ScoreboardStyle.deleteBtn, "");
                    Helper.setHide(ScoreboardStyle.previewWrapper, "");
                } else {
                    Helper.setHide(ScoreboardStyle.activateBtn, "hide");
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
                        var ss = ScoreboardStyle;
                        ss.markCBox.prop("checked", true).val(true);

                        ss.styleNameInput.val("My Custom Style");
                        ss.styleNameInput.removeAttr("disabled");
                        ss.saveBtn
                            .val("Save")
                            .removeClass("btn-danger")
                            .addClass("btn-primary");

                        ss.visibilityTable.html(
                            data.scoreboard_styles["checkbox"]
                        );
                        ss.previewTable.html(data.scoreboard_styles["preview"]);
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
                        ss.visibilityTable.html(
                            data.scoreboard_styles["checkbox"]
                        );

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

                                ss.styleSelect
                                    .removeAttr("disabled")
                                    .html(data.scoreboard_styles["option"]);

                                ss.styleSelect.val(
                                    ScoreboardStyle.selectedStyle
                                );

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
                            ss.styleSelect
                                .removeAttr("disabled")
                                .html(data.scoreboard_styles["option"]);

                            ss.styleSelect.val(ScoreboardStyle.selectedStyle);

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
                                ss.styleSelect
                                    .removeAttr("disabled")
                                    .html(data.scoreboard_styles["option"])
                                    .val(0);

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
                                ScoreboardStyle.previewTable.html(
                                    data.scoreboard_styles["preview"]
                                );
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
                                    ScoreboardStyle.styleSelect
                                        .html(data2.scoreboard_styles["option"])
                                        .val(data.latest_id);

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
                                    ScoreboardStyle.styleSelect.html(
                                        data2.scoreboard_styles["option"]
                                    );
                                    ScoreboardStyle.styleSelect.val(
                                        ScoreboardStyle.selectedStyle
                                    );

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
                                        ScoreboardStyle.styleSelect
                                            .removeAttr("disabled")
                                            .html(
                                                data2.scoreboard_styles[
                                                    "option"
                                                ]
                                            );

                                        ScoreboardStyle.styleSelect.val(
                                            data2.live_style
                                        );

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
                                        ScoreboardStyle.styleSelect
                                            .html(
                                                data2.scoreboard_styles[
                                                    "option"
                                                ]
                                            )
                                            .val(0);

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
            Team.bindEvents();
        },
        bindEvents: function() {
            $("#create-team-button").click(this.setupCreateForm);
            $("#form-team").on("submit", this.submitForm);
            $(document)
                .on("click", ".team-delete-btn-cls", this.setupDeleteForm)
                .on("click", ".team-update-btn-cls", this.setupUpdateForm);
        },
        loadTable: function(team_table) {
            if (team_table != "") $("#team-table tbody").html(team_table);
        },
        loadOption: function(options) {
            if (options != "") {
                $("#player-team").html(options);
                $("#gamedraw-team-a").html(options);
                $("#gamedraw-team-b").html(options);
            }
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
                $("#team-logo").val("");
                $("#team-logo").removeAttr("disabled");
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
                $("#team-logo").val("");
                $("#team-logo").removeAttr("disabled");
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
                        Team.loadForm(data.team, modeget);
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
                                url: "/scoreboard/controller.php?team_get=new",
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
                                            data.scoreboard_styles;
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.bowstyleSelect,
                                            scoreboard_styles["bowstyle"][
                                                "option"
                                            ]
                                        );
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.styleSelect,
                                            scoreboard_styles["option"]
                                        );
                                        ScoreboardStyle.setInfo(
                                            scoreboard_styles["info"][
                                                "bowstyle"
                                            ],
                                            scoreboard_styles["info"]["style"]
                                        );
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
            Player.bindEvents();
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
        loadTable: function(player_table) {
            if (player_table != "") $("#player-table tbody").html(player_table);
        },
        loadOption: function(options) {
            if (options != "") {
                $("#gamedraw-player-a").html(options);
                $("#gamedraw-player-b").html(options);
            }
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
                        Player.loadForm(data.player, modeget);
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
                            url: "/scoreboard/controller.php?player_get=new",
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
                                        data.scoreboard_styles;
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.bowstyleSelect,
                                        scoreboard_styles["bowstyle"]["option"]
                                    );
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.styleSelect,
                                        scoreboard_styles["option"]
                                    );
                                    ScoreboardStyle.setInfo(
                                        scoreboard_styles["info"]["bowstyle"],
                                        scoreboard_styles["info"]["style"]
                                    );
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
                                        data.scoreboard_styles;
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.bowstyleSelect,
                                        scoreboard_styles["bowstyle"]["option"]
                                    );
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.styleSelect,
                                        scoreboard_styles["option"]
                                    );
                                    ScoreboardStyle.setInfo(
                                        scoreboard_styles["info"]["bowstyle"],
                                        scoreboard_styles["info"]["style"]
                                    );
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
                            $("#gameset-setnum").val(data.new_set);
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
                        $("#gameset-info-modal-table").html(
                            data.gameset["summary"]
                        );
                        $("#gameset-info-modal").modal();
                    }
                }
            });
        },
        loadTable: function(gameset_table) {
            if (gameset_table != "")
                $("#gameset-table tbody").html(gameset_table);
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
                        GameSet.loadForm(data.gameset, modeget);
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
                                            data.scoreboard_styles;
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.bowstyleSelect,
                                            scoreboard_styles["bowstyle"][
                                                "option"
                                            ]
                                        );
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.styleSelect,
                                            scoreboard_styles["option"]
                                        );
                                        ScoreboardStyle.setInfo(
                                            scoreboard_styles["info"][
                                                "bowstyle"
                                            ],
                                            scoreboard_styles["info"]["style"]
                                        );
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
                                            data.scoreboard_styles;
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.bowstyleSelect,
                                            scoreboard_styles["bowstyle"][
                                                "option"
                                            ]
                                        );
                                        ScoreboardStyle.setOption(
                                            ScoreboardStyle.styleSelect,
                                            scoreboard_styles["option"]
                                        );
                                        ScoreboardStyle.setInfo(
                                            scoreboard_styles["info"][
                                                "bowstyle"
                                            ],
                                            scoreboard_styles["info"]["style"]
                                        );
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
                                        data.scoreboard_styles;
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.bowstyleSelect,
                                        scoreboard_styles["bowstyle"]["option"]
                                    );
                                    ScoreboardStyle.setOption(
                                        ScoreboardStyle.styleSelect,
                                        scoreboard_styles["option"]
                                    );
                                    ScoreboardStyle.setInfo(
                                        scoreboard_styles["info"]["bowstyle"],
                                        scoreboard_styles["info"]["style"]
                                    );
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
