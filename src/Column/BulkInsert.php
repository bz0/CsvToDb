<?php
    namespace bz0\CSVToDB\Column;
    use bz0\CSVToDB\Column\ColumnExecuteInterface;
    class BulkInsert implements ColumnExecuteInterface{
        private $queueFactory;
        const MAXROW = 1000;
        private $table;
        private $queue;
        private $rowCount = 0;
        private $isHeader;

        public function __construct($pdo, $table, $column, $isHeader=false){
            $this->queueFactory = new \Yuyat_Bulky_QueueFactory(
                new \Yuyat_Bulky_DbAdapter_PdoMysqlAdapter($pdo),
                self::MAXROW
            );
            $this->table = $table;
            $this->isHeader = $isHeader;
            $this->setConfig($column);
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

        public function execute($row){
            $this->rowCount++;
            if ($this->isHeader()){
                $this->queue->insert($row);
            }
        }

        private function isHeader(){
            if($this->isHeader){
                if ($this->rowCount>1){
                    return true;
                }

                return false;
            }

            return true;
        } 
    }