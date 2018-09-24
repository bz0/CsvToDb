<?php
    namespace bz0\CSVToDB\Process;
    class ChatworkMessageSend implements ProcessInterface{
        private $token;
        private $roomId;
        private $message;
        private $url;

        const DOMAIN = 'https://api.chatwork.com';

        public function __construct($token, $roomId, $message){
            $this->token = $token;
            $this->roomId = $roomId;
            $this->message = $message;
            $this->url = self::DOMAIN . "/v2/rooms/{$roomId}/messages";
        }

        public function execute(){
            $command = $this->createCommand();
            $this->commandExec($command);
        }

        private function commandExec($command){
            if ($command===''){
                throw new \Exception(get_class($this) . ":Command Not Found");
            }

            $res  = shell_exec($command);
            $json = json_decode($res, true);

            if (isset($json['errors'][0])){
                throw new \Exception(get_class($this) . ":" . $json['errors'][0]); 
            }
        }

        private function createCommand(){
            $header  = "X-ChatWorkToken: {$this->token}";
            $body    = "body=" . urlencode($this->message) . "&self_unread=0";
            $command = "curl -X POST -H \"{$header}\" -d \"{$body}\" \"$this->url\"";

            return $command;
        }
    }