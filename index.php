<?php 

    require_once "vendor/classes/Route.php";
    require_once "vendor/classes/Middleware.php";
    require_once 'scripts/controller/UserController.php';
    require_once 'scripts/controller/PersonalController.php';
    require_once 'scripts/controller/AdminController.php';

    $route = new Route;
    $userController = new UserController;

    $script_name = dirname($_SERVER['SCRIPT_NAME']); // e.g. /bug_report
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = '/' . ltrim(substr($path, strlen($script_name)), '/');

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

    $route->add('GET', '/sign_in', [new UserController, 'sign_in']);
    $route->add('POST', '/sign_in', [new UserController, 'auth']);
    $route->add('GET', '/sign_up', [new UserController, 'sign_up']);
    $route->add('POST', '/sign_up', [new UserController, 'store']);

    $route->add('GET', '/logout', [new UserController, 'logout']); 
    $route->add("GET", "/", [new PersonalController, 'personal']);
    $route->add("GET", "/create", [new PersonalController, 'create']);
    $route->add("POST", "/create", [new PersonalController, 'createReport']);

    $route->add("GET", "/admin", [new AdminController, 'admin']);
    $route->add("POST", "/priority/{id_report}", [new AdminController, 'priority']);

    $route->middleware(['sign'], ['/sign_in', '/sign_in', '/sign_up', '/sign_up'], $path);
    $route->middleware(['auth'], ['/', '/create', '/create', '/logout'], $path);
    $route->middleware(['admin'], ['/admin', '/priority/{id_report}'], $path);
    
    echo($route->dispatch($_SERVER['REQUEST_METHOD'], $path));

?>