<?php

$version = explode('.', PHP_VERSION);
if($version[0]*10+$version[1]<53)
	set_magic_quotes_runtime(0);

if(@$_SERVER["REQUEST_URI"])
{
	$pinfo=pathinfo($_SERVER["REQUEST_URI"]);
	$dirname = @$pinfo["dirname"];
	$dir = explode("/",$dirname);
	$dirname="";
	foreach($dir as $subdir)
	{
		if($subdir!="")
			$dirname.="/".rawurlencode($subdir);
	}
	if($dirname!="")
	{
//		@session_set_cookie_params(0,$dirname);
	}
}
@session_cache_limiter("none");
@session_start();

error_reporting(E_ALL ^ E_NOTICE);

$host="localhost";
$user="root";
$pwd="";
$port="3306";
$sys_dbname="native";


$cCharset = "windows-1251";

header("Content-Type: text/html; charset=".$cCharset);

$dDebug=false;
$dSQL="";

$bSubqueriesSupported=true;

$tables_data=array();
$field_labels=array();

//	custom labels
$custom_labels=array();
$custom_labels["English"] = array();

/// include php specific code
include('phpfunctions.php');
include("locale.php");
include("events.php");
include("commonfunctions.php");
include("dbconnection.php");
include("dal_source.php");
include("audit.php");
include("blocking.php");



define("FORMAT_NONE","");
define("FORMAT_DATE_SHORT","Short Date");
define("FORMAT_DATE_LONG","Long Date");
define("FORMAT_DATE_TIME","Datetime");
define("FORMAT_TIME","Time");
define("FORMAT_CURRENCY","Currency");
define("FORMAT_PERCENT","Percent");
define("FORMAT_HYPERLINK","Hyperlink");
define("FORMAT_EMAILHYPERLINK","Email Hyperlink");
define("FORMAT_FILE_IMAGE","File-based Image");
define("FORMAT_DATABASE_IMAGE","Database Image");
define("FORMAT_DATABASE_FILE","Database File");
define("FORMAT_FILE","Document Download");
define("FORMAT_LOOKUP_WIZARD","Lookup wizard");
define("FORMAT_PHONE_NUMBER","Phone Number");
define("FORMAT_NUMBER","Number");
define("FORMAT_HTML","HTML");
define("FORMAT_CHECKBOX","Checkbox");
define("FORMAT_CUSTOM","Custom");

define("EDIT_FORMAT_NONE","");
define("EDIT_FORMAT_TEXT_FIELD","Text field");
define("EDIT_FORMAT_TEXT_AREA","Text area");
define("EDIT_FORMAT_PASSWORD","Password");
define("EDIT_FORMAT_DATE","Date");
define("EDIT_FORMAT_TIME","Time");
define("EDIT_FORMAT_RADIO","Radio button");
define("EDIT_FORMAT_CHECKBOX","Checkbox");
define("EDIT_FORMAT_DATABASE_IMAGE","Database image");
define("EDIT_FORMAT_DATABASE_FILE","Database file");
define("EDIT_FORMAT_FILE","Document upload");
define("EDIT_FORMAT_LOOKUP_WIZARD","Lookup wizard");
define("EDIT_FORMAT_HIDDEN","Hidden field");
define("EDIT_FORMAT_READONLY","Readonly");

define("EDIT_DATE_SIMPLE",0);
define("EDIT_DATE_SIMPLE_DP",11);
define("EDIT_DATE_DD",12);
define("EDIT_DATE_DD_DP",13);

define("MODE_ADD",0);
define("MODE_EDIT",1);
define("MODE_SEARCH",2);
define("MODE_LIST",3);
define("MODE_PRINT",4);
define("MODE_VIEW",5);
define("MODE_INLINE_ADD",6);
define("MODE_INLINE_EDIT",7);
define("MODE_EXPORT",8);

define("LOGIN_HARDCODED",0);
define("LOGIN_TABLE",1);

define("SECURITY_NONE",-1);
define("SECURITY_HARDCODED", 0);
define("SECURITY_TABLE", 1);

define("ADVSECURITY_ALL",0);
define("ADVSECURITY_VIEW_OWN",1);
define("ADVSECURITY_EDIT_OWN",2);
define("ADVSECURITY_NONE",3);

define("ACCESS_LEVEL_ADMIN","Admin");
define("ACCESS_LEVEL_ADMINGROUP","AdminGroup");
define("ACCESS_LEVEL_USER","User");
define("ACCESS_LEVEL_GUEST","Guest");

define("nDATABASE_MySQL",0);
define("nDATABASE_Oracle",1);
define("nDATABASE_MSSQLServer",2);
define("nDATABASE_Access",3);
define("nDATABASE_PostgreSQL",4);

define("ADD_SIMPLE",0);
define("ADD_INLINE",1);
define("ADD_ONTHEFLY",2);

define("titTABLE",0);
define("titVIEW",1);
define("titREPORT",2);
define("titCHART",3);

// for report lib
define("REPORT_STEPPED", 0);
define("REPORT_BLOCK", 1);
define("REPORT_OUTLINE", 2);
define("REPORT_ALIGN", 3);
define("REPORT_TABULAR", 6);

define("TOTAL_NONE", -1);
define("TOTAL_MAX", 0);
define("TOTAL_AVG", 1);
define("TOTAL_SUM", 3);
define("TOTAL_MIN", 4);

define("LIST_SIMPLE",0);
define("LIST_LOOKUP",1);
define("DP_POPUP",2);
define("DP_INLINE",3);
define("DP_NONE", 4);
define("RIGHTS_PAGE", 5);
define("MEMBERS_PAGE", 6);


define("SEARCH_SIMPLE",0);
define("SEARCH_LOAD_CONTROL",1);

define("LCT_DROPDOWN",0);
define("LCT_AJAX",1);
define("LCT_LIST",2);
define("LCT_CBLIST",3);

define("LT_LISTOFVALUES",0);
define("LT_LOOKUPTABLE",1);

$strLeftWrapper="`";
$strRightWrapper="`";

$cLoginTable				= "carsusers";
$cUserNameField			= "username";
$cPasswordField			= "password";
$cUserGroupField			= "username";
$cEmailField			= "";


set_error_handler("error_handler");

$useAJAX = true;
$suggestAllContent = true;

$strLastSQL="";

$includes_js=array();
$includes_jsreq=array();
$includes_css=array();

$mlang_messages=array();
$mlang_charsets=array();

// table captions
$tableCaptions = array();
$tableCaptions["English"]=array();
$tableCaptions["English"]["carsmake"] = "Carsmake";
$tableCaptions["English"]["carsmodels"] = "Carsmodels";
$tableCaptions["English"]["carsusers"] = "Carsusers";
$tableCaptions["English"]["project39_blocking"] = "Project39 Blocking";
$tableCaptions["English"]["project41_audit"] = "Project41 Audit";
$tableCaptions["English"]["carscars"] = "Carscars";

$mlang_defaultlang="English";



$conn=db_connect();
if(!@$_SESSION["UserID"])
{
	$allowGuest=true;
	$scriptname=$_SERVER["PHP_SELF"];
	$pos=strrpos($scriptname,"/");
	if($pos!==FALSE)
		$scriptname=substr($scriptname,$pos+1);
	if($allowGuest && $scriptname!="login.php" && $scriptname!="remind.php" && $scriptname!="register.php")
	{
		$_SESSION["UserID"]="Guest";
		$_SESSION["GroupID"]="<Guest>";
		$_SESSION["AccessLevel"]=ACCESS_LEVEL_GUEST;
		$audit->LogLogin();
		if(function_exists("AfterSuccessfulLogin"))
		{			
			$dummy=array();
			AfterSuccessfulLogin("","",$dummy);
		}
	}
}

?>
