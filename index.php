<?php
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
	header('Location: admin.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="estilo/js/comportamento.js"></script>
    <link rel="stylesheet" href="estilo/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="estilo/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Odmin</title>
</head>
<body>

	<div class="container-fluid">
		<div class="jumbotron">
			<h1 class="odmin"><span class="cor-odoo">o</span><span class="cor-odoo-2">dmin</span></h1>
		</div>
	</div>

	<div class="row-fluid login-row">
		<div class="login-box col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
			<h3>Login:</h3>
			<form action="">
				<input class="input-login" placeholder="Usuário" id="nome" type="text"> <br>
				<input class="input-senha" placeholder="Senha" id="senha" type="password"> <br>
				<p id="aviso"></p>
				<button class="btn" type="button" id="logar" disabled />Entrar</button>
			</form>
		</div>
	</div>



</body>
</html>
