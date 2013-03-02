<?php   
/*
 * @class: Controller
 * 
 * Esta class manipula o acesso dos controllers e actions através
 * da URL.
 * 
 * @author: Diego Lopes do Nascimento
 * @since: 0.1
 * @package: framework.system
 * @date: 10-12-2012
 * 
 */ 

class Controller extends Router {
    /*
     * Define o controller padrão a ser chamado, caso não seja passado nenhum
     * controller.
     * @access public
     */
    private $defaultController = 'MainController';
    /*
     * Define o action padrão a ser chamado, caso não seja passado nenhum
     * action.
     * @access public
     */
    private $defaultAction = 'index';
    /*
     * Armazena os parâmetros.
     * @access public
     */
    private $params = array();
    
    public $helpers = null;
    
    protected $controller;
    
    protected $action;
    
    private $object;
    
    public function __construct(){
        parent::__construct();
        
        if(parent::getController()=='')
            $this->setController($this->defaultController);
        else
            $this->setController(parent::getController());
        
        if(parent::getAction()=='')
            $this->setAction($this->defaultAction);
        else
            $this->setAction(parent::getAction());
        
        if(parent::getParams() != '')
            $this->params = parent::getParams();
        
        
        if(count( $this->helpers ) > 0)
            Application::helper( $this->helpers );
        
        // Implementa o título da página.
        Application::set('pageTitle', ' > ' . ucfirst( $this->action ));
        
    }
    
    public function setController($name){
        $this->controller = $name;
    }
    
    public function getController(){
        return strstr($this->controller, 'Controller') != false ? ucfirst($this->controller) : ucfirst($this->controller).'Controller';
    }
    
    public function setAction($name){
        $this->action = $name;
    }
    
    public function getAction(){
        return $this->action;
    }
    
    public function getParams(){
        return $this->params;
    }
    
    public function importController($controller){
        $namespace = 'application.controllers.'.$controller;
        $path = Application::getPathFromAlias($namespace,'.php');
        
        if(!is_null($path))
            include_once($path);
        else
            Application::output( printf("O controller <b>%s</b> não existe.", $this->getController()) );
    } 
    
    public function run(){
        $this->importController($this->getController());
        
        $reflection = new ReflectionClass($this->getController());
        
        if($this->IsMethodExists($reflection, $this->getAction())){
            
            if($this->IsParamExists($reflection, $this->getAction())){
                
            } else {
                $this->simpleRun($this->getController(), $this->getAction());
            }
            
        } else {
            Application::output( sprintf('O método <b>%s</b> não existe no Controller <b>%s</b>.', $this->getAction(), $this->getController()) );
        }
    }
    public function simpleRun($controller, $method){
        $object = new $controller();
        $object->$method();
    }
    
    /**/
    
    
    public function runInternal($controller, $method, ReflectionClass $reflection){
        $object = new $controller();
        
        $params = $reflection->getMethod($method)->getParameters();
        
        
        /*if(!$this->hasParams())
            $object->$method(); 
        else {
            echo 'q';
          /*  $params = $reflection->getMethod($method)->getParameters();
            var_dump($params); */
            
             # É uma sugestão criar uma class chamada Action para controlar a passagem
            # de parâmetros.
        /*} */
    }
    
    private function IsParamExists(ReflectionClass $reflection, $method){
        return (count($reflection->getMethod($method)->getParameters())>0) ? true : false;
    }
    
    private function IsMethodExists(ReflectionClass $reflection, $method){
        if($reflection->hasMethod($method))
            return true;
    }
    
    private function hasParams(){
        return ($this->getParams() != '/') ? true : false;
    }
    
  /*  public function loadController($controller){
        var_dump($controller, $this->getController());
        $namespace='application.controllers.'.$controller;
        $path = Application::getPathFromAlias($namespace,'.php');
    
        if($path!=false){
            Application::import($namespace,'.php');
            return new $controller();
        } 
    }*/
    
   /* public function run(){
        $this->controller = $this->getController();
        $this->action = $this->getAction();
        
        $this->obj=$this->loadController($this->controller);
       
        if(!$this->hasParams())
            $this->runNoParams();
        else
            $this->runWithParams();
    }
    private function runNoParams(){
        $obj = $this->getObject();
        $action = $this->getAction();
        $obj->$action();
    } */
 /*   private function runWithParams(){
        $object = $this->getObject();
        
        $class = new ReflectionClass($this->getController());
        $method = $class->getMethod($this->getAction());
        
        $internalParams = $method->getParameters();
        $externalParams = $this->getParams();
        
        if($this->compareParams($internalParams,$externalParams))
            call_user_func_array(array(new $object, $this->getAction()), $this->getParams());
        else
            $this->errorHandler();
    }
    
    private function compareParams($internalParams,$externalParams){
        $i=0;
        $qtdInternal = count($internalParams);
        $qtdExternal = count($externalParams);
        if($qtdInternal != $qtdExternal)
            return false;
        
        foreach($externalParams as $key=>$value){
            if($key!=$internalParams[$i]->name || empty($value))
                return false;           
            $i++;
        }
        return true;
    } */
    /*
     * render
     * 
     * @param string $path É o caminho do arquivo que deverá ser incluso.
     * @param array $data São os dados que serão passados para a view.
     * @param bool $return Flag que delimita se o layout vai ser renderizado ou passado 
     * como string.
     * @return void
     */
  /*  public function render($path,$data=array(),$return=false){
        $view = new View();
        $prepath = explode('/',$path);
        $controller = str_replace('Controller','',$this->getController());
        $path = count($prepath) > 1 ? $path : strtolower($controller).'/'.$path;
        @$view->template = !is_null($this->layout)?$this->layout:$view->template;
        
        $view->load($path ,$data);
    } */
    
    public function render($path, $data){
        
        $view = new View( $this->controller, $this->layout );
        $view->render( $path , $data );
        
    }
    
    public function renderPartial($path, $data){
        
        $view = new View( $this->controller );
        $view->render( $path );
    }
}
?>
