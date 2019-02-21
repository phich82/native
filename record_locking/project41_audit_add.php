<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

include("include/dbcommon.php");
include("include/project41_audit_variables.php");
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
	$templatefile = "project41_audit_inline_add.htm";
else
	$templatefile = "project41_audit_add.htm";

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
			'mShortTableName':'project41_audit', 
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
//	processing datetime - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_datetime_".$id);
	$type=postvalue("type_datetime_".$id);
	if (FieldSubmitted("datetime_".$id))
	{
		$value=prepare_for_db("datetime",$value,$type);
/*
		$value=prepare_for_db("datetime",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="datetime";
		$avalues["datetime"]=$value;
	}
	}
//	processibng datetime - end
//	processing ip - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_ip_".$id);
	$type=postvalue("type_ip_".$id);
	if (FieldSubmitted("ip_".$id))
	{
		$value=prepare_for_db("ip",$value,$type);
/*
		$value=prepare_for_db("ip",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="ip";
		$avalues["ip"]=$value;
	}
	}
//	processibng ip - end
//	processing user - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_user_".$id);
	$type=postvalue("type_user_".$id);
	if (FieldSubmitted("user_".$id))
	{
		$value=prepare_for_db("user",$value,$type);
/*
		$value=prepare_for_db("user",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="user";
		$avalues["user"]=$value;
	}
	}
//	processibng user - end
//	processing table - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_table_".$id);
	$type=postvalue("type_table_".$id);
	if (FieldSubmitted("table_".$id))
	{
		$value=prepare_for_db("table",$value,$type);
/*
		$value=prepare_for_db("table",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="table";
		$avalues["table"]=$value;
	}
	}
//	processibng table - end
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
//	processing description - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_description_".$id);
	$type=postvalue("type_description_".$id);
	if (FieldSubmitted("description_".$id))
	{
		$value=prepare_for_db("description",$value,$type);
/*
		$value=prepare_for_db("description",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="description";
		$avalues["description"]=$value;
	}
	}
//	processibng description - end



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
	header("Location: project41_audit_".$pageObject->getPageType().".php");
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
	$defvalues["datetime"]=@$avalues["datetime"];
	$defvalues["ip"]=@$avalues["ip"];
	$defvalues["user"]=@$avalues["user"];
	$defvalues["table"]=@$avalues["table"];
	$defvalues["action"]=@$avalues["action"];
	$defvalues["description"]=@$avalues["description"];
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
	$xt->assign("datetime_fieldblock",true);
	$xt->assign("datetime_label",true);
	if(isEnableSection508())
		$xt->assign_section("datetime_label","<label for=\"".GetInputElementId("datetime", $id)."\">","</label>");
	$xt->assign("ip_fieldblock",true);
	$xt->assign("ip_label",true);
	if(isEnableSection508())
		$xt->assign_section("ip_label","<label for=\"".GetInputElementId("ip", $id)."\">","</label>");
	$xt->assign("user_fieldblock",true);
	$xt->assign("user_label",true);
	if(isEnableSection508())
		$xt->assign_section("user_label","<label for=\"".GetInputElementId("user", $id)."\">","</label>");
	$xt->assign("table_fieldblock",true);
	$xt->assign("table_label",true);
	if(isEnableSection508())
		$xt->assign_section("table_label","<label for=\"".GetInputElementId("table", $id)."\">","</label>");
	$xt->assign("action_fieldblock",true);
	$xt->assign("action_label",true);
	if(isEnableSection508())
		$xt->assign_section("action_label","<label for=\"".GetInputElementId("action", $id)."\">","</label>");
	$xt->assign("description_fieldblock",true);
	$xt->assign("description_label",true);
	if(isEnableSection508())
		$xt->assign_section("description_label","<label for=\"".GetInputElementId("description", $id)."\">","</label>");
	
	if($onsubmit)
		$onsubmit="onsubmit=\"".htmlspecialchars($onsubmit)."\"";
	if($inlineadd!=ADD_ONTHEFLY)
	{
		$body["begin"]=$includes.
		'<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="project41_audit_add.php" '.$onsubmit.'>'.
		'<input type=hidden name="a" value="added">'.
		(isShowDetailTable() ? '<input type=hidden name="editType" value="dpinline">' : '');
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='project41_audit_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$body["begin"]='<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="project41_audit_add.php" '.$onsubmit.' target="flyframe'.$id.'">'.
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
	//	datetime - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"datetime", "Short Date"),"field=datetime".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "datetime";
				$showRawValues[] = substr($data["datetime"],0,100);
	////////////////////////////////////////////
	//	ip - 
		$value="";
				$value = ProcessLargeText(GetData($data,"ip", ""),"field=ip".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "ip";
				$showRawValues[] = substr($data["ip"],0,100);
	////////////////////////////////////////////
	//	user - 
		$value="";
				$value = ProcessLargeText(GetData($data,"user", ""),"field=user".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "user";
				$showRawValues[] = substr($data["user"],0,100);
	////////////////////////////////////////////
	//	table - 
		$value="";
				$value = ProcessLargeText(GetData($data,"table", ""),"field=table".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "table";
				$showRawValues[] = substr($data["table"],0,100);
	////////////////////////////////////////////
	//	action - 
		$value="";
				$value = ProcessLargeText(GetData($data,"action", ""),"field=action".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "action";
				$showRawValues[] = substr($data["action"],0,100);
	////////////////////////////////////////////
	//	description - 
		$value="";
				$value = ProcessLargeText(GetData($data,"description", ""),"field=description".$keylink,"",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "description";
				$showRawValues[] = substr($data["description"],0,100);

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
//	control - datetime
$control_datetime=array();
$control_datetime["func"]="xt_buildeditcontrol";
$control_datetime["params"] = array();
$control_datetime["params"]["field"]="datetime";
$control_datetime["params"]["value"]=@$defvalues["datetime"];

//	Begin Add validation
$arrValidate = array();	
$arrValidate['basicValidate'][] = "IsRequired";
$control_datetime["params"]["validate"]=$arrValidate;
//	End Add validation

$control_datetime["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_datetime["params"]["mode"]="inline_add";
else
	$control_datetime["params"]["mode"]="add";
if(!$detailKeys || !in_array("datetime", $detailKeys))
	$xt->assignbyref("datetime_editcontrol",$control_datetime);
else if(!in_array("datetime", $defvalues)) 	
		$xt->assignbyref("datetime_editcontrol",$defvalues['datetime']);
//	control - ip
$control_ip=array();
$control_ip["func"]="xt_buildeditcontrol";
$control_ip["params"] = array();
$control_ip["params"]["field"]="ip";
$control_ip["params"]["value"]=@$defvalues["ip"];

//	Begin Add validation
$arrValidate = array();	
$control_ip["params"]["validate"]=$arrValidate;
//	End Add validation

$control_ip["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_ip["params"]["mode"]="inline_add";
else
	$control_ip["params"]["mode"]="add";
if(!$detailKeys || !in_array("ip", $detailKeys))
	$xt->assignbyref("ip_editcontrol",$control_ip);
else if(!in_array("ip", $defvalues)) 	
		$xt->assignbyref("ip_editcontrol",$defvalues['ip']);
//	control - user
$control_user=array();
$control_user["func"]="xt_buildeditcontrol";
$control_user["params"] = array();
$control_user["params"]["field"]="user";
$control_user["params"]["value"]=@$defvalues["user"];

//	Begin Add validation
$arrValidate = array();	
$control_user["params"]["validate"]=$arrValidate;
//	End Add validation

$control_user["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_user["params"]["mode"]="inline_add";
else
	$control_user["params"]["mode"]="add";
if(!$detailKeys || !in_array("user", $detailKeys))
	$xt->assignbyref("user_editcontrol",$control_user);
else if(!in_array("user", $defvalues)) 	
		$xt->assignbyref("user_editcontrol",$defvalues['user']);
//	control - table
$control_table=array();
$control_table["func"]="xt_buildeditcontrol";
$control_table["params"] = array();
$control_table["params"]["field"]="table";
$control_table["params"]["value"]=@$defvalues["table"];

//	Begin Add validation
$arrValidate = array();	
$control_table["params"]["validate"]=$arrValidate;
//	End Add validation

$control_table["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_table["params"]["mode"]="inline_add";
else
	$control_table["params"]["mode"]="add";
if(!$detailKeys || !in_array("table", $detailKeys))
	$xt->assignbyref("table_editcontrol",$control_table);
else if(!in_array("table", $defvalues)) 	
		$xt->assignbyref("table_editcontrol",$defvalues['table']);
//	control - action
$control_action=array();
$control_action["func"]="xt_buildeditcontrol";
$control_action["params"] = array();
$control_action["params"]["field"]="action";
$control_action["params"]["value"]=@$defvalues["action"];

//	Begin Add validation
$arrValidate = array();	
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
//	control - description
$control_description=array();
$control_description["func"]="xt_buildeditcontrol";
$control_description["params"] = array();
$control_description["params"]["field"]="description";
$control_description["params"]["value"]=@$defvalues["description"];

//	Begin Add validation
$arrValidate = array();	
$control_description["params"]["validate"]=$arrValidate;
//	End Add validation

$control_description["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_description["params"]["mode"]="inline_add";
else
	$control_description["params"]["mode"]="add";
if(!$detailKeys || !in_array("description", $detailKeys))
	$xt->assignbyref("description_editcontrol",$control_description);
else if(!in_array("description", $defvalues)) 	
		$xt->assignbyref("description_editcontrol",$defvalues['description']);
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
	$strTableName = "project41_audit";
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
