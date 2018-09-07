<?php
    namespace bz0\CSVToDB\Import;
    use Goodby\CSV\Import\Standard\LexerConfig;
    
    class Tsv{
        const DELIMITER   = "\t";
        const ENCLOSURE   = "'";
        const ESCAPE      = "\\";
        const FROMCHARSET = 'UTF-8';
        const TOCHARSET   = 'SJIS-win';

        public function config(){
            $config = new LexerConfig();
            $config
                ->setDelimiter(self::DELIMITER) // Customize delimiter. Default value is comma(,)
                ->setEnclosure(self::ENCLOSURE)  // Customize enclosure. Default value is double quotation(")
                ->setEscape(self::ESCAPE)    // Customize escape character. Default value is backslash(\)
                ->setToCharset(self::FROMCHARSET) // Customize target encoding. Default value is null, no converting.
                ->setFromCharset(self::TOCHARSET) // Customize CSV file encoding. Default value is null.
            ;

            return $config;
        }
    }