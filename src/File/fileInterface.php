<?php
    namespace bz0\CSVToDB\File;
    interface FileInterface{
        public function config();
        public function accept($ext);
    }