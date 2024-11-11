<?php
session_start();
$money=0;
$_SESSION['players']=array("p","p","p");
if(isset($_SESSION["clue"])==false || $_SESSION["clue"]==""){
  $clueset = explode("\n",file_get_contents("./clues.txt"));
  $c=$clueset[rand(0,count($clueset)-1)];
  $_SESSION['clue'] = explode(" ",explode("/",$c)[0]);
  $_SESSION['cluetext'] = explode("/",$c)[1];
  $_SESSION["lettersGuessed"] =array_diff($_SESSION['lettersGuessed'],$_SESSION['lettersGuessed']);
}
if (!isset($_SESSION["spinMoney"])){
  $_SESSION["spinMoney"] = "$0";
}
else{
  if($_SESSION['spinMoney']=='Bankrupt'){
    $money=-1;
  }else{
    $money = intval(substr($_SESSION['spinMoney'],1));
  }

}
if (!isset($_SESSION['round'])){
  $_SESSION['round']=0;
}
if(!isset($_SESSION['display'])){
  $_SESSION['display']= true;
}
if (isset($_SESSION["gameMoney1"])==false){
  $_SESSION["gameMoney1"]= $_SESSION["gameMoney2"]= $_SESSION["gameMoney3"]= $_SESSION["roundMoney1"]=$_SESSION["roundMoney2"]= $_SESSION["roundMoney3"]=0;
}
if (isset($_SESSION['players'])==false){
    $_SESSION['players'] = array("p","p","p");
    $_SESSION['turn']=0;
}
if (isset($_SESSION["lettersGuessed"])==false){
    $_SESSION["lettersGuessed"]= array();
}
$consonants = array("q","j","z","x","v","k","w","y","f","b","g","h","m","p","d","c","l","s","n","t","r");

if ($_SESSION['players'][$_SESSION['turn']]=="e"){
  foreach ($consonants as $l){
    if (in_array($l,$_SESSION["lettersGuessed"])==false){
      $_POST['guess']=$l;
  }
}
}
if ($_SESSION['players'][$_SESSION['turn']]== 'm'){
  do{
    $l=$consonants[rand(0,count($consonants)-1)];
  }while(in_array($l,$_SESSION['lettersGuessed'])==false);
  $_POST['guess']=$l;
}
    

if (isset($_POST['guess']) && $_POST['guess']!='?'){
    if (in_array($_POST["guess"],$_SESSION["lettersGuessed"])==false){
      $_SESSION["lettersGuessed"][] = $_POST['guess'];
    }
}
?>
<!DOCTYPE html>
<head>
    <title>Wheel Of Fortune</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
        <div class="topHalf">
            <div class="leftSidebar">
                Round: <?php print($_SESSION['round']);?><br><br>
                Spin Money: <?php print($_SESSION['spinMoney']); ?><br><br>
                Letters Guessed:<br> <?php foreach($_SESSION['lettersGuessed'] as $key => $value) {
                 print(strtoupper($value)); 
                 if ($key!=count($_SESSION['lettersGuessed'])-1){
                  if(($key+1)%4==0){
                      print('<br>');
                  }else{
                print(',&nbsp');}
                }} ?><br>
            </div>
            <div class="board">
                <img src="./img/puzzle-thumb.jpg">
                <?php
                 $r=53.63;
                 $t=34.75;
                 $j=0;  
                 $i=0;
                 foreach ($_SESSION['clue'] as $key => $value) {
                     if ($value!="_" && $value!="|") {
                        if(in_array($value,$_SESSION["lettersGuessed"])){
                          if($value == $_POST['guess']){
                            print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/'.$value.'.png" class="dissapear" style="animation-delay:'. $j.'s"></div>');
                            $j+=.5;
                            $i++;
                          }else{
                            print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/'.$value.'.png"></div>');
                          }
                        }
                        else {
                            print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/blankSpace.png"></div>');
                        }
                    }
                    if ($value=="|"){
                        $t= $t+16;
                        $r= 53.63;
                    }
                    else{
                        $r=$r-3.35;
                    }
                 }
                 if (in_array($_POST['guess'],$consonants)){
                    if($_SESSION['turn']==0){
                      $_SESSION['roundMoney1']+=($money*$i);
                    }
                    if($_SESSION['turn']==1){
                      $_SESSION['roundMoney2']+=($money*$i);
                    }
                    if($_SESSION['turn']==2){
                      $_SESSION['roundMoney3']+=($money*$i);
                    }
                 }
                 if($i==0){
                  $_SESSION['turn']=($_SESSION['turn']+1)%3;
                 }
                ?>
            </div>
            <div class="rightSidebar">
                <?php if($_SESSION['turn']==0)print("&#8594;");?>Player 1: <?php print($_SESSION['roundMoney1']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney1']);?>$ <br>
                <?php if($_SESSION['turn']==1)print("&#8594;");?>Player 2: <?php print($_SESSION['roundMoney2']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney2']);?>$ <br>
                <?php if($_SESSION['turn']==2)print("&#8594;");?>Player 3: <?php print($_SESSION['roundMoney3']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney3']);?>$ <br>
            </div>
        </div>
        <?php if ($_SESSION['display']){
          $_SESSION['spinMoney']="?";
          if(isset($_POST['guess'])){
            print("<div class=message><h1>There's ".$i."&nbsp;".strtoupper($_POST["guess"])."(s) in the Puzzle</h1>");
            if(in_array($_POST['guess'], $consonants)){
            print('<h1>Player '.$_SESSION['turn']+1 .' Earned '.($money*$i).'$</h1>');
            }
            if($i==0){
              print("<h1>It is now Player ".$_SESSION['turn']+1 ."'s Turn");
            }
            print("</div>");
          }
          if($_SESSION['players'][$_SESSION['turn']]=="p"){
          ?>
          <div class="bottomHalf">
              <a href="./wheel.php"><div class="selectButton">
                Spin
              </div></a>

              <a href="board.php"><div class="selectButton">
                Buy a Vowel
              </div></a>
              <a href="solve.php"><div class="selectButton">
                Solve
              </div></a>
        </div>
        </body>
        <?php $_SESSION['display']=!$_SESSION['display']; exit();} }?>
        <form action="./board.php" method="post">
        <div class="bottomHalf">
              <?php
              if ($_SESSION['players'][$_SESSION['turn']]=="p"){
              if ($money==0){
                ?>
                <div class="button-group">
                <input type="radio" id="a" name="guess" value="a"/>
                <label for="a">A</label>
              </div>
              <div class="button-group">
                <input type="radio" id="e" name="guess" value="e"/>
                <label for="e">E</label>
              </div>
              <div class="button-group">
                <input type="radio" id="i" name="guess" value="i" />
                <label for="i">I</label>
              </div>              
              <div class="button-group">
                <input type="radio" id="o" name="guess" value="o"/>
                <label for="o">O</label>
              </div>
              <div class="button-group">
                <input type="radio" id="u" name="guess" value="u"/>
                <label for="u">U</label>
            </div>
            <?php
              if($_SESSION['turn']==0){
                $_SESSION['roundMoney1']-=250;
              }
              if($_SESSION['turn']==1){
                $_SESSION['roundMoney2']-=250;
              }
              if($_SESSION['turn']==2){
                $_SESSION['roundMoney3']-=250;
              }
            }else{
                ?>

              <div class="button-group">
                <input type="radio" id="b" name="guess" value="b"/>
                <label for="b">B</label>
              </div>
              <div class="button-group">
                <input type="radio" id="c" name="guess" value="c"/>
                <label for="c">C</label>
              </div>
              <div class="button-group">
                <input type="radio" id="d" name="guess" value="d" />
                <label for="d">D</label>
              </div>              
              <div class="button-group">
                <input type="radio" id="f" name="guess" value="f"/>
                <label for="f">F</label>
              </div>
              <div class="button-group">
                <input type="radio" id="g" name="guess" value="g"/>
                <label for="g">G</label>
              </div>
              <div class="button-group">
                <input type="radio" id="h" name="guess" value="h" />
                <label for="h">H</label>
              </div>
              <div class="button-group">
                <input type="radio" id="j" name="guess" value="j" />
                <label for="j">J</label>
              </div>
              <div class="button-group">
                <input type="radio" id="k" name="guess" value="k" />
                <label for="k">K</label>
              </div>
              <div class="button-group">
                <input type="radio" id="l" name="guess" value="l" />
                <label for="l">L</label>
              </div>
              <div class="button-group">
                <input type="radio" id="m" name="guess" value="m" />
                <label for="m">M</label>
              </div>
              <div class="button-group">
                <input type="radio" id="n" name="guess" value="n" />
                <label for="n">N</label>
              </div>
              <div class="button-group">
                <input type="radio" id="p" name="guess" value="p" />
                <label for="p">P</label>
              </div>
              <div class="button-group">
                <input type="radio" id="q" name="guess" value="q" />
                <label for="q">Q</label>
              </div>
              <div class="button-group">
                <input type="radio" id="r" name="guess" value="r" />
                <label for="r">R</label>
              </div>
              <div class="button-group">
                <input type="radio" id="s" name="guess" value="s" />
                <label for="s">S</label>
              </div>
              <div class="button-group">
                <input type="radio" id="t" name="guess" value="t" />
                <label for="t">T</label>
              </div>
              <div class="button-group">
                <input type="radio" id="v" name="guess" value="v" />
                <label for="v">V</label>
              </div>
              <div class="button-group">
                <input type="radio" id="w" name="guess" value="w" />
                <label for="w">W</label>
              </div>
              <div class="button-group">
                <input type="radio" id="x" name="guess" value="x" />
                <label for="x">X</label>
              </div>
              <div class="button-group">
                <input type="radio" id="y" name="guess" value="y" />
                <label for="y">Y</label>
              </div>
              <div class="button-group">
                <input type="radio" id="z" name="guess" value="z" />
                <label for="z">Z</label>
              </div>
            <?php }  $_SESSION['display']=!$_SESSION['display'];}?>
        </div>
            <input type="submit">
        </form>

    </body> 