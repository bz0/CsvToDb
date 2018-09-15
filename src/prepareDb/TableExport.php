<?php
    namespace bz0\CSVToDB\PrepareDb;
    class TableExport implements PrepareDbInterface{
        private $pdo;
        private $table;

        public function __construct($pdo, $table){
            $this->pdo = $pdo;
            $this->table = $table;
        }

        public function execute($bkupPath){
            try{
                $this->pdo->beginTransaction();
                $sql  = "mysqldump -u " . USER . " -p" . PASSWORD . " -h " . HOST . " " . DBNAME . " " . $this->table . " > " . $bkupPath;
                $this->pdo->query($sql);
                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollback();
            }
        }
    }