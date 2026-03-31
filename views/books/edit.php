<?php
/**
 * @var string $title
 * @var object $book
 * @var array $errors
 * @var array $old
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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4"><?= htmlspecialchars($title) ?></h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/books/update" method="POST">
                <input type="hidden" name="id" value="<?= $book->id ?>">

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN *</label>
                    <input type="text" class="form-control" id="isbn" name="isbn"
                           value="<?= htmlspecialchars($old['isbn'] ?? $book->isbn) ?>"
                           placeholder="978-5-699-12345-6" required>
                    <div class="form-text">Уникальный международный номер книги</div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Название книги *</label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="<?= htmlspecialchars($old['title'] ?? $book->title) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Автор *</label>
                    <input type="text" class="form-control" id="author" name="author"
                           value="<?= htmlspecialchars($old['author'] ?? $book->author) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="publisher" class="form-label">Издательство</label>
                    <input type="text" class="form-control" id="publisher" name="publisher"
                           value="<?= htmlspecialchars($old['publisher'] ?? $book->publisher) ?>">
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Год издания</label>
                    <input type="number" class="form-control" id="year" name="year"
                           value="<?= htmlspecialchars($old['year'] ?? $book->year) ?>"
                           min="1000" max="<?= date('Y') ?>" step="1">
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Жанр</label>
                    <input type="text" class="form-control" id="genre" name="genre"
                           value="<?= htmlspecialchars($old['genre'] ?? $book->genre) ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($old['description'] ?? $book->description) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="total_copies" class="form-label">Всего экземпляров *</label>
                    <input type="number" class="form-control" id="total_copies" name="total_copies"
                           value="<?= htmlspecialchars($old['total_copies'] ?? $book->total_copies) ?>"
                           min="0" required>
                </div>

                <div class="mb-3">
                    <label for="available_copies" class="form-label">Доступно экземпляров</label>
                    <input type="number" class="form-control" id="available_copies" name="available_copies"
                           value="<?= htmlspecialchars($old['available_copies'] ?? $book->available_copies) ?>"
                           min="0" max="<?= $book->total_copies ?? 999 ?>">
                    <div class="form-text">Оставьте пустым для автоматического расчета</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/books" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>