<?php
$strTableName="carsusers";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="carsusers";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,  password,  username";
$gsqlFrom="FROM carsusers";
$gsqlWhereExpr="";
$gsqlTail="";

include("carsusers_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_carsusers;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>