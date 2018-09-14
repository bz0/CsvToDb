<?php
    namespace bz0\CSVToDB;
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

        public function prepareDb(){
            $prepareDbList = $this->config->getPrepareDb();
            foreach($prepareDbList as $prepareDb){
                $prepareDb->execute();
            }
        }

        public function execute($filePathList){
            try{
                $this->prepareDb();
                
                foreach($filePathList as $filePath){
                    $finfo = pathinfo($filePath);
                    $this->logger->addInfo("ext:" . $finfo['extension'] . " path:" . $filePath);
                    if($config = $this->fileConfigSelector($finfo['extension'])){
                        $lexer = new Lexer($config);
                        $interpreter = new Interpreter();
    
                        $interpreter->addObserver(function(array $columns){
                            foreach($this->columnExecList as $columnExec){
                                $columnExec->execute($columns);
                            }
                        });
        
                        $lexer->parse($filePath, $interpreter);
                    }
                }
            }catch(Exception $e){
                $this->logger->addError($e->getMessage());
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