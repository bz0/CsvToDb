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
                $sql  = "DELETE FROM :table";
                $stmt = $this->pdo->prepare($sql);
                $params = array(
                    ':table' => $this->table
                );

                return $stmt->execute($params);
            }catch(PDOException $e){
                return $e->getMessage();
            }
        }
    }