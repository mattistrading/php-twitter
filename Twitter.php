<?php

/**
* Class Twitter
*
* PHP Twitter is a PHP5-strict library to Twitter API
* as documented in http://apiwiki.twitter.com/REST+API+Documentation.
* All methods and parameters are supported by this c
lass.
*
* This class provides a basic class for connecting, authenticating,
* and doing post and get requests. To use methods described in twitter
* apiwiki, include classes in Methods/ directory.
*
* This class depends on PEAR HTTP_Request and XML_Serializer.
* Please make sure that these libraries are installed on your
* server.
*
* @package	Twitter
* @file		Twitter.php
* @author	Galuh Utama <galuh.utama@gwutama.de>
* @version 	160808
*
*/

  
require_once 'HTTP/Request.php';
require_once 'XML/Unserializer.php';


class Twitter {

  protected $user;
  protected $pass;

  protected $req;
  protected $us;

  protected $getData = array();

  const TWIT_URL = 'http://twitter.com/';
  const STAT_URL = 'http://twitter.com/statuses/';
  const USER_URL = 'http://twitter.com/users/';
  const DMSG_URL = 'http://twitter.com/direct_messages/';
  const FRND_URL = 'http://twitter.com/friendships/';
  const ACC_URL = 'http://twitter.com/account/';
  const FAV_URL = 'http://twitter.com/favorites/';
  const NOT_URL = 'http://twitter.com/notifications/';
  const BLK_URL = 'http://twitter.com/blocks/';
  const HELP_URL = 'http://twitter.com/help/';

  const E_HTTP_SEND_FAILED = 'Error sending HTTP get/post request.';
  const E_MAX_CHAR_EXCEEDED = 'Maximum of 160 characters limit exceeded.';


  public function __construct($user = NULL, $pass = NULL) {
    $this->user = $user;
    $this->pass = $pass;

    //Initializes PEAR HTTP_REQUEST object
    $this->req = new HTTP_Request();

    //Initializes PEAR XML_Unserializer object
    $this->us = new XML_Unserializer(); 
  }


  private function setURL($url) {
    $this->req->setURL($url);    
  }


  private function request() {
    if (!PEAR::isError($this->req->sendRequest())) {
      return $this->req->getResponseBody();
    }
    else {
      die(self::E_HTTP_SEND_FAILED);
    }
  }


  private function setHttpMethod($httpMethod) {
    if($httpMethod == 'get') {
      $this->req->setMethod(HTTP_REQUEST_METHOD_GET);
    }
    if($httpMethod == 'post') {
      $this->req->setMethod(HTTP_REQUEST_METHOD_POST);
    }
  }


  private function setBasicAuth($auth) {
    if($auth) {
      $this->req->setBasicAuth($this->user, $this->pass);
    }
    else {
      //empty user and password
      $this->req->setBasicAuth('', '');
    }
  }


  protected function addPostData($param, $value) {
    if($value != NULL) {
      $this->req->addPostData($param, $value);
    }
  }


  protected function addGetData($param, $value) {
    if($value != NULL) {
      $this->getData[$param] = $value;
    }
  }


  protected function get($url, $method, $auth = NULL) {
    if($auth) {
      $this->setBasicAuth(1);
    }
    else {
       $this->setBasicAuth(0);
    }
    //expand $getData array
    if(count($this->getData) > 0) {
      $getData = '?';
      foreach($this->getData as $key => $value) {
        $getData .= $key.'='.$value.'&';
      }
    }
    $this->setHttpMethod('get');
    $this->setURL($url.$method.'.xml'.$getData);
    $r = $this->request();
    self::__destruct();
    return $this->parse($r);
  }


  protected function post($url, $method, $auth = NULL) {
    if($auth) {
      $this->setBasicAuth(1);
    }
    else {
       $this->setBasicAuth(0);
    }
    $this->setHttpMethod('post');
    $this->setURL($url.$method.'.xml');
    $r = $this->request();
    self::__destruct();
    return $this->parse($r);
  }


  private function parse($xmlSource) {
    $this->us->unserialize($xmlSource);
    return $this->us->getUnserializedData();
  }


  public function __destruct() {
    $this->setBasicAuth(0);
    $this->getData = array();
  }
}

?>
