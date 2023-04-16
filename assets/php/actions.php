<?php
require_once 'functions.php';
require_once 'send_code.php';
//we are adding value="functionname" for keeping the entered data intact to the page not erasing it if we entered uname and not password
//for managing signup
    if(isset($_GET['signup'])){
        $response = validateSignup($_POST);
        if($response['status']){
            if(createUser($_POST)){
                header('location:../../?login');
            }
            else{
                echo "<script>alert('Something went wrong')</script>";

            }
        }
        else{
            $_SESSION['error']=$response;
            $_SESSION["formdata"]=$_POST;
            header("location:../../?signup");
        }
    }

    //for managing login
    if(isset($_GET['login'])){
        $response = validateLogin($_POST);
        if($response['status']){
            $_SESSION['auth'] = true;
            $_SESSION['userdata']=$response['user'];
        if($response['user']['ac_status']==0){
            $_SESSION['code']= $code = rand(111111,999999);
            sendCode($response['user']['email'],'Verify Your Email',$code);
        }
            header("location:../../");
        }
        else{
            $_SESSION['error']=$response;
            $_SESSION["formdata"]=$_POST;
            header("location:../../?login");
        } 
    }

    if(isset($_GET['resend_code'])){
        $_SESSION['code']= $code = rand(111111,999999);
        sendCode($_SESSION['userdata']['email'],'Verify Your Email',$code);
        header('location:../../?resent');
    }

    if(isset($_GET['verify_email'])){
        $user_code = $_POST['code'];
        $code = $_SESSION['code'];
        if($code==$user_code){
            if(verifyEmail($_SESSION['userdata']['email'])){
                header("location:../../");
            }else{
                echo "Something went wrong :(";
            }
        }else{
            $response['msg']='Incorrect verification code!';
            if(!$_POST['code']){
                $response['msg']='Empty! Please enter code!';
            }
            $response['field']='email_verify';
            $_SESSION['error']=$response;
            header('location:../../?forgotpassword');
        }
    }

    if(isset($_GET['forgotpassword'])){
        if(!$_POST['email']){
            $response['msg']="Enter your Email-id!";
            $response['field']='email';
            $_SESSION['error']=$response;
            header('location:../../?forgotpassword');
        }elseif(!isEmailRegistered($_POST['email'])){
            $response['msg']="Email-id NOT Registered!";
            $response['field']='email';
            $_SESSION['error']=$response;
            header('location:../../?forgotpassword');
        }else{
            $_SESSION['forgot_email']=$_POST['email'];
            $_SESSION['forgot_code']= $code = rand(111111,999999);
            sendCode($_POST['email'],'Forgot Your Password?',$code);
            header('location:../../?forgotpassword&resent');
        }

    }
    


    //For logout button
    if(isset($_GET['logout'])){
        session_destroy();
        header('location:../../');
    }

    // for verifying forgotten code
    if(isset($_GET['verifycode'])){
        $user_code = $_POST['code'];
        $code = $_SESSION['forgot_code'];
        if($code==$user_code){
            $_SESSION['auth_temp']=true; //temporary authorization given
            header('location:../../');
            }else{
            $response['msg']='Incorrect verification code!';
            if(!$_POST['code']){
                $response['msg']='Empty! Please enter code!';
            }
            $response['field']='email_verify';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
        }
    }

    if(isset($_GET['changepassword'])){
        if(!$_POST['password']){
            $response['msg']="Enter your new password";
            $response['field']='password';
            $_SESSION['error']=$response;
            header('location:../../?forgotpassword');
        }else{
            resetPassword($_SESSION['forgot_email'],$_POST['password']);
            header('location:../../?reset');
        }
        
    }

    if(isset($_GET['updateprofile'])){
        $response = validateUpdateForm($_POST,$_FILES['profilepic']);
        if($response['status']){
            if(updateProfile($_POST,$_FILES['profilepic'])){
                header("location:../../?edit_profile&success");
            }else{
                echo "Something went wrong";
            }
            }
        else{
            $_SESSION['error']=$response;
            header("location:../../?edit_profile");
        } 
    }