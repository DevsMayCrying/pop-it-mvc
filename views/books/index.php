<?php
/**
 * @var string $title
 * @var array $books
 */
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($title) ?></h1>
        <a href="/books/create" class="btn btn-primary">+ Добавить книгу</a>
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
            32<th>ID</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Год</th>
            <th>Всего</th>
            <th>Доступно</th>
            <th>Цена</th>
            <th>Новинка</th>
            <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($books)): ?>
                <tr>
                    <td colspan="9" class="text-center">Нет книг в библиотеке</td>
                </tr>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book->id ?></td>
                        <td><strong><?= htmlspecialchars($book->title) ?></strong></td>
                        <td><?= htmlspecialchars($book->author) ?></td>
                        <td><?= $book->year ?></td>
                        <td><?= $book->total_copies ?? 1 ?></td>
                        <td>
                            <?php $available = $book->available_copies ?? 1; ?>
                            <span class="badge <?= $available > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $available ?>
                                </span>
                        </td>
                        <td><?= $book->price ?> ₽</td>
                        <td><?= $book->is_new ? '✅ Да' : '❌ Нет' ?></td>
                        <td>
                            <!-- ИСПРАВЛЕНО: ссылка на редактирование с ID в URL -->
                            <a href="/books/edit/<?= $book->id ?>" class="btn btn-sm btn-warning">Редактировать</a>

                            <!-- ИСПРАВЛЕНО: форма удаления с ID в URL -->
                            <form action="/books/delete/<?= $book->id ?>" method="POST" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить книгу &quot;<?= htmlspecialchars($book->title) ?>&quot;?')">
                                    Удалить
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