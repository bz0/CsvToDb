<?php
    namespace bz0\CSVToDB;
    use bz0\CSVToDB\File\FileInterface;
    use bz0\CSVToDB\Process\ProcessInterface;
    use bz0\CSVToDB\PostProcess\PostProcessInterface;
    use bz0\CSVToDB\Column\ColumnExecuteInterface;

    class Config{
        private $fileConfig;
        private $prepareProcess;
        private $postProcess;
        private $columnExecute;

        public function setFileConfig(FileInterface $fileConfig){
            $this->fileConfig[] = $fileConfig;
        }

        public function getFileConfig(){
            return $this->fileConfig;
        }

        public function setPrepareProcess(ProcessInterface $process){
            $this->prepareProcess[] = $process;
        }

        public function getPrepareProcess(){
            return $this->prepareProcess;
        }

        public function setPostProcess(ProcessInterface $process){
            $this->postProcess[] = $process;
        }

        public function getPostProcess(){
            return $this->postProcess;
        }

        public function setColumnExecute(ColumnExecuteInterface $columnExecute){
            $this->columnExecute = $columnExecute;
        }

        public function getColumnExecute(){
            return $this->columnExecute;
        }
    }