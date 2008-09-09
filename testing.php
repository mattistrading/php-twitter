<?php

require_once 'Twitter.php';
require_once 'Methods/Status.php';

$t = new Status('user', 'pass');
print_r($t->publicTimeline());

?>
