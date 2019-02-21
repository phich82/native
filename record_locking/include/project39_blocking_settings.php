<?php

//	field labels
$fieldLabelsproject39_blocking = array();
$fieldLabelsproject39_blocking["English"]=array();
$fieldLabelsproject39_blocking["English"]["id"] = "Id";
$fieldLabelsproject39_blocking["English"]["tablename"] = "Tablename";
$fieldLabelsproject39_blocking["English"]["startdatetime"] = "Startdatetime";
$fieldLabelsproject39_blocking["English"]["confirmdatetime"] = "Confirmdatetime";
$fieldLabelsproject39_blocking["English"]["keys"] = "Keys";
$fieldLabelsproject39_blocking["English"]["sessionid"] = "Sessionid";
$fieldLabelsproject39_blocking["English"]["userid"] = "Userid";
$fieldLabelsproject39_blocking["English"]["action"] = "Action";


$tdataproject39_blocking=array();
	 $tdataproject39_blocking[".NumberOfChars"]=80; 
	$tdataproject39_blocking[".ShortName"]="project39_blocking";
	$tdataproject39_blocking[".OwnerID"]="";
	$tdataproject39_blocking[".OriginalTable"]="project39_blocking";
	$tdataproject39_blocking[".NCSearch"]=false;
	
	$tdataproject39_blocking[".dpType"]= DP_NONE;	



$tdataproject39_blocking[".shortTableName"] = "project39_blocking";

$tdataproject39_blocking[".dataSourceTable"] = "project39_blocking";

$tdataproject39_blocking[".strCaption"] = "Project39 Blocking";

$tdataproject39_blocking[".nSecOptions"] = 0;

$tdataproject39_blocking[".nLoginMethod"] = 1;

$tdataproject39_blocking[".recsPerRowList"] = 1;	

$tdataproject39_blocking[".tableGroupBy"] = "0";

$tdataproject39_blocking[".dbType"] = 0;

$tdataproject39_blocking[".mainTableOwnerID"] = "";



$tdataproject39_blocking[".isUseAjaxSuggest"] = true;

$tdataproject39_blocking[".rowHighlite"] = true;


$tdataproject39_blocking[".isGroupSecurity"] = true;

$tdataproject39_blocking[".arrKeyFields"][] = "id";

$tdataproject39_blocking[".basicSearchFieldsArr"][] = "id";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "tablename";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "startdatetime";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "confirmdatetime";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "keys";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "sessionid";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "userid";
$tdataproject39_blocking[".basicSearchFieldsArr"][] = "action";

// use datepicker for search panel
$tdataproject39_blocking[".isUseCalendarForSearch"] = true;

// use timepicker for search panel
$tdataproject39_blocking[".isUseTimeForSearch"] = false;




$tdataproject39_blocking[".isUseInlineJs"] = $tdataproject39_blocking[".isUseInlineAdd"] || $tdataproject39_blocking[".isUseInlineEdit"];

$tdataproject39_blocking[".advSearchFieldsArr"][] = "id";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "tablename";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "startdatetime";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "confirmdatetime";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "keys";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "sessionid";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "userid";
$tdataproject39_blocking[".advSearchFieldsArr"][] = "action";


	

$tdataproject39_blocking[".isDisplayLoading"] = true;


$tdataproject39_blocking[".createLoginPage"] = true;

$tdataproject39_blocking[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdataproject39_blocking[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdataproject39_blocking[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdataproject39_blocking[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdataproject39_blocking[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdataproject39_blocking[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdataproject39_blocking[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdataproject39_blocking[".strOrderBy"] = $gstrOrderBy;
	
$tdataproject39_blocking[".orderindexes"] = array();

$tdataproject39_blocking[".sqlHead"] = "SELECT id,   tablename,   startdatetime,   confirmdatetime,   `keys`,   sessionid,   userid,   `action`";

$tdataproject39_blocking[".sqlFrom"] = "FROM project39_blocking";

$tdataproject39_blocking[".sqlWhereExpr"] = "";

$tdataproject39_blocking[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdataproject39_blocking[".Keys"]=$keys;

	
//	id
	$fdata = array();
	 $fdata["Label"]="Id"; 
	
	
	$fdata["FieldType"]= 3;
		$fdata["AutoInc"]=true;
			$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "id";
		$fdata["FullName"]= "id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["id"]=$fdata;
	
/*	$advSearchFieldArr[] = "id";		
*/	
	
	
//	tablename
	$fdata = array();
	 $fdata["Label"]="Tablename"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "tablename";
		$fdata["FullName"]= "tablename";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["tablename"]=$fdata;
	
/*	$advSearchFieldArr[] = "tablename";		
*/	
	
	
//	startdatetime
	$fdata = array();
	 $fdata["Label"]="Startdatetime"; 
	
	
	$fdata["FieldType"]= 135;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "startdatetime";
		$fdata["FullName"]= "startdatetime";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	 $fdata["DateEditType"]=13; 
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["startdatetime"]=$fdata;
	
/*	$advSearchFieldArr[] = "startdatetime";		
*/	
	
	
//	confirmdatetime
	$fdata = array();
	 $fdata["Label"]="Confirmdatetime"; 
	
	
	$fdata["FieldType"]= 135;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "confirmdatetime";
		$fdata["FullName"]= "confirmdatetime";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	 $fdata["DateEditType"]=13; 
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["confirmdatetime"]=$fdata;
	
/*	$advSearchFieldArr[] = "confirmdatetime";		
*/	
	
	
//	keys
	$fdata = array();
	 $fdata["Label"]="Keys"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "keys";
		$fdata["FullName"]= "`keys`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["keys"]=$fdata;
	
/*	$advSearchFieldArr[] = "keys";		
*/	
	
	
//	sessionid
	$fdata = array();
	 $fdata["Label"]="Sessionid"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "sessionid";
		$fdata["FullName"]= "sessionid";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["sessionid"]=$fdata;
	
/*	$advSearchFieldArr[] = "sessionid";		
*/	
	
	
//	userid
	$fdata = array();
	 $fdata["Label"]="Userid"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "userid";
		$fdata["FullName"]= "userid";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["userid"]=$fdata;
	
/*	$advSearchFieldArr[] = "userid";		
*/	
	
	
//	action
	$fdata = array();
	 $fdata["Label"]="Action"; 
	
	
	$fdata["FieldType"]= 3;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "action";
		$fdata["FullName"]= "`action`";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject39_blocking["action"]=$fdata;
	
/*	$advSearchFieldArr[] = "action";		
*/	
	
$tables_data["project39_blocking"]=&$tdataproject39_blocking;
$field_labels["project39_blocking"] = &$fieldLabelsproject39_blocking;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["project39_blocking"] = array();

	
// tables which are master tables for current table
$masterTablesData["project39_blocking"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_project39_blocking = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "id, 
tablename, 
startdatetime, 
confirmdatetime, 
`keys`, 
sessionid, 
userid, 
`action`",
	"m_strFrom" => "FROM project39_blocking",
	"m_strWhere" => "",
	"m_strOrderBy" => "",
	"m_strTail" => "",
	"m_where" => new SQLLogicalExpr(array(
"m_sql" => "",
"m_uniontype" => "SQLL_UNKNOWN",
	"m_column" => null,
	"m_contained" => array(
	),
	"m_strCase" => "",
	"m_havingmode" => "0",
	"m_inBrackets" => "0",
))
,
	"m_having" => new SQLLogicalExpr(array(
"m_sql" => "",
"m_uniontype" => "SQLL_UNKNOWN",
	"m_column" => null,
	"m_contained" => array(
	),
	"m_strCase" => "",
	"m_havingmode" => "0",
	"m_inBrackets" => "0",
))
,
	"m_fieldlist" => array(
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "id",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "tablename",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "startdatetime",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "confirmdatetime",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "keys",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "sessionid",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "userid",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "action",
	"m_strTable" => "project39_blocking",
))
,
	"m_alias" => "",
))
,
	),
	"m_fromlist" => array(
												new SQLFromListItem(array(
	"m_link" => "SQLL_MAIN",
		"m_table" => new SQLTable(array(
	"m_strName" => "project39_blocking",
	"m_columns" => array(
	   "id",
	   "tablename",
	   "startdatetime",
	   "confirmdatetime",
	   "keys",
	   "sessionid",
	   "userid",
	   "action",
	),
))
,
	"m_alias" => "",
	"m_joinon" => new SQLLogicalExpr(array(
"m_sql" => "",
"m_uniontype" => "SQLL_UNKNOWN",
	"m_column" => null,
	"m_contained" => array(
	),
	"m_strCase" => "",
	"m_havingmode" => "0",
	"m_inBrackets" => "0",
))
,
))
,
	),
	"m_groupby" => array(
	),
	"m_orderby" => array(
	),
))
;

?>