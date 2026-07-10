<?php

use SQL\Join;

require "../src/autoload.php";

$exp = new Join('orders', 'customers', Join::LEFT)
  ->on('customer', 'id')
  ->join('contacts', function (Join $j) {
    $j->on('contact', 'id');
  });

echo "<pre>";
echo $exp;
echo "</pre>";
