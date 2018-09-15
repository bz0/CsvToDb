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

### ３．テーブルへのインサート（登録）

CSV等のファイルをテーブルにインサート（登録）することができます。  
バルクインサートで一括登録します。

## 実装

### 準備

#### PDO

```
//PDO
$pdo = new \PDO(DSN, USER, PASSWORD);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる
```
#### ログ（monolog）

```
//Monolog設定
$logName = "logTest";
$logPath = dirname(__FILE__) . "/log/" . date("YmdHis") . ".log";
$monolog = new CSVToDB\Monolog($logName, $logPath);
$logger  = $monolog->setConfig();
```
