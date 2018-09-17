<?php
    ini_set("display_errors", 1);
    error_reporting(-1);

    require_once(dirname(__FILE__) . '/../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../DBConfig.php');
    
    use bz0\CSVToDB as CSVToDB;
    $client = new CSVToDB\Client();

    $table = "test";
    $filePathList = [
        dirname(__FILE__) . "/file/sjis.csv",
        dirname(__FILE__) . "/file/sjis.tsv"
    ];

    $column = array(
        'sei'   => 0,
        'yubin' => 2
    );

    $client->setColumnExecute(new CSVToDB\Column\BulkInsert($table, $column, true));
    $filePathList = [
        dirname(__FILE__) . "/file/sjis.csv",
        dirname(__FILE__) . "/file/sjis.tsv"
    ];

    $client->execute($filePathList);