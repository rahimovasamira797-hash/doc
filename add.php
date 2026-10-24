
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$type = trim($_POST['document_type'] ?? '');
$author = trim($_POST['author'] ?? '');
$approver = trim($_POST['approver'] ?? '');
$description = trim($_POST['description'] ?? '');

$allowedTypes = [
    'Договор', 'Приказ', 'Заявление', 'Записка', 'Отчёт'
];

if (
    $title === '' || mb_strlen($title) > 255 ||
    $author === '' || mb_strlen($author) > 150 ||
    $approver === '' || mb_strlen($approver) > 150 ||
    mb_strlen($description) > 10000 ||
    !in_array($type, $allowedTypes, true)
) {
    exit('Проверьте заполнение полей. <a href="index.php">Назад</a>');
}

$stmt = $pdo->prepare(
    "INSERT INTO documents
    (title, document_type, author, approver, description)
    VALUES (?, ?, ?, ?, ?)"
);

$stmt->execute([
    $title, $type, $author, $approver, $description
]);

header('Location: index.php');
exit;
?>
