<h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
<p>Роль: <strong><?= $_SESSION['user_role'] === 'admin' ? 'Администратор' : 'Библиотекарь' ?></strong></p>

<hr style="margin: 20px 0;">

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
    <div style="background: #ecf0f1; padding: 20px; border-radius: 5px; text-align: center;">
        <h3>📚 Книги</h3>
        <p style="font-size: 2rem; margin: 10px 0;"><?= $total_books ?? 0 ?></p>
        <a href="/books" class="btn">Управление</a>
    </div>
    
    <div style="background: #ecf0f1; padding: 20px; border-radius: 5px; text-align: center;">
        <h3>👥 Читатели</h3>
        <p style="font-size: 2rem; margin: 10px 0;"><?= $total_readers ?? 0 ?></p>
        <a href="/readers" class="btn">Управление</a>
    </div>
    
    <div style="background: #ecf0f1; padding: 20px; border-radius: 5px; text-align: center;">
        <h3>📖 Выдано книг</h3>
        <p style="font-size: 2rem; margin: 10px 0;"><?= $active_loans ?? 0 ?></p>
        <a href="/loans" class="btn">Просмотр</a>
    </div>
</div>
