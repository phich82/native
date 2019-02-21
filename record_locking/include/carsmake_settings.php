<?php

//	field labels
$fieldLabelscarsmake = array();
$fieldLabelscarsmake["English"]=array();
$fieldLabelscarsmake["English"]["id"] = "Id";
$fieldLabelscarsmake["English"]["make"] = "Make";


$tdatacarsmake=array();
	 $tdatacarsmake[".NumberOfChars"]=80; 
	$tdatacarsmake[".ShortName"]="carsmake";
	$tdatacarsmake[".OwnerID"]="";
	$tdatacarsmake[".OriginalTable"]="carsmake";
	$tdatacarsmake[".NCSearch"]=false;
	
	$tdatacarsmake[".dpType"]= DP_NONE;	



$tdatacarsmake[".shortTableName"] = "carsmake";

$tdatacarsmake[".dataSourceTable"] = "carsmake";

$tdatacarsmake[".strCaption"] = "Carsmake";

$tdatacarsmake[".nSecOptions"] = 0;

$tdatacarsmake[".nLoginMethod"] = 1;

$tdatacarsmake[".recsPerRowList"] = 1;	

$tdatacarsmake[".tableGroupBy"] = "0";

$tdatacarsmake[".dbType"] = 0;

$tdatacarsmake[".mainTableOwnerID"] = "";

$tdatacarsmake[".exportTo"] = true;

$tdatacarsmake[".printFriendly"] = true;

$tdatacarsmake[".isUseAjaxSuggest"] = true;

$tdatacarsmake[".rowHighlite"] = true;


$tdatacarsmake[".isGroupSecurity"] = true;

$tdatacarsmake[".arrKeyFields"][] = "id";

$tdatacarsmake[".basicSearchFieldsArr"][] = "id";
$tdatacarsmake[".basicSearchFieldsArr"][] = "make";

// use datepicker for search panel
$tdatacarsmake[".isUseCalendarForSearch"] = false;

// use timepicker for search panel
$tdatacarsmake[".isUseTimeForSearch"] = false;



$tdatacarsmake[".isUseInlineAdd"] = true;

$tdatacarsmake[".isUseInlineEdit"] = true;
$tdatacarsmake[".isUseInlineJs"] = $tdatacarsmake[".isUseInlineAdd"] || $tdatacarsmake[".isUseInlineEdit"];

$tdatacarsmake[".advSearchFieldsArr"][] = "id";
$tdatacarsmake[".advSearchFieldsArr"][] = "make";


	

$tdatacarsmake[".isDisplayLoading"] = true;


$tdatacarsmake[".createLoginPage"] = true;

$tdatacarsmake[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdatacarsmake[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdatacarsmake[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdatacarsmake[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdatacarsmake[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdatacarsmake[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdatacarsmake[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdatacarsmake[".strOrderBy"] = $gstrOrderBy;
	
$tdatacarsmake[".orderindexes"] = array();

$tdatacarsmake[".sqlHead"] = "SELECT id,  make";

$tdatacarsmake[".sqlFrom"] = "FROM carsmake";

$tdatacarsmake[".sqlWhereExpr"] = "";

$tdatacarsmake[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdatacarsmake[".Keys"]=$keys;

	
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
	$tdatacarsmake["id"]=$fdata;
	
/*	$advSearchFieldArr[] = "id";		
*/	
	
	
//	make
	$fdata = array();
	 $fdata["Label"]="Make"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "make";
		$fdata["FullName"]= "make";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarsmake["make"]=$fdata;
	
/*	$advSearchFieldArr[] = "make";		
*/	
	
$tables_data["carsmake"]=&$tdatacarsmake;
$field_labels["carsmake"] = &$fieldLabelscarsmake;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["carsmake"] = array();

	
// tables which are master tables for current table
$masterTablesData["carsmake"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_carsmake = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "id,
make",
	"m_strFrom" => "FROM carsmake",
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
	"m_strTable" => "carsmake",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "make",
	"m_strTable" => "carsmake",
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
	"m_strName" => "carsmake",
	"m_columns" => array(
	   "id",
	   "make",
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