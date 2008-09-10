<?php

class Friendship extends Twitter {

  public function create($id) {
    return $this->post(self::FRND_URL, 'create/'.$i, 1);
  }


  public function destroy($id) {
    return $this->post(self::FRND_URL, 'destroy/'.$id, 1);
  }


  public function exists($userA, $userB) {
    $this->addGetData('user_a', $userA);
    $this->addGetData('user_b', $userB);
    return $this->get(self::FRND_URL, 'exists', 1);
  }
}

?>
