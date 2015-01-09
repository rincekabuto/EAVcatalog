<?php
error_reporting(E_ALL);

$config = require '../config/config.php';

//constants
define("ROOT_SECTION_ID", $config['section_root_id']);
define("ITEMS_PER_PAGE", $config['items_per_page']);

//autoloader
$autoload = function($class){
    $matches = array();
    preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);
    $filepath = '../'.str_replace('\\', '/', $matches['namespace']).$matches['class'].'.php';
    if(file_exists($filepath)){
        include $filepath;
        return $class;
    }
    return false;
};
spl_autoload_register($autoload);

//database

$dbConfig = $config['db']; //TODO:addException
$dsn = sprintf('mysql:host=%s;dbname=%s;',$dbConfig['host'],$dbConfig['schema']);
$user = $dbConfig['username'];
$pass = $dbConfig['password'];
$dbAdapter = new PDO($dsn,$user,$pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
\library\DB::setAdapter($dbAdapter);

//simple routing
$parsedRoute = parse_url($_SERVER['REQUEST_URI']);
$routeChunks = array_filter(explode('/', $parsedRoute['path']), function($el){
    if(is_string($el) && strlen($el) > 0) return true;
});
$controllerName = isset($routeChunks[1]) ? ucfirst($routeChunks[1]) : 'Catalog';
$actionName = isset($routeChunks[2]) ? $routeChunks[2] : 'index';
for($i=3; $i <= count($routeChunks); $i++){
    $params[] = $routeChunks[$i];
}
$params = isset($params) ? $params : null;
$controllerName = '\\controller\\'.$controllerName;
$actionName = $actionName.'Action';
$controller = new $controllerName();
$controller->$actionName($params);
