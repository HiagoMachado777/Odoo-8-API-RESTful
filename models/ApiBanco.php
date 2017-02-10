<?php
require_once ('Odoo.php');
require_once ('Customer.php');
require_once ('../ripcord/ripcord.php');
require_once ('Sales.php');

class ApiBanco {

	private $mysqli;
	private $url;
	private $db;
	private $username;
	private $password;
	private $odoo;
	private $uid;
	private $user;
	private $order;

	function __construct() {
		//conexão com o banco da api
		$servidor     = 'localhost';
		$usuario      = 'root';
		$senha        = 'password';
		$banco        = 'database';
		$this->mysqli = new mysqli($servidor, $usuario, $senha, $banco);
		//conexão com o banco do odoo
		$this->url      = "url_odoo";
		$this->db       = "database_odoo";
		$this->username = "root_odoo";
		$this->password = "password_odoo";
		$this->odoo     = new Odoo($this->url, $this->db, $this->username, $this->password);
		$this->uid      = $this->odoo->login();
		$this->user     = new Customer($this->odoo);
		$this->order    = new Sales($this->odoo);
	}

	public function validarToken($token) {
		$sql   = "SELECT token FROM permissoes WHERE token = '".$token."'";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			return true;
		} else {
			return false;
		}
	}

	public function registrarPermissao($nome, $validade) {
		$tk = hash('sha256', time());
		while ($this->validarToken($tk) == true) {
			$tk = hash('sha256', time());
		}
		$sql   = "INSERT INTO permissoes (token, aplicacao, validade) VALUES ('".$tk."', '".$nome."', '".$validade."' )";
		$query = $this->mysqli->query($sql);
	}

	private function autorizarLogin($user, $pass) {
		$sql   = "SELECT login, senha FROM usuario WHERE login = '".$user."' AND senha = '".$pass."'";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			return true;
		} else {
			return false;
		}
	}

	public static function converterDataFormatoBanco($data) {
		$fragmento = explode('/', $data);
		$dia       = $fragmento[0];
		$mes       = $fragmento[1];
		$ano       = $fragmento[2];
		$val       = ApiBanco::validarData($dia, $mes, $ano);
		if ($val) {
			$data = $ano.'-'.$mes.'-'.$dia;
			return $data;
		} else {
			return false;
		}
	}

	private static function validarData($d, $m, $a) {
		if ($a >= date('Y') && $m > 0 && $m < 13 && $d > 0 && $d < 32) {
			if ($a > date('Y')) {
				return true;
			} else if ($m > date('m')) {
				return true;
			} else if ($d > date('d')) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function disponibilidadeApp($nome, $validade) {
		$sql   = "SELECT aplicacao FROM permissoes WHERE aplicacao = '".$nome."'";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			return false;
		} else {
			$this->registrarPermissao($nome, $validade);
			return true;
		}
	}

	public function disponibilidade($user, $pass) {
		$sql   = "SELECT login FROM usuario WHERE login = '".$user."'";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			return false;
		} else {
			$this->criarUser($user, $pass);
			return true;
		}
	}

	private function criarUser($nome, $pass) {
		$sql   = "INSERT INTO usuario (login, senha) VALUES ('".$nome."', '".$senha."')";
		$query = $this->mysqli->query($sql);
	}

	public function logar($user, $pass) {
		$autorizacao = $this->autorizarLogin($user, $pass);
		if ($autorizacao) {
			session_start();
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			return true;
		} else {
			return false;
		}

	}

	public function listarApiUsers() {
		$sql   = "SELECT login FROM usuario";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			$tabela = '<table class="table-wid">
						<tr>
							<th class="tabela-admin-header"><h3>Usuários:</h3><th/>
						</tr>';
			while ($dados = $query->fetch_array()) {
				$tabela = $tabela.'<tr class="tabela-admin-body"><td>'.$dados['login'].' <div class="pull-right"></div></td></tr>';
			}
			$tabela = $tabela.'</table>';
			return $tabela;

		} else {
			return 'Nenhum usuário encontrado.';
		}
	}

	public function listarApiApps() {
		$sql   = "SELECT id, aplicacao, token, YEAR(validade) as ano, MONTH(validade) as mes, DAY(validade) as dia FROM permissoes";
		$query = $this->mysqli->query($sql);
		if ($query->num_rows == true) {
			$tabela = '<table class="table-wid"><thead>
						<tr>
							<th class="tabela-admin-header"><h3>ID</h3></th>
							<th class="tabela-admin-header"><h3>Aplicação</h3></th>
							<th class="tabela-admin-header"><h3>Validade</h3></th>
							<th class="tabela-admin-header"><h3>Token</h3></th>
						</tr></thead><tbody>';
			while ($dados = $query->fetch_array()) {
				if ($dados['dia'] < 10) {
					$dados['dia'] = '0'.$dados['dia'];
				}
				if ($dados['mes'] < 10) {
					$dados['mes'] = '0'.$dados['mes'];
				}
				$tabela = $tabela.'<tr class="tabela-admin-body"><td>'.$dados['id'].'</td>';
				$tabela = $tabela.'<td>'.$dados['aplicacao'].'</td>';
				$tabela = $tabela.'<td>'.$dados['dia'].'/'.$dados['mes'].'/'.$dados['ano'].'</td>';
				$tabela = $tabela.'<td>'.$dados['token'].'</td></tr>';
			}
			$tabela = $tabela.'</tbody></table>';
			return $tabela;

		} else {
			return 'Nenhuma aplicação encontrada.';
		}
	}

	public function editaSale($reference) {
		$this->order->editSaleOrder($reference);

	}

	public function enviarOdoo($name, $email, $profissao, $tel, $nasc, $endereco, $tipo, $ref) {
		$profissao = self::definirProfissao($profissao);
		$this->user->addCustomer($name, $email, $profissao, $tel, $nasc, $endereco, $tipo, $ref);
	}
	public function pagarFatura($referencia) {
		$this->user->pagarFatura($referencia);
	}

	private static function definirProfissao($idprofissao) {

		if ($idprofissao > 0 && $idprofissao < 21) {

			switch ($idprofissao) {

				case 1:

					$profissao = 'Desembargador(a)';
					break;

				case 2:

					$profissao = 'Juíz(a) de Direito';
					break;

				case 3:

					$profissao = 'Promotor(a) de Justiça';
					break;

				case 4:

					$profissao = 'Advogado(a)';
					break;

				case 5:

					$profissao = 'Professor(a)';
					break;
				case 6:

					$profissao = 'Estudante';
					break;
				case 7:

					$profissao = 'Mestre(a) em Direito';
					break;
				case 8:

					$profissao = 'Defensor(a) Público';
					break;
				case 9:

					$profissao = 'Procurador(a) de Justiça';
					break;
				case 10:

					$profissao = 'Estagiário(a)';
					break;
				case 11:

					$profissao = 'Psicólogo(a)';
					break;
				case 12:

					$profissao = 'Outros';
					break;
				case 13:

					$profissao = 'Não consta';
					break;
				case 14:

					$profissao = 'Advogado(a) e Professor(a) 	';
					break;
				case 15:

					$profissao = 'Ministro(a)';
					break;
				case 16:

					$profissao = 'Oficial de Registro Civil';
					break;
				case 17:

					$profissao = 'Magistrado';
					break;
				case 18:

					$profissao = 'Escrivão(ã)';
					break;
				case 19:

					$profissao = 'Psicanalista';
					break;
				case 20:

					$profissao = 'Oficial de Justiça';
					break;
			}return $profissao;

		} else {
			return 'Indefinido';
		}
	}
}
