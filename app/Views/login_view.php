<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 5rem;
        }
        .form-signin {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin: auto;
        }
    </style>
</head>
<body class="text-center">

    <form class="form-signin" action="<?= site_url('login/submit') ?>" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Log in</h1>

        <?php if (isset($session->error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $session->error ?>
            </div>
        <?php endif; ?>

        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control mb-3" placeholder="Username" value="<?= set_value('username') ?>" required autofocus>
        
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <a href="<?= site_url('register') ?>">Register</a>
        
        <p class="mt-5 mb-3 text-muted">&copy; 2024</p>
    </form>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
