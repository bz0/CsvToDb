<?php
    namespace bz0\CSVToDB\Column;
    use bz0\CSVToDB\Column\ColumnExecuteInterface;
    class BulkInsert implements ColumnExecuteInterface{
        private $queueFactory;
        const MAXROW = 1000;
        private $table;
        private $queue;
        private $rowCount = 0;
        private $headerRowCount = 1;
        private $isHeader = false;
        private $csvToDbMap;

        public function __construct($pdo, $table, $column, $isHeader=false){
            $this->csvToDbMap = array();
            if (!(array_values($column) === $column)) {
                $this->csvToDbMap = $column;
                $column = array_keys($column);
            }

            $this->queueFactory = new \Yuyat_Bulky_QueueFactory(
                new \Yuyat_Bulky_DbAdapter_PdoMysqlAdapter($pdo),
                self::MAXROW
            );
            $this->table = $table;
            $this->setConfig($column);
            $this->isHeader = $isHeader;
        }

        public function setIsHeader($isHeader){
            $this->isHeader = $isHeader;
        }

        public function initRowCount(){
            $this->rowCount = 0;
        }

        public function getRowCount(){
            return $this->rowCount;
        }

        public function execute($row){
            if ($this->permit()){
                $row = $this->formatter($row);
                $this->queue->insert($row);
            }
            
            $this->addRowCount();
        }

        private function formatter($row){
            if($this->csvToDbMap){
                foreach($this->csvToDbMap as $key => $address){
                    $tmp[] = $row[$address];
                }
                $row = $tmp;       
            }

            return $row;
        }

        private function setConfig($column){
            $this->queue = $this->queueFactory->createQueue(
                $this->table, 
                $column
            );

            $this->queue->on('error', function ($records) {
                return "Error!";
            });
        }

        private function permit(){
            if (!$this->isHeader){
                return true;
            }

            if ($this->rowCount >= $this->headerRowCount){
                return true;
            }

            return false;
        }

        private function addRowCount(){
            $this->rowCount++;
        }
    }