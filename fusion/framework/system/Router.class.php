<?php
class Router {
    protected $route;
    protected $routes = array();
    
    public function __construct(){
        $this->route = isset($_GET['r']) ? $_GET['r'] : die('Rota inexistente');  
        
        
        $this->run();
    }
    
    private function run(){
        
        $this->route('Main/Index', 'Main/Indexx');
        
        foreach($this->getRoutes() as $route => $match){
            
        }
    }
    
    public function route($route, $match){
        $this->routes[$route] = $match;
    }
    
    public function getRoutes(){
        return $this->routes;
    }
    
    public function getRoute(){
        return $this->route;
    }
    
    public function getController(){
        return $this->getUriSegment(0);
    }
    
    
    public function getAction(){
        return $this->getUriSegment(1);
    }
    
    public function getParams(){
        return $this->getUriSegment(2).'/'.$this->getUriSegment(3);
    }
    
    public function getUriSegment($pos){
        $fragments = explode('/',$this->getRoute());
        return isset($fragments[$pos]) ? $fragments[$pos] : '';
    }
}
?>