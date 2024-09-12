<?php
session_start();

$servername = "localhost";
$dbname = "cafe";
$dbusername = "root"; 
$dbpassword = ""; 

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $user;
        header("Location: project.html");
        exit();
    } else {
        $error = "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('images/coffee2.jpg') no-repeat center center fixed; 
            background-size: cover;
            animation: fadeIn 1s ease-in-out;
        }

        .login-container {
            display: flex;
            align-items: center;
            max-width: 900px;
            width: 100%;
            margin: 0 20px;
        }

        .login-image {
            width: 50%;
            height: auto;
            padding-right: 30px;
            border-radius: 20px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .glass-effect {
    backdrop-filter: blur(12px) saturate(150%);
    -webkit-backdrop-filter: blur(12px) saturate(150%);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.4));
    border-radius: 20px;
    border: 2px solid rgba(255, 255, 255, 0.7);
    padding: 40px 60px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
    color: #e0e0e0; /* Slightly brighter text color for better visibility */
    animation: fadeIn 1.5s ease-in-out;
}

.glass-effect h2 {
    margin-bottom: 40px;
    font-size: 30px;
    color: #ffffff; /* Brighter text color for the heading */
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4); /* Subtle shadow for enhanced readability */
}

input[type="text"], input[type="password"] {
    width: calc(100% - 40px);
    padding: 18px;
    margin: 20px 0;
    border: none;
    border-radius: 15px;
    background-color: rgba(255, 255, 255, 0.25); /* Lighter background for better contrast */
    color: #333333; /* Darker text color for better visibility */
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.4);
    font-size: 18px;
    transition: background-color 0.3s ease;
}

input[type="text"]:focus, input[type="password"]:focus {
    background-color: rgba(255, 255, 255, 0.35);
    outline: none;
}

.login-button {
    width: 100%;
    padding: 15px;
    background-color: rgba(255, 255, 255, 0.45); /* Slightly darker background for better visibility */
    border: none;
    border-radius: 15px;
    color: #333333; /* Darker text color for the button */
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 30px;
}

.login-button:hover {
    background-color: rgba(255, 255, 255, 0.55);
}


        .toggle-password {
            position: absolute;
            right: 10px;
            top: 45%;
            cursor: pointer;
        }

        .input-container {
            position: relative;
        }

        .error {
            color: #ff4d4d;
            text-align: center;
            margin-top: 20px;
        }

        @media (min-width: 768px) {
            .login-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="images/login.jpg" alt="Cafe Image" class="login-image">
        <div class="glass-effect">
            <h2>LOGIN</h2>
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required autocomplete="off" aria-label="Username">
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password" aria-label="Password">
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
                <button type="submit" class="login-button">Login</button>
                <?php if (!empty($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var passwordType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', passwordType);
        }
    </script>
</body>
</html>
