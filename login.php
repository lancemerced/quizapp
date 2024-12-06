<?php
session_start();

$conn = new mysqli("localhost", "root", "root", "quiz_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = trim($_POST['user_id']);

    if (!empty($user_id)) {
        
        $stmt = $conn->prepare("INSERT IGNORE INTO users (user_id) VALUES (?)");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        $_SESSION['user_id'] = $user_id; 
        header("Location: index.php");
        exit;
    } else {
        $error = "Enter your name.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1>LOG IN</h1>
        <form method="post">
            <label for="user_id">Enter your name here:</label><br>
            <input type="text" id="user_id" name="user_id" required><br>
            <button type="submit">Start Quiz</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
