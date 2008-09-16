<?php

class Account extends Twitter {

  public function verifyCredentials() {
    return $this->get(self::ACC_URL, 'verify_credentials', 1);
  }


  public function updateLocation($location) {
    $this->addPostData('location', $location);
    return $this->post(self::ACC_URL, 'update_location', 1);
  }


  public function updateDeliveryDevice($device) {
    $this->addPostData('device', $device);
    return $this->post(self::ACC_URL, 'update_delivery_device', 1);
  }


  public function rateLimitStatus() {
    return $this->get(self::ACC_URL, 'rate_limit_status', 1);
  }

}

?>
