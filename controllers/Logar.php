<?php

require_once ('../models/ApiBanco.php');

$usuario = $_POST['login'];
$senha   = $_POST['senha'];

$login = new ApiBanco();
$logou = $login->logar($usuario, $senha);

if ($logou) {
	echo 1;
	if (!isset($_SESSION)) {
		session_start();
	}
	$_SESSION['user'] = $usuario;
	$_SESSION['pass'] = $senha;
} else {
	echo 0;
}

?>