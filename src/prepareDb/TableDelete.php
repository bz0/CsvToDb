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
                $sql  = "DELETE FROM " . $this->table;
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }
        }
    }