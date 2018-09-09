<?php
    require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../common/Generic_Tests_DatabaseTestCase.php');

    use PHPUnit\DbUnit\DataSet\CsvDataSet;

    class TableDeleteTest extends Generic_Tests_DatabaseTestCase
    { 
        /** 
         * @return PHPUnit_Extensions_Database_DataSet_IDataSet 
         */ 
        protected function getDataSet() { 
            $dataSet = new CsvDataSet(); 
            $dataSet->addTable('test', dirname(__FILE__) . "/../_fixture/hoge_table.csv");
            return $dataSet;
        }
     
        public function testGetRowCount()
        {
      // 4件insertしたのでテーブルの件数が4件になっていることを確認する
            $this->assertEquals(4, $this->getConnection()->getRowCount('test'));
        }
    }