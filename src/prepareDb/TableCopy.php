<?php
    namespace bz0\CSVToDB\prepareDb;
    class TableCopy implements InterfacePrepareDb{
        private $pdo;
        private $table;

        public function __construct($pdo, $table){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute($copyTable){
            try{
                $params = array(
                    ':basetable' => $this->table,
                    ':copytable' => $copyTable
                );

                $this->pdo->beginTransaction();
                $this->copy($params);
                if ($res){
                    $this->insert($params);
                }
                $this->pdo->commit();

                return $res;
            }catch(PDOException $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }
        }

        private function copy($params){
            $sql = "CREATE TABLE :copyTable LIKE :baseTable";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }

        private function insert($params){
            $sql  = "INSERT INTO :copyTable SELECT * FROM :baseTable";
            $stmt = $this->pdo->prepare($sql);
            $res  = $stmt->execute($params);
        }
    }