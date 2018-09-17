<?php
    namespace bz0\CSVToDB\Process;
    class TableTruncate implements ProcessInterface{
        private $pdo;
        private $table;

        public function __construct($table){
            $this->table = $table;
        }

        public function execute(){
            $sql  = "TRUNCATE TABLE `" . $this->table . "`";
            $this->pdo->query($sql);
        }

        public function setPDO(\PDO $pdo){
            $this->pdo = $pdo;
        }
    }