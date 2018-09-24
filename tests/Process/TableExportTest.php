<?php
    require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../common/Generic_Tests_DatabaseTestCase.php');
    require_once(dirname(__FILE__) . '/../../DBConfig.php');

    use PHPUnit\DbUnit\DataSet\CsvDataSet;
    use bz0\CSVToDB\Process\TableExport;
    use PHPUnit\DbUnit\Operation\Exception;

    class TableExportTest extends Generic_Tests_DatabaseTestCase
    { 
        /** 
         * @return PHPUnit_Extensions_Database_DataSet_IDataSet 
         */ 
        protected function getDataSet() { 
            $dataSet = new CsvDataSet(); 
            $dataSet->addTable('test', dirname(__FILE__) . "/../_fixture/hoge_table.csv");
            return $dataSet;
        }
     
        public function testExport()
        {
            // 4件insertしたのでテーブルの件数が4件になっていることを確認する
            $this->assertEquals(4, $this->getConnection()->getRowCount('test'));

            $bkupPath = dirname(__FILE__) . "/test.sql";
            $table = new TableExport('test', $bkupPath);
            $table->setPDO($this->getPdo());
            $res = $table->execute();

            $this->assertEquals(true, $res);
        }

        public function testExport_存在しないテーブルをexport()
        {
            try{
                $bkupPath = dirname(__FILE__) . "test.sql";
                $table = new TableExport('test_notexists', $bkupPath);
                $table->setPDO($this->getPdo());
                $res = $table->execute();
            }catch(\Exception $e){
                $res = $e->getMessage();
            }

            $this->assertEquals('bz0\CSVToDB\Process\TableExport:mysqldump: Couldn\'t find table: "test_notexists"', $res);
        }
    }