<?php

class Block extends Twitter {

  public function create($id) {
    return $this->post(self::BLK_URL, 'create/'.$id, 1);
  }

  public function destroy($id) {
    return $this->post(self::BLK_URL, 'destroy/'.$id, 1);
  }

}

?>
