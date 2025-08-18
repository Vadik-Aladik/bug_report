<?php 

    $csrf = new CSRF();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create report</title>
    <link rel="stylesheet" href="views/style/style.css">
</head>
<body class="bg-main h-screen flex">
    <div class="container flex">
        <form action="create" method="POST" class="bg-white radius-15px py-30px  m-auto flex flex-col items-center px-20px w-fit-content">
            <input type="hidden" name="token" value="<?= $csrf->getToken() ?>">

            <div class="mb-10px">
                <input type="text" class="field border-solid-gray p-6px radius-5px w-366px " name="title" placeholder="Введите название репорта" value="<?= old_data('title') ?>">
                <p class="color-red ml-5px"><?= errors_message('title') ?></p>
            </div>

            <div class="flex flex-col w-100 items-end mb-10px">
                <select name="priority" id="" class=" ml-auto">
                    <option value="" selected>ПРИОРИТЕТ</option>
                    <option value="Low" <?php if(isset($_SESSION['old_data']['priority']) && $_SESSION['old_data']['priority'] == 'Low') echo 'selected' ?>>Low</option>
                    <option value="Middle" <?php if(isset($_SESSION['old_data']['priority']) && $_SESSION['old_data']['priority'] == 'Middle') echo 'selected' ?>>Middle</option>
                    <option value="HARD" <?php if(isset($_SESSION['old_data']['priority'])&& $_SESSION['old_data']['priority'] == 'HARD') echo 'selected' ?>>HARD</option>
                </select>
                <p class="color-red ml-5px"><?= errors_message('priority') ?></p>
            </div>

            <div class="mb-10px ">
                <textarea name="description" id="" class="field border-solid-gray p-6px radius-5px w-366px h-190px resize-none" placeholder="Описание"><?= old_data('description') ?></textarea>
                <p class="color-red ml-5px"><?= errors_message('description') ?></p>
            </div>

            <div class="flex flex-col justify-center">
                <button type="submit" value="true" name="create" class="mt-20px bg-gray radius-5px px-15px py-8px border-none text-20">Создать репорт</button>
                <button type="submit" value="true" name="back" class="mt-20px bg-gray radius-5px px-15px py-8px border-none text-20">Назад</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php 

    destroy_message();

?>