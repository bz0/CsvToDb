<?php
    namespace bz0\CSVToDB\Column;
    class BUlkInsert implements ImportInterface{
        private $queueFactory;
        const MAXROW = 1000;
        private $table;
        private $queue;

        public function __construct($pdo, $table, $column){
            $this->queueFactory = new Yuyat_Bulky_QueueFactory(
                new Yuyat_Bulky_DbAdapter_PdoMysqlAdapter($pdo),
                self::MAXROW
            );
            $this->table = $table;
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
            $this->queue->insert($row);
        }
    }