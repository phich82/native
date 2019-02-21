<?php

//	field labels
$fieldLabelscarsmodels = array();
$fieldLabelscarsmodels["English"]=array();
$fieldLabelscarsmodels["English"]["id"] = "Id";
$fieldLabelscarsmodels["English"]["make"] = "Make";
$fieldLabelscarsmodels["English"]["model"] = "Model";


$tdatacarsmodels=array();
	 $tdatacarsmodels[".NumberOfChars"]=80; 
	$tdatacarsmodels[".ShortName"]="carsmodels";
	$tdatacarsmodels[".OwnerID"]="";
	$tdatacarsmodels[".OriginalTable"]="carsmodels";
	$tdatacarsmodels[".NCSearch"]=false;
	
	$tdatacarsmodels[".dpType"]= DP_NONE;	



$tdatacarsmodels[".shortTableName"] = "carsmodels";

$tdatacarsmodels[".dataSourceTable"] = "carsmodels";

$tdatacarsmodels[".strCaption"] = "Carsmodels";

$tdatacarsmodels[".nSecOptions"] = 0;

$tdatacarsmodels[".nLoginMethod"] = 1;

$tdatacarsmodels[".recsPerRowList"] = 1;	

$tdatacarsmodels[".tableGroupBy"] = "0";

$tdatacarsmodels[".dbType"] = 0;

$tdatacarsmodels[".mainTableOwnerID"] = "";

$tdatacarsmodels[".exportTo"] = true;

$tdatacarsmodels[".printFriendly"] = true;

$tdatacarsmodels[".isUseAjaxSuggest"] = true;

$tdatacarsmodels[".rowHighlite"] = true;


$tdatacarsmodels[".isGroupSecurity"] = true;

$tdatacarsmodels[".arrKeyFields"][] = "id";

$tdatacarsmodels[".basicSearchFieldsArr"][] = "id";
$tdatacarsmodels[".basicSearchFieldsArr"][] = "make";
$tdatacarsmodels[".basicSearchFieldsArr"][] = "model";

// use datepicker for search panel
$tdatacarsmodels[".isUseCalendarForSearch"] = false;

// use timepicker for search panel
$tdatacarsmodels[".isUseTimeForSearch"] = false;



$tdatacarsmodels[".isUseInlineAdd"] = true;

$tdatacarsmodels[".isUseInlineEdit"] = true;
$tdatacarsmodels[".isUseInlineJs"] = $tdatacarsmodels[".isUseInlineAdd"] || $tdatacarsmodels[".isUseInlineEdit"];

$tdatacarsmodels[".advSearchFieldsArr"][] = "id";
$tdatacarsmodels[".advSearchFieldsArr"][] = "make";
$tdatacarsmodels[".advSearchFieldsArr"][] = "model";


	

$tdatacarsmodels[".isDisplayLoading"] = true;


$tdatacarsmodels[".createLoginPage"] = true;

$tdatacarsmodels[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdatacarsmodels[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdatacarsmodels[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdatacarsmodels[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdatacarsmodels[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdatacarsmodels[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdatacarsmodels[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdatacarsmodels[".strOrderBy"] = $gstrOrderBy;
	
$tdatacarsmodels[".orderindexes"] = array();

$tdatacarsmodels[".sqlHead"] = "SELECT id,   make,   model";

$tdatacarsmodels[".sqlFrom"] = "FROM carsmodels";

$tdatacarsmodels[".sqlWhereExpr"] = "";

$tdatacarsmodels[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdatacarsmodels[".Keys"]=$keys;

	
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
	$tdatacarsmodels["id"]=$fdata;
	
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
	$tdatacarsmodels["make"]=$fdata;
	
/*	$advSearchFieldArr[] = "make";		
*/	
	
	
//	model
	$fdata = array();
	 $fdata["Label"]="Model"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "model";
		$fdata["FullName"]= "model";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarsmodels["model"]=$fdata;
	
/*	$advSearchFieldArr[] = "model";		
*/	
	
$tables_data["carsmodels"]=&$tdatacarsmodels;
$field_labels["carsmodels"] = &$fieldLabelscarsmodels;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["carsmodels"] = array();

	
// tables which are master tables for current table
$masterTablesData["carsmodels"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_carsmodels = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "id, 
make, 
model",
	"m_strFrom" => "FROM carsmodels",
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
	"m_strTable" => "carsmodels",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "make",
	"m_strTable" => "carsmodels",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "model",
	"m_strTable" => "carsmodels",
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
	"m_strName" => "carsmodels",
	"m_columns" => array(
	   "id",
	   "make",
	   "model",
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