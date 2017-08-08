<?php
/**
 * @ EvolutionScript
 * Created by NextPay.ir
 * author: Nextpay Company
 * ID: @nextpay
 * Date: 04/01/2017
 * Time: 5:45 PM
 * Website: NextPay.ir
 * Email: info@nextpay.ir
 * @copyright 2017
 * @package NextPay_Gateway
 * @version 1.0
 */
class Database {
	public $functions = array("connect" => "mysql_connect", "pconnect" => "mysql_pconnect", "select_db" => "mysql_select_db", "query" => "mysql_query", "result" => "mysql_result", "query_unbuffered" => "mysql_unbuffered_query", "fetch_row" => "mysql_fetch_row", "fetch_array" => "mysql_fetch_array", "fetch_field" => "mysql_fetch_field", "free_result" => "mysql_free_result", "data_seek" => "mysql_data_seek", "error" => "mysql_error", "errno" => "mysql_errno", "affected_rows" => "mysql_affected_rows", "num_rows" => "mysql_num_rows", "num_fields" => "mysql_num_fields", "field_name" => "mysql_field_name", "insert_id" => "mysql_insert_id", "escape_string" => "mysql_escape_string", "real_escape_string" => "mysql_real_escape_string", "close" => "mysql_close", "client_encoding" => "mysql_client_encoding", "mysql_set_charset" => "mysql_set_charset");
	public $registry;
	public $fetchtypes = array("DBARRAY_NUM" => MYSQL_NUM, "DBARRAY_ASSOC" => MYSQL_ASSOC, "DBARRAY_BOTH" => MYSQL_BOTH);
	public $database;

	public function connect($db_name, $db_server, $db_user, $db_passwd) {
		$this->tbl_prefix = "";
		$this->database = $db_name;
		$this->connection_master = $this->db_connect($db_name, $db_server, $db_user, $db_passwd);
		$this->select_db($this->database);
		$this->functions['query']('SET CHARACTER SET "utf8"');
	}

	public function db_connect($db_name, $db_server, $db_user, $db_passwd) {
		$link = @$this->functions['connect']($db_server, $db_user, $db_passwd);
		$this->functions['query']('SET CHARACTER SET "utf8"');

		if (!$link) {
			exit("<br /><br /><strong>Error MySQL DB Conection</strong><br>Please contact to site administrator.");
		}

		return $link;
	}

	public function select_db($database) {
		$this->database = $database;
		$this->functions['query']('SET CHARACTER SET "utf8"');

		if (!@$this->functions['select_db']($this->database, $this->connection_master)) {
			exit("<br /><br /><strong>Error MySQL DB Conection</strong><br>Please contact to site administrator.");
		}

	}

	public function close() {
		return @$this->functions['close']($this->connection_master);
	}

	public function query($sql, $buffered = true) {
		$this->sql = &$sql;
		return $this->execute_query($buffered, $this->connection_master);
	}

	public function &execute_query($buffered = true, &$link) {
		$this->connection_recent = &$link;

		$this->querycount++;

		if ($queryresult = $this->functions[($buffered ? "query" : "query_unbuffered")]($this->sql, $link)) {
			$this->sql = "";
			return $queryresult;
		}

		$this->sql = "";
	}

	public function &fetchRow($sql, $type = "DBARRAY_ASSOC") {
		$this->sql = &$sql;
		$queryresult = $this->execute_query(true, $this->connection_master);
		$returnarray = $this->fetch_array($queryresult, $type);
		$this->free_result($queryresult);
		return $returnarray;
	}

	public function fetch_array($queryresult, $type = "DBARRAY_ASSOC") {
		$result = @$this->functions['fetch_array']($queryresult, $this->fetchtypes[$type]);
		return $result;
	}

	public function fetchOne($sql) {
		$this->sql = &$sql;

		$queryresult = $this->execute_query(true, $this->connection_master);
		$returnresult = $this->result($queryresult);
		$this->free_result($queryresult);
		return $returnresult;
	}

	public function insert($tbl, $dataArray) {
		foreach ($dataArray as $k => $v) {
			$keys .= $k . ", ";
			$values .= "'" . $this->real_escape_string($v) . "', ";
		}

		$keys = substr($keys, 0, strlen($keys) - 2);
		$values = substr($values, 0, strlen($values) - 2);
		$sql = "INSERT INTO " . $tbl . "(" . $keys . ") VALUES(" . $values . ")";
		$exeq = $this->query($sql);
		return $exeq;
	}

	public function lastInsertId() {
		return @$this->functions['insert_id']($this->connection_master);
	}

	public function delete($tbl, $data = null) {
		if ($data != "") {
			$conditional = "WHERE " . $data;
		}

		$sql = ("DELETE FROM " . $tbl . " ") . $conditional;
		$this->query($sql);
	}

	public function update($tbl, $dataArray, $conditional = null) {
		foreach ($dataArray as $k => $v) {
			$updsql .= $k . "='" . $this->real_escape_string($v) . "', ";
		}

		$updsql = substr($updsql, 0, strlen($updsql) - 2);

		if ($conditional != "") {
			$updsql .= "WHERE " . $conditional;
		}

		$sql = "UPDATE " . $tbl . " SET " . $updsql;
		$this->query($sql);
	}

	public function result($queryresult) {
		$result = @$this->functions['result']($queryresult, $this->fetchtypes[$type]);
		return $result;
	}

	public function free_result($queryresult) {
		$this->sql = "";
		return @$this->functions['free_result']($queryresult);
	}

	public function escape_string($string) {
		if ($this->functions['escape_string'] == $this->functions['real_escape_string']) {
			return $this->functions['escape_string']($string, $this->connection_master);
		}

		return $this->functions['escape_string']($string);
	}

	public function real_escape_string($string) {
		$this->sql = "";
		return @$this->functions['real_escape_string']($string);
	}
}

class VData {
	public function details($license) {
/*		$postfields['license'] = $license;
		$postfields['domain'] = $_SERVER['SERVER_NAME'];
		$validurl = "http://50.28.45.97/v2/";
		sleep(2);

		if (function_exists("curl_exec")) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $validurl . "checklicense.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
		}
		else {
			$fp = fsockopen($validurl, 80, $errno, $errstr, 5);

			if ($fp) {
				$querystring = "";
				foreach ($postfields as $k => $v) {
					$querystring .= $k . "=" . urlencode($v) . "&";
				}

				$header = "POST " . $validurl . "checklicense.php HTTP/1.0\r\n";
				$header .= "Host: " . $validurl . "\r\n";
				$header .= "Content-type: application/x-www-form-urlencoded\r\n";
				$header .= "Content-length: " . @strlen($querystring) . "\r\n";
				$header .= "Connection: close\r\n\r\n";
				$header .= $status;
				$data = "";
				@stream_set_timeout($fp, 20);
				@fputs($fp, $header);
				$status = @socket_get_status($fp);

				while (!@feof($fp) && $status) {
					$data .= @fgets($fp, 1024);
					$status = @socket_get_status($fp);
				}

				@fclose($fp);
			}
		}

		$this->masterkey = $data;
*/
		$this->masterkey = "123456";
	}

	public function getinfo($data2 = null) {
/*		if ($data2 == null) {
			$lk = $this->masterkey;
		}
		else {
			$lk = $data2;
		}

		$lk = str_replace("\n", "", $lk);
		$lc = substr($lk, 0, strlen($lk) - 64);
		$pk = substr($lk, strlen($lk) - 32);
		$pk = strrev($pk);
		$msh = substr($lk, strlen($lk) - 64, 32);

		if ($msh == md5($lc . $pk)) {
			$lc = strrev($lc);
			$msh = substr($lc, 0, 32);
			$lc = substr($lc, 32);
			$lc = base64_decode($lc);
			$lkrs = unserialize($lc);
		}

		$this->info = $lkrs;
*/
		$this->info = array('ptc_memberships'=>'enable','referral_contest'=>'enable','revenue_shares'=>'enable','virool'=>'enable','points_contest'=>'enable','peanutlabs'=>'enable','facebook_connect'=>'enable','fixed_ptc_advertisements'=>'enable','clixgrid_clone'=>'enable','mochimedia'=>'enable','botsystem'=>'enable','headtail'=>'enable','live_banner'=>'enable','matomy'=>'enable','crowdflower'=>'enable','ptc_autosurf'=>'enable','superRewards'=>'enable','traffic_exchange'=>'enable');
	}

	public function validate($license) {
/*		$nextcheck = 172800 + $this->info['checkdate'];

		if (empty($this->info['checkdate']) || $nextcheck < time()) {
			$this->checkstatus = false;
			return null;
		}


		if ($this->info['status'] != "Active" || $this->info['license'] != $license) {
			$this->checkstatus = false;
			return null;
		}
*/
		$this->checkstatus = true;
		$this->info['clientname'] = "Mtimer <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_blank\" style=\"margin-top:10px;margin-bottom:5px;\"><input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"><input type=\"hidden\" name=\"hosted_button_id\" value=\"N3T56B5LHAGBS\"><input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif\" border=\"0\" name=\"submit\" alt=\"Donate to help this project!\" style=\"margin-bottom:-5px;\"><p style=\"display:inline;margin-left:10px;\"> to get an addon via email ;) ~</p></form>";
		$this->info['clientemail'] = "*** Send your script to mtimercms@hotmail.com and receive full decoded & nulled script in return! :D ***";
		$this->info['product'] = "EvolutionScript 5.0 Full Decoded & Nulled By Mtimer";
		$this->info['domain'] = $_SERVER["SERVER_NAME"];
		$this->info['checkdate'] = time();
		$this->info['support'] = time() + 199999999;
		$this->info['ptc_memberships'] = "enable";
	}

	public function response() {
		exit("Invalid License Key");
	}
}

class Registry {
	public function Registry() {

		define("CWD", ($getcwd = getcwd() ? $getcwd : "."));
		$config = array();
		include INCLUDES . "config.php";

		if (sizeof($config) == 0) {
			if (file_exists(INCLUDES . "config.php")) {
				exit( "<div style=\"border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:100%;padding:10px;color:#cc0000;\"><strong>Welcome to EvolutionScript 5.0 FULL DECODED && NULLED BY MTIMER!</strong><a></a><br>Before you can begin using EvolutionScript you need to perform the installation procedure. <a href=\"" . (file_exists( "install/install.php" ) ? "" : "../") . "install/install.php\" style=\"color:#000;\">Click here to begin ...</a><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_blank\" style=\"margin-top:10px;margin-bottom:5px;\"><input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"><input type=\"hidden\" name=\"hosted_button_id\" value=\"N3T56B5LHAGBS\"><input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif\" border=\"0\" name=\"submit\" alt=\"Donate to get an addon!\" style=\"margin-bottom:-5px;\"><p style=\"display:inline;margin-left:10px;\"> get an addon via email.</p></form></div>" );
			}
			else {
				exit("<br /><br /><strong>Configuration</strong>: includes/config.php does not exist. Please fill out the data in config.php.new and rename it to config.php");
			}
		}

		$this->config = $config;

		define("TABLE_PREFIX", trim($this->config['Database']['tableprefix']));
		define("COOKIE_PREFIX", (empty($this->config['Misc']['cookieprefix']) ? "ptc" : $this->config['Misc']['cookieprefix']) . "_");
	}
}

class Input_Cleaner {
	public $cleaned_vars = array();

	public function __construct() {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			foreach (array_keys($_POST) as $key) {

				if (isset($_GET[$key])) {
					$_GET[$key] = $_REQUEST[$key] = $_POST[$key];
					continue;
				}
			}
		}


		if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
			$this->stripslashes_deep($_REQUEST);
			$this->stripslashes_deep($_GET);
			$this->stripslashes_deep($_POST);
			$this->stripslashes_deep($_COOKIE);
		}


		if (function_exists("set_magic_quotes_runtime")) {
			@set_magic_quotes_runtime(0);
			@ini_set("magic_quotes_sybase", 0);
		}

		$this->frm = $_POST;
		$this->frmg = $_GET;
		$this->cookie = $_COOKIE;
		$this->req = $_REQUEST;

		if ($this->frm) {

			while (list($kk,$vv) = each($this->frm)) {
				if (is_array($vv)) {
					$vv_cleaned = $key;
				}
				else {
					$vv = trim($vv);
					$vv_cleaned = htmlspecialchars(trim($vv));
				}

				$this->p[$kk] = $vv;
				$this->pc[$kk] = $vv_cleaned;
			}
		}


		if ($this->frmg) {

			while (list($kk,$vv) = each($this->frmg)) {
				if (is_array($vv)) {
					$vv_cleaned = $key;
				}
				else {
					$vv = trim($vv);
					$vv_cleaned = htmlspecialchars(trim($vv));
				}

				$this->g[$kk] = $vv;
				$this->gc[$kk] = $vv_cleaned;
			}
		}


		if ($this->cookie) {

			while (list($kk, $vv) = each($this->cookie)) {
				if (is_array($vv)) {
				}
				else {
					$vv = trim($vv);
					$vv_cleaned = htmlspecialchars(trim($vv));
				}

				$this->c[$kk] = $vv;
				$this->cc[$kk] = $vv_cleaned;
			}
		}


		while (list($kk, $vv) = each($this->req)) {
			if (is_array($vv)) {
				$vv_cleaned = $key;
			}
			else {
				$vv = trim($vv);
				$vv_cleaned = htmlspecialchars(trim($vv));
			}

			$this->r[$kk] = $vv;
			$this->rc[$kk] = $vv_cleaned;
		}

	}

	public function stripslashes_deep($value, $depth = 0) {
		if (is_array($value)) {
			foreach ($value as $key => $val) {

				if (is_string($val)) {
					$value[$key] = stripslashes($val);
					continue;
				}


				if (is_array($val) && $depth < 10) {
					$this->stripslashes_deep($value[$key], $depth + 1);
					continue;
				}
			}
		}

	}
}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

?>