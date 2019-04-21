$(document).ready(function () {

    var active_mode = 1;
    var timer = null;
    var interval = 1000;
    var firstLoad = true;

    GetWebScoreboard(active_mode);
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
            GetWebScoreboard(active_mode);
        }, interval);
    }

    function StopLoadData() {
        if (timer == null) return;
        clearInterval(timer);
        timer = null;
    }

    function GetWebScoreboard(mode) {
        $.ajax({
            type: "get",
            url: "/scoreboard/controller.php?GetWebScoreboard=live&mode=" + mode,
            dataType: "json",
            success: function (response) {
                var scoreboard = response;
                $("#set-num").text(scoreboard.set);
                SetContestantAScore(scoreboard);
                SetContestantBScore(scoreboard);
                if (firstLoad) {
                    firstLoad = false;
                    active_mode = scoreboard.active_mode;
                } else {
                    if (mode != scoreboard.active_mode) {
                        active_mode = scoreboard.active_mode;
                        StopLoadData();
                    }
                }
                SetBoard(active_mode);
                StartLoadData();

            }
        });
    }

    function SetContestantAScore(contestant = null) {
        $("#logo-a").attr("src", contestant.logo_a);
        $("#team-a").text(contestant.contestant_a);
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
        $("#setpoints-a").text(contestant.setpoints_a);
        if ((contestant.desc_a).trim() == "") {
            $("#desc-a").parent().hide();
        } else {
            $("#desc-a").parent().show();
        }
        $("#desc-a").text(contestant.desc_a);
    }

    function SetContestantBScore(contestant = null) {
        $("#logo-b").attr("src", contestant.logo_b);
        $("#team-b").text(contestant.contestant_b);
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
        $("#setpoints-b").text(contestant.setpoints_b);
        if ((contestant.desc_b).trim() == "") {
            $("#desc-b").parent().hide();
        } else {
            $("#desc-b").parent().show();
        }
        $("#desc-b").text(contestant.desc_b);
    }

    function SetBoard(mode) {
        if (mode == 1) {
            $(".score-point").hide();
            $(".score-timer").show();
            $(".score-total").show();
        }
        else if (mode == 2) {
            $(".score-timer").hide();
            $(".score-point").show();
            $(".score-total").show();
        }
        else if (mode == 3) {
            $(".score-total").hide();
            $(".score-point").hide();
            $(".score-timer").hide();
        }
    }
});