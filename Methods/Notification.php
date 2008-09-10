<?php

class Notification extends Twitter {

  public function follow($id) {
    return $this->post(self::NOT_URL, 'follow/'.$id, 1);
  }

  public function leave($id) {
    return $this->post(self::NOT_URL, 'leave/'.$id, 1);
  }

}

?>
