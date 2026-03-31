<?php
/**
 * @var string $title
 * @var array $readers
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
            <a href="/readers/create" class="btn btn-primary">+ Добавить читателя</a>
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
                        <th>№ билета</th>
                        <th>ФИО</th>
                        <th>Телефон</th>
                        <th>Адрес</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($readers)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Нет читателей</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($readers as $reader): ?>
                            <tr>
                                <td><?= $reader->id ?></td>
                                <td><strong><?= htmlspecialchars($reader->library_card_number) ?></strong></td>
                                <td><?= htmlspecialchars($reader->full_name) ?></td>
                                <td><?= htmlspecialchars($reader->phone ?? '-') ?></td>
                                <td><?= htmlspecialchars($reader->address ?? '-') ?></td>
                                <td><?= date('d.m.Y', strtotime($reader->created_at)) ?></td>
                                <td>
                                    <a href="/readers/edit?id=<?= $reader->id ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                    <form action="/readers/delete" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $reader->id ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить читателя?')">Удалить</button>
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
