<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

include("include/dbcommon.php");
include("include/carsmodels_variables.php");
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
$query = $queryData_carsmodels->Copy();

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
$templatefile = ( $inlineedit ) ? "carsmodels_inline_edit.htm" : "carsmodels_edit.htm";

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
	$value = postvalue("value_make_".$id);
	$type=postvalue("type_make_".$id);
	if(FieldSubmitted("make_".$id))
	{
		
		$value=prepare_for_db("make",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["make"]=$value;
	}


//	processibng make - end
	}
	$condition = 1;

	if($condition)
	{
	$value = postvalue("value_model_".$id);
	$type=postvalue("type_model_".$id);
	if(FieldSubmitted("model_".$id))
	{
		
		$value=prepare_for_db("model",$value,$type);
	}
	else
	{
		$value=false;
	}
	if($value!==false)
	{	



		$evalues["model"]=$value;
	}


//	processibng model - end
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
	header("Location: carsmodels_".$pageObject->getPageType().".php?".$keyGetQ);
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
$query = $queryData_carsmodels->Copy();
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
		header("Location: carsmodels_list.php?a=return");
		exit();
	}
	else
		$data=array();
}

$readonlyfields=array();


if($readevalues)
{
	$data["make"]=$evalues["make"];
	$data["model"]=$evalues["model"];
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
	$xt->assign("make_fieldblock",true);
	$xt->assign("make_label",true);
	if(isEnableSection508())
		$xt->assign_section("make_label","<label for=\"".GetInputElementId("make", $id)."\">","</label>");
	$xt->assign("model_fieldblock",true);
	$xt->assign("model_label",true);
	if(isEnableSection508())
		$xt->assign_section("model_label","<label for=\"".GetInputElementId("model", $id)."\">","</label>");
	
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".htmlspecialchars($onsubmit)."\"";
	$body["begin"]=$includes.'
	<form name="'.$formname.'" id="'.$formname.'" encType="multipart/form-data" method="post" action="carsmodels_edit.php" '.$onsubmit.'>'.
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
		
		$queryData_carsmodels->RemoveBlobFields(true);
		//$sql_next=gSQLWhere($where.$where_next).$order_next;
		$sql_next = $queryData_carsmodels->toSql($where.$where_next, $order_next);
		//$sql_prev=gSQLWhere($where.$where_prev).$order_prev;
		$sql_prev = $queryData_carsmodels->toSql($where.$where_prev, $order_prev);
		
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
		$xt->assign("nextbutton_attrs","align=\"absmiddle\" onclick=\"UnblockRecord('carsmodels_edit.php','".$skeys."','',function(){window.location.href='carsmodels_edit.php?".$nextlink."'});return false;\"");
		$resetEditors.="$('#next".$id."').attr('style','');$('#next".$id."').attr('disabled','');";
	}
	else 
		$xt->assign("next_button",false);
	if(count($prev))
	{
		$xt->assign("prev_button",true);
				$prevlink .="editid1=".htmlspecialchars(rawurlencode($prev[1]));
		$xt->assign("prevbutton_attrs","align=\"absmiddle\" onclick=\"UnblockRecord('carsmodels_edit.php','".$skeys."','',function(){window.location.href='carsmodels_edit.php?".$prevlink."'});return false;\"");
		$resetEditors.="$('#prev".$id."').attr('style','');$('#prev".$id."').attr('disabled','');";
	}
	else 
		$xt->assign("prev_button",false);
	
	$resetEditors .= "Runner.controls.ControlManager.resetControlsForTable('".htmlspecialchars(jsreplace($strTableName))."');";
	$xt->assign("resetbutton_attrs",'onclick="'.$resetEditors.'" onmouseover="this.focus();"');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Next Prev button
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
	$xt->assign("backbutton_attrs","onclick=\"UnblockRecord('carsmodels_edit.php','".$skeys."','',function(){window.location.href='carsmodels_list.php?a=return'});return false;\"");
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


//	id - 

		$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_LIST);
//		$smarty->assign("show_id",$value);
		$showValues[] = $value;
		$showFields[] = "id";
				$showRawValues[] = substr($data["id"],0,100);

//	make - 

		$value="";
				$value = ProcessLargeText(GetData($data,"make", ""),"field=make".$keylink,"",MODE_LIST);
//		$smarty->assign("show_make",$value);
		$showValues[] = $value;
		$showFields[] = "make";
				$showRawValues[] = substr($data["make"],0,100);

//	model - 

		$value="";
				$value = ProcessLargeText(GetData($data,"model", ""),"field=model".$keylink,"",MODE_LIST);
//		$smarty->assign("show_model",$value);
		$showValues[] = $value;
		$showFields[] = "model";
				$showRawValues[] = substr($data["model"],0,100);
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
//	control - make
$control_make=array();
$control_make["func"]="xt_buildeditcontrol";
$control_make["params"] = array();
$control_make["params"]["field"]="make";
$control_make["params"]["value"]=@$data["make"];
//	Begin Add validation
$arrValidate = array();	

$control_make["params"]["validate"]=$arrValidate;
//	End Add validation
$control_make["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_make["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_make["params"]["mode"]="inline_edit";
else
	$control_make["params"]["mode"]="edit";
if(!$detailKeys || !in_array("make", $detailKeys))	
	$xt->assignbyref("make_editcontrol",$control_make);
else if(!in_array("make", $data)) 	
		$xt->assignbyref("make_editcontrol",$data['make']);
//	control - model
$control_model=array();
$control_model["func"]="xt_buildeditcontrol";
$control_model["params"] = array();
$control_model["params"]["field"]="model";
$control_model["params"]["value"]=@$data["model"];
//	Begin Add validation
$arrValidate = array();	

$control_model["params"]["validate"]=$arrValidate;
//	End Add validation
$control_model["params"]["id"]=$id;
$additionalCtrlParams = array();
$additionalCtrlParams["disabled"] = $disableCtrlsForEditing;
$control_model["params"]["additionalCtrlParams"]=$additionalCtrlParams;
if($inlineedit)
	$control_model["params"]["mode"]="inline_edit";
else
	$control_model["params"]["mode"]="edit";
if(!$detailKeys || !in_array("model", $detailKeys))	
	$xt->assignbyref("model_editcontrol",$control_model);
else if(!in_array("model", $data)) 	
		$xt->assignbyref("model_editcontrol",$data['model']);
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
	$pageObject->AddJSCode("window.timeid".$id."=setInterval(\"ConfirmBlock('carsmodels_edit.php','".jsreplace($strTableName)."','".$skeys."',".$id.",'".$inlineedit."')\",".($block->ConfirmTime*1000).");");

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
	$strTableName = "carsmodels";		
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
