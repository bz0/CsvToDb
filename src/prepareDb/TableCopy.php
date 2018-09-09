<?php
    class TableCopy implements InterfacePrepareDb{
        private $pdo;
        private $table;

        public function __construct($pdo, $table){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute($copyTable){
            try{
                //構造コピー
                $sql = "CREATE TABLE :copyTable LIKE :baseTable";
                $params = array(
                    ':basetable' => $this->table,
                    ':copytable' => $copyTable
                );

                $stmt = $this->pdo->prepare($sql);
                $res  = $stmt->execute($params);

                //データコピー
                if ($res){
                    $sql  = "INSERT INTO :copyTable SELECT * FROM :baseTable";
                    $stmt = $this->pdo->prepare($sql);
                    $res  = $stmt->execute($params);
                }

                return $res;
            }catch(PDOException $e){
                return $e->getMessage();
            }
        }
    }