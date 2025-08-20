<?php 

    class Middleware
    {
        public function sign()
        {
            if(isset($_SESSION['user']) && $_SESSION['user']['auth'] == true){
                // echo 'Эта страница не доступна, так как пользователь уже авторозирован';
                header("Location: /bug_report");
                exit();
            }
        }

        public function auth()
        {
            if(!isset($_SESSION['user']) || $_SESSION['user']['auth'] == false){
                // echo 'Эта страница не доступна, так как пользователь не авторозирован';
                header("Location: sign_in");
                exit();
            }
        }

        public function admin()
        {
            if(!isset($_SESSION['user']) || $_SESSION['user']['login'] != 'admin'){
                header("Location: /bug_report");
                exit();
            }
        }

        // public function check_record_availability_edit()
        // {
            
        // }
    }

?>