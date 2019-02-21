<?php
$strTableName="carscars";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="carscars";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT category,  color,  `Date Listed`,  descr,  EPACity,  EPAHighway,  features,  Horsepower,  id,  Make,  Model,  `Phone #`,  Picture,  Price,  UserID,  YearOfMake,  zipcode";
$gsqlFrom="FROM carscars";
$gsqlWhereExpr="";
$gsqlTail="";

include("carscars_settings.php");

// alias for 'SQLQuery' object
$gQuery = &$queryData_carscars;


$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>