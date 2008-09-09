<?php
  
require_once 'HTTP/Request.php';
require_once 'XML/Unserializer.php';


class Twitter {

  private $user;
  private $pass;

  private $req;
  private $us;

  private $getData = array();

  const STAT_URL = 'http://twitter.com/statuses/';
  const USER_URL = 'http://twitter.com/users/show/';

  const E_HTTP_SEND_FAILED = 'Error sending HTTP get/post request.';
  const E_MAX_CHAR_EXCEEDED = 'Maximum of 160 characters limit exceeded.';


  public function __construct($user = '', $pass = '') {
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


  private function addPostData($param, $value) {
    $this->req->addPostData($param, $value);
  }


  private function addGetData($param, $value) {
    $this->getData[$param] = $value;
  }


  private function get($url, $method) {
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


  private function post($url, $method) {
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


  public function publicTimeline() {
    return $this->get(self::STAT_URL, 'public_timeline');
  }


  public function friendsTimeline($since = '', $sinceId = '',
                                  $count = '', $page = '') {
    if(!empty($since)) {
      $this->addGetData('since', $since);
    }
    if(!empty($sinceId)) {
      $this->addGetData('since_id', $sinceId);
    }
    if(!empty($count) && $count <= 200) {
      $this->addGetData('count', $count);
    }
    if(!empty($page)) {
      $this->addGetData('page', $page);
    }
    $this->setBasicAuth(1);
    return $this->get(self::STAT_URL, 'friends_timeline');
  }


  public function userTimeline($id = '', $count = '', $since = ''
                               $sinceId = '', $page = '') {
    if(!empty($id)) {
      $this->addGetData('id', $id);
    }
    if(!empty($count) && $count <= 200) {
      $this->addGetData('count', $count);
    }
    if(!empty($since)) {
      $this->addGetData('since', $since);
    }
    if(!empty($sinceId)) {
      $this->addGetData('since_id', $sinceId);
    }
    if(!empty($page)) {
      $this->addGetData('page', $page);
    }
    $this->setBasicAuth(1);
    return $this->get(self::STAT_URL, 'user_timeline');
  }


  public function show($id) {
    return $this->get(self::STAT_URL, 'show/'.$id);
  }


  public function update($status, $inReplyToStatusId = '') {
    if(strlen($status) > 160) die(self::E_MAX_CHAR_EXCEEDED);
    $this->addPostData('status', $status);
    if(!empty($inReplyToStatusId)) {
      $this->addPostData('in_reply_to_status_id', $inReplyToStatusId);
    }
    $this->setBasicAuth(1);
    return $this->post(self::STAT_URL, 'update');
  }


  public function replies($page = '', $since = '', $sinceId = '') {
    if(!empty($page)) {
      $this->addGetData('page', $page);
    }
    if(!empty($since)) {
      $this->addGetData('since', $since);
    }
    if(!empty($sinceId)) {
      $this->addGetData('since_id', $sinceId);
    }
    $this->setBasicAuth(1);
    return $this->get(self::STAT_URL, 'replies');
  }


  public function destroy($id) {
    $this->setBasicAuth(1);
    return $this->post(self::STAT_URL, 'destroy/'.$id);  
  }


  public function friends($id = '', $page = '', $lite = '', $since = '') {
    if(!empty($id)) {
      $this->addGetData('id', $id);
    }
    if(!empty($page)) {
      $this->addGetData('page', $page);
    }
    if(!empty($lite)) {
      $this->addGetData('lite', $lite);
    }
    if(!empty($since)) {
      $this->addGetData('since', $since);
    }
    $this->setBasicAuth(1);
    return $this->get(self::STAT_URL, 'friends');
  }


  public function __destruct() {
    $this->setBasicAuth(0);
    $this->getData = array();
  }
}

//debug
//$t = new Twitter('user', 'pass');
//$r = $t->replies(1);
//$r = $t->update('thinks twitter is waaaay cooler than plurk');
//print_r($r);

?>
