<?php
/* @class? Application
 * @author: Diego Lopes do Nascimento
 * @email? diiego.lopes01@gmail.com
 * @date: 10-12-12
 * @version: 0.1
 * @package: framework.system
 * @since: 1.0
 * 
 * @note É a class mais importante do framework, pois ela controlará todas as chamadas
 * e funcionamento entre as demais classes.
 * 
 */
class Application {
    private static $params=array();
    /*
     * 
     */
    public static function instance(){
        return new self();
    }
    
    public function run(){
        // Carrega todas as classes.
        $this->autoload();
        
        try {
            
            $controller = new Controller();
            $controller->run();
            
        } catch(SystemException $e){
            
            $mensagem = $e->getMessage();
            echo $mensagem;
            
        }
    }
    
    public static function set($index,$value,$overwrite=false){
        if(array_key_exists($index,self::$params)){
            if(!empty($value)){
                if(is_bool($overwrite)){
                    if($overwrite==false){
                        $oldvalue = self::$params[$index];
                        $value = implode('',array($oldvalue,$value));
                    }
                    self::$params[$index]=$value;
                }
            }
        } else {
            self::$params[$index] = $value;
        }
    }
    
    public function setParams($params){
        if(is_array($params))
            self::$params=$params;
        return $this;
    }
    
    public static function get($index){
        if(isset(self::$params[$index]))
            return self::$params[$index];
    }
    
    /*
     * autoload
     * 
     * Método que importará todas as classes necessárias para o funcionamento
     * do Framework 
     * 
     * @access private
     * @return void
     * 
     * Observação: Ainda em teste! Pois estou pensando em uma maneira de melhorar esse método.
     */
    private function autoload(){
        
        $classes = array('router' => '/system/Router.class.php',
                         'controller' => '/system/Controller.class.php',
                         'view' => '/system/View.class.php');
        
        foreach($classes as $class => $path){
            
            $path = self::getRoot().$path;
            
            if( file_exists( $path ) ){
                include_once( $path );
            } else {
                Application::output( printf("A class <b>%s</b> não pôde ser inicializada, pois o caminho se encontra incorreto.", ucfirst( $class )) );
            }
        }   
    }
    /*
     * 
     */
    public function setRoot($path){
        self::set('root', $path);
        return $this;
    }
    /*
     * 
     */
    public static function getRoot(){
        return self::get('root');
    }
    /*
     * 
     */
    public static function getPath(){
        return self::get('basePath');
    }
    /*
     * import
     * 
     * @param string $namespace É passado o namespace, caminho, da localização do arquivo.
     * @param string $extension É a extensão dos arquivos que serão inseridos. .php, .class.php, .tlp
     * @param bool $return Flag que identifica se o arquivo importado será retornado ou não.
     * @since 0.1
     * @return void;
     * 
     * Observação: Foi implementada o carregamento automatico do diretório. Ainda encontra-se em versão de testes.
     * 
     * Exemplo: application.controllers.*
     */
    public static function import($namespace,$extension='.class.php',$return = false){
        
        $readAll = false;
        $path = null;
        if(stripos($namespace,'*'))
            $readAll = true;
        
        if($readAll == false)
            $path = self::getPathFromAlias($namespace,$extension);
         elseif($readAll == true) {
            $namespace = str_replace(array('*','.'),array('',DS),$namespace);
            $dir = opendir($namespace);
            
            while(false !== ($file = readdir($dir))){
                if($file != '.' && $file != '..')
                    $path = $namespace.$file;
            }
        }
        
        if($path == '')
            echo 'Class não carregada, pois o caminho está incorreto.';
        else {
            if($return)
                return include_once($path);
            else
                include_once($path);
        }
    }
    
    /*
     * getPathFromAlias
     *
     * @param string $namespace É passado o namespace da localização do arquivo.
     * @param string $extension É a extensão dos arquivos que serão inseridos. .php, .class.php, .tlp
     * @return mixed $fullPath Retorna o caminho completo , se verdadeiro.;
     * 
     */
    public static function getPathFromAlias($path,$extension = null){
        
        $path = self::get('basePath').'/'.str_replace('.','/',$path);  
        $fullPath = is_null($extension)?$path:$path.$extension;
        
        if(file_exists($fullPath))
            return $fullPath;
        else{
            return null;
        }
    }
    /*
     * Write
     * 
     * 
     * @param string $var é o nome da constant a ser declarada.
     * @param string $value é o valor que a constant terá.
     * @return void;
     */
    public static function write($var,$value){
        if(!defined($var))
            define($var,$value);
    } 
    
    /*
     * @param mixed $name
     */
    public static function helper($name){
        
        if(is_array($name)){
            for($i = 0; $i <= count($name)-1; $i++)
                self::getHelper($name[$i]);
            
        } else
            self::getHelper($name);
    }
    /*
     * @return path.to.helper
     */
    private static function getHelper($index){
        $path = self::getRoot().'/helpers/'.$index;
        
        if(file_exists( $path . '.php' ))
            include_once( $path . '.php' );
        else if( file_exists( $path . '.class.php' ) )
            include_once( $path . '.class.php' );
        else
            Application::output( printf('Helper <b>%s</b> não existe no caminho <b>%s</b>', $index, $path) );
    }
    
    
    /*
     * Output
     * 
     * Adiciona uma mensagem de saída da Aplicação, instanciando a class SystemException que é
     * filha da class Exception, logo, possui os mesmos métodos da class Exception.
     * 
     * @param string $message É a mensagem a ser mostrada
     * @param int $code É o codigo a ser passado.
     */
    public static function output($message, $code = 0){
        throw new SystemException($message, $code);
    }
}

/*
 * Funciona como um atalho para acessar a class Application e seus respectivos
 * métodos.
 */
class App extends Application {}


?>
