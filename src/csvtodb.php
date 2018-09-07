<?php
    //import
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    $csv = new Csv();
    $tsv = new Tsv();

    $lexer = new Lexer($config);
    $interpreter = new Interpreter();
    //csvtodb
    $interpreter->addObserver(function(array $columns) use ($pdo) {
        //データに処理を入れるときはこのあたりに
        $stmt = $pdo->prepare('INSERT INTO users (id, name, email) VALUES (?, ?, ?)');
        $stmt->execute($columns);
    });
    
    //読み込むCSVファイル
    $lexer->parse('users.csv', $interpreter);