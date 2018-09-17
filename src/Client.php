<?php
    namespace bz0\CSVToDB;

    use bz0\CSVToDB\Config;
    use bz0\CSVToDB\Monolog;
    use bz0\CSVToDB\File\Csv;
    use bz0\CSVToDB\File\Tsv;

    use bz0\CSVToDB\File\FileInterface;
    use bz0\CSVToDB\Column\ColumnExecuteInterface;
    use bz0\CSVToDB\Process\ProcessInterface;

    class Client{
        private $pdo;
        private $logger;
        private $config;

        public function __construct(){
            $this->defaultPDO();
            $this->defaultMonolog();
            $this->defaultFIleConfig();
        }

        private function defaultPDO(){
            $this->pdo = new \PDO(DSN, USER, PASSWORD);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); //静的プレースホルダを指定
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //エラー発生時に例外を投げる
        }

        private function defaultMonolog(){
            $logName = "log";
            $logPath = dirname(__DIR__) . "/" . date("YmdHis") . ".log";
            $monolog = new Monolog($logName, $logPath);
            $this->logger  = $monolog->setConfig();
        }

        private function defaultFileConfig(){
            $this->config = new Config();
            $this->config->setFileConfig(new Csv());
            $this->config->setFileConfig(new Tsv());
        }

        public function setColumnExecute(ColumnExecuteInterface $exec){
            $exec->setConfig($this->pdo);
            $this->config->setColumnExecute($exec);
        }

        public function setPrepareProcess(ProcessInterface $process){
            if (method_exists($process, 'setPDO')){
                $process->setPDO($this->pdo);
            }
            $this->config->setPrepareProcess($process);
        }

        public function setPostProcess(ProcessInterface $process){
            if (method_exists($process, 'setPDO')){
                $process->setPDO($this->pdo);
            }
            $this->config->setPostProcess($process);
        }

        public function setFileConfig(FileInterface $file){
            $this->config->setFileConfig($file);
        }

        public function setPDO(\PDO $pdo){
            $this->pdo = $pdo;
        }

        public function setMonolog(\LoggerInterface $logger){
            $this->logger = $logger;
        }

        public function execute($filePathList){
            $csvtodb = new CSVToDB($this->config, $this->logger);
            $csvtodb->execute($filePathList);
        }
    }