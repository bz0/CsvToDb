<?php
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\File\FileInterface;
    use bz0\CSVToDB\PrepareDb\PrepareDbInterface;
    use bz0\CSVToDB\PostProcess\PostProcessInterface;

    class Config{
        private $fileConfig;
        private $prepareDb;
        private $postProcessing;
        private $columnExecute;

        public function setFileConfig(FileInterface $fileConfig){
            $this->fileConfig[] = $fileConfig;
        }

        public function getFileConfig(){
            return $fileConfig;
        }

        public function setPrepareDb(PrepareDbInterface $prepareDb){
            $this->prepareDb[] = $prepareDb;
        }

        public function getPrepareDb(){
            return $this->prepareDb;
        }

        public function setPostProcessing(PostProcessInterface $postProcessing){
            $this->postProcessing[] = $postProcessing;
        }

        public function getPostProcessing(){
            return $this->postProcessing;
        }

        public function setColumnExecute(ColumnInterface $columnExecute){
            $this->columnExecute[] = $columnExecute;
        }

        public function getColumnExecute(){
            return $this->columnExecute;
        }
    }