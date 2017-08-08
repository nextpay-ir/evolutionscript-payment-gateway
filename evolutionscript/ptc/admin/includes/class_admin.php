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
class Admin {
	function Admin($username, $password) {
		$this->password = $password;
		$this->username = $username;
	}

	function verify() {
		global $db;

		$this->username = $db->real_escape_string($this->username);
		$v = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE username='" . $this->username . "' AND password='" . $this->password . "'");

		if ($v == 0) {
			return false;
		}

		$admin = $db->fetchRow("SELECT * FROM admin WHERE username='" . $this->username . "'");
		$this->id = $admin['id'];
		$this->username = $admin['username'];
		$this->password = $admin['password'];
		$this->login = $admin['login'];
		$this->last_login = $admin['last_login'];
		$this->notes = $admin['notes'];
		$this->email = $admin['email'];
		$this->permissions = unserialize($admin['permissions']);
		$this->permissions = (!is_array($this->permissions) ? array() : $this->permissions);
		$this->status = $admin['status'];
		$this->signature = $admin['signature'];
		$this->ip = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
		$this->browser = (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT']);
		$this->pin = $admin['pin'];
		$this->last_ip = $admin['last_ip'];
		$this->protection = $admin['protection'];
		$this->check_code = $admin['check_code'];
		return true;
	}

	function getSession() {
		$sesid = md5($this->ip . ":" . $this->browser . ":" . $this->login . ":" . $this->pin);
		return $sesid;
	}

	function getCurrentSession() {
		$sesid = md5($this->ip . ":" . $this->browser . ":" . $this->last_login . ":" . $this->pin);
		return $sesid;
	}

	function startSession() {
		setcookie("c_uid", $this->username);
		setcookie("c_pwd", $this->password);
		setcookie("c_tkn", $this->getSession());
	}

	function deleteSession() {
		setcookie("c_uid", "", TIMENOW - 35000);
		setcookie("c_pwd", "", TIMENOW - 35000);
		setcookie("c_tkn", "", TIMENOW - 35000);
	}

	function checkToken($token) {
		if ($this->getCurrentSession() != $token) {
			return false;
		}

		return true;
	}

	function getId() {
		return $this->id;
	}

	function getUsername() {
		return $this->username;
	}

	function getPassword() {
		return $this->password;
	}

	function getLogin() {
		return $this->login;
	}

	function getLastlogin() {
		return $this->last_login;
	}

	function getNotes() {
		return $this->notes;
	}

	function getEmail() {
		return $this->email;
	}

	function getStatus() {
		return $this->status;
	}

	function getSignature() {
		return $this->signature;
	}

	function getIp() {
		return $this->ip;
	}

	function getBrowser() {
		return $this->browser;
	}

	function getPin() {
		return $this->pin;
	}

	function getLastIp() {
		return $this->last_ip;
	}

	function getProtection() {
		return $this->protection;
	}

	function getCheckcode() {
		return $this->check_code;
	}
}

?>