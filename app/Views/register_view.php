<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 5rem;
        }
        .form-register {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin: auto;
        }
    </style>
</head>
<body class="text-center">

    <form class="form-register" action="<?= site_url('register/submit') ?>" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Register</h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= set_value('username') ?>" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        <a href="<?= site_url('login') ?>">Login</a>
        
        <p class="mt-5 mb-3 text-muted">&copy; 2024</p>
    </form>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
