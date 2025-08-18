<?php 

    class Controller
    {
        public function render(string $view, array $data = [])
        {
            if(isset($data) || !empty($data)){
                extract($data);
            }
            include __DIR__ . "/../../views/$view.php";
        }

        // public function render(string $view)
        // {
        //     include __DIR__ . "/../../views/$view.php";
        // }
    }

?>