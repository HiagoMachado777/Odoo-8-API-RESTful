<?php

require_once ('../ripcord/ripcord.php');

class Odoo {

	var $username;
	var $password;
	var $common;
	var $uid;
	var $url;
	var $db;

	function Odoo($url, $db, $username, $password) {

		$this->url      = $url;
		$this->db       = $db;
		$this->username = $username;
		$this->password = $password;
	}

	function login() {

		$this->common = ripcord::client($this->url."/xmlrpc/2/common");
		$this->common->version();

		$this->user_id = $this->common->authenticate($this->db, $this->username, $this->password, array());

		return $this->user_id;
	}

}
