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
    $_SESSION['players'] = array("p","e","m");
    $_SESSION['turn']=0;
}
if (isset($_SESSION["lettersGuessed"])==false){
    $_SESSION["lettersGuessed"]= array();
}
$consonants = array("q","j","z","x","v","k","w","y","f","b","g","h","m","p","d","c","l","s","n","t","r");

$clue = array("s","t","r","a","w","b","e","r","r","i","e","s","|","a","n","d","_","c","r","e","a","m");
$cluetext = "strawberries and cream";
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
$right = (isset($_POST['solveGuess']) && strcasecmp($_POST['solveGuess'],$cluetext)== 0);
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
                Spin Money: <?php print($_SESSION['spinMoney']."$"); ?><br><br>
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
                 foreach ($clue as $key => $value) {
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
                Player 1: <?php print($_SESSION['roundMoney1']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney1']);?>$ <br>
                Player 2: <?php print($_SESSION['roundMoney2']);?>$ <br>
                Bank: <?php print($_SESSION['gameMoney2']);?>$ <br>
                Player 3: <?php print($_SESSION['roundMoney3']);?>$ <br>
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
           
            $_SESSION['round']++;
            print('<h2>Player '. ($_SESSION["turn"]+1) .' Is Correct!<br>They Banked '. $money .'$ This Round</h2>');
                $_SESSION['round']= ($_SESSION['round']+1)%3;
                $_SESSION['roundMoney1']= $_SESSION['roundMoney2'] = $_SESSION['roundMoney3'] = 0;
                $_SESSION['lettersGuessed']= array_diff($_SESSION['lettersGuessed'], $_SESSION['lettersGuessed']);

             
            }?>
            <a href="board.php">Continue</a>
        </body>



