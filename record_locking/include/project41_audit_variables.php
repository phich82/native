<?php
$strTableName="project41_audit";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="project41_audit";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,   datetime,   ip,   `user`,   `table`,   `action`,   description";
$gsqlFrom="FROM project41_audit";
$gsqlWhereExpr="";
$gsqlTail="";

include("project41_audit_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_project41_audit;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>