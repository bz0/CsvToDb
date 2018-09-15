<?php
namespace bz0\CSVToDB\Column;
interface ColumnExecuteInterface{
    public function execute($column);
    public function setIsHeader($isHeader);
    public function initRowCount();
}