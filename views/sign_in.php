<?php 
    $csrf = new CSRF;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="views/style/style.css">
</head>
<body class="bg-main h-screen flex">
    <div class="container flex">
        <form action="sign_in" class="sign bg-white py-30px w-406px m-auto radius-15px flex items-center flex-col" method="POST">
            <h2 class="mb-20px">ВХОД</h2>
            <input type="hidden" name="token" value="<?= $csrf->getToken() ?>">
            <div class="mb-10px">
                <input type="text" class="field border-solid-gray p-6px radius-5px w-366px" name="email" placeholder="Введите email" value="<?= old_data('email') ?>">
                <p class="color-red ml-5px"><?= errors_message('email') ?></p>
            </div>

            <div class="mb-10px">
                <input type="password" class="field border-solid-gray p-6px radius-5px w-366px" name="password" placeholder="Введите пароль" value="<?= old_data('password') ?>">
                <p class="color-red ml-5px"><?= errors_message('password') ?></p>
            </div>
            <button class="mt-20px bg-gray radius-5px px-15px py-8px border-none text-20" type="submit">ВОЙТИ</button>
            <a href="sign_up" class="mt-10px">У меня нет <span class="color-main">аккаунта</span></a>
        </form>
    </div>
</body>
</html>

<?php 

    destroy_message();

?>