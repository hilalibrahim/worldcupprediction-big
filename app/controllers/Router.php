<?php
/**
 * PredictCup Router
 * Handles URL routing for the application
 */

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->setupRoutes();
    }
    
    private function setupRoutes() {
        $this->addRoute('', 'MainController', 'index');
        $this->addRoute('/', 'MainController', 'index'); // Also match slash
        $this->addRoute('register', 'AuthController', 'register');
        $this->addRoute('login', 'AuthController', 'login');
        $this->addRoute('logout', 'AuthController', 'logout');
        $this->addRoute('forgot-password', 'AuthController', 'forgotPassword');
        $this->addRoute('reset-password/([a-zA-Z0-9]+)', 'AuthController', 'resetPassword', ['token' => 1]);
        $this->addRoute('profile', 'AuthController', 'profile');
        $this->addRoute('dashboard', 'MainController', 'dashboard');
        $this->addRoute('daily-matches', 'MainController', 'dailyMatches');
        $this->addRoute('match/([0-9]+)', 'MainController', 'matchDetail', ['id' => 1]);
        $this->addRoute('predict', 'MainController', 'predict');
        $this->addRoute('leaderboard', 'MainController', 'leaderboard');
        $this->addRoute('about', 'MainController', 'about');
        $this->addRoute('rooms', 'RoomController', 'getAllRooms');
        $this->addRoute('rooms/create', 'RoomController', 'create');
        $this->addRoute('rooms/join', 'RoomController', 'join');
        $this->addRoute('rooms/view/([0-9]+)', 'RoomController', 'view', ['id' => 1]);
        $this->addRoute('rooms/edit/([0-9]+)', 'RoomController', 'edit', ['id' => 1]);
        $this->addRoute('rooms/delete/([0-9]+)', 'RoomController', 'delete', ['id' => 1]);
        $this->addRoute('rooms/members/([0-9]+)', 'RoomController', 'members', ['id' => 1]);
        $this->addRoute('rooms/leave/([0-9]+)', 'RoomController', 'leave', ['id' => 1]);
        $this->addRoute('admin/login', 'AdminController', 'login');
        $this->addRoute('admin/logout', 'AdminController', 'logout');
        $this->addRoute('admin/dashboard', 'AdminController', 'dashboard');
        $this->addRoute('admin/teams', 'AdminController', 'teams');
        $this->addRoute('admin/matches', 'AdminController', 'matches');
        $this->addRoute('admin/users', 'AdminController', 'users');
        $this->addRoute('admin/rooms', 'AdminController', 'rooms');
        $this->addRoute('admin/api', 'AdminController', 'api');
        $this->addRoute('api/([a-zA-Z0-9_-]+)', 'ApiController', 'handleRequest', ['action' => 1]);
    }
    
    public function addRoute($pattern, $controller, $method, $params = []) {
        $this->routes[] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'method' => $method,
            'params' => $params
        ];
    }
    
    public function run() {
        $uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($uri, PHP_URL_PATH);
        
        $uri = $parsed_uri;
        $uri = str_replace('/worldcupprediction-big', '', $uri);
        $uri = trim($uri, '/');
        
        if (empty($uri)) {
            $uri = '';
        }
        
        foreach ($this->routes as $route) {
            // Use # as regex delimiter since route patterns may contain /
            $pattern = '#^' . $route['pattern'] . '$#i';
            
            if (preg_match($pattern, $uri, $matches)) {
                $params = [];
                foreach ($route['params'] as $key => $index) {
                    $params[$key] = $matches[$index];
                }
                $this->executeRoute($route['controller'], $route['method'], $params);
                return;
            }
        }
        
        $this->show404();
    }
    
    private function executeRoute($controller, $method, $params) {
        $controllerFile = __DIR__ . "/{$controller}.php";
        
        if (!file_exists($controllerFile)) {
            $this->show404();
            return;
        }
        
        require_once $controllerFile;
        
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $method)) {
            $this->show404();
            return;
        }
        
        call_user_func_array([$controllerInstance, $method], $params);
    }
    
    private function show404() {
        http_response_code(404);
        echo "<div style='max-width: 800px; margin: 50px auto; padding: 20px; border: 2px solid #f00; background: #fff;'>";
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page you requested does not exist.</p>";
        $uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($uri, PHP_URL_PATH);
        echo "<div style='background: #ffe6e6; padding: 10px; margin: 10px 0;'>";
        echo "<strong>Debug Info:</strong><br>";
        echo "Original URI: $uri<br>";
        echo "Parsed URI: $parsed_uri<br>";
        echo "BASE_URL: " . BASE_URL . "<br>";
        echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";
        echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "<br>";
        echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
        echo "</div>";
        echo "<a href='" . BASE_URL . "'>Return Home</a>";
        echo "</div>";
    }
}
