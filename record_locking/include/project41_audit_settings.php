<?php

//	field labels
$fieldLabelsproject41_audit = array();
$fieldLabelsproject41_audit["English"]=array();
$fieldLabelsproject41_audit["English"]["id"] = "Id";
$fieldLabelsproject41_audit["English"]["datetime"] = "Datetime";
$fieldLabelsproject41_audit["English"]["ip"] = "Ip";
$fieldLabelsproject41_audit["English"]["user"] = "User";
$fieldLabelsproject41_audit["English"]["table"] = "Table";
$fieldLabelsproject41_audit["English"]["action"] = "Action";
$fieldLabelsproject41_audit["English"]["description"] = "Description";


$tdataproject41_audit=array();
	 $tdataproject41_audit[".NumberOfChars"]=80; 
	$tdataproject41_audit[".ShortName"]="project41_audit";
	$tdataproject41_audit[".OwnerID"]="";
	$tdataproject41_audit[".OriginalTable"]="project41_audit";
	$tdataproject41_audit[".NCSearch"]=false;
	
	$tdataproject41_audit[".dpType"]= DP_NONE;	



$tdataproject41_audit[".shortTableName"] = "project41_audit";

$tdataproject41_audit[".dataSourceTable"] = "project41_audit";

$tdataproject41_audit[".strCaption"] = "Project41 Audit";

$tdataproject41_audit[".nSecOptions"] = 0;

$tdataproject41_audit[".nLoginMethod"] = 1;

$tdataproject41_audit[".recsPerRowList"] = 1;	

$tdataproject41_audit[".tableGroupBy"] = "0";

$tdataproject41_audit[".dbType"] = 0;

$tdataproject41_audit[".mainTableOwnerID"] = "";



$tdataproject41_audit[".isUseAjaxSuggest"] = true;

$tdataproject41_audit[".rowHighlite"] = true;


$tdataproject41_audit[".isGroupSecurity"] = true;

$tdataproject41_audit[".arrKeyFields"][] = "id";

$tdataproject41_audit[".basicSearchFieldsArr"][] = "id";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "datetime";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "ip";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "user";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "table";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "action";
$tdataproject41_audit[".basicSearchFieldsArr"][] = "description";

// use datepicker for search panel
$tdataproject41_audit[".isUseCalendarForSearch"] = true;

// use timepicker for search panel
$tdataproject41_audit[".isUseTimeForSearch"] = false;




$tdataproject41_audit[".isUseInlineJs"] = $tdataproject41_audit[".isUseInlineAdd"] || $tdataproject41_audit[".isUseInlineEdit"];

$tdataproject41_audit[".advSearchFieldsArr"][] = "id";
$tdataproject41_audit[".advSearchFieldsArr"][] = "datetime";
$tdataproject41_audit[".advSearchFieldsArr"][] = "ip";
$tdataproject41_audit[".advSearchFieldsArr"][] = "user";
$tdataproject41_audit[".advSearchFieldsArr"][] = "table";
$tdataproject41_audit[".advSearchFieldsArr"][] = "action";
$tdataproject41_audit[".advSearchFieldsArr"][] = "description";


	

$tdataproject41_audit[".isDisplayLoading"] = true;


$tdataproject41_audit[".createLoginPage"] = true;

$tdataproject41_audit[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdataproject41_audit[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdataproject41_audit[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdataproject41_audit[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdataproject41_audit[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdataproject41_audit[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdataproject41_audit[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdataproject41_audit[".strOrderBy"] = $gstrOrderBy;
	
$tdataproject41_audit[".orderindexes"] = array();

$tdataproject41_audit[".sqlHead"] = "SELECT id,   datetime,   ip,   `user`,   `table`,   `action`,   description";

$tdataproject41_audit[".sqlFrom"] = "FROM project41_audit";

$tdataproject41_audit[".sqlWhereExpr"] = "";

$tdataproject41_audit[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdataproject41_audit[".Keys"]=$keys;

	
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
	$tdataproject41_audit["id"]=$fdata;
	
/*	$advSearchFieldArr[] = "id";		
*/	
	
	
//	datetime
	$fdata = array();
	 $fdata["Label"]="Datetime"; 
	
	
	$fdata["FieldType"]= 135;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "datetime";
		$fdata["FullName"]= "datetime";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	 $fdata["DateEditType"]=13; 
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["datetime"]=$fdata;
	
/*	$advSearchFieldArr[] = "datetime";		
*/	
	
	
//	ip
	$fdata = array();
	 $fdata["Label"]="Ip"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ip";
		$fdata["FullName"]= "ip";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=15";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["ip"]=$fdata;
	
/*	$advSearchFieldArr[] = "ip";		
*/	
	
	
//	user
	$fdata = array();
	 $fdata["Label"]="User"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "user";
		$fdata["FullName"]= "`user`";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["user"]=$fdata;
	
/*	$advSearchFieldArr[] = "user";		
*/	
	
	
//	table
	$fdata = array();
	 $fdata["Label"]="Table"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "table";
		$fdata["FullName"]= "`table`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["table"]=$fdata;
	
/*	$advSearchFieldArr[] = "table";		
*/	
	
	
//	action
	$fdata = array();
	 $fdata["Label"]="Action"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "action";
		$fdata["FullName"]= "`action`";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=250";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["action"]=$fdata;
	
/*	$advSearchFieldArr[] = "action";		
*/	
	
	
//	description
	$fdata = array();
	 $fdata["Label"]="Description"; 
	
	
	$fdata["FieldType"]= 201;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "description";
		$fdata["FullName"]= "description";
	
	
	
	
	$fdata["Index"]= 7;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataproject41_audit["description"]=$fdata;
	
/*	$advSearchFieldArr[] = "description";		
*/	
	
$tables_data["project41_audit"]=&$tdataproject41_audit;
$field_labels["project41_audit"] = &$fieldLabelsproject41_audit;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["project41_audit"] = array();

	
// tables which are master tables for current table
$masterTablesData["project41_audit"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_project41_audit = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "id, 
datetime, 
ip, 
`user`, 
`table`, 
`action`, 
description",
	"m_strFrom" => "FROM project41_audit",
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
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "datetime",
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "ip",
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "user",
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "table",
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "action",
	"m_strTable" => "project41_audit",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "description",
	"m_strTable" => "project41_audit",
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
	"m_strName" => "project41_audit",
	"m_columns" => array(
	   "id",
	   "datetime",
	   "ip",
	   "user",
	   "table",
	   "action",
	   "description",
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