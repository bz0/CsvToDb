<?php
    namespace bz0\CSVToDB\File;
    interface fileInterface{
        public function config();
        public function accept($ext);
    }