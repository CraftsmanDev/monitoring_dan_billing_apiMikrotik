<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url("css/input.css") ?>">
    <link rel="icon" href="<?= base_url('assest/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <section class="center h-screen">
        <div class="wrapper">
            <form class="form" action="<?= base_url('login') ?>" method="post">
                <?= csrf_field(); ?>
                <div class="form-header">
                    <h1 class="title fs-md">Sign In</h1>
                    <p class="subtitle">Enter your credentials to continue</p>
                </div>
                <!-- <div class="error">
                    <p></p>
                </div> -->
                <div class="form-group col">
                    <label class="label">Username</label>
                    <input type="text" class="input-light" name="username" value="<?= old('username') ?>" required>
                </div>

                <div class="form-group col">
                    <label class="label">Password</label>
                    <input type="password" class="input-light" id="password" name="password">
                    <span class="eye" onclick="togglePassword()">
                        <i id="eyeIcon" class="fa-regular fa-eye-slash"></i>
                    </span>
                </div>

                <div class="form-group flex between align-center" style="margin-top: 20px;">
                    <label class="checkbox">
                        <input type="checkbox" class="input-checkbox" id="remember">
                        Remember Me
                    </label>

                    <a href="#" class="link">Forgot password?</a>
                </div>

                <button class="btn">Login</button>
            </form>
        </div>
    </section>
</body>
<script src="<?= base_url("js/script.js") ?>"></script>

</html>