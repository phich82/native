<?php
include("constants.php");
      
class MySQLDB
{
   var $connection;         //The MySQL database connection

   public function __construct()
   {
      $this->connection = @mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
      @mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
   }

   public function confirmIPAddress($value) {
	$q = "SELECT attempts, (CASE when lastlogin is not NULL and DATE_ADD(LastLogin, INTERVAL ".TIME_PERIOD." MINUTE)>NOW() then 1 else 0 end) as Denied ".
   " FROM ".TBL_ATTEMPTS." WHERE ip = '$value'";
 
   $result = @mysql_query($q, $this->connection);
   $data = @mysql_fetch_array($result);   
 
   //Verify that at least one login attempt is in database

   if (!$data) {
     return 0;
   } 
   if ($data["attempts"] >= ATTEMPTS_NUMBER)
   {
      if($data["Denied"] == 1)
      {
         return 1;
      }
     else
     {
        $this->clearLoginAttempts($value);
        return 0;
     }
   }
   return 0;  
  }
   
  public function addLoginAttempt($value) {
   // increase number of attempts
   // set last login attempt time if required    
	  $q = "SELECT * FROM ".TBL_ATTEMPTS." WHERE ip = '$value'"; 
	  $result = @mysql_query($q, $this->connection);
	  $data = @mysql_fetch_array($result);
	  
	  if($data)
      {
        $attempts = $data["attempts"]+1;

        if($attempts==3) {
		 $q = "UPDATE ".TBL_ATTEMPTS." SET attempts=".$attempts.", lastlogin=NOW() WHERE ip = '$value'";
		 $result = @mysql_query($q, $this->connection);
		}
        else {
		 $q = "UPDATE ".TBL_ATTEMPTS." SET attempts=".$attempts." WHERE ip = '$value'";
		 $result = @mysql_query($q, $this->connection);
		}
       }
      else {
	   $q = "INSERT INTO ".TBL_ATTEMPTS." (attempts,IP,lastlogin) values (1, '$value', NOW())";
	   $result = @mysql_query($q, $this->connection);
	  }
    }
   
   
    public function confirmUserPass($username, $password){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
      $q = "SELECT password FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1; //Indicates username failure
      }

      /* Retrieve password from result, strip slashes */
      $dbarray = mysql_fetch_array($result);
      $dbarray['password'] = stripslashes($dbarray['password']);
      $password = stripslashes($password);

      /* Validate that password is correct */
      if($password == $dbarray['password']){
         return 0; //Success! Username and password confirmed
      }
      else{
         return 1; //Indicates password failure
      }
   }
   
   public function confirmUserName($username){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
      $q = "SELECT * FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1; //Indicates username failure
      } 
	  
	  return 0;

   }
   
   public function clearLoginAttempts($value) {
    $q = "UPDATE ".TBL_ATTEMPTS." SET attempts = 0 WHERE ip = '$value'"; 
	return mysql_query($q, $this->connection);
   }
   
   public function getUserInfo($username){
      $q = "SELECT * FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      /* Error occurred, return given name by default */
      if(!$result || (mysql_numrows($result) < 1)){
         return NULL;
      }
      /* Return result array */
      $dbarray = mysql_fetch_array($result);
      return $dbarray;
   }
      
   public function displayUsers(){
   $q = "SELECT username,password "
       ."FROM ".TBL_USERS." ORDER BY username";
   $result = mysql_query($q, $this->connection);

   $num_rows = @mysql_numrows($result);

   if($num_rows == 0){
      echo "Users table is empty";
      return;
   }

   echo "<p><table align=\"left\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">\n";
   echo "<tr><td class=\"tableheadprop\">&nbsp;Username&nbsp;</td><td class=\"tableheadprop\">&nbsp;Password&nbsp;</td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $upass = mysql_result($result,$i,"password");

      echo "<tr><td class=\"tableprop\">$uname</td><td class=\"tableprop\">$upass</td></tr>\n";
   }
   echo "</table></p><br>\n";
   }
   
   public function displayAttempts($value){
   $q = "SELECT ip, attempts,lastlogin "
       ."FROM ".TBL_ATTEMPTS." WHERE ip = '$value' ORDER BY lastlogin";
   $result = mysql_query($q, $this->connection);

   $num_rows = @mysql_numrows($result);

   if($num_rows == 0){
      echo "LoginAttempts table is empty";
      return;
   }

   echo "<p><table align=\"left\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">\n";
   echo "<tr><td class=\"tableheadprop\">&nbsp;Your IP Address&nbsp;</td><td class=\"tableheadprop\">&nbsp;Attempts&nbsp;</td><td class=\"tableheadprop\">&nbsp;LastLogin&nbsp;</td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uip  = mysql_result($result,$i,"ip");
      $uattempt = mysql_result($result,$i,"attempts");
	  $ulogin = mysql_result($result,$i,"lastlogin");
	  

      echo "<tr><td class=\"tableprop\">$uip</td><td class=\"tableprop\">$uattempt</td><td class=\"tableprop\">$ulogin</td></tr>\n";
   }
   echo "</table></p><br>\n";
   }

};

/* Create database connection */
$database = new MySQLDB;
