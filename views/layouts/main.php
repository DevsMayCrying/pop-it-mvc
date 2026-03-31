<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Библиотечная система' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .header .user-info {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .nav {
            background: #34495e;
            padding: 10px 20px;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            padding: 5px 10px;
        }
        
        .nav a:hover {
            background: #2c3e50;
            border-radius: 3px;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        table th {
            background: #f2f2f2;
        }
        
        .btn {
            display: inline-block;
            padding: 5px 10px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            border: none;
            cursor: pointer;
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-success {
            background: #27ae60;
        }
        
        form input, form select, form textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Библиотечная система</h1>
        <div class="user-info">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>👤 <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                <a href="/logout" class="btn" style="background: #e74c3c;">Выйти</a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="nav">
        <a href="/dashboard">Главная</a>
        <a href="/books">Книги</a>
        <a href="/readers">Читатели</a>
        <a href="/loans">Выдачи</a>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="/admin/users">Управление библиотекарями</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="content">
            <?= $content ?? '' ?>
        </div>
    </div>
</body>
</html>