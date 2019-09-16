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
            <div id="scoreboard">
                <table>
                    <thead>
                        <tr>
                            <td class="score-logo"></td>
                            <td class="td-w score-team"><span id="set-num-team" class="set-num">Set X</span></td>
                            <td class="td-w score-player"><span id="set-num-player" class="set-num">Set X</span></td>
                            <td class="score-timer"></td>
                            <td class="score-point-1 point-group-3"></td>
                            <td class="score-point-2 point-group-3"></td>
                            <td class="score-point-3 point-group-3"></td>
                            <td class="score-point-4 d-none"></td>
                            <td class="score-point-5 d-none"></td>
                            <td class="score-point-6 d-none"></td>
                            <td class="align-middle score-total"></td>
                            <td class="align-middle score-gametotal">Total</td>
                            <td class="score-setpoint"></td>
                            <td class="score-gamepoint"><span>Set pts</span></td>
                            <td class="score-desc"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="score-logo"><div><img id="logo-a" class="img-fluid" src="uploads/no-image.png"></div></td>
                            <td class="td-w score-team"><div><span id="team-a" class="">Team A</span></div></td>
                            <td class="td-w score-player"><div><span id="player-a" class="">Player A</span></div></td>
                            <td class="score-timer"><div><span id="timer-a">120s</span></div></td>
                            <td class="score-point-1 point-group-3"><div><span id="point-a-1">0</span></div></td>
                            <td class="score-point-2 point-group-3"><div><span id="point-a-2">0</span></div></td>
                            <td class="score-point-3 point-group-3"><div><span id="point-a-3">0</span></div></td>
                            <td class="score-point-4 d-none"><div><span id="point-a-4">0</span></div></td>
                            <td class="score-point-5 d-none"><div><span id="point-a-5">0</span></div></td>
                            <td class="score-point-6 d-none"><div><span id="point-a-6">0</span></div></td>
                            <td class="align-middle score-total"><div><span id="total-a" class="">0</span></div></td>
                            <td class="align-middle score-gametotal"><div><span id="gametotal-a" class="">0</span></div></td>
                            <td class="score-setpoint"><div><span id="setpoints-a">0</span></div></td>
                            <td class="score-gamepoint"><div><span id="gamepoints-a">0</span></div></td>
                            <td class="td-w align-middle score-desc"><div><span id="desc-a">WINNER</span></div></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="score-logo"><div class="align-middle"><img id="logo-b" class="img-fluid" src="uploads/no-image.png"></div></td>
                            <td class="td-w score-team"><div><span id="team-b" class="">Team B</span></div></td>
                            <td class="td-w score-player"><div><span id="player-b" class="">Player B</span></div></td>
                            <td class="score-timer"><div><span id="timer-b">120s</span></div></td>
                            <td class="score-point-1 point-group-3"><div><span id="point-b-1">0</span></div></td>
                            <td class="score-point-2 point-group-3"><div><span id="point-b-2">0</span></div></td>
                            <td class="score-point-3 point-group-3"><div><span id="point-b-3">0</span></div></td>
                            <td class="score-point-4 d-none"><div><span id="point-b-4">0</span></div></td>
                            <td class="score-point-5 d-none"><div><span id="point-b-5">0</span></div></td>
                            <td class="score-point-6 d-none"><div><span id="point-b-6">0</span></div></td>
                            <td class="align-middle score-total"><div><span id="total-b" class="">0</span></div></td>
                            <td class="align-middle score-gametotal"><div><span id="gametotal-b" class="">0</span></div></td>
                            <td class="score-setpoint"><div><span id="setpoints-b">0</span></div></td>
                            <td class="score-gamepoint"><div><span id="gamepoints-b">0</span></div></td>
                            <td class="td-w align-middle score-desc"><div><span id="desc-b">WINNER</span></div></td>
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