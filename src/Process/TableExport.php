<?php
    namespace bz0\CSVToDB\Process;
    class TableExport implements ProcessInterface{
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
                throw new \Exception(get_class($this) . ": バックアップに失敗しました");
            }
        }
    }