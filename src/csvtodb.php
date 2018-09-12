<?php
    //import
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    class CSVToDB{
        private $fileConfigList = [];
        private $prepareDbList  = [];
        private $columnExecList = [];

        /*
         * @param $prepareDbList
         * @param $columnExecList
         */
        public function __construct(PrepareDbInterface $prepareDbList, 
                                    ColumnInterface    $columnExecList, 
                                    $logger){
            $this->fileConfigList[] = new Csv();
            $this->fileConfigList[] = new Tsv();
            $this->prepareDbList    = $prepareDbList;
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
        public function prepareDb(){
            try{
                foreach($this->prepareDbList as $prepareDb){
                    $prepareDb->execute();
                }
            }catch(Exception $e){
                $this->logger->addError($e->getMessage());
            }
        }

        /*
         * 実行
         * @param array $filePathList
         */
        public function execute($filePathList){
            $this->prepareDb();

            foreach($filePathList as $filePath){
                $finfo = pathinfo($filePath);
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
            $this->logger->addError($message);
            throw new Exception($message);
        }
    }