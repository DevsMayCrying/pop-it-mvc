<?php
/**
 * @var string $title
 * @var array $errors
 * @var array $old
 */
?>

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
            
            <form action="/books/store" method="POST">
                <div class="mb-3">
                    <label for="author" class="form-label">Автор *</label>
                    <input type="text" class="form-control" id="author" name="author" 
                           value="<?= htmlspecialchars($old['author'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Название книги *</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="year" class="form-label">Год издания</label>
                    <input type="number" class="form-control" id="year" name="year" 
                           value="<?= htmlspecialchars($old['year'] ?? date('Y')) ?>">
                </div>
                
                <div class="mb-3">
                    <label for="total_copies" class="form-label">Количество экземпляров</label>
                    <input type="number" class="form-control" id="total_copies" name="total_copies" 
                           value="<?= htmlspecialchars($old['total_copies'] ?? 1) ?>" min="1">
                </div>
                
                <div class="mb-3">
                    <label for="price" class="form-label">Цена (₽)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" 
                           value="<?= htmlspecialchars($old['price'] ?? 0) ?>">
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_new" name="is_new" 
                           <?= isset($old['is_new']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_new">Новинка</label>
                </div>
                
                <div class="mb-3">
                    <label for="annotation" class="form-label">Аннотация</label>
                    <textarea class="form-control" id="annotation" name="annotation" rows="3"><?= htmlspecialchars($old['annotation'] ?? '') ?></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="/books" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>
