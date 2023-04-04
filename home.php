<?php
include("core/init.php");
if(!$userObj->isLoggedIn()){
    $userObj->redirect("index.php");
}
$user = $userObj->userData();
$userObj->updateSession();
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
    <script>
        const conn = new WebSocket("ws://localhost:8080/?token=<?php echo $userObj->sessionID;?>")
    </script>
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
        min-height: 80vh;
    }
    div.logs{
        min-height: 80vh;
    }
    img.user_image{
        width: 80px;
        height: 80px;
    }
    img.users_image{
        width: 60px;
        height: 60px;
    }
    h3.text-center{
        font-weight: lighter;
    }
    div.d-flex{
        border-right: 1px solid rgb(220, 216, 216);
    }
    a.user_links{
        text-decoration: none;
        color: black;
        width: 100%;
    }
    div#video{
         height: 100%;
        background-color: white;
    }
    #video{
        display: grid;
        grid-template-columns: 1fr;
        height: 100vh;
        overflow:hidden;
    }

    .video-player{
        background-color: black;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .smallFrame{
        position: fixed;
        top: 20px;
        left: 20px;
        height: 170px;
        width: 300px;
        border-radius: 5px;
        border:2px solid #b366f9;
        -webkit-box-shadow: 3px 3px 15px -1px rgba(0,0,0,0.77);
        box-shadow: 3px 3px 15px -1px rgba(0,0,0,0.77);
        z-index: 999;
    }


    #controls{
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform:translateX(-50%);
        display: flex;
        gap: 1em;
    }


    .control-container{
        background-color: rgb(179, 102, 249, .9);
        padding: 20px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .control-container img{
        height: 30px;
        width: 30px;
    }

    #leave-btn{
        background-color: rgb(255,80,80, 1);
    }


    @media screen and (max-width: 764px) {
        div.login-section{
            min-height: 40vh;
        }
        div.logs{
            min-height: 40vh;
        }
        .smallFrame{
            height: 80px;
            width: 120px;
        }

        .control-container img{
            height: 20px;
            width: 20px;
        }
    }
</style>
<body>
  <header class="container-fluid bg-info">
  </header>
  <div class="container-fluid rgba">
      <div class="container login-section mt-5">
            <div class="row">
                <div class="col-md-4 border-end align-items-center ps-3 pt-3 logs">
                    <nav class="navbar navbar-expand border-bottom w-100">
                        <a href="#" class="navbar-brand"><img src="assets/images/<?php echo $user->profileImage; ?>" alt="user image" class="user_image"> <b class="text-dark"><?php echo $user->name;?></b></a>
                        <div class="collapse navbar-collapse justify-content-end">
                            <ul class="navbar-nav nav">
                                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-circle text-success"></i></a></li>
                                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-comment text-secondary"></i></a></li>
                                <li class="nav-item dropdown"><a class="nav-link" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v text-secondary"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="logout.php" class="dropdown-item">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="mt-3 mb-5">
                        <input type="text" class="form-control" name="search_user" placeholder="Search users">
                    </div>
                    <div class="users_list">
                        <h5 class="pb-3"><b>Users</b></h5>
                        <?php $userObj->getUsers();?>
                    </div>
                </div>
                <div class="col-md-8 logs d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="text-center">
                            <?php if(!isset($_GET["user"])){ ?>
                                <p><i class="fa fa-5x fa-user"></i></p>
                            <?php }else{?>
                                <img src="assets/images/<?php echo "female.jpg"?>" alt="user image" class="rounded-circle user_image">
                                <p class="pt-1"><b><?php echo "Timilehin Amu";?></b></p>
                            <?php }?>
                            <h1 class="fw-light">Keep your webcam connected</h1>
                            <p>This app allows users to video chat with other users</p>
                            <?php if(isset($_GET["user"])){ ?>
                                <button class="btn btn-success rounded-circle px-3 py-3"><i class="fa fa-camera fa-2x"></i></button>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
      </div>
  </div>
    <div id="video" class="d-none">
        <video class="video-player user-1" id="remoteVideo" autoplay playsinline></video>
        <video class="video-player user-2 smallFrame" muted id="localVideo" autoplay playsinline></video>
        <div id="controls">
            <button disabled="disabled btn btn-secondary text-light" id="call_timer"></button>
            <button id="hangupBtn" class="btn control-container bg-danger text-light"><b>Stop</b></button>
        </div>
    </div>
    <div class="modal fade d-none align-items-center justify-content-center" id="call_modal">
        <div class="modal-dialog w-100">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="caller d-flex align-items-center">
                        <img src="" id="caller_image" alt="caller image" class="user_image rounded-circle">
                        <p class="ps-2 pt-2" id="caller_name">Timilehin Amu</p>
                        <div class="d-flex justify-content-end w-100 w-md-80">
                            <button class="btn btn-success me-2" data-bs-dismiss="modal" id="answerBtn"><i class="fa fa-phone"></i></button>
                            <button class="btn btn-danger me-2" id="declineBtn"><b>x</b></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



  <script src="assets/js/main.js"></script>
  <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
</body>
</html>