<?php
    //import
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    class CSVToDB{
        private $configList = [];

        public function __construct(){
            $this->configList[] = new Csv();
            $this->configList[] = new Tsv();
        }

        public function setConfig(fileInterface $config){
            $this->configList[] = $config;
        }

        public function observer($ext, $execList){
            if($config = $this->configSelector($ext)){
                $lexer = new Lexer($config);
                $interpreter = new Interpreter();

                $interpreter->addObserver(function(array $columns) use ($execList) {
                    //データに処理を入れるときはこのあたりに
                    foreach($execList as $exec){
                        $exec->exec($columns);
                    }
                });
            }

        }

        public function configSelector($ext){
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