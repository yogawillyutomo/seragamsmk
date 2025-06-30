<?php $this->view('templates/header', $data); ?>

<?php Flasher::flash(); ?>

<!-- <div class="container mt-5"> -->
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-4">
        <div class="card shadow rounded-4">
            <div class="card-body">
                <h4 class="text-center mb-4">Login</h4>
                <form action="<?= BASEURL; ?>/auth/login" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- </div> -->

<?php $this->view('templates/footer'); ?>