<?php

require_once('connect.php');

/** @var PDO $pdo */

session_start();

function reader($msg, $pagination)
{
    $result = '';
    $counter = 0;
    try {
        while ($message = $msg->fetch()) {
            $result .= $message['id'] . ' ' . $message['message'] . ' (' . $message['created_at'] . ')' . PHP_EOL;
            $counter++;
            if ($counter == $pagination) {
                $counter = 0;
                yield $result;
                $result = '';
            }
        }
    } catch (PDOException $e) {
        $result .= 'Query error' . PHP_EOL;
        yield $result;
    }
}

$query = 'SELECT * FROM messages';
$msg = $pdo->query($query);

$content = [];
$_SESSION['page'] = 1;


if (isset($_GET['page'])) {
    $_SESSION['page'] = $_GET['page'];
}

$perviousPage = ($_SESSION['page']) - 1;
$nextPage = ($_SESSION['page']) + 1;

foreach (reader($msg, 4) as $item) {
    $content[] = $item;
}

if ($perviousPage < 1) {
    $perviousPage = 1;
}
if ($nextPage > count($content)) {
    $nextPage = count($content);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = 'INSERT INTO messages (message, created_at) VALUES (:message, :created_at)';
    $query = $pdo->prepare($query);
    $query->execute([
        'message' => $_POST['message'],
        'created_at' => date('Y-m-d H:i:s')
    ]);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>]
<textarea rows='10' cols='80'> <?= $content[$_SESSION['page'] - 1] ?></textarea><br/>
<a href="?page=<?= $perviousPage ?>"><--</a>&nbsp&nbsp<a href="?page=<?= $nextPage ?>">--></a><br/><br/>


<form method='post'>
    Write a message:<br/>
    <textarea name='message' cols='30'></textarea>
    <br/>
    <input type='submit' value='Send'>
</form>
</body>
</html>

