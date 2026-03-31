<?php
/**
 * @var string $title
 * @var array $books
 * @var array $readers
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
                
                <form action="/loans/store" method="POST">
                    <div class="mb-3">
                        <label for="book_id" class="form-label">Книга *</label>
                        <select class="form-select" id="book_id" name="book_id" required>
                            <option value="">Выберите книгу</option>
                            <?php foreach ($books as $book): ?>
                                <option value="<?= $book->id ?>" 
                                    <?= ($old['book_id'] ?? '') == $book->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($book->title) ?> — 
                                    <?= htmlspecialchars($book->author) ?> 
                                    (доступно: <?= $book->available_copies ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reader_id" class="form-label">Читатель *</label>
                        <select class="form-select" id="reader_id" name="reader_id" required>
                            <option value="">Выберите читателя</option>
                            <?php foreach ($readers as $reader): ?>
                                <option value="<?= $reader->id ?>" 
                                    <?= ($old['reader_id'] ?? '') == $reader->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($reader->full_name) ?> 
                                    (№<?= htmlspecialchars($reader->library_card_number) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/loans" class="btn btn-secondary">Отмена</a>
                        <button type="submit" class="btn btn-primary">Выдать книгу</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
