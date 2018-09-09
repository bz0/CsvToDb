<?php
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
                $sql  = "DELETE FROM :table";
                $stmt = $this->pdo->prepare($sql);
                $params = array(
                    ':table' => $this->table
                );

                $res = $stmt->execute($params);
                $this->pdo->commit();

                return $res;
            }catch(PDOException $e){
                $this->pdo->rollback();
                return $e->getMessage();
            }
        }
    }