
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
$comment = trim($_POST['comment'] ?? '');

$allowedStatuses = [
    'На согласовании',
    'Согласовано',
    'Отклонено'
];

if (
    !$id ||
    !in_array($status, $allowedStatuses, true) ||
    mb_strlen($comment) > 2000
) {
    exit('Некорректные данные. <a href="index.php">Назад</a>');
}

$stmt = $pdo->prepare(
    "UPDATE documents
     SET status = ?, comment = ?
     WHERE id = ?"
);

$stmt->execute([$status, $comment, $id]);

header('Location: index.php');
exit;
?>
