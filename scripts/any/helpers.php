<?php 

    function hsc($data){
        return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    }

    function auth($arr){
        $_SESSION['user']['auth'] = true;
        $_SESSION['user']['id'] = $arr['id'];
        $_SESSION['user']['login'] = $arr['login'];
    }

    function destroy_message(){
        if(isset($_SESSION['errors_msg']) || isset($_SESSION['old_data'])){
            unset($_SESSION['errors_msg']);
            unset($_SESSION['old_data']);
        }
    }

    function errors_message($field){
        if(isset($_SESSION['errors_msg'][$field])) return $_SESSION['errors_msg'][$field][0];
    }

    function old_data($field){
        if(isset($_SESSION['old_data'][$field])) return hsc($_SESSION['old_data'][$field]);
    }

?>