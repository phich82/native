<?php
$strTableName="project39_blocking";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="project39_blocking";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,   tablename,   startdatetime,   confirmdatetime,   `keys`,   sessionid,   userid,   `action`";
$gsqlFrom="FROM project39_blocking";
$gsqlWhereExpr="";
$gsqlTail="";

include("project39_blocking_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_project39_blocking;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>