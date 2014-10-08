<?php

function autoload($class) {
	if (file_exists(LIBS_PATH.$class.".php")) {
		require LIBS_PATH.$class.".php";
	} else {
		exit ('Could not find '.$class.' and there is no '.$class.'.php in the libs folder.');
	}
}

spl_autoload_register("autoload");

?>
