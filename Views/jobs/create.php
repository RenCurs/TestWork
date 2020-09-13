<?php require_once __DIR__ . '/../layouts/header.php'?>

<div class="container">
    <div class="col-5">
    
        <?php if(isset($errors)):?>
            <?php foreach($errors as $error): ?>
                <p><?= $error?></p>
            <?php endforeach ?>    
        <?php endif?>
        
        <?php if(isset($result) && $result === true ):?>
            <p>Задача добавлена</p>
        <?php endif?>


        <h2>Добавление задачи</h2>
        <div class="wrap-form">
            <form action="/jobs/create" method="POST">
                <div class="form-group">
                    <lable>Имя пользователя</lable>
                    <input required class="form-control" type="text" name="username" value="<?= $_POST['username'] ?? ''?>">
                </div>
                <div class="form-group">
                    <lable>e-mail</lable>
                    <input required class="form-control" type="email" name="email" value="<?= $_POST['email'] ?? ''?>">
                </div>
                <div class="form-group">
                    <lable>Текст задачи</lable>
                    <textarea required class="form-control"name="text"><?= $_POST['text'] ?? ''?></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Создать</button>
                    <button class="btn btn-info" type="button" onclick="window.location.href = '/jobs/create'">Сброс</button>
                </div>
            </form>
        </div>
    </div>
</div>




<?php require_once __DIR__ . '/../layouts/footer.php' ?>