<?php require_once __DIR__ . '/../layouts/header.php'?>

<div class="container">
        <h2>Редактирование задачи</h2>
        <div class="wrap-form col-5">
            <form action="#" method="POST">
                <div class="form-group">
                    <lable>Имя пользователя</lable>
                    <input disabled class="form-control" type="text" name="username" value="<?= $job->getUserName() ?>">
                </div>
                <div class="form-group">
                    <lable>e-mail</lable>
                    <input disabled class="form-control" type="email" name="email" value="<?= $job->getEmail() ?>">
                </div>
                <div class="form-group">
                    <lable>Текст задачи</lable>
                    <textarea required class="form-control"name="text"><?= $job->getText() ?></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Редактировать</button>
                    <a type="button" class="btn btn-success" onclick="window.location.href = '/'">На главную</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php' ?>