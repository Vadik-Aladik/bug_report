<?php 

    $csrf = new CSRF();
    $json = json_encode($reports_users);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
    <link rel="stylesheet" href="views/style/style.css">
</head>
<body class="bg-main h-screen flex">
    <div class="container flex flex-col">
        <h1 class="mx-auto my-30px color-white">BugReport.ru</h1>

        <div class="report_main bg-white pb-100px pt-30px radius-15px">
            <div class="report_personal flex justify-between items-center text-base mb-30px">
                <p class="report_text"><span class="color-main font-semibold">ADMIN PANEL</span></p>

                <div class="report_buts flex ">
                    <a href="/bug_report" class="a_but logout bg-gray radius-5px px-15px py-8px border-none text-20">Выйти из панели</a>
                </div>
            </div>

            <div class="report_user grid justify-items-center">
                <?php foreach($reports_users as $report){ ?>
                    <div class="report p-10px bg-gray radius-5px w-280px mb-10px cursor-pointer cursor-pointer">
                        <h3 class="text mb-10px"><?= $report['title'] ?></h3>
                        <p class="text text-3str mb-10px"><?= $report['description'] ?></p>
                        <p class="text mb-10px">Пользователь: <span class="color-main font-semibold"><?= $report['login'] ?></span></p>
                        <!-- <p class=" mb-10px">Приоретитет: <?= $report['priority'] ?></p> -->
                        <?php if($report['priority'] == 'Low') echo "<p class=' mb-10px'>Приоретитет: <span class='color-green font-semibold'>$report[priority]</span></p>"; ?>
                        <?php if($report['priority'] == 'Middle') echo "<p class=' mb-10px'>Приоретитет: <span class='color-main font-semibold'>$report[priority]</span></p>"; ?>
                        <?php if($report['priority'] == 'HARD') echo "<p class=' mb-10px'>Приоретитет: <span class='color-red font-semibold'>$report[priority]</span></p>"; ?>
                        <div class="flex justify-between text-16px mb-10px">
                            <!-- <p>Статус: <span class="color-main font-semibold"><?= $report['status'] ?></span></p> -->
                            <?php if($report['status'] == 'Исправлено') echo "<p>Статус: <span class='color-green font-semibold'>$report[status]</span></p>"; ?>
                            <?php if($report['status'] == 'В процессе') echo "<p>Статус: <span class='color-main font-semibold'>$report[status]</span></p>"; ?>
                            <?php if($report['status'] == 'Отложено') echo "<p>Статус: <span class='color-red font-semibold'>$report[status]</span></p>"; ?>
                            <p><?= $report['created_at'] ?></p>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>

    </div>

    <div class="modal fixed top-0 left-0 flex none">
        <div class="moadal_info bg-white py-30px radius-15px m-auto">
            <h2 id="report_title" class="text">Title report</h2>
            <p class=" mb-10px">Пользователь: <span id="report_user" class="text color-main font-semibold">юзер</span></p>
            <div class="flex mb-10px">
                <p>Приоритет: <span id="report_priority" class=" font-semibold mr-20px">Middle</span></p>
                <p id="report_date">0000.00.00</p>
            </div>
            <p id="report_description" class="text report_description mb-10px">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Itaque maiores deserunt sapiente id? Libero numquam nam harum officiis. Odio labore beatae fugit sequi officia tenetur, necessitatibus molestiae! Eveniet, doloribus praesentium.</p>
            <form id="report_form" action="" method="POST" class="flex flex-col items-center mt-30px">
                <input type="hidden" name="token" value="<?= $csrf->getToken() ?>">
                <button class="mb-10px bg-gray radius-5px px-15px py-8px border-none text-20" value="true" name="fixed">Исправлено</button>
                <button class="mb-10px bg-gray radius-5px px-15px py-8px border-none text-20" value="true" name="pass">Отложено</button>
            </form>
            <div class="flex justify-center">
                <button class="close bg-gray radius-5px px-15px py-8px border-none text-20" value="true" name="back">Назад</button>
            </div>
        </div>
    </div>

    <script>
        let report = document.querySelectorAll(".report");
        let modal = document.querySelector(".modal");

        let report_form = document.querySelector("#report_form");
        let report_title = document.querySelector("#report_title");
        let report_description = document.querySelector("#report_description");
        let report_priority = document.querySelector("#report_priority");
        let report_date = document.querySelector("#report_date");
        let report_user = document.querySelector("#report_user");

        let close = document.querySelector(".close");
        let reports_users = <?= $json ?>;

        console.log(report);
        report.forEach((element, index) => {
            element.addEventListener('click', ()=>{
                report_priority.classList.remove('color-green');
                report_priority.classList.remove('color-main');
                report_priority.classList.remove('color-red');
                modal.classList.remove('none');
                console.log(reports_users[index]);
                report_title.textContent = reports_users[index]['title'];
                report_description.textContent = reports_users[index]['description'];

                switch(reports_users[index]['priority']){
                    case 'Low':
                        report_priority.classList.add('color-green');
                        break;
                    case 'Middle':
                        report_priority.classList.add('color-main');
                        break;
                    case 'HARD':
                        report_priority.classList.add('color-red');
                        break;   
                }

                report_priority.textContent = reports_users[index]['priority'];
                report_date.textContent = reports_users[index]['created_at'];
                report_user.textContent = reports_users[index]['login'];
                report_form.action = "priority/"+reports_users[index]['id'];
                // console.log(modal);
                console.log(index);
            })
        });

        close.addEventListener('click', ()=>{
            modal.classList.add('none');
        })

        console.log(reports_users);
        // moadl.classList.remove('.none');
    </script>
</body>
</html>