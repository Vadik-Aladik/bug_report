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
            $reports_users = $reports->reports_user()->all();
            return $this->render('admin_panel', compact('reports_users'));
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
    }

?>