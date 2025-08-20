let report = document.querySelectorAll(".report");
let modal = document.querySelector(".modal");
let edit_report = document.querySelector("#edit_report");
let report_title = document.querySelector("#report_title");
let report_status = document.querySelector("#report_status");
let report_description = document.querySelector("#report_description");
let report_date = document.querySelector("#report_date");

let close = document.querySelector(".close");
        // let reports_users = <?= $json ?>;

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

        edit_report.href = '/bug_report/edit/' + reports_users[index]['id'];
    })
});

close.addEventListener('click', ()=>{
    modal.classList.add('none');
})

console.log(reports_users);