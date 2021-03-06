<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");

include("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

if (@$_POST["a"] == "logout" || @$_GET["a"] == "logout") {
    $audit->LogLogout();

    session_unset();
    setcookie("username", "", time()-365*1440*60);
    setcookie("password", "", time()-365*1440*60);
    header("Location: login.php");
    exit();
}

include('include/xtempl.php');
$xt = new Xtempl();



//	Before Process event
if (function_exists("BeforeProcessLogin")) {
    BeforeProcessLogin($conn);
}

$myurl = @$_SESSION["MyURL"];
unset($_SESSION["MyURL"]);



$message="";

$pUsername = postvalue("username");
$pPassword = postvalue("password");

$is508 = isEnableSection508();

$rememberbox_checked="";
$rememberbox_attrs = ($is508==true ? "id=\"remember_password\" " : "")."name=\"remember_password\" value=\"1\"";
if (@$_COOKIE["username"] || @$_COOKIE["password"]) {
    $rememberbox_checked=" checked";
}

$logacc=true;

    if ($audit->LoginAccess()<>"") {
        $logacc=false;
        $message="Access denied for ".$audit->LoginAccess()." minutes";
    }

if (@$_POST["btnSubmit"] == "Login" && $logacc) {
    if (@$_POST["remember_password"] == 1) {
        setcookie("username", $pUsername, time()+365*1440*60);
        setcookie("password", $pPassword, time()+365*1440*60);
        $rememberbox_checked=" checked";
    } else {
        setcookie("username", "", time()-365*1440*60);
        setcookie("password", "", time()-365*1440*60);
        $rememberbox_checked = "";
    }
    //   	 username and password are stored in the database
    $strUsername = (string)$pUsername;
    $strPassword = (string)$pPassword;
    $sUsername = $strUsername;
    $sPassword = $strPassword;
    $rstemp = db_query("select * from `carsusers` where 1=0", $conn);
        
    if (FieldNeedQuotes($rstemp, $cUserNameField)) {
        $strUsername = "'".db_addslashes($strUsername)."'";
    } else {
        $strUsername = (0+$strUsername);
    }
    if (FieldNeedQuotes($rstemp, $cPasswordField)) {
        $strPassword = "'".db_addslashes($strPassword)."'";
    } else {
        $strPassword = (0+$strPassword);
    }
    $strSQL = "select * from `carsusers` where "
                    .AddFieldWrappers($cUserNameField)."=".$strUsername
                    ." and "
                    .AddFieldWrappers($cPasswordField)."=".$strPassword;
    $retval=true;
    $logged=false;
    if (function_exists("BeforeLogin")) {
        $retval=BeforeLogin($pUsername, $pPassword, $message);
    }
    if ($retval) {
        $rs=db_query($strSQL, $conn);
        $data=db_fetch_array($rs);
        if ($data) {
            if (@$data[$cUserNameField]==$sUsername && @$data[$cPasswordField]==$sPassword) {
                $logged=true;
            }
        }
    }
    
    if ($logged) {
        $_SESSION["UserID"] = $pUsername;
        $_SESSION["AccessLevel"] = ACCESS_LEVEL_USER;

        $_SESSION["GroupID"] = $data["username"];
        if ($_SESSION["GroupID"]=="<Default>") {
            $_SESSION["AccessLevel"] = ACCESS_LEVEL_ADMINGROUP;
        }
        if ($_SESSION["GroupID"]=="admin") {
            $_SESSION["AccessLevel"] = ACCESS_LEVEL_ADMINGROUP;
        }
        



        $_SESSION["OwnerID"] = $data["id"];
        $_SESSION["_carsmake_OwnerID"] = $data["id"];
    
        $audit->LogLogin();
        $audit->LoginSuccessful();

        if (function_exists("AfterSuccessfulLogin")) {
            AfterSuccessfulLogin($pUsername, $pPassword, $data);
        }
        if ($myurl) {
            header("Location: ".$myurl);
        } else {
            header("Location: menu.php");
        }
        return;
    } else {
        $audit->LogLoginFailed();
        $audit->LoginUnsuccessful();

        if (function_exists("AfterUnsuccessfulLogin")) {
            AfterUnsuccessfulLogin($pUsername, $pPassword, $message);
        }
        if ($message=="") {
            $message = "Invalid Login";
        }
    }
}

$xt->assign("rememberbox_attrs", $rememberbox_attrs.$rememberbox_checked);


    $xt->assign("guestlink_block", true);

    
$_SESSION["MyURL"]=$myurl;
if ($myurl) {
    $xt->assign("guestlink_attrs", "href=\"".$myurl."\"");
} else {
    $xt->assign("guestlink_attrs", "href=\"menu.php\"");
}
    
if (@$_POST["username"] || @$_GET["username"]) {
    $xt->assign("username_attrs", ($is508==true ? "id=\"username\" " : "")."value=\"".htmlspecialchars($pUsername)."\"");
} else {
    $xt->assign("username_attrs", ($is508==true ? "id=\"username\" " : "")."value=\"".htmlspecialchars(refine(@$_COOKIE["username"]))."\"");
}


$password_attrs="onkeydown=\"e=event; if(!e) e = window.event; if (e.keyCode != 13) return; e.cancel = true; e.cancelBubble=true; document.forms[0].submit(); return false;\"";
if (@$_POST["password"]) {
    $password_attrs.=($is508==true ? " id=\"password\"": "")." value=\"".htmlspecialchars($pPassword)."\"";
} else {
    $password_attrs.=($is508==true ? " id=\"password\"": "")." value=\"".htmlspecialchars(refine(@$_COOKIE["password"]))."\"";
}
$xt->assign("password_attrs", $password_attrs);

if (@$_GET["message"]=="expired") {
    $message = "Your session has expired. Please login again.";
}


if ($message) {
    $xt->assign("message_block", true);
    $xt->assign("message", $message);
}

$body=array();
$body["begin"]="<form method=post action=\"login.php\" id=form1 name=form1>
		<input type=hidden name=btnSubmit value=\"Login\">";
$body["end"]="</form>
<script>
function elementVisible(jselement)
{ 
	do
	{
		if (jselement.style.display.toUpperCase() == 'NONE')
			return false;
		jselement=jselement.parentNode; 
	}
	while (jselement.tagName.toUpperCase() != 'BODY'); 
	return true;
}
if(elementVisible(document.forms[0].elements['username']))
	document.forms[0].elements['username'].focus();
</script>";
$xt->assignbyref("body", $body);

$xt->assign("username_label", true);
$xt->assign("password_label", true);
$xt->assign("remember_password_label", true);
if (isEnableSection508()) {
    $xt->assign_section("username_label", "<label for=\"username\">", "</label>");
    $xt->assign_section("password_label", "<label for=\"password\">", "</label>");
    $xt->assign_section("remember_password_label", "<label for=\"remember_password\">", "</label>");
}
$xt->assign("html_attrs", "lang=\"en\"");
$templatefile="login.htm";
if (function_exists("BeforeShowLogin")) {
    BeforeShowLogin($xt, $templatefile);
}

$xt->display($templatefile);
