<?php 


////////////////////////////////////////////////////////////////////////////////
// table and field info functions
////////////////////////////////////////////////////////////////////////////////

function GetDPType($tName) {
	return GetTableData($tName, ".dpType", $tName);
}


/**
 * Returns array of detail keys for passed masterTable
 *
 * @param string $mTableName - it's data sourse table name, $tName - current table
 * @return array if success otherwise false
 */
function GetDetailKeysByMasterTable($mTableName = "", $tName = "")
{
	global $masterTablesData;
	if(!$mTableName)
		return false;
	if(!$tName)
		$tName = $strTableName;
	foreach($masterTablesData[$tName] as $mTableDataArr)
	{
		if ($mTableDataArr['mDataSourceTable'] == $mTableName)
			return $mTableDataArr['detailKeys'];
	}
	return false;
}

/**
 * Returns array of master tables , which are detail for current table
 * tName - It's data source table name
 * @return array if success otherwise false
 */
function GetMasterTablesArr($tName) 
{
	global $masterTablesData;
	return $masterTablesData[$tName];
}
/**
 * Returns array of detail tables , which are detail for current table
 * tName - It's data source table name
 * @return array if success otherwise false
 */
function GetDetailTablesArr($tName) 
{
	global $detailsTablesData;
	return $detailsTablesData[$tName];
}
/**
 * Returns array of master keys for passed detailTable
 *
 * @param string $dTableName - it's detail data sourse table name, $tName - current table name
 * @return array if success otherwise false
 */
function GetMasterKeysByDetailTable($dTableName, $tName = "")
{
	global $detailsTablesData;
	if(!$dTableName)
		return false;
	if(!$tName)
		$tName = $strTableName;
	foreach ($detailsTablesData[$tName] as $dTableDataArr)
	{
		if ($dTableDataArr['dDataSourceTable'] == $dTableName)
			return $dTableDataArr['masterKeys'];
	}
	return false;
}
/**
 * Returns array of detail keys for passed detailTable
 *
 * @param string $dTableName - It's detail data sourse table name
 * @return array if success otherwise false
 */
function GetDetailKeysByDetailTable($dTableName, $tName)
{
	global $detailsTablesData;
		
	foreach ($detailsTablesData[$tName] as $dTableDataArr)
	{
		if ($dTableDataArr['dDataSourceTable'] == $dTableName)
			return $dTableDataArr['detailKeys'];
	}
	
	return false;
}



function GetTableData($table,$key,$default)
{
	global $strTableName,$tables_data;
	if(!$table) 
		$table = $strTableName;
	if(!array_key_exists($table,$tables_data))
		return $default;
	if(!array_key_exists($key,$tables_data[$table]))
		return $default;
	return $tables_data[$table][$key];
}

function GetFieldData($table,$field,$key,$default)
{
	global $strTableName,$tables_data;
	if(!$table) 
		$table = $strTableName;
	if(!array_key_exists($table,$tables_data))
		return $default;
	if(!array_key_exists($field,$tables_data[$table]))
		return $default;
	if(!array_key_exists($key,$tables_data[$table][$field]))
		return $default;
	return $tables_data[$table][$field][$key];
}

function GetFieldByIndex($index, $table="")
{
	global $strTableName,$tables_data;
	if(!$table) 
		$table = $strTableName;
	if(!array_key_exists($table,$tables_data))
		return null;
	foreach($tables_data[$table] as $key=>$value)
	{
		if(!is_array($value) || !array_key_exists("Index",$value))
			continue;
		if($value["Index"]==$index and GetFieldIndex($key))
			return $key;
	}
	return null;
}

// return field label
function Label($field,$table="")
{
	return GetFieldData($table,$field,"Label",$field);
}

// return filename field if any
function GetFilenameField($field,$table="")
{
	return GetFieldData($table,$field,"Filename","");
}

//	return hyperlink prefix
function GetLinkPrefix($field,$table="")
{
	return GetFieldData($table,$field,"LinkPrefix","");
}

//	return database field type
//	using ADO DataTypeEnum constants
//	the full list available at:
//	http://msdn.microsoft.com/library/default.asp?url=/library/en-us/ado270/htm/mdcstdatatypeenum.asp
function GetFieldType($field,$table="")
{
	return GetFieldData($table,$field,"FieldType","");
}

function IsAutoincField($field,$table="")
{
	return GetFieldData($table,$field,"AutoInc",false);
}

function IsUseiBox($field,$table="")
{
	return GetFieldData($table,$field,"UseiBox",false);
}

//	return Edit format
function GetEditFormat($field,$table="")
{
	return GetFieldData($table,$field,"EditFormat","");
}

//	return View format
function ViewFormat($field,$table="")
{
	return GetFieldData($table,$field,"ViewFormat","");
}

//	show time in datepicker or not
function DateEditShowTime($field,$table="")
{
	return GetFieldData($table,$field,"ShowTime",false);
}

//	use FastType Lookup wizard or not
function FastType($field,$table="")
{
	return GetFieldData($table,$field,"FastType",false);
}

function LookupControlType($field,$table="")
{
	return GetFieldData($table,$field,"LCType",LCT_DROPDOWN);
}


//	is Lookup wizard dependent or not
function UseCategory($field,$table="")
{
	return GetFieldData($table,$field,"UseCategory",false);
}

//	is Lookup wizard with multiple selection
function Multiselect($field,$table="")
{
	return GetFieldData($table,$field,"Multiselect",false);
}

function ShowThumbnail($field,$table="")
{
	return GetFieldData($table,$field,"ShowThumbnail",false);
}

function GetImageWidth($field,$table="")
{
	return GetFieldData($table,$field,"ImageWidth",0);
}

function GetImageHeight($field,$table="")
{
	return GetFieldData($table,$field,"ImageHeight",0);
}

//	return Lookup Wizard Where expression
function GetLWWhere($field,$table="")
{
	global $strTableName;
	if(!$table) 
		$table = $strTableName;
	return "";
}

function GetLookupType($field,$table="")
{
	return GetFieldData($table,$field,"LookupType",0);
}

function GetLookupTable($field,$table="")
{
	return GetFieldData($table,$field,"LookupTable","");
}

function GetLWLinkField($field,$table="")
{
	return GetFieldData($table,$field,"LinkField","");
}

function GetLWLinkFieldType($field,$table="")
{
	return GetFieldData($table,$field,"LinkFieldType",0);
}

function GetLWDisplayField($field,$table="")
{
	return GetFieldData($table,$field,"DisplayField","");
}

function NeedEncode($field,$table="")
{
	return GetFieldData($table,$field,"NeedEncode",false);
}

function AppearOnListPage($field,$table="")
{
	return GetFieldData($table,$field,"ListPage",false);
}

function GetTablesList()
{
	$arr=array();
	$strPerm = GetUserPermissions("carsmake");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="carsmake";
	}
	$strPerm = GetUserPermissions("carsmodels");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="carsmodels";
	}
	$strPerm = GetUserPermissions("carsusers");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="carsusers";
	}
	$strPerm = GetUserPermissions("project39_blocking");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="project39_blocking";
	}
	$strPerm = GetUserPermissions("project41_audit");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="project41_audit";
	}
	$strPerm = GetUserPermissions("carscars");
	if(strpos($strPerm, "P")!==false)
	{
		$arr[]="carscars";
	}
	return $arr;
}

function GetTablesListReport()
{
	$arr=array();
	$strPerm = GetUserPermissions("carsmake");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="carsmake";
	}
	$strPerm = GetUserPermissions("carsmodels");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="carsmodels";
	}
	$strPerm = GetUserPermissions("carsusers");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="carsusers";
	}
	$strPerm = GetUserPermissions("project39_blocking");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="project39_blocking";
	}
	$strPerm = GetUserPermissions("project41_audit");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="project41_audit";
	}
	$strPerm = GetUserPermissions("carscars");
	if(strpos($strPerm, "P")!==false || strpos($strPerm, "S")!==false)
	{
		$arr[]="carscars";
	}
	return $arr;
}


function GetFieldsList($table="")
{
	global $strTableName,$tables_data;
	if(!$table)
		$table = $strTableName;
	if(!array_key_exists($table,$tables_data))
		return array();
	$t = array_keys($tables_data[$table]);
	$arr=array();
	foreach($t as $f)
		if(substr($f,0,1)!=".")
			$arr[]=$f;
	return $arr;
}

function GetNBFieldsList($table="")
{
	$t = GetFieldsList($table);
	$arr=array();
	foreach($t as $f)
		if(!IsBinaryType(GetFieldType($f,$table)))
			$arr[]=$f;
	return $arr;
}

//	Category Control field for dependent dropdowns
function CategoryControl($field,$table="")
{
	return GetFieldData($table,$field,"CategoryControl","");
}

//	create Thumbnail or not
function GetCreateThumbnail($field,$table="")
{
	return GetFieldData($table,$field,"CreateThumbnail",false);
}

//	return Thumbnail prefix
function GetThumbnailPrefix($field,$table="")
{
	return GetFieldData($table,$field,"ThumbnailPrefix","");
}

//	resize on upload
function ResizeOnUpload($field,$table="")
{
	return GetFieldData($table,$field,"ResizeImage",false);
}

//	get size to reduce image after upload
function GetNewImageSize($field,$table="")
{
	return GetFieldData($table,$field,"NewSize",0);
}

//	return field name
function GetFieldByGoodFieldName($field,$table="")
{
	global $strTableName,$tables_data;
	if(!$table)
		$table=$strTableName;
	if(!array_key_exists($table,$tables_data))
		return "";

	foreach($tables_data[$table] as $key=>$value)
	{
		if(count($value)>1)
		{
			if($value["GoodName"]==$field)
				return $key;
		}
	}
	return "";
}

//	return the full database field original name
function GetFullFieldName($field,$table="")
{
	$fname=AddTableWrappers(GetOriginalTableName($table)).".".AddFieldWrappers($field);
	return GetFieldData($table,$field,"FullName",$fname);
}

//     return height of text area
function GetNRows($field,$table="")
{
	return GetFieldData($table,$field,"nRows",$field);
}

//     return width of text area
function GetNCols($field,$table="")
{
	return GetFieldData($table,$field,"nCols",$field);
}

//	return original table name
function GetOriginalTableName($table="")
{
	global $strTableName;
	if(!$table)
		$table=$strTableName;
	return GetTableData($table,".OriginalTable",$table);
}

//	return list of key fields
function GetTableKeys($table="")
{
	return GetTableData($table,".Keys",array());
}


//	return number of chars to show before More... link
function GetNumberOfChars($table="")
{
	return GetTableData($table,".NumberOfChars",0);
}

//	return table short name
function GetTableURL($table="")
{
	global $strTableName;
	if(!$table)
		$table=$strTableName;
	if("carsmake"==$table) 
		return "carsmake";
	if("carsmodels"==$table) 
		return "carsmodels";
	if("carsusers"==$table) 
		return "carsusers";
	if("project39_blocking"==$table) 
		return "project39_blocking";
	if("project41_audit"==$table) 
		return "project41_audit";
	if("carscars"==$table) 
		return "carscars";
}

//	return table Owner ID field
function GetTableOwnerID($table="")
{
	return GetTableData($table,".OwnerID",0);
}

//	is field marked as required
function IsRequired($field,$table="")
{
	return GetFieldData($table,$field,"IsRequired",false);
}

//	use Rich Text Editor or not
function UseRTE($field,$table="")
{
	return GetFieldData($table,$field,"UseRTE",false);
}

//	add timestamp to filename when uploading files or not
function UseTimestamp($field,$table="")
{
	return GetFieldData($table,$field,"UseTimestamp",false);
}

function GetUploadFolder($field, $table="")
{
	$path = GetFieldData($table,$field,"UploadFolder","");
	if(strlen($path) && substr($path,strlen($path)-1) != "/")
		$path.="/";
	return $path;
}

function GetFieldIndex($field, $table="")
{
	return GetFieldData($table,$field,"Index",0);
}

//	return Date field edit type
function DateEditType($field,$table="")
{
	return GetFieldData($table,$field,"DateEditType",0);
}

// returns text edit parameters
function GetEditParams($field, $table="")
{
	return GetFieldData($table,$field,"EditParams","");
}

// returns Chart type
function GetChartType($shorttable)
{
	return "";
}

////////////////////////////////////////////////////////////////////////////////
// data output functions
////////////////////////////////////////////////////////////////////////////////

//	format field value for output
function GetData($data, $field, $format)
{
	return GetDataInt($data[$field], $data, $field, $format);
}

function GetDataValue($value, $field, $format)
{
	return GetDataInt($value, null, $field, $format);
}

//	GetData Internal
function GetDataInt($value, $data, $field, $format)
{
	global $strTableName;
	if($format == FORMAT_CUSTOM && $data)
	{
		return CustomExpression($value,$data,$field,"");
	}

	$ret="";
// long binary data?
	if(IsBinaryType(GetFieldType($field)))
	{
		$ret="LONG BINARY DATA - CANNOT BE DISPLAYED";
	} 
	else
		$ret = $value;
	if($ret===false)
		$ret="";
	
	if($format == FORMAT_DATE_SHORT) 
		$ret = format_shortdate(db2time($value));
	else if($format == FORMAT_DATE_LONG) 
		$ret = format_longdate(db2time($value));
	else if($format == FORMAT_DATE_TIME) 
		$ret = str_format_datetime(db2time($value));
	else if($format == FORMAT_TIME) 
	{
		if(IsDateFieldType(GetFieldType($field)))
			$ret = str_format_time(db2time($value));
		else
		{
			$numbers=parsenumbers($value);
			if(!count($numbers))
				return "";
			while(count($numbers)<3)
				$numbers[]=0;
			$ret = str_format_time(array(0,0,0,$numbers[0],$numbers[1],$numbers[2]));
		}
	}
	else if($format == FORMAT_NUMBER) 
		$ret = str_format_number($value);
	else if($format == FORMAT_CURRENCY) 
		$ret = str_format_currency($value);
	else if($format == FORMAT_CHECKBOX) 
	{
		$ret="<img src=\"images/check_";
		if($value && $value!=0)
			$ret.="yes";
		else
			$ret.="no";
		$ret.=".gif\" border=0";
		if(isEnableSection508())
			$ret.= " alt=\" \"";
		$ret.= ">";
	}
	else if($format == FORMAT_PERCENT) 
	{
		if($value!="")
			$ret = ($value*100)."%";
	}
	else if($format == FORMAT_PHONE_NUMBER)
	{
		if(strlen($ret)==7)
			$ret=substr($ret,0,3)."-".substr($ret,3);
		else if(strlen($ret)==10)
			$ret="(".substr($ret,0,3).") ".substr($ret,3,3)."-".substr($ret,6);
	}
	else if($format == FORMAT_FILE_IMAGE)
	{
		if(!CheckImageExtension($ret))
			return "";
			
		$thumbnailed=false;
		$thumbprefix="";
		if($thumbnailed)
		{
		 	// show thumbnail
			$thumbname=$thumbprefix.$ret;
			if(substr(GetLinkPrefix($field),0,7)!="http://" && !myfile_exists(GetUploadFolder($field).$thumbname))
				$thumbname=$ret;
			$ret="<a target=_blank href=\"".htmlspecialchars(AddLinkPrefix($field,$ret))."\">";
			$ret.="<img";
			if(isEnableSection508())
				$ret.= " alt=\"".htmlspecialchars($data[$field])."\"";
			$ret.=" border=0";
			$ret.=" src=\"".htmlspecialchars(AddLinkPrefix($field,$thumbname))."\"></a>";
		}
		else
			if(isEnableSection508())
				$ret='<img alt=\"".htmlspecialchars($data[$field])."\" src="'.AddLinkPrefix($field,$ret).'" border=0>';
			else
				$ret='<img src="'.htmlspecialchars(AddLinkPrefix($field,$ret)).'" border=0>';
	}
	else if($format == FORMAT_HYPERLINK)
	{
		if($data)
			$ret=GetHyperlink($ret,$field,$data);
	}
	else if($format==FORMAT_EMAILHYPERLINK)
	{
		$link=$ret;
		$title=$ret;
		if(substr($ret,0,7)=="mailto:")
			$title=substr($ret,8);
		else
			$link="mailto:".$link;
		$ret='<a href="'.$link.'">'.$title.'</a>';
	}
	else if($format==FORMAT_FILE)
	{
		$iquery="field=".rawurlencode($field);
		if($strTableName=="carsmake")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		if($strTableName=="carsmodels")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		if($strTableName=="carsusers")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		if($strTableName=="project39_blocking")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		if($strTableName=="project41_audit")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		if($strTableName=="carscars")
		{
			if($data)
				$iquery.="&key1=".rawurlencode($data["id"]);
		}
		return 	'<a href="'.GetTableURL($strTableName).'_download.php?'.$iquery.'".>'.htmlspecialchars($ret).'</a>';
	}
	else if(GetEditFormat($field)==EDIT_FORMAT_CHECKBOX && $format==FORMAT_NONE)
	{
		if($ret && $ret!=0)
			$ret="Yes";
		else
			$ret="No";
	}
	return $ret;
}


function ProcessLargeText($strValue,$iquery="",$table="", $mode=MODE_LIST)
{
	global $strTableName;

	$cNumberOfChars = GetNumberOfChars($table);
	//??
	if(substr($strValue,0,8)=="<a href=")
		return $strValue;
	//??
	if(substr($strValue,0,23)=="<img src=\"images/check_")
		return $strValue;
	
	/*if($cNumberOfChars>0 && strlen($strValue)>$cNumberOfChars && (strlen($strValue)<200 || !strlen($iquery)) && $mode==MODE_LIST)
	{
		$ret = substr($strValue,0,$cNumberOfChars );
		$ret=htmlspecialchars($ret);
		$ret.=" <a href=\"#\" onClick=\"javascript: pwin = window.open('',null,'height=300,width=400,status=yes,resizable=yes,toolbar=no,menubar=no,location=no,left=150,top=200,scrollbars=yes'); ";
		$ind = 1;
		$ret.="pwin.document.write('" . htmlspecialchars(jsreplace(nl2br(substr($strValue,0, 801)))) ."');";
//		$ret.="pwin.document.write('" . db_addslashes(str_replace("\r\n","<br>",htmlspecialchars(substr($strValue,0, 801)))) ."');";
		$ret.="pwin.document.write('<br><hr size=1 noshade><a href=# onClick=\\'window.close();return false;\\'>"."Close window"."</a>');";
		$ret.="return false;\">"."More"." ...</a>";
	}
	else*/ if($cNumberOfChars>0 && strlen($strValue)>$cNumberOfChars && $mode==MODE_LIST)
	{
		$table = GetTableURL($table);
		$ret = substr($strValue,0,$cNumberOfChars );
		$ret=htmlspecialchars($ret);
		$ret.=" <a href=# onClick=\"return DisplayPage(event,'".$table."_fulltext.php','','','".$iquery."','','');\">"."More"." ...</a>";
	}
	else if($cNumberOfChars>0 && strlen($strValue)>$cNumberOfChars && $mode==MODE_PRINT)
	{
		$ret = substr($strValue,0,$cNumberOfChars );
		$ret=htmlspecialchars($ret);
		if(strlen($strValue)>$cNumberOfChars)
			$ret.=" ...";
	}
	else
		$ret= htmlspecialchars($strValue);

/*
//	highlight search results
	if ($mode==MODE_LIST && $_SESSION[$strTableName."_search"]==1)
	{
		$ind = 0;
		$searchopt=$_SESSION[$strTableName."_searchoption"];
		$searchfor=$_SESSION[$strTableName."_searchfor"];
//		highlight Contains search
		if($searchopt=="Contains")
		{
			while ( ($ind = my_stripos($ret, $searchfor, $ind)) !== false )
			{
				$ret = substr($ret, 0, $ind) . "<span class=highlight>". substr($ret, $ind, strlen($searchfor)) ."</span>" . substr($ret, $ind + strlen($searchfor));
				$ind+= strlen("<span class=highlight>") + strlen($searchfor) + strlen("</span>");
			}
		}
//		highlight Starts with search
		elseif($searchopt=="Starts with ...")
		{
			if( !strncasecmp($ret, $searchfor,strlen($searchfor)) )
				$ret = "<span class=highlight>". substr($ret, 0, strlen($searchfor)) ."</span>" . substr($ret, strlen($searchfor));
		}
		elseif($searchopt=="Equals")
		{
			if( !strcasecmp($ret, $searchfor) )
				$ret = "<span class=highlight>". $ret ."</span>";
		}
		elseif($searchopt=="More than ...")
		{
			if( strtoupper($ret)>strtoupper($searchfor) )
				$ret = "<span class=highlight>". $ret ."</span>";
		}
		elseif($searchopt=="Less than ...")
		{
			if( strtoupper($ret)<strtoupper($searchfor) )
				$ret = "<span class=highlight>". $ret ."</span>";
		}
		elseif($searchopt=="Equal or more than ...")
		{
			if( strtoupper($ret)>=strtoupper($searchfor) )
				$ret = "<span class=highlight>". $ret ."</span>";
		}
		elseif($searchopt=="Equal or less than ...")
		{
			if( strtoupper($ret)<=strtoupper($searchfor) )
				$ret = "<span class=highlight>". $ret ."</span>";
		}
	}
*/
	return nl2br($ret);
}

//	construct hyperlink
function GetHyperlink($str, $field,$data,$table="")
{
	global $strTableName;
	if(!strlen($table))
		$table=$strTableName;
	if(!strlen($str))
		return "";
	$ret=$str;
	$title=$ret;
	$link=$ret;
	if(substr($ret,strlen($ret)-1)=='#')
	{
		$i=strpos($ret,'#');
		$title=substr($ret,0,$i);
		$link=substr($ret,$i+1,strlen($ret)-$i-2);
		if(!$title)
			$title=$link;
	}
	$target="";
	
	if(strpos($link,"://")===false && substr($link,0,7)!="mailto:")
		$link=$prefix.$link;
	$ret='<a href="'.$link.'"'.$target.'>'.$title.'</a>';
	return $ret;
}

//	add prefix to the URL
function AddLinkPrefix($field,$link,$table="")
{
	if(strpos($link,"://")===false && substr($link,0,7)!="mailto:")
		return GetLinkPrefix($field,$table).$link;
	return $link;
}

function GetTotalsForTime($value,$arr)
{
	$nsec=0;
	$nmin=0;
	if($value!='')
	{
		$time=parsenumbers($value);
		if(!empty($time))
		{
			if(count($time)==3 && is_numeric($time[2]))
			{
				$nsec=$arr[2]+$time[2];
				if($nsec>59)
				{	
					$arr[2]=$nsec-60;
					$time[1]+=1;
				}
				else $arr[2]+=$time[2];
			}
			if(count($time)>1 && is_numeric($time[1]))
			{
				$nmin=$arr[1]+$time[1];  
				if($nmin>59)
				{
					$arr[1]=$nmin-60;
					$time[0]+=1;	
				}
				else $arr[1]+=$time[1];
			}
			if(is_numeric($time[0]))
				$arr[0]+=$time[0];
		}
	}
	return $arr;
}


//	return Totals string
function GetTotals($field,$value, $stype, $iNumberOfRows,$sFormat)
{
	$days=0;
	if($stype=="AVERAGE")
	{
		if($iNumberOfRows)
		{	
			if($sFormat == FORMAT_TIME)
			{
				if($value!='')
				{
					$pr=parsenumbers($value);
					if(!empty($pr) && count($pr)==3)
					{
						$avhor=round($pr[0]/$iNumberOfRows,0);
						if($avhor>23)
						{
							$days=floor($avhor/24);
							$avhor=$avhor-$days*24;
						}
						$avmin=round($pr[1]/$iNumberOfRows,0);
						$avsec=round($pr[2]/$iNumberOfRows,0);
						$value=($days!=0 ? $days.'d ' : '').$avhor.':'.($avmin>9 ? $avmin : ($avmin==0 ? '00' : '0'.$avmin)).':'.($avsec>9 ? $avsec : ($avsec==0 ? '00' : '0'.$avsec));
					}
				}
			}
			else $value=round($value/$iNumberOfRows,2);	
		}
		else
			return "";
	}
	if($stype=="TOTAL")
	{
		if($sFormat == FORMAT_TIME)
		{
			if($value!='')
			{
				$pr=parsenumbers($value);
				if(!empty($pr)&& count($pr)==3)
				{
					if($pr[0]>23)
					{	
						$days=floor($pr[0]/24);
						$pr[0]=$pr[0]-$days*24;
					}
					$value=($days!=0 ? $days.'d ' :'').($pr[0]==0 ? '00' : $pr[0]).':'.($pr[1]==0 ? '00' : ($pr[1]>9 ? $pr[1] : '0'.$pr[1])).':'.($pr[2]==0 ? '00' : ($pr[2]>9 ? $pr[2] : '0'.$pr[2]));
				}
			}
		}
	}
	$sValue="";
	$data=array($field=>$value);
	if($sFormat == FORMAT_CURRENCY)
	 	$sValue = str_format_currency($value);
	else if($sFormat == FORMAT_PERCENT)
		$sValue = str_format_number($value*100)."%"; 
	else if($sFormat == FORMAT_NUMBER)
 		$sValue = str_format_number($value);
	else if($sFormat == FORMAT_CUSTOM && $stype!="COUNT")
 		$sValue = GetData($data,$field,$sFormat);
	else 
 		$sValue = $value;

	if($stype=="COUNT") 
		return $value;
	if($stype=="TOTAL") 
		return $sValue;
	if($stype=="AVERAGE") 
		return $sValue;
	return "";
}


//	display Lookup Wizard value in List/View mode
function DisplayLookupWizard($field, $value, $data, $keylink, $mode)
{
	global $conn;
	if(!strlen($value))
		return "";
	$LookupSQL="SELECT ";
	$LookupSQL.=GetLWDisplayField($field);
	$LookupSQL.=" FROM ".AddTableWrappers(GetLookupTable($field))." WHERE ";
	$where="";
	$lookupvalue=$value;
	if(Multiselect($field))
	{
		$arr = splitvalues($value);
		$numeric=true;
		$type = GetLWLinkFieldType($field);
		if(!$type)
		{
			foreach($arr as $val)
				if(strlen($val) && !is_numeric($val))
				{
					$numeric=false;
					break;
				}
		}
		else
			$numeric = !NeedQuotes($type);
		$in="";
		foreach($arr as $val)
		{
			if($numeric && !strlen($val))
				continue;
			if(strlen($in))
				$in.=",";
			if($numeric)
				$in.=($val+0);
			else
				$in.="'".db_addslashes($val)."'";
		}
		if(strlen($in))
		{
			$LookupSQL.= GetLWLinkField($field)." in (".$in.")";
			$where = GetLWWhere($field);
			if(strlen($where))
				$LookupSQL.=" and (".$where.")";
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$found=false;
			$out="";
			while($lookuprow=db_fetch_numarray($rsLookup))
			{
				$lookupvalue=$lookuprow[0];
				if($found)
					$out.=",";
				$found = true;
				$out.=GetDataInt($lookupvalue,$data,$field, ViewFormat($field));
			}
			if($found)
			{
				if(NeedEncode($field) && $mode!=MODE_EXPORT)
					return ProcessLargeText($out,"field=".htmlspecialchars(rawurlencode($field)).$keylink,"",$mode);
				else
					return $out;
			}
		}
	}
	else
	{
		$strdata = make_db_value($field,$value);
		$LookupSQL.=GetLWLinkField($field)." = " . $strdata;
		$where = GetLWWhere($field);
		if(strlen($where))
			$LookupSQL.=" and (".$where.")";
		LogInfo($LookupSQL);
		$rsLookup = db_query($LookupSQL,$conn);
		if($lookuprow=db_fetch_numarray($rsLookup))
			$lookupvalue=$lookuprow[0];
	}
	if(NeedEncode($field) && $mode!=MODE_EXPORT)
		$value=ProcessLargeText(GetDataInt($lookupvalue,$data,$field, ViewFormat($field)),"field=".htmlspecialchars(rawurlencode($field)).$keylink,"",$mode);
	else
		$value=GetDataInt($lookupvalue,$data,$field, ViewFormat($field));
	return $value;
}

function DisplayNoImage()
{
	$path = GetAbsoluteFileName("images/no_image.gif");
	$img=myfile_get_contents($path,"r");
	header("Content-Type: image/gif");
	echo $img;
}

function DisplayFile()
{
	$path = GetAbsoluteFileName("images/file.gif");
	$img=myfile_get_contents($path,"r");
	header("Content-Type: image/gif");
	echo $img;
}

function echobig($string, $bufferSize = 8192)
{
	for ($chars=strlen($string)-1,$start=0;$start <= $chars;$start += $bufferSize) 
		echo substr($string,$start,$bufferSize);
}

////////////////////////////////////////////////////////////////////////////////
// miscellaneous functions
////////////////////////////////////////////////////////////////////////////////



//	analog of strrpos function
function my_strrpos($haystack, $needle) {
   $index = strpos(strrev($haystack), strrev($needle));
   if($index === false) {
       return false;
   }
   $index = strlen($haystack) - strlen($needle) - $index;
   return $index;
}

//	utf-8 analog of strlen function
function strlen_utf8($str)
{
	$len=0;
	$i=0;
	$olen=strlen($str);
	while($i<$olen)
	{
		$c=ord(substr($str,$i,1));
		if($c<128)
			$i++;
		else if($i<$olen-1 && $c>=192 && $c<=223)
			$i+=2;
		else if($i<$olen-2 && $c>=224 && $c<=239)
			$i+=3;
		else if($i<$olen-3 && $c>=240)
			$i+=4;
		else
			break;
		$len++;
	}
	return $len;
}

//	utf-8 analog of substr function
function substr_utf8($str,$index,$strlen)
{
	if($strlen<=0)
		return "";
	$len=0;
	$i=0;
	$olen=strlen($str);
	$oindex=-1;
	while($i<$olen)
	{
		if($len==$index)
			$oindex=$i;
		
		$c=ord(substr($str,$i,1));
		if($c<128)
			$i++;
		else if($i<$olen-1 && $c>=192 && $c<=223)
			$i+=2;
		else if($i<$olen-2 && $c>=224 && $c<=239)
			$i+=3;
		else if($i<$olen-3 && $c>=240)
			$i+=4;
		else
			break;
		$len++;
		if($oindex>=0 && $len==$index+$strlen)
			return substr($str,$oindex,$i-$oindex);
	}
	if($oindex>0)
		return substr($str,$oindex,$olen-$oindex);
	return "";
}

//	construct "good" field name
function GoodFieldName($field)
{
	$field=(string)$field;	
	$out="";
	for($i=0;$i<strlen($field);$i++)
	{
		$t=substr($field,$i,1);
		if((ord($t)<ord('a') || ord($t)>ord('z')) && (ord($t)<ord('A') || ord($t)>ord('Z')) && (ord($t)<ord('0') || ord($t)>ord('9')))
			$out.='_';
		else
			$out.=$t;
	}
	return $out;
}

//	prepare string for JavaScript. Replace ' with \' and linebreaks with \r\n
function jsreplace($str)
{
	$ret= str_replace(array("\\","'","\r","\n"),array("\\\\","\\'","\\r","\\n"),$str);
	return my_str_ireplace("</script>","</scr'+'ipt>",$ret);
}


function LogInfo($SQL)
{
	global $dSQL,$dDebug;
	$dSQL=$SQL;
	if($dDebug)
	{
		echo $dSQL;
		echo "<br>";
	}
}


//	check if file extension is image extension
function CheckImageExtension($filename)
{
	if(strlen($filename)<4)
		return false;
	$ext=strtoupper(substr($filename,strlen($filename)-4));
	if($ext==".GIF" || $ext==".JPG" || $ext=="JPEG" || $ext==".PNG" || $ext==".BMP")
		return $ext;
	return false;
} 























































































function RTESafe($strText)
{
//	returns safe code for preloading in the RTE
	$tmpString="";
	
	$tmpString = trim($strText);
	if(!$tmpString) return "";
	
//	convert all types of single quotes
	$tmpString = str_replace( chr(145), chr(39),$tmpString);
	$tmpString = str_replace( chr(146), chr(39),$tmpString);
	$tmpString = str_replace("'", "&#39;",$tmpString);
	
//	convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34),$tmpString);
	$tmpString = str_replace(chr(148), chr(34),$tmpString);
	
//	replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ",$tmpString);
	$tmpString = str_replace(chr(13), " ",$tmpString);
	
	return $tmpString;
}



function html_special_decode($str)
{
	$ret=$str;
	$ret=str_replace("&gt;",">",$ret);
	$ret=str_replace("&lt;","<",$ret);
	$ret=str_replace("&quot;","\"",$ret);
	$ret=str_replace("&#039;","'",$ret);
	$ret=str_replace("&#39;","'",$ret);
	$ret=str_replace("&amp;","&",$ret);
	return $ret;
}

////////////////////////////////////////////////////////////////////////////////
// database and SQL related functions
////////////////////////////////////////////////////////////////////////////////

function CalcSearchParameters()
{
	global $strTableName, $strSQL;
	$sWhere="";
	if(@$_SESSION[$strTableName."_search"]==2)
//	 advanced search
	{
		foreach(@$_SESSION[$strTableName."_asearchfor"] as $f => $sfor)
		{
			$strSearchFor=trim($sfor);
			$strSearchFor2="";
			$type=@$_SESSION[$strTableName."_asearchfortype"][$f];
			if(array_key_exists($f,@$_SESSION[$strTableName."_asearchfor2"]))
				$strSearchFor2=trim(@$_SESSION[$strTableName."_asearchfor2"][$f]);
			if($strSearchFor!="" || true)
			{
				if (!$sWhere) 
				{
					if($_SESSION[$strTableName."_asearchtype"]=="and")
						$sWhere="1=1";
					else
						$sWhere="1=0";
				}
				$strSearchOption=trim($_SESSION[$strTableName."_asearchopt"][$f]);
				if($where=StrWhereAdv($f, $strSearchFor, $strSearchOption, $strSearchFor2,$type))
				{
					if($_SESSION[$strTableName."_asearchnot"][$f])
						$where="not (".$where.")";
					if($_SESSION[$strTableName."_asearchtype"]=="and")
	   					$sWhere .= " and ".$where;
					else
	   					$sWhere .= " or ".$where;
				}
			}
		}
	}
	return $sWhere;
}

//	add WHERE condition to gstrSQL
function gSQLWhere($where)
{
	global $gsqlFrom,$gsqlWhereExpr,$gsqlTail;
	global $gQuery;
	$sqlHead = $gQuery->HeadToSql();
	return gSQLWhere_int($sqlHead,$gsqlFrom,$gsqlWhereExpr,$gsqlTail,$where);
}

function gSQLWhere_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$where)
{
	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	
	return $sqlHead." ".$sqlFrom.$strWhere.$sqlTail;
}

//	add clause to WHERE expression
function whereAdd($where,$clause)
{
	if(!strlen($clause))
		return $where;
	if(!strlen($where))
		return $clause;
	return "(".$where.") and (".$clause.")";
}

//	add WHERE clause to SQL string
function AddWhere($sql,$where)
{
	if(!strlen($where))
		return $sql;
	$sql=str_replace(array("\r\n","\n","\t")," ",$sql);
	$tsql = strtolower($sql);
	$n = my_strrpos($tsql," where ");
	$n1 = my_strrpos($tsql," group by ");
	$n2 = my_strrpos($tsql," order by ");
	if($n1===false)
		$n1=strlen($tsql);
	if($n2===false)
		$n2=strlen($tsql);
	if ($n1>$n2)
		$n1=$n2;
	if($n===false)
		return substr($sql,0,$n1)." where ".$where.substr($sql,$n1);
	else
		return substr($sql,0,$n+strlen(" where "))."(".substr($sql,$n+strlen(" where "),$n1-$n-strlen(" where ")).") and (".$where.")".substr($sql,$n1);
}

//	construct WHERE clause with key values
function KeyWhere(&$keys, $table="")
{
	global $strTableName;
	if(!$table)
		$table=$strTableName;
	$strWhere="";

//	carsmake
	if($table=="carsmake")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}

//	carsmodels
	if($table=="carsmodels")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}

//	carsusers
	if($table=="carsusers")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}

//	project39_blocking
	if($table=="project39_blocking")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}

//	project41_audit
	if($table=="project41_audit")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}

//	carscars
	if($table=="carscars")
	{
			$value=make_db_value("id",$keys["id"]);
				$valueisnull = ($value==="null");
		if($valueisnull)
			$strWhere.=GetFullFieldName("id")." is null";
		else
			$strWhere.=GetFullFieldName("id")."=".make_db_value("id",$keys["id"]);
	}
	return $strWhere;
}

function GetKeyFields($table="")
{
	global $strTableName;
	if(!$table)
		$table=$strTableName;
	$keys = array();
	if($table=="carsmake")
	{
		$keys []= "id";
	}
	if($table=="carsmodels")
	{
		$keys []= "id";
	}
	if($table=="carsusers")
	{
		$keys []= "id";
	}
	if($table=="project39_blocking")
	{
		$keys []= "id";
	}
	if($table=="project41_audit")
	{
		$keys []= "id";
	}
	if($table=="carscars")
	{
		$keys []= "id";
	}
	return $keys;
}

//	consctruct SQL WHERE clause for simple search
function StrWhereExpression($strField, $SearchFor, $strSearchOption, $SearchFor2)
{
	global $strTableName;
	$type=GetFieldType($strField);
	
	$ismssql=false;
	
	$btexttype=IsTextType($type);
	$btexttype=false;

	if($strSearchOption=='Empty')
	{
		if(IsCharType($type) && (!$ismssql || !$btexttype))
			return "(".GetFullFieldName($strField)." is null or ".GetFullFieldName($strField)."='')";			
		elseif ($ismssql && $btexttype)	
			return "(".GetFullFieldName($strField)." is null or ".GetFullFieldName($strField)." LIKE '')";
		else
			return GetFullFieldName($strField)." is null";
	}
	$strQuote="";
	if(NeedQuotes($type))
		$strQuote = "'";
//	return none if trying to compare numeric field and string value
	$sSearchFor=$SearchFor;
	$sSearchFor2=$SearchFor2;
	if(IsBinaryType($type))
		return "";
	

	
	if(IsDateFieldType($type) && $strSearchOption!="Contains" && $strSearchOption!="Starts with" )
	{
		$time=localdatetime2db($SearchFor);
		if($time=="null")
			return "";
		$sSearchFor=db_datequotes($time);
		if($strSearchOption=="Between")
		{
			$time=localdatetime2db($SearchFor2);
			if($time=="null")
				$sSearchFor2="";
			else
				$sSearchFor2=db_datequotes($time);
		}
	}
	
	if(!$strQuote && !is_numeric($sSearchFor) && !is_numeric($sSearchFor))
		return "";
	else if(!$strQuote && $strSearchOption!="Contains" && $strSearchOption!="Starts with")
	{
		$sSearchFor = 0+$sSearchFor;
		$sSearchFor2 = 0+$sSearchFor2;
	}
	else if(!IsDateFieldType($type) && $strSearchOption!="Contains" && $strSearchOption!="Starts with")
	{
		if($btexttype)
		{
			$sSearchFor=$strQuote.db_addslashes($sSearchFor).$strQuote;
			if($strSearchOption=="Between" && $sSearchFor2)
				$sSearchFor2=$strQuote.db_addslashes($sSearchFor2).$strQuote;
		}
		else
		{
			$sSearchFor=isEnableUpper($strQuote.db_addslashes($sSearchFor).$strQuote);
			if($strSearchOption=="Between" && $sSearchFor2)
				$sSearchFor2=isEnableUpper($strQuote.db_addslashes($sSearchFor2).$strQuote);
		}
	}
	else if(!IsDateFieldType($type) || $strSearchOption=="Contains" || $strSearchOption=="Starts with" )
		$sSearchFor=db_addslashes($sSearchFor);
		

	if(IsCharType($type) && !$btexttype)
		$strField=isEnableUpper(GetFullFieldName($strField));
	elseif ($ismssql && !$btexttype && ($strSearchOption=="Contains" || $strSearchOption=="Starts with"))
		$strField="convert(varchar(50),".GetFullFieldName($strField).")";
	else 
		$strField=GetFullFieldName($strField);
	$ret="";
		$like="like";
	if($strSearchOption=="Contains")
	{
		if(IsCharType($type) && !$btexttype)
			return $strField." ".$like." ".isEnableUpper("'%".$sSearchFor."%'");
		else
			return $strField." ".$like." '%".$sSearchFor."%'";
	}
	else if($strSearchOption=="Equals") return $strField."=".$sSearchFor;
	else if($strSearchOption=="Starts with")
	{
		if(IsCharType($type) && !$btexttype)
			return $strField." ".$like." ".isEnableUpper("'".$sSearchFor."%'");
		else
			return $strField." ".$like." '".$sSearchFor."%'";
	}
	else if($strSearchOption=="More than") return $strField.">".$sSearchFor;
	else if($strSearchOption=="Less than") return $strField."<".$sSearchFor;
	else if($strSearchOption=="Between")
	{		
		$ret=$strField.">=".$sSearchFor;
		if($sSearchFor2) $ret.=" and ".$strField."<=".$sSearchFor2;
			return $ret;
	}
	return "";
}

//	construct SQL WHERE clause for Advanced search
function StrWhereAdv($strField, $SearchFor, $strSearchOption, $SearchFor2, $etype)
{
	global $strTableName;
	$type=GetFieldType($strField);

	$ismssql=false;
	
	$btexttype=IsTextType($type);
	$btexttype=false;

	if(IsBinaryType($type))
		return "";
	if($strSearchOption=='Empty')
	{
		if(IsCharType($type) && (!$ismssql || !$btexttype))
			return "(".GetFullFieldName($strField)." is null or ".GetFullFieldName($strField)."='')";			
		elseif ($ismssql && $btexttype)	
			return "(".GetFullFieldName($strField)." is null or ".GetFullFieldName($strField)." LIKE '')";
		else
			return GetFullFieldName($strField)." is null";
	}
		$like="like";
	if(GetEditFormat($strField)==EDIT_FORMAT_LOOKUP_WIZARD)
	{
		
		if(Multiselect($strField))
			$SearchFor=splitvalues($SearchFor);
		else
			$SearchFor=array($SearchFor);
		$ret="";
		foreach($SearchFor as $value)
		{
			if(!($value=="null" || $value=="Null" || $value==""))
			{
				if(strlen($ret))
					$ret.=" or ";
				if($strSearchOption=="Equals")
				{
					$value=make_db_value($strField,$value);
					if(!($value=="null" || $value=="Null"))
						$ret.=GetFullFieldName($strField).'='.$value;
				}
				else
				{
					if(strpos($value,",")!==false || strpos($value,'"')!==false)
						$value = '"'.str_replace('"','""',$value).'"';
					$value=db_addslashes($value);
					$ret.=GetFullFieldName($strField)." = '".$value."'";

					$ret.=" or ".GetFullFieldName($strField)." ".$like." '%,".$value.",%'";
					$ret.=" or ".GetFullFieldName($strField)." ".$like." '%,".$value."'";
					$ret.=" or ".GetFullFieldName($strField)." ".$like." '".$value.",%'";
				}
			}
		}
		if(strlen($ret))
			$ret="(".$ret.")";
		return $ret;
	}
	if(GetEditFormat($strField)==EDIT_FORMAT_CHECKBOX)
	{
		if($SearchFor=="none")
			return "";
		if(NeedQuotes($type))
		{
			if($SearchFor=="on")
				return "(".GetFullFieldName($strField)."<>'0' and ".GetFullFieldName($strField)."<>'' and ".GetFullFieldName($strField)." is not null)";
			else
				return "(".GetFullFieldName($strField)."='0' or ".GetFullFieldName($strField)."='' or ".GetFullFieldName($strField)." is null)";
		}
		else
		{
			if($SearchFor=="on")
				return "(".GetFullFieldName($strField)."<>0 and ".GetFullFieldName($strField)." is not null)";
			else
				return "(".GetFullFieldName($strField)."=0 or ".GetFullFieldName($strField)." is null)";
		}
	}
	$value1=make_db_value($strField,$SearchFor,$etype);
	
	$value2=false;
	if($strSearchOption=="Between")
		$value2=make_db_value($strField,$SearchFor2,$etype);
	if($strSearchOption!="Contains" && $strSearchOption!="Starts with" && ($value1==="null" || $value2==="null" ))
		return "";

	if(IsCharType($type) && !$btexttype)
	{
		$value1=isEnableUpper($value1);
		$value2=isEnableUpper($value2);
		$strField=isEnableUpper(GetFullFieldName($strField));
	}
	elseif ($ismssql && !$btexttype && ($strSearchOption=="Contains" || $strSearchOption=="Starts with"))
		$strField="convert(varchar,".GetFullFieldName($strField).")";
	else 
		$strField=GetFullFieldName($strField);
	$ret="";
	if($strSearchOption=="Contains")
	{
		if(IsCharType($type) && !$btexttype)
			return $strField." ".$like." ".isEnableUpper("'%".db_addslashes($SearchFor)."%'");
		else
			return $strField." ".$like." '%".db_addslashes($SearchFor)."%'";
	}
	else if($strSearchOption=="Equals") return $strField."=".$value1;
	else if($strSearchOption=="Starts with")
	{
		if(IsCharType($type) && !$btexttype)
			return $strField." ".$like." ".isEnableUpper("'".db_addslashes($SearchFor)."%'");
		else
			return $strField." ".$like." '".db_addslashes($SearchFor)."%'";
	}
	else if($strSearchOption=="More than") return $strField.">".$value1;
	else if($strSearchOption=="Less than") return $strField."<".$value1;
	else if($strSearchOption=="Equal or more than") return $strField.">=".$value1;
	else if($strSearchOption=="Equal or less than") return $strField."<=".$value1;
	else if($strSearchOption=="Between")
	{
		
		$ret=$strField.">=".$value1;
		$ret.=" and ".$strField."<=".$value2;
		return $ret;
	}
	return "";
}

//	get count of rows from the query
function gSQLRowCount($where,$groupby=false)
{
	global $gsqlFrom,$gsqlWhereExpr,$gsqlTail;
	global $gQuery;
	$sqlHead = $gQuery->HeadToSql();
	return gSQLRowCount_int($sqlHead,$gsqlFrom,$gsqlWhereExpr,$gsqlTail,$where,$groupby);
}

function gSQLRowCount_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$where,$groupby=false)
{
	global $conn;
	global $bSubqueriesSupported;
	
	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	
	if($groupby)
	{
			if($bSubqueriesSupported)
		{
			$countstr = "select count(*) from (".gSQLWhere_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$where).") a";
		}
		else
		{
			$countstr = gSQLWhere_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$where);
			return GetMySQL4RowCount($countstr);
		}
	}
	else
	{
		$countstr = "select count(*) ".$sqlFrom.$strWhere.$sqlTail;
	}
	
	$countrs = db_query($countstr, $conn);
	$countdata = db_fetch_numarray($countrs);
	return $countdata[0];
}

//	get count of rows from the query
function GetRowCount($strSQL)
{
	global $conn;
	$strSQL=str_replace(array("\r\n","\n","\t")," ",$strSQL);
	$tstr = strtoupper($strSQL);
	$ind1 = strpos($tstr,"SELECT ");
	$ind2 = my_strrpos($tstr," FROM ");
	$ind3 = my_strrpos($tstr," GROUP BY ");
	if($ind3===false)
	{
		$ind3 = strpos($tstr," ORDER BY ");
		if($ind3===false)
			$ind3=strlen($strSQL);
	}
	$countstr=substr($strSQL,0,$ind1+6)." count(*) ".substr($strSQL,$ind2+1,$ind3-$ind2);
	$countrs = db_query($countstr,$conn);
	$countdata = db_fetch_numarray($countrs);
	return $countdata[0];
}

//	add MSSQL Server TOP clause
function AddTop($strSQL, $n)
{
	$tstr = strtoupper($strSQL);
	$ind1 = strpos($tstr,"SELECT");
	return substr($strSQL,0,$ind1+6)." top ".$n." ".substr($strSQL,$ind1+6);
}

//	add Oracle ROWNUMBER checking
function AddRowNumber($strSQL, $n)
{
	return "select * from (".$strSQL.") where rownum<".($n+1);
}

// test database type if values need to be quoted
function NeedQuotesNumeric($type)
{
    if($type == 203 || $type == 8 || $type == 129 || $type == 130 || 
		$type == 7 || $type == 133 || $type == 134 || $type == 135 ||
		$type == 201 || $type == 205 || $type == 200 || $type == 202 || $type==72 || $type==13)
		return true;
	else
		return false;
}

//	using ADO DataTypeEnum constants
//	the full list available at:
//	http://msdn.microsoft.com/library/default.asp?url=/library/en-us/ado270/htm/mdcstdatatypeenum.asp

function IsNumberType($type)
{
	if($type==20 || $type==6 || $type==14 || $type==5 || $type==10 
	|| $type==3 || $type==131 || $type==4 || $type==2 || $type==16
	|| $type==21 || $type==19 || $type==18 || $type==17 || $type==139
	|| $type==11)
		return true;
	return false;
}

function IsFloatType($type)
{
	if($type==14 || $type==5 || $type==131 || $type==6)
		return true;
	return false;
}


function NeedQuotes($type)
{
	return !IsNumberType($type);
}

function IsBinaryType($type)
{
	if($type==128 || $type==205 || $type==204)
		return true;
	return false;
}

function IsDateFieldType($type)
{
	if($type==7 || $type==133 || $type==135)
		return true;
	return false;
}

function IsTimeType($type)
{
	if($type==134)
		return true;
	return false;
}

function IsCharType($type)	
{
	if(IsTextType($type) || $type==8 || $type==129 || $type==200 || $type==202 || $type==130)
		return true;
	return false;
}

function IsTextType($type)
{
	if($type==201 || $type==203)
		return true;
	return false;
}

////////////////////////////////////////////////////////////////////////////////
// security functions
////////////////////////////////////////////////////////////////////////////////


//	return user permissions on the table
//	A - Add
//	D - Delete
//	E - Edit
//	S - List/View/Search
//	P - Print/Export


function IsAdmin()
{
	return false;
}

function GetUserPermissionsStatic($table="")
{
	global $strTableName;
	if(!$table)
		$table=$strTableName;

	$sUserGroup=@$_SESSION["GroupID"];
	if($table=="carsmake" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="carsmake" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="carsmake")
	{
		return "AEDSPI";
	}
	if($table=="carsmodels" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="carsmodels" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="carsmodels")
	{
		return "AEDSPI";
	}
	if($table=="carsusers" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="carsusers" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="carsusers")
	{
		return "AEDSPI";
	}
	if($table=="project39_blocking" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="project39_blocking" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="project39_blocking")
	{
		return "AEDSPI";
	}
	if($table=="project41_audit" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="project41_audit" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="project41_audit")
	{
		return "AEDSPI";
	}
	if($table=="carscars" && $sUserGroup=="<Guest>")
	{
				return "S";
	}
	if($table=="carscars" && $sUserGroup=="admin")
	{
				return "AEDSPI";
	}
//	default permissions	
	if($table=="carscars")
	{
		return "AEDSPI";
	}
}

function GetUserPermissions($table="")
{
	return GetUserPermissionsStatic($table);
}


//	check whether field is viewable
function CheckFieldPermissions($field, $table="")
{
	return GetFieldData($table,$field,"FieldPermissions",false);
}

// 
function CheckSecurity($strValue, $strAction)
{
global $cAdvSecurityMethod, $strTableName;
	if($_SESSION["AccessLevel"]==ACCESS_LEVEL_ADMIN)
		return true;

	$strPerm = GetUserPermissions();
	if(@$_SESSION["AccessLevel"]!=ACCESS_LEVEL_ADMINGROUP && strpos($strPerm, "M")===false)
	{
	}
	//	 check user group permissions
	if($strAction=="Add" && !(strpos($strPerm, "A")===false) ||
	   $strAction=="Edit" && !(strpos($strPerm, "E")===false) ||
	   $strAction=="Delete" && !(strpos($strPerm, "D")===false) ||
	   $strAction=="Search" && !(strpos($strPerm, "S")===false) ||
	   $strAction=="Import" && !(strpos($strPerm, "I")===false) ||
	   $strAction=="Export" && !(strpos($strPerm, "P")===false) )
		return true;
	else
		return false;
	return true;
}


//	add security WHERE clause to SELECT SQL command
function SecuritySQL($strAction, $table="")
{
global $cAdvSecurityMethod,$strTableName;
	
	if (!strlen($table))	
		$table = $strTableName;
		
   	$ownerid=@$_SESSION["_".$table."_OwnerID"];
	$ret="";
	if(@$_SESSION["AccessLevel"]==ACCESS_LEVEL_ADMIN)
		return "";
	$ret="";

	$strPerm = GetUserPermissions($table);

	if(@$_SESSION["AccessLevel"]!=ACCESS_LEVEL_ADMINGROUP)
	{

	}
	
	if($strAction=="Edit" && !(strpos($strPerm, "E")===false) ||
	   $strAction=="Delete" && !(strpos($strPerm, "D")===false) ||
	   $strAction=="Search" && !(strpos($strPerm, "S")===false) ||
	   $strAction=="Export" && !(strpos($strPerm, "P")===false) )
		return $ret;
	else
		return "1=0";
	return "";
}

////////////////////////////////////////////////////////////////////////////////
// editing functions
////////////////////////////////////////////////////////////////////////////////

function make_db_value($field,$value,$controltype="",$postfilename="",$table="")
{	
	$ret=prepare_for_db($field,$value,$controltype,$postfilename,$table);
	
	if($ret===false)
		return $ret;
	return add_db_quotes($field,$ret,$table);
}

function add_db_quotes($field,$value,$table="")
{
	global $strTableName;
	$type = GetFieldType($field, $table);
	if(IsBinaryType($type))
		return db_addslashesbinary($value);
	if(($value==="" || $value===FALSE) && !IsCharType($type))
		return "null";
	if(NeedQuotes($type))
	{
		if(!IsDateFieldType($type))
			$value="'".db_addslashes($value)."'";
		else
			$value=db_datequotes($value);
	}
	else
	{
		$strvalue = (string)$value;
		$strvalue = str_replace(",",".",$strvalue);
		if(is_numeric($strvalue))
			$value=$strvalue;
		else
			$value=0;
	}
	return $value;
}

function prepare_for_db($field,$value,$controltype="",$postfilename="",$table="")
{
	global $strTableName;
	$filename="";
	$type=GetFieldType($field,$table);
	if(!$controltype || $controltype=="multiselect")
	{
		if(is_array($value))
			$value=combinevalues($value);
		if(($value==="" || $value===FALSE) && !IsCharType($type))
			return "";
		return $value;
	}	
	else if($controltype=="time")
	{
		if(!strlen($value))
			return "";
		$time=localtime2db($value);
		if(IsDateFieldType(GetFieldType($field,$table)))
		{
			$time="2000-01-01 ".$time;
		}
		return $time;
	}
	else if(substr($controltype,0,4)=="date")
	{
		$dformat=substr($controltype,4);
		if($dformat==EDIT_DATE_SIMPLE || $dformat==EDIT_DATE_SIMPLE_DP)
		{
			$time=localdatetime2db($value);
			if($time=="null")
				return "";
			return $time;
		}
		else if($dformat==EDIT_DATE_DD || $dformat==EDIT_DATE_DD_DP)
		{
			$a=explode("-",$value);
			if(count($a)<3)
				return "";
			else
			{
				$y=$a[0];
				$m=$a[1];
				$d=$a[2];
			}
			if($y<100)
			{
				if($y<70)
					$y+=2000;
				else
					$y+=1900;
			}
			return mysprintf("%04d-%02d-%02d",array($y,$m,$d));
		}
		else
			return "";
	}
	else if(substr($controltype,0,8)=="checkbox")
	{
		if($value=="on")
			$ret=1;
		else if($value=="none")
			return "";
		else 
			$ret=0;
		return $ret;
	}
	else
		return false;
}

//	delete uploaded files when deleting the record
function DeleteUploadedFiles($where,$table="")
{
	global $conn,$gstrSQL;
	$sql = gSQLWhere($where);
	$rs = db_query($sql,$conn);
	if(!($data=db_fetch_array($rs)))
		return;
	foreach($data as $field=>$value)
	{
		if(strlen($value) && GetEditFormat($field)==EDIT_FORMAT_FILE)
		{
			if(myfile_exists(GetUploadFolder($field).$value))
				myunlink(GetUploadFolder($field).$value);
			if(GetCreateThumbnail($field) && myfile_exists(GetUploadFolder($field).GetThumbnailPrefix($field).$value))
				myunlink(GetUploadFolder($field).GetThumbnailPrefix($field).$value);
		}
	}
}

//	combine checked values from multi-select list box
function combinevalues($arr)
{
	$ret="";
	foreach($arr as $val)
	{
		if(strlen($ret))
			$ret.=",";
		if(strpos($val,",")===false && strpos($val,'"')===false)
			$ret.=$val;
		else
		{
			$val=str_replace('"','""',$val);
			$ret.='"'.$val.'"';
		}
	}
	return $ret;
}

//	split values for multi-select list box
function splitvalues($str)
{
	$arr=array();
	$start=0;
	$i=0;
	$inquot=false;
	while($i<=strlen($str))
	{
		if($i<strlen($str) && substr($str,$i,1)=='"')
			$inquot=!$inquot;
		else if($i==strlen($str) || !$inquot && substr($str,$i,1)==',')
		{
			$val=substr($str,$start,$i-$start);
			$start=$i+1;
			if(strlen($val) && substr($val,0,1)=='"')
			{
				$val=substr($val,1,strlen($val)-2);
				$val=str_replace('""','"',$val);
			}
			$arr[]=$val;
		}
		$i++;
	}
	return $arr;
}


////////////////////////////////////////////////////////////////////////////////
// edit controls creation functions
////////////////////////////////////////////////////////////////////////////////


//	write days dropdown
function WriteDays($d)
{
	$ret='<option value=""> </option>';
	for($i=1;$i<=31;$i++)
		$ret.='<option value="'.$i.'" '.($i==$d?"selected":"").'>'.$i."</option>\r\n";
	return $ret;
}

//	write months dropdown
function WriteMonths($m)
{
	$monthnames=array();
	$monthnames[1]="January";
	$monthnames[2]="February";
	$monthnames[3]="March";
	$monthnames[4]="April";
	$monthnames[5]="May";
	$monthnames[6]="June";
	$monthnames[7]="July";
	$monthnames[8]="August";
	$monthnames[9]="September";
	$monthnames[10]="October";
	$monthnames[11]="November";
	$monthnames[12]="December";
	$ret='<option value=""></option>';
	for($i=1;$i<=12;$i++)
		$ret.='<option value="'.$i.'" '.($i==$m?"selected":"").'>'.$monthnames[$i]."</option>\r\n";
	return $ret;
}

//	write years dropdown
function WriteYears($y)
{
	$currectYear=GetCurrentYear();
	$firstyear = $currectYear-100;
	$lastyear=$currectYear+10;
	$ret='<option value=""> </option>';
	if($y && $firstyear>$y-5)
		$firstyear=$y-10;
	if($y && $lastyear<$y+5)
		$lastyear=$y+10;
	for($i=$firstyear;$i<=$lastyear;$i++)
		$ret.='<option value="'.$i.'" '.($i==$y?"selected":"").'>'.$i."</option>\r\n";
	return $ret;
}

//	returns HTML code that represents required Date edit control
function GetDateEdit($field, $value, $type, $fieldNum=0,$search=MODE_EDIT,$record_id="",$jsControlObjectParams, &$pageObj)
{	
	global $cYearRadius, $locale_info, $jscode, $strTableName;
	$is508=isEnableSection508();
	$label=Label($field);
	$cfieldname=GoodFieldName($field);
	$cfield="value_".GoodFieldName($field).'_'.$record_id;
	if($fieldNum)
		$cfield="value".$fieldNum."_".GoodFieldName($field).'_'.$record_id;
	$tvalue=$value;
	if($search==MODE_SEARCH && ($type==EDIT_DATE_SIMPLE || $type==EDIT_DATE_SIMPLE_DP))
		$tvalue=localdatetime2db($value);
	$time=db2time($tvalue);
	if(!count($time))
		$time=array(0,0,0,0,0,0);
	$dp=0;
	switch($type)
	{
		case EDIT_DATE_SIMPLE_DP:
			$ovalue=$value;
			if($locale_info["LOCALE_IDATE"]==1)
			{
				$fmt="dd".$locale_info["LOCALE_SDATE"]."MM".$locale_info["LOCALE_SDATE"]."yyyy";
				$sundayfirst="false";
			}
			else if($locale_info["LOCALE_IDATE"]==0)
			{
				$fmt="MM".$locale_info["LOCALE_SDATE"]."dd".$locale_info["LOCALE_SDATE"]."yyyy";
				$sundayfirst="true";
			}
			else
			{
				$fmt="yyyy".$locale_info["LOCALE_SDATE"]."MM".$locale_info["LOCALE_SDATE"]."dd";
				$sundayfirst="false";
			}
			if(DateEditShowTime($field) )
			{
				if($time[5])
					$fmt.=" HH:mm:ss";
				else if($time[3] || $time[4])
					$fmt.=" HH:mm";
			}
			if($time[0])
				$ovalue=format_datetime_custom($time,$fmt);
			$ovalue1=$time[2]."-".$time[1]."-".$time[0];
			$showtime="false";
			if(DateEditShowTime($field))
			{
				$showtime="true";
				$ovalue1.=" ".$time[3].":".$time[4].":".$time[5];
			}
			// need to create date control object to use it with datePicker
			$ret='<input id="'.$cfield.'" type="Text" name="'.$cfield.'" size = "20" value="'.$ovalue.'">';
			$ret.='<input id="ts'.$cfield.'" type="Hidden" name="ts'.$cfield.'" value="'.$ovalue1.'">&nbsp;&nbsp;';
			$ret.='&nbsp;<a href="#" id="imgCal_'.$cfield.'" onclick="var cntrl = window.Runner.controls.ControlManager.getAt(\''.htmlspecialchars(jsreplace($strTableName)).'\', \''.$record_id.'\', \''.htmlspecialchars(jsreplace($field)).'\'); var v=show_calendar(cntrl, \'\',\'\', $(\'input[@name=ts'.$cfield.']\').get(0).value,'.$showtime.','.$sundayfirst.'); return false;">'.
				'<img src="images/cal.gif" width=16 height=16 border=0 alt="'."Click Here to Pick up the date".'"></a>';			
			echo $ret;
			$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.', useDatePicker: true, dateFormat: '.$locale_info["LOCALE_IDATE"].' '.($showtime ? ', showTime: true' : '').'}';	
			jscodeToAdd("DateTextField",$jsControlObjectParams,$record_id, $pageObj);
			return;
		case EDIT_DATE_DD_DP:
			$dp=1;
		case EDIT_DATE_DD:
			$retday='<select id="day'.$cfield.'" class=selects '.(($search == MODE_INLINE_EDIT || $search==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="day'.$cfield.'" >'.WriteDays($time[2])."</select>";
			$retmonth='<select id="month'.$cfield.'" class=selectm '.(($search == MODE_INLINE_EDIT || $search==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="month'.$cfield.'" >'.WriteMonths($time[1])."</select>";
			$retyear='<select id="year'.$cfield.'" class=selects '.(($search == MODE_INLINE_EDIT || $search==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="year'.$cfield.'" >'.WriteYears($time[0])."</select>";
			$sundayfirst="false";
			if($locale_info["LOCALE_ILONGDATE"]==1)
				$ret=$retday."&nbsp;".$retmonth."&nbsp;".$retyear;
			else if($locale_info["LOCALE_ILONGDATE"]==0)
			{
				$ret=$retmonth."&nbsp;".$retday."&nbsp;".$retyear;
				$sundayfirst="true";
			}
			else
				$ret=$retyear."&nbsp;".$retmonth."&nbsp;".$retday;
				
			if($time[0] && $time[1] && $time[2])
				$ret.="<input id=\"".$cfield."\" type=hidden name=\"".$cfield."\" value=\"".$time[0]."-".$time[1]."-".$time[2]."\">";
			else
				$ret.="<input id=\"".$cfield."\" type=hidden name=\"".$cfield."\" value=\"\">";
				
			// calendar handling for three DD
			if($dp)
			{
				$ret.='&nbsp;<a href="#" id="imgCal_'.$cfield.'" onclick="var cntrl = window.Runner.controls.ControlManager.getAt(\''.htmlspecialchars(jsreplace($strTableName)).'\', \''.$record_id.'\', \''.htmlspecialchars(jsreplace($field)).'\'); var v=show_calendar(cntrl, \'\',\'\', $(\'input[@name=ts'.$cfield.']\').get(0).value,false,'.$sundayfirst.'); return false;">'.
				'<img src="images/cal.gif" width=16 height=16 border=0 alt="Click Here to Pick up the date"></a>'.
				'<input id="ts'.$cfield.'" type=hidden name="ts'.$cfield.'" value="'.$time[2].'-'.$time[1].'-'.$time[0].'">';
				$str = substr($jsControlObjectParams, 0, -1);
				$jsControlObjectParams = $str.', useDatePicker: true}';			
			}
			echo $ret;
			jscodeToAdd("DateDropDown",$jsControlObjectParams,$record_id, $pageObj);			
			return;
	//	case EDIT_DATE_SIMPLE:
		default:
			$ovalue=$value;
			if($time[0])
			{
				if($time[3] || $time[4] || $time[5])
					$ovalue=str_format_datetime($time);
				else
					$ovalue=format_shortdate($time);
			}
			echo '<input id="'.$cfield.'" type=text name="'.$cfield.'" size = "20" value="'.htmlspecialchars($ovalue).'">';
			jscodeToAdd("DateTextField",$jsControlObjectParams,$record_id, $pageObj);
			return;;
	}
}

//	create javascript array with values for dependent dropdowns
function BuildSecondDropdownArray( $arrName, $strSQL)
{
	global $conn;

	echo $arrName . "=new Array();\r\n";
	$i=0;
	$rs = db_query($strSQL,$conn);
	while($row=db_fetch_numarray($rs))
	{
		echo $arrName."[".($i*3)."]='".jsreplace($row[0]). "';\r\n";
		echo $arrName."[".($i*3 + 1)."]='".jsreplace($row[1]). "';\r\n";
		echo $arrName."[".($i*3 + 2)."]='".jsreplace($row[2]). "';\r\n";
		$i++;
	}
}

//	create Lookup wizard control
function BuildSelectControl($field, $value, $values="", $fieldNum=0, $mode, $id="", $jsControlObjectParams, $additionalCtrlParams, &$pageObj)
{
	global $conn,$LookupSQL,$strTableName;
	
	$label=Label($field);
	$LookupSQL ="";
	$strSize = 1;
	$is508=isEnableSection508();
	$cfield="value_".GoodFieldName($field)."_".$id;
	$clookupfield="display_value_".GoodFieldName($field)."_".$id;
	$openlookup = "open_lookup_".GoodFieldName($field)."_".$id;
	$ctype="type_".GoodFieldName($field)."_".$id;
	if($fieldNum)
	{
		$cfield="value".$fieldNum."_".GoodFieldName($field)."_".$id;
		$ctype="type".$fieldNum."_".GoodFieldName($field)."_".$id;
	}
	if($values)
		$arr=&$values;
	$addnewitem=false;
	$advancedadd=false;
	$add_page="";
	$add_table = "";
	$strCategoryControl=CategoryControl($field);
	$lookuptype=LookupControlType($field);
	
	$inputStyle = ($additionalCtrlParams['style'] ? 'style="'.$additionalCtrlParams['style'].'"' : '');
	
	$checkBoxMode = false;
//if use checkbox list
	if($checkBoxMode)
		echo '<div align=\'left\'>';
	$script="";
//	build SQL strings for lookup wizards
	if($lookuptype==LCT_LIST)
		$addnewitem=false;
//	prepare multi-select attributes
	$multiple="";
	$postfix="";
	if($strSize>1)
	{
		$avalue=splitvalues($value);
		$multiple=" multiple";
		$postfix="[]";
	}
	else 
		$avalue=array((string)$value);
//if Lookup with Table	
	if($LookupSQL)
	{		
		if(UseCategory($field))
		{
			$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.", parentFieldName: '".GoodFieldName(CategoryControl($field))."'}";
		}
//	ajax-lookup control
		if($lookuptype==LCT_AJAX || $lookuptype==LCT_LIST)
		{
//if use dependent elements			
			if(UseCategory($field))
			{
//	dependent dropdown
				/*$str = substr($jsControlObjectParams, 0, -1);
				$jsControlObjectParams = $str.", parentFieldName: ".GoodFieldName(CategoryControl($field))."}";*/
				
				
				$categoryFieldId = GoodFieldName(CategoryControl($field))."_".$id;
				if($lookuptype==LCT_AJAX)
				{
					jscodeToAdd("EditBoxLookup",$jsControlObjectParams,$id, $pageObj);
					echo '<input type="text" categoryId="'.$categoryFieldId.'" autocomplete="off" id="'.$clookupfield.'" name="'.$clookupfield.'" '.$inputStyle.'>';
				}
				elseif(	$lookuptype==LCT_LIST)
				{	
					jscodeToAdd("ListPageLookup",$jsControlObjectParams,$id, $pageObj);
					echo '<input type="text" categoryId="'.$categoryFieldId.'" autocomplete="off" id="'.$clookupfield.'" name="'.$clookupfield.'"  readonly '.$inputStyle.'>';
				}
				echo '<input type="hidden" id="'.$cfield.'" name="'.$cfield.'">';
//if use add new item
				if($mode!=MODE_SEARCH && $addnewitem || $lookuptype==LCT_LIST)
				{
					$celement="document.getElementById('value_".GoodFieldName($strCategoryControl)."_".$id."')";
					if($mode=MODE_INLINE_EDIT && $mode=MODE_INLINE_ADD)
						$extra="&mode=".$mode."&id=".$cfield;
					else
						$extra="";
					$celementvalue = '$('.$celement.").val()";
					if($addnewitem)
					{
						if(!$advancedadd)
						{
							echo "<a href=# id='addnew_".$cfield."' onclick=\"window.open('".$add_table."_addnewitem.php?".
							"field=".htmlspecialchars(jsreplace(rawurlencode($field))).$extra.
							"&category='+escape(".$celementvalue.")".
							",\r\n".
							"'AddNewItem', 'width=250,height=100,status=no,resizable=yes,top=200,left=200');\">\r\n".
							"Add new"."</a>";
						}
						else
							echo "&nbsp;<a href=# id='addnew_".$cfield."' onclick=\"return DisplayPage(event,'".$add_page."',".$id.",'".$cfield."','".jsreplace($field)."','".jsreplace($strTableName)."',".$celementvalue.");\">"."Add new"."</a>";
					}
					if($lookuptype==LCT_LIST)
						echo "&nbsp;<a href=# id=".$openlookup." onclick=\"return DisplayPage(event,'".$list_page."',".$id.",'".$cfield."','".jsreplace($field)."','".jsreplace($strTableName)."',".$celementvalue.");\">"."Select"."</a>";



				}
				if($checkBoxMode)
					echo '</div>';
				return;
//	fasttype - dependent - end
			}
//	fasttype - regular - start
//	get the initial value
			$lookup_SQL = "";
			$lookup_value = "";
			$rs_lookup=db_query($lookup_SQL,$conn);
			if ( $data = db_fetch_numarray($rs_lookup) ) 
				$lookup_value = $data[1];
			else
			{
				$rs_lookup=db_query($lookup_SQL,$conn);			
				
				if($data = db_fetch_numarray($rs_lookup))
					$lookup_value = $data[1];
			}
//	build the control
			if($lookuptype==LCT_AJAX)
			{
				jscodeToAdd("EditBoxLookup",$jsControlObjectParams,$id, $pageObj);
				echo '<input type="text" '.$inputStyle.' autocomplete="off" '.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'id="'.$clookupfield.'" name="'.$clookupfield.'" value="'.htmlspecialchars($lookup_value).'">';
			}
			elseif($lookuptype==LCT_LIST)
			{
				jscodeToAdd("ListPageLookup",$jsControlObjectParams,$id, $pageObj);
				echo '<input type="text" autocomplete="off" '.$inputStyle.' id="'.$clookupfield.'" '.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$clookupfield.'" value="'.htmlspecialchars($lookup_value).'" 	readonly >';
			}
			echo '<input type="hidden" id="'.$cfield.'" name="'.$cfield.'" value="'.htmlspecialchars($value).'">';
			//	add new item
			if($addnewitem &&  $mode!=MODE_SEARCH)
			{
				if(!$advancedadd)
				{
					$extra="";
					if( $mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD )
						$extra="&mode=".$mode."&id=".$cfield;
					echo "<a href=# id='addnew_".$cfield."' onclick=\"window.open('".GetTableURL($strTableName)."_addnewitem.php?field=".htmlspecialchars(jsreplace(rawurlencode($field))).$extra."',\r\n".
					"'AddNewItem', 'width=250,height=100,status=no,resizable=yes,top=200,left=200');\">\r\n".
					"Add new"."</a>";
				}
				else
					echo "&nbsp;<a href=# id='addnew_".$cfield."' onclick=\"return DisplayPage(event,'".$add_page."',".$id.",'".$cfield."','".jsreplace($field)."','".jsreplace($strTableName)."','');\">"."Add new"."</a>";
			}
			if($lookuptype==LCT_LIST)
				echo "&nbsp;<a href=# id=".$openlookup." onclick=\"return DisplayPage(event,'".$list_page."',".$id.",'".$cfield."','".jsreplace($field)."','".jsreplace($strTableName)."','');\">"."Select"."</a>";
//	fasttype - regular - end
//	fasttype - end
		}
//if not ajax lookup		
		else
		{
//	classic dropdown - start
			if($multiple!="")
			{
				$str = substr($jsControlObjectParams, 0, -1);
				$jsControlObjectParams = $str.", multiSel: ".$strSize."}";
			}	
			if(!$checkBoxMode)
				jscodeToAdd("DropDownLookup",$jsControlObjectParams,$id, $pageObj);	
			LogInfo($LookupSQL);
			$rs=db_query($LookupSQL,$conn);
//	print Type control to allow selecting nothing
			if($multiple!="")
				echo "<input id=\"".$ctype."\" type=hidden name=\"".$ctype."\" value=\"multiselect\">";
			$spacer = '<br/>';
	      	$found=false;
		    $i = 0;
		    if ($checkBoxMode)
			{
		    	$str = substr($jsControlObjectParams, 0, -1);
		    	$numRows = db_numrows($rs);
				$jsControlObjectParams = $str.", checkBoxCount: ".$numRows."}";
		    	jscodeToAdd("CheckBoxLookup",$jsControlObjectParams,$id, $pageObj);	
		    }
			while($data=db_fetch_numarray($rs))
			{
				
				$res=array_search((string)$data[0],$avalue);
				if(!($res===NULL || $res===FALSE))
				{
					$found=true;
					if($checkBoxMode)
					{
						echo '<input id="'.$cfield.'_'.$i.'" type="checkbox" '.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.$postfix.'" value="'.htmlspecialchars($data[0]).'" checked="checked"/>';
						echo '&nbsp;<b id="data_'.$cfield.'_'.$i.'">'.htmlspecialchars($data[1]).'</b>'.$spacer;
					}
					else
						echo '<option value="'.htmlspecialchars($data[0]).'" selected>'.htmlspecialchars($data[1]).'</option>';
				}
				else
				{
					if($checkBoxMode)
					{
						echo '<input id="'.$cfield.'_'.$i.'" type="checkbox" '.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.$postfix.'" value="'.htmlspecialchars($data[0]).'"/>';
						echo '&nbsp;<b id="data_'.$cfield.'_'.$i.'">'.htmlspecialchars($data[1]).'</b>'.$spacer;
					}
					else
						echo '<option value="'.htmlspecialchars($data[0]).'">'.htmlspecialchars($data[1]).'</option>';
				}
				$i++;
			}
			$spacer = '<br/>';
			if(!$checkBoxMode)
				echo "</select>";
//	add new item
			if($addnewitem &&  $mode!=MODE_SEARCH && $mode!=MODE_INLINE_EDIT && $mode!=MODE_INLINE_ADD)
			{
				if(!$advancedadd)
				{
					echo "<a href=# id='addnew_".$cfield."' onclick=\"window.open('".GetTableURL($strTableName)."_addnewitem.php?field=".htmlspecialchars(jsreplace(rawurlencode($field)))."',\r\n".
					"'AddNewItem', 'width=250,height=100,status=no,resizable=yes,top=200,left=200');\">\r\n".
					"Add new"."</a>";
				}
				else
				{
					echo "&nbsp;<a href=# id='addnew_".$cfield."' onclick=\"return DisplayPage(event,'".$add_page."',".$id.",'".htmlspecialchars(jsreplace($cfield))."','".jsreplace($field)."','".jsreplace($strTableName)."','');\">"."Add new"."</a>";
				}
			}
			if($addnewitem &&  $mode!=MODE_SEARCH &&  ($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD))
			{
				if(!$advancedadd)
				{
					echo "<a href=# id='addnew_".$cfield."' onclick=\"window.open('".GetTableURL($strTableName)."_addnewitem.php?field=".htmlspecialchars(jsreplace(rawurlencode($field)))."&mode=".$mode."&id=".$cfield."',\r\n".
					"'AddNewItem', 'width=250,height=100,status=no,resizable=yes,top=200,left=200');\">\r\n".
					"Add new"."</a>";
				}
				else
				{
					echo "&nbsp;<a href=# id='addnew_".$cfield."' onclick=\"return DisplayPage(event,'".$add_page."',".$id.",'".$cfield."','".jsreplace($field)."','".jsreplace($strTableName)."','');\">"."Add new"."</a>";
				}
			}
		}
	}
//if Look up as list 	
	else
	{
		//	print Type control to allow selecting nothing
		if($multiple!="")
			echo "<input id=\"".$ctype."\" type=hidden name=\"".$ctype."\" value=\"multiselect\">";
		$spacer = '<br/>';
	}
	if($checkBoxMode)
		echo '</div>';
	return;
}

function BuildRadioControl($field, $value,$fieldNum=0,$id="", $mode)
{
	global $conn,$LookupSQL,$strTableName;
	$is508=isEnableSection508();
	$label=Label($field);
	$cfieldname=GoodFieldName($field)."_".$id;
	$cfield="value_".GoodFieldName($field)."_".$id;
	//$cfieldid="value_".GoodFieldName($field);
	$ctype="type_".GoodFieldName($field)."_".$id;
	
	if($fieldNum)
	{
		$cfield="value".$fieldNum."_".GoodFieldName($field)."_".$id;
		$ctype="type".$fieldNum."_".GoodFieldName($field)."_".$id;
	}
	$LookupSQL ="";
	$spacer = '<br/>';

	if($LookupSQL)
	{
	    LogInfo($LookupSQL);
		$rs=db_query($LookupSQL,$conn);
		echo '<input id="'.$cfield.'" type=hidden name="'.$cfield.'" value="'.htmlspecialchars($value).'">';
		$i=0;
	    while($data=db_fetch_numarray($rs))
		{
			$checked="";
			if($data[0]==$value)
				$checked=" checked";
			echo "<input type=\"Radio\" id=\"radio_".$cfieldname."_".$i."\" ".(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? "alt=\"".$label."\" " : "")."name=\"radio_".$cfieldname."\" ".$checked." value=\"".htmlspecialchars($data[0])."\">".htmlspecialchars($data[1]).$spacer;
			$i++;
		}
	}
	else
	{
		echo '<input id="'.$cfield.'" type=hidden name="'.$cfield.'" value="'.htmlspecialchars($value).'">';
		$i=0;
		foreach($arr as $opt)
		{
			$checked="";
			if($opt==$value)
				$checked=" checked";
			echo "<input  type=\"Radio\" id=\"radio_".$cfieldname."_".$i."\" ".(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $is508==true ? "alt=\"".$label."\" " : "")."name=\"radio_".$cfieldname."\" ".$checked." value=\"".htmlspecialchars($opt)."\">".htmlspecialchars($opt).$spacer;
			$i++;
		}
	}
	return;

}
//For add jscode to control
function jscodeToAdd($ControlObjectName,$jsControlObjectParams,$id, &$pageObj)
{
	global $strTableName;
	
	if ($ControlObjectName)	
	{				
		$jscodeToAdd = "var new".$ControlObjectName.$id." = new Runner.controls.".$ControlObjectName."(".$jsControlObjectParams."); ";
		AddScriptBe4Postload($jscodeToAdd, (isset($pageObj->id) ? $pageObj->id : $id));
	}	
}		
//for js control object params
function ControlObjectParams($validate,$field,$id,$fieldNum=0,$additionalCtrlParams)
{
	global $strTableName;
	$jsControlObjectParams = "{
		fieldName: '".jsreplace($field)."', 
		goodFieldName: '".GoodFieldName($field)."',
		shortTableName: '".jsreplace(GetTableData($strTableName, ".shortTableName", ''))."',
		id: '".$id."',
		ctrlInd: ".$fieldNum.",";
	
	// custom user validation regExp
	if (isset($validate['RegExp']))
	{
		$jsControlObjectParams .= "
			regExp:{
				regex: '".$validate["RegExp"][0]."',
				message: '".$validate["RegExp"][1]."',
				messagetype: '".$validate["RegExp"][2]."'
			},";
	}
	// predefined validations and custom user functions
	if (isset($validate['basicValidate']))
	{
		// make js validation arr for constructor cfg
		$jsControlObjectParams .= "validationArr: [";
		for($i=0;$i<count($validate['basicValidate']);$i++)
			$jsControlObjectParams .= "'".$validate['basicValidate'][$i]."',";		
		$jsControlObjectParams = substr($jsControlObjectParams, 0, -1);
		$jsControlObjectParams .= "],";
	}
	
	// add additional params to object cfg as string
	foreach($additionalCtrlParams as $paramName=>$paramVal)
	{
		if (is_string($paramVal))
			$jsControlObjectParams .= $paramName.": '".$paramVal."',";
		else if(is_bool($paramVal))
			$jsControlObjectParams .= $paramName.": ".($paramVal ? 'true' : 'false').",";
		else
			$jsControlObjectParams .= $paramName.": ".$paramVal.",";
	}
	$jsControlObjectParams .= "table: '".jsreplace($strTableName)."'}";
	return $jsControlObjectParams;
}
 
function BuildEditControl($field , $value, $format, $edit, $fieldNum=0, $id="",$validate, $additionalCtrlParams, &$pageObj)
{
	global $rs,$data,$strTableName,$filenamelist,$keys,$locale_info,$jscode;
			
	$additionalCtrlParams['mode'] = $edit;
	$jsControlObjectParams = ControlObjectParams($validate,$field,$id,$fieldNum,$additionalCtrlParams);
	
	$inputStyle = 'style="';
	$inputStyle .= ($additionalCtrlParams['style'] ? $additionalCtrlParams['style'] : '');
	//$inputStyle .= ($additionalCtrlParams['hidden'] ? 'display: none;' : '');
	$inputStyle .= '"';
	
	// for files, if we need to use time stamp
	if (UseTimestamp($field))
	{
		$str = substr($jsControlObjectParams, 0, -1);
		$jsControlObjectParams = $str.", addTimeStamp: 'true'}";
	}
	
	$cfieldname=GoodFieldName($field)."_".$id;
	$cfield="value_".GoodFieldName($field)."_".$id;
	$ctype="type_".GoodFieldName($field)."_".$id;
	$is508=isEnableSection508();

	$label=Label($field);
	if($fieldNum)

	{
		$cfield="value".$fieldNum."_".GoodFieldName($field)."_".$id;
		$ctype="type".$fieldNum."_".GoodFieldName($field)."_".$id;
	}
	$type=GetFieldType($field);
	$arr="";
	$iquery="field=".rawurlencode($field);
	$keylink="";
	if($strTableName=="carsmake")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	if($strTableName=="carsmodels")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	if($strTableName=="carsusers")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	if($strTableName=="project39_blocking")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	if($strTableName=="project41_audit")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	if($strTableName=="carscars")
	{
		$keylink.="&key1=".rawurlencode(@$keys["id"]);
		$iquery.=$keylink;
	}
	$isHidden = (isset($additionalCtrlParams['hidden']) && $additionalCtrlParams['hidden']);
	echo '<span id="edit'.$id.'_'.GoodFieldName($field).'_'.$fieldNum.'" '.($isHidden ? 'style="display:none;"' : '').'>';
	if($format==EDIT_FORMAT_FILE && $edit==MODE_SEARCH)
		$format="";
	if($format==EDIT_FORMAT_TEXT_FIELD)
	{
		if(IsDateFieldType($type))
		{
			echo '<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="date'.EDIT_DATE_SIMPLE.'">'.GetDateEdit($field,$value,0,$fieldNum,$edit,$id,$jsControlObjectParams, $pageObj);
			$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.", ctrlType: 'date".EDIT_DATE_SIMPLE."'}";			
		}
		else
	    {
			if($edit==MODE_SEARCH)
				echo '<input id="'.$cfield.'" '.$inputStyle.' type="text" autocomplete="off" '. (($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '') . 'name="'.$cfield.'" '.GetEditParams($field).' value="'.htmlspecialchars($value).'">';				
			else
				echo '<input id="'.$cfield.'" '.$inputStyle.' type="text" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" '.GetEditParams($field).' value="'.htmlspecialchars($value).'">';
			
		}
		jscodeToAdd("TextField",$jsControlObjectParams,$id, $pageObj);
	}
	else if($format==EDIT_FORMAT_TIME)
	{
		
		echo '<input id="'.$ctype.'" '.$inputStyle.' type="hidden" name="'.$ctype.'" value="time">';
		$str = substr($jsControlObjectParams, 0, -1);
		$jsControlObjectParams = $str.", ctrlType: 'time'}";
		jscodeToAdd("TimeField",$jsControlObjectParams,$id, $pageObj);	
	}
	else if($format==EDIT_FORMAT_TEXT_AREA)
	{
		$nWidth = GetNCols($field);
		$nHeight = GetNRows($field);
		if(UseRTE($field))
		{
			$value = RTESafe($value);
						$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.", useRTE: 'RTE'}";
			// creating src url
			$browser="";
			if(@$_REQUEST["browser"]=="ie")
				$browser="&browser=ie";
			$iframeSrcParam = GetTableURL($strTableName)."_rte.php?".($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD ? "id=".$id ."&": '').$iquery.$browser;
			// add JS code
			jscodeToAdd("RTEInnova",$jsControlObjectParams,$id, $pageObj);
			echo "<iframe frameborder=\"0\" vspace=\"0\" hspace=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" id=\"".$cfield."\" ".(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? "alt=\"".$label."\" " : "")."name=\"".$cfield."\" title=\"Basic rich text editor\" style='width: " . ($nWidth+1) . "px;height: " . ($nHeight+100) . "px;'";
			echo " src=\"".GetTableURL($strTableName)."_rte.php?".($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD ? "id=".$id ."&": '').$iquery.$browser."&".($edit==MODE_ADD || $edit==MODE_INLINE_ADD ? "action=add" : '')."\">";  
			echo "</iframe>";
								}
		else{
				jscodeToAdd("TextArea",$jsControlObjectParams,$id, $pageObj);
				echo '<textarea id="'.$cfield.'" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" style="width: ' . $nWidth . 'px;height: ' . $nHeight . 'px;">'.htmlspecialchars($value).'</textarea>';
			}
	}
	else if($format==EDIT_FORMAT_PASSWORD)
	{
		echo '<input '.$inputStyle.' id="'.$cfield.'" type="Password" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" '.GetEditParams($field).' value="'.htmlspecialchars($value).'">';
		jscodeToAdd("TextField",$jsControlObjectParams,$id, $pageObj);
	}
	else if($format==EDIT_FORMAT_DATE)
	{
		echo '<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="date'.DateEditType($field).'">'.GetDateEdit($field,$value,DateEditType($field),$fieldNum,$edit,$id,$jsControlObjectParams, $pageObj);
		$str = substr($jsControlObjectParams, 0, -1);
		$jsControlObjectParams = $str.", ctrlType: 'date".DateEditType($field)."'}";	
	}
	else if($format==EDIT_FORMAT_RADIO)
	{
		jscodeToAdd("RadioControl",$jsControlObjectParams,$id, $pageObj);
		BuildRadioControl($field,$value,$fieldNum,$id,$edit);
	}
	else if($format==EDIT_FORMAT_CHECKBOX)
	{
		if($edit==MODE_ADD || $edit==MODE_INLINE_ADD || $edit==MODE_EDIT || $edit==MODE_INLINE_EDIT) 
		{
			$checked="";
			if($value && $value!=0)
				$checked=" checked";
			echo '<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="checkbox">';
			$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.", ctrlType: 'checkbox'}";
			echo '<input id="'.$cfield.'" type="Checkbox" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" '.$checked.'>';
		}
		else
		{
			echo '<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="checkbox">';
			$str = substr($jsControlObjectParams, 0, -1);
			$jsControlObjectParams = $str.", ctrlType: 'checkbox'}";
			echo '<select id="'.$cfield.'" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'">';
			$val=array("none","on","off");
			$show=array("","True","False");
			foreach($val as $i=>$v)
			{
				$sel="";
				if($value===$v)
					$sel=" selected";
				echo '<option value="'.$v.'"'.$sel.'>'.$show[$i].'</option>';
			}
			echo "</select>";
		}
	}
	else if($format==EDIT_FORMAT_DATABASE_IMAGE || $format==EDIT_FORMAT_DATABASE_FILE)
	{
		
		if ($format==EDIT_FORMAT_DATABASE_IMAGE){
			jscodeToAdd("ImageField",$jsControlObjectParams,$id, $pageObj);	
		}else {
			jscodeToAdd("FileField",$jsControlObjectParams,$id, $pageObj);
		}
		$disp="";
		$strfilename="";
		//$onchangefile="";
		if($edit==MODE_EDIT || $edit==MODE_INLINE_EDIT)
		{
			$value=db_stripslashesbinary($value);
			$itype=SupposeImageType($value);
			$thumbnailed=false;
			$thumbfield="";

			if($itype)
			{
				if($thumbnailed)
				{
					$disp="<a ";
					
					if(IsUseiBox($field, $strTableName))
						$disp.= " rel='ibox'";
					else
						$disp.= " target=_blank";
						
					$disp.=" href=\"".GetTableURL($strTableName)."_imager.php?".$iquery."\">";
					$disp.= "<img name=\"".$cfield."\" border=0";
					if(isEnableSection508())
						$disp.= " alt=\"Image from DB\"";
					$disp.=" src=\"".GetTableURL($strTableName)."_imager.php?field=".rawurlencode($thumbfield)."&alt=".rawurlencode($field).$keylink."\">";
					$disp.= "</a>";
				}
				else
				{
					$disp='<img name="'.$cfield.'"';
					if(isEnableSection508())
						$disp.= ' alt="Image from DB"';
					$disp.=' border=0 src="'.GetTableURL($strTableName).'_imager.php?'.$iquery.'">';
				}	
				
			}
			else
			{
				if(strlen($value))
				{
					$disp='<img name="'.$cfield.'" border=0 ';
					if(isEnableSection508())
						$disp.= ' alt="file"';
					$disp.='src="images/file.gif">';
				}
				else
				{
					$disp='<img name="'.$cfield.'" border=0';
					if(isEnableSection508())
						$disp.= ' alt=" "';
					$disp.='src="images/no_image.gif">';
				}
			}
//	filename
			if($format==EDIT_FORMAT_DATABASE_FILE && !$itype && strlen($value))
			{
				if(!($filename=@$data[GetFilenameField($field)]))
					$filename="file.bin";
				$disp='<a href="'.GetTableURL($strTableName).'_getfile.php?filename='.htmlspecialchars($filename).'&'.$iquery.'".>'.$disp.'</a>';
			}
//	filename edit
			if($format==EDIT_FORMAT_DATABASE_FILE && GetFilenameField($field))
			{
				if(!($filename=@$data[GetFilenameField($field)]))
					$filename="";
				if($edit==MODE_INLINE_EDIT)
				{
					$strfilename='<br><label for="filename_'.$cfieldname.'">'."Filename".'</label>&nbsp;&nbsp;<input type="text" '.$inputStyle.' id="filename_'.$cfieldname.'" name="filename_'.$cfieldname.'" size="20" maxlength="50" value="'.htmlspecialchars($filename).'">';					
				}
				else
				{
					$strfilename='<br><label for="filename_'.$cfieldname.'">'."Filename".'</label>&nbsp;&nbsp;<input type="text" '.$inputStyle.' id="filename_'.$cfieldname.'" name="filename_'.$cfieldname.'" size="20" maxlength="50" value="'.htmlspecialchars($filename).'">';					
				}
			}
			$strtype='<br><input id="'.$ctype.'_keep" type="Radio" name="'.$ctype.'" value="file0" checked>'."Keep";
			if(strlen($value) && !IsRequired($field))
			{
				$strtype.='<input id="'.$ctype.'_delete" type="Radio" name="'.$ctype.'" value="file1">'."Delete";
			}
			$strtype.='<input id="'.$ctype.'_update" type="Radio" name="'.$ctype.'" value="file2">'."Update";
		}
		else
		{
//	if Add mode
			$strtype='<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="file2">';
			if($format==EDIT_FORMAT_DATABASE_FILE && GetFilenameField($field))
			{
				$strfilename='<br><label for="filename_'.$cfieldname.'">'."Filename".'</label>&nbsp;&nbsp;<input type="text" '.$inputStyle.' id="filename_'.$cfieldname.'" name="filename_'.$cfieldname.'" size="20" maxlength="50">';			
			}
		}
		
		if($edit==MODE_INLINE_EDIT && $format==EDIT_FORMAT_DATABASE_FILE)
			$disp="";
		echo $disp.$strtype.'<br><input type="File" '.$inputStyle.' id="'.$cfield.'" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" >'.$strfilename;
	}
	else if($format==EDIT_FORMAT_LOOKUP_WIZARD)
			BuildSelectControl($field, $value, $arr, $fieldNum, $edit,$id, $jsControlObjectParams, $additionalCtrlParams, $pageObj);
	else if($format==EDIT_FORMAT_HIDDEN)
			echo '<input id="'.$cfield.'" type="Hidden" name="'.$cfield.'" value="'.htmlspecialchars($value).'">';
	else if($format==EDIT_FORMAT_READONLY)
			echo '<input id="'.$cfield.'" type="Hidden" name="'.$cfield.'" value="'.htmlspecialchars($value).'">';
	else if($format==EDIT_FORMAT_FILE)
	{
		jscodeToAdd("FileField",$jsControlObjectParams,$id, $pageObj);
		$disp="";
		$strfilename="";
		$function="";
		if($edit==MODE_EDIT || $edit==MODE_INLINE_EDIT)
		{
//	show current file
			if(ViewFormat($field)==FORMAT_FILE || ViewFormat($field)==FORMAT_FILE_IMAGE)
			{
				$disp=GetData($data,$field,ViewFormat($field))."<br>";
			}
			$filename=$value;			
//	filename edit
			$filename_size=30;
			if(UseTimestamp($field))
				$filename_size=50;
			$strfilename='<input type=hidden name="filenameHidden_'.$cfieldname.'" value="'.htmlspecialchars($filename).'"><br>'."Filename".'&nbsp;&nbsp;<input type="text" style="background-color:gainsboro" disabled id="filename_'.$cfieldname.'" name="filename_'.$cfieldname.'" size="'.$filename_size.'" maxlength="100" value="'.htmlspecialchars($filename).'">';
			if ( $edit==MODE_INLINE_EDIT ) {
				$strtype='<br><input id="'.$ctype.'_keep" type="Radio" name="'.$ctype.'" value="upload0" checked onclick="/*$(\'[@id='.$cfield.']\').css(\'backgroundColor\',\'gainsboro\');$(\'[@id='.$cfield.']\')[0].disabled=true;*/">'."Keep";
			} else {
				$strtype='<br><input id="'.$ctype.'_keep" type="Radio" name="'.$ctype.'" value="upload0" checked onclick="/*controlfilename'.$cfieldname.'(false)*/">'."Keep";
			}


			if(strlen($value) && !IsRequired($field))
			{
				$strtype.='<input id="'.$ctype.'_delete" type="Radio" name="'.$ctype.'" value="upload1">'."Delete";
			}
			$strtype.='<input id="'.$ctype.'_update" type="Radio" name="'.$ctype.'" value="upload2">'."Update";
		}
		else
		{
//	if Adding record		
			$filename_size=30;
			if(UseTimestamp($field))
				$filename_size=50;
			$strtype='<input id="'.$ctype.'" type="hidden" name="'.$ctype.'" value="upload2">';
			$strfilename='<br>'."Filename".'&nbsp;&nbsp;<input type="text" id="filename_'.$cfieldname.'" name="filename_'.$cfieldname.'" size="'.$filename_size.'" maxlength="100">';			
		}
		echo $disp.$strtype.$function.'<br><input type="File" id="'.$cfield.'" '.(($edit==MODE_INLINE_EDIT || $edit==MODE_INLINE_ADD) && $is508==true ? 'alt="'.$label.'" ' : '').'name="'.$cfield.'" >'.$strfilename;
	}
	if(count($validate['basicValidate']) && array_search('IsRequired', $validate['basicValidate'])!==false)
		echo'&nbsp;<font color="red">*</font></span>';
	else
		echo '</span>';
}
function my_stripos($str,$needle, $offest)
{
    if (strlen($needle)==0 || strlen($str)==0)
		return false;
	return strpos(strtolower($str),strtolower($needle), $offest);
} 

function my_str_ireplace($search, $replace,$str)
{
    $pos=my_stripos($str,$search,0);
	if($pos===false)
		return $str;
	return substr($str,0,$pos).$replace.substr($str,$pos+strlen($search));
} 


function in_assoc_array($name, $arr)
{
foreach ($arr as $key => $value) 
	if ($key==$name)
		return true;

return false;
}

function loadSelectContent($childFieldName, $parentVal, $doFilter=true, $childVal="")
{
	global $conn,$LookupSQL,$strTableName;
	
	$Lookup = "";
	$response = array();
	$output = "";


	$rs=db_query($LookupSQL,$conn);

	if(!FastType($childFieldName))
	{
		while ($data = db_fetch_numarray($rs)) 
		{
			$response[] = $data[0];
			$response[] = $data[1];
		}
	}
	else
	{
		$data=db_fetch_numarray($rs);
//	one record only
		if($data && (strlen($childVal) || !db_fetch_numarray($rs)))
		{
			$response[] = $data[0];
			$response[] = $data[1];
		}
	}
	return $response;
}

function xmlencode($str)
{

	$str = str_replace("&","&amp;",$str);
	$str = str_replace("<","&lt;",$str);
	$str = str_replace(">","&gt;",$str);

	$out="";
	$len=strlen($str);
	$ind=0;
	for($i=0;$i<$len;$i++)
	{
		if(ord(substr($str,$i,1))>=128)
		{
			if($ind<$i)
				$out.=substr($str,$ind,$i-$ind);
			$out.="&#".ord(substr($str,$i,1)).";";
			$ind=$i+1;
		}
	}
	if($ind<$len)
		$out.=substr($str,$ind);
	return str_replace("'","&apos;",$out);

}

function print_inline_array(&$arr,$printkey=false)
{
	if(!$printkey)
	{
		foreach ( $arr as $key=>$val )
			echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$val))."\\n";
	}
	else
	{
		foreach( $arr as $key=>$val )
			echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$key))."\\n";
	}
		
}


function GetChartXML($chartname)
{


}

//For add script code to function postloadstep, that performed after loading all files
function AddScript2Postload($code,$id="")
{
	echo "<s"."cript  language='javascript'>
			AddScript2Postload(function(){".$code."}, ".($id!=='' ? $id : "''").");
		 </script>";
}

function AddScriptBe4Postload($code,$id="")
{
	echo "<s"."cript  language='javascript'>
			AddScriptBe4Postload(function(){".$code."}, ".($id!=='' ? $id : "''").");
		 </script>";
}

function AddCSSFile($file)
{
	global $includes_css;
	$includes_css[]=$file;
}

function AddJSFile($file,$req1="",$req2="",$req3="")
{
	global $includes_js,$includes_jsreq;
	$includes_js[]=$file;
	if($req1!="")
		$includes_jsreq[$file]=array($req1);
	if($req2!="")
		$includes_jsreq[$file][]=$req2;
	if($req3!="")
		$includes_jsreq[$file][]=$req3;
}

function LoadJS_CSS($id)
{
	global $includes_js,$includes_jsreq,$includes_css;
	$includes_js=array_unique($includes_js);
	$includes_css=array_unique($includes_css);
	$sl = "sl".$id;
	$out="var ".$sl." = new ScriptLoader('".$id."');\r\n";
	foreach($includes_css as $file)
		$out.=$sl.".loadCSS('".$file."');";
	foreach($includes_js as $file)
	{
		$out .= $sl.".addJS('".$file."'";
		if(array_key_exists($file,$includes_jsreq))
		{
			foreach($includes_jsreq[$file] as $req)
				$out.=",'".$req."'";
		}
		$out.=");\r\n";
	}
	$out.=$sl.".load();";
	return $out;
}

function PrepareJSCode(&$js,$id)
{
	$js="window.postloadstep".($id!=='' ? "_".$id : '')." = function(){".$js."};\r\n ";
	$js.=LoadJS_CSS($id);
}


function loadindicator()
{
	$path = GetAbsoluteFileName("templates/loadindicator.htm");
	return myfile_get_contents($path,"r");
}




function GetSiteUrl()
{
	$url = "http://".$_SERVER["SERVER_NAME"];
	if($_SERVER["SERVER_PORT"]!=80)
	{
		if ($_SERVER["SERVER_PORT"]==443)
		   $url = "https://".$_SERVER["SERVER_NAME"];
		else
		   $url.=":".$_SERVER["SERVER_PORT"];
	}
	return $url;
}
function isAuditEnable($table)
{
	if($table=="carsmake")
		return true;
	if($table=="carsmodels")
		return true;
	if($table=="carsusers")
		return false;
	if($table=="project39_blocking")
		return false;
	if($table=="project41_audit")
		return false;
	if($table=="carscars")
		return true;
}
function isBlockingEnable($table)
{
	if($table=="carsmake")
		return true;
	if($table=="carsmodels")
		return true;
	if($table=="carsusers")
		return false;
	if($table=="project39_blocking")
		return false;
	if($table=="project41_audit")
		return false;
	if($table=="carscars")
		return true;
}


function isEnableSection508()
{
	return false;
}
function isEnableUpper($val)
{
	global $strTableName,$tables_data;
	if($tables_data[$strTableName][".NCSearch"])
		return db_upper($val);
	else
		return $val;
}
/**
 * Returns validation type which defined in js validation object.
 * Use this function, because runner constants has another names of validation functions
 *
 * @param string $name
 * @return string
 */
function getJsValidatorName($name) 
{	
	switch ($name) 
	{
		case "Number":
			return "IsNumeric";
			break;
		case "Password":
			return "IsPassword";
			break;
		case "Email":
			return "IsEmail";
			break;
		case "Currency":
			return "IsMoney";
			break;
		case "US ZIP Code":
			return "IsZipCode";
			break;
		case "US Phone Number":
			return "IsPhoneNumber";
			break;
		case "US State":
			return "IsState";
			break;
		case "US SSN":
			return "IsSSN";
			break;
		case "Credit Card":
			return "IsCC";
			break;
		case "Time":
			return "IsTime";
			break;
		case "Regular expression":
			return "RegExp";
			break;						
		default:
			return $name;
			break;
	}
}

function GetInputElementId($field ,$id)
{
	$format=GetEditFormat($field);
	if($format==EDIT_FORMAT_DATE)
	{
		$type=DateEditType($field);
		if($type==EDIT_DATE_DD || $type==EDIT_DATE_DD_DP)
			return "dayvalue_".GoodFieldName($field)."_".$id;
		else
			return "value_".GoodFieldName($field)."_".$id;
	}
	else if($format==EDIT_FORMAT_RADIO)
		return "radio_".GoodFieldName($field)."_".$id."_0";
	else if($format==EDIT_FORMAT_LOOKUP_WIZARD)	
	{
		$lookuptype=LookupControlType($field);
		if($lookuptype==LCT_AJAX || $lookuptype==LCT_LIST)
			return "display_value_".GoodFieldName($field)."_".$id;
		else
			return "value_".GoodFieldName($field)."_".$id;
	}	
	else
		return "value_".GoodFieldName($field)."_".$id;		
}

function SetLangVars($links)
{
	global $xt;
	$xt->assign("lang_label",true);
	if(@$_REQUEST["language"])
		$_SESSION["language"]=@$_REQUEST["language"];

	$var=GoodFieldName(mlang_getcurrentlang())."_langattrs";
	$xt->assign($var,"selected");
	$is508=isEnableSection508();
	if($is508)
		$xt->assign_section("lang_label","<label for=\"lang\">","</label>");
	$xt->assign("langselector_attrs","name=lang ".($is508==true ? "id=\"lang\" " : "")."onchange=\"javascript: window.location='".$links.".php?language='+this.options[this.selectedIndex].value\"");
}

function isShowDetailTable()
{
	return true;
}	
function GetTableCaption($table)
{
	global $tableCaptions;
	return @$tableCaptions[mlang_getcurrentlang()][$table];
}

function GetFieldLabel($table,$field)
{
	global $field_labels;
	if(!array_key_exists($table,$field_labels))
		return "";
	return @$field_labels[$table][mlang_getcurrentlang()][$field];
}

function GetCustomLabel($custom)
{
	global $custom_labels;
	return @$custom_labels[mlang_getcurrentlang()][$custom];
}
function mlang_getcurrentlang()
{
	global $mlang_messages,$mlang_defaultlang;
	if(@$_SESSION["language"])
		return $_SESSION["language"];
	return $mlang_defaultlang;
}
function mlang_getlanglist()
{
	global $mlang_messages,$mlang_defaultlang;
	return array_keys($mlang_messages);
}
?>
