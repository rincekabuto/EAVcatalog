<?php

namespace controller;

abstract class AbstractController {
    protected $dbAdapter;
    
    public function __construct() {
        $this->dbAdapter = \library\DB::getAdapter();
    }
    
    protected function render($template, $params = null){
        $templateDir = strtolower(preg_filter("^controller\\\^",'',get_called_class()));
        $templatePath = '../view/'.$templateDir.'/'.$template.'.php';
        if($params && is_array($params)){
            extract($params);
        }
        ob_start();
        include $templatePath;
        $content = ob_get_clean();
        $layoutPath = '../view/layout/layout.php';
        ob_start();
        include $layoutPath;
        $content = ob_get_clean();
        return $content;
    }
}

?>
