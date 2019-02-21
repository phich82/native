<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

include("include/dbcommon.php");
include("include/carscars_variables.php");
include('include/xtempl.php');
include('classes/runnerpage.php');

//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readavalues=false;

$keys=array();
$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;

if(@$_REQUEST["editType"]=="inline")
	$inlineadd=ADD_INLINE;
elseif(@$_REQUEST["editType"]=="onthefly")
	$inlineadd=ADD_ONTHEFLY;
elseif(@$_REQUEST["editType"]=="dpinline")
	$inlineadd=DP_INLINE;
else
	$inlineadd=ADD_SIMPLE;

if($inlineadd==ADD_INLINE)
	$templatefile = "carscars_inline_add.htm";
else
	$templatefile = "carscars_add.htm";

if($inlineadd==ADD_ONTHEFLY)
	$id=postvalue("id");	
elseif($inlineadd==ADD_INLINE)
	$id=postvalue("recordID");
else
	$id = 1;

//If undefined session value for mastet table, but exist post value master table, than take second
//It may be happen only when use dpInline mode on page add
if(!@$_SESSION[$strTableName."_mastertable"] && postvalue("mastertable"))
	$_SESSION[$strTableName."_mastertable"] = postvalue("mastertable");
//Get detail table keys	
$detailKeys = array();
$detailKeys = GetDetailKeysByMasterTable($_SESSION[$strTableName."_mastertable"], $strTableName);	

$xt = new Xtempl();
	
// assign an id		
$xt->assign("id",$id);
$formname="editform".$id;
	
//array of params for classes
$params = array("pageType" => PAGE_ADD,"id" => $id,"mode" => $inlineadd);

$params["calendar"] = true;
$params['tName'] = $strTableName;
$params['includes_js']=$includes_js;
$params['includes_jsreq']=$includes_jsreq;
$params['includes_css']=$includes_css;
$params['locale_info']=$locale_info;

$pageObject = new RunnerPage($params);

$dpParams = array();
if(isShowDetailTable() && ($inlineadd==ADD_SIMPLE || $inlineadd==DP_INLINE))
{
	$ids = $id;
	if($inlineadd==ADD_SIMPLE)
	{
		$pageObject->AddJSCode("window.dpObj = new dpInlineOnAddEdit({
			'mTableName':'".jsreplace($strTableName)."',
			'mShortTableName':'carscars', 
			'mForm':$('#".$formname."'),
			'mPageType':'".PAGE_ADD."',
			'mMessage':'', 
			'mId':".$id.",
			'ext':'php',
			'dMessages':'', 
			'dCaptions':[],
			'dInlineObjs':[]});
			window.dpInline".$id." = new detailsPreviewInline({'pageId':".$id.",'mode':'simple_add'}); 
			window.dpInline".$id.".createPreviewIframe();");
		$pageObject->AddJSFile("detailspreview");
	}
}

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
	$blobfields=array();
	$files_move=array();
	$files_save=array();
//	processing category - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_category_".$id);
	$type=postvalue("type_category_".$id);
	if (FieldSubmitted("category_".$id))
	{
		$value=prepare_for_db("category",$value,$type);
/*
		$value=prepare_for_db("category",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="category";
		$avalues["category"]=$value;
	}
	}
//	processibng category - end
//	processing color - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_color_".$id);
	$type=postvalue("type_color_".$id);
	if (FieldSubmitted("color_".$id))
	{
		$value=prepare_for_db("color",$value,$type);
/*
		$value=prepare_for_db("color",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="color";
		$avalues["color"]=$value;
	}
	}
//	processibng color - end
//	processing Date Listed - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Date_Listed_".$id);
	$type=postvalue("type_Date_Listed_".$id);
	if (FieldSubmitted("Date Listed_".$id))
	{
		$value=prepare_for_db("Date Listed",$value,$type);
/*
		$value=prepare_for_db("Date Listed",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Date Listed";
		$avalues["Date Listed"]=$value;
	}
	}
//	processibng Date Listed - end
//	processing descr - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_descr_".$id);
	$type=postvalue("type_descr_".$id);
	if (FieldSubmitted("descr_".$id))
	{
		$value=prepare_for_db("descr",$value,$type);
/*
		$value=prepare_for_db("descr",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="descr";
		$avalues["descr"]=$value;
	}
	}
//	processibng descr - end
//	processing EPACity - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_EPACity_".$id);
	$type=postvalue("type_EPACity_".$id);
	if (FieldSubmitted("EPACity_".$id))
	{
		$value=prepare_for_db("EPACity",$value,$type);
/*
		$value=prepare_for_db("EPACity",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="EPACity";
		$avalues["EPACity"]=$value;
	}
	}
//	processibng EPACity - end
//	processing EPAHighway - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_EPAHighway_".$id);
	$type=postvalue("type_EPAHighway_".$id);
	if (FieldSubmitted("EPAHighway_".$id))
	{
		$value=prepare_for_db("EPAHighway",$value,$type);
/*
		$value=prepare_for_db("EPAHighway",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="EPAHighway";
		$avalues["EPAHighway"]=$value;
	}
	}
//	processibng EPAHighway - end
//	processing features - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_features_".$id);
	$type=postvalue("type_features_".$id);
	if (FieldSubmitted("features_".$id))
	{
		$value=prepare_for_db("features",$value,$type);
/*
		$value=prepare_for_db("features",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="features";
		$avalues["features"]=$value;
	}
	}
//	processibng features - end
//	processing Horsepower - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Horsepower_".$id);
	$type=postvalue("type_Horsepower_".$id);
	if (FieldSubmitted("Horsepower_".$id))
	{
		$value=prepare_for_db("Horsepower",$value,$type);
/*
		$value=prepare_for_db("Horsepower",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Horsepower";
		$avalues["Horsepower"]=$value;
	}
	}
//	processibng Horsepower - end
//	processing Make - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Make_".$id);
	$type=postvalue("type_Make_".$id);
	if (FieldSubmitted("Make_".$id))
	{
		$value=prepare_for_db("Make",$value,$type);
/*
		$value=prepare_for_db("Make",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Make";
		$avalues["Make"]=$value;
	}
	}
//	processibng Make - end
//	processing Model - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Model_".$id);
	$type=postvalue("type_Model_".$id);
	if (FieldSubmitted("Model_".$id))
	{
		$value=prepare_for_db("Model",$value,$type);
/*
		$value=prepare_for_db("Model",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Model";
		$avalues["Model"]=$value;
	}
	}
//	processibng Model - end
//	processing Phone # - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Phone___".$id);
	$type=postvalue("type_Phone___".$id);
	if (FieldSubmitted("Phone #_".$id))
	{
		$value=prepare_for_db("Phone #",$value,$type);
/*
		$value=prepare_for_db("Phone #",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Phone #";
		$avalues["Phone #"]=$value;
	}
	}
//	processibng Phone # - end
//	processing Picture - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Picture_".$id);
	$type=postvalue("type_Picture_".$id);
	if (FieldSubmitted("Picture_".$id))
	{
			$fileNameForPrepareFunc = "";
		if(substr($type,0,4)=="file")
		{		
			$value = prepare_file($value,"Picture",$type,$fileNameForPrepareFunc ,$id);
		}
		else if(substr($type,0,6)=="upload")
		{		
			$value=prepare_upload("Picture",$type,$fileNameForPrepareFunc,$value,"" ,$id);
		}
			
/*
		$value=prepare_for_db("Picture",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Picture";
		$avalues["Picture"]=$value;
	}
	}
//	processibng Picture - end
//	processing Price - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_Price_".$id);
	$type=postvalue("type_Price_".$id);
	if (FieldSubmitted("Price_".$id))
	{
		$value=prepare_for_db("Price",$value,$type);
/*
		$value=prepare_for_db("Price",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="Price";
		$avalues["Price"]=$value;
	}
	}
//	processibng Price - end
//	processing UserID - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_UserID_".$id);
	$type=postvalue("type_UserID_".$id);
	if (FieldSubmitted("UserID_".$id))
	{
		$value=prepare_for_db("UserID",$value,$type);
/*
		$value=prepare_for_db("UserID",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="UserID";
		$avalues["UserID"]=$value;
	}
	}
//	processibng UserID - end
//	processing YearOfMake - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_YearOfMake_".$id);
	$type=postvalue("type_YearOfMake_".$id);
	if (FieldSubmitted("YearOfMake_".$id))
	{
		$value=prepare_for_db("YearOfMake",$value,$type);
/*
		$value=prepare_for_db("YearOfMake",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="YearOfMake";
		$avalues["YearOfMake"]=$value;
	}
	}
//	processibng YearOfMake - end
//	processing zipcode - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_zipcode_".$id);
	$type=postvalue("type_zipcode_".$id);
	if (FieldSubmitted("zipcode_".$id))
	{
		$value=prepare_for_db("zipcode",$value,$type);
/*
		$value=prepare_for_db("zipcode",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="zipcode";
		$avalues["zipcode"]=$value;
	}
	}
//	processibng zipcode - end



	$failed_inline_add=false;
//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$usermessage,$inlineadd);
	if($retval)
	{
		if(DoInsertRecord($strOriginalTableName,$avalues,$blobfields))
		{
			$IsSaved=true;
//	after edit event
			if(isAuditEnable($strTableName) || function_exists("AfterAdd"))
			{
				foreach($keys as $idx=>$val)
					$avalues[$idx]=$val;
			}
			
			if(isAuditEnable($strTableName))
				$audit->LogAdd($strTableName,$avalues,$keys);

			if(function_exists("AfterAdd"))
				AfterAdd($avalues,$keys,$inlineadd);
		}
	}
	else
	{
		$message = $usermessage;
		$status="DECLINED";
		$readavalues=true;
	}
}
$message = "<div class=message>".$message."</div>";

// PRG rule, to avoid POSTDATA resend
//if ($inlineedit==ADD_SIMPLE && @$_POST["a"]=="added"){
if (no_output_done() && $inlineadd==ADD_SIMPLE && $IsSaved)
{
	// saving message
	$_SESSION["message"] = ($message ? $message : "");
	// redirect
	header("Location: carscars_".$pageObject->getPageType().".php");
	// turned on output buffering, so we need to stop script
	exit();
}
if($inlineadd==DP_INLINE && $IsSaved)
	$_SESSION["message"] = ($message ? $message : "");
// for PRG rule, to avoid POSTDATA resend. Saving mess in session
if($inlineadd==ADD_SIMPLE && isset($_SESSION["message"]))
{
	$message = $_SESSION["message"];
	unset($_SESSION["message"]);
}

$defvalues=array();

//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
	if(!$defvalues)
		$defvalues=array();
//	clear key fields
	$defvalues["id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else
{
}

if($readavalues)
{
	$defvalues["category"]=@$avalues["category"];
	$defvalues["color"]=@$avalues["color"];
	$defvalues["Date Listed"]=@$avalues["Date Listed"];
	$defvalues["descr"]=@$avalues["descr"];
	$defvalues["EPACity"]=@$avalues["EPACity"];
	$defvalues["EPAHighway"]=@$avalues["EPAHighway"];
	$defvalues["features"]=@$avalues["features"];
	$defvalues["Horsepower"]=@$avalues["Horsepower"];
	$defvalues["Make"]=@$avalues["Make"];
	$defvalues["Model"]=@$avalues["Model"];
	$defvalues["Phone #"]=@$avalues["Phone #"];
	$defvalues["Price"]=@$avalues["Price"];
	$defvalues["UserID"]=@$avalues["UserID"];
	$defvalues["YearOfMake"]=@$avalues["YearOfMake"];
	$defvalues["zipcode"]=@$avalues["zipcode"];
}
//for basic files
$includes="";
if ($inlineadd!==ADD_INLINE && $inlineadd!=ADD_ONTHEFLY)
	$pageObject->addJSCode("AddEventForControl('".jsreplace($strTableName)."', '', ".$id.");\r\n");

		
	
$onsubmit = $pageObject->onSubmitForEditingPage($formname);
	

//////////////////////////////////////////////////////////////////	
////////////////////// time picker
//////////////////////
$body=array();
$pageObject->AddJSFile('customlabels');
if(isset($params["calendar"]))
	$pageObject->AddJSFile("calendar");
if($inlineadd!=ADD_INLINE)
{
	if($inlineadd!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
		$pageObject->AddJSFile("ajaxsuggest");		
		$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
		$includes.="<div id=\"search_suggest\"></div>\r\n";
	}
	$xt->assign("category_fieldblock",true);
	$xt->assign("category_label",true);
	if(isEnableSection508())
		$xt->assign_section("category_label","<label for=\"".GetInputElementId("category", $id)."\">","</label>");
	$xt->assign("color_fieldblock",true);
	$xt->assign("color_label",true);
	if(isEnableSection508())
		$xt->assign_section("color_label","<label for=\"".GetInputElementId("color", $id)."\">","</label>");
	$xt->assign("Date_Listed_fieldblock",true);
	$xt->assign("Date_Listed_label",true);
	if(isEnableSection508())
		$xt->assign_section("Date_Listed_label","<label for=\"".GetInputElementId("Date Listed", $id)."\">","</label>");
	$xt->assign("descr_fieldblock",true);
	$xt->assign("descr_label",true);
	if(isEnableSection508())
		$xt->assign_section("descr_label","<label for=\"".GetInputElementId("descr", $id)."\">","</label>");
	$xt->assign("EPACity_fieldblock",true);
	$xt->assign("EPACity_label",true);
	if(isEnableSection508())
		$xt->assign_section("EPACity_label","<label for=\"".GetInputElementId("EPACity", $id)."\">","</label>");
	$xt->assign("EPAHighway_fieldblock",true);
	$xt->assign("EPAHighway_label",true);
	if(isEnableSection508())
		$xt->assign_section("EPAHighway_label","<label for=\"".GetInputElementId("EPAHighway", $id)."\">","</label>");
	$xt->assign("features_fieldblock",true);
	$xt->assign("features_label",true);
	if(isEnableSection508())
		$xt->assign_section("features_label","<label for=\"".GetInputElementId("features", $id)."\">","</label>");
	$xt->assign("Horsepower_fieldblock",true);
	$xt->assign("Horsepower_label",true);
	if(isEnableSection508())
		$xt->assign_section("Horsepower_label","<label for=\"".GetInputElementId("Horsepower", $id)."\">","</label>");
	$xt->assign("Make_fieldblock",true);
	$xt->assign("Make_label",true);
	if(isEnableSection508())
		$xt->assign_section("Make_label","<label for=\"".GetInputElementId("Make", $id)."\">","</label>");
	$xt->assign("Model_fieldblock",true);
	$xt->assign("Model_label",true);
	if(isEnableSection508())
		$xt->assign_section("Model_label","<label for=\"".GetInputElementId("Model", $id)."\">","</label>");
	$xt->assign("Phone___fieldblock",true);
	$xt->assign("Phone___label",true);
	if(isEnableSection508())
		$xt->assign_section("Phone___label","<label for=\"".GetInputElementId("Phone #", $id)."\">","</label>");
	$xt->assign("Picture_fieldblock",true);
	$xt->assign("Picture_label",true);
	if(isEnableSection508())
		$xt->assign_section("Picture_label","<label for=\"".GetInputElementId("Picture", $id)."\">","</label>");
	$xt->assign("Price_fieldblock",true);
	$xt->assign("Price_label",true);
	if(isEnableSection508())
		$xt->assign_section("Price_label","<label for=\"".GetInputElementId("Price", $id)."\">","</label>");
	$xt->assign("UserID_fieldblock",true);
	$xt->assign("UserID_label",true);
	if(isEnableSection508())
		$xt->assign_section("UserID_label","<label for=\"".GetInputElementId("UserID", $id)."\">","</label>");
	$xt->assign("YearOfMake_fieldblock",true);
	$xt->assign("YearOfMake_label",true);
	if(isEnableSection508())
		$xt->assign_section("YearOfMake_label","<label for=\"".GetInputElementId("YearOfMake", $id)."\">","</label>");
	$xt->assign("zipcode_fieldblock",true);
	$xt->assign("zipcode_label",true);
	if(isEnableSection508())
		$xt->assign_section("zipcode_label","<label for=\"".GetInputElementId("zipcode", $id)."\">","</label>");
	
	if($onsubmit)
		$onsubmit="onsubmit=\"".htmlspecialchars($onsubmit)."\"";
	if($inlineadd!=ADD_ONTHEFLY)
	{
		$body["begin"]=$includes.
		'<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carscars_add.php" '.$onsubmit.'>'.
		'<input type=hidden name="a" value="added">'.
		(isShowDetailTable() ? '<input type=hidden name="editType" value="dpinline">' : '');
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='carscars_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$body["begin"]='<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carscars_add.php" '.$onsubmit.' target="flyframe'.$id.'">'.
		'<input type=hidden name="a" value="added">'.
		'<input type=hidden name="editType" value="onthefly">'.
		'<input type=hidden name="table" value="'.postvalue('table').'">'.
		'<input type=hidden name="field" value="'.postvalue('field').'">'.
		'<input type=hidden name="category" value="'.postvalue('category').'">'.
		'<input type=hidden name="id" value="'.$id.'">';
		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".$id."');\"");
		$xt->assign("cancel_button",true);
		$xt->assign("header","");
	}
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	
	// reset button handler
	$resetEditors = "Runner.controls.ControlManager.resetControlsForTable('".htmlspecialchars(jsreplace($strTableName))."');";
	$xt->assign("resetbutton_attrs",'onclick="'.$resetEditors.'" onmouseover="this.focus();"');
}

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
//$xt->assign("status",$status);

$readonlyfields=array();

//	show readonly fields
$linkdata="";

if(!$inlineadd==ADD_INLINE) 
	$pageObject->AddJSCode("SetToFirstControl('".$formname."');");
	
if(@$_POST["a"]=="added" && $inlineadd==ADD_ONTHEFLY && !$error_happened && $status!="DECLINED")
{
	$LookupSQL="";
	if($LookupSQL)
		$LookupSQL.=" from ".AddTableWrappers($strOriginalTableName);

	$data=0;
	if(count($keys) && $LookupSQL)
	{
		$where=KeyWhere($keys);
		$LookupSQL.=" where ".$where;
		$rs=db_query($LookupSQL,$conn);
		$data=db_fetch_numarray($rs);
	}
	if(!$data)
	{
		$data=array(@$avalues[$linkfield],@$avalues[$dispfield]);
	}
	echo "<textarea id=\"data\">";
	echo "added";
	print_inline_array($data);
	echo "</textarea>";
	exit();
}

if(@$_POST["a"]=="added" && $inlineadd==ADD_INLINE) 
{
	//Preparation   view values
	//	get current values and show edit controls
	$data=0;
	if(count($keys))
	{
		$where=KeyWhere($keys);
			$strSQL = gSQLWhere($where);
		LogInfo($strSQL);
		$rs=db_query($strSQL,$conn);
		$data=db_fetch_array($rs);
	}
	if(!$data)
	{
		$data=$avalues;
		$HaveData=false;
	}
	//check if correct values added

	$showKeys[] = htmlspecialchars($keys["id"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["id"]));

//	foreach Fields as @f filter @f.bListPage order @f.nListPageOrder

	////////////////////////////////////////////
	//	category - 
		$value="";
				$value = ProcessLargeText(GetData($data,"category", ""),"field=category".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "category";
				$showRawValues[] = substr($data["category"],0,100);
	////////////////////////////////////////////
	//	color - 
		$value="";
				$value = ProcessLargeText(GetData($data,"color", ""),"field=color".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "color";
				$showRawValues[] = substr($data["color"],0,100);
	////////////////////////////////////////////
	//	Date Listed - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"Date Listed", "Short Date"),"field=Date+Listed".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Date_Listed";
				$showRawValues[] = substr($data["Date Listed"],0,100);
	////////////////////////////////////////////
	//	descr - 
		$value="";
				$value = ProcessLargeText(GetData($data,"descr", ""),"field=descr".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "descr";
				$showRawValues[] = substr($data["descr"],0,100);
	////////////////////////////////////////////
	//	EPACity - 
		$value="";
				$value = ProcessLargeText(GetData($data,"EPACity", ""),"field=EPACity".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "EPACity";
				$showRawValues[] = substr($data["EPACity"],0,100);
	////////////////////////////////////////////
	//	EPAHighway - 
		$value="";
				$value = ProcessLargeText(GetData($data,"EPAHighway", ""),"field=EPAHighway".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "EPAHighway";
				$showRawValues[] = substr($data["EPAHighway"],0,100);
	////////////////////////////////////////////
	//	features - 
		$value="";
				$value = ProcessLargeText(GetData($data,"features", ""),"field=features".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "features";
				$showRawValues[] = substr($data["features"],0,100);
	////////////////////////////////////////////
	//	Horsepower - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Horsepower", ""),"field=Horsepower".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Horsepower";
				$showRawValues[] = substr($data["Horsepower"],0,100);
	////////////////////////////////////////////
	//	id - 
		$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "id";
				$showRawValues[] = substr($data["id"],0,100);
	////////////////////////////////////////////
	//	Make - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Make", ""),"field=Make".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Make";
				$showRawValues[] = substr($data["Make"],0,100);
	////////////////////////////////////////////
	//	Model - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Model", ""),"field=Model".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Model";
				$showRawValues[] = substr($data["Model"],0,100);
	////////////////////////////////////////////
	//	Phone # - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Phone #", ""),"field=Phone+%23".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Phone__";
				$showRawValues[] = substr($data["Phone #"],0,100);
	////////////////////////////////////////////
	//	Picture - Database Image
		$value="";
								$value = "<img";
			if(isEnableSection508())
				$value.= " alt=\"Image from DB\"";
									$value.=" id=\"img_Picture_".$id."\" border=0";
			$value.=" src=\"carscars_imager.php?field=Picture".$keylink."\">";
		$showValues[] = $value;
		$showFields[] = "Picture";
				$showRawValues[] = "";
	////////////////////////////////////////////
	//	Price - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Price", ""),"field=Price".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Price";
				$showRawValues[] = substr($data["Price"],0,100);
	////////////////////////////////////////////
	//	UserID - 
		$value="";
				$value = ProcessLargeText(GetData($data,"UserID", ""),"field=UserID".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "UserID";
				$showRawValues[] = substr($data["UserID"],0,100);
	////////////////////////////////////////////
	//	YearOfMake - 
		$value="";
				$value = ProcessLargeText(GetData($data,"YearOfMake", ""),"field=YearOfMake".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "YearOfMake";
				$showRawValues[] = substr($data["YearOfMake"],0,100);
	////////////////////////////////////////////
	//	zipcode - 
		$value="";
				$value = ProcessLargeText(GetData($data,"zipcode", ""),"field=zipcode".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "zipcode";
				$showRawValues[] = substr($data["zipcode"],0,100);

	echo "<textarea id=\"data\">";
	if($IsSaved && count($showValues))
	{
		if($HaveData)
			echo "saved";
		else
			echo "savnd";
		print_inline_array($showKeys);
		echo "\n";
		print_inline_array($showValues);
		echo "\n";
		print_inline_array($showFields);
		echo "\n";
		print_inline_array($showRawValues);
		echo "\n";
		print_inline_array($showDetailKeys,true);
		echo "\n";
		print_inline_array($showDetailKeys);
		echo "\n";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$usermessage);
	}
	else
	{
		if($status=="DECLINED")
			echo "decli";
		else
			echo "error";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$message);
	}
	echo "</textarea>";
	exit();
} 

/////////////////////////////////////////////////////////////
if($inlineadd==DP_INLINE)
{
	echo "<textarea id=\"data\">";
	$code = "window.dpObj.Opts.mMessage =\"".$message."\";";
	if(($_POST["a"]=="added" && $IsSaved))
	{
		for($i=0;$i<count($dpParams['ids']);$i++)
		{
			$data=0;
			if(count($keys))
			{
				$where=KeyWhere($keys);
							$strSQL = gSQLWhere($where);
				LogInfo($strSQL);
				$rs=db_query($strSQL,$conn);
				$data=db_fetch_array($rs);
			}
			if(!$data)
				$data=$avalues;
				
			$code .= "var obj = window.inlineEditing".$dpParams['ids'][$i].";
					  if(obj && obj.isSbmSuc){obj.mKeys = [";
			foreach($mKeys[$dpParams['shortTableNames'][$i]] as $mk)
				$code .= "'".jsreplace($data[$mk])."',";
			$code = substr($code, 0, -1);
			$code .= "];}";
		}
		$code .= "window.dpObj.saveAllDetail()";
	}
	elseif(@$_REQUEST["isSbmSuc"]==='0')
		$code .= "window.dpObj.saveAllDetail()";
	else
		$code .= "window.dpObj.showMessage('error');";	
	echo $code."</textarea>";
	exit();
}


/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
//	validation stuff
$regex='';
$regexmessage='';
$regextype = '';
//	control - category
$control_category=array();
$control_category["func"]="xt_buildeditcontrol";
$control_category["params"] = array();
$control_category["params"]["field"]="category";
$control_category["params"]["value"]=@$defvalues["category"];

//	Begin Add validation
$arrValidate = array();	
$control_category["params"]["validate"]=$arrValidate;
//	End Add validation

$control_category["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_category["params"]["mode"]="inline_add";
else
	$control_category["params"]["mode"]="add";
if(!$detailKeys || !in_array("category", $detailKeys))
	$xt->assignbyref("category_editcontrol",$control_category);
else if(!in_array("category", $defvalues)) 	
		$xt->assignbyref("category_editcontrol",$defvalues['category']);
//	control - color
$control_color=array();
$control_color["func"]="xt_buildeditcontrol";
$control_color["params"] = array();
$control_color["params"]["field"]="color";
$control_color["params"]["value"]=@$defvalues["color"];

//	Begin Add validation
$arrValidate = array();	
$control_color["params"]["validate"]=$arrValidate;
//	End Add validation

$control_color["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_color["params"]["mode"]="inline_add";
else
	$control_color["params"]["mode"]="add";
if(!$detailKeys || !in_array("color", $detailKeys))
	$xt->assignbyref("color_editcontrol",$control_color);
else if(!in_array("color", $defvalues)) 	
		$xt->assignbyref("color_editcontrol",$defvalues['color']);
//	control - Date_Listed
$control_Date_Listed=array();
$control_Date_Listed["func"]="xt_buildeditcontrol";
$control_Date_Listed["params"] = array();
$control_Date_Listed["params"]["field"]="Date Listed";
$control_Date_Listed["params"]["value"]=@$defvalues["Date Listed"];

//	Begin Add validation
$arrValidate = array();	
$control_Date_Listed["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Date_Listed["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Date_Listed["params"]["mode"]="inline_add";
else
	$control_Date_Listed["params"]["mode"]="add";
if(!$detailKeys || !in_array("Date_Listed", $detailKeys))
	$xt->assignbyref("Date_Listed_editcontrol",$control_Date_Listed);
else if(!in_array("Date_Listed", $defvalues)) 	
		$xt->assignbyref("Date_Listed_editcontrol",$defvalues['Date_Listed']);
//	control - descr
$control_descr=array();
$control_descr["func"]="xt_buildeditcontrol";
$control_descr["params"] = array();
$control_descr["params"]["field"]="descr";
$control_descr["params"]["value"]=@$defvalues["descr"];

//	Begin Add validation
$arrValidate = array();	
$control_descr["params"]["validate"]=$arrValidate;
//	End Add validation

$control_descr["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_descr["params"]["mode"]="inline_add";
else
	$control_descr["params"]["mode"]="add";
if(!$detailKeys || !in_array("descr", $detailKeys))
	$xt->assignbyref("descr_editcontrol",$control_descr);
else if(!in_array("descr", $defvalues)) 	
		$xt->assignbyref("descr_editcontrol",$defvalues['descr']);
//	control - EPACity
$control_EPACity=array();
$control_EPACity["func"]="xt_buildeditcontrol";
$control_EPACity["params"] = array();
$control_EPACity["params"]["field"]="EPACity";
$control_EPACity["params"]["value"]=@$defvalues["EPACity"];

//	Begin Add validation
$arrValidate = array();	
$control_EPACity["params"]["validate"]=$arrValidate;
//	End Add validation

$control_EPACity["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_EPACity["params"]["mode"]="inline_add";
else
	$control_EPACity["params"]["mode"]="add";
if(!$detailKeys || !in_array("EPACity", $detailKeys))
	$xt->assignbyref("EPACity_editcontrol",$control_EPACity);
else if(!in_array("EPACity", $defvalues)) 	
		$xt->assignbyref("EPACity_editcontrol",$defvalues['EPACity']);
//	control - EPAHighway
$control_EPAHighway=array();
$control_EPAHighway["func"]="xt_buildeditcontrol";
$control_EPAHighway["params"] = array();
$control_EPAHighway["params"]["field"]="EPAHighway";
$control_EPAHighway["params"]["value"]=@$defvalues["EPAHighway"];

//	Begin Add validation
$arrValidate = array();	
$control_EPAHighway["params"]["validate"]=$arrValidate;
//	End Add validation

$control_EPAHighway["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_EPAHighway["params"]["mode"]="inline_add";
else
	$control_EPAHighway["params"]["mode"]="add";
if(!$detailKeys || !in_array("EPAHighway", $detailKeys))
	$xt->assignbyref("EPAHighway_editcontrol",$control_EPAHighway);
else if(!in_array("EPAHighway", $defvalues)) 	
		$xt->assignbyref("EPAHighway_editcontrol",$defvalues['EPAHighway']);
//	control - features
$control_features=array();
$control_features["func"]="xt_buildeditcontrol";
$control_features["params"] = array();
$control_features["params"]["field"]="features";
$control_features["params"]["value"]=@$defvalues["features"];

//	Begin Add validation
$arrValidate = array();	
$control_features["params"]["validate"]=$arrValidate;
//	End Add validation

$control_features["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_features["params"]["mode"]="inline_add";
else
	$control_features["params"]["mode"]="add";
if(!$detailKeys || !in_array("features", $detailKeys))
	$xt->assignbyref("features_editcontrol",$control_features);
else if(!in_array("features", $defvalues)) 	
		$xt->assignbyref("features_editcontrol",$defvalues['features']);
//	control - Horsepower
$control_Horsepower=array();
$control_Horsepower["func"]="xt_buildeditcontrol";
$control_Horsepower["params"] = array();
$control_Horsepower["params"]["field"]="Horsepower";
$control_Horsepower["params"]["value"]=@$defvalues["Horsepower"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$control_Horsepower["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Horsepower["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Horsepower["params"]["mode"]="inline_add";
else
	$control_Horsepower["params"]["mode"]="add";
if(!$detailKeys || !in_array("Horsepower", $detailKeys))
	$xt->assignbyref("Horsepower_editcontrol",$control_Horsepower);
else if(!in_array("Horsepower", $defvalues)) 	
		$xt->assignbyref("Horsepower_editcontrol",$defvalues['Horsepower']);
//	control - Make
$control_Make=array();
$control_Make["func"]="xt_buildeditcontrol";
$control_Make["params"] = array();
$control_Make["params"]["field"]="Make";
$control_Make["params"]["value"]=@$defvalues["Make"];

//	Begin Add validation
$arrValidate = array();	
$control_Make["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Make["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Make["params"]["mode"]="inline_add";
else
	$control_Make["params"]["mode"]="add";
if(!$detailKeys || !in_array("Make", $detailKeys))
	$xt->assignbyref("Make_editcontrol",$control_Make);
else if(!in_array("Make", $defvalues)) 	
		$xt->assignbyref("Make_editcontrol",$defvalues['Make']);
//	control - Model
$control_Model=array();
$control_Model["func"]="xt_buildeditcontrol";
$control_Model["params"] = array();
$control_Model["params"]["field"]="Model";
$control_Model["params"]["value"]=@$defvalues["Model"];

//	Begin Add validation
$arrValidate = array();	
$control_Model["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Model["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Model["params"]["mode"]="inline_add";
else
	$control_Model["params"]["mode"]="add";
if(!$detailKeys || !in_array("Model", $detailKeys))
	$xt->assignbyref("Model_editcontrol",$control_Model);
else if(!in_array("Model", $defvalues)) 	
		$xt->assignbyref("Model_editcontrol",$defvalues['Model']);
//	control - Phone__
$control_Phone__=array();
$control_Phone__["func"]="xt_buildeditcontrol";
$control_Phone__["params"] = array();
$control_Phone__["params"]["field"]="Phone #";
$control_Phone__["params"]["value"]=@$defvalues["Phone #"];

//	Begin Add validation
$arrValidate = array();	
$control_Phone__["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Phone__["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Phone__["params"]["mode"]="inline_add";
else
	$control_Phone__["params"]["mode"]="add";
if(!$detailKeys || !in_array("Phone__", $detailKeys))
	$xt->assignbyref("Phone___editcontrol",$control_Phone__);
else if(!in_array("Phone__", $defvalues)) 	
		$xt->assignbyref("Phone___editcontrol",$defvalues['Phone__']);
//	control - Picture
$control_Picture=array();
$control_Picture["func"]="xt_buildeditcontrol";
$control_Picture["params"] = array();
$control_Picture["params"]["field"]="Picture";
$control_Picture["params"]["value"]=@$defvalues["Picture"];

//	Begin Add validation
$arrValidate = array();	
$control_Picture["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Picture["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Picture["params"]["mode"]="inline_add";
else
	$control_Picture["params"]["mode"]="add";
if(!$detailKeys || !in_array("Picture", $detailKeys))
	$xt->assignbyref("Picture_editcontrol",$control_Picture);
else if(!in_array("Picture", $defvalues)) 	
		$xt->assignbyref("Picture_editcontrol",$defvalues['Picture']);
//	control - Price
$control_Price=array();
$control_Price["func"]="xt_buildeditcontrol";
$control_Price["params"] = array();
$control_Price["params"]["field"]="Price";
$control_Price["params"]["value"]=@$defvalues["Price"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$control_Price["params"]["validate"]=$arrValidate;
//	End Add validation

$control_Price["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_Price["params"]["mode"]="inline_add";
else
	$control_Price["params"]["mode"]="add";
if(!$detailKeys || !in_array("Price", $detailKeys))
	$xt->assignbyref("Price_editcontrol",$control_Price);
else if(!in_array("Price", $defvalues)) 	
		$xt->assignbyref("Price_editcontrol",$defvalues['Price']);
//	control - UserID
$control_UserID=array();
$control_UserID["func"]="xt_buildeditcontrol";
$control_UserID["params"] = array();
$control_UserID["params"]["field"]="UserID";
$control_UserID["params"]["value"]=@$defvalues["UserID"];

//	Begin Add validation
$arrValidate = array();	
$control_UserID["params"]["validate"]=$arrValidate;
//	End Add validation

$control_UserID["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_UserID["params"]["mode"]="inline_add";
else
	$control_UserID["params"]["mode"]="add";
if(!$detailKeys || !in_array("UserID", $detailKeys))
	$xt->assignbyref("UserID_editcontrol",$control_UserID);
else if(!in_array("UserID", $defvalues)) 	
		$xt->assignbyref("UserID_editcontrol",$defvalues['UserID']);
//	control - YearOfMake
$control_YearOfMake=array();
$control_YearOfMake["func"]="xt_buildeditcontrol";
$control_YearOfMake["params"] = array();
$control_YearOfMake["params"]["field"]="YearOfMake";
$control_YearOfMake["params"]["value"]=@$defvalues["YearOfMake"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$control_YearOfMake["params"]["validate"]=$arrValidate;
//	End Add validation

$control_YearOfMake["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_YearOfMake["params"]["mode"]="inline_add";
else
	$control_YearOfMake["params"]["mode"]="add";
if(!$detailKeys || !in_array("YearOfMake", $detailKeys))
	$xt->assignbyref("YearOfMake_editcontrol",$control_YearOfMake);
else if(!in_array("YearOfMake", $defvalues)) 	
		$xt->assignbyref("YearOfMake_editcontrol",$defvalues['YearOfMake']);
//	control - zipcode
$control_zipcode=array();
$control_zipcode["func"]="xt_buildeditcontrol";
$control_zipcode["params"] = array();
$control_zipcode["params"]["field"]="zipcode";
$control_zipcode["params"]["value"]=@$defvalues["zipcode"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$control_zipcode["params"]["validate"]=$arrValidate;
//	End Add validation

$control_zipcode["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_zipcode["params"]["mode"]="inline_add";
else
	$control_zipcode["params"]["mode"]="add";
if(!$detailKeys || !in_array("zipcode", $detailKeys))
	$xt->assignbyref("zipcode_editcontrol",$control_zipcode);
else if(!in_array("zipcode", $defvalues)) 	
		$xt->assignbyref("zipcode_editcontrol",$defvalues['zipcode']);
$pageObject->AddJSFile("runnerJS/Runner");
$pageObject->AddJSFile("runnerJS/Event", "runnerJS/Runner");
$pageObject->AddJSFile("runnerJS/Validate","runnerJS/Event");
$pageObject->AddJSFile('runnerJS/ControlManager','runnerJS/Validate');
$pageObject->AddJSFile("runnerJS/Control","runnerJS/ControlManager");
$pageObject->AddJSFile("runnerJS/TextAreaControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/TextFieldControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/TimeFieldControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/RteControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/FileControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/DateFieldControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/RadioControl", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/LookupWizard", "runnerJS/Control");
$pageObject->AddJSFile("runnerJS/DropDown", "runnerJS/LookupWizard");
$pageObject->AddJSFile("runnerJS/CheckBox", "runnerJS/LookupWizard");
$pageObject->AddJSFile("runnerJS/TextFieldLookup", "runnerJS/LookupWizard");
$pageObject->AddJSFile("runnerJS/EditBoxLookup", "runnerJS/TextFieldLookup");
$pageObject->AddJSFile("runnerJS/ListPageLookup", "runnerJS/TextFieldLookup");
$pageObject->AddJSFile("runnerJS/ControlsEventHandler", "runnerJS/Control");

/////////////////////////////////////////////////////////////
if(isShowDetailTable())
{
	$options = array();
	//array of params for classes
	$options["mode"] = DP_INLINE;
	$options["pageType"] = PAGE_LIST;
	$options["masterPageType"] = PAGE_ADD;
	$options['masterTable'] = $strTableName;
	$options['firstTime'] = 1;
	$listPageObjects = array();
	for($d=0;$d<count($dpParams['ids']);$d++)
	{
		include("include/".$dpParams['shortTableNames'][$d]."_settings.php");
		$strTableName = $dpParams['strTableNames'][$d];
		if(!$d)
		{
			include('classes/listpage.php');
			include('classes/listpage_embed.php');
			include('classes/listpage_dpinline.php');
			include("classes/searchclause.php");
		}
		$options['xt'] = new Xtempl();
		$options['id'] = $dpParams['ids'][$d];

		$listPageObject = &ListPage::createListPage($strTableName,$options);
		// prepare code
		$listPageObject->prepareForBuildPage();
		
		if($listPageObject->isDispGrid())
		{
			$listJsFiles = array();
			$listCssFiles = array();
			
			//Add Detail's js code to master's code
			$pageObject->AddJSCode("\n /*---Begin code for detailsPreview_".$options['id']."---*/ \n".
									$listPageObject->grabAllJsCode().
									"\n /*---End code for detailsPreview_".$options['id']."---*/ \n");
			
			//Add detail's js files to master's files
			$listJsFiles = $listPageObject->grabAllJSFiles();
			for($i=0;$i<count($listJsFiles);$i++)
				$pageObject->AddJSFile($listJsFiles[$i]);
			
			//Add detail's css files to master's files	
			$listCssFiles = $listPageObject->grabAllCSSFiles();	
			for($i=0;$i<count($listCssFiles);$i++)
				$pageObject->AddCSSFile($listCssFiles[$i]);
		}
		
		$listPageObjects[] = $listPageObject;
	}	
	$strTableName = "carscars";
}
/////////////////////////////////////////////////////////////

$jscode = $pageObject->PrepareJS();
if($inlineadd!=ADD_ONTHEFLY || $inlineadd!=DP_INLINE)
{
	if($inlineadd==ADD_INLINE)
	{
		$jscode=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$jscode);
		$xt->assignbyref("linkdata",$jscode);
	}
	$body["end"]="</form><script>".$jscode."</script>";
	$xt->assign("body",$body);
	$xt->assign("flybody",true);
}
else
{
	if(!@$_POST["a"]=="added")
	{
		$jscode = str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$jscode);
		echo $jscode;
		echo "\n";
	}
	else if(@$_POST["a"]=="added" && ($error_happened || $status=="DECLINED"))
	{
		echo "<textarea id=\"data\">decli";
		echo htmlspecialchars($jscode);
		echo "</textarea>";
	}
	$body["end"]="</form>";
	$xt->assign("footer","");
	$xt->assign("flybody",$body);
	$xt->assign("body",true);
}	

$xt->assign("html_attrs","lang=\"en\"");

$xt->assign("style_block",true);


if(function_exists("BeforeShowAdd"))
	BeforeShowAdd($xt,$templatefile);

if($inlineadd==ADD_ONTHEFLY)
{
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("flybody");
}
else
	$xt->display($templatefile);

/////////////////////////////////////////////////////////////////////
if(isShowDetailTable())
{	
	for($d=0;$d<count($listPageObjects);$d++)
	{
		$strTableName = $dpParams['strTableNames'][$d];
		// show page
		if($listPageObjects[$d]->isDispGrid())
			$listPageObjects[$d]->showPage();	
	}
}
/////////////////////////////////////////////////////////////////////	
?>
