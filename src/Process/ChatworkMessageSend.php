<?php
    namespace bz0\CSVToDB\Process;
    class ChatworkMessageSend implements ProcessInterface{
        private $token;
        private $roomId;
        private $message;

        public function __construct($token, $roomId, $message){
            $this->token = $token;
            $this->roomId = $roomId;
            $this->message = $message;
        }

        public function execute(){
            $header  = "X-ChatWorkToken: {$this->token}";
            $body    = "body=" . urlencode($this->message) . "&self_unread=0";
            $url     = "https://api.chatwork.com/v2/rooms/{$this->roomId}/messages";
            $command = "curl -X POST -H \"{$header}\" -d \"{$body}\" \"$url\"";
            exec($command, $out, $status);

            if ($status!=0){
                throw new \Exception(get_class($this) . ": チャットワークへのメッセージの通知に失敗しました");
            }
        }
    }