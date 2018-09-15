<?php
    namespace bz0\CSVToDB\PrepareDb;
    class TableExport implements PrepareDbInterface{
        private $pdo;
        private $table;
        private $bkupPath;

        public function __construct($pdo, $table, $bkupPath){
            $this->pdo      = $pdo;
            $this->table    = $table;
            $this->bkupPath = $bkupPath;
        }

        public function execute(){
            $command = "MYSQL_PWD=\"" . PASSWORD . "\" mysqldump -u "
                  . USER . " -h " . HOST . " " . DBNAME . " " 
                  . $this->table . " > " . $this->bkupPath;
            exec($command, $out, $status);

            if ($status!=0){
                throw new \Exception("TableExport: バックアップに失敗しました");
            }
        }
    }