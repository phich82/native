<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

include("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
header("Pragma: no-cache");
header("Cache-Control: no-cache");

include("include/carscars_variables.php");

if(!@$_SESSION["UserID"])
{ 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	return;
}



$response = array();

$suggestAllContent=true;
if(postvalue("start"))
	$suggestAllContent=false;

if (isset($_GET['searchFor']) && postvalue('searchFor') != '') 
{

	$searchFor = postvalue('searchFor');
	$searchField = GoodFieldName( postvalue('searchField') );
	
		
/*
	$searchByField = ($searchField == '' || $searchField=="category");
*/
	
	$searchByField = ($searchField == '' || $searchField=="category");
	
	if($searchByField)
	{
	
		$field="category";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="color");
*/
	
	$searchByField = ($searchField == '' || $searchField=="color");
	
	if($searchByField)
	{
	
		$field="color";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
				
/*
	$searchByField = ($searchField == '' || $searchField=="EPACity");
*/
	
	$searchByField = ($searchField == '' || $searchField=="EPACity");
	
	if($searchByField)
	{
	
		$field="EPACity";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="EPAHighway");
*/
	
	$searchByField = ($searchField == '' || $searchField=="EPAHighway");
	
	if($searchByField)
	{
	
		$field="EPAHighway";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
			
/*
	$searchByField = ($searchField == '' || $searchField=="Horsepower");
*/
	
	$searchByField = ($searchField == '' || $searchField=="Horsepower");
	
	if($searchByField)
	{
	
		$field="Horsepower";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="id");
*/
	
	$searchByField = ($searchField == '' || $searchField=="id");
	
	if($searchByField)
	{
	
		$field="id";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="Make");
*/
	
	$searchByField = ($searchField == '' || $searchField=="Make");
	
	if($searchByField)
	{
	
		$field="Make";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="Model");
*/
	
	$searchByField = ($searchField == '' || $searchField=="Model");
	
	if($searchByField)
	{
	
		$field="Model";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="Phone__");
*/
	
	$searchByField = ($searchField == '' || $searchField=="Phone__");
	
	if($searchByField)
	{
	
		$field="Phone #";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="Price");
*/
	
	$searchByField = ($searchField == '' || $searchField=="Price");
	
	if($searchByField)
	{
	
		$field="Price";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="UserID");
*/
	
	$searchByField = ($searchField == '' || $searchField=="UserID");
	
	if($searchByField)
	{
	
		$field="UserID";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="YearOfMake");
*/
	
	$searchByField = ($searchField == '' || $searchField=="YearOfMake");
	
	if($searchByField)
	{
	
		$field="YearOfMake";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
		
/*
	$searchByField = ($searchField == '' || $searchField=="zipcode");
*/
	
	$searchByField = ($searchField == '' || $searchField=="zipcode");
	
	if($searchByField)
	{
	
		$field="zipcode";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like ".isEnableUpper("'%".db_addslashes($searchFor)."%'") : " like ".isEnableUpper("'".db_addslashes($searchFor)."%'");
		$whereCondition = " ".isEnableUpper(GetFullFieldName($field)).$whereCondition;
		$whereCondition = whereAdd($gsqlWhereExpr,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);
			$i=0;
			while ($row = db_fetch_numarray($rs)) 
			{
				$i++;
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
				if ($i>10)
					break;
			}
		}
		}
	db_close($conn);
}

$response = array_unique($response); 
sort($response);

for( $i=0;$i<10 && $i<count($response);$i++) 
{
	$value=$response[$i];
	if($suggestAllContent)
	{
		$str=substr($value,0,50);
		$pos=my_stripos($str,$searchFor,0);
		if($pos===false)
			echo $str;
		else
			echo substr($str,0,$pos)."<b>".substr($str,$pos,strlen($searchFor))."</b>".substr($str,$pos+strlen($searchFor));
		echo "\n";
	}
	else
		echo  "<b>".substr($value,0,strlen($searchFor))."</b>".substr($value,strlen($searchFor),50-strlen($searchFor))."\n";
}
?>