$(document).ready(function () {

    var timer = null;
    var interval = 1000;

    getLiveGameScoreboard();


    function startLoadScoreboard() {
        if (timer !== null) return;
        timer = setInterval(function () {
            getLiveGameScoreboard();
        }, interval);
    }

    // function stopLoadScoreboard() {
    //     if (timer == null) return;
    //     clearInterval(timer);
    //     timer = null;
    // }

    function getLiveGameScoreboard() {
        $.ajax({
            type: "get",
            // url: "/scoreboard/controller.php?GetWebScoreboard=live&mode=" + mode,
            //url: "/scoreboard/controller.php?GetWebScoreboard=live",
            url: "/scoreboard/controller.php?livegame_get=scoreboard",
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $("#live-scoreboard table").html(response.style_config);
                }else{
                    $("#live-scoreboard table").html('');
                }
                startLoadScoreboard();
            }
        });
    }
});