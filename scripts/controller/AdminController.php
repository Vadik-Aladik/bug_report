<?php 

    require_once __DIR__ . '/../../vendor/classes/Controller.php';
    require_once __DIR__ . '/../../vendor/classes/Validate.php';
    require_once __DIR__ . '/../../vendor/classes/QueryBuilder.php';
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../models/Report.php';
    require_once __DIR__ . '/../../vendor/classes/CSRF.php';

    class AdminController extends Controller
    {
        public function admin()
        {
            $reports = new Report();

            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

            $count = $reports->count('reports')->all();
            $max_page = ceil($count[0]['COUNT(*)']/3);

            $reports_users = $reports->reports_user()->paginate(3, $page)->all();
            return $this->render('admin_panel', compact('reports_users', 'page', 'max_page'));
        }
        
        public function priority($id_report)
        {
            $reports = new Report();
            $csrf = new CSRF();

            if($_POST['fixed'] && $csrf->checkToken($_POST['token'])){
                $reports->update('reports', ['status' => 'Исправлено'])->where(['id', $id_report])->go();
            }
            if($_POST['pass'] && $csrf->checkToken($_POST['token'])){
                $reports->update('reports', ['status' => 'Отложено'])->where(['id', $id_report])->go();
            }

            header("Location: /bug_report/admin");
            exit();
        }

        public function dev_room()
        {
            echo '<h1>wellcome in test room dev</h1> <br>';
            $reports = new Report();
            echo '<pre>';
            print_r($reports->reports_user()->paginate(3, -5)->all());
            // print_r($reports->paginate(5, 4));
            echo '</pre>';
        }
    }

?>