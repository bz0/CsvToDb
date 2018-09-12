<?php
namespace bz0\CSVToDB\prepareDb;
interface PrepareDbInterface{
    public function execute($params);
    public function accept($class);
}