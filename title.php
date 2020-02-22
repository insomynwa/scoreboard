<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Title | Scoreboard</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/title.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div id="wrapper">
            <div id="live-scoreboard">
                <table class=''>
                    <thead>
                        <tr>
                            <td class="live-scoreboard-logo text-light small"></td>
                            <td class="td-w live-scoreboard-team"><span></span></td>
                            <td class="td-w live-scoreboard-player"><span></span></td>
                            <td class="live-scoreboard-timer"></td>
                            <td class="live-scoreboard-score1"></td>
                            <td class="live-scoreboard-score2"></td>
                            <td class="live-scoreboard-score3"></td>
                            <td class="live-scoreboard-score4"></td>
                            <td class="live-scoreboard-score5"></td>
                            <td class="live-scoreboard-score6"></td>
                            <td class="live-scoreboard-setpoint"></td>
                            <td class="live-scoreboard-setscore"></td>
                            <td class="live-scoreboard-gamepoint"><span>Set pts</span></td>
                            <td class="live-scoreboard-gamescore"><span>Total</span></td>
                            <td class="td-w live-scoreboard-desc"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="live-scoreboard-logo text-light small"><div><img id="cont-a-logo" src=""></div></td>
                            <td class="live-scoreboard-team"><div><span id="cont-a-team"></span></div></td>
                            <td class="live-scoreboard-player"><div><span id="cont-a-player"></span></div></td>
                            <td class="live-scoreboard-timer"><div><span id="cont-a-score_timer"></span></div></td>
                            <td class="live-scoreboard-score1"><div><span id="cont-a-score_1"></span></div></td>
                            <td class="live-scoreboard-score2"><div><span id="cont-a-score_2"></span></div></td>
                            <td class="live-scoreboard-score3"><div><span id="cont-a-score_3"></span></div></td>
                            <td class="live-scoreboard-score4"><div><span id="cont-a-score_4"></span></div></td>
                            <td class="live-scoreboard-score5"><div><span id="cont-a-score_5"></span></div></td>
                            <td class="live-scoreboard-score6"><div><span id="cont-a-score_6"></span></div></td>
                            <td class="live-scoreboard-setpoint"><div><span id="cont-a-set_points"></span></div></td>
                            <td class="live-scoreboard-setscore"><div><span id="cont-a-set_scores"></span></div></td>
                            <td class="live-scoreboard-gamepoint"><div><span id="cont-a-game_points"></span></div></td>
                            <td class="live-scoreboard-gamescore"><div><span id="cont-a-game_scores"></span></div></td>
                            <td class="live-scoreboard-desc"><div><span id="cont-a-desc"></span></div></td>
                        </tr>
                        <tr>
                            <td class="live-scoreboard-logo text-light small"><div><img id="cont-b-logo" src=""></div></td>
                            <td class="live-scoreboard-team"><div><span id="cont-b-team"></span></div></td>
                            <td class="live-scoreboard-player"><div><span id="cont-b-player"></span></div></td>
                            <td class="live-scoreboard-timer"><div><span id="cont-b-score_timer"></span></div></td>
                            <td class="live-scoreboard-score1"><div><span id="cont-b-score_1"></span></div></td>
                            <td class="live-scoreboard-score2"><div><span id="cont-b-score_2"></span></div></td>
                            <td class="live-scoreboard-score3"><div><span id="cont-b-score_3"></span></div></td>
                            <td class="live-scoreboard-score4"><div><span id="cont-b-score_4"></span></div></td>
                            <td class="live-scoreboard-score5"><div><span id="cont-b-score_5"></span></div></td>
                            <td class="live-scoreboard-score6"><div><span id="cont-b-score_6"></span></div></td>
                            <td class="live-scoreboard-setpoint"><div><span id="cont-b-set_points"></span></div></td>
                            <td class="live-scoreboard-setscore"><div><span id="cont-b-set_scores"></span></div></td>
                            <td class="live-scoreboard-gamepoint"><div><span id="cont-b-game_points"></span></div></td>
                            <td class="live-scoreboard-gamescore"><div><span id="cont-b-game_scores"></span></div></td>
                            <td class="live-scoreboard-desc"><div><span id="cont-b-desc"></span></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/title.js"></script>
    </body>
</html>