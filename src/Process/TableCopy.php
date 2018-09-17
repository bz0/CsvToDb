<?php
    namespace bz0\CSVToDB\Process;
    class TableCopy implements ProcessInterface{
        private $pdo;
        private $table;
        private $copyTable;

        public function __construct($table, $copyTable){
            $this->table = $table;
            $this->copyTable = $copyTable;
        }

        public function execute(){
            try{
                $this->pdo->beginTransaction();
                $this->copy();
                $this->insert();
                $this->pdo->commit();
            }catch(\Exception $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }

            return true;
        }

        private function copy(){
            try{
                $sql = "CREATE TABLE `{$this->copyTable}` LIKE `{$this->table}`";
                $this->pdo->query($sql);
            }catch(\Exception $e){
                return $e->getMessage();
            }

            return true;
        }

        private function insert(){
            $sql  = "INSERT INTO `{$this->copyTable}`"
                  . " SELECT * FROM `{$this->table}`";
            $this->pdo->query($sql);
        }

        public function setPDO(\PDO $pdo){
            $this->pdo = $pdo;
        }
    }