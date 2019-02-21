<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

include("include/dbcommon.php");
include("include/project39_blocking_variables.php");

//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

include('include/xtempl.php');
include('classes/runnerpage.php');
$xt = new Xtempl();

// Modify query: remove blob fields from fieldlist.
// Blob fields on an edit page are shown using imager.php (for example).
// They don't need to be selected from DB in edit.php itself.
$gQuery->RemoveBlobFields();

$query = $gQuery->Copy();
$filename="";	
$message="";
$key=array();
$next=array();
$prev=array();
$all=postvalue("all");
$pdf=postvalue("pdf");
$mypage=1;

if(postvalue("id"))
	$id = postvalue("id");
else
	$id = 1;
	
// assign an id			
$xt->assign("id",$id);

//array of params for classes
$params = array("pagetype" => PAGE_VIEW,"id" =>$id);
$pageObject = new RunnerPage($params);

//	Before Process event
if(function_exists("BeforeProcessView"))
	BeforeProcessView($conn);

$strWhereClause = '';
if(!$all)
{
	$keys=array();
	$keys["id"]=postvalue("editid1");

//	get current values and show edit controls
	$query->Where()->AddFilterByKeys($keys);


	// get string representation of sql and where clause separately
	$strWhereClause = $query->Where()->toSql($query);
	$strSQL = $query->toSql('');
}
else
{
	if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
	{
		$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
		$strWhereClause=@$_SESSION[$strTableName."_SelectedWhere"];
	}
	else
	{
		$strWhereClause=@$_SESSION[$strTableName."_where"];
		$strSQL=gSQLWhere($strWhereClause);
	}
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause,0);
}

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryView"))
	BeforeQueryView($strSQL,$strWhereClause);
if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);

if(!$all)
{
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
}
else
{
//	 Pagination:

	$nPageSize=0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage=(integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize=(integer)@$_SESSION[$strTableName."_pagesize"];
		if($numrows<=($mypage-1)*$nPageSize)
			$mypage=ceil($numrows/$nPageSize);
		if(!$nPageSize)
			$nPageSize=$gPageSize;
		if(!$mypage)
			$mypage=1;

		$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
	}
	$rs=db_query($strSQL,$conn);
}

$data=db_fetch_array($rs);

$out="";
$first=true;

$templatefile="";

while($data)
{



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["id"]));

////////////////////////////////////////////
//	id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"id", ""),"","",MODE_VIEW);
	$xt->assign("id_value",$value);
	$xt->assign("id_fieldblock",true);
////////////////////////////////////////////
//	tablename - 
	$value="";
		$value = ProcessLargeText(GetData($data,"tablename", ""),"","",MODE_VIEW);
	$xt->assign("tablename_value",$value);
	$xt->assign("tablename_fieldblock",true);
////////////////////////////////////////////
//	startdatetime - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"startdatetime", "Short Date"),"","",MODE_VIEW);
	$xt->assign("startdatetime_value",$value);
	$xt->assign("startdatetime_fieldblock",true);
////////////////////////////////////////////
//	confirmdatetime - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"confirmdatetime", "Short Date"),"","",MODE_VIEW);
	$xt->assign("confirmdatetime_value",$value);
	$xt->assign("confirmdatetime_fieldblock",true);
////////////////////////////////////////////
//	keys - 
	$value="";
		$value = ProcessLargeText(GetData($data,"keys", ""),"","",MODE_VIEW);
	$xt->assign("keys_value",$value);
	$xt->assign("keys_fieldblock",true);
////////////////////////////////////////////
//	sessionid - 
	$value="";
		$value = ProcessLargeText(GetData($data,"sessionid", ""),"","",MODE_VIEW);
	$xt->assign("sessionid_value",$value);
	$xt->assign("sessionid_fieldblock",true);
////////////////////////////////////////////
//	userid - 
	$value="";
		$value = ProcessLargeText(GetData($data,"userid", ""),"","",MODE_VIEW);
	$xt->assign("userid_value",$value);
	$xt->assign("userid_fieldblock",true);
////////////////////////////////////////////
//	action - 
	$value="";
		$value = ProcessLargeText(GetData($data,"action", ""),"","",MODE_VIEW);
	$xt->assign("action_value",$value);
	$xt->assign("action_fieldblock",true);

$body=array();
$body["begin"]="";
$body["end"]="";


if(!$pdf)
{
	$body["begin"].="<script type=\"text/javascript\" src=\"include/jquery.js\"></script>\r\n";
	$body["begin"].="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$pageObject->AddJsCode("\n window.TEXT_PDF_BUILD1='".jsreplace("")."';".
	"\n window.TEXT_PDF_BUILD2='".jsreplace("")."';");
}



$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
$xt->assign("html_attrs","lang=\"en\"");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Next Prev button
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
if(!@$_SESSION[$strTableName."_noNextPrev"])
{
	$where_next=$where_prev="";
	$order_next=$order_prev="";
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
			$_SESSION[$strTableName."_noNextPrev"] = 1;
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
		$order_next=$_SESSION[$strTableName."_order"];
	elseif($lenArr>0)
	{
		for($i=0;$i<$lenArr;$i++)
			$order_next .=(GetFieldByIndex($arrFieldForSort[$i]) ? ($order_next!="" ? ", " : " ORDER BY ").$arrFieldForSort[$i]." ".$arrHowFieldSort[$i] : "");
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
				else{
						$gasc=($arrHowFieldSort[$i]=="ASC" ? $dbg." IS NOT NULL" : "");
						$gdesc=($arrHowFieldSort[$i]=="ASC" ? "" : $dbg." IS NOT NULL");
						$gravn=($i!=$lenArr-1 ? $dbg." IS NULL" : "");
						$ganull=$gdnull="";
					}
				$where_next .=($where_next!="" ? " and (" : " (").($gasc=="" && $gravn=="" ? " 1=0 " : ($gasc!="" ? $gasc.$ganull : "").($gasc!="" && $gravn!="" ? " or " : "").$gravn." ");
				$where_prev .=($where_prev!="" ? " and (" : " (").($gdesc=="" && $gravn=="" ? " 1=0 " : ($gdesc!="" ? $gdesc.$gdnull : "").($gdesc!="" && $gravn!="" ? " or " : "").$gravn." ");
				$scob .=")";
			}
			else $flag=1;
		}
		$where_next =$where_next.$scob;
		$where_prev =$where_prev.$scob;
		$where_next=whereAdd($where_next,SecuritySQL("Search"));
		$where_prev=whereAdd($where_prev,SecuritySQL("Search"));
		if($flag==1)
		{
			$order_next="";
			for($i=0;$i<$lenArr;$i++)
				$order_next .=(GetFieldByIndex($arrFieldForSort[$i]) ? ($order_next!="" ? ", " : " ORDER BY ").$arrFieldForSort[$i]." ".$arrHowFieldSort[$i] : "");
		}
		
		$query = $queryData_project39_blocking->Copy();
		$query->RemoveBlobFields(true);
		//$sql_next=gSQLWhere($where.$where_next).$order_next;
		$sql_next = $query->toSql($where.$where_next, $order_next);
		//$sql_prev=gSQLWhere($where.$where_prev).$order_prev;
		$sql_prev = $query->toSql($where.$where_prev, $order_prev);
		
		if($where_next!="" and $order_next!="" and $where_prev!="" and $order_prev!="")
		{
					$sql_next.=" limit 1";
			$sql_prev.=" limit 1";
		
			$res_next=db_query($sql_next,$conn);		
			if($row_next=db_fetch_array($res_next))
			{
				$next[1]=$row_next["id"];
			}
			
			$res_prev=db_query($sql_prev,$conn);	
			if($row_prev=db_fetch_array($res_prev))
			{
				$prev[1]=$row_prev["id"];
			}
		}	
	}
}	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	  
if(!$pdf && !$all)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $nextlink=$prevlink="";
	if(count($next))
    {
		$xt->assign("next_button",true);
	 		$nextlink .="editid1=".htmlspecialchars(rawurlencode($next[1]));
		$xt->assign("nextbutton_attrs","onclick=\"window.location.href='project39_blocking_view.php?".$nextlink."'\"");
	}
	else 
		$xt->assign("next_button",false);	
	if(count($prev))
	{
		$xt->assign("prev_button",true);
			$prevlink .="editid1=".htmlspecialchars(rawurlencode($prev[1]));
		$xt->assign("prevbutton_attrs","onclick=\"window.location.href='project39_blocking_view.php?".$prevlink."'\"");
	}
    else 
		$xt->assign("prev_button",false);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='project39_blocking_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "project39_blocking_view.htm";
if(!$all)
{
	if(function_exists("BeforeShowView"))
		BeforeShowView($xt,$templatefile,$data);
	if(!$pdf)
		$xt->display($templatefile);
	break;
}

}


?>
