<?php
/*
 * Inclui a class Application.
 * 
 * @since 1.0
 */

require_once(dirname(__FILE__).'/system/Application.class.php');

/*
 * Inclui a class System Exception que é utilizada Debuggar a Aplicação
 * 
 * @since 1.0
 */

require_once(dirname(__FILE__).'/system/SystemException.class.php');

/*
 * Retorna o caminho atual do core do framework
 * 
 * @since 1.0
 */

return dirname(__FILE__);

?>
