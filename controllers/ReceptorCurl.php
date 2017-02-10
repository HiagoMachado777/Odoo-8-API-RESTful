<?php

//this file will receive the post requests via curl when a register(example) or a payment are confirmed in your system

//esse arquivo irá receber as requisições post via curl quando um registro(exemplo) ou um pagamento forem confirmados no seu sistema 

require_once ('../models/ApiBanco.php');

$token     = $_POST['token'];
$api       = new ApiBanco;
$validacao = $api->validarToken($token);
$operacao  = $_POST['operacao'];

if ($validacao == true) {
	if ($operacao == 1) {//em caso de registro  [in register]
		$nasc     = $_POST['nascimento']; //birth date
		$tel      = $_POST['celular']; //phone number
		$email    = $_POST['email']; //email
		$name     = $_POST['nome'];// name
		$endereco = $_POST['rua'].', '.$_POST['numero'].'. ('.$_POST['complemento'].'), '.$_POST['bairro']; //adress
		$idprof   = $_POST['atividade_profissional']; //work
		$tipo     = $_POST['tipo_associado']; //register type
		$ref      = $_POST['ref']; //reference code to payment and sales stuff 
		$api->enviarOdoo($name, $email, $idprof, $tel, $nasc, $endereco, $tipo, $ref);
	} else if ($operacao == 2) {//em caso de pagamento de fatura [in payment]
		$referencia = $_POST['referencia'];
		$api->pagarFatura($referencia);
	}
} else {
	return die;
}

?>