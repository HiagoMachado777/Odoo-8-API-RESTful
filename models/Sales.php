<?php

require_once ('../ripcord/ripcord.php');
require_once ('Odoo.php');

class Sales {

	var $odoo;

	public function __construct($odoo) {

		$this->odoo = $odoo;
	}

}
