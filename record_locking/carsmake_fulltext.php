<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");


include("include/dbcommon.php");
include("include/carsmake_variables.php");


if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	DisplayCloseWindow($id); 
	return;
}
$id = @$_GET["id"];
$field = @$_GET["field"];
if(!CheckFieldPermissions($field))
	return DisplayCloseWindow($id);
	
if(!$gQuery->HasGroupBy())
{
	// Do not select any fields except current (full text) field.
	// If query has 'group by' clause then other fields are used in it and we may not simply cut 'em off.
	// Just don't do anything in that case.
	$gQuery->RemoveAllFieldsExcept($field, 'carsmake');
}

$keys=array();
$keys["id"]=postvalue("key1");
$where=KeyWhere($keys);


$sql = gSQLWhere($where);

$rs = db_query($sql,$conn);
if(!$rs || !($data=db_fetch_array($rs)))
  return DisplayCloseWindow($id);

$value=nl2br(htmlspecialchars($data[$field]));
echobig($value);
DisplayCloseWindow($id);
return;

function DisplayCloseWindow($id)
{
	echo "<br>";
	echo "<hr size=1 noshade>";
	//mlang_message("CLOSE_WINDOW")
	echo "<a href=# onclick=\"RemoveFlyDiv('".$id."');\">CLOSE_WINDOW</a>";
}

?>
