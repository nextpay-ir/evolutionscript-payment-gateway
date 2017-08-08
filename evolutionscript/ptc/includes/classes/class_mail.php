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
class Mail {
	function mail() {
		global $db;
		global $cache;

		$mail_settings = $cache->get("mail_settings");

		if ($mail_settings == null) {
			$query = $db->query("SELECT * FROM mail_settings");

			while ($result = $db->fetch_array($query)) {
				$mail_settings[$result['field']] = $result['value'];
			}

			$cache->set("mail_settings", $mail_settings, 604800);
		}

		$this->mail_settings = $mail_settings;
		$this->mail = new PHPMailer();

		if ($this->mail_settings['email_type'] == "smtp") {
			$this->mail->IsSMTP();
			$this->mail->SMTPAuth = true;
			$this->mail->SMTPSecure = $this->mail_settings['smtp_ssl'];
			$this->mail->Host = $this->mail_settings['smtp_host'];
			$this->mail->Port = $this->mail_settings['smtp_port'];
			$this->mail->Username = $this->mail_settings['smtp_username'];
			$this->mail->Password = $this->mail_settings['smtp_password'];
		}

	}

	function setFrom($from_email = null, $name = null) {
		if (!empty($from_email)) {
			$this->from = $this->mail_settings['email_from_address'];
			$this->from_name = $this->mail_settings['email_from_name'];
		}
		else {
			$this->from = $from_email;

			if ($name == "") {
				$this->from_name = $this->from;
			}
			else {
				$this->from_name = $name;
			}
		}

		$this->mail->SetFrom($this->from, $this->from_name);
		$this->mail->AddReplyTo($this->from, $this->from_name);
	}

	function addTo($to_email, $to_name = null) {
		$this->to = $to_email;

		if ($to_name == "") {
			$this->to_name = $this->to;
		}
		else {
			$this->to_name = $to_name;
		}

		$this->mail->AddAddress($this->to, $this->to_name);
	}

	function setSubject($subject) {
		$this->mail->Subject = $subject;
	}

	function setBodyText($message) {
		$this->mail->ContentType = "text/plain";
		$this->mail->IsHTML(false);
		$this->mail->Body = $message;
	}

	function setBodyHtml($message) {
		$this->mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
		$this->mail->MsgHTML(stripslashes($message));
	}

	function send() {
		if (!$this->mail->Send()) {
		}

	}
}

require ROOTPATH . "includes/classes/class_refcron.php";
?>