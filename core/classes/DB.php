<?php
    namespace MyApp;
    use PDO;
    class DB{
        public function connect(){
            $db = new PDO("mysql:host=127.0.0.1; dbname=my_v_chat", "dwightxawft", "Hesoyam12+34.");
            return $db;
        }
    }
?>