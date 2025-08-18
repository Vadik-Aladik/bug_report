<?php 

    class Database
    {
        private $config;
        public $connect;

        public function __construct()
        {
            $this->config = require __DIR__ . '/../config.php';
            $this->connect();
        }

        private function connect()
        {
            $this->connect = new mysqli($this->config['database']['host'], $this->config['database']['user'], $this->config['database']['password'], $this->config['database']['database']);

            if($this->connect->connect_error){
                echo("ERROR: " . $this->connect->connect_error);
            }

            return $this->connect;
        }
    }

?>