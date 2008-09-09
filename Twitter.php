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
  const USER_URL = 'http://twitter.com/users/';
  const TWIT_URL = 'http://twitter.com/';
  const DMSG_URL = 'http://twitter.com/direct_messages/';

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


  private function addPostData($param, $value) {
    if($value != NULL) {
      $this->req->addPostData($param, $value);
    }
  }


  private function addGetData($param, $value) {
    if($value != NULL) {
      $this->getData[$param] = $value;
    }
  }


  private function get($url, $method, $auth = NULL) {
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


  private function post($url, $method, $auth = NULL) {
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


  public function publicTimeline() {
    return $this->get(self::STAT_URL, 'public_timeline');
  }


  public function friendsTimeline($since = NULL, $sinceId = NULL,
                                  $count = NULL, $page = NULL) {
    $this->addGetData('since', $since);
    $this->addGetData('since_id', $sinceId);
    if(count($count) <= 200) {
      $this->addGetData('count', $count);
    }
    $this->addGetData('page', $page);
    return $this->get(self::STAT_URL, 'friends_timeline', 1);
  }


  public function userTimeline($id = NULL, $count = NULL, $since = NULL,
                               $sinceId = NULL, $page = NULL) {
    $this->addGetData('id', $id);
    if(count($count) <= 200) {
      $this->addGetData('count', $count);
    }
    $this->addGetData('since', $since);
    $this->addGetData('since_id', $sinceId);
    $this->addGetData('page', $page);
    return $this->get(self::STAT_URL, 'user_timeline', 1);
  }


  public function show($id) {
    return $this->get(self::STAT_URL, 'show/'.$id);
  }


  public function update($status, $inReplyToStatusId = NULL) {
    if(strlen($status) > 160) die(self::E_MAX_CHAR_EXCEEDED);
    $this->addPostData('status', $status);
    $this->addPostData('in_reply_to_status_id', $inReplyToStatusId);
    return $this->post(self::STAT_URL, 'update', 1);
  }


  public function replies($page = NULL, $since = NULL, $sinceId = NULL) {
    $this->addGetData('page', $page);
    $this->addGetData('since', $since);
    $this->addGetData('since_id', $sinceId);
    return $this->get(self::STAT_URL, 'replies', 1);
  }


  public function destroy($id) {
    return $this->post(self::STAT_URL, 'destroy/'.$id, 1);
  }


  public function friends($id = NULL, $page = NULL,
                          $lite = NULL, $since = NULL) {
    $this->addGetData('id', $id);
    $this->addGetData('page', $page);
    $this->addGetData('lite', $lite);
    $this->addGetData('since', $since);
    return $this->get(self::STAT_URL, 'friends', 1);
  }


  public function followers($id = NULL, $page = NULL, $lite = NULL) {
    $this->addGetData('id', $id);
    $this->addGetData('page', $page);
    $this->addGetData('lite', $lite);
    return $this->get(self::STAT_URL, 'followers', 1);
  }


  public function featured() {
    return $this->get(self::STAT_URL, 'featured');
  }


  public function usersShow($id, $email = NULL) {
    $this->addGetData('id', $id);
    $this->addGetData('email', $email);
    return $this->get(self::USER_URL, 'show/'.$id, 1);
  }


  public function directMessages($since = NULL, $sinceId = NULL,
                                 $page = NULL) {
    $this->addGetData('since', $since);
    $this->addGetData('since_id', $sinceId);
    $this->addGetData('page', $page);
    return $this->get(self::TWIT_URL, 'direct_messages', 1);
  }


  public function sent($since = NULL, $sinceId = NULL, $page = NULL) {
    $this->addGetData('since', $since);
    $this->addGetData('since_id', $sinceId);
    $this->addGetData('page', $page);
    return $this->get(self::DMSG_URL, 'sent', 1);
  }


  public function _new($user, $text) {
    $this->addPostData('user', $user);
    $this->addPostData('text', $text);
    return $this->post(self::DMSG_URL, 'new', 1);
  }


  public function dmDestroy($id) {
    return $this->post(self::DMSG_URL, 'destroy/'.$id, 1);
  }


  public function __destruct() {
    $this->setBasicAuth(0);
    $this->getData = array();
  }
}

//debug
//$t = new Twitter('user', 'pass');
//$r = $t->replies(1);
//$r = $t->update('executes another PHP-Twitter test');
//print_r($r);

?>
