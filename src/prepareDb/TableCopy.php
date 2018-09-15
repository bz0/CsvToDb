<?php
    namespace bz0\CSVToDB\PrepareDb;
    class TableCopy implements PrepareDbInterface{
        private $pdo;
        private $table;
        private $copyTable;

        public function __construct($pdo, $table, $copyTable){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute(){
            try{
                $this->pdo->beginTransaction();
                $this->copy();
                $this->insert();
                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollback();
            }
        }

        private function copy(){
            $sql = "CREATE TABLE " . $this->pdo->quote($this->copyTable) 
                 . " LIKE " . $this->pdo->quote($this->table);
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }

        private function insert(){
            $sql  = "INSERT INTO " . $this->pdo->quote($this->copyTable)
                  . " SELECT * FROM " . $this->pdo->quote($this->table);
            $stmt = $this->pdo->prepare($sql);
            $res  = $stmt->execute($params);
        }
    }