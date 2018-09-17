<?php
require_once(dirname(__DIR__) . "/../vendor/autoload.php");
require_once(dirname(__DIR__) . "/../src/File/Csv.php");

use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    public function testAccept()
    {
        $csv = new bz0\CSVToDB\File\Csv();
        $this->assertEquals(true, $csv->accept("csv"));
        $this->assertEquals(false, $csv->accept("tsv"));
    }
}