<?php

//	field labels
$fieldLabelscarsusers = array();
$fieldLabelscarsusers["English"]=array();
$fieldLabelscarsusers["English"]["id"] = "Id";
$fieldLabelscarsusers["English"]["password"] = "Password";
$fieldLabelscarsusers["English"]["username"] = "Username";


$tdatacarsusers=array();
	 $tdatacarsusers[".NumberOfChars"]=80; 
	$tdatacarsusers[".ShortName"]="carsusers";
	$tdatacarsusers[".OwnerID"]="";
	$tdatacarsusers[".OriginalTable"]="carsusers";
	$tdatacarsusers[".NCSearch"]=false;
	
	$tdatacarsusers[".dpType"]= DP_NONE;	



$tdatacarsusers[".shortTableName"] = "carsusers";

$tdatacarsusers[".dataSourceTable"] = "carsusers";

$tdatacarsusers[".strCaption"] = "Carsusers";

$tdatacarsusers[".nSecOptions"] = 0;

$tdatacarsusers[".nLoginMethod"] = 1;

$tdatacarsusers[".recsPerRowList"] = 1;	

$tdatacarsusers[".tableGroupBy"] = "0";

$tdatacarsusers[".dbType"] = 0;

$tdatacarsusers[".mainTableOwnerID"] = "";



$tdatacarsusers[".isUseAjaxSuggest"] = true;

$tdatacarsusers[".rowHighlite"] = true;


$tdatacarsusers[".isGroupSecurity"] = true;

$tdatacarsusers[".arrKeyFields"][] = "id";

$tdatacarsusers[".basicSearchFieldsArr"][] = "id";
$tdatacarsusers[".basicSearchFieldsArr"][] = "password";
$tdatacarsusers[".basicSearchFieldsArr"][] = "username";

// use datepicker for search panel
$tdatacarsusers[".isUseCalendarForSearch"] = false;

// use timepicker for search panel
$tdatacarsusers[".isUseTimeForSearch"] = false;




$tdatacarsusers[".isUseInlineJs"] = $tdatacarsusers[".isUseInlineAdd"] || $tdatacarsusers[".isUseInlineEdit"];

$tdatacarsusers[".advSearchFieldsArr"][] = "id";
$tdatacarsusers[".advSearchFieldsArr"][] = "password";
$tdatacarsusers[".advSearchFieldsArr"][] = "username";


	

$tdatacarsusers[".isDisplayLoading"] = true;


$tdatacarsusers[".createLoginPage"] = true;

$tdatacarsusers[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdatacarsusers[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdatacarsusers[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdatacarsusers[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdatacarsusers[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdatacarsusers[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdatacarsusers[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdatacarsusers[".strOrderBy"] = $gstrOrderBy;
	
$tdatacarsusers[".orderindexes"] = array();

$tdatacarsusers[".sqlHead"] = "SELECT id,  password,  username";

$tdatacarsusers[".sqlFrom"] = "FROM carsusers";

$tdatacarsusers[".sqlWhereExpr"] = "";

$tdatacarsusers[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdatacarsusers[".Keys"]=$keys;

	
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
	$tdatacarsusers["id"]=$fdata;
	
/*	$advSearchFieldArr[] = "id";		
*/	
	
	
//	password
	$fdata = array();
	 $fdata["Label"]="Password"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "password";
		$fdata["FullName"]= "password";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarsusers["password"]=$fdata;
	
/*	$advSearchFieldArr[] = "password";		
*/	
	
	
//	username
	$fdata = array();
	 $fdata["Label"]="Username"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "username";
		$fdata["FullName"]= "username";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarsusers["username"]=$fdata;
	
/*	$advSearchFieldArr[] = "username";		
*/	
	
$tables_data["carsusers"]=&$tdatacarsusers;
$field_labels["carsusers"] = &$fieldLabelscarsusers;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["carsusers"] = array();

	
// tables which are master tables for current table
$masterTablesData["carsusers"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_carsusers = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "id,
password,
username",
	"m_strFrom" => "FROM carsusers",
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
	"m_strTable" => "carsusers",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "password",
	"m_strTable" => "carsusers",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "username",
	"m_strTable" => "carsusers",
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
	"m_strName" => "carsusers",
	"m_columns" => array(
	   "id",
	   "password",
	   "username",
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