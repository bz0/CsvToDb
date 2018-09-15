<?php
    ini_set("display_errors", 1);
    error_reporting(-1);

    require_once(dirname(__FILE__) . '/../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../DBConfig.php');
    
    $pdo = new \PDO(HOST, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる

    use bz0\CSVToDB as CSVToDB;

    $config = new CSVToDB\Config();
    //読み込むファイル形式を指定（拡張子で形式判別するので違ってると受け付けません）
    $config->setFileConfig(new CSVToDB\File\Csv());
    $config->setFileConfig(new CSVToDB\File\Tsv());

    //ファイルを読み込む前の事前処理
    $table = "test";
    $copyTable = "test_" . date("YmdHis");
    $config->setPrepareDb(new CSVToDB\prepareDb\TableCopy($pdo, $table, $copyTable));

    $column = [
        'sei',
        'mei',
        'yubin',
        'tel'
    ];

    //ファイルを１行ずつ読み込んだときに行う処理
    $config->setColumnExecute(new CSVToDB\Column\BulkInsert($pdo, $table, $column));

    $filePathList = [
        dirname(__FILE__) . "/file/sjis.csv",
        dirname(__FILE__) . "/file/sjis.tsv"
    ];

    $csvtodb = new CSVToDB\CSVToDB($config, $logger);
    $csvtodb->execute($filePathList);