<?php
/**
 * @var string $title
 * @var array $loans
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= htmlspecialchars($title) ?></h1>
            <a href="/loans" class="btn btn-secondary">← Активные выдачи</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Книга</th>
                        <th>Читатель</th>
                        <th>Дата выдачи</th>
                        <th>Дата возврата</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Нет истории выдач</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loans as $loan): ?>
                            <tr>
                                <td><?= $loan->id ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($loan->book->title) ?></strong><br>
                                    <small><?= htmlspecialchars($loan->book->author) ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($loan->reader->full_name) ?><br>
                                    <small>№<?= htmlspecialchars($loan->reader->library_card_number) ?></small>
                                </td>
                                <td><?= date('d.m.Y H:i', strtotime($loan->issued_at)) ?></td>
                                <td>
                                    <?php if ($loan->returned_at): ?>
                                        <?= date('d.m.Y H:i', strtotime($loan->returned_at)) ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">На руках</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($loan->returned_at): ?>
                                        <span class="badge bg-success">Возвращена</span>
                                    <?php elseif ($loan->isOverdue()): ?>
                                        <span class="badge bg-danger">Просрочена!</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Выдана</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <a href="/dashboard" class="btn btn-secondary">На дашборд</a>
    </div>
</body>
</html>
