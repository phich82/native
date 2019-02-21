<?php
$strTableName="carsmodels";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="carsmodels";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,   make,   model";
$gsqlFrom="FROM carsmodels";
$gsqlWhereExpr="";
$gsqlTail="";

include("carsmodels_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_carsmodels;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>