<?php
require_once(dirname(__DIR__) . "/../vendor/autoload.php");
require_once(dirname(__DIR__) . "/../src/File/Tsv.php");

use PHPUnit\Framework\TestCase;

class TsvTest extends TestCase
{
    public function testAccept()
    {
        $tsv = new bz0\CSVToDB\File\Tsv();
        $this->assertEquals(false, $tsv->accept("csv"));
        $this->assertEquals(true, $tsv->accept("tsv"));
    }
}