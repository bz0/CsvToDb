<?php
    ini_set("display_errors", 1);
    error_reporting(-1);

    require_once(dirname(__FILE__) . '/../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../DBConfig.php');

    use bz0\CSVToDB as CSVToDB;
    $client = new CSVToDB\Client();
    
    $table     = "test";
    $copyTable = "test_" . date("YmdHis");
    $client->setPrepareProcess(new CSVToDB\Process\TableCopy($table, $copyTable));

    $bkupPath  = dirname(__FILE__) . "/bkup.sql";
    $client->setPrepareProcess(new CSVToDB\Process\TableExport($table, $bkupPath));
    $client->setPrepareProcess(new CSVToDB\Process\ChatworkMessageSend("ef35192f2f60e27b851e47f8706c0ac6", 1613708, "Hello world!!"));

    $table     = "test";
    $copyTable = "run_" . date("YmdHis");
    $client->setPostProcess(new CSVToDB\Process\TableCopy($table, $copyTable));

    $column = array(
        'sei',
        'mei',
        'yubin',
        'tel'
    );
    $client->setColumnExecute(new CSVToDB\Column\BulkInsert($table, $column, true));
    $filePathList = [
        dirname(__FILE__) . "/file/sjis.csv",
        dirname(__FILE__) . "/file/sjis.tsv"
    ];

    $client->execute($filePathList);