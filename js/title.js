$(document).ready(function () {

    var active_mode = 0;
    var timer = null;
    var interval = 1000;
    var firstLoad = true;

    // GetWebScoreboard(active_mode);
    GetWebScoreboard();
    /* setInterval(function () {
        GetWebScoreboard(active_mode);
    }, 1000); */

    /* LoadingData();

    function LoadingData() {
        $.ajax({
            type: "get",
            url: "/scoreboard/controller.php?GetConfig=all",
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var config = response.config;

                    GetWebScoreboard(config.active_mode);

                    if ((config.time_interval != interval) || (config.active_mode != active_mode)) {
                        StopLoadData();
                        active_mode = config.active_mode;
                        interval = config.time_interval;
                        StartLoadData();
                    } else {
                    }

                }
            }
        });
    } */

    function StartLoadData() {
        if (timer !== null) return;
        timer = setInterval(function () {
            // GetWebScoreboard(active_mode);
            GetWebScoreboard();
        }, interval);
    }

    function StopLoadData() {
        if (timer == null) return;
        clearInterval(timer);
        timer = null;
    }

    // function GetWebScoreboard(mode) {
    function GetWebScoreboard() {
        $.ajax({
            type: "get",
            // url: "/scoreboard/controller.php?GetWebScoreboard=live&mode=" + mode,
            //url: "/scoreboard/controller.php?GetWebScoreboard=live",
            url: "/scoreboard/controller.php?title_get=scoreboard",
            dataType: "json",
            success: function (response) {

                if (response.status) {
                    var scoreboard = response.scoreboard;
                    if (scoreboard.live_game == 0) {
                        $("#scoreboard").hide();
                    } else {
                        $("#scoreboard").show();
                        if (firstLoad) {
                            firstLoad = false;
                            active_mode = response.active_mode;
                        } else {
                            if (active_mode != response.active_mode) {
                                active_mode = response.active_mode;
                                StopLoadData();
                            }
                        }

                        Scoreboard_Config(scoreboard.config);


                        // if (scoreboard.gamemode == 1) {
                        //     $(".score-team").show();
                        //     $(".score-player").hide();
                        // } else {
                        //     if (scoreboard.team_a == "" && scoreboard.team_b == "") {
                        //         $(".score-team").hide();
                        //         $("#set-num-player").show();
                        //     } else {
                        //         $(".score-team").show();
                        //         $("#set-num-player").hide();
                        //     }
                        //     $(".score-player").show();
                        // }
                        SetBoard(scoreboard, active_mode);

                        SetContestantAScore(scoreboard);
                        SetContestantBScore(scoreboard);
                    }
                } else {
                    $("#scoreboard").hide();
                }
                StartLoadData();

            }
        });
    }

    function SetContestantAScore(contestant = null) {
        $("#logo-a").attr("src", contestant.logo_a);
        $("#team-a").text(contestant.team_a);
        $("#player-a").text(contestant.player_a);

        if (contestant.timer_a < 10) {
            if (!$("#timer-a").hasClass('time-warning')) {
                $("#timer-a").addClass('time-warning');
            }
        } else {
            if ($("#timer-a").hasClass('time-warning')) {
                $("#timer-a").removeClass('time-warning');
            }
        }
        $("#timer-a").text(contestant.timer_a + "s");
        $("#point-a-1").text(contestant.point_1a);
        $("#point-a-2").text(contestant.point_2a);
        $("#point-a-3").text(contestant.point_3a);
        $("#point-a-4").text(contestant.point_4a);
        $("#point-a-5").text(contestant.point_5a);
        $("#point-a-6").text(contestant.point_6a);
        $("#total-a").text(contestant.total_a);
        $("#gametotal-a").text(contestant.game_total_points_a);
        $("#setpoints-a").text(contestant.setpoints_a);
        $("#gamepoints-a").text(contestant.gamepoints_a);

        if (contestant.desc_a == "" || contestant.desc_a == null) {
            $("#desc-a").parent().hide();
        } else {
            if ((contestant.desc_a).trim() == "") {
                $("#desc-a").parent().hide();
            } else {
                $("#desc-a").parent().show();
            }
        }
        $("#desc-a").text(contestant.desc_a);
    }

    function SetContestantBScore(contestant = null) {
        $("#logo-b").attr("src", contestant.logo_b);
        $("#team-b").text(contestant.team_b);
        $("#player-b").text(contestant.player_b);
        if (contestant.timer_b < 10) {
            if (!$("#timer-b").hasClass('time-warning')) {
                $("#timer-b").addClass('time-warning');
            }
        } else {
            if ($("#timer-b").hasClass('time-warning')) {
                $("#timer-b").removeClass('time-warning');
            }
        }
        $("#timer-b").text(contestant.timer_b + "s");
        $("#point-b-1").text(contestant.point_1b);
        $("#point-b-2").text(contestant.point_2b);
        $("#point-b-3").text(contestant.point_3b);
        $("#point-b-4").text(contestant.point_4b);
        $("#point-b-5").text(contestant.point_5b);
        $("#point-b-6").text(contestant.point_6b);
        $("#total-b").text(contestant.total_b);
        $("#gametotal-b").text(contestant.game_total_points_b);
        $("#setpoints-b").text(contestant.setpoints_b);
        $("#gamepoints-b").text(contestant.gamepoints_b);

        if (contestant.desc_b == "" || contestant.desc_b == null) {
            $("#desc-b").parent().hide();
        } else {
            if ((contestant.desc_b).trim() == "") {
                $("#desc-b").parent().hide();
            } else {
                $("#desc-b").parent().show();
            }
        }
        $("#desc-b").text(contestant.desc_b);
    }

    function Scoreboard_Config(config) {
        // console.log(config['logo']);
        SetScoreboardVisibility($(".score-logo"), config['logo']['visibility']);
        SetScoreboardVisibility($(".score-team"), config['team']['visibility']);
        SetScoreboardVisibility($(".score-player"), config['player']['visibility']);
        SetScoreboardVisibility($(".score-timer"), config['timer']['visibility']);
        SetScoreboardVisibility($(".score-point-1"), config['p1']['visibility']);
        SetScoreboardVisibility($(".score-point-2"), config['p2']['visibility']);
        SetScoreboardVisibility($(".score-point-3"), config['p3']['visibility']);
        SetScoreboardVisibility($(".score-point-4"), config['p4']['visibility']);
        SetScoreboardVisibility($(".score-point-5"), config['p5']['visibility']);
        SetScoreboardVisibility($(".score-point-6"), config['p6']['visibility']);
        SetScoreboardVisibility($(".score-total"), config['set_total_points']['visibility']);
        SetScoreboardVisibility($(".score-gametotal"), config['game_total_points']['visibility']);
        SetScoreboardVisibility($(".score-setpoint"), config['set_points']['visibility']);
        SetScoreboardVisibility($(".score-gamepoint"), config['game_points']['visibility']);
        SetScoreboardVisibility($(".score-desc"), config['description']['visibility']);
    }

    function SetScoreboardVisibility(elementTarget, visibility) {
        if (visibility == false) {
            elementTarget.addClass("d-none");
        } else {
            elementTarget.removeClass("d-none");
        }
    }

    function SetBoard(scoreboard, mode) {
        // 1=recurve ~ 2=compound
        // console.log(bowstyle);
        var bowstyle_id = scoreboard.bowstyle_id;
        if (bowstyle_id == 1) {
            $(".set-num").text( "Set " + scoreboard.set);
            if (mode == 4) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").show();
                $(".score-gametotal").hide();
                $(".score-setpoint").hide();
                $(".score-gamepoint").show();
                $(".score-desc").hide();
            } else if (mode == 5) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").show();
                $(".score-point-2").show();
                $(".score-point-3").show();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").show();
                $(".score-gametotal").hide();
                $(".score-setpoint").hide();
                $(".score-gamepoint").show();
                $(".score-desc").hide();
            } else if (mode == 6) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").hide();
                $(".score-gametotal").hide();
                $(".score-setpoint").hide();
                $(".score-gamepoint").show();
                $(".score-desc").hide();
            } else {
                $(".score-logo").hide();
                $(".score-team").hide();
                $(".score-player").hide();
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").hide();
                $(".score-gametotal").hide();
                $(".score-setpoint").hide();
                $(".score-gamepoint").hide();
                $(".score-desc").hide();
            }
        }
        else if (bowstyle_id == 2) {
            $(".set-num").text( "Set " + scoreboard.set + " of " + scoreboard.num_set);
            if (mode == 7) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").show();
                $(".score-gametotal").show();
                $(".score-setpoint").hide();
                $(".score-gamepoint").hide();
                $(".score-desc").hide();
            } else if (mode == 8) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").show();
                $(".score-point-2").show();
                $(".score-point-3").show();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").show();
                $(".score-gametotal").show();
                $(".score-setpoint").hide();
                $(".score-gamepoint").hide();
                $(".score-desc").hide();
            } else if (mode == 9) {
                $(".score-logo").hide();
                if (scoreboard.gamemode == 1) {
                    $(".score-team").show();
                } else {
                    if (scoreboard.team_a == "" || scoreboard.team_b == "") {
                        $(".score-team").hide();
                        $("#set-num-player").show();
                    } else {
                        $(".score-team").show();
                        $("#set-num-player").hide();
                    }
                }
                if (scoreboard.gamemode == 1) {
                    $(".score-player").hide();
                } else {
                    $(".score-player").show();
                }
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").hide();
                $(".score-gametotal").show();
                $(".score-setpoint").hide();
                $(".score-gamepoint").hide();
                $(".score-desc").hide();
            } else {
                $(".score-logo").hide();
                $(".score-team").hide();
                $(".score-player").hide();
                $(".score-timer").hide();
                $(".score-point-1").hide();
                $(".score-point-2").hide();
                $(".score-point-3").hide();
                $(".score-point-4").hide();
                $(".score-point-5").hide();
                $(".score-point-6").hide();
                $(".score-total").hide();
                $(".score-gametotal").hide();
                $(".score-setpoint").hide();
                $(".score-gamepoint").hide();
                $(".score-desc").hide();
            }
        }
        /* if (mode == 1) {
            // $(".score-point").hide();

            $(".point-group-3").hide();
            $(".score-timer").show();
            $(".score-total").show();
        }
        else if (mode == 2) {
            $(".score-timer").hide();

            // $(".score-point").show();

            $(".point-group-3").show();
            $(".score-total").show();
        }
        else if (mode == 3) {
            $(".score-total").hide();

            // $(".score-point").hide();

            $(".point-group-3").hide();
            $(".score-timer").hide();
        }
        else if (mode == 4) {
            // 0=default ~ 1=recurve ~ 2=compound

            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        }
        else if (mode == 5) {
            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        }
        else if (mode == 6) {
            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        }
        else if (mode == 7) {
            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        }
        else if (mode == 8) {
            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        }
        else if (mode == 9) {
            // $(".score-total").hide();

            // $(".score-point").hide();

            // $(".point-group-3").hide();
            // $(".score-timer").hide();
        } */
    }
});