<?php
    namespace bz0\CSVToDB\File;
    use bz0\CSVToDB\Import\fileInterface;
    use Goodby\CSV\Import\Standard\LexerConfig;
    
    class Tsv implements fileInterface{
        public static $config = [
            'DELIMITER'   => "\t",
            'ENCLOSURE'   => '"',
            'ESCAPE'      => "\\",
            'FROMCHARSET' => 'UTF-8',
            'TOCHARSET'   => 'SJIS-win'
        ];

        const EXT         = 'tsv';

        public function config(){
            $config = new LexerConfig();
            $config
                ->setDelimiter(self::config['DELIMITER'])
                ->setEnclosure(self::config['ENCLOSURE'])
                ->setEscape(self::config['ESCAPE'])
                ->setToCharset(self::config['FROMCHARSET'])
                ->setFromCharset(self::config['TOCHARSET'])
            ;

            return $config;
        }

        public function accept($ext){
            return self::EXT === strtolower($ext);
        }
    }