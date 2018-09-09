<?php
    namespace bz0\CSVToDB\prepareDb;
    class TableDelete implements InterfacePrepareDb{
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

                $res = $stmt->execute();
                $this->pdo->commit();

                return $res;
            }catch(PDOException $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }
        }
    }