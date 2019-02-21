<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");


include("include/dbcommon.php");

if(!@$_SESSION["UserID"])
{
	header("Location: login.php");
	return;
}


include('include/xtempl.php');
$xt = new Xtempl();


//	Before Process event
if(function_exists("BeforeProcessMenu"))
	BeforeProcessMenu($conn);
$xt->assign("body",true);
$body=array();
$body["begin"] = "<script type=\"text/javascript\" src=\"include/jquery.js\"></script>".
"<script type=\"text/javascript\" src=\"include/jsfunctions.js\"></script>";
$xt->assignbyref("body",$body);

$xt->assign("username",$_SESSION["UserID"]);
$xt->assign("changepwd_link",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$xt->assign("changepwdlink_attrs","onclick=\"window.location.href='changepwd.php';return false;\"");
$xt->assign("logoutlink_attrs","onclick=\"window.location.href='login.php?a=logout';\"");
$xt->assign("html_attrs","lang=\"en\"");
$xt->assign("loggedas_block",true);
$xt->assign("logout_link",true);
$createmenu = false;
$count_menu=0;
$redirect_menu="";
$strPerm = GetUserPermissions("carsmake");
$allow_carsmake= (strpos($strPerm, "A")!==false || strpos($strPerm, "S")!==false);
if($allow_carsmake)
{
	$createmenu=true;
	$xt->assign("carsmake_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("carsmake");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("carsmake_tablelink_attrs","href=\"carsmake_".$page.".php\"");
	$redirect_menu="carsmake_".$page.".php";
	$count_menu++;
}
$strPerm = GetUserPermissions("carsmodels");
$allow_carsmodels= (strpos($strPerm, "A")!==false || strpos($strPerm, "S")!==false);
if($allow_carsmodels)
{
	$createmenu=true;
	$xt->assign("carsmodels_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("carsmodels");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("carsmodels_tablelink_attrs","href=\"carsmodels_".$page.".php\"");
	$redirect_menu="carsmodels_".$page.".php";
	$count_menu++;
}
$strPerm = GetUserPermissions("carsusers");
$allow_carsusers= (strpos($strPerm, "A")!==false || strpos($strPerm, "S")!==false);
if($allow_carsusers)
{
	$createmenu=true;
	$xt->assign("carsusers_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("carsusers");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("carsusers_tablelink_attrs","href=\"carsusers_".$page.".php\"");
	$redirect_menu="carsusers_".$page.".php";
	$count_menu++;
}
$strPerm = GetUserPermissions("project41_audit");
$allow_project41_audit= (strpos($strPerm, "A")!==false || strpos($strPerm, "S")!==false);
if($allow_project41_audit)
{
	$createmenu=true;
	$xt->assign("project41_audit_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("project41_audit_tablelink_attrs","href=\"project41_audit_".$page.".php\"");
	$redirect_menu="project41_audit_".$page.".php";
	$count_menu++;
}
$strPerm = GetUserPermissions("carscars");
$allow_carscars= (strpos($strPerm, "A")!==false || strpos($strPerm, "S")!==false);
if($allow_carscars)
{
	$createmenu=true;
	$xt->assign("carscars_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("carscars");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("carscars_tablelink_attrs","href=\"carscars_".$page.".php\"");
	$redirect_menu="carscars_".$page.".php";
	$count_menu++;
}

if($createmenu)
	$xt->assign("menustyle_block",true);




if($count_menu<2)
	header("Location: ".$redirect_menu); 

$templatefile="menu.htm";
if(function_exists("BeforeShowMenu"))
	BeforeShowMenu($xt,$templatefile);

$xt->display($templatefile);
?>