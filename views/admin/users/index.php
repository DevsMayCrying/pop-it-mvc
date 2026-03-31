<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Управление пользователями</h1>
    <a href="/admin/users/create" class="btn btn-primary">+ Добавить библиотекаря</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Логин</th>
                <th>ФИО</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><strong><?= htmlspecialchars($user->username) ?></strong></td>
                <td><?= htmlspecialchars($user->full_name) ?></td>
                <td>
                    <span class="badge bg-<?= $user->role === 'admin' ? 'danger' : 'warning' ?>">
                        <?= $user->role === 'admin' ? 'Администратор' : 'Библиотекарь' ?>
                    </span>
                </td>
                <td><?= date('d.m.Y', strtotime($user->created_at)) ?></td>
                <td>
                    <a href="/admin/users/edit/<?= $user->id ?>" class="btn btn-sm btn-warning">Редактировать</a>
                    <?php if ($user->id != $_SESSION['user_id']): ?>
                    <form action="/admin/users/delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?= $user->id ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<a href="/dashboard" class="btn btn-secondary">На дашборд</a>
