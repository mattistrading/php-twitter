<?php

class	Favorite extends Twitter {

  public function favorites($id = NULL, $page = NULL) {
    $this->addGetData('page', $page);
    if(!empty($id)) {
      return $this->get(self::FAV_URL, $id, 1);
    }
    else {
      return $this->get(self::TWIT_URL, 'favorites', 1);
    }
  }


  public function create($id) {
    return $this->post(self::FAV_URL, 'create/'.$id, 1);
  }


  public function destroy($id) {
    return $this->post(self::FAV_URL, 'destroy/'.$id, 1);
  }

}

?>
