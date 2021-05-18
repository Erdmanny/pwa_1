<?php
$validation = \Config\Services::validation();
?>

<div class="container">
    <h1 class="mt-3 text-light">Login</h1>
    <div class="row mt-3">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="/" method="post">
                        <div class="row m-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('email')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('email') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" value="">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('password')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                            <div class="col-4">
                                <button type="submit" class="btn btn-warning">Login</button>
                            </div>
                            <div class="col-8 text-right mt-1">
                                <a href="/register">No account yet?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

