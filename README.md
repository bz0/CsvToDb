# CsvToDb

複数のCSV/TSVファイルをテーブル登録できます。
また、ありがちな前処理 / 後処理を簡単に行えます。

## できること

### １．読み込み可能ファイル

- CSV(文字コード：SJIS)
- TSV(文字コード：SJIS)

### ２．事前処理 / 後処理

ファイルをテーブルに登録する前の事前処理 / 後処理を簡単に行えます。  
用意されている処理は下記です。

- テーブルの削除
- テーブルのコピー
- テーブルのエクスポート（SQLファイル）
- チャットワークへの通知

### ３．テーブルへのインサート（登録）

ファイルをテーブルに登録することができます。  
バルクインサートで一括登録します。


## 実装

### サンプル

```
require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../DBConfig.php');

use bz0\CSVToDB as CSVToDB;
$client = new CSVToDB\Client();

//事前処理：テーブルコピー
$table     = "test";
$copyTable = "test_" . date("YmdHis");
$client->setPrepareProcess(new CSVToDB\Process\TableCopy($table, $copyTable));
//事前処理：バックアップ
$bkupPath  = dirname(__FILE__) . "/bkup.sql";
$client->setPrepareProcess(new CSVToDB\Process\TableExport($table, $bkupPath));
//事前処理：チャット通知
$client->setPrepareProcess(new CSVToDB\Process\ChatworkMessageSend("TOKEN", "ROOMID", "メッセージ"));
//後処理：テーブルコピー
$table     = "test";
$copyTable = "run_" . date("YmdHis");
$client->setPostProcess(new CSVToDB\Process\TableCopy($table, $copyTable));

//CSVとテーブルカラムの指定
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
```

### 必須）１．準備

#### DBConfig.phpを設定

DB情報を設定して下さい。

```
define('HOST', '');
define('DBNAME', '');
define('CHARSET', '');
define('DSN', '');
define('USER', '');
define('PASSWORD', '');
```

### 必須）クライアントのインスタンスを生成する

```
use bz0\CSVToDB as CSVToDB;
$client = new CSVToDB\Client();
```

### 任意）２．事前処理 / 後処理の設定

必要であれば下記設定できます。

#### テーブルコピー

下記を指定して下さい

- バックアップ元のテーブル名
- バックアップ先のテーブル名

```
$table     = "test";
$copyTable = "test_" . date("YmdHis");

//事前処理
$client->setPrepareProcess(new CSVToDB\Process\TableCopy($table, $copyTable));
//後処理
$client->setPostProcess(new CSVToDB\Process\TableCopy($table, $copyTable));
```

#### テーブルバックアップ（SQLファイル）

下記を指定して下さい

- バックアップ元のテーブル名
- バックアップ先のファイルパス

```
$table     = "test";
$bkupPath  = dirname(__FILE__) . "/bkup.sql";

//事前処理
$client->setPrepareProcess(new CSVToDB\Process\TableExport($table, $bkupPath));
//後処理
$client->setPostProcess(new CSVToDB\Process\TableExport($table, $bkupPath));
```

#### テーブル削除（TRUNCATE）

下記を指定して下さい

- 削除するテーブル名

```
//事前処理
$config->setPrepareProcess(new CSVToDB\Process\TableTruncate($table));
//後処理
$config->setPostProcess(new CSVToDB\Process\TableTruncate($table));
```

#### チャットワークへの通知

下記を指定して下さい

- トークン
- 通知する部屋番号
- 通知するメッセージ

```
//事前処理
$client->setPrepareProcess(new CSVToDB\Process\ChatworkMessageSend("TOKEN", "通知する部屋番号", "通知するメッセージ"));
//後処理
$client->setPostProcess(new CSVToDB\Process\ChatworkMessageSend("TOKEN", "通知する部屋番号", "通知するメッセージ"));
```

### 必須）３．テーブル登録設定

下記を指定して下さい

- テーブル名
- テーブルカラム（CSVでの並び順にする）
- ヘッダ有無の指定

```
$column = array(
    'sei',
    'mei',
    'yubin',
    'tel'
);
$client->setColumnExecute(new CSVToDB\Column\BulkInsert($table, $column, true));
```

### 必須）４．実行

読み込むファイルを指定して、実行します

```
$filePathList = [
    dirname(__FILE__) . "/file/sjis.csv",
    dirname(__FILE__) . "/file/sjis.tsv"
];

$client->execute($filePathList);
```
