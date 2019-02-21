<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

include("include/dbcommon.php");
include("include/carsusers_variables.php");
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
	$templatefile = "carsusers_inline_add.htm";
else
	$templatefile = "carsusers_add.htm";

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
			'mShortTableName':'carsusers', 
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
//	processing id - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_id_".$id);
	$type=postvalue("type_id_".$id);
	if (FieldSubmitted("id_".$id))
	{
		$value=prepare_for_db("id",$value,$type);
/*
		$value=prepare_for_db("id",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="id";
		$avalues["id"]=$value;
	}
	}
//	processibng id - end
//	processing password - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_password_".$id);
	$type=postvalue("type_password_".$id);
	if (FieldSubmitted("password_".$id))
	{
		$value=prepare_for_db("password",$value,$type);
/*
		$value=prepare_for_db("password",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="password";
		$avalues["password"]=$value;
	}
	}
//	processibng password - end
//	processing username - start
    
	$inlineAddOption = true;
	if($inlineAddOption)
	{
	$value = postvalue("value_username_".$id);
	$type=postvalue("type_username_".$id);
	if (FieldSubmitted("username_".$id))
	{
		$value=prepare_for_db("username",$value,$type);
/*
		$value=prepare_for_db("username",$value,$type);
*/
	}
	else
		$value=false;
	if(!($value===false))
	{


		$blobfields[]="username";
		$avalues["username"]=$value;
	}
	}
//	processibng username - end



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
	header("Location: carsusers_".$pageObject->getPageType().".php");
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
	$defvalues["id"]=@$avalues["id"];
	$defvalues["password"]=@$avalues["password"];
	$defvalues["username"]=@$avalues["username"];
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
	$xt->assign("id_fieldblock",true);
	$xt->assign("id_label",true);
	if(isEnableSection508())
		$xt->assign_section("id_label","<label for=\"".GetInputElementId("id", $id)."\">","</label>");
	$xt->assign("password_fieldblock",true);
	$xt->assign("password_label",true);
	if(isEnableSection508())
		$xt->assign_section("password_label","<label for=\"".GetInputElementId("password", $id)."\">","</label>");
	$xt->assign("username_fieldblock",true);
	$xt->assign("username_label",true);
	if(isEnableSection508())
		$xt->assign_section("username_label","<label for=\"".GetInputElementId("username", $id)."\">","</label>");
	
	if($onsubmit)
		$onsubmit="onsubmit=\"".htmlspecialchars($onsubmit)."\"";
	if($inlineadd!=ADD_ONTHEFLY)
	{
		$body["begin"]=$includes.
		'<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carsusers_add.php" '.$onsubmit.'>'.
		'<input type=hidden name="a" value="added">'.
		(isShowDetailTable() ? '<input type=hidden name="editType" value="dpinline">' : '');
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='carsusers_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$body["begin"]='<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carsusers_add.php" '.$onsubmit.' target="flyframe'.$id.'">'.
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
//	control - id
$control_id=array();
$control_id["func"]="xt_buildeditcontrol";
$control_id["params"] = array();
$control_id["params"]["field"]="id";
$control_id["params"]["value"]=@$defvalues["id"];

//	Begin Add validation
$arrValidate = array();	
$validatetype=getJsValidatorName("Number");
	$arrValidate['basicValidate'][] = $validatetype;
$arrValidate['basicValidate'][] = "IsRequired";
$control_id["params"]["validate"]=$arrValidate;
//	End Add validation

$control_id["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_id["params"]["mode"]="inline_add";
else
	$control_id["params"]["mode"]="add";
if(!$detailKeys || !in_array("id", $detailKeys))
	$xt->assignbyref("id_editcontrol",$control_id);
else if(!in_array("id", $defvalues)) 	
		$xt->assignbyref("id_editcontrol",$defvalues['id']);
//	control - password
$control_password=array();
$control_password["func"]="xt_buildeditcontrol";
$control_password["params"] = array();
$control_password["params"]["field"]="password";
$control_password["params"]["value"]=@$defvalues["password"];

//	Begin Add validation
$arrValidate = array();	
$control_password["params"]["validate"]=$arrValidate;
//	End Add validation

$control_password["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_password["params"]["mode"]="inline_add";
else
	$control_password["params"]["mode"]="add";
if(!$detailKeys || !in_array("password", $detailKeys))
	$xt->assignbyref("password_editcontrol",$control_password);
else if(!in_array("password", $defvalues)) 	
		$xt->assignbyref("password_editcontrol",$defvalues['password']);
//	control - username
$control_username=array();
$control_username["func"]="xt_buildeditcontrol";
$control_username["params"] = array();
$control_username["params"]["field"]="username";
$control_username["params"]["value"]=@$defvalues["username"];

//	Begin Add validation
$arrValidate = array();	
$control_username["params"]["validate"]=$arrValidate;
//	End Add validation

$control_username["params"]["id"]=$id;
if($inlineadd==ADD_INLINE || $inlineadd==ADD_ONTHEFLY)
	$control_username["params"]["mode"]="inline_add";
else
	$control_username["params"]["mode"]="add";
if(!$detailKeys || !in_array("username", $detailKeys))
	$xt->assignbyref("username_editcontrol",$control_username);
else if(!in_array("username", $defvalues)) 	
		$xt->assignbyref("username_editcontrol",$defvalues['username']);
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
	$strTableName = "carsusers";
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
