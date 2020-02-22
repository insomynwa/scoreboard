$(document).ready(function() {
  var timer = null;
  var interval = 1000;
  var init_board = 0;

  getLiveGameScoreboard();

  function startLoadScoreboard() {
    if (timer !== null) return;
    timer = setInterval(function() {
      getLiveGameScoreboard();
    }, interval);
  }

  // function stopLoadScoreboard() {
  //     if (timer == null) return;
  //     clearInterval(timer);
  //     timer = null;
  // }

  function setHide(element, toggleClass) {
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

  function getLiveGameScoreboard() {
    $.ajax({
      type: "get",
      // url: "/scoreboard/controller.php?GetWebScoreboard=live&mode=" + mode,
      //url: "/scoreboard/controller.php?GetWebScoreboard=live",
      url: "/scoreboard/controller.php?livegame_get=scoreboard",
      dataType: "json",
      success: function(response) {
        if (response.status) {
          var board = response.livegame["scoreboard"];
          if (!$.isEmptyObject(board)) {
            var style_config = board.style_config,
              // score_data = preview.score_data,
              sets = board.sets,
              cont_a = board.contestants[0],
              cont_b = board.contestants[1],
              set_str = "Set X",
              set_str_helper = "";

            if (board.bowstyle_id == 1) {
              // Recurve
              set_str = `Set ${sets.curr_set}`;
            } else if (board.bowstyle_id == 2) {
              // Compound
              set_str = `Set ${sets.curr_set} of ${sets.end_set}`;
            }
            if (style_config.team_vc == "") {
              set_str_helper = "hide";
            }

            setHide($(".live-scoreboard-logo"), `${style_config.logo_vc}`);
            setHide($(".live-scoreboard-team"), `${style_config.team_vc}`);
            setHide($(".live-scoreboard-player"), `${style_config.player_vc}`);
            setHide($(".live-scoreboard-player span"), `${set_str_helper}`);
            setHide($(".live-scoreboard-timer"), `${style_config.timer_vc}`);
            setHide($(".live-scoreboard-score1"), `${style_config.score1_vc}`);
            setHide($(".live-scoreboard-score2"), `${style_config.score2_vc}`);
            setHide($(".live-scoreboard-score3"), `${style_config.score3_vc}`);
            setHide($(".live-scoreboard-score4"), `${style_config.score4_vc}`);
            setHide($(".live-scoreboard-score5"), `${style_config.score5_vc}`);
            setHide($(".live-scoreboard-score6"), `${style_config.score6_vc}`);
            setHide($(".live-scoreboard-setpoint"), `${style_config.setpoint_vc}`);
            setHide($(".live-scoreboard-setscore"), `${style_config.setscore_vc}`);
            setHide($(".live-scoreboard-gamepoint"), `${style_config.gamepoint_vc}`);
            setHide($(".live-scoreboard-gamescore"), `${style_config.gamescore_vc}`);
            setHide($(".live-scoreboard-desc"), `${style_config.description_vc}`);
            
            $(".live-scoreboard-team span").html(set_str);
            $(".live-scoreboard-player span").html(set_str);

            $("#cont-a-logo").attr("src", cont_a.logo);
            $("#cont-a-team").html(cont_a.team);
            $("#cont-a-player").html(cont_a.player);
            $("#cont-a-score_timer").html(cont_a.score_timer);
            $("#cont-a-score_1").html(cont_a.score_1);
            $("#cont-a-score_2").html(cont_a.score_2);
            $("#cont-a-score_3").html(cont_a.score_3);
            $("#cont-a-score_4").html(cont_a.score_4);
            $("#cont-a-score_5").html(cont_a.score_5);
            $("#cont-a-score_6").html(cont_a.score_6);
            $("#cont-a-set_points").html(cont_a.set_points);
            $("#cont-a-set_scores").html(cont_a.set_scores);
            $("#cont-a-game_points").html(cont_a.game_points);
            $("#cont-a-game_scores").html(cont_a.game_scores);
            $("#cont-a-desc").html(cont_a.desc);

            $("#cont-b-logo").attr("src", cont_b.logo);
            $("#cont-b-team").html(cont_b.team);
            $("#cont-b-player").html(cont_b.player);
            $("#cont-b-score_timer").html(cont_b.score_timer);
            $("#cont-b-score_1").html(cont_b.score_1);
            $("#cont-b-score_2").html(cont_b.score_2);
            $("#cont-b-score_3").html(cont_b.score_3);
            $("#cont-b-score_4").html(cont_b.score_4);
            $("#cont-b-score_5").html(cont_b.score_5);
            $("#cont-b-score_6").html(cont_b.score_6);
            $("#cont-b-set_points").html(cont_b.set_points);
            $("#cont-b-set_scores").html(cont_b.set_scores);
            $("#cont-b-game_points").html(cont_b.game_points);
            $("#cont-b-game_scores").html(cont_b.game_scores);
            $("#cont-b-desc").html(cont_b.desc);

            // $("#live-scoreboard table").html(str);
            if(init_board == 0){
                // setHide($("#live-scoreboard"),'');
                // $("#live-scoreboard table").removeClass("hide-board").removeClass("hide").addClass("show-board");
                $("#live-scoreboard table").animate({left: "0px", opacity: '1'}, 2000, function(){
                    init_board = 1;
                });
            }
          } else {
            if(init_board == 1){
                // setHide($("#live-scoreboard"),'');
                // $("#live-scoreboard table").removeClass("show-board").addClass("hide-board").addClass("hide");
                $("#live-scoreboard table").animate({left: "-1920px", opacity: '0'}, 2000, function(){
                    init_board = 0;
                });
            }
            // setHide($("#live-scoreboard table"),'hide');
          }
        } else {
            setHide($("#live-scoreboard table"),'hide');
        //   $("#live-scoreboard table").html("");
        }
        startLoadScoreboard();
      }
    });
  }
});
