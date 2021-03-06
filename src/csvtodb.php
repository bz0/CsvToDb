<?php
    namespace bz0\CSVToDB;

    use Goodby\CSV\Import\Standard\Lexer;
    use Goodby\CSV\Import\Standard\Interpreter;

    use bz0\CSVToDB\File\Csv;
    use bz0\CSVToDB\File\Tsv;
    use bz0\CSVToDB\prepareDb\PrepareDbInterface;
    use bz0\CSVToDB\Column\ColumnInterface;

    class CSVToDB{
        private $config;
        private $logger;

        public function __construct($config, $logger){
            $this->config = $config;
            $this->logger = $logger;
        }

        public function prepareProcess(){
            if (!$this->config->getPrepareProcess()){
                return false;
            }

            foreach($this->config->getPrepareProcess() as $prepare){
                $prepare->execute();
            }
        }

        public function postProcess(){
            if (!$this->config->getPostProcess()){
                return false;
            }

            foreach($this->config->getPostProcess() as $postProcess){
                $postProcess->execute();
            }
        }

        private function initFileRowCount(){
            $this->config->getColumnExecute()->initRowCount();
        }

        private function fileRowCount(){
            return $this->config->getColumnExecute()->getRowCount();
        }

        private function setConfig(){
            $this->config->getColumnExecute()->setConfig();
        }

        public function execute($filePathList){
            try{
                $this->prepareProcess();
                
                foreach($filePathList as $filePath){
                    $finfo = pathinfo($filePath);
                    $this->logger->info("ext:" . $finfo['extension'] . " path:" . $filePath);
                    if($config = $this->fileConfigSelector($finfo['extension'])){
                        $lexer = new Lexer($config);
                        $interpreter = new Interpreter();

                        $interpreter->addObserver(function(array $columns){
                            $this->config->getColumnExecute()->execute($columns);
                        });

                        $lexer->parse($filePath, $interpreter);
                        $rowCount = $this->fileRowCount();
                        $this->logger->info("success row:" . $rowCount . " path:" . $filePath);
                        $this->initFileRowCount();
                    }
                }

                $this->postProcess();
            }catch(\Exception $e){
                $this->logger->error($e->getMessage());
            }
        }

        /*
         * ファイル設定選択
         * @param string $ext
         * @return false|$config->config()
         */
        public function fileConfigSelector($ext){
            $fileConfigs = $this->config->getFileConfig();
            foreach($fileConfigs as $fileConfig){
                if ($fileConfig->accept($ext)){
                    return $fileConfig->config();
                }
            }

            $message = '該当するファイル設定がありません';
            throw new Exception($message);
        }
    }