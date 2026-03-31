<?php
/**
 * @var string $title
 * @var object $user
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">📚 Библиотечная система</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link text-light">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? 'Гость') ?></span>
                <a href="/logout" class="nav-link text-light">Выйти</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-3">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link" href="/dashboard">Главная</a></li>
            <li class="nav-item"><a class="nav-link" href="/books">Книги</a></li>
            <li class="nav-item"><a class="nav-link" href="/readers">Читатели</a></li>
            <li class="nav-item"><a class="nav-link" href="/loans">Выдачи</a></li>
            <li class="nav-item"><a class="nav-link active" href="/admin/users">Управление библиотекарями</a></li>
        </ul>
    </div>
    
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
                
                <form action="/admin/users/update" method="POST">
                    <input type="hidden" name="id" value="<?= $user->id ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Логин *</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= htmlspecialchars($old['username'] ?? $user->username) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">ФИО *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?= htmlspecialchars($old['full_name'] ?? $user->full_name) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль (оставьте пустым, если не хотите менять)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted">Минимум 6 символов</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/admin/users" class="btn btn-secondary">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
