<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

include("include/dbcommon.php");
include("include/project39_blocking_variables.php");
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
	$templatefile = "project39_blocking_inline_add.htm";
else
	$templatefile = "project39_blocking_add.htm";

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
			'mShortTableName':'project39_blocking', 
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
//	processing tablename - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_tablename_".$id);
	$type=postvalue("type_tablename_".$id);
	if (FieldSubmitted("tablename_".$id))
	{
		$value=prepare_for_db("tablename",$value,$type);
/*
		$value=prepare_for_db("tablename",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="tablename";
		$avalues["tablename"]=$value;
	}
	}
//	processibng tablename - end
//	processing startdatetime - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_startdatetime_".$id);
	$type=postvalue("type_startdatetime_".$id);
	if (FieldSubmitted("startdatetime_".$id))
	{
		$value=prepare_for_db("startdatetime",$value,$type);
/*
		$value=prepare_for_db("startdatetime",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="startdatetime";
		$avalues["startdatetime"]=$value;
	}
	}
//	processibng startdatetime - end
//	processing confirmdatetime - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_confirmdatetime_".$id);
	$type=postvalue("type_confirmdatetime_".$id);
	if (FieldSubmitted("confirmdatetime_".$id))
	{
		$value=prepare_for_db("confirmdatetime",$value,$type);
/*
		$value=prepare_for_db("confirmdatetime",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="confirmdatetime";
		$avalues["confirmdatetime"]=$value;
	}
	}
//	processibng confirmdatetime - end
//	processing keys - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_keys_".$id);
	$type=postvalue("type_keys_".$id);
	if (FieldSubmitted("keys_".$id))
	{
		$value=prepare_for_db("keys",$value,$type);
/*
		$value=prepare_for_db("keys",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="keys";
		$avalues["keys"]=$value;
	}
	}
//	processibng keys - end
//	processing sessionid - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_sessionid_".$id);
	$type=postvalue("type_sessionid_".$id);
	if (FieldSubmitted("sessionid_".$id))
	{
		$value=prepare_for_db("sessionid",$value,$type);
/*
		$value=prepare_for_db("sessionid",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="sessionid";
		$avalues["sessionid"]=$value;
	}
	}
//	processibng sessionid - end
//	processing userid - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_userid_".$id);
	$type=postvalue("type_userid_".$id);
	if (FieldSubmitted("userid_".$id))
	{
		$value=prepare_for_db("userid",$value,$type);
/*
		$value=prepare_for_db("userid",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="userid";
		$avalues["userid"]=$value;
	}
	}
//	processibng userid - end
//	processing action - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_action_".$id);
	$type=postvalue("type_action_".$id);
	if (FieldSubmitted("action_".$id))
	{
		$value=prepare_for_db("action",$value,$type);
/*
		$value=prepare_for_db("action",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="action";
		$avalues["action"]=$value;
	}
	}
//	processibng action - end



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
	header("Location: project39_blocking_".$pageObject->getPageType().".php");
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
	$defvalues["tablename"]=@$avalues["tablename"];
	$defvalues["startdatetime"]=@$avalues["startdatetime"];
	$defvalues["confirmdatetime"]=@$avalues["confirmdatetime"];
	$defvalues["keys"]=@$avalues["keys"];
	$defvalues["sessionid"]=@$avalues["sessionid"];
	$defvalues["userid"]=@$avalues["userid"];
	$defvalues["action"]=@$avalues["action"];
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
	$xt->assign("tablename_fieldblock",true);
	$xt->assign("tablename_label",true);
	if(isEnableSection508())
		$xt->assign_section("tablename_label","<label for=\"".GetInputElementId("tablename", $id)."\">","</label>");
	$xt->assign("startdatetime_fieldblock",true);
	$xt->assign("startdatetime_label",true);
	if(isEnableSection508())
		$xt->assign_section("startdatetime_label","<label for=\"".GetInputElementId("startdatetime", $id)."\">","</label>");
	$xt->assign("confirmdatetime_fieldblock",true);
	$xt->assign("confirmdatetime_label",true);
	if(isEnableSection508())
		$xt->assign_section("confirmdatetime_label","<label for=\"".GetInputElementId("confirmdatetime", $id)."\">","</label>");
	$xt->assign("keys_fieldblock",true);
	$xt->assign("keys_label",true);
	if(isEnableSection508())
		$xt->assign_section("keys_label","<label for=\"".GetInputElementId("keys", $id)."\">","</label>");
	$xt->assign("sessionid_fieldblock",true);
	$xt->assign("sessionid_label",true);
	if(isEnableSection508())
		$xt->assign_section("sessionid_label","<label for=\"".GetInputElementId("sessionid", $id)."\">","</label>");
	$xt->assign("userid_fieldblock",true);
	$xt->assign("userid_label",true);
	if(isEnableSection508())
		$xt->assign_section("userid_label","<label for=\"".GetInputElementId("userid", $id)."\">","</label>");
	$xt->assign("action_fieldblock",true);
	$xt->assign("action_label",true);
	if(isEnableSection508())
		$xt->assign_section("action_label","<label for=\"".GetInputElementId("action", $id)."\">","</label>");
	
	if($onsubmit)
		$onsubmit="onsubmit=\"".htmlspecialchars($onsubmit)."\"";
	if($inlineadd!=ADD_ONTHEFLY)
	{
		$body["begin"]=$includes.
		'<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="project39_blocking_add.php" '.$onsubmit.'>'.
		'<input type=hidden name="a" value="added">'.
		(isShowDetailTable() ? '<input type=hidden name="editType" value="dpinline">' : '');
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='project39_blocking_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$body["begin"]='<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="project39_blocking_add.php" '.$onsubmit.' target="flyframe'.$id.'">'.
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
	//	id - 
		$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "id";
				$showRawValues[] = substr($data["id"],0,100);
	////////////////////////////////////////////
	//	tablename - 
		$value="";
				$value = ProcessLargeText(GetData($data,"tablename", ""),"field=tablename".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "tablename";
				$showRawValues[] = substr($data["tablename"],0,100);
	////////////////////////////////////////////
	//	startdatetime - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"startdatetime", "Short Date"),"field=startdatetime".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "startdatetime";
				$showRawValues[] = substr($data["startdatetime"],0,100);
	////////////////////////////////////////////
	//	confirmdatetime - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"confirmdatetime", "Short Date"),"field=confirmdatetime".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "confirmdatetime";
				$showRawValues[] = substr($data["confirmdatetime"],0,100);
	////////////////////////////////////////////
	//	keys - 
		$value="";
				$value = ProcessLargeText(GetData($data,"keys", ""),"field=keys".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "keys";
				$showRawValues[] = substr($data["keys"],0,100);
	////////////////////////////////////////////
	//	sessionid - 
		$value="";
				$value = ProcessLargeText(GetData($data,"sessionid", ""),"field=sessionid".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "sessionid";
				$showRawValues[] = substr($data["sessionid"],0,100);
	////////////////////////////////////////////
	//	userid - 
		$value="";
				$value = ProcessLargeText(GetData($data,"userid", ""),"field=userid".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "userid";
				$showRawValues[] = substr($data["userid"],0,100);
	////////////////////////////////////////////
	//	action - 
		$value="";
				$value = ProcessLargeText(GetData($data,"action", ""),"field=action".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "action";
				$showRawValues[] = substr($data["action"],0,100);

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
//	control - tablename
$control_tablename=array();
$control_tablename["func"]="xt_buildeditcontrol";
$control_tablename["params"] = array();
$control_tablename["params"]["field"]="tablename";
$control_tablename["params"]["value"]=@$defvalues["tablename"];

//	Begin Add validation
$arrValidate = array();	
$control_tablename["params"]["validate"]=$arrValidate;
//	End Add validation

$control_tablename["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_tablename["params"]["mode"]="inline_add";
else
	$control_tablename["params"]["mode"]="add";
if(!$detailKeys || !in_array("tablename", $detailKeys))
	$xt->assignbyref("tablename_editcontrol",$control_tablename);
else if(!in_array("tablename", $defvalues)) 	
		$xt->assignbyref("tablename_editcontrol",$defvalues['tablename']);
//	control - startdatetime
$control_startdatetime=array();
$control_startdatetime["func"]="xt_buildeditcontrol";
$control_startdatetime["params"] = array();
$control_startdatetime["params"]["field"]="startdatetime";
$control_startdatetime["params"]["value"]=@$defvalues["startdatetime"];

//	Begin Add validation
$arrValidate = array();	
$arrValidate['basicValidate'][] = "IsRequired";
$control_startdatetime["params"]["validate"]=$arrValidate;
//	End Add validation

$control_startdatetime["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_startdatetime["params"]["mode"]="inline_add";
else
	$control_startdatetime["params"]["mode"]="add";
if(!$detailKeys || !in_array("startdatetime", $detailKeys))
	$xt->assignbyref("startdatetime_editcontrol",$control_startdatetime);
else if(!in_array("startdatetime", $defvalues)) 	
		$xt->assignbyref("startdatetime_editcontrol",$defvalues['startdatetime']);
//	control - confirmdatetime
$control_confirmdatetime=array();
$control_confirmdatetime["func"]="xt_buildeditcontrol";
$control_confirmdatetime["params"] = array();
$control_confirmdatetime["params"]["field"]="confirmdatetime";
$control_confirmdatetime["params"]["value"]=@$defvalues["confirmdatetime"];

//	Begin Add validation
$arrValidate = array();	
$arrValidate['basicValidate'][] = "IsRequired";
$control_confirmdatetime["params"]["validate"]=$arrValidate;
//	End Add validation

$control_confirmdatetime["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_confirmdatetime["params"]["mode"]="inline_add";
else
	$control_confirmdatetime["params"]["mode"]="add";
if(!$detailKeys || !in_array("confirmdatetime", $detailKeys))
	$xt->assignbyref("confirmdatetime_editcontrol",$control_confirmdatetime);
else if(!in_array("confirmdatetime", $defvalues)) 	
		$xt->assignbyref("confirmdatetime_editcontrol",$defvalues['confirmdatetime']);
//	control - keys
$control_keys=array();
$control_keys["func"]="xt_buildeditcontrol";
$control_keys["params"] = array();
$control_keys["params"]["field"]="keys";
$control_keys["params"]["value"]=@$defvalues["keys"];

//	Begin Add validation
$arrValidate = array();	
$control_keys["params"]["validate"]=$arrValidate;
//	End Add validation

$control_keys["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_keys["params"]["mode"]="inline_add";
else
	$control_keys["params"]["mode"]="add";
if(!$detailKeys || !in_array("keys", $detailKeys))
	$xt->assignbyref("keys_editcontrol",$control_keys);
else if(!in_array("keys", $defvalues)) 	
		$xt->assignbyref("keys_editcontrol",$defvalues['keys']);
//	control - sessionid
$control_sessionid=array();
$control_sessionid["func"]="xt_buildeditcontrol";
$control_sessionid["params"] = array();
$control_sessionid["params"]["field"]="sessionid";
$control_sessionid["params"]["value"]=@$defvalues["sessionid"];

//	Begin Add validation
$arrValidate = array();	
$control_sessionid["params"]["validate"]=$arrValidate;
//	End Add validation

$control_sessionid["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_sessionid["params"]["mode"]="inline_add";
else
	$control_sessionid["params"]["mode"]="add";
if(!$detailKeys || !in_array("sessionid", $detailKeys))
	$xt->assignbyref("sessionid_editcontrol",$control_sessionid);
else if(!in_array("sessionid", $defvalues)) 	
		$xt->assignbyref("sessionid_editcontrol",$defvalues['sessionid']);
//	control - userid
$control_userid=array();
$control_userid["func"]="xt_buildeditcontrol";
$control_userid["params"] = array();
$control_userid["params"]["field"]="userid";
$control_userid["params"]["value"]=@$defvalues["userid"];

//	Begin Add validation
$arrValidate = array();	
$control_userid["params"]["validate"]=$arrValidate;
//	End Add validation

$control_userid["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_userid["params"]["mode"]="inline_add";
else
	$control_userid["params"]["mode"]="add";
if(!$detailKeys || !in_array("userid", $detailKeys))
	$xt->assignbyref("userid_editcontrol",$control_userid);
else if(!in_array("userid", $defvalues)) 	
		$xt->assignbyref("userid_editcontrol",$defvalues['userid']);
//	control - action
$control_action=array();
$control_action["func"]="xt_buildeditcontrol";
$control_action["params"] = array();
$control_action["params"]["field"]="action";
$control_action["params"]["value"]=@$defvalues["action"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$control_action["params"]["validate"]=$arrValidate;
//	End Add validation

$control_action["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_action["params"]["mode"]="inline_add";
else
	$control_action["params"]["mode"]="add";
if(!$detailKeys || !in_array("action", $detailKeys))
	$xt->assignbyref("action_editcontrol",$control_action);
else if(!in_array("action", $defvalues)) 	
		$xt->assignbyref("action_editcontrol",$defvalues['action']);
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
	$strTableName = "project39_blocking";
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
