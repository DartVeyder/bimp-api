<?php
    include_once('vendor/autoload.php');
    require_once('config.php');
    require_once('class/Bimp.php');
    
    $bimp = new Bimp(); 
    echo $bimp->getCompanyAccessToken();
      $bimp->makeOrder();
?>