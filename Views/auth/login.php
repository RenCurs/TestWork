<? require_once __DIR__ . ('/../layouts/header.php');?>

<div class="container">
    <div class="row ">
        <div class="col-5 m-auto">
            <div class="wrap-regist-form m-4">
                <h4>Авторизация</h4>
                
                <?php if(isset($error) && $error === false): ?>
                    <div class="alert alert-danger fade show">
                        Неверный логин или пароль
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif;?>

                <form action="/login" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input  required type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                        value="<?=  $_POST['email'] ?? ''?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input required type="password" class="form-control" id="exampleInputPassword1" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>
<? require_once __DIR__ . ('/../layouts/footer.php');?>