<?php
    //import
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\Import\Csv;
    use bz0\CSVToDB\Import\Tsv;

    class CSVToDB{
        private $configList = [];
        private $execList = [];

        public function __construct(){
            $this->configList[] = new Csv();
            $this->configList[] = new Tsv();
        }

        public function setConfig(fileInterface $config){
            $this->configList[] = $config;
        }

        public function observer($ext){
            if($config = $this->configSelector($ext)){
                $lexer = new Lexer($config);
                $interpreter = new Interpreter();

                $interpreter->addObserver(function(array $columns) use ($execList) {
                    foreach($this->execList as $exec){
                        $exec->exec($columns);
                    }
                });

                $lexer->parse('users.csv', $interpreter);
            }

            throw new Exception();
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