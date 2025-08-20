<?php 

    class Route
    {
        private $routes = [];

        public function add($request, $route, $controller)
        {
            $this->routes[] = compact('request', 'route', 'controller');
        }

        public function dispatch($request, $path)
        {
            foreach($this->routes as $route){
                $rule =  preg_replace('#\{\w+\}#', '([^\/]+)', $route['route']);
                if($request == $route['request'] && preg_match("#^$rule$#", $path, $matches) && preg_match_all('#\{(\w+)\}#', $route['route'], $key)){
                    array_shift($matches);
                    array_shift($key);
                    return call_user_func_array($route['controller'], array_combine($key[0], $matches));
                }

                if($request == $route['request'] && $path == $route['route']){
                    return call_user_func($route['controller']);
                }
            }

            return 'error';

        }

        public function middleware(array $rules, array $urls, $path)
        {
            foreach($urls as $url){
                if($url == $path){
                    foreach($rules as $rule){
                        return call_user_func([new Middleware, $rule]);
                    }
                }
            }
        }
    }

?>