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


$query = $conn->prepare("SELECT user_id, score, quiz_title, timestamp FROM user_scores ORDER BY score DESC, timestamp ASC");
$query->execute();
$result = $query->get_result();


$leaderboard = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
} else {
    $message = "No scores yet!";
}

$query->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Leaderboard</h1>
        <?php if (!empty($leaderboard)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                        <th>Quiz Title</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $index => $entry): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($entry['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($entry['score']); ?></td>
                            <td><?php echo htmlspecialchars($entry['quiz_title']); ?></td>
                            <td><?php echo htmlspecialchars($entry['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <a href="index.php">Back to Quiz</a>
    </div>
</body>
</html>
