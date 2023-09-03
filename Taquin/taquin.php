<div class="grid grid-taquin">
  <div class="item" data-n='1'>1</div>
  <div class="item" data-n='2'>2</div>
  <div class="item" data-n='3'>3</div>
  <div class="item" data-n='4'>4</div>
  <div class="item" data-n='5'>5</div>
  <div class="item" data-n='6'>6</div>
  <div class="item" data-n='7'>7</div>
  <div class="item" data-n='8'>8</div>
  <div class="item" data-n='9'>9</div>
  <div class="item" data-n='10'>10</div>
  <div class="item" data-n='11'>11</div>
  <div class="item" data-n='12'>12</div>
  <div class="item" data-n='13'>13</div>
  <div class="item" data-n='14'>14</div>
  <div class="item" data-n='15'>15</div>
  <div class="special" data-n='16'></div>
</div>
<div class="win">
  FELICITATION !
</div>

<style>
  * {
  margin: 0;
}

::selection {
  background: transparent;
}
body {
  background: #1e1f29;
  min-height: 120vh;
  overflow: hidden;
  font-family: sans-serif;
}
.grid-taquin
{
  background: #1e1f29;
  display: flex;
  min-height: 100vh;
  overflow: hidden;
  font-family: sans-serif;
}
.grid {
  position: relative;
  width: 400px;
  height: 400px;
  margin: auto;
}
.win {
  position: absolute;
  width: 100vw;
  height: 100vh;
  line-height: 100vh;
  font-size: 200px;
  text-align: center;
  font-weight: bold;
  color: #fff;
  opacity: 0;
  z-index: 0;
}
.item {
  z-index: 999;
  position: absolute;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: hsl(160, 100%, 50%);
  text-align: center;
  line-height: 100px;
  font-size: 24px;
  color: #1e1f29;
  cursor: pointer;
}

.special {
  position: absolute;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #fff2;
}
/*
.item:nth-child(1){background: hsl(20,100%,50%)}
.item:nth-child(2){background: hsl(40,100%,50%)}
.item:nth-child(3){background: hsl(60,100%,50%)}
.item:nth-child(4){background: hsl(80,100%,50%)}
.item:nth-child(5){background: hsl(100,100%,50%)}
.item:nth-child(6){background: hsl(120,100%,50%)}
.item:nth-child(7){background: hsl(140,100%,50%)}
.item:nth-child(8){background: hsl(160,100%,50%)}
.item:nth-child(9){background: hsl(180,100%,50%)}
.item:nth-child(10){background: hsl(200,100%,50%)}
.item:nth-child(11){background: hsl(220,100%,50%)}
.item:nth-child(12){background: hsl(240,100%,50%)}
.item:nth-child(13){background: hsl(260,100%,50%)}
.item:nth-child(14){background: hsl(280,100%,50%)}
.item:nth-child(15){background: hsl(300,100%,50%)}
*/
</style>
<!-- partial -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js'></script>
<script>
    var grid = document.querySelector(".grid");
var special = document.querySelector(".special");
var items;
var rang = [4, 4, 4, 4, 3, 3, 3, 3, 2, 2, 2, 2, 1, 1, 1, 1];

// melange et test
function melangeTest() {
  // je mélange
  for (var i = grid.children.length; i >= 0; i--) {
    grid.appendChild(grid.children[(Math.random() * i) | 0]);
  }
  
  items = document.querySelectorAll(".grid>div");

  // je teste
  var melange = 0;
  var pos;

  for (var i = 0; i <= 15; i++) {
    if (Number(items[i].dataset.n) === 16) {
      pos = rang[i];
    }
    for (var j = i + 1; j <= 15; j++) {
      let a = Number(items[i].dataset.n);
      let b = Number(items[j].dataset.n);
      if (a > b) {
        if (a !== 16 && b !== 16) {
          melange++;
        } // if
      } // if
    } // for j
  } // for i
  if((pos + melange)%2 === 0){
    // on remélange
    melangeTest();
  }else{
    console.log(pos,melange);
  }
} // function

melangeTest();



TweenMax.set(".grid>div", {
  x: function(i) {
    return (i % 4) * 100;
  },
  y: function(i) {
    return Math.floor(i / 4) * 100;
  }
});

function distance(r1, r2) {
  var a = r1.x - r2.x;
  var b = r1.y - r2.y;
  return Math.sqrt(a * a + b * b);
}

grid.addEventListener("click", function(e) {
  if (e.target.className === "item") {
    let sRect = special._gsTransform;
    let tRect = e.target._gsTransform;
    if (distance(sRect, tRect) <= 100) {
      TweenMax.to(".special", 0.2, {
        x: tRect.x,
        y: tRect.y
      });
      TweenMax.to(e.target, 0.2, {
        x: sRect.x,
        y: sRect.y,
        onComplete: checkLaWin
      });
    } // fin du if distance
  } // fin du if target
});

function checkLaWin() {
  var score = 0;
  for (var i = 0; i < items.length; i++) {
    let n = Number(items[i].dataset.n) - 1;
    if (
      items[i]._gsTransform.x === (n % 4) * 100 &&
      items[i]._gsTransform.y === Math.floor(n / 4) * 100
    ) {
      score++;
    }
  }
  if (score === 16) {
    var tl = new TimelineMax();
    tl.fromTo(".win", 0.4, { opacity: 0, scale: 0 }, { opacity: 1, scale: 1 });
    tl.staggerTo(
      ".grid>div",
      2,
      {
        opacity: 0,
        rotation: function() {
          return Math.random() * 720;
        },
        x: function() {
          return Math.random() * 1000 + 1000 * (Math.random() > 0.5 ? -1 : 1);
        },
        y: function() {
          return Math.random() * 1000 + 1000 * (Math.random() > 0.5 ? -1 : 1);
        },
        ease: Power4.easeOut
      },
      0.01
    );
  }
}
</script>
<div class="titreSection">
	<Accueil><a href="../jeux/">Retourner en arrière</a>
</div>