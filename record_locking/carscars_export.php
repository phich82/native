<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");


include("include/dbcommon.php");
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

// Modify query: remove blob fields from fieldlist.
// Blob fields on an export page are shown using imager.php (for example).
// They don't need to be selected from DB in export.php itself.
$gQuery->RemoveBlobFields();


//	Before Process event
if(function_exists("BeforeProcessExport"))
	BeforeProcessExport($conn);

$strWhereClause="";

$options = "1";
if (@$_REQUEST["a"]!="")
{
	$options = "";
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


	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
}

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


$mypage=1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryExport"))
		BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
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

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(@$_REQUEST["type"]=="excel")
	{
		ExportToExcel();
	}
	else if(@$_REQUEST["type"]=="word")
	{
		ExportToWord();
	}
	else if(@$_REQUEST["type"]=="xml")
	{
		ExportToXML();
	}
	else if(@$_REQUEST["type"]=="csv")
	{
		ExportToCSV();
	}
	else if(@$_REQUEST["type"]=="pdf")
	{
		ExportToPDF();
	}

	db_close($conn);
	return;
}

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include('include/xtempl.php');
$xt = new Xtempl();
if($options)
{
	$xt->assign("rangeheader_block",true);
	$xt->assign("range_block",true);
}
$body=array();
$body["begin"]="<form action=\"carscars_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->assign("html_attrs","lang=\"en\"");
$xt->display("carscars_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=carscars.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-Type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=carscars.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn;
	header("Content-Type: text/xml");
	header("Content-Disposition: attachment;Filename=carscars.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("category"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"category",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("color"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"color",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Date Listed"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Date Listed",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("descr"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"descr",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("EPACity"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"EPACity",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("EPAHighway"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"EPAHighway",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("features"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"features",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Horsepower"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Horsepower",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Make"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Make",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Model"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Model",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Phone #"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Phone #",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Picture"));
		echo "<".$field.">";
		echo "LONG BINARY DATA - CANNOT BE DISPLAYED";
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Price"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Price",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("UserID"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"UserID",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("YearOfMake"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"YearOfMake",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("zipcode"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"zipcode",""));
		echo "</".$field.">\r\n";
		echo "</row>\r\n";
		$i++;
		$row=db_fetch_array($rs);
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn;
	header("Content-Type: application/csv");
	header("Content-Disposition: attachment;Filename=carscars.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"category\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"color\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Date Listed\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"descr\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"EPACity\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"EPAHighway\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"features\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Horsepower\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Make\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Model\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Phone #\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Picture\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Price\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"UserID\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"YearOfMake\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"zipcode\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"category",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"color",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"Date Listed",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"descr",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"EPACity",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"EPAHighway",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"features",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Horsepower",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Make",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Model",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Phone #",$format)).'"';
		if($outstr!="")
			$outstr.=",";
		$outstr.='"'.htmlspecialchars("LONG BINARY DATA - CANNOT BE DISPLAYED").'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Price",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"UserID",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"YearOfMake",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"zipcode",$format)).'"';
		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;

}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn;
	if(!($row=db_fetch_array($rs)))
		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Category").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Color").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Date Listed").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Descr").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("EPACity").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("EPAHighway").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Features").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Horsepower").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Id").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Make").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Model").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Phone #").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Picture").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Price").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("User ID").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Year Of Make").'</td>';	
	echo '<td style="width: 100" x:str>'.PrepareForExcel("Zipcode").'</td>';	
	}
	else
	{
		echo "<td>"."Category"."</td>";
		echo "<td>"."Color"."</td>";
		echo "<td>"."Date Listed"."</td>";
		echo "<td>"."Descr"."</td>";
		echo "<td>"."EPACity"."</td>";
		echo "<td>"."EPAHighway"."</td>";
		echo "<td>"."Features"."</td>";
		echo "<td>"."Horsepower"."</td>";
		echo "<td>"."Id"."</td>";
		echo "<td>"."Make"."</td>";
		echo "<td>"."Model"."</td>";
		echo "<td>"."Phone #"."</td>";
		echo "<td>"."Picture"."</td>";
		echo "<td>"."Price"."</td>";
		echo "<td>"."User ID"."</td>";
		echo "<td>"."Year Of Make"."</td>";
		echo "<td>"."Zipcode"."</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"category",$format));
		else
			echo htmlspecialchars(GetData($row,"category",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"color",$format));
		else
			echo htmlspecialchars(GetData($row,"color",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Date Listed",$format));
		else
			echo htmlspecialchars(GetData($row,"Date Listed",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"descr",$format));
		else
			echo htmlspecialchars(GetData($row,"descr",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"EPACity",$format));
		else
			echo htmlspecialchars(GetData($row,"EPACity",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"EPAHighway",$format));
		else
			echo htmlspecialchars(GetData($row,"EPAHighway",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"features",$format));
		else
			echo htmlspecialchars(GetData($row,"features",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Horsepower",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"id",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Make",$format));
		else
			echo htmlspecialchars(GetData($row,"Make",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Model",$format));
		else
			echo htmlspecialchars(GetData($row,"Model",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Phone #",$format));
		else
			echo htmlspecialchars(GetData($row,"Phone #",$format));
	echo '</td>';
	echo '<td>';
		echo "LONG BINARY DATA - CANNOT BE DISPLAYED";
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Price",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"UserID",$format));
		else
			echo htmlspecialchars(GetData($row,"UserID",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"YearOfMake",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"zipcode",$format));
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

}

function XMLNameEncode($strValue)
{	
	$search=array(" ","#","'","/","\\","(",")",",","[");
	$ret=str_replace($search,"",$strValue);
	$search=array("]","+","\"","-","_","|","}","{","=");
	$ret=str_replace($search,"",$ret);
	return $ret;
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}





?>