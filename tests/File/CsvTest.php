<?php
require_once(dirname(__DIR__) . "/../vendor/autoload.php");
require_once(dirname(__DIR__) . "/../src/File/Csv.php");

use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{
    public function testAccept()
    {
        $csv = new bz0\CSVToDB\File\Csv();
        $this->assertEquals(true, $csv->accept("csv"));
        $this->assertEquals(false, $csv->accept("tsv"));
    }

    public function testCsvConfig(){
        $c = new bz0\CSVToDB\File\Csv();
        $config = $c->config();

        $this->assertEquals(",", $config->getDelimiter());
        $this->assertEquals("\"", $config->getEnclosure());
        $this->assertEquals("\\", $config->getEscape());
        $this->assertEquals("UTF-8", $config->getToCharset());
        $this->assertEquals("SJIS-win", $config->getFromCharset());
    }
}