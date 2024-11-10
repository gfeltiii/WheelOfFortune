<?php
// Define the wheel options
$wheel_values = [
    "$100", "$200", "$300", "$400", "$500", "$600", "$700",
    "Bankrupt", "$800", "$900", "$1000", "$2000", "$2500"
];

// Randomly pick a value from the wheel values array
$selected_value = "";
$rotation_degrees = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Randomly pick a value
    $selected_value = $wheel_values[array_rand($wheel_values)];
    $_SESSION['spinMoney']=$selectedValue;
    // Random rotation degrees (e.g., full spin + random degree)
    $rotation_degrees = rand(720, 1080);  // Random spin degrees between 720 and 1080
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheel of Fortune Spinner</title>
    <link rel="stylesheet" href="wheeldesign.css">
</head>
<body>
    <div class="container">
        <span class="dot"></span>
            <!-- The Wheel -->
        <ul class="wheel-of-fortune">
            <li>$100</li>
            <li>$200</li>
            <li>$300</li>
            <li>$400</li>
            <li>$500</li>
            <li>$600</li>
            <li>$700</li>
            <li class="black">Bankrupt</li>
            <li>$800</li>
            <li>$900</li>
            <li>$1000</li>
            <li>$2000</li>
            <li>$2500</li>
        </ul>
        <button type="button"></button>
        <form method="POST">
            <button type="submit" class="spin">Spin the wheel!</button>
        </form>
    </div>
    <div class="blink"><span class="dots dot1"></span></div>
    <div class="blink2"><span class="dots dot2"></span></div>
    <div class="blink1"><span class="dots dot3"></span></div>
    <div class="blink2"><span class="dots dot4"></span></div>
    <div class="blink"><span class="dots dot5"></span></div>
    <div class="blink1"><span class="dots dot6"></span></div>
    <div class="blink"><span class="dots dot7"></span></div>
    <div class="blink2"><span class="dots dot8"></span></div>
    <div class="result">
        <?php if ($selected_value) { echo "You landed on: <span style='color: red;'>$selected_value</span>"; } ?>
    </div>
    <audio src="WOFtheme.mp3" autoplay></audio>
</body>
</html>
