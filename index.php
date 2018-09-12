<?php
    require_once(dirname(__FILE__) . '/vendor/autoload.php');
    require_once(dirname(__FILE__) . 'config.php');
    
    $pdo = new \PDO(HOST, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる

    use bz0\CSVToDB\CSVToDB;
    use bz0\CSVToDB\prepareDb\TableCopy;

    $table = 'test';
    $prepareDbList[] = new TableCopy($pdo, $table);


    $csvtodb = new CSVToDB();

