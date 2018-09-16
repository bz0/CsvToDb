<?php
namespace bz0\CSVToDB\Process;
interface ProcessInterface{
    public function execute();
    public function getType();
}