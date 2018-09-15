<?php
    require_once(dirname(__FILE__) . '/../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../DBConfig.php');
    
    //PDO
    $pdo = new \PDO(DSN, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる

    use bz0\CSVToDB as CSVToDB;

    //-------------------------------
    //Monolog設定
    //-------------------------------
    $logName = "logTest";
    $logPath = dirname(__FILE__) . "/log/" . date("YmdHis") . ".log";
    $monolog = new CSVToDB\Monolog($logName, $logPath);
    $logger  = $monolog->setConfig();

    $config = new CSVToDB\Config();

    //-------------------------------
    //読み込むファイル形式を指定
    //-------------------------------
    //注意：拡張子で形式判別するので違ってるとファイルを受け付けません
    $config->setFileConfig(new CSVToDB\File\Csv()); //CSV
    $config->setFileConfig(new CSVToDB\File\Tsv()); //TSV

    //-------------------------------
    //ファイルを読み込む前の事前処理
    //-------------------------------
    //注意：DBの事前処理で使用するテーブル名等は、決してユーザ側で自由に指定させないようにして下さい
    //SQLインジェクションが起きる可能性があります
    $table     = "test";
    $copyTable = "test_" . date("YmdHis");
    $bkupPath  = dirname(__FILE__) . "/bkup.sql";
    $config->setPrepareDb(new CSVToDB\prepareDb\TableCopy($pdo, $table, $copyTable));
    $config->setPrepareDb(new CSVToDB\prepareDb\TableExport($pdo, $table, $bkupPath));

    //-------------------------------
    //ファイルを１行ずつ読み込んだときに行う処理
    //-------------------------------
    //テーブル項目設定
    $column = [
        'sei',
        'mei',
        'yubin',
        'tel'
    ];
    //ヘッダ有無
    $isHeader = true;

    $config->setColumnExecute(new CSVToDB\Column\BulkInsert($pdo, $table, $column, $isHeader));
    $filePathList = [
        dirname(__FILE__) . "/file/sjis.csv",
        dirname(__FILE__) . "/file/sjis.tsv"
    ];

    $csvtodb = new CSVToDB\CSVToDB($config, $logger);
    $csvtodb->execute($filePathList);