<? require_once __DIR__ . ('/../layouts/header.php');?>

<div class="container">
    <div class="row ">
        <div class="col-5 m-auto">
            <div class="wrap-regist-form m-4">

                <?php if(isset($errors)):?>
                   <?php foreach($errors as $error):?>
                        <p><?= $error?></p>
                   <?php endforeach;?>
                <?php elseif(isset($result) && $result === true):?>
                    <p>Вы успешно зарегистрировались!</p>
                <?php endif;?>



                <h4>Регистрация</h4>
                <form action="/register" method="POST">
                    <div class="form-group">
                        <label for="nameId">Имя пользователя</label>
                        <input required type="text" class="form-control" id="nameId" name="username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input  required type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input required type="password" class="form-control" id="exampleInputPassword1" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <a type="button" class="btn btn-primary" href="/login/show">Войти</a>
                </form>
            </div>
        </div>
    </div>
</div>
<? require_once __DIR__ . ('/../layouts/footer.php');?>