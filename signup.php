<!-- signup.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Wheel of Fortune Game</title>
    <link rel="stylesheet" href="profiledesign.css">
</head>
<body>

<div class="container">
    <h2>Signup for Wheel of Fortune Game</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $pass = htmlspecialchars(trim($_POST['password']));

        // Hash the password
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Path to the text file storing user data
        $file = 'users.txt';

        // Check if the email already exists
        $exists = false;
        $handle = fopen($file, 'r');  // Open the file for reading
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                list($existing_name, $existing_email, $existing_pass) = explode(":", trim($line));  // Extract name, email, and password
                if ($existing_email == $email) {
                    $exists = true;
                    break;
                }
            }
            fclose($handle);  // Close the file after reading
        }

        if ($exists) {
            echo "<p class='error'>Email already taken. Please choose another.</p>";
        } else {
            // Open the file for appending (write)
            $handle = fopen($file, 'a');
            if ($handle) {
                // Write user data (name, email, password)
                $user_data = $name . ":" . $email . ":" . $hashed_pass . PHP_EOL;  
                fwrite($handle, $user_data);  // Write to the file
                fclose($handle);  // Close the file after writing
                echo "<p class='success'>You have sucessfully signed up for Wheel of Fortune! <a href='login.php'>Login here</a>.</p>";
            } else {
                echo "<p class='error'>Failed to open the file for writing.</p>";
            }
        }
    }
    ?>

    <form method="POST" action="">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</div>

</body>
</html>
