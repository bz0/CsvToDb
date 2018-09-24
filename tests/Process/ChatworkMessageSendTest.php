<?php
    use PHPUnit\Framework\TestCase;

    class ChatworkMessageSendTest extends TestCase{
        public function testCreateCommand(){
            $token = "aaaa";
            $roomId = "1111";
            $message = "Hello World";
            $ps = new bz0\CSVToDB\Process\ChatworkMessageSend($token, $roomId, $message);
            $reflection = new \ReflectionClass($ps);
            $method = $reflection->getMethod('createCommand');
            $method->setAccessible(true);
            $this->assertEquals('curl -X POST -H "X-ChatWorkToken: aaaa" -d "body=Hello+World&self_unread=0" "https://api.chatwork.com/v2/rooms/1111/messages"', $method->invoke($ps));
        }

        public function testCommandExecNotFound(){
            $token = "aaaa";
            $roomId = "1111";
            $message = "Hello World";
            $ps = new bz0\CSVToDB\Process\ChatworkMessageSend($token, $roomId, $message);
            $reflection = new \ReflectionClass($ps);
            $method = $reflection->getMethod('commandExec');
            $method->setAccessible(true);
            $command = '';
            try{
                $method->invoke($ps, $command);
            }catch(\Exception $e){
                $message = $e->getMessage();
            }

            $this->assertEquals("bz0\CSVToDB\Process\ChatworkMessageSend:Command Not Found", $message);
        }

        public function testCommandExec(){
            $token = "aaaa";
            $roomId = "1111";
            $message = "Hello World";

            $ps = new bz0\CSVToDB\Process\ChatworkMessageSend($token, $roomId, $message);
            $reflection = new \ReflectionClass($ps);
            $method = $reflection->getMethod('commandExec');
            $method->setAccessible(true);
            $command = 'curl -s -S -X POST -H "X-ChatWorkToken: aaaa" -d "body=Hello+World&self_unread=0" "https://api.chatwork.com/v2/rooms/1111/messages"';

            try{
                $method->invoke($ps, $command);
            }catch(\Exception $e){
                $message = $e->getMessage();
            }

            $this->assertEquals("bz0\CSVToDB\Process\ChatworkMessageSend:Invalid API Token", $message);
        }
    }