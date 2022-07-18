<?php

require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = 'INSERT INTO messages (message, created_at) VALUES (:message, :created_at)';
    $query = $pdo->prepare($query);
    $query->execute([
        'message' => $_POST['message'],
        'created_at' => date('Y-m-d H:i:s')
    ]);
}
$query = 'SELECT * FROM messages';
$msg = $pdo->query($query);

try {
    while ($message = $msg->fetch()) {
        echo $message['id'] . ' ' . $message['message'] . ' ' . $message['created_at'] . '<br />';
    }
} catch (PDOException $e) {
    echo 'Query error';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>
    
    <form method='post'>
        Write a message:<br /><br />
        <textarea name='message'></textarea>
        <br />
        <input type='submit' value='Send'>
    </form>
</body>
</html>

