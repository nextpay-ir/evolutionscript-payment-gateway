<?php

class Authentication {
  public $accountId;
  public $apiName;
  public $securityWord;
  public $version = 2;

  function __construct($accountId, $apiName, $securityWord) {
    $this->accountId = $accountId;
    $this->apiName = $apiName;
    $this->securityWord = $securityWord;
  }

  function hex2bin($h) {
    if (!is_string($h)) {
      
      return null;
    }
    
    $r = "";
    
    for ($a = 0;$a < strlen($h);$a += 2) {
      $r.= chr(hexdec($h[$a] . $h[$a + 1]));
    }
    
    
    return $r;
  }

  public function createAuthToken($id, $extras) {
    $datePart = gmdate("Ymd");
    $timePart = gmdate("H");
    $datePart = $datePart . ":" . $timePart;
    $authString = $this->securityWord . ":";

    if ($this->version == 2) {
      $authString.= $id . ":";
      
      if ($extras) {
        
        foreach ($extras as $item) {
          $authString.= $item . ":";
        }
      }
    }

    $authString .= $datePart;
    $sha256 = bin2hex($this->hex2bin(hash("sha256", $authString)));
    return strtoupper($sha256);
  }
}

interface IApiService {
  function balance($currency);

  function accountName($accountToRetrieve);
}

class ApiAgentFactory {
  const XML = "xml";
  const NVP = "nvp";
  const JSON = "json";
  const SOAP = "soap";

  public static function createApiAgent($type, Authentication $auth) {
    switch ($type) {
      case "xml":
        return new XmlApiAgent($auth);
        break;
      case "nvp":
        return new NvpApiAgent($auth);
        break;
      case "json":
        return new JsonApiAgent($auth);
        break;
      case "soap":
        return new SoapApiAgent($auth);
        break;
      default:
        die("Error");
        break;
    }
  }
}

class HistoryItem {
  public $Batch;
  public $Date;
  public $Amount;
  public $Fee;
  public $Balance;
  public $Currency;
  public $Payer;
  public $PayerName;
  public $Payee;
  public $PayeeName;
  public $Memo;
  public $Private;
  public $Reference;
  public $Source;
}