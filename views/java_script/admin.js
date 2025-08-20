let report = document.querySelectorAll(".report");
        let modal = document.querySelector(".modal");

        let report_form = document.querySelector("#report_form");
        let report_title = document.querySelector("#report_title");
        let report_description = document.querySelector("#report_description");
        let report_priority = document.querySelector("#report_priority");
        let report_date = document.querySelector("#report_date");
        let report_user = document.querySelector("#report_user");

        let close = document.querySelector(".close");
        // let reports_users = <?= $json ?>;

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