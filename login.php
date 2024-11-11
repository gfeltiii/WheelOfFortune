<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wheel of Fortune Game</title>
    <link rel="stylesheet" href="profiledesign.css">
</head>
<body>

<div class="container">
    <h2>Login to Wheel of Fortune Game</h2>

    <?php
    session_start();

    // If a user is already logged in, redirect them to the game page
    if (isset($_SESSION['username'])) {
        header("Location: game.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $email = htmlspecialchars(trim($_POST['email']));
        $pass = htmlspecialchars(trim($_POST['password']));

        // Path to the text file storing user data
        $file = 'users.txt';

        // Read users from the file
        $found = false;
        $handle = fopen($file, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                list($existing_name, $existing_email, $existing_pass) = explode(":", trim($line));  // Extract name, email, and password
                if ($existing_email == $email) {
                    $found = true;
                    // Check if the password matches
                    if (password_verify($pass, $existing_pass)) {
                        // Start session and store username
                        $_SESSION['username'] = $existing_name;

                        // Set a cookie to remember the user for 30 days
                        setcookie('username', $existing_name, time() + 60 * 60 * 24 * 30, '/');

                        // Redirect to the game page after successful login
                        header("Location: game.php");
                        exit();
                    } else {
                        echo "<p class='error'>Incorrect password.</p>";
                    }
                    break;
                }
            }
            fclose($handle);
        }

        if (!$found) {
            echo "<p class='error'>No user found with that email.</p>";
        }
    }
    ?>

    <form method="POST" action="index.html">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
</div>

</body>
</html>

