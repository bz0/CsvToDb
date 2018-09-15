# CsvToDb

ファイルの中身をDBにInsertする・Insertする前後の処理を簡単に行えます。

## できること

### １．読み込むファイルの形式を複数指定可

現状下記が利用可能です。

ファイルの拡張子でどのファイル形式かを判別するので、もし拡張子が間違っていた場合はファイルの読み込みができません。

- CSV(文字コード：SJIS)
- TSV(文字コード：SJIS)

### ２．事前処理 / 後処理

CSV等のファイルをDBにInsertする前の事前処理と、Insert後の処理を簡単に設定することができます。  
用意されている処理は下記です。

- テーブルの削除
- テーブルのコピー
- テーブルのエクスポート（SQLファイル）
- チャットワークへの通知

### ３．テーブルへのインサート（登録）

CSV等のファイルをテーブルにインサート（登録）することができます。  
バルクインサートで一括登録します。



## 実装

### 準備

#### PDO

```
$pdo = new \PDO(DSN, USER, PASSWORD);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる
```

#### ログ（monolog）

ログ名とログファイルのパスを指定して下さい。

```
use bz0\CSVToDB as CSVToDB;
$logName = "logTest";
$logPath = dirname(__FILE__) . "/log/" . date("YmdHis") . ".log";
$monolog = new CSVToDB\Monolog($logName, $logPath);
$logger  = $monolog->setConfig();
```

### 読み込むファイルの形式を指定

CSVとTSVを読み込む場合は、下記のように指定します。

```
$config = new CSVToDB\Config();
//注意：拡張子で形式判別するので違ってるとファイルを受け付けません
$config->setFileConfig(new CSVToDB\File\Csv()); //CSV
$config->setFileConfig(new CSVToDB\File\Tsv()); //TSV
```

### 事前処理 / 後処理の設定

#### テーブルコピー

下記を指定して下さい

- PDO
- バックアップ元のテーブル名
- バックアップ先のテーブル名

```
$config->setPrepareProcess(new CSVToDB\Process\TableCopy($pdo, "バックアップ元のテーブル名", "バックアップ先のテーブル名"));
```

#### テーブルバックアップ（SQLファイル）

下記を指定して下さい

- PDO
- バックアップ元のテーブル名
- バックアップ先のファイルパス

```
$config->setPrepareProcess(new CSVToDB\Process\TableExport($pdo, "バックアップ元のテーブル名", "バックアップ先のファイルパス"));
```

#### チャットワークへの通知

下記を指定して下さい

- トークン
- 通知する部屋番号
- 通知するメッセージ

```
$config->setPrepareProcess(new CSVToDB\Process\ChatworkMessageSend("トークン", "通知する部屋番号", "通知するメッセージ"));
```
