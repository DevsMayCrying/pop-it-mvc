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
            <div>
                <a href="/loans/create" class="btn btn-primary">+ Выдать книгу</a>
                <a href="/loans/history" class="btn btn-secondary">История</a>
            </div>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Книга</th>
                        <th>Читатель</th>
                        <th>Дата выдачи</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Нет активных выдач</td>
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
                                    <?php if ($loan->isOverdue()): ?>
                                        <span class="badge bg-danger">Просрочена!</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Активна</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="/loans/return" method="POST">
                                        <input type="hidden" name="loan_id" value="<?= $loan->id ?>">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Принять возврат книги?')">
                                            Вернуть
                                        </button>
                                    </form>
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
