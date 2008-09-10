<?php

class Help extends Twitter {

  public function test() {
    return $this->get(self::HELP_URL, 'test');
  }

  public function downtimeSchedule() {
    return $this->get(self::HELP_URL, 'downtime_schedule');
  }

}

?>
