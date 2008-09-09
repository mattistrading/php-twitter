<?php

class User extends Twitter {

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

}

?>
