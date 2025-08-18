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
            $reports = $report->select('reports', 'title, description, priority, status, created_at')->where(['user_id', $session['id']])->all();
            return $this->render("personal", compact('session', 'reports'));
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
                'title' => ['required', 'max(128)', 'min(16)'],
                'description' => ['required', 'min(32)'],
                'priority' => ['required'],
            ]);

            $report_validate = $isValid->validation([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'priority' => $_POST['priority'],
            ]);

            if($report_validate && $csrf->checkToken($_POST['token'])){
                $report->create('reports', [
                    'user_id' => $session_user['id'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'priority' => $_POST['priority'],
                    'status' => "В процессе",
                    'created_at' => date("Y-m-d H:i:s"),
                ]);

                destroy_message();
                header("Location: /bug_report");
                exit();
            }
            else{
                $_SESSION['errors_msg'] = $isValid->getErrors();
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: create");
                exit();
            }

            // echo '<pre>';
            // print_r($isValid->getErrors());
            // print_r($report_validate);
            // echo '</pre>';
        }
    }

?>