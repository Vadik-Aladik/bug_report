<?php 

    require_once "vendor/classes/Route.php";
    require_once "vendor/classes/Middleware.php";
    require_once 'scripts/controller/UserController.php';
    require_once 'scripts/controller/PersonalController.php';
    require_once 'scripts/controller/AdminController.php';
    require_once 'scripts/any/helpers.php';

    $route = new Route;
    $userController = new UserController;

    $script_name = dirname($_SERVER['SCRIPT_NAME']); // e.g. /bug_report
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = '/' . ltrim(substr($path, strlen($script_name)), '/');

    $route->add('GET', '/sign_in', [new UserController, 'sign_in']);
    $route->add('POST', '/sign_in', [new UserController, 'auth']);
    $route->add('GET', '/sign_up', [new UserController, 'sign_up']);
    $route->add('POST', '/sign_up', [new UserController, 'store']);

    $route->add('GET', '/logout', [new UserController, 'logout']); 
    $route->add("GET", "/", [new PersonalController, 'personal']);

    $route->add("GET", "/create", [new PersonalController, 'create']);
    $route->add("POST", "/create", [new PersonalController, 'createReport']);

    $route->add("GET", "/edit/{id_report}", [new PersonalController, 'edit']);
    $route->add("POST", "/edit/{id_report}", [new PersonalController, 'editReport']);

    $route->add("GET", "/dev_room", [new AdminController, 'dev_room']);
    $route->add("GET", "/admin", [new AdminController, 'admin']);
    $route->add("POST", "/priority/{id_report}", [new AdminController, 'priority']);

    $route->middleware(['sign'], ['/sign_in', '/sign_in', '/sign_up', '/sign_up'], $path);
    $route->middleware(['auth'], ['/', '/create', '/create', '/logout', '/edit/{id_report}', '/edit/{id_report}'], $path);
    $route->middleware(['admin'], ['/admin', '/priority/{id_report}', '/dev_room'], $path);  
    
    echo($route->dispatch($_SERVER['REQUEST_METHOD'], $path));

?>