<!DOCTYPE HTML>
<html lang="en">
  <head>
    <title>WebGL Globe</title>
    <meta charset="utf-8">
    <style type="text/css">
      html {
        height: 100%;
      }
      body {
        margin: 0;
        padding: 0;
        background: #000000 url(/globe/loading.gif) center center no-repeat;
        color: #ffffff;
        font-family: sans-serif;
        font-size: 13px;
        line-height: 20px;
        height: 100%;
      }

      #alerts {
        font-size: 11px;
        background-color: rgba(0,0,0,0.8);
        border-radius: 3px;
        right: 10px;
        padding: 10px;
        position: absolute;
        top: 20px;
      }

      .alert {
        background-color: #9C3E3E;
        padding: 11px;
        border-radius: 5px;
        opacity: 0.9;
      }

      #currentInfo {
        width: 270px;
        position: absolute;
        left: 20px;
        top: 63px;

        background-color: rgba(0,0,0,0.2);

        border-top: 1px solid rgba(255,255,255,0.4);
        padding: 10px;
      }

      a {
        color: #aaa;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
      }

      .bull {
        padding: 0 5px;
        color: #555;
      }

      #title {
        position: absolute;
        top: 20px;
        width: 270px;
        left: 20px;
        background-color: rgba(0,0,0,0.2);
        border-radius: 3px;
        font: 20px Georgia;
        padding: 10px;
      }

      .year {
        font: 16px Georgia;
        line-height: 26px;
        height: 30px;
        text-align: center;
        float: left;
        width: 90px;
        color: rgba(255, 255, 255, 0.4);

        cursor: pointer;
        -webkit-transition: all 0.1s ease-out;
      }

      .year:hover, .year.active {
        font-size: 23px;
        color: #fff;
      }

      #ce span {
        display: none;
      }

      #ce {
        width: 107px;
        height: 55px;
        display: block;
        position: absolute;
        bottom: 15px;
        left: 20px;
        background: url(/globe/ce.png);
      }


    </style>
  </head>
  <body>

  <div id="container"></div>

  <div id="currentInfo">
    <span id="year1990" class="year">Realtime</span>
    <span id="year1995" class="year">Past Hour</span>
    <span id="year2000" class="year">Past Day</span>
  </div>

  <div id="alerts">
    <h2>Alerts</h2>
    <div class="alert">
      Response time of 1023ms measured from <a href="">Tokyo</a> to endpoint <a href="">Load Balancer 1</a>
    </div>
  </div>

  <div id="title">
    Application Response Times
  </div>

  <script type="text/javascript" src="/globe/third-party/Detector.js"></script>
  <script type="text/javascript" src="/globe/third-party/three.min.js"></script>
  <script type="text/javascript" src="/globe/third-party/Tween.js"></script>
  <script type="text/javascript" src="/globe/globe.js"></script>
  <script type="text/javascript">

    if(!Detector.webgl){
      Detector.addGetWebGLMessage();
    } else {

      var years = ['1990','1995','2000'];
      var container = document.getElementById('container');
      var globe = new DAT.Globe(container);

      console.log(globe);
      var i, tweens = [];
      
      var settime = function(globe, t) {
        return function() {
          new TWEEN.Tween(globe).to({time: t/years.length},500).easing(TWEEN.Easing.Cubic.EaseOut).start();
          var y = document.getElementById('year'+years[t]);
          if (y.getAttribute('class') === 'year active') {
            return;
          }
          var yy = document.getElementsByClassName('year');
          for(i=0; i<yy.length; i++) {
            yy[i].setAttribute('class','year');
          }
          y.setAttribute('class', 'year active');
        };
      };
      
      for(var i = 0; i<years.length; i++) {
        var y = document.getElementById('year'+years[i]);
        y.addEventListener('mouseover', settime(globe,i), false);
      }
      
      var xhr;
      TWEEN.start();
      
      
      xhr = new XMLHttpRequest();
      xhr.open('GET', '/globe/population909500.json', true);
      xhr.onreadystatechange = function(e) {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            window.data = data;
            for (i=0;i<data.length;i++) {
              globe.addData(data[i][1], {format: 'magnitude', name: data[i][0], animated: true});
            }
            globe.createPoints();
            settime(globe,0)();
            globe.animate();
            document.body.style.backgroundImage = 'none'; // remove loading
          }
        }
      };
      xhr.send(null);
    }

  </script>

  </body>

</html>
