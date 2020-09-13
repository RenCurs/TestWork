<?php require_once __DIR__ . '/layouts/header.php'?>

<div class="container">
    <div class="row">
        <div class="wrap-all-jobs col-12">
            <div class="wrap-buttons m-4 row">
                <div class="actions">
                    <a href="/jobs/create" class="btn btn-info">Создать</a>
                    <a href="/" class="btn btn-info">Сбросить фильтр</a>
                </div>

                <div class="auth-buttons ml-auto">
                <?php if(isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['user']?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Мои задачи</a>
                            <a class="dropdown-item" href="/logout">Выйти</a>
                        </div>
                    <?php else:?>
                        <a href="/register" class="btn btn-info float-right">Регистрация</a>
                        <a href="/login" class="btn btn-info float-right mr-2">Вход</a>
                    <?php endif;?>
                    </div>
                </div>
            </div>

            <table class="table">
                <caption>Список задач</caption>
                <thead>
                    <tr>
                        <th scope="col">

                            <div class="row">
                                <span>Имя пользователя
                                <a class="text-decoration-none" style="font-size: 25px" href="/?page=<?=$paginator->getCurrPage()?>&sort=username_up">↑</a>
                                <a class="text-decoration-none" style="font-size: 25px" href="/?page=<?=$paginator->getCurrPage()?>&sort=username_down">↓</a>
                                </span>
                            </div>
                        </th>
                        <th scope="col">Еmail
                            <a class="text-decoration-none" style="font-size: 25px" href="/?sort=email_up">↑</a>
                            <a class="text-decoration-none" style="font-size: 25px" href="/?sort=email_down">↓</a>
                        </th>
                        <th scope="col">Текст задачи
                            <a class="text-decoration-none" style="font-size: 25px" href="/?sort=text_up">↑</a>
                            <a class="text-decoration-none" style="font-size: 25px" href="/?sort=text_down">↓</a>
                        </th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Cтатус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!is_null($jobs)):?>
                        <? foreach($jobs as $job): ?>
                        <tr>
                            <td scope="col"><?= $job->getUserName()?></td>
                            <td scope="col"><?= $job->getEmail()?></td>
                            <td scope="col"><textarea  class="form-control"><?= $job->getText()?></textarea></td>
                            <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1): ?>
                                <td scope="col">
                                    <a class="btn btn-success" href="/jobs/edit/<?= $job->getId()?>">Редактировать</a>
                                </td>
                            <?php else: ?>
                                <td></td>
                            <?php endif;?>

                            <?php if($job->isEdit()):?>
                                <td scope="col">Отредактировано администатором</td>
                            <?php else:?>
                                <td scope="col"></td>
                            <?php endif;?>

                            <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1): ?>
                                <?php if($job->isDone()):?>
                                    <td>
                                        <form action="/done/<?= $job->getId()?>">
                                            <div class="form-group form-check">
                                                <input  checked type="checkbox" class="form-check-input" id="exampleCheck1" job_id="<?= $job->getId()?>">
                                                <label  class="form-check-label" for="exampleCheck1">выполнено</label>
                                            </div>
                                        </form>
                                    </td>
                                <?php else:?>
                                    <td>
                                        <form action="/done/<?= $job->getId()?>">
                                            <div class="form-group form-check">
                                                <input  type="checkbox" class="form-check-input" id="exampleCheck1" job_id="<?= $job->getId()?>">
                                                <label  class="form-check-label" for="exampleCheck1">выполняется</label>
                                            </div>
                                        </form>
                                    </td>
                                <?php endif;?>
                            <?php else:?>
                                <td>
                                    <?php if($job->isDone()):?>
                                        <span>выполнено</span>    
                                    <?php else:?>
                                        <span>выполняется</span>    
                                    <?php endif;?>
                                </td>
                            <?php endif;?>

                        </tr>
                        <? endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>

            <div class="paginate">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php if($paginator->getCurrPage() === 1):?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                        <?php else:?>
                            <li class="page-item">
                                <a class="page-link"  href="/?page=<?= $paginator->getCurrPage()-1 ?>&sort=<?= $paginator->getSort()?>" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                        <?php endif;?>

                        <?php for($i = $paginator->getStart(); $i <= $paginator->getEnd(); $i++):?>
                            <?php if($paginator->getCurrPage() === $i):?>
                                <li class="page-item active"><a class="page-link" href="/?page=<?= $i?>&sort=<?= $paginator->getSort()?>"><?= $i?></a></li>
                            <?php else:?>
                                <li class="page-item"><a class="page-link" href="/?page=<?= $i?>&sort=<?= $paginator->getSort()?>"><?= $i?></a></li>
                            <?php endif;?>
                        <?php endfor;?>
                        
                        <?php if($paginator->getCurrPage() === $paginator->getLast() ):?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                            </li>
                        <?php else:?>
                            <li class="page-item">
                                <a class="page-link" href="/?page=<?= $paginator->getCurrPage()+1?>&sort=<?= $paginator->getSort()?>" tabindex="-1" aria-disabled="true">Next</a>
                            </li>
                        <?php endif;?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/layouts/footer.php' ?>

<script>
    $(document).ready(function(){
        var check = $(".form-check-input").click(function(){
            var id = $(this).attr('job_id');
            $.ajax({
                url: '/done',
                method: 'POST',
                data: {'id':id},
                success: function(data)
                {
                   location.reload();
                }
            });
        });
        
    });
</script>