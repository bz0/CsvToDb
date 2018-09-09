<?php
    //import
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    class CSVToDB{
        private $fileConfigList = [];
        private $execList = [];

        public function __construct(){
            $this->fileConfigList[] = new Csv();
            $this->fileConfigList[] = new Tsv();
        }

        /*
         * ファイル設定追加
         */
        public function setFileConfig(fileInterface $config){
            $this->fileConfigList[] = $config;
        }

        public function execute($filePathList){
            foreach($filePathList as $filePath){
                if($config = $this->fileConfigSelector($ext)){
                    $lexer = new Lexer($config);
                    $interpreter = new Interpreter();
    
                    $interpreter->addObserver(function(array $columns) use ($execList) {
                        foreach($this->execList as $exec){
                            $exec->exec($columns);
                        }
                    });
    
                    $lexer->parse('users.csv', $interpreter);
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



    $lexer = new Lexer($config);
    $interpreter = new Interpreter();
    //csvtodb

    
    //読み込むCSVファイル
    $lexer->parse('users.csv', $interpreter);