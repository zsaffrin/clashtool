<?php

function autoload($class) {
	if (file_exists(LIBS_PATH.$class.".php")) {
		require LIBS_PATH.$class.".php";
	} else {
		exit ('The file '.$class.'.php not found in libs folder');
	}
}

spl_autoload_register("autoload");

?>