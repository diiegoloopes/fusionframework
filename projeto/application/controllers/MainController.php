<?php
class MainController extends Controller{
    /*
     * Esse exemplo de renderização de layout só é válido
     * caso o usuário queira carregar um layout sem que o mesmo
     * esteja na pasta layout
     */
    //public $layout = '..//layout/main';
    /*
     * Esse exemplo de renderização de layout só é válido quando
     * se é necessário carregar um template que esteja dentro da
     * pasta layout.
     */
    public $helpers = array('Html');
    
    public $layout = 'main';
    
    public function index(){
        
        echo 'Oi';

    }
    
    public function download(){
        
        $this->render('download', array('page' => 'Download'));
        
    }
    
    public function contato(){
        
        $this->render('contato',array('model'=>'Aqui será passado uma model.'));
    } 
    
    
    
    /*
    public function contato($id){
        $this->render('main/contato');
    }*/
}
?>
