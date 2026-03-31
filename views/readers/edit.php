<?php
/**
 * @var string $title
 * @var object $reader
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
                
                <form action="/readers/update" method="POST">
                    <input type="hidden" name="id" value="<?= $reader->id ?>">
                    
                    <div class="mb-3">
                        <label for="library_card_number" class="form-label">Номер читательского билета *</label>
                        <input type="text" class="form-control" id="library_card_number" name="library_card_number" 
                               value="<?= htmlspecialchars($old['library_card_number'] ?? $reader->library_card_number) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">ФИО *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?= htmlspecialchars($old['full_name'] ?? $reader->full_name) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?= htmlspecialchars($old['phone'] ?? $reader->phone) ?>" 
                               placeholder="+7 (999) 123-45-67">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Адрес</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($old['address'] ?? $reader->address) ?></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/readers" class="btn btn-secondary">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
