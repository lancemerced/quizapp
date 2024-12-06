<?php

session_start(); 
$conn = new mysqli("localhost", "root", "root", "quiz_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} 

$quiz_title = "PHP QUIZ";
$questions = [
    [
        "question" => "1. What does PHP stand for?",
        "options" => ["Private Home Page", "Personal Hypertext Processor", "PHP: Hypertext Processor", "Philippines"],
        "answer" => 2
    ],
];

$score = 0;
$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM user_scores WHERE user_id = ?");
$query->bind_param("s", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    echo "<h2>You have already taken this quiz!</h2>";
    echo "<a href='leaderboard.php'>View Leaderboard</a>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($questions as $index => $question) {
        if (isset($_POST["questions$index"]) && $_POST["questions$index"] == $question["answer"]) {
            $score++;
        }
    }

    $sqlquery = $conn->prepare("INSERT INTO user_scores (user_id, score, quiz_title, timestamp) VALUES (?, ?, ?, NOW())");
    $sqlquery->bind_param("sis", $user_id, $score, $quiz_title);
    $sqlquery->execute();

    echo "<h2>Your score: $score / " . count($questions) . "</h2>";
    echo "<a href='leaderboard.php'>View Leaderboard</a>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quiz_title; ?></title>
</head>
<body>
    <div class="container">
        <h1><?php echo $quiz_title; ?></h1>
        <form action="" method="post">
            <?php foreach ($questions as $index => $question): ?> <BR>
                <fieldset>
                    <legend><?php echo $question['question']; ?></legend>
                    <?php foreach ($question['options'] as $optionIndex => $option): ?>
                        <label>
                            <input type="radio" name="questions<?php echo $index; ?>" value="<?php echo $optionIndex; ?>">
                            <?php echo $option; ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
