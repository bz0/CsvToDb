<?php
    namespace bz0\CSVToDB\prepareDb;
    class TableCopy implements PrepareDbInterface{
        private $pdo;
        private $table;

        public function __construct($pdo, $table){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute($params){
            try{
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

        private function copy($copyTable){
            $sql = "CREATE TABLE " . $this->pdo->quote($copyTable) 
                 . " LIKE " . $this->pdo->quote($this->table);
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }

        private function insert($copyTable){
            $sql  = "INSERT INTO " . $this->pdo->quote($copyTable)
                  . " SELECT * FROM " . $this->pdo->quote($this->table);
            $stmt = $this->pdo->prepare($sql);
            $res  = $stmt->execute($params);
        }

        public function accept($class){
            return strtolower(get_class($this)) === $class;
        }
    }