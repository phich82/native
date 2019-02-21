<?php
include("database.php");
include("form.php");

class Session
{
   var $username;            //Username given on sign-up
   var $userid;              //Random value generated on current login
   var $userlevel;           //The level to which the user pertains
   var $time;                //Time user was last active (page loaded)
   var $logged_in;           //True if user is logged in, false otherwise
   var $userinfo = array();  //The array holding all user info
   var $url;                 //The page url current being viewed
   var $referrer;            //Last recorded site page viewed
   var $ip;                  //Remote IP address  

   function Session(){
      $this->ip = $_SERVER["REMOTE_ADDR"];
      $this->time = time();
      $this->startSession();
   }

   function startSession(){
      global $database;  
      session_start();   
	  
      /* Determine if user is logged in */
      $this->logged_in = $this->checkLogin();

      /* Set referrer page */
      if(isset($_SESSION['url'])){
         $this->referrer = $_SESSION['url'];
      }else{
         $this->referrer = "/";
      }

      /* Set current url */
      $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
   }

   function checkLogin(){
      global $database; 
      /* Check if user has been remembered */
      if(isset($_COOKIE['cookname'])){
         $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
      }

      if(isset($_SESSION['username'])){
         if($database->confirmUserName($_SESSION['username']) != 0){
            unset($_SESSION['username']);
            return false;
         }

         $this->userinfo  = $database->getUserInfo($_SESSION['username']);
         $this->username  = $this->userinfo['username'];
         return true;
      }
      else{
         return false;
      }
   }

   function login($subuser, $subpass, $subremember){
      global $database, $form;  

	  /* Checks if this IP address is currently blocked*/	
      $result = $database->confirmIPAddress($this->ip);

      if($result == 1){
         $error_type = "access";
         $form->setError($error_type, "Access denied for ".TIME_PERIOD." minutes");
      } 

	  /* Return if form errors exist */
      if($form->num_errors > 0){
         return false;
      }
	  
	  $error_type = "attempt";
	  /* Username and password error checking */
      if(!$subuser || !$subpass || strlen($subuser = trim($subuser)) == 0){
         $form->setError($error_type, "Username or password not entered");
      }
      
      if($form->num_errors > 0){
         return false;
      }

      /* Checks that username is in database and password is correct */
      $subuser = stripslashes($subuser);
      $result = $database->confirmUserPass($subuser, $subpass);

      if($result == 1){
         $form->setError($error_type, "Invalid username or password.");
		 $database->addLoginAttempt($this->ip);
      }
	  
      if($form->num_errors > 0){
         return false;
      }

      /* Username and password correct, register session variables */
      $this->userinfo  = $database->getUserInfo($subuser);
      $this->username  = $_SESSION['username'] = $this->userinfo['username'];

      
      /* Null login attempts */
	  $database->clearLoginAttempts($this->ip);

	  if($subremember){
         setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH);
      }

      /* Login completed successfully */
      return true;
   }

   function logout(){
      global $database;  

      if(isset($_COOKIE['cookname'])){
         setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
      }

      unset($_SESSION['username']);

      $this->logged_in = false;
      
   }
};


/* Initialize session object */
$session = new Session;

/* Initialize form object */
$form = new Form;

?>
