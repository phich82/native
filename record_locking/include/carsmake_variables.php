<?php
$strTableName="carsmake";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="carsmake";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,  make";
$gsqlFrom="FROM carsmake";
$gsqlWhereExpr="";
$gsqlTail="";

include("carsmake_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_carsmake;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>