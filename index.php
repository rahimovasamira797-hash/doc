
<?php
require 'db.php';

$documents = $pdo->query(
    "SELECT * FROM documents ORDER BY created_at DESC"
)->fetchAll();

function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

$colors = [
    'На согласовании' => 'pending',
    'Согласовано' => 'approved',
    'Отклонено' => 'rejected'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система согласования документов</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>📁 DocFlow</h2>
    <p>Система документооборота</p>
    <a href="index.php">▦ Все документы</a>
    <a href="#add">＋ Добавить документ</a>
</div>

<main class="main">
    <header>
        <div>
            <h1>Согласование документов</h1>
            <p>Управление и контроль документооборота</p>
        </div>
        <div class="avatar">АД</div>
    </header>

    <section class="stats">
        <div class="stat">
            <span>Всего документов</span>
            <strong><?= count($documents) ?></strong>
        </div>

        <div class="stat">
            <span>На согласовании</span>
            <strong><?= count(array_filter(
                $documents,
                fn($d) => $d['status'] === 'На согласовании'
            )) ?></strong>
        </div>

        <div class="stat">
            <span>Согласовано</span>
            <strong><?= count(array_filter(
                $documents,
                fn($d) => $d['status'] === 'Согласовано'
            )) ?></strong>
        </div>
    </section>

    <section class="panel" id="add">
        <h2>＋ Новый документ</h2>

        <form action="add.php" method="post" class="form-grid">
            <div>
                <label>Название документа</label>
                <input type="text" name="title" maxlength="255" required>
            </div>

            <div>
                <label>Тип документа</label>
                <select name="document_type" required>
                    <option value="Договор">Договор</option>
                    <option value="Приказ">Приказ</option>
                    <option value="Заявление">Заявление</option>
                    <option value="Записка">Служебная записка</option>
                    <option value="Отчёт">Отчёт</option>
                </select>
            </div>

            <div>
                <label>Автор документа</label>
                <input type="text" name="author" maxlength="150" required>
            </div>

            <div>
                <label>Ответственный за согласование</label>
                <input type="text" name="approver" maxlength="150" required>
            </div>

            <div class="full">
                <label>Описание</label>
                <textarea name="description" rows="3"></textarea>
            </div>

            <div class="full">
                <button type="submit">Добавить документ</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Список документов</h2>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Документ</th>
                    <th>Автор</th>
                    <th>Ответственный</th>
                    <th>Дата создания</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?= e($doc['id']) ?></td>
                        <td>
                            <strong><?= e($doc['title']) ?></strong>
                            <small><?= e($doc['document_type']) ?></small>
                            <?php if ($doc['description']): ?>
                                <small><?= e($doc['description']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= e($doc['author']) ?></td>
                        <td><?= e($doc['approver']) ?></td>
                        <td><?= e($doc['created_at']) ?></td>
                        <td>
                            <span class="badge <?= e(
                                $colors[$doc['status']] ?? 'pending'
                            ) ?>">
                                <?= e($doc['status']) ?>
                            </span>
                        </td>
                        <td>
                            <form action="update.php" method="post"
                                  class="action-form">
                                <input type="hidden" name="id"
                                       value="<?= e($doc['id']) ?>">

                                <select name="status" aria-label="Новый статус">
                                    <option value="На согласовании">
                                        На согласовании
                                    </option>
                                    <option value="Согласовано">
                                        Согласовано
                                    </option>
                                    <option value="Отклонено">
                                        Отклонено
                                    </option>
                                </select>

                                <input type="text" name="comment"
                                       maxlength="2000"
                                       placeholder="Комментарий">

                                <button type="submit" class="small-btn">
                                    Сохранить
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$documents): ?>
                    <tr>
                        <td colspan="7">Документов пока нет.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>
