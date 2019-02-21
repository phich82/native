<?php

//	field labels
$fieldLabelscarscars = array();
$fieldLabelscarscars["English"]=array();
$fieldLabelscarscars["English"]["category"] = "Category";
$fieldLabelscarscars["English"]["color"] = "Color";
$fieldLabelscarscars["English"]["Date_Listed"] = "Date Listed";
$fieldLabelscarscars["English"]["descr"] = "Descr";
$fieldLabelscarscars["English"]["EPACity"] = "EPACity";
$fieldLabelscarscars["English"]["EPAHighway"] = "EPAHighway";
$fieldLabelscarscars["English"]["features"] = "Features";
$fieldLabelscarscars["English"]["Horsepower"] = "Horsepower";
$fieldLabelscarscars["English"]["id"] = "Id";
$fieldLabelscarscars["English"]["Make"] = "Make";
$fieldLabelscarscars["English"]["Model"] = "Model";
$fieldLabelscarscars["English"]["Phone__"] = "Phone #";
$fieldLabelscarscars["English"]["Picture"] = "Picture";
$fieldLabelscarscars["English"]["Price"] = "Price";
$fieldLabelscarscars["English"]["UserID"] = "User ID";
$fieldLabelscarscars["English"]["YearOfMake"] = "Year Of Make";
$fieldLabelscarscars["English"]["zipcode"] = "Zipcode";


$tdatacarscars=array();
	 $tdatacarscars[".NumberOfChars"]=80; 
	$tdatacarscars[".ShortName"]="carscars";
	$tdatacarscars[".OwnerID"]="";
	$tdatacarscars[".OriginalTable"]="carscars";
	$tdatacarscars[".NCSearch"]=false;
	
	$tdatacarscars[".dpType"]= DP_NONE;	



$tdatacarscars[".shortTableName"] = "carscars";

$tdatacarscars[".dataSourceTable"] = "carscars";

$tdatacarscars[".strCaption"] = "Carscars";

$tdatacarscars[".nSecOptions"] = 0;

$tdatacarscars[".nLoginMethod"] = 1;

$tdatacarscars[".recsPerRowList"] = 1;	

$tdatacarscars[".tableGroupBy"] = "0";

$tdatacarscars[".dbType"] = 0;

$tdatacarscars[".mainTableOwnerID"] = "";

$tdatacarscars[".exportTo"] = true;

$tdatacarscars[".printFriendly"] = true;

$tdatacarscars[".isUseAjaxSuggest"] = true;

$tdatacarscars[".rowHighlite"] = true;


$tdatacarscars[".isGroupSecurity"] = true;

$tdatacarscars[".arrKeyFields"][] = "id";

$tdatacarscars[".basicSearchFieldsArr"][] = "category";
$tdatacarscars[".basicSearchFieldsArr"][] = "color";
$tdatacarscars[".basicSearchFieldsArr"][] = "Date Listed";
$tdatacarscars[".basicSearchFieldsArr"][] = "descr";
$tdatacarscars[".basicSearchFieldsArr"][] = "EPACity";
$tdatacarscars[".basicSearchFieldsArr"][] = "EPAHighway";
$tdatacarscars[".basicSearchFieldsArr"][] = "features";
$tdatacarscars[".basicSearchFieldsArr"][] = "Horsepower";
$tdatacarscars[".basicSearchFieldsArr"][] = "id";
$tdatacarscars[".basicSearchFieldsArr"][] = "Make";
$tdatacarscars[".basicSearchFieldsArr"][] = "Model";
$tdatacarscars[".basicSearchFieldsArr"][] = "Phone #";
$tdatacarscars[".basicSearchFieldsArr"][] = "Price";
$tdatacarscars[".basicSearchFieldsArr"][] = "UserID";
$tdatacarscars[".basicSearchFieldsArr"][] = "YearOfMake";
$tdatacarscars[".basicSearchFieldsArr"][] = "zipcode";

// use datepicker for search panel
$tdatacarscars[".isUseCalendarForSearch"] = true;

// use timepicker for search panel
$tdatacarscars[".isUseTimeForSearch"] = false;



$tdatacarscars[".isUseInlineAdd"] = true;

$tdatacarscars[".isUseInlineEdit"] = true;
$tdatacarscars[".isUseInlineJs"] = $tdatacarscars[".isUseInlineAdd"] || $tdatacarscars[".isUseInlineEdit"];

$tdatacarscars[".advSearchFieldsArr"][] = "category";
$tdatacarscars[".advSearchFieldsArr"][] = "color";
$tdatacarscars[".advSearchFieldsArr"][] = "Date Listed";
$tdatacarscars[".advSearchFieldsArr"][] = "descr";
$tdatacarscars[".advSearchFieldsArr"][] = "EPACity";
$tdatacarscars[".advSearchFieldsArr"][] = "EPAHighway";
$tdatacarscars[".advSearchFieldsArr"][] = "features";
$tdatacarscars[".advSearchFieldsArr"][] = "Horsepower";
$tdatacarscars[".advSearchFieldsArr"][] = "id";
$tdatacarscars[".advSearchFieldsArr"][] = "Make";
$tdatacarscars[".advSearchFieldsArr"][] = "Model";
$tdatacarscars[".advSearchFieldsArr"][] = "Phone #";
$tdatacarscars[".advSearchFieldsArr"][] = "Price";
$tdatacarscars[".advSearchFieldsArr"][] = "UserID";
$tdatacarscars[".advSearchFieldsArr"][] = "YearOfMake";
$tdatacarscars[".advSearchFieldsArr"][] = "zipcode";


	

$tdatacarscars[".isDisplayLoading"] = true;


$tdatacarscars[".createLoginPage"] = true;

$tdatacarscars[".menuTablesArr"][] = array("shortTName"=>"carsmake","dataSourceTName"=>"carsmake","nType"=>0);
$tdatacarscars[".menuTablesArr"][] = array("shortTName"=>"carsmodels","dataSourceTName"=>"carsmodels","nType"=>0);
$tdatacarscars[".menuTablesArr"][] = array("shortTName"=>"carsusers","dataSourceTName"=>"carsusers","nType"=>0);
$tdatacarscars[".menuTablesArr"][] = array("shortTName"=>"project41_audit","dataSourceTName"=>"project41_audit","nType"=>0);
$tdatacarscars[".menuTablesArr"][] = array("shortTName"=>"carscars","dataSourceTName"=>"carscars","nType"=>0);
 	





$tdatacarscars[".subQueriesSupp"] = $bSubqueriesSupported; 

$tdatacarscars[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdatacarscars[".strOrderBy"] = $gstrOrderBy;
	
$tdatacarscars[".orderindexes"] = array();

$tdatacarscars[".sqlHead"] = "SELECT category,  color,  `Date Listed`,  descr,  EPACity,  EPAHighway,  features,  Horsepower,  id,  Make,  Model,  `Phone #`,  Picture,  Price,  UserID,  YearOfMake,  zipcode";

$tdatacarscars[".sqlFrom"] = "FROM carscars";

$tdatacarscars[".sqlWhereExpr"] = "";

$tdatacarscars[".sqlTail"] = "";



	$keys=array();
	$keys[]="id";
	$tdatacarscars[".Keys"]=$keys;

	
//	category
	$fdata = array();
	 $fdata["Label"]="Category"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "category";
		$fdata["FullName"]= "category";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["category"]=$fdata;
	
/*	$advSearchFieldArr[] = "category";		
*/	
	
	
//	color
	$fdata = array();
	 $fdata["Label"]="Color"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "color";
		$fdata["FullName"]= "color";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["color"]=$fdata;
	
/*	$advSearchFieldArr[] = "color";		
*/	
	
	
//	Date Listed
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 7;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Date_Listed";
		$fdata["FullName"]= "`Date Listed`";
	
	
	
	
	$fdata["Index"]= 3;
	 $fdata["DateEditType"]=13; 
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Date Listed"]=$fdata;
	
/*	$advSearchFieldArr[] = "Date Listed";		
*/	
	
	
//	descr
	$fdata = array();
	 $fdata["Label"]="Descr"; 
	
	
	$fdata["FieldType"]= 201;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "descr";
		$fdata["FullName"]= "descr";
	
	
	
	
	$fdata["Index"]= 4;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["descr"]=$fdata;
	
/*	$advSearchFieldArr[] = "descr";		
*/	
	
	
//	EPACity
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "EPACity";
		$fdata["FullName"]= "EPACity";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["EPACity"]=$fdata;
	
/*	$advSearchFieldArr[] = "EPACity";		
*/	
	
	
//	EPAHighway
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "EPAHighway";
		$fdata["FullName"]= "EPAHighway";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["EPAHighway"]=$fdata;
	
/*	$advSearchFieldArr[] = "EPAHighway";		
*/	
	
	
//	features
	$fdata = array();
	 $fdata["Label"]="Features"; 
	
	
	$fdata["FieldType"]= 201;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "features";
		$fdata["FullName"]= "features";
	
	
	
	
	$fdata["Index"]= 7;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["features"]=$fdata;
	
/*	$advSearchFieldArr[] = "features";		
*/	
	
	
//	Horsepower
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Horsepower";
		$fdata["FullName"]= "Horsepower";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Horsepower"]=$fdata;
	
/*	$advSearchFieldArr[] = "Horsepower";		
*/	
	
	
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
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["id"]=$fdata;
	
/*	$advSearchFieldArr[] = "id";		
*/	
	
	
//	Make
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Make";
		$fdata["FullName"]= "Make";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Make"]=$fdata;
	
/*	$advSearchFieldArr[] = "Make";		
*/	
	
	
//	Model
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Model";
		$fdata["FullName"]= "Model";
	
	
	
	
	$fdata["Index"]= 11;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Model"]=$fdata;
	
/*	$advSearchFieldArr[] = "Model";		
*/	
	
	
//	Phone #
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Phone__";
		$fdata["FullName"]= "`Phone #`";
	
	
	
	
	$fdata["Index"]= 12;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Phone #"]=$fdata;
	
/*	$advSearchFieldArr[] = "Phone #";		
*/	
	
	
//	Picture
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 128;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Database image";
	$fdata["ViewFormat"]= "Database Image";
	
	
		
				$fdata["ImageWidth"] = 0;
	$fdata["ImageHeight"] = 0;
		$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Picture";
		$fdata["FullName"]= "Picture";
	
	
	
	
	$fdata["Index"]= 13;
	
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Picture"]=$fdata;
	
/**/	
	
	
//	Price
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Price";
		$fdata["FullName"]= "Price";
	
	
	
	
	$fdata["Index"]= 14;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["Price"]=$fdata;
	
/*	$advSearchFieldArr[] = "Price";		
*/	
	
	
//	UserID
	$fdata = array();
	 $fdata["Label"]="User ID"; 
	
	
	$fdata["FieldType"]= 200;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "UserID";
		$fdata["FullName"]= "UserID";
	
	
	
	
	$fdata["Index"]= 15;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["UserID"]=$fdata;
	
/*	$advSearchFieldArr[] = "UserID";		
*/	
	
	
//	YearOfMake
	$fdata = array();
	 $fdata["Label"]="Year Of Make"; 
	
	
	$fdata["FieldType"]= 3;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "YearOfMake";
		$fdata["FullName"]= "YearOfMake";
	
	
	
	
	$fdata["Index"]= 16;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["YearOfMake"]=$fdata;
	
/*	$advSearchFieldArr[] = "YearOfMake";		
*/	
	
	
//	zipcode
	$fdata = array();
	 $fdata["Label"]="Zipcode"; 
	
	
	$fdata["FieldType"]= 3;
				$fdata["UseiBox"] = false;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "zipcode";
		$fdata["FullName"]= "zipcode";
	
	
	
	
	$fdata["Index"]= 17;
	
			$fdata["EditParams"]="";
			 $fdata["bListPage"]=true; 
				$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatacarscars["zipcode"]=$fdata;
	
/*	$advSearchFieldArr[] = "zipcode";		
*/	
	
$tables_data["carscars"]=&$tdatacarscars;
$field_labels["carscars"] = &$fieldLabelscarscars;



// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table
$detailsTablesData["carscars"] = array();

	
// tables which are master tables for current table
$masterTablesData["carscars"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(GetAbsoluteFileName("classes/sql.php"));











$queryData_carscars = new SQLQuery(array(
	"m_strHead" => "SELECT",
	"m_strFieldList" => "category,
color,
`Date Listed`,
descr,
EPACity,
EPAHighway,
features,
Horsepower,
id,
Make,
Model,
`Phone #`,
Picture,
Price,
UserID,
YearOfMake,
zipcode",
	"m_strFrom" => "FROM carscars",
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
	"m_strName" => "category",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "color",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Date Listed",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "descr",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "EPACity",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "EPAHighway",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "features",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Horsepower",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "id",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Make",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Model",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Phone #",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Picture",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "Price",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "UserID",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "YearOfMake",
	"m_strTable" => "carscars",
))
,
	"m_alias" => "",
))
,
												new SQLFieldListItem(array(
		"m_expr" => new SQLField(array(
	"m_strName" => "zipcode",
	"m_strTable" => "carscars",
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
	"m_strName" => "carscars",
	"m_columns" => array(
	   "category",
	   "color",
	   "Date Listed",
	   "descr",
	   "EPACity",
	   "EPAHighway",
	   "features",
	   "Horsepower",
	   "id",
	   "Make",
	   "Model",
	   "Phone #",
	   "Picture",
	   "Price",
	   "UserID",
	   "YearOfMake",
	   "zipcode",
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