<?php
    require_once 'assets/php/functions.php';
    if(isset($_GET['newfp'])){
        unset($_SESSION['auth_temp']);
        unset($_SESSION['forgot_email']);
        unset($_SESSION['fogot_code']);
    }
    if(isset($_SESSION['auth'])){
    $user = getUser($_SESSION['userdata']['id']);
    }

    $pagecount = count($_GET);



    //managing pages
    if(isset($_SESSION['auth']) && $user['ac_status']==1 && !$pagecount){
        showPage('header',['page_title'=>'Home']);
        showPage('navbar');
        showPage('wall');

    }elseif(isset($_SESSION['auth']) && $user['ac_status']==0 && !$pagecount){
        showPage('header',['page_title'=>'Verify your Email']);
        showPage('verify_email');
    }elseif(isset($_SESSION['auth']) && $user['ac_status']==2 && !$pagecount){
        showPage('header',['page_title'=>'Blocked']);
        showPage('blocked');
    }elseif(isset($_SESSION['auth']) && isset($_GET['edit_profile']) && $user['ac_status']==1){
        showPage('header',['page_title'=>'Edit Profile']);
        showPage('navbar');
        showPage('edit_profile');
    }
    elseif(isset($_GET['signup'])){
        showPage('header',['page_title'=>'UsBroUs-Signup']);
        showPage('signup');
    }elseif(isset($_GET['login'])){
        showPage('header',['page_title'=>'UsBroUs-Login']);
        showPage('login');
    }elseif(isset($_GET['forgotpassword'])){
        showPage('header',['page_title'=>'UsBroUs-Forgot Password?']);
        showPage('forgot_password');
    }else{
        if(isset($_SESSION['auth']) && $user['ac_status']==1){
            showPage('header',['page_title'=>'Home']);
            showPage('navbar');
            showPage('wall');
        }elseif(isset($_SESSION['auth']) && $user['ac_status']==0){
            showPage('header',['page_title'=>'Verify your Email']);
            showPage('verify_email');
        }elseif(isset($_SESSION['auth']) && $user['ac_status']==2){
            showPage('header',['page_title'=>'Blocked']);
            showPage('blocked');
        }else{
        showPage('header',['page_title'=>'UsBroUs-Login']);
        showPage('login');
        }
    }

showPage('footer');
unset($_SESSION['error']);
unset($_SESSION['formdata']);