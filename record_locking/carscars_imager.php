<?php 
include_once("include/dbcommon.php");
if(!isset($pdf))
{
	ini_set("display_errors","1");
	ini_set("display_startup_errors","1");
	

	include("include/carscars_variables.php");
	if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
	{ 
		header("Location: login.php"); 
		return;
	}

	$field = @$_GET["field"];
	if(!CheckFieldPermissions($field))
		return DisplayNoImage();

//	construct sql

$keys=array();
$keys["id"]=postvalue("key1");
	
}
else
{
	$field = @$params["field"];
	$keys=array();
	$keys["id"]=@$params["key1"];
}

if(!$gQuery->HasGroupBy())
{
	// Do not select any fields except current (image) field.
	// If query has 'group by' clause then other fields are used in it and we may not simply cut 'em off.
	// Just don't do anything in that case.
	$gQuery->RemoveAllFieldsExcept($field, 'carscars');
}

$where=KeyWhere($keys);


$sql = gSQLWhere($where);

$rs = db_query($sql,$conn);

if(isset($pdf))
{
	if($rs && ($data=db_fetch_array($rs)))
		$file = $data[$field];
}
else
{

if(!$rs || !($data=db_fetch_array($rs)))
  return DisplayNoImage();


$value=db_stripslashesbinary($data[$field]);
if(!$value)
{
	if(@$_GET["alt"])
	{
		$value=db_stripslashesbinary($data[$_GET["alt"]]);
		if(!$value)
			return DisplayNoImage();
	}
	else
		return DisplayNoImage();
}

$itype=SupposeImageType($value);
if($itype)
	header("Content-Type: ".$itype);
else
	return DisplayFile();
echobig($value);
return;
}


?>
