<?php 

    // session_start();

    // $_SESSION['errors_msg'] = [];
    
    require_once __DIR__ . '/../../vendor/classes/Controller.php';
    require_once __DIR__ . '/../../vendor/classes/Validate.php';
    require_once __DIR__ . '/../../vendor/classes/QueryBuilder.php';
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../models/Report.php';
    require_once __DIR__ . '/../../vendor/classes/CSRF.php';

    class PersonalController extends Controller
    {
        public function personal()
        {
            $report = new Report();
            $session = $_SESSION['user'];

            $count = $report->count('reports')->where(['user_id', $session['id']])->all();
            $max_page = ceil($count[0]['COUNT(*)']/3);
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

            $reports = $report->select('reports', 'id, title, description, priority, status, created_at')->where(['user_id', $session['id']])->paginate(3, $page)->all();
            return $this->render("personal", compact('session', 'reports', 'page', 'max_page'));
        }

        public function create()
        {
            return $this->render("create_report");
        }

        public function createReport()
        {
            $isValid = new Validate;
            $report = new Report;
            $csrf = new CSRF();
            $session_user = $_SESSION['user'];

            $isValid->rules([
                'title' => ['required', 'max(128)', 'min(8)'],
                'description' => ['required', 'min(32)'],
                'priority' => ['required'],
            ]);

            $report_validate = $isValid->validation([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'priority' => $_POST['priority'],
            ]);

            if($_POST['back']){
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }

            if($report_validate && $csrf->checkToken($_POST['token'] && $_POST['create'])){
                $report->create('reports', [
                    'user_id' => $session_user['id'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'priority' => $_POST['priority'],
                    'status' => "В процессе",
                    'created_at' => date("Y-m-d H:i:s"),
                ]);

                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }
            else{
                $_SESSION['errors_msg'] = $isValid->getErrors();
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: create");
                exit();
            }
        }

        public function edit($id_report)
        {
            $report = new Report;
            $session_user = $_SESSION['user'];

            $user_report = $report->select('reports', 'id, title, priority, description')->where(['id', $id_report])->one();
            $check_report = !empty($report->select('reports')->where([['user_id', $session_user['id']], ['id', $id_report]])->one()) ? true : false;

            if($check_report){
                return $this->render("edit_report", compact("user_report", "id_report"));
            }
            else{
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }

        }

        public function editReport($id_report)
        {
            $isValid = new Validate;
            $report = new Report;
            $csrf = new CSRF();
            $session_user = $_SESSION['user'];

            $check_report = !empty($report->select('reports')->where([['user_id', $session_user['id']], ['id', $id_report]])->one()) ? true : false;

            $isValid->rules([
                'title' => ['required', 'max(128)', 'min(8)'],
                'description' => ['required', 'min(32)'],
                'priority' => ['required'],
            ]);

            $report_validate = $isValid->validation([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'priority' => $_POST['priority'],
            ]);

            if($report_validate && $csrf->checkToken($_POST['token']) && $check_report){
                $report->update('reports', ['title'=>$_POST['title'], 'description'=>$_POST['description'], 'priority'=>$_POST['priority']])->where(['id', $id_report])->go();
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }
            else if(!$check_report){
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }
            else{
                $_SESSION['errors_msg'] = $isValid->getErrors();
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: /bug_report/edit/$id_report");
                exit();
            }
        }


    }

?>