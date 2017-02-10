<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ('../ripcord/ripcord.php');
require_once ('Odoo.php');

class Customer {

	var $odoo;

	public function __construct($odoo) {

		$this->odoo = $odoo;
	}

	function addCustomer($nome, $mail, $profission, $phon, $birth, $ender, $tipo, $ref) {

		if ($tipo == 1) {
			$price   = 150;
			$prod_id = 11;
		} else if ($tipo == 2) {
			$price   = 300;
			$prod_id = 12;
		} else if ($tipo == 3) {
			$price   = 600;
			$prod_id = 13;
		}

		$models = ripcord::client($this->odoo->url."/xmlrpc/2/object");

		$account = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.account', 'create', array(array('name' => $ref, 'user_type' => 2, 'type' => 'other', 'code' => $ref)));

		$accid = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.account', 'search_read', array(array(array('name', '=', $ref))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];

		//add user no db
		$id = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'res.partner', 'create', array(array('name' => $nome,
					'birthdate'                                                                                                                      => $birth,
					'phone'                                                                                                                          => $phon,
					'function'                                                                                                                       => $profission,
					'email'                                                                                                                          => $mail,
					'ref'                                                                                                                            => $ref,
					'street'                                                                                                                         => $ender)));

		//pegar id do cliente
		$client = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'res.partner', 'search_read', array(array(array('customer', '=', true), array('ref', '=', $ref))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];

		//criando cotação provisoria
		$venda = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'sale.order', 'create', array(array('client_order_ref' => $ref, 'invoice_quantity' => 'draft invoice', 'state' => 'progress', 'partner_id' => $client['id'], 'name' => $ref)));

		$vendaid = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'sale.order', 'search_read', array(array(array('name', '=', $ref))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];

		//criando linha de venda
		$orderline = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'sale.order.line', 'create', array(array('order_id' => $vendaid['id'], 'state' => 'confirmed', 'name' => $ref, 'product_id' => $prod_id)));

		//criar fatura
		$invoice = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice', 'create', array(array('name' => $ref, 'type' => 'out_invoice', 'reference' => $ref, 'state' => 'open', 'account_id' => $accid['id'], 'vendedor_name' => 'site', 'partner_id' => $client['id'])));

		$invoiceid = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice', 'search_read', array(array(array('name', '=', $ref))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];

		$invoiceline = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice.line', 'create', array(array('name' => $ref, 'partner_id' => $client['id'], 'product_id' => $prod_id, 'price_unit' => $price, 'invoice_id' => $invoiceid['id'], 'account_id' => $accid['id'])));

		print_r($account);
		//print_r($invoice);

	}

	function pagarFatura($referencia) {

		$models = ripcord::client($this->odoo->url."/xmlrpc/2/object");

		$client = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'res.partner', 'search_read', array(array(array('customer', '=', true), array('ref', '=', $referencia))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];
		$partner_id = $client['id'];

		$venda = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'sale.order', 'search_read', array(array(array('name', '=', $referencia))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];
		$sale_id = $venda['id'];

		$account = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.account', 'search_read', array(array(array('name', '=', $referencia))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];
		$account_id = $account['id'];

		$invoice = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice', 'search_read', array(array(array('name', '=', $referencia))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];
		$invoice_id = $invoice['id'];

		$invoiceline = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice.line', 'search_read', array(array(array('invoice_id', '=', $invoice_id))), array(
				'limit'  => 1,
				'fields' => array('id', 'price_unit')))[0];
		$price = $invoiceline['price_unit'];

		$models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.invoice', 'write', array(array($invoice_id), array('state' => 'paid')));

		$voucher   = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.voucher', 'create', array(array('state' => 'posted', 'payment_option' => 'with_writeoff', 'pay_now' => 'pay_now', 'partner_id' => $partner_id, 'name' => $referencia, 'reference' => $referencia, 'account_id' => $account_id, 'type' => 'receipt', 'amount' => $price)));
		$voucherid = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.voucher', 'search_read', array(array(array('name', '=', $referencia), array('partner_id', '=', $partner_id))), array(
				'limit'  => 1,
				'fields' => array('id')))[0];

		$voucherline = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'account.voucher.line', 'create', array(array('pay_now'     => 'pay_now', 'partner_id'     => $partner_id, 'name'     => $referencia, 'reference'     => $referencia, 'account_id'     => $account_id, 'type'     => 'receipt', 'amount'     => $price)));
		$pagarvenda  = $models->execute_kw($this->odoo->db, $this->odoo->user_id, $this->odoo->password, 'sale.order', 'write', array(array($sale_id), array('state' => 'done')));

		self::response($voucher);
	}

	function response($client) {

		echo json_encode($client);
		//
	}

}
