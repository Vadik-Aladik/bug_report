<?php 

    session_start();

    // $_SESSION['errors_msg'] = [];
    
    require_once __DIR__ . '/../../vendor/classes/Controller.php';
    require_once __DIR__ . '/../../vendor/classes/Validate.php';
    require_once __DIR__ . '/../../vendor/classes/QueryBuilder.php';
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../../vendor/classes/CSRF.php';

    class UserController extends Controller
    {
        public function sign_in()
        {
            return $this->render('sign_in');
        }

        public function sign_up()
        {
            return $this->render('sign_up');
        }

        public function auth()
        {
            $isValid = new Validate;
            $user = new User;
            $csrf = new CSRF();
            $isValid->rules([
                'email' => ['required', 'email', 'max(256)', 'exists(users,email)'],
                'password' => ['required', 'min(8)'],
            ]);

            $sign_in = $isValid->validation([
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            ]);

            $auth_user = $user->select('users', 'id, login, password')->where(['email', $_POST['email']])->one();
            $password_verify = password_verify($_POST['password'], $auth_user['password']);

            if($sign_in && $password_verify && $csrf->checkToken($_POST['token'])){
                auth($auth_user);
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }
            else if($sign_in && $csrf->checkToken($_POST['token'])){
                $_SESSION['errors_msg']['password'][] = 'Неверный пароль';
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: sign_in");
                exit();
            }
            else{
                $_SESSION['errors_msg'] = $isValid->getErrors();
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: sign_in");
                exit();
            }
        }

        public function store()
        {
            $isValid = new Validate;
            $user = new User;
            $csrf = new CSRF();

            $isValid->rules([
                'login' => ['required', 'min(4)', 'unique(users,login)'],
                'email' => ['required', 'email', 'max(256)', 'unique(users,email)'],
                'password' => ['required', 'min(8)'],
                'password_confirm' => ['required', 'same(password)']
            ]);

            $sign_up = $isValid->validation([
                'login' => $_POST['login'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'password_confirm' => $_POST['password_confirm'],
            ]);

            if($sign_up && $csrf->checkToken($_POST['token'])){
                $user->create('users', [
                    'login' => $_POST['login'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                    'created_at' => date("Y-m-d H:i:s"),
                ]);
                
                $new_user = $user->select('users', 'id, login')->where(['email', $_POST['email']])->one();

                auth($new_user);
                destroy_message();
                header("Location: /bug_report?page=1");
                exit();
            }
            else{
                $_SESSION['errors_msg'] = $isValid->getErrors();
                $_SESSION['old_data'] = $isValid->getData();
                header("Location: sign_up");
                exit();
            }
        }

        public function logout()
        {
            $_SESSION = [];
            session_destroy();
            header("Location: sign_in");
            exit();
        }
        
    }

?>