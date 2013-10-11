<?php
	/***
	 * @author Andres Aldana M
	 * @date 27/09/2013
	 **/
	 
	require_once '/../src/File_Bootstrap.php';
	
	switch((int)$argv[1])
	{
		case 1: 
			require_once 'generador.php';
			break;
		default:
			echo "No definida ... :'(";
			break;
	}
?>