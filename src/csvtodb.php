<?php
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\File\Csv;
    use bz0\CSVToDB\File\Tsv;
    use bz0\CSVToDB\prepareDb\PrepareDbInterface;
    use bz0\CSVToDB\Column\ColumnInterface;

    class CSVToDB{
        private $fileConfigList = [];
        private $prepareDbList  = [];
        private $columnExecList = [];

        /*
         * @param $prepareDbList
         * @param $columnExecList
         */
        public function __construct($pdo,
                                    $table,
                                    $logger){
            $this->fileConfigList[] = new Csv();
            $this->fileConfigList[] = new Tsv();

            $this->prepareDbList[]  = new TableCopy($pdo, $table);
            $this->prepareDbList[]  = new TableDelete($pdo, $table);
            
            $this->columnExecList   = $columnExecList;
            $this->logger           = $logger;
        }

        /*
         * ファイル設定追加
         * @param fileInterface $config
         */
        public function setFileConfig(fileInterface $config){
            $this->fileConfigList[] = $config;
        }

        /*
         * DB前処理
         */
        public function prepareDb($command){
            foreach($this->prepareDbList as $prepareDb){
                if ($prepareDb->accept($command)){
                    $prepareDb->execute();
                }
            }
        }

        /*
         * 実行
         * @param array $filePathList
         */
        public function execute($filePathList, $commands=[]){
            try{
                foreach($commands as $command){
                    $this->prepareDb();
                }
                
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
            foreach($this->fileConfigList as $fileConfig){
                if ($fileConfig->accept($ext)){
                    return $fileConfig->config();
                }
            }

            $message = '該当するファイル設定がありません';
            throw new Exception($message);
        }
    }