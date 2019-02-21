<?php
define("DB_SERVER", "localhost:3306");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "native");
define("TBL_USERS", "users");
define("TBL_ATTEMPTS", "login_attempts");
define("ATTEMPTS_NUMBER", "3");
define("TIME_PERIOD", "30");
define("COOKIE_EXPIRE", 60*60*24*100);      //100 days by default
define("COOKIE_PATH", "/");                 //Avaible in whole domain
?>
