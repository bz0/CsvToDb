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

        public function prepareDb(){
            foreach($this->config->getPrepareDb() as $prepareDb){
                $prepareDb->execute();
            }
        }

        public function execute($filePathList){
            try{
                $this->prepareDb();
                
                foreach($filePathList as $filePath){
                    $finfo = pathinfo($filePath);
                    $this->logger->info("ext:" . $finfo['extension'] . " path:" . $filePath);
                    if($config = $this->fileConfigSelector($finfo['extension'])){
                        $lexer = new Lexer($config);
                        $interpreter = new Interpreter();

                        $interpreter->addObserver(function(array $columns){
                            foreach($this->config->getColumnExecute() as $columnExecute){
                                $columnExecute->execute($columns);
                            }
                        });
        
                        $lexer->parse($filePath, $interpreter);
                    }
                }
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