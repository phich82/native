<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

include("include/dbcommon.php");
include("include/carscars_variables.php");
include('include/xtempl.php');
include('classes/runnerpage.php');

/////////////////////////////////////////////////////////////
//	check if logged in
/////////////////////////////////////////////////////////////
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if($_REQUEST["action"]!="")
{
	if(isBlockingEnable($strTableName))
	{
		$arrkeys = array();
		$arrtmp = array();
		$skeys=split("&",refine($_REQUEST["keys"]));
		foreach($skeys as $ind=>$val)
		{
			$arrtmp=split("=",refine($val));
			$arrkeys[$ind]=urldecode($arrtmp[1]);
		}
			
		if($_REQUEST["action"]=="unblock")
		{
			$block->UnblockRecord($strTableName,$arrkeys,$_REQUEST["oldid"]);
			if(IsAdmin() || $_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMINGROUP)
			{
				if($_REQUEST["param"]!="")
					$block->UnblockAdmin($strTableName,$arrkeys);
				if($_REQUEST["param"]=="no")
					echo "unlock";
				if($_REQUEST["param"]=="yes")
				{
					$block->BlockRecord($strTableName,$arrkeys);
					echo "block";
				}
			}
		}
		if($_REQUEST["action"]=="confirm")
				$block->ConfirmBlock($strTableName,$arrkeys);
	}
	exit();	
}

/////////////////////////////////////////////////////////////
//init variables
/////////////////////////////////////////////////////////////
$query = $queryData_carscars->Copy();

$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readevalues=false;
$bodyonload="";
$key=array();
$next=array();
$prev=array();

$body=array();
$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
	
$inlineedit = (postvalue("editType")=="inline") ? true : false;
$templatefile = ( $inlineedit ) ? "carscars_inline_edit.htm" : "carscars_edit.htm";

//Get detail table keys	
$detailKeys = array();
$detailKeys = GetDetailKeysByMasterTable($_SESSION[$strTableName."_mastertable"], $strTableName);	

$xt = new Xtempl();

if(postvalue("recordID"))
	$id = postvalue("recordID");
else 
	$id =1;
// assign an id		
$xt->assign("id",$id);
$formname="editform".$id;

//array of params for classes
$params = array("pageType" => PAGE_EDIT,"id" => $id,"mode" => $inlineedit);

$params["calendar"] = true;
$params['tName'] = $strTableName;
$params['includes_js']=$includes_js;
$params['includes_jsreq']=$includes_jsreq;
$params['includes_css']=$includes_css;
$params['locale_info']=$locale_info;

$pageObject = new RunnerPage($params);

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$skeys="";
$keys["id"]=postvalue("editid1");
$skeys.=rawurlencode("editid1=".postvalue("editid1"))."&";
if($skeys!="")
	$skeys=substr($skeys,0,-1);
	
$dpParams = array();
if(isShowDetailTable() && !$inlineedit)
{
	$ids = $id;
	$pageObject->AddJSCode("window.dpObj = new dpInlineOnAddEdit({
			'mTableName':'".jsreplace($strTableName)."',
			'mForm':$('#".$formname."'),
			'mPageType':'".PAGE_EDIT."',
			'dMessages':'',
			'dCaptions':[],			
			'dInlineObjs':[]});");		
	$pageObject->AddJSFile("detailspreview");
}	
	
/////////////////////////
//Blocking recors
/////////////////////////

$disableCtrlsForEditing = "false";
$xt->assign("system_attrs","style='visibility:hidden;'");


if(isBlockingEnable($strTableName))
{
	$disableCtrlsForEditing = $block->BlockRecord($strTableName,$keys);
	if($disableCtrlsForEditing=="true")
	{
		if($inlineedit)
		{
			if(IsAdmin() || $_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMINGROUP)
				echo "block".$block->GetBlockInfo($strTableName,$keys,false);
			else
				echo "block".$block->BlockUser;
			exit();
		}
		$xt->assign("system_attrs","style='visibility:visible';");
		$xt->assign("centerblock_attrs","style='margin-top:60px';");
		$xt->assign("system_message",$block->BlockUser);
	}

	if(IsAdmin() || $_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMINGROUP)
	{
		$rb=$block->GetBlockInfo($strTableName,$keys,true);
		if($rb!="")
		{
			$xt->assign("system_attrs","style='visibility:visible';");
			$xt->assign("centerblock_attrs","style='margin-top:60px';");
			$xt->assign("system_message",$rb);
		}
	}
}

/////////////////////////////////////////////////////////////
//	process entered data, read and save
/////////////////////////////////////////////////////////////

if(@$_POST["a"]=="edited")
{
	$query->Where()->AddFilterByKeys($keys);
		$strWhereClause = $query->Where()->toSql($query);
	if(function_exists("AfterEdit") || function_exists("BeforeEdit") || isAuditEnable($strTableName))
	{
		//	read old values
		$rsold=db_query($query->toSql(), $conn);
		$dataold=db_fetch_array($rsold);
	}
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
	$files_move=array();
	$files_save=array();
	$blobfields=array();

	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_category_".$id);
	$type=postvalue("type_category_".$id);
	if(FieldSubmitted("category_".$id))
	{
		
		$value=prepare_for_db("category",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["category"]=$value;
	}


//	processibng category - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_color_".$id);
	$type=postvalue("type_color_".$id);
	if(FieldSubmitted("color_".$id))
	{
		
		$value=prepare_for_db("color",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["color"]=$value;
	}


//	processibng color - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Date_Listed_".$id);
	$type=postvalue("type_Date_Listed_".$id);
	if(FieldSubmitted("Date Listed_".$id))
	{
		
		$value=prepare_for_db("Date Listed",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Date Listed"]=$value;
	}


//	processibng Date Listed - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_descr_".$id);
	$type=postvalue("type_descr_".$id);
	if(FieldSubmitted("descr_".$id))
	{
		
		$value=prepare_for_db("descr",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["descr"]=$value;
	}


//	processibng descr - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_EPACity_".$id);
	$type=postvalue("type_EPACity_".$id);
	if(FieldSubmitted("EPACity_".$id))
	{
		
		$value=prepare_for_db("EPACity",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["EPACity"]=$value;
	}


//	processibng EPACity - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_EPAHighway_".$id);
	$type=postvalue("type_EPAHighway_".$id);
	if(FieldSubmitted("EPAHighway_".$id))
	{
		
		$value=prepare_for_db("EPAHighway",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["EPAHighway"]=$value;
	}


//	processibng EPAHighway - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_features_".$id);
	$type=postvalue("type_features_".$id);
	if(FieldSubmitted("features_".$id))
	{
		
		$value=prepare_for_db("features",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["features"]=$value;
	}


//	processibng features - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Horsepower_".$id);
	$type=postvalue("type_Horsepower_".$id);
	if(FieldSubmitted("Horsepower_".$id))
	{
		
		$value=prepare_for_db("Horsepower",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Horsepower"]=$value;
	}


//	processibng Horsepower - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Make_".$id);
	$type=postvalue("type_Make_".$id);
	if(FieldSubmitted("Make_".$id))
	{
		
		$value=prepare_for_db("Make",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Make"]=$value;
	}


//	processibng Make - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Model_".$id);
	$type=postvalue("type_Model_".$id);
	if(FieldSubmitted("Model_".$id))
	{
		
		$value=prepare_for_db("Model",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Model"]=$value;
	}


//	processibng Model - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Phone___".$id);
	$type=postvalue("type_Phone___".$id);
	if(FieldSubmitted("Phone #_".$id))
	{
		
		$value=prepare_for_db("Phone #",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Phone #"]=$value;
	}


//	processibng Phone # - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Picture_".$id);
	$type=postvalue("type_Picture_".$id);
	if(FieldSubmitted("Picture_".$id))
	{
		
			$fileNameForPrepareFunc = postvalue("filename_Picture_".$id);
		if($fileNameForPrepareFunc)
			$value = $fileNameForPrepareFunc;
		if(substr($type,0,4)=="file")
		{		
			$value = prepare_file($value,"Picture",$type,$fileNameForPrepareFunc ,$id);
		}
		else if(substr($type,0,6)=="upload")
		{		
			$value=prepare_upload("Picture",$type,$fileNameForPrepareFunc,$value,"" ,$id);
		}
			
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$blobfields[]="Picture";
		$evalues["Picture"]=$value;
	}


//	processibng Picture - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_Price_".$id);
	$type=postvalue("type_Price_".$id);
	if(FieldSubmitted("Price_".$id))
	{
		
		$value=prepare_for_db("Price",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["Price"]=$value;
	}


//	processibng Price - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_UserID_".$id);
	$type=postvalue("type_UserID_".$id);
	if(FieldSubmitted("UserID_".$id))
	{
		
		$value=prepare_for_db("UserID",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["UserID"]=$value;
	}


//	processibng UserID - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_YearOfMake_".$id);
	$type=postvalue("type_YearOfMake_".$id);
	if(FieldSubmitted("YearOfMake_".$id))
	{
		
		$value=prepare_for_db("YearOfMake",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["YearOfMake"]=$value;
	}


//	processibng YearOfMake - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_zipcode_".$id);
	$type=postvalue("type_zipcode_".$id);
	if(FieldSubmitted("zipcode_".$id))
	{
		
		$value=prepare_for_db("zipcode",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["zipcode"]=$value;
	}


//	processibng zipcode - end
	}

	foreach($efilename_values as $ekey=>$value)
	{
		$evalues[$ekey]=$value;
	}
	
	if(isBlockingEnable($strTableName))
	{
		if($block->BlockRecord($strTableName,$keys)=="true")
		{
			$xt->assign("system_attrs","style='visibility:visible';");
			$xt->assign("centerblock_attrs","style='margin-top:60px';");
			if(IsAdmin() || $_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMINGROUP)
			{
				$xt->assign("system_message",$block->GetBlockInfo($strTableName,$keys,true));
			}
			else
			{
				$xt->assign("system_message",$block->BlockUser);
			}
			$status="DECLINED";
			$readevalues=true;
		}
	}
	
	if($readevalues==false)
	{
	//	do event
		$retval=true;
		if(function_exists("BeforeEdit"))
		{
			$retval=BeforeEdit($evalues,$strWhereClause,$dataold,$keys,$usermessage,$inlineedit);
		}
		if($retval)
		{		
			if(DoUpdateRecord($strOriginalTableName,$evalues,$blobfields,$strWhereClause))
			{
				$IsSaved=true;
	//	after edit event

				if(isAuditEnable($strTableName) || function_exists("AfterEdit"))
				{
					foreach($dataold as $idx=>$val)
					{
						if(!array_key_exists($idx,$evalues))
						{
							$evalues[$idx]=$val;
						}
					}
				}

				if(isAuditEnable($strTableName))
				{
					$audit->LogEdit($strTableName,$evalues,$dataold,$keys);
				}
				if(function_exists("AfterEdit"))
				{
					AfterEdit($evalues,KeyWhere($keys),$dataold,$keys,$inlineedit);
				}
			}
		}
		else
		{
			$readevalues=true;
			$message = $usermessage;
			$status="DECLINED";
		}
	}
}

$message = "<div class=message>".$message."</div>";

// PRG rule, to avoid POSTDATA resend
if ($IsSaved && no_output_done() && !$inlineedit )
{
	// saving message
	$_SESSION["message"] = ($message ? $message : "");
	// key get query
	$keyGetQ = "";
		$keyGetQ.="editid1=".rawurldecode($keys["id"])."&";
	// cut last &
	$keyGetQ = substr($keyGetQ, 0, strlen($keyGetQ)-1);	
	// redirect
	header("Location: carscars_".$pageObject->getPageType().".php?".$keyGetQ);
	// turned on output buffering, so we need to stop script
	exit();
}
// for PRG rule, to avoid POSTDATA resend. Saving mess in session
if (!$inlineedit && isset($_SESSION["message"])){
	$message = $_SESSION["message"];
	unset($_SESSION["message"]);
}



/////////////////////////////////////////////////////////////
//	read current values from the database
/////////////////////////////////////////////////////////////
$query = $queryData_carscars->Copy();
$query->Where()->AddFilterByKeys($keys);

//$strSQL=gSQLWhere($strWhereClause);
$strWhereClause = $query->Where()->toSql($query);
$strSQL = $query->toSql(""); // do not include 'where' in sql string

$strSQLbak = $strSQL;
//	Before Query event
if(function_exists("BeforeQueryEdit"))
	BeforeQueryEdit($strSQL, $strWhereClause);

if($strSQLbak == $strSQL)
	$strSQL = $query->toSql($strWhereClause);
LogInfo($strSQL);
$rs=db_query($strSQL, $conn);
$data=db_fetch_array($rs);

if(!$data)
{
	if(!$inlineedit)
	{
		header("Location: carscars_list.php?a=return");
		exit();
	}
	else
		$data=array();
}

$readonlyfields=array();


if($readevalues)
{
	$data["category"]=$evalues["category"];
	$data["color"]=$evalues["color"];
	$data["Date Listed"]=$evalues["Date Listed"];
	$data["descr"]=$evalues["descr"];
	$data["EPACity"]=$evalues["EPACity"];
	$data["EPAHighway"]=$evalues["EPAHighway"];
	$data["features"]=$evalues["features"];
	$data["Horsepower"]=$evalues["Horsepower"];
	$data["Make"]=$evalues["Make"];
	$data["Model"]=$evalues["Model"];
	$data["Phone #"]=$evalues["Phone #"];
	$data["Price"]=$evalues["Price"];
	$data["UserID"]=$evalues["UserID"];
	$data["YearOfMake"]=$evalues["YearOfMake"];
	$data["zipcode"]=$evalues["zipcode"];
}
/////////////////////////////////////////////////////////////
//	assign values to $xt class, prepare page for displaying
/////////////////////////////////////////////////////////////
//Basic includes js files
$includes="";
//javascript code
if (!$inlineedit)
	$pageObject->addJSCode("AddEventForControl('".jsreplace($strTableName)."', prevNextButtonHandler,".$id.");\r\n");
	
//event for onsubmit
$onsubmit = $pageObject->onSubmitForEditingPage($formname);

////////////////////// time picker
//////////////////////
$pageObject->AddJSFile("customlabels");
if(isset($params["calendar"]))
	$pageObject->AddJSFile("calendar");
	
	
if(!$inlineedit)
{
	$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
	$pageObject->AddJSFile("ajaxsuggest");	
	$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$includes.="<div id=\"search_suggest".$id."\"></div>\r\n";
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
	
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".htmlspecialchars($onsubmit)."\"";
	$body["begin"]=$includes.'
	<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carscars_edit.php" '.$onsubmit.'>'.
	'<input type="hidden" name="a" value="edited">';
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["id"])."\">";
	$xt->assign("show_key1", htmlspecialchars(GetData($data,"id", "")));
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Begin Next Prev button
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
if(!@$_SESSION[$strTableName."_noNextPrev"])
{
	$where_next="";
	$where_prev="";
	$order_next="";
	$order_prev="";
	$arrFieldForSort=array();
	$arrHowFieldSort=array();
	$where=$_SESSION[$strTableName."_where"];
	
	if(GetFieldIndex("id"))
		$key[]=GetFieldIndex("id");
	
//if session mass sorting empty, then create it as a sheet
	if(@$_SESSION[$strTableName."_arrFieldForSort"] && @$_SESSION[$strTableName."_arrHowFieldSort"])
	{
		$arrFieldForSort=$_SESSION[$strTableName."_arrFieldForSort"];
		$arrHowFieldSort=$_SESSION[$strTableName."_arrHowFieldSort"];
		$lenArr=count($arrFieldForSort);
	}
	else
	{
		if(count($g_orderindexes))
		{
			for($i=0;$i<count($g_orderindexes);$i++)
			{
				$arrFieldForSort[]=$g_orderindexes[$i][0];
				$arrHowFieldSort[]=$g_orderindexes[$i][1];
			}
		}
		elseif($gstrOrderBy!='')
		{
			$_SESSION[$strTableName."_noNextPrev"] = 1;
		}
		
		if(count($key))
		{
			for($i=0;$i<count($key);$i++)
			{
				$idsearch=array_search($key[$i],$arrFieldForSort);
				if($idsearch===false)
				{
					$arrFieldForSort[]=$key[$i];
					$arrHowFieldSort[]="ASC";
				}
			}
		}
		
		$_SESSION[$strTableName."_arrFieldForSort"]=$arrFieldForSort;
		$_SESSION[$strTableName."_arrHowFieldSort"]=$arrHowFieldSort;
		$lenArr=count($arrFieldForSort);
	}
//if session order by empty, then create a line order		
	if(@$_SESSION[$strTableName."_order"])
	{
		$order_next=$_SESSION[$strTableName."_order"];
	}
	elseif($lenArr>0)
	{
		for($i=0;$i<$lenArr;$i++)
		{
			$order_next .=(GetFieldByIndex($arrFieldForSort[$i]) ? ($order_next!="" ? ", " : " ORDER BY ").$arrFieldForSort[$i]." ".$arrHowFieldSort[$i] : "");
		}
	}
//create a line where and order for the two queries
	if($lenArr>0 and count($key) and !$_SESSION[$strTableName."_noNextPrev"])
	{
		if($where)
			$where .=" and ";
		$scob="";
		$flag=0;
		for($i=0;$i<$lenArr;$i++)
		{
			$fieldName=GetFieldByIndex($arrFieldForSort[$i]);
			if($fieldName)
			{
				$order_prev .=($order_prev!="" ? ", " : " ORDER BY ").$arrFieldForSort[$i].($arrHowFieldSort[$i]=="ASC" ? " DESC" : " ASC");
				$dbg=GetFullFieldName($fieldName);
				if(!is_null($data[$fieldName]))
				{
					$mdv=make_db_value($fieldName,$data[$fieldName]);
					$ga=($arrHowFieldSort[$i]=="ASC" ? ">" : "<");
					$gd=($arrHowFieldSort[$i]=="ASC" ? "<" : ">");
					$gasc=$dbg.$ga.$mdv;
					$gdesc=$dbg.$gd.$mdv;
					$gravn=($i!=$lenArr-1 ? $dbg."=".$mdv : "");
					$ganull=($ga=="<" ? " or ".$dbg." IS NULL" : "");
					$gdnull=($gd=="<" ? " or ".$dbg." IS NULL" : "");
				}
				else
				{
					$gasc=($arrHowFieldSort[$i]=="ASC" ? $dbg." IS NOT NULL" : "");
					$gdesc=($arrHowFieldSort[$i]=="ASC" ? "" : $dbg." IS NOT NULL");
					$gravn=($i!=$lenArr-1 ? $dbg." IS NULL" : "");
					$ganull=$gdnull="";
				}
				$where_next .=($where_next!="" ? " and (" : " (").($gasc=="" && $gravn=="" ? " 1=0 " : ($gasc!="" ? $gasc.$ganull : "").($gasc!="" && $gravn!="" ? " or " : "").$gravn." ");
				$where_prev .=($where_prev!="" ? " and (" : " (").($gdesc=="" && $gravn=="" ? " 1=0 " : ($gdesc!="" ? $gdesc.$gdnull : "").($gdesc!="" && $gravn!="" ? " or " : "").$gravn." ");
				$scob .=")";
			}
			else 
				$flag=1;
		}
		$where_next =$where_next.$scob;
		$where_prev =$where_prev.$scob;
		$where_next=whereAdd($where_next,SecuritySQL("Edit"));
		$where_prev=whereAdd($where_prev,SecuritySQL("Edit"));
		if($flag==1)
		{
			$order_next="";
			for($i=0;$i<$lenArr;$i++)
				$order_next .=(GetFieldByIndex($arrFieldForSort[$i]) ? ($order_next!="" ? ", " : " ORDER BY ").$arrFieldForSort[$i]." ".$arrHowFieldSort[$i] : "");
		}
		
		$queryData_carscars->RemoveBlobFields(true);
		//$sql_next=gSQLWhere($where.$where_next).$order_next;
		$sql_next = $queryData_carscars->toSql($where.$where_next, $order_next);
		//$sql_prev=gSQLWhere($where.$where_prev).$order_prev;
		$sql_prev = $queryData_carscars->toSql($where.$where_prev, $order_prev);
		
		if($where_next!="" and $order_next!="" and $where_prev!="" and $order_prev!="")
		{
					$sql_next.=" limit 1";
			$sql_prev.=" limit 1";
		
			$res_next=db_query($sql_next,$conn);		
			$row_next=db_fetch_array($res_next);
		
			$res_prev=db_query($sql_prev,$conn);	
			$row_prev=db_fetch_array($res_prev);
		
			if($res_next)
			{
					$next[1]=$row_next["id"];
			}
		
			if($res_prev)
			{
					$prev[1]=$row_prev["id"];
			}
		}
	}
}
	$nextlink=$prevlink="";
	// reset button handler
	$resetEditors="";
	if(count($next))
	{
		$xt->assign("next_button",true);
				$nextlink .="editid1=".htmlspecialchars(rawurlencode($next[1]));
		$xt->assign("nextbutton_attrs","align=\"absmiddle\" onclick=\"UnblockRecord('carscars_edit.php','".$skeys."','',function(){window.location.href='carscars_edit.php?".$nextlink."'});return false;\"");
		$resetEditors.="$('#next".$id."').attr('style','');$('#next".$id."').attr('disabled','');";
	}
	else 
		$xt->assign("next_button",false);
	if(count($prev))
	{
		$xt->assign("prev_button",true);
				$prevlink .="editid1=".htmlspecialchars(rawurlencode($prev[1]));
		$xt->assign("prevbutton_attrs","align=\"absmiddle\" onclick=\"UnblockRecord('carscars_edit.php','".$skeys."','',function(){window.location.href='carscars_edit.php?".$prevlink."'});return false;\"");
		$resetEditors.="$('#prev".$id."').attr('style','');$('#prev".$id."').attr('disabled','');";
	}
	else 
		$xt->assign("prev_button",false);
	
	$resetEditors .= "Runner.controls.ControlManager.resetControlsForTable('".htmlspecialchars(jsreplace($strTableName))."');";
	$xt->assign("resetbutton_attrs",'onclick="'.$resetEditors.'" onmouseover="this.focus();"');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Next Prev button
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
	$xt->assign("backbutton_attrs","onclick=\"UnblockRecord('carscars_edit.php','".$skeys."','',function(){window.location.href='carscars_list.php?a=return'});return false;\"");
	// onmouseover event, for changing focus. Needed to proper submit form
	$onmouseover = "this.focus();";
	$onmouseover = 'onmouseover="'.$onmouseover.'"';
	
	if($disableCtrlsForEditing=="true")
		$xt->assign("savebutton_attrs","disabled=true style='background-color:#dcdcdc' ".$onmouseover);
	else
		$xt->assign("savebutton_attrs",$onmouseover);
	
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}
$showKeys[] = rawurlencode($keys["id"]);
if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
/////////////////////////////////////////////////////////////
//process readonly and auto-update fields
/////////////////////////////////////////////////////////////
//old way to disabled button prev next
	if(!$inlineedit) 
		$pageObject->AddJSCode($bodyonload."\r\n SetToFirstControl('".$formname."');\r\n");
	
/////////////////////////////////////////////////////////////
//	return new data to the List page or report an error
/////////////////////////////////////////////////////////////
if (postvalue("a")=="edited" && $inlineedit ) 
{
	if(!$data)
	{
		$data=$evalues;
		$HaveData=false;
	}
	//Preparation   view values

//	detail tables

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["id"]));


//	category - 

		$value="";
				$value = ProcessLargeText(GetData($data,"category", ""),"field=category".$keylink,"",MODE_LIST);
//		$smarty->assign("show_category",$value);
		$showValues[] = $value;
		$showFields[] = "category";
				$showRawValues[] = substr($data["category"],0,100);

//	color - 

		$value="";
				$value = ProcessLargeText(GetData($data,"color", ""),"field=color".$keylink,"",MODE_LIST);
//		$smarty->assign("show_color",$value);
		$showValues[] = $value;
		$showFields[] = "color";
				$showRawValues[] = substr($data["color"],0,100);

//	Date Listed - Short Date

		$value="";
				$value = ProcessLargeText(GetData($data,"Date Listed", "Short Date"),"field=Date+Listed".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Date_Listed",$value);
		$showValues[] = $value;
		$showFields[] = "Date_Listed";
				$showRawValues[] = substr($data["Date Listed"],0,100);

//	descr - 

		$value="";
				$value = ProcessLargeText(GetData($data,"descr", ""),"field=descr".$keylink,"",MODE_LIST);
//		$smarty->assign("show_descr",$value);
		$showValues[] = $value;
		$showFields[] = "descr";
				$showRawValues[] = substr($data["descr"],0,100);

//	EPACity - 

		$value="";
				$value = ProcessLargeText(GetData($data,"EPACity", ""),"field=EPACity".$keylink,"",MODE_LIST);
//		$smarty->assign("show_EPACity",$value);
		$showValues[] = $value;
		$showFields[] = "EPACity";
				$showRawValues[] = substr($data["EPACity"],0,100);

//	EPAHighway - 

		$value="";
				$value = ProcessLargeText(GetData($data,"EPAHighway", ""),"field=EPAHighway".$keylink,"",MODE_LIST);
//		$smarty->assign("show_EPAHighway",$value);
		$showValues[] = $value;
		$showFields[] = "EPAHighway";
				$showRawValues[] = substr($data["EPAHighway"],0,100);

//	features - 

		$value="";
				$value = ProcessLargeText(GetData($data,"features", ""),"field=features".$keylink,"",MODE_LIST);
//		$smarty->assign("show_features",$value);
		$showValues[] = $value;
		$showFields[] = "features";
				$showRawValues[] = substr($data["features"],0,100);

//	Horsepower - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Horsepower", ""),"field=Horsepower".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Horsepower",$value);
		$showValues[] = $value;
		$showFields[] = "Horsepower";
				$showRawValues[] = substr($data["Horsepower"],0,100);

//	id - 

		$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_LIST);
//		$smarty->assign("show_id",$value);
		$showValues[] = $value;
		$showFields[] = "id";
				$showRawValues[] = substr($data["id"],0,100);

//	Make - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Make", ""),"field=Make".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Make",$value);
		$showValues[] = $value;
		$showFields[] = "Make";
				$showRawValues[] = substr($data["Make"],0,100);

//	Model - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Model", ""),"field=Model".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Model",$value);
		$showValues[] = $value;
		$showFields[] = "Model";
				$showRawValues[] = substr($data["Model"],0,100);

//	Phone # - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Phone #", ""),"field=Phone+%23".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Phone__",$value);
		$showValues[] = $value;
		$showFields[] = "Phone__";
				$showRawValues[] = substr($data["Phone #"],0,100);

//	Picture - Database Image

		$value="";
								$value = "<img";
			if(isEnableSection508())
				$value.= " alt=\"Image from DB\"";
									$value.=" id=\"img_Picture_".$id."\" border=0";
			$value.=" src=\"carscars_imager.php?field=Picture".$keylink."\">";
//		$smarty->assign("show_Picture",$value);
		$showValues[] = $value;
		$showFields[] = "Picture";
				$showRawValues[] = "";

//	Price - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Price", ""),"field=Price".$keylink,"",MODE_LIST);
//		$smarty->assign("show_Price",$value);
		$showValues[] = $value;
		$showFields[] = "Price";
				$showRawValues[] = substr($data["Price"],0,100);

//	UserID - 

		$value="";
				$value = ProcessLargeText(GetData($data,"UserID", ""),"field=UserID".$keylink,"",MODE_LIST);
//		$smarty->assign("show_UserID",$value);
		$showValues[] = $value;
		$showFields[] = "UserID";
				$showRawValues[] = substr($data["UserID"],0,100);

//	YearOfMake - 

		$value="";
				$value = ProcessLargeText(GetData($data,"YearOfMake", ""),"field=YearOfMake".$keylink,"",MODE_LIST);
//		$smarty->assign("show_YearOfMake",$value);
		$showValues[] = $value;
		$showFields[] = "YearOfMake";
				$showRawValues[] = substr($data["YearOfMake"],0,100);

//	zipcode - 

		$value="";
				$value = ProcessLargeText(GetData($data,"zipcode", ""),"field=zipcode".$keylink,"",MODE_LIST);
//		$smarty->assign("show_zipcode",$value);
		$showValues[] = $value;
		$showFields[] = "zipcode";
				$showRawValues[] = substr($data["zipcode"],0,100);
/////////////////////////////////////////////////////////////
//	start inline output
/////////////////////////////////////////////////////////////
	echo "<textarea id=\"data\">";
	if($IsSaved)
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
$control_category["params"]["value"]=@$data["category"];
//	Begin Add validation
$arrValidate = array();	

$control_category["params"]["validate"]=$arrValidate;
//	End Add validation
$control_category["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_category["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_category["params"]["mode"]="inline_edit";
else
	$control_category["params"]["mode"]="edit";
if(!$detailKeys || !in_array("category", $detailKeys))	
	$xt->assignbyref("category_editcontrol",$control_category);
else if(!in_array("category", $data)) 	
		$xt->assignbyref("category_editcontrol",$data['category']);
//	control - color
$control_color=array();
$control_color["func"]="xt_buildeditcontrol";
$control_color["params"] = array();
$control_color["params"]["field"]="color";
$control_color["params"]["value"]=@$data["color"];
//	Begin Add validation
$arrValidate = array();	

$control_color["params"]["validate"]=$arrValidate;
//	End Add validation
$control_color["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_color["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_color["params"]["mode"]="inline_edit";
else
	$control_color["params"]["mode"]="edit";
if(!$detailKeys || !in_array("color", $detailKeys))	
	$xt->assignbyref("color_editcontrol",$control_color);
else if(!in_array("color", $data)) 	
		$xt->assignbyref("color_editcontrol",$data['color']);
//	control - Date_Listed
$control_Date_Listed=array();
$control_Date_Listed["func"]="xt_buildeditcontrol";
$control_Date_Listed["params"] = array();
$control_Date_Listed["params"]["field"]="Date Listed";
$control_Date_Listed["params"]["value"]=@$data["Date Listed"];
//	Begin Add validation
$arrValidate = array();	

$control_Date_Listed["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Date_Listed["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Date_Listed["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Date_Listed["params"]["mode"]="inline_edit";
else
	$control_Date_Listed["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Date_Listed", $detailKeys))	
	$xt->assignbyref("Date_Listed_editcontrol",$control_Date_Listed);
else if(!in_array("Date_Listed", $data)) 	
		$xt->assignbyref("Date_Listed_editcontrol",$data['Date_Listed']);
//	control - descr
$control_descr=array();
$control_descr["func"]="xt_buildeditcontrol";
$control_descr["params"] = array();
$control_descr["params"]["field"]="descr";
$control_descr["params"]["value"]=@$data["descr"];
//	Begin Add validation
$arrValidate = array();	

$control_descr["params"]["validate"]=$arrValidate;
//	End Add validation
$control_descr["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_descr["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_descr["params"]["mode"]="inline_edit";
else
	$control_descr["params"]["mode"]="edit";
if(!$detailKeys || !in_array("descr", $detailKeys))	
	$xt->assignbyref("descr_editcontrol",$control_descr);
else if(!in_array("descr", $data)) 	
		$xt->assignbyref("descr_editcontrol",$data['descr']);
//	control - EPACity
$control_EPACity=array();
$control_EPACity["func"]="xt_buildeditcontrol";
$control_EPACity["params"] = array();
$control_EPACity["params"]["field"]="EPACity";
$control_EPACity["params"]["value"]=@$data["EPACity"];
//	Begin Add validation
$arrValidate = array();	

$control_EPACity["params"]["validate"]=$arrValidate;
//	End Add validation
$control_EPACity["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_EPACity["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_EPACity["params"]["mode"]="inline_edit";
else
	$control_EPACity["params"]["mode"]="edit";
if(!$detailKeys || !in_array("EPACity", $detailKeys))	
	$xt->assignbyref("EPACity_editcontrol",$control_EPACity);
else if(!in_array("EPACity", $data)) 	
		$xt->assignbyref("EPACity_editcontrol",$data['EPACity']);
//	control - EPAHighway
$control_EPAHighway=array();
$control_EPAHighway["func"]="xt_buildeditcontrol";
$control_EPAHighway["params"] = array();
$control_EPAHighway["params"]["field"]="EPAHighway";
$control_EPAHighway["params"]["value"]=@$data["EPAHighway"];
//	Begin Add validation
$arrValidate = array();	

$control_EPAHighway["params"]["validate"]=$arrValidate;
//	End Add validation
$control_EPAHighway["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_EPAHighway["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_EPAHighway["params"]["mode"]="inline_edit";
else
	$control_EPAHighway["params"]["mode"]="edit";
if(!$detailKeys || !in_array("EPAHighway", $detailKeys))	
	$xt->assignbyref("EPAHighway_editcontrol",$control_EPAHighway);
else if(!in_array("EPAHighway", $data)) 	
		$xt->assignbyref("EPAHighway_editcontrol",$data['EPAHighway']);
//	control - features
$control_features=array();
$control_features["func"]="xt_buildeditcontrol";
$control_features["params"] = array();
$control_features["params"]["field"]="features";
$control_features["params"]["value"]=@$data["features"];
//	Begin Add validation
$arrValidate = array();	

$control_features["params"]["validate"]=$arrValidate;
//	End Add validation
$control_features["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_features["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_features["params"]["mode"]="inline_edit";
else
	$control_features["params"]["mode"]="edit";
if(!$detailKeys || !in_array("features", $detailKeys))	
	$xt->assignbyref("features_editcontrol",$control_features);
else if(!in_array("features", $data)) 	
		$xt->assignbyref("features_editcontrol",$data['features']);
//	control - Horsepower
$control_Horsepower=array();
$control_Horsepower["func"]="xt_buildeditcontrol";
$control_Horsepower["params"] = array();
$control_Horsepower["params"]["field"]="Horsepower";
$control_Horsepower["params"]["value"]=@$data["Horsepower"];
//	Begin Add validation
$arrValidate = array();	

$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;



$control_Horsepower["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Horsepower["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Horsepower["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Horsepower["params"]["mode"]="inline_edit";
else
	$control_Horsepower["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Horsepower", $detailKeys))	
	$xt->assignbyref("Horsepower_editcontrol",$control_Horsepower);
else if(!in_array("Horsepower", $data)) 	
		$xt->assignbyref("Horsepower_editcontrol",$data['Horsepower']);
//	control - Make
$control_Make=array();
$control_Make["func"]="xt_buildeditcontrol";
$control_Make["params"] = array();
$control_Make["params"]["field"]="Make";
$control_Make["params"]["value"]=@$data["Make"];
//	Begin Add validation
$arrValidate = array();	

$control_Make["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Make["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Make["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Make["params"]["mode"]="inline_edit";
else
	$control_Make["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Make", $detailKeys))	
	$xt->assignbyref("Make_editcontrol",$control_Make);
else if(!in_array("Make", $data)) 	
		$xt->assignbyref("Make_editcontrol",$data['Make']);
//	control - Model
$control_Model=array();
$control_Model["func"]="xt_buildeditcontrol";
$control_Model["params"] = array();
$control_Model["params"]["field"]="Model";
$control_Model["params"]["value"]=@$data["Model"];
//	Begin Add validation
$arrValidate = array();	

$control_Model["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Model["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Model["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Model["params"]["mode"]="inline_edit";
else
	$control_Model["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Model", $detailKeys))	
	$xt->assignbyref("Model_editcontrol",$control_Model);
else if(!in_array("Model", $data)) 	
		$xt->assignbyref("Model_editcontrol",$data['Model']);
//	control - Phone__
$control_Phone__=array();
$control_Phone__["func"]="xt_buildeditcontrol";
$control_Phone__["params"] = array();
$control_Phone__["params"]["field"]="Phone #";
$control_Phone__["params"]["value"]=@$data["Phone #"];
//	Begin Add validation
$arrValidate = array();	

$control_Phone__["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Phone__["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Phone__["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Phone__["params"]["mode"]="inline_edit";
else
	$control_Phone__["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Phone__", $detailKeys))	
	$xt->assignbyref("Phone___editcontrol",$control_Phone__);
else if(!in_array("Phone__", $data)) 	
		$xt->assignbyref("Phone___editcontrol",$data['Phone__']);
//	control - Picture
$control_Picture=array();
$control_Picture["func"]="xt_buildeditcontrol";
$control_Picture["params"] = array();
$control_Picture["params"]["field"]="Picture";
$control_Picture["params"]["value"]=@$data["Picture"];
//	Begin Add validation
$arrValidate = array();	

$control_Picture["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Picture["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Picture["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Picture["params"]["mode"]="inline_edit";
else
	$control_Picture["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Picture", $detailKeys))	
	$xt->assignbyref("Picture_editcontrol",$control_Picture);
else if(!in_array("Picture", $data)) 	
		$xt->assignbyref("Picture_editcontrol",$data['Picture']);
//	control - Price
$control_Price=array();
$control_Price["func"]="xt_buildeditcontrol";
$control_Price["params"] = array();
$control_Price["params"]["field"]="Price";
$control_Price["params"]["value"]=@$data["Price"];
//	Begin Add validation
$arrValidate = array();	

$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;



$control_Price["params"]["validate"]=$arrValidate;
//	End Add validation
$control_Price["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_Price["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_Price["params"]["mode"]="inline_edit";
else
	$control_Price["params"]["mode"]="edit";
if(!$detailKeys || !in_array("Price", $detailKeys))	
	$xt->assignbyref("Price_editcontrol",$control_Price);
else if(!in_array("Price", $data)) 	
		$xt->assignbyref("Price_editcontrol",$data['Price']);
//	control - UserID
$control_UserID=array();
$control_UserID["func"]="xt_buildeditcontrol";
$control_UserID["params"] = array();
$control_UserID["params"]["field"]="UserID";
$control_UserID["params"]["value"]=@$data["UserID"];
//	Begin Add validation
$arrValidate = array();	

$control_UserID["params"]["validate"]=$arrValidate;
//	End Add validation
$control_UserID["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_UserID["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_UserID["params"]["mode"]="inline_edit";
else
	$control_UserID["params"]["mode"]="edit";
if(!$detailKeys || !in_array("UserID", $detailKeys))	
	$xt->assignbyref("UserID_editcontrol",$control_UserID);
else if(!in_array("UserID", $data)) 	
		$xt->assignbyref("UserID_editcontrol",$data['UserID']);
//	control - YearOfMake
$control_YearOfMake=array();
$control_YearOfMake["func"]="xt_buildeditcontrol";
$control_YearOfMake["params"] = array();
$control_YearOfMake["params"]["field"]="YearOfMake";
$control_YearOfMake["params"]["value"]=@$data["YearOfMake"];
//	Begin Add validation
$arrValidate = array();	

$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;



$control_YearOfMake["params"]["validate"]=$arrValidate;
//	End Add validation
$control_YearOfMake["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_YearOfMake["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_YearOfMake["params"]["mode"]="inline_edit";
else
	$control_YearOfMake["params"]["mode"]="edit";
if(!$detailKeys || !in_array("YearOfMake", $detailKeys))	
	$xt->assignbyref("YearOfMake_editcontrol",$control_YearOfMake);
else if(!in_array("YearOfMake", $data)) 	
		$xt->assignbyref("YearOfMake_editcontrol",$data['YearOfMake']);
//	control - zipcode
$control_zipcode=array();
$control_zipcode["func"]="xt_buildeditcontrol";
$control_zipcode["params"] = array();
$control_zipcode["params"]["field"]="zipcode";
$control_zipcode["params"]["value"]=@$data["zipcode"];
//	Begin Add validation
$arrValidate = array();	

$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;



$control_zipcode["params"]["validate"]=$arrValidate;
//	End Add validation
$control_zipcode["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_zipcode["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_zipcode["params"]["mode"]="inline_edit";
else
	$control_zipcode["params"]["mode"]="edit";
if(!$detailKeys || !in_array("zipcode", $detailKeys))	
	$xt->assignbyref("zipcode_editcontrol",$control_zipcode);
else if(!in_array("zipcode", $data)) 	
		$xt->assignbyref("zipcode_editcontrol",$data['zipcode']);
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

if(isBlockingEnable($strTableName) && $disableCtrlsForEditing=="false")
	$pageObject->AddJSCode("window.timeid".$id."=setInterval(\"ConfirmBlock('carscars_edit.php','".jsreplace($strTableName)."','".$skeys."',".$id.",'".$inlineedit."')\",".($block->ConfirmTime*1000).");");

/////////////////////////////////////////////////////////////
if(isShowDetailTable())
{
	$options = array();
	//array of params for classes
	$options["mode"] = DP_INLINE;
	$options["pageType"] = PAGE_LIST;
	$options["masterPageType"] = PAGE_EDIT;
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
		$mkr=1;
		foreach($mKeys[$dpParams['shortTableNames'][$d]] as $mk)
			$options['masterKeysReq'][$mkr++] = $data[$mk];

		$listPageObject = &ListPage::createListPage($strTableName, $options);
		// prepare code
		$listPageObject->prepareForBuildPage();
		// show page
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

if($inlineedit)
{
	$jscode = str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$jscode);
	$xt->assignbyref("linkdata",$jscode);
}
else{
	$body["end"]="</form><script>".$jscode."</script>";	
	$xt->assignbyref("body",$body);
}

$xt->assign("html_attrs","lang=\"en\"");

/////////////////////////////////////////////////////////////
//display the page
/////////////////////////////////////////////////////////////
if(function_exists("BeforeShowEdit"))
	BeforeShowEdit($xt,$templatefile);

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
