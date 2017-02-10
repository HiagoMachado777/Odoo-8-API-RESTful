<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['pass'])) {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="estilo/js/jquerymask.js"></script>
    <script src="estilo/js/comportamento.js"></script>
    <script src="estilo/js/mascara.js"></script>
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
			<div class="pull-right pull-right-margin">
				<h5 class="odmin-info"><span class="log-as-1">Logado como: </span><span class="log-as-2"><?=$_SESSION['user']?></span></h5>
				<a class="sair" id="sair" href="#">Sair</a>
			</div>
		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="painel-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
			<h2>Área do administrador:</h2>
			<ul class="menu">
				<li class="lista-admin" id="add-app"><p class="link-bar-app">Registrar aplicação</p></li>
				<li class="lista-admin" id="rec-app"><p class="link-bar-app">Aplicações registradas</p></li>
				<li class="lista-admin" id="add-user"><p class="link-bar-app">Registrar usuário</p></li>
				<li class="lista-admin" id="rec-user"><p class="link-bar-app">Usuários registrados</p></li>
			</ul>
		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="painel-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 margin" id="conteudo">

		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="painel-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 margin" id="cadastroUser">
			<h3>Cadastro de novo usuário:</h3>
			<form action="">
				<input type="text" class="input-login" placeholder="Nome" id="nomeCadastroUser"/> <br/>
				<input type="password" class="input-senha" placeholder="Senha" id="senhaCadastroUser"/> <br><br>
				<p id="aviso"></p>
				<br>
				<button type="button" id="btnCadastrarUser" disabled class="btn">Cadastrar</button>
			</form>
		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="painel-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3  margin" id="cadastroApp">
			<h3>Cadastro de nova aplicação:</h3>
			<form action="">
				<input type="text" class="input-login" placeholder="Aplicação" id="nomeCadastroApp"/> <br/>
				<input type="text" class="input-senha" placeholder="Validade" id="validadeCadastroApp"/> <br><br>
				<p id="aviso2"></p>
				<br>
				<button type="button" id="btnCadastrarApp" disabled class="btn">Cadastrar</button>
			</form>
		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="suc-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 alert alert-success margin" id="sucesso">
			<h1>Usuário cadastrado com sucesso!</h1>
		</div>
	</div>

	<div class="row-fluid admin-row">
		<div class="suc-box col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 alert alert-success margin" id="sucesso2">

		</div>
	</div>



</body>
</html>