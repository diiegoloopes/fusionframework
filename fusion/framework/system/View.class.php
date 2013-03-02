<?php
class View {
    private $view;
    private $layout;
    private $content;
    
    public function __construct( $dir, $layout = null ){
        
        $this->dir = $dir;
        
        if( !is_null($layout) )
            $this->layout = $layout;
    }
    /*
     * @return void
     */
    public function render($path, $data){
        
        $data = $this->renderInternal( $path, $data );
        
        extract( $data, EXTR_OVERWRITE );
        
        if( !empty( $this->layout ) ) {
            
            $layout = $this->getLayoutPath().'/'.$this->layout.'.php';
            
            if(! file_exists( $layout ) ){
                Application::output( printf('O layout <b>%s</b> não pôde ser inicializado, pois o caminho <b>%s</b> está incorreto.', $this->layout, $layout) );
            } else {
                
                ob_start();
                    include_once( $layout );
                    
                    $layout = ob_get_contents();
                ob_clean();
                
                $search = array('{content}', '{title}');
                $replace = array( $content, Application::get('pageTitle') );
                
                $layout = str_replace( $search, $replace, $layout );
                
                echo $layout;
            }
            
        } else {
            echo $content;
        }
        
    }
    
    /*
     * @return array
     */
    private function renderInternal($path, $data){
        $view = $this->getViewPath().'/'.$this->dir.'/'.str_replace('.php', '', $path).'.php';
        
        if(file_exists( $view )){
            ob_start();
                extract( $data, EXTR_OVERWRITE );
                include_once( $view );
                $data['content'] = ob_get_contents();
            ob_clean();
            
        } else {
            Application::output( printf("A View <b>%s</b> não pôde ser inicializada, pois o caminho <b>%s</b> está incorreto", $path, $view ));
        }
        return $data;
    }
    
    /*
     * @return string
     */
    private function getLayoutPath(){
        return $this->getViewPath().'/layout';
    }
    /*
     * @return string
     */
    private function getViewPath(){
        return Application::getPath().'/application/views';
    }
}
?>