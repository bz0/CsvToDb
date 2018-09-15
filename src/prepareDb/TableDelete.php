<?php
    namespace bz0\CSVToDB\PrepareDb;
    class TableDelete implements PrepareDbInterface{
        private $pdo;
        private $table;

        public function __construct($pdo, $table){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute(){
            try{
                $this->pdo->beginTransaction();
                $sql  = "DELETE FROM `" . $this->table . "`";
                $this->pdo->query($sql);
                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }
        }
    }