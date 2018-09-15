<?php
    namespace bz0\CSVToDB;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
    use Monolog\Handler\RotatingFileHandler;
    use Monolog\Formatter\LineFormatter;

    class Monolog{
        private $monolog;
        private $logName;
        private $logPath;

        public function __construct($logName, $logPath){
            $this->logName = $logName;
            $this->logPath = $logPath;
        }

        public function setConfig(){
            $this->monolog = new Logger($this->logName);
            $logFormatter = new LineFormatter("[%datetime%] %level_name%: %message% %context% %extra%\n");
            $logHandler = new StreamHandler($this->logPath, Logger::DEBUG);
            $logHandler->setFormatter($logFormatter);
            $this->monolog->pushHandler($logHandler);

            return $this->monolog;
        }
    }