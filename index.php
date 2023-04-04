<?php
include("core/init.php");
if($userObj->isLoggedIn()){
    $userObj->redirect("home.php");
}
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST)){
        $mail = trim(stripcslashes(htmlentities($_POST["email"])));
        $pass = $_POST["password"];
        if(!empty($mail) && !empty($pass)){
            if($user = $userObj->email_exists($mail)){
                if(password_verify($pass, $user->password)){
                    session_regenerate_id();
                    $_SESSION["userID"] = $user->userID;
                    $userObj->redirect("home.php");
                }else{
                    $error = "Incorrect email or password";
                }
            }
        }else{
            $error = "Please enter your email and password to login";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/popper2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <title>Live Video Chat Using PHP</title>
</head>
<style>
    header{
    height: 150px;
}
div.rgba{
    height: 100%;
    position: absolute;
    background-color: rgba(0,0,0,0.2);
    top: 0;bottom: 0; left: 0; right: 0;
}
div.login-section{
    background-color: white;
    border-radius: 5px;
}
img.user_image{
    width: 120px;
    height: 120px;
}
h3.text-center{
    font-weight: lighter;
}
div.d-flex{
    border-right: 1px solid rgb(220, 216, 216);
}
</style>
<body>
  <header class="container-fluid bg-info">
  </header>
  <div class="container-fluid rgba">
      <div class="container login-section mt-5">
            <div class="row">
                <div class="d-sm-flex d-none col-md-4 d-flex align-items-center ps-5">
                <img src="assets/images/aleksander-vlad-jiVeo0i1EB4-unsplash.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="container pt-5">
                        <center><img src="assets/images/male.jpg" alt="" class="rounded-circle user_image"></center>
                        <h3 class="text-center pt-3">Welcome!</h3>
                        <p class="pt-2 text-center">Signin into Your Account</p>
                        
                        <form action="" method="post">
                            <div class="form-group mt-3">
                                <label for="mail">Email:</label>
                                <input type="email" name="email" id="mail" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="pass">Password:</label>
                                <input type="password" name="password" id="pass" class="form-control" required>
                            </div>
                            <?php if(isset($error)){ ?>
                                <div class="invalid-feedback d-block"><?php echo $error; ?></div>
                            <?php }?>
                            <div class="form-group text-center mt-5 mb-4">
                                <button class="btn btn-success rounded-pill px-5" name="submit">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
      </div>
  </div>
  



  <script src="../jquery-3.5.1.min.js"></script>
    <script src="../popper2.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>