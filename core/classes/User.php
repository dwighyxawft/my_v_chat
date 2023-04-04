<?php
    namespace MyApp;
    use PDO;
    class User{
        public $db, $userID, $sessionID;
        public function __construct(){
            $db = new \MyApp\DB;
            $this->db = $db->connect();
            $this->userID = $this->ID();
            $this->sessionID = $this->getSessionID();
        }
        public function ID(){
            if($this->isLoggedIn()){
                return $_SESSION["userID"];
            }
        }
        public function getSessionID(){
            return session_id();
        }
        public function email_exists($email){
            $stmt = $this->db->prepare("SELECT * FROM `users_table` WHERE `email` = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            if(!empty($user)){
                return $user;
            }else{
                return false;
            }
        }
        public function hash($password){
            return password_hash($password, PASSWORD_BCRYPT);
        }
        public function redirect($location){
            header("Location: ". BASE_URL.$location);
        }
        public function userData($userID = ''){
            $userID = ((!empty($userID)) ? $userID : $this->userID);
            $stmt = $this->db->prepare("SELECT * FROM `users_table` WHERE `userID` = :userID");
            $stmt->bindParam(":userID", $userID, PDO::PARAM_STR);
            $stmt->execute();
            return $user = $stmt->fetch(PDO::FETCH_OBJ);
        }
        public function isLoggedIn(){
            return ((isset($_SESSION['userID'])) ? true : false);
        }
        public function logout(){
            $_SESSION = array();
            session_destroy();
            session_regenerate_id();
            $this->redirect("index.php");
        }
        public function getUsers(){
            $stmt = $this->db->prepare("SELECT * FROM `users_table` WHERE `userID` != :userID");
            $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach($users as $user){
                echo '<div class="user border-top border py-2 px-2 mb-2">
                        <a href="'.BASE_URL.$user->username.'" class="user_links"><img src="assets/images/'.$user->profileImage.'" alt="" class="users_image rounded-circle"> &nbsp; &nbsp; <b class="text-dark">'.$user->name.'</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i class="fa fa-phone text-success"></i></a>
                      </div>';
            }
        }
        public function getUserByUsername($username){
            $stmt = $this->db->prepare("SELECT * FROM `users_table` WHERE `username` = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        public function updateSession(){
            $stmt = $this->db->prepare("UPDATE `users_table` SET `sessionID` = :sessionID WHERE `userID` = :userID");
            $stmt->bindParam(":sessionID", $this->sessionID, PDO::PARAM_STR);
            $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
            $stmt->execute();
        }
        public function getUserBySession($sessionID){
            $stmt = $this->db->prepare("SELECT * FROM `users_table` WHERE `sessionID` = :sessionID");
            $stmt->bindParam(":sessionID", $sessionID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        public function updateConnection($connectionID, $userID){
            $stmt = $this->db->prepare("UPDATE `users_table` SET `connectionID` = :connectionID WHERE `userID` = :userID");
            $stmt->bindParam(":connectionID", $connectionID, PDO::PARAM_STR);
            $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
?>