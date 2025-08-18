<?php 

    $json = json_encode($reports);

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
                <p class="report_text">Здравствуйте, <span class="color-main font-semibold"><?= $session['login'] ?></span></p>

                <div class="report_buts flex ">
                    <?php if($session['login'] == 'admin') echo "<a href='admin' class='create_report report_create a_but bg-gray radius-5px px-15px py-8px border-none text-20'>Admin panel</a>" ?>
                    <a href="create" class="create_report report_create a_but bg-gray radius-5px px-15px py-8px border-none text-20">Создать репорт</a>
                    <a href="logout" class="logout a_but bg-gray radius-5px px-15px py-8px border-none text-20">Выйти из аккаунта</a>
                </div>
            </div>

            <div class="report_user grid justify-items-center">
                <?php foreach($reports as $report) { ?>
                <div class="report p-10px bg-gray radius-5px w-280px mb-10px cursor-pointer cursor-pointer flex flex-col justify-around">
                    <h3 class="text mb-10px"><?= $report['title'] ?></h3>
                    <p class="text text-3str mb-10px"><?= $report['description'] ?></p>
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
            <h2 id="report_title" class="text mb-10px"></h2>
            <div class="flex mb-10px">
                <p>Статус: <span id="report_status" class="color-main font-semibold mr-20px"></span></p>
                <p id="report_date"></p>
            </div>
            <p id="report_description" class="text mb-10px"></p>
            <div class="flex flex-col items-center mt-30px">
                <button class="close bg-gray radius-5px px-15px py-8px border-none text-20">Назад</button>
            </div>
        </div>
    </div>

    <script>
        let report = document.querySelectorAll(".report");
        let modal = document.querySelector(".modal");

        let report_title = document.querySelector("#report_title");
        let report_status = document.querySelector("#report_status");
        let report_description = document.querySelector("#report_description");
        let report_date = document.querySelector("#report_date");

        let close = document.querySelector(".close");
        let reports_users = <?= $json ?>;

        console.log(report);
        report.forEach((element, index) => {
            element.addEventListener('click', ()=>{
                report_status.classList.remove('color-green');
                report_status.classList.remove('color-main');
                report_status.classList.remove('color-red');
                modal.classList.remove('none');
                console.log(reports_users[index]);
                report_title.textContent = reports_users[index]['title'];
                report_description.textContent = reports_users[index]['description'];
                report_status.textContent = reports_users[index]['status'];

                switch(reports_users[index]['status']){
                    case 'Исправлено':
                        report_status.classList.add('color-green');
                        break;
                    case 'В процессе':
                        report_status.classList.add('color-main');
                        break;
                    case 'Отложено':
                        report_status.classList.add('color-red');
                        break;   
                }

                report_date.textContent = reports_users[index]['created_at'];
            })
        });

        close.addEventListener('click', ()=>{
            modal.classList.add('none');
        })

        console.log(reports_users);
    </script>
</body>
</html>