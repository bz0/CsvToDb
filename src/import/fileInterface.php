<?php
    namespace bz0\CSVToDB\Import;
    interface fileInterface{
        public function config();
        public function accept($ext);
    }