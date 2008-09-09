<?php

class Status extends Twitter {

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

}

?>
