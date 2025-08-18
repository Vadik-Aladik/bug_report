<?php 

    // session_start();

    class CSRF
    {
        public function __construct()
        {
            if(!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] == null){
                $_SESSION['csrf_token'] = $this->generateToken();
            }
        }
        
        public function generateToken()
        {
            $token = bin2hex(random_bytes(32));
            return $token;
        }

        public function getToken()
        {
            return $_SESSION['csrf_token'];
        }

        public function checkToken($token)
        {
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(!hash_equals($this->getToken(), $token)){
                    $_SESSION['csrf_token'] = $this->generateToken();
                    return false;
                }

                return true;
            }
        }
    }

?>