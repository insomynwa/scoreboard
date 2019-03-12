<!DOCTYPE html>
<html>
<head>
<title>Scoreboard</title>

<!-- <script src="js/jquery-3.3.1.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="js/jquery-3.3.1.min.js"></script>
<!-- <script src="js/jquery-3.3.1.min.map"></script> -->

<style>
  .show-row{
    display:block;
  }
  .hide-row{
    display:none;
  }
  .show{
    display:block;
  }
  .hide{
    display:none;
  }
</style>
</head>
<body>

  <table>
    <tr>
      <th></th>
      <th><span id="sb-gameset">Set X</span></th>
      <th class="timer"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="total-point"></th>
      <th class="set-point">Set pts</th>
      <th class="description"></th>
    </tr>
    <tr>
      <td><img src="" id="sb-teamA-logo"></td>
      <td><span id="sb-teamA-name"></span></td>
      <td class="timer"><span id="sb-teamA-timer"></span></td>
      <td class="point"><span id="sb-teamA-pt1"></span></td>
      <td class="point"><span id="sb-teamA-pt2"></span></td>
      <td class="point"><span id="sb-teamA-pt3"></span></td>
      <td class="point"><span id="sb-teamA-pt4"></span></td>
      <td class="point"><span id="sb-teamA-pt5"></span></td>
      <td class="point"><span id="sb-teamA-pt6"></span></td>
      <td class="total-point"><span id="sb-teamA-totpts"></span></td>
      <td class="set-point"><span id="sb-teamA-setpts"></span></td>
      <td class="status"><span id="sb-teamA-status"></span></td>
    </tr>
    <tr>
      <td><img src="" id="sb-teamB-logo"></td>
      <td><span id="sb-teamB-name"></span></td>
      <td class="timer"><span id="sb-teamB-timer"></span></td>
      <td class="point"><span id="sb-teamB-pt1"></span></td>
      <td class="point"><span id="sb-teamB-pt2"></span></td>
      <td class="point"><span id="sb-teamB-pt3"></span></td>
      <td class="point"><span id="sb-teamB-pt4"></span></td>
      <td class="point"><span id="sb-teamB-pt5"></span></td>
      <td class="point"><span id="sb-teamB-pt6"></span></td>
      <td class="total-point"><span id="sb-teamB-totpts"></span></td>
      <td class="set-point"><span id="sb-teamB-setpts"></span></td>
      <td class="status"><span id="sb-teamB-status"></span></td>
    </tr>
  </table>

  <script src="js/score.js"></script>
</body>
</html>