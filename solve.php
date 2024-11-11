<?php
session_start();
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
$right = (isset($_POST['solveGuess']) && strcasecmp($_POST['solveGuess'],$_SESSION['cluetext'])== 0);
if(isset($_POST['solveGuess'])){ 
$_SESSION['display']=!$_SESSION['display'];
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
                Letters Guessed: <?php foreach($_SESSION['lettersGuessed'] as $key => $value) {
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
                 foreach ($_SESSION['clue'] as $key => $value) {
                     if ($value!="_" && $value!="|") {
                        if(in_array($value,$_SESSION["lettersGuessed"])){
                          if($value == $_POST['guess']){
                            print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/'.$value.'.png" class="dissapear" style="animation-delay:'. $j.'s"></div>');
                            $j+=.5;
                          }else{
                            print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/'.$value.'.png"></div>');
                          }
                        }else if($right && !in_array($value,$_SESSION['lettersGuessed'])){
                          print('<div class="letterBox" style="right: '.$r.'vw; top:'.$t.'%;"><img src="./img/'.$value.'.png" class="dissapear" style="animation-delay:'. $j.'s"></div>');
                          $j+= .5;
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
                ?>
            </div>
            <div class="rightSidebar">
            <?php if($_SESSION['turn']==0)print("&#8594;");?>Player 1: <?php print($_SESSION['roundMoney1']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney1']);?>$ <br><br>
                <?php if($_SESSION['turn']==1)print("&#8594;");?>Player 2: <?php print($_SESSION['roundMoney2']);?>$ <br><br>
                Bank: <?php print($_SESSION['gameMoney2']);?>$ <br>
                <?php if($_SESSION['turn']==2)print("&#8594;");?>Player 3: <?php print($_SESSION['roundMoney3']);?>$ <br><br>
                Bank: <?php print($_SESSION['gameMoney3']);?>$ <br>
            </div>
        </div>
        <?php if(!isset($_POST['solveGuess']) || $_POST['solveGuess']=="?"){
            ?>
        <form method="post" action="solve.php">
        <div class="bottomHalf">
            <input type="text" name="solveGuess">
        </div>
        <input type="submit">
        </body>
        <?php exit();}
        if($right){
            $money=0;
            $_SESSION['clue']="";
            
            if ($_SESSION['turn']==0){
                $_SESSION['gameMoney1']+=$_SESSION['roundMoney1'];
                $money=$_SESSION['roundMoney1'];
            }else if($_SESSION['turn']==1){
                $_SESSION['gameMoney2']+=$_SESSION['roundMoney2'];
                $money=$_SESSION['roundMoney2'];
            }else{
                $_SESSION['gameMoney3']+=$_SESSION['roundMoney3'];
                $money=$_SESSION['roundMoney3'];
            }
           if ($money< 1000){
            $money=1000;
          }
            $_SESSION['round']++;
            print("<div class='bottomHalf'>");
            print('<h2>Player '. ($_SESSION["turn"]+1) .' Is Correct!<br>They Banked '. $money .'$ This Round</h2>');
                $_SESSION['round']= ($_SESSION['round']+1);
                $_SESSION['roundMoney1']= $_SESSION['roundMoney2'] = $_SESSION['roundMoney3'] = 0;
                $_SESSION['lettersGuessed']= array_diff($_SESSION['lettersGuessed'], $_SESSION['lettersGuessed']);
            print("</div>");
              if($_SESSION['round']>2){
                print('<a href="./leaderboard.html">Results</a>');
                $_SESSION['round']=0;
              }else{
                print('<a href="board.php">Continue</a>');
              }
            }
            else{
              $_SESSION['turn']=($_SESSION['turn']+1)%3;
              print("<div class='bottomHalf'>");
              print('<h2>Player '. ($_SESSION["turn"]) .' Is Wrong!<br>It is now Player '. ($_SESSION["turn"]) .'\'s Turn</h2>');
              print("</div>");
            }

              ?>
        </body>



