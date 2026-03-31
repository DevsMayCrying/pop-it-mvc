<h2>📖 <?= htmlspecialchars($book->title) ?></h2>

<div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
    <p><strong>Автор:</strong> <?= htmlspecialchars($book->author) ?></p>
    <p><strong>Год издания:</strong> <?= htmlspecialchars($book->year) ?></p>
    <p><strong>Цена:</strong> <?= htmlspecialchars($book->price) ?> ₽</p>
    <p><strong>Новинка:</strong> <?= $book->is_new ? '✅ Да' : '❌ Нет' ?></p>
    <p><strong>Аннотация:</strong></p>
    <p><?= nl2br(htmlspecialchars($book->annotation)) ?></p>
</div>

<div style="margin-top: 20px;">
    <a href="/books/edit/<?= $book->id ?>" class="btn">✏️ Редактировать</a>
    <a href="/books" class="btn">← Назад к списку</a>
</div>
