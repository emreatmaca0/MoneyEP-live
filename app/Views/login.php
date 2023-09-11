<?= $this->extend('default') ?>

<?= $this->section('content') ?>

<section class="py-4 py-md-5 my-5">
    <div class="container py-md-5">
        <div class="row">
            <div class="col-md-6 text-center"><img class="img-fluid w-100" src="/img/illustrations/login.svg"></div>
            <div class="col-md-5 col-xl-4 text-center text-md-start">
                <h2 class="display-6 fw-bold mb-5"><span class="underline pb-1"><strong>Login</strong><br></span></h2>
                <form method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3"><input class="shadow form-control" type="email" name="email" placeholder="Email"></div>
                    <div class="mb-3"><input class="shadow form-control" type="password" name="password" placeholder="Password"></div>
                    <div class="mb-5"><button class="btn btn-primary shadow" type="submit">Log in</button></div>
                    <p class="text-muted"><a href="forgotten-password">Forgot your password?</a></p>
                </form>

                <?php if (isset($login_error)): ?>
                    <div class="login-errors">
                        <ul>

                                <li style="color: red"><?php echo $login_error; ?></li>

                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
