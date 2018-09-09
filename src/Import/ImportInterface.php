<?php
namespace bz0\CSVToDB\File;
interface ImportInterface{
    public function execute($column);
}