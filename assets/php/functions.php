<?php
require_once 'config.php';
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//to show the pages on the web
function showPage($page,$data=""){
    include("assets/pages/$page.php");
}
//to show the errors
function showError($field){
if(isset($_SESSION['error'])){
    $error=$_SESSION['error'];
    if (isset($error['field']) && $field==$error['field']){
        ?>
<div class="alert alert-danger" role="alert">
  <?=$error['msg']?>
</div>
        <?php
    }
    }
}
//To show previos data (after entering half entries when we click btn others are lost so for that we will use this function)
function showFormData($field){
    if(isset($_SESSION["formdata"])){
        $formdata = $_SESSION["formdata"];
        return $formdata[$field];
    }
}

//for checking duplicate email (for uniqueness)
function isEmailRegistered($email){
    global $db;

    $query = "SELECT count(*) as row FROM users WHERE email='$email'";
    $run = mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//for checking duplicate username (for uniqueness)
function isUsernameRegistered($username){
    global $db;

    $query = "SELECT count(*) as row FROM users WHERE username='$username'";
    $run = mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//for checking duplicate username by others (for uniqueness)
function isUsernameRegisteredByOthers($username){
    global $db;
    $user_id=$_SESSION['userdata']['id'];
    $query = "SELECT count(*) as row FROM users WHERE username='$username' && id!=$user_id";
    $run = mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//to validate the signup *here I've used validateSignup for validateSignupFrom*
function validateSignup($form){ 
$response=array();
$response['status']=true; 
    if(!$form['password']){
        $response['msg']="You have not entered password";
        $response['status']=false;
        $response['field']="password";
    }
    if(!$form['username']){
        $response['msg']="You have not entered username";
        $response['status']=false;
        $response['field']="username";
    }
    if(!$form['email']){
        $response['msg']="You have not entered your email-id";
        $response['status']=false;
        $response['field']="email";
    }
    if(!$form['lastname']){
        $response['msg']="You have not entered last name";
        $response['status']=false;
        $response['field']="lastname";
    }
    if(!$form['firstname']){
        $response['msg']="You have not entered first name";
        $response['status']=false;
        $response['field']="firstname";  
    }
    if(isEmailRegistered($form['email'])){
        $response['msg']="This Email-id is Already Registered!";
        $response['status']=false;
        $response['field']="email";
    }
    if(isUsernameRegistered($form['username'])){
        $response['msg']="This Username Already Exists!";
        $response['status']=false;
        $response['field']="username";
    }
    return $response;
}

//for validating login (login)
function validateLogin($form){
    $response=array();
    $response['status']=true; 
    $blank=false;
        if(!$form['password']){
            $response['msg']="You have not entered password";
            $response['status']=false;
            $response['field']="password";
            $blank=true;
        }
        if(!$form['username_email']){ //here in bracket we write name of that field
            $response['msg']="You have not entered username/email";
            $response['status']=false;
            $response['field']="username_email";
            $blank=true;
        }
        if(!$blank && !checkUser($form)['status']){ 
            $response['msg']="Username/email or Password is incorrect... Register First";
            $response['status']=false;
            $response['field']='checkuser';
        }else{
            $response['user']=checkUser($form)['user'];   
        }

        return $response;
    }

//for checking user  
function checkUser($login_data){
    global $db;
    $username_email=$login_data['username_email'];
    $password=md5($login_data['password']);

    $query= "SELECT * FROM users WHERE (email='$username_email' OR username='$username_email') AND password='$password'";
    $run = mysqli_query($db,$query);
    $data['user'] = mysqli_fetch_assoc($run) ?? array();
    if(count($data['user'])>0){
        $data['status'] = true; 
    }else{
        $data['status'] = false;
    }
    return $data;
}

function getUser($user_id){
    global $db;
    $query= "SELECT * FROM users WHERE id=$user_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run);
}  

//for creating new users  WORKING(signup)
function createUser($data){
    global $db;
    $firstname = mysqli_real_escape_string($db,$data['firstname']);
    $lastname = mysqli_real_escape_string($db,$data['lastname']);
    $gender = $data['gender'];
    $email = mysqli_real_escape_string($db,$data['email']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);
    $password = md5($password);
    $query="INSERT INTO users(firstname,lastname,gender,email,username,password)";
    $query.="VALUES('$firstname','$lastname',$gender,'$email','$username','$password')";
    return mysqli_query($db,$query);
    }
//Function for verifying email
function verifyEmail($email){
    global $db;
    $query="UPDATE users SET ac_status=1 WHERE email='$email'";
    return mysqli_query($db,$query);
}

//Function for changing password
function resetPassword($email,$password){
    global $db;
    $password=md5($password);
    $query="UPDATE users SET password=$password WHERE email='$email'";
    return mysqli_query($db,$query);
}

//for validating updated form
function validateUpdateForm($form,$image_data){
    $response=array();
    $response['status']=true; 
        if(!$form['username']){
            $response['msg']="You have not entered username";
            $response['status']=false;
            $response['field']="username";
        }
        if(!$form['lastname']){
            $response['msg']="You have not entered last name";
            $response['status']=false;
            $response['field']="lastname";
        }
        if(!$form['firstname']){
            $response['msg']="You have not entered first name";
            $response['status']=false;
            $response['field']="firstname";  
        }
        if(isUsernameRegisteredByOthers($form['username'])){
            $response['msg']=$form['username']." Already Exists!";
            $response['status']=false;
            $response['field']="username";
        }
        if($image_data['name']){
            $image = basename($image_data['name']);
            $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
            $size = $image_data['size']/1000;
        if($type!='jpg' && $type!='jpeg' && $type!='png'){
            $response['msg']="Filetype not allowed(hint:use jpg,jpeg,png)";
            $response['status']=false;
            $response['field']="profilepic";  
        }
         if($size>1000){
            $response['msg']="Upload image with size<1 mb";
                $response['status']=false;
                $response['field']="profilepic";
         }
        }
        return $response;
}

//function for updating profile
function updateProfile($data,$imagedata){
    global $db;
    $firstname = mysqli_real_escape_string($db,$data['firstname']);
    $lastname = mysqli_real_escape_string($db,$data['lastname']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);
if(!$data['password']){
    $password=$_SESSION['userdata']['password'];

}else{
    $password = md5($password);
    $_SESSION['userdata']['password']=$password;
}
$profilepic='';
if($imagedata['name']){
    $image_name = time().basename($imagedata['name']);
    $image_dir="../images/profile/$image_name";
    move_uploaded_file($imagedata['tmp_name'],$image_dir);
    $profilepic = ", profilepic='$image_name'";
    
    }



    $query = "UPDATE users SET firstname='$firstname', lastname='$lastname', username='$username',password='$password' $profilepic WHERE id=".$_SESSION['userdata']['id'];
    return mysqli_query($db,$query);

} 
    

?>