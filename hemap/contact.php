<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 20px;
        }
        h1 {
            margin-bottom: 1em;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 1em;
        }
        label {
            font-weight: bold;
            margin-bottom: 0.5em;
            display: block;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 0.75em;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 1em;
            font-size: 1em;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .message {
            font-size: 1em;
            color: #d9534f;
            margin-top: 1em;
        }
        .success {
            color: #5bc0de;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cafe_contact";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $message);

            if ($stmt->execute()) {
                echo '<p class="message success">Your message has been sent successfully!</p>';
            } else {
                echo '<p class="message">There was an error sending your message. Please try again.</p>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <input type="submit" value="Send Message">
        </form>
    </div>
</body>
</html>
