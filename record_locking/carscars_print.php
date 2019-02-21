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
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"))
{
	echo "<p>"."You don't have permissions to access this table"."<a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}

$all=postvalue("all");

$pageName = "print.php";

include('include/xtempl.php');
$xt = new Xtempl();

// Modify query: remove blob fields from fieldlist.
// Blob fields on a print page are shown using imager.php (for example).
// They don't need to be selected from DB in print.php itself.
$gQuery->RemoveBlobFields();

//	Before Process event
if(function_exists("BeforeProcessPrint"))
	BeforeProcessPrint($conn);

$strWhereClause="";

if (@$_REQUEST["a"]!="") 
{
	
	$sWhere = "1=0";	
	
//	process selection
	$selected_recs=array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["id"]=refine($_REQUEST["mdelete1"][mdeleteIndex($ind)]);
			$selected_recs[]=$keys;
		}
	}
	elseif(@$_REQUEST["selection"])
	{
		foreach(@$_REQUEST["selection"] as $keyblock)
		{
			$arr=explode("&",refine($keyblock));
			if(count($arr)<1)
				continue;
			$keys=array();
			$keys["id"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}
//	$strSQL = AddWhere($gstrSQL,$sWhere);
	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	$strSQL = gSQLWhere($strWhereClause);
}
if(postvalue("pdf"))
	$strWhereClause = @$_SESSION[$strTableName."_pdfwhere"];

$_SESSION[$strTableName."_pdfwhere"] = $strWhereClause;


$strOrderBy=$_SESSION[$strTableName."_order"];
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;
$strSQL.=" ".trim($strOrderBy);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryPrint"))
	BeforeQueryPrint($strSQL,$strWhereClause,$strOrderBy);

//	Rebuild SQL if needed
if($strSQL!=$strSQLbak)
{
//	changed $strSQL - old style	
	$numrows=GetRowCount($strSQL);
}
else
{
	$strSQL = gSQLWhere($strWhereClause);
	$strSQL.=" ".trim($strOrderBy);
	$numrows=gSQLRowCount($strWhereClause,0);
}

LogInfo($strSQL);

$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$recno=1;
$records=0;	
$pageindex=1;

$maxpages=1;

if(!$all)
{	
	if($numrows)
	{
		$maxRecords = $numrows;
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
	}
	if($numrows)
	{
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);
	
	
	//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
		$recordsonpage=$PageSize;
		
	$xt->assign("page_number",true);
	$xt->assign("maxpages",$maxpages);
	$xt->assign("pageno",$mypage);
}
else
{
	$rs=db_query($strSQL,$conn);
	$recordsonpage = $numrows;
	$maxpages=ceil($recordsonpage/30);
	$xt->assign("page_number",true);
	$xt->assign("maxpages",$maxpages);
	
}

$colsonpage=1;
if($colsonpage>$recordsonpage)
	$colsonpage=$recordsonpage;
if($colsonpage<1)
	$colsonpage=1;


//	fill $rowinfo array
	$pages = array();
	$rowinfo = array();
	$rowinfo["data"]=array();

	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowPrint"))
		{
			if(!BeforeProcessRowPrint($data))
				continue;
		}
		break;
	}
	while($data && ($all || $recno<=$PageSize))
	{
		$row=array();
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		for($col=1;$data && ($all || $recno<=$PageSize) && $col<=1;$col++)
		{
			$record=array();
			$recno++;
			$records++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["id"]));


//	category - 
			$value="";
				$value = ProcessLargeText(GetData($data,"category", ""),"field=category".$keylink,"",MODE_PRINT);
			$record["category_value"]=$value;

//	color - 
			$value="";
				$value = ProcessLargeText(GetData($data,"color", ""),"field=color".$keylink,"",MODE_PRINT);
			$record["color_value"]=$value;

//	Date Listed - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"Date Listed", "Short Date"),"field=Date+Listed".$keylink,"",MODE_PRINT);
			$record["Date_Listed_value"]=$value;

//	descr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"descr", ""),"field=descr".$keylink,"",MODE_PRINT);
			$record["descr_value"]=$value;

//	EPACity - 
			$value="";
				$value = ProcessLargeText(GetData($data,"EPACity", ""),"field=EPACity".$keylink,"",MODE_PRINT);
			$record["EPACity_value"]=$value;

//	EPAHighway - 
			$value="";
				$value = ProcessLargeText(GetData($data,"EPAHighway", ""),"field=EPAHighway".$keylink,"",MODE_PRINT);
			$record["EPAHighway_value"]=$value;

//	features - 
			$value="";
				$value = ProcessLargeText(GetData($data,"features", ""),"field=features".$keylink,"",MODE_PRINT);
			$record["features_value"]=$value;

//	Horsepower - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Horsepower", ""),"field=Horsepower".$keylink,"",MODE_PRINT);
			$record["Horsepower_value"]=$value;

//	id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_PRINT);
			$record["id_value"]=$value;

//	Make - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Make", ""),"field=Make".$keylink,"",MODE_PRINT);
			$record["Make_value"]=$value;

//	Model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Model", ""),"field=Model".$keylink,"",MODE_PRINT);
			$record["Model_value"]=$value;

//	Phone # - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Phone #", ""),"field=Phone+%23".$keylink,"",MODE_PRINT);
			$record["Phone___value"]=$value;

//	Picture - Database Image
			$value="";
							$value = "<img";
										$value.=" border=0";
				if(isEnableSection508())
					$value.= " alt=\"Image from DB\"";
				$value.=" src=\"carscars_imager.php?field=Picture".$keylink."\">";
			$record["Picture_value"]=$value;

//	Price - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Price", ""),"field=Price".$keylink,"",MODE_PRINT);
			$record["Price_value"]=$value;

//	UserID - 
			$value="";
				$value = ProcessLargeText(GetData($data,"UserID", ""),"field=UserID".$keylink,"",MODE_PRINT);
			$record["UserID_value"]=$value;

//	YearOfMake - 
			$value="";
				$value = ProcessLargeText(GetData($data,"YearOfMake", ""),"field=YearOfMake".$keylink,"",MODE_PRINT);
			$record["YearOfMake_value"]=$value;

//	zipcode - 
			$value="";
				$value = ProcessLargeText(GetData($data,"zipcode", ""),"field=zipcode".$keylink,"",MODE_PRINT);
			$record["zipcode_value"]=$value;
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			
			if(function_exists("BeforeMoveNextPrint"))
				BeforeMoveNextPrint($data,$row,$record);
				
			$row["grid_record"]["data"][]=$record;
			
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowPrint"))
				{
					if(!BeforeProcessRowPrint($data))
						continue;
				}
				break;
			}
		}
		if($col<=$colsonpage)
		{
			$row["grid_record"]["data"][count($row["grid_record"]["data"])-1]["endrecord_block"]=false;
		}
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
		
		if($all && $records>=30)
		{
			$page=array("grid_row" =>$rowinfo);
			$page["pageno"]=$pageindex;
			$pageindex++;
			$pages[] = $page;
			$records=0;
			$rowinfo=array();
		}
		
	}
	if(count($rowinfo))
	{
		$page=array("grid_row" =>$rowinfo);
		if($all)
			$page["pageno"]=$pageindex;
		$pages[] = $page;
	}
	
	for($i=0;$i<count($pages);$i++)
	{
	 	if($i<count($pages)-1)
			$pages[$i]["begin"]="<div name=page class=printpage>";
		else
		    $pages[$i]["begin"]="<div name=page>";
			
		$pages[$i]["end"]="</div>";
	}

	$page=array();
	$page["data"]=&$pages;
	$xt->assignbyref("page",$page);


	

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);
$xt->assign("html_attrs","lang=\"en\"");

$xt->assign("category_fieldheadercolumn",true);
$xt->assign("category_fieldheader",true);
$xt->assign("category_fieldcolumn",true);
$xt->assign("category_fieldfootercolumn",true);
$xt->assign("color_fieldheadercolumn",true);
$xt->assign("color_fieldheader",true);
$xt->assign("color_fieldcolumn",true);
$xt->assign("color_fieldfootercolumn",true);
$xt->assign("Date_Listed_fieldheadercolumn",true);
$xt->assign("Date_Listed_fieldheader",true);
$xt->assign("Date_Listed_fieldcolumn",true);
$xt->assign("Date_Listed_fieldfootercolumn",true);
$xt->assign("descr_fieldheadercolumn",true);
$xt->assign("descr_fieldheader",true);
$xt->assign("descr_fieldcolumn",true);
$xt->assign("descr_fieldfootercolumn",true);
$xt->assign("EPACity_fieldheadercolumn",true);
$xt->assign("EPACity_fieldheader",true);
$xt->assign("EPACity_fieldcolumn",true);
$xt->assign("EPACity_fieldfootercolumn",true);
$xt->assign("EPAHighway_fieldheadercolumn",true);
$xt->assign("EPAHighway_fieldheader",true);
$xt->assign("EPAHighway_fieldcolumn",true);
$xt->assign("EPAHighway_fieldfootercolumn",true);
$xt->assign("features_fieldheadercolumn",true);
$xt->assign("features_fieldheader",true);
$xt->assign("features_fieldcolumn",true);
$xt->assign("features_fieldfootercolumn",true);
$xt->assign("Horsepower_fieldheadercolumn",true);
$xt->assign("Horsepower_fieldheader",true);
$xt->assign("Horsepower_fieldcolumn",true);
$xt->assign("Horsepower_fieldfootercolumn",true);
$xt->assign("id_fieldheadercolumn",true);
$xt->assign("id_fieldheader",true);
$xt->assign("id_fieldcolumn",true);
$xt->assign("id_fieldfootercolumn",true);
$xt->assign("Make_fieldheadercolumn",true);
$xt->assign("Make_fieldheader",true);
$xt->assign("Make_fieldcolumn",true);
$xt->assign("Make_fieldfootercolumn",true);
$xt->assign("Model_fieldheadercolumn",true);
$xt->assign("Model_fieldheader",true);
$xt->assign("Model_fieldcolumn",true);
$xt->assign("Model_fieldfootercolumn",true);
$xt->assign("Phone___fieldheadercolumn",true);
$xt->assign("Phone___fieldheader",true);
$xt->assign("Phone___fieldcolumn",true);
$xt->assign("Phone___fieldfootercolumn",true);
$xt->assign("Picture_fieldheadercolumn",true);
$xt->assign("Picture_fieldheader",true);
$xt->assign("Picture_fieldcolumn",true);
$xt->assign("Picture_fieldfootercolumn",true);
$xt->assign("Price_fieldheadercolumn",true);
$xt->assign("Price_fieldheader",true);
$xt->assign("Price_fieldcolumn",true);
$xt->assign("Price_fieldfootercolumn",true);
$xt->assign("UserID_fieldheadercolumn",true);
$xt->assign("UserID_fieldheader",true);
$xt->assign("UserID_fieldcolumn",true);
$xt->assign("UserID_fieldfootercolumn",true);
$xt->assign("YearOfMake_fieldheadercolumn",true);
$xt->assign("YearOfMake_fieldheader",true);
$xt->assign("YearOfMake_fieldcolumn",true);
$xt->assign("YearOfMake_fieldfootercolumn",true);
$xt->assign("zipcode_fieldheadercolumn",true);
$xt->assign("zipcode_fieldheader",true);
$xt->assign("zipcode_fieldcolumn",true);
$xt->assign("zipcode_fieldfootercolumn",true);

	$record_header=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
		}
		$record_header["data"][]=$rheader;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);


$templatefile = "carscars_print.htm";
	
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($xt,$templatefile);

if(!postvalue("pdf"))
	$xt->display($templatefile);
else
{
	$xt->load_template($templatefile);
	$page = $xt->fetch_loaded();
	$pagewidth=postvalue("width")*1.05;
	$pageheight=postvalue("height")*1.05;
	$landscape=false;
	if(postvalue("all"))
	{
		if($pagewidth>$pageheight)
		{
			$landscape=true;
			if($pagewidth/$pageheight<297/210)
				$pagewidth = 297/210*$pageheight;
		}
		else
		{
			if($pagewidth/$pageheight<210/297)
				$pagewidth = 210/297*$pageheight;
		}
	}
}

