<?php

class DirectMessage extends Twitter {

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


  public function destroy($id) {
    return $this->post(self::DMSG_URL, 'destroy/'.$id, 1);
  }

}

?>
