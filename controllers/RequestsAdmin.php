<?php

require_once ('../models/ApiBanco.php');

$codigo = $_POST['codigo'];

$api = new ApiBanco();

if ($codigo == 0) {
	echo $api->listarApiApps();
} else if ($codigo == 1) {
	echo $api->listarApiUsers();
} else if ($codigo == 2) {
	$nomeApp     = $_POST['nomeApp'];
	$validadeApp = $_POST['validade'];
	$validadeApp = ApiBanco::converterDataFormatoBanco($validadeApp);
	if ($validadeApp) {
		$disp = $api->disponibilidadeApp($nomeApp, $validadeApp);
		echo $disp;
	} else {
		echo '3';
	}
} else if ($codigo == 3) {
	$nomeUser  = $_POST['nomeuser'];
	$senhaUser = $_POST['senhauser'];
	echo $api->disponibilidade($nomeUser, $senhaUser);
} else if ($codigo == 4) {
	if (!isset($_SESSION)) {
		session_start();
	}
	unset($_SESSION['user']);
	unset($_SESSION['pass']);
}

?>