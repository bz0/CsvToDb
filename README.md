# CsvToDb

ファイルの中身をDBにInsertすること・Insertする前後の処理を簡単に行えます。

## できること

### １．読み込むファイルの形式を複数指定可

現状下記が利用可能です。

ファイルの拡張子でどのファイル形式かを判別するので、もし拡張子が間違っていた場合はファイルの読み込みができません。

- CSV(文字コード：SJIS)
- TSV(文字コード：SJIS)

### 事前処理 / 後処理

CSV等のファイルをDBにInsertする前の事前処理と、Insert後の処理を簡単に設定することができます。  
用意されている処理は下記です。

- テーブルの削除
- テーブルのコピー

### DB Insert

CSV等のファイルをDBにInsertすることができます。  
バルクインサートで一括登録します。
