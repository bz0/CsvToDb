<?php
    require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
    require_once(dirname(__FILE__) . '/../common/Generic_Tests_DatabaseTestCase.php');

    use PHPUnit\DbUnit\DataSet\CsvDataSet;
    use bz0\CSVToDB\prepareDb\TableDelete;

    class TableCopyTest extends Generic_Tests_DatabaseTestCase
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
            var_dump($this->getConnection()->getRowCount('test'));
            $this->assertEquals(4, $this->getConnection()->getRowCount('test'));

            $table = new TableDelete(parent::$pdo, 'test');
            $table->execute();

            var_dump($this->getConnection()->getRowCount('test'));
            $this->assertEquals(0, $this->getConnection()->getRowCount('test'));
        }
    }