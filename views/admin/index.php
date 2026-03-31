<div class="card">
    <div class="card-header">
        <h1><?= htmlspecialchars($title) ?></h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="/dashboard" class="btn btn-secondary">Назад</a>
            <a href="/admin/users/create" class="btn btn-primary">Добавить пользователя</a>
        </div>

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
                    <td><?= htmlspecialchars($user->username) ?></td>
                    <td><?= htmlspecialchars($user->full_name) ?></td>
                    <td>
                        <span class="badge bg-<?= $user->role === 'admin' ? 'danger' : ($user->role === 'librarian' ? 'warning' : 'secondary') ?>">
                            <?= $user->role === 'admin' ? 'Администратор' : ($user->role === 'librarian' ? 'Библиотекарь' : 'Пользователь') ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y', strtotime($user->created_at)) ?></td>
                    <td>
                        <a href="/admin/users/edit/<?= $user->id ?>" class="btn btn-sm btn-primary">Редактировать</a>
                        <?php if ($user->id != $_SESSION['user_id']): ?>
                            <form action="/admin/users/delete" method="POST" style="display: inline-block;">
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
</div>