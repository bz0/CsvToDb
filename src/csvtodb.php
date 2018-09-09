<?php
    //import
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    class CSVToDB{
        private $fileConfigList = [];
        private $execList = [];
        private $prepareDbList = [];

        public function __construct($prepareDbList){
            $this->fileConfigList[] = new Csv();
            $this->fileConfigList[] = new Tsv();
            $this->prepareDbList    = $prepareDbList;
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
                return $e->getMessage();
            }
        }

        /*
         * 実行
         * @param array $filePathList
         */
        public function execute($filePathList){
            $message = $this->prepareDb();
            if ($message!==""){
                return $message;
            }

            foreach($filePathList as $filePath){
                $finfo = pathinfo($filePath);
                if($config = $this->fileConfigSelector($finfo['extension'])){
                    $lexer = new Lexer($config);
                    $interpreter = new Interpreter();
    
                    $interpreter->addObserver(function(array $columns) {
                        foreach($this->execList as $exec){
                            $exec->exec($columns);
                        }
                    });
    
                    $lexer->parse($filePath, $interpreter);
                }else{
                    throw new Exception('該当するファイル設定がありません');
                }
            }
        }

        /*
         * ファイル設定選択
         * @param string $ext
         * @return false|$config->config()
         */
        public function fileConfigSelector($ext){
            foreach($this->configList as $config){
                if ($config->accept($ext)){
                    return $config->config();
                }
            }

            return false;
        }
    }