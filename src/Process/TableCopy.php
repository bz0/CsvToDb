<?php
    namespace bz0\CSVToDB\Process;
    class TableCopy implements ProcessInterface{
        private $pdo;
        private $table;
        private $copyTable;

        public function __construct($pdo, $table, $copyTable){
            $this->pdo = $pdo;
            $this->table = $table;
            $this->copyTable = $copyTable;
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
            $sql = "CREATE TABLE `{$this->copyTable}` LIKE `{$this->table}`";
            $this->pdo->query($sql);
        }

        private function insert(){
            $sql  = "INSERT INTO `{$this->copyTable}`"
                  . " SELECT * FROM `{$this->table}`";
            $this->pdo->query($sql);
        }
    }