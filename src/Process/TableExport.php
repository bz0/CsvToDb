<?php
    namespace bz0\CSVToDB\Process;
    class TableExport implements ProcessInterface{
        private $pdo;
        private $table;
        private $bkupPath;

        public function __construct($table, $bkupPath){
            $this->table    = $table;
            $this->bkupPath = $bkupPath;
        }

        public function execute(){
            $command = "MYSQL_PWD=\"" . PASSWORD . "\" mysqldump -u "
            . USER . " -h " . HOST . " " . DBNAME . " " 
            . $this->table . " 2>&1 > " . $this->bkupPath;

            $res = exec($command, $out, $status);

            if ($status!==0){
                throw new \Exception(get_class($this) . ":" . $out[0]);
                return false;
            }

            return true;
        }

        public function setPDO(\PDO $pdo){
            $this->pdo = $pdo;
        }
    }