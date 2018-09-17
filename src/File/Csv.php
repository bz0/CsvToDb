<?php
    namespace bz0\CSVToDB\File;
    use Goodby\CSV\Import\Standard\LexerConfig;

    class Csv implements fileInterface{
        public static $config = array(
            'DELIMITER'   => ",",
            'ENCLOSURE'   => '"',
            'ESCAPE'      => "\\",
            'FROMCHARSET' => 'UTF-8',
            'TOCHARSET'   => 'SJIS-win'
        );

        const EXT         = 'csv';

        public function config(){
            $config = new LexerConfig();
            $config
                ->setDelimiter(self::$config['DELIMITER'])
                ->setEnclosure(self::$config['ENCLOSURE'])
                ->setEscape(self::$config['ESCAPE'])
                ->setToCharset(self::$config['FROMCHARSET'])
                ->setFromCharset(self::$config['TOCHARSET'])
            ;

            return $config;
        }

        public function accept($ext){
            return self::EXT === strtolower($ext);
        }
    }