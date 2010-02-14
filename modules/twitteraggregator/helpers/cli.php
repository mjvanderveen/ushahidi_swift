<?php
/*
 * Created on 7-feb-2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class cli {

	// usage: cli::execute('controller', 'action', 'id', array('parameters'), true, null);
 	public static function execute($controller, $action, $id=null, $args=array(), $async=true, $file=null) {

 		if(!$file)
 			$file = DOCROOT.'index.php';

 		$safe_args = '';

 		foreach($args as $arg) {
 			$safe_args .= ' ' . escapeshellarg(serialize($arg));
 		}

 		// build command query
 		$command = "php $file --uri=$controller/$action" . ($id ? "/$id" : "") . " $safe_args " . ($async ? "> /dev/null &" : "2>&1");

 		// execute fork call
 		return shell_exec($command);
 	}
 }

?>
