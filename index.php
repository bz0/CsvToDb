<?php
    require_once(dirname(__FILE__) . '/vendor/autoload.php');
    require_once(dirname(__FILE__) . 'config.php');
    
    $pdo = new PDO(HOST, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
