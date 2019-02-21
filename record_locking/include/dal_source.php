<?php

$dal_info=array();
$daltable_carsmake = array();
$daltable_carsmake["id"]=array();
$daltable_carsmake["id"]["nType"]=3;
	$daltable_carsmake["id"]["bKey"]=true;
$daltable_carsmake["id"]["varname"]="id";
$daltable_carsmake["make"]=array();
$daltable_carsmake["make"]["nType"]=200;
	$daltable_carsmake["make"]["varname"]="make";
$dal_info["carsmake"]=&$daltable_carsmake;
$daltable_carsmodels = array();
$daltable_carsmodels["id"]=array();
$daltable_carsmodels["id"]["nType"]=3;
	$daltable_carsmodels["id"]["bKey"]=true;
$daltable_carsmodels["id"]["varname"]="id";
$daltable_carsmodels["make"]=array();
$daltable_carsmodels["make"]["nType"]=200;
	$daltable_carsmodels["make"]["varname"]="make";
$daltable_carsmodels["model"]=array();
$daltable_carsmodels["model"]["nType"]=200;
	$daltable_carsmodels["model"]["varname"]="model";
$dal_info["carsmodels"]=&$daltable_carsmodels;
$daltable_carscars = array();
$daltable_carscars["category"]=array();
$daltable_carscars["category"]["nType"]=200;
	$daltable_carscars["category"]["varname"]="category";
$daltable_carscars["color"]=array();
$daltable_carscars["color"]["nType"]=200;
	$daltable_carscars["color"]["varname"]="color";
$daltable_carscars["Date Listed"]=array();
$daltable_carscars["Date Listed"]["nType"]=7;
	$daltable_carscars["Date Listed"]["varname"]="Date_Listed";
$daltable_carscars["descr"]=array();
$daltable_carscars["descr"]["nType"]=201;
	$daltable_carscars["descr"]["varname"]="descr";
$daltable_carscars["EPACity"]=array();
$daltable_carscars["EPACity"]["nType"]=200;
	$daltable_carscars["EPACity"]["varname"]="EPACity";
$daltable_carscars["EPAHighway"]=array();
$daltable_carscars["EPAHighway"]["nType"]=200;
	$daltable_carscars["EPAHighway"]["varname"]="EPAHighway";
$daltable_carscars["features"]=array();
$daltable_carscars["features"]["nType"]=201;
	$daltable_carscars["features"]["varname"]="features";
$daltable_carscars["Horsepower"]=array();
$daltable_carscars["Horsepower"]["nType"]=3;
	$daltable_carscars["Horsepower"]["varname"]="Horsepower";
$daltable_carscars["id"]=array();
$daltable_carscars["id"]["nType"]=3;
	$daltable_carscars["id"]["bKey"]=true;
$daltable_carscars["id"]["varname"]="id";
$daltable_carscars["Make"]=array();
$daltable_carscars["Make"]["nType"]=200;
	$daltable_carscars["Make"]["varname"]="Make";
$daltable_carscars["Model"]=array();
$daltable_carscars["Model"]["nType"]=200;
	$daltable_carscars["Model"]["varname"]="Model";
$daltable_carscars["Phone #"]=array();
$daltable_carscars["Phone #"]["nType"]=200;
	$daltable_carscars["Phone #"]["varname"]="Phone__";
$daltable_carscars["Picture"]=array();
$daltable_carscars["Picture"]["nType"]=128;
	$daltable_carscars["Picture"]["varname"]="Picture";
$daltable_carscars["Price"]=array();
$daltable_carscars["Price"]["nType"]=3;
	$daltable_carscars["Price"]["varname"]="Price";
$daltable_carscars["UserID"]=array();
$daltable_carscars["UserID"]["nType"]=200;
	$daltable_carscars["UserID"]["varname"]="UserID";
$daltable_carscars["YearOfMake"]=array();
$daltable_carscars["YearOfMake"]["nType"]=3;
	$daltable_carscars["YearOfMake"]["varname"]="YearOfMake";
$daltable_carscars["zipcode"]=array();
$daltable_carscars["zipcode"]["nType"]=3;
	$daltable_carscars["zipcode"]["varname"]="zipcode";
$dal_info["carscars"]=&$daltable_carscars;
$daltable_project39_blocking = array();
$daltable_project39_blocking["id"]=array();
$daltable_project39_blocking["id"]["nType"]=3;
	$daltable_project39_blocking["id"]["bKey"]=true;
$daltable_project39_blocking["id"]["varname"]="id";
$daltable_project39_blocking["tablename"]=array();
$daltable_project39_blocking["tablename"]["nType"]=200;
	$daltable_project39_blocking["tablename"]["varname"]="tablename";
$daltable_project39_blocking["startdatetime"]=array();
$daltable_project39_blocking["startdatetime"]["nType"]=135;
	$daltable_project39_blocking["startdatetime"]["varname"]="startdatetime";
$daltable_project39_blocking["confirmdatetime"]=array();
$daltable_project39_blocking["confirmdatetime"]["nType"]=135;
	$daltable_project39_blocking["confirmdatetime"]["varname"]="confirmdatetime";
$daltable_project39_blocking["keys"]=array();
$daltable_project39_blocking["keys"]["nType"]=200;
	$daltable_project39_blocking["keys"]["varname"]="keys";
$daltable_project39_blocking["sessionid"]=array();
$daltable_project39_blocking["sessionid"]["nType"]=200;
	$daltable_project39_blocking["sessionid"]["varname"]="sessionid";
$daltable_project39_blocking["userid"]=array();
$daltable_project39_blocking["userid"]["nType"]=200;
	$daltable_project39_blocking["userid"]["varname"]="userid";
$daltable_project39_blocking["action"]=array();
$daltable_project39_blocking["action"]["nType"]=3;
	$daltable_project39_blocking["action"]["varname"]="action";
$dal_info["project39_blocking"]=&$daltable_project39_blocking;
$daltable_project41_audit = array();
$daltable_project41_audit["id"]=array();
$daltable_project41_audit["id"]["nType"]=3;
	$daltable_project41_audit["id"]["bKey"]=true;
$daltable_project41_audit["id"]["varname"]="id";
$daltable_project41_audit["datetime"]=array();
$daltable_project41_audit["datetime"]["nType"]=135;
	$daltable_project41_audit["datetime"]["varname"]="datetime";
$daltable_project41_audit["ip"]=array();
$daltable_project41_audit["ip"]["nType"]=200;
	$daltable_project41_audit["ip"]["varname"]="ip";
$daltable_project41_audit["user"]=array();
$daltable_project41_audit["user"]["nType"]=200;
	$daltable_project41_audit["user"]["varname"]="user";
$daltable_project41_audit["table"]=array();
$daltable_project41_audit["table"]["nType"]=200;
	$daltable_project41_audit["table"]["varname"]="table";
$daltable_project41_audit["action"]=array();
$daltable_project41_audit["action"]["nType"]=200;
	$daltable_project41_audit["action"]["varname"]="action";
$daltable_project41_audit["description"]=array();
$daltable_project41_audit["description"]["nType"]=201;
	$daltable_project41_audit["description"]["varname"]="description";
$dal_info["project41_audit"]=&$daltable_project41_audit;
$daltable_carsusers = array();
$daltable_carsusers["id"]=array();
$daltable_carsusers["id"]["nType"]=3;
	$daltable_carsusers["id"]["bKey"]=true;
$daltable_carsusers["id"]["varname"]="id";
$daltable_carsusers["password"]=array();
$daltable_carsusers["password"]["nType"]=200;
	$daltable_carsusers["password"]["varname"]="password";
$daltable_carsusers["username"]=array();
$daltable_carsusers["username"]["nType"]=200;
	$daltable_carsusers["username"]["varname"]="username";
$dal_info["carsusers"]=&$daltable_carsusers;



function CustomQuery($dalSQL)
{
	global $conn;
	$rs = db_query($dalSQL,$conn);
	  return $rs;
}

function UsersTableName()
{
	return "`carsusers`";
}


class tDAL
{
	var $carsmake;
	var $carsmodels;
	var $carscars;
	var $project39_blocking;
	var $project41_audit;
	var $carsusers;
  function Table($strTable)
  {
          if(strtoupper($strTable)==strtoupper("carsmake"))
              return $this->carsmake;
          if(strtoupper($strTable)==strtoupper("carsmodels"))
              return $this->carsmodels;
          if(strtoupper($strTable)==strtoupper("carscars"))
              return $this->carscars;
          if(strtoupper($strTable)==strtoupper("project39_blocking"))
              return $this->project39_blocking;
          if(strtoupper($strTable)==strtoupper("project41_audit"))
              return $this->project41_audit;
          if(strtoupper($strTable)==strtoupper("carsusers"))
              return $this->carsusers;
//	check table names without dbo. and other prefixes
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("carsmake")))
              return $this->carsmake;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("carsmodels")))
              return $this->carsmodels;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("carscars")))
              return $this->carscars;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("project39_blocking")))
              return $this->project39_blocking;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("project41_audit")))
              return $this->project41_audit;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("carsusers")))
              return $this->carsusers;
  }
}

$dal = new tDAL;

class tDALTable
{
	var $m_TableName;
	var $Param = array();
	var $Value = array();
	
	function TableName()
	{
		return AddTableWrappers($this->m_TableName);
	} 
	
	function Add() 
	{
		global $conn,$dal_info;
		$insertFields="";
		$insertValues="";
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].'))
			{
				$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';
			}';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				$insertFields.= AddFieldWrappers($fieldname).",";
				if (NeedQuotes($fld["nType"]))
					$insertValues.= "'".db_addslashes($value) . "',";
				else
					$insertValues.= "".(0+$value) . ",";		
				break;
			}
		}
//	prepare and exec SQL
		if ($insertFields!="" && $insertValues!="")		
		{
			$insertFields = substr($insertFields,0,-1);
			$insertValues = substr($insertValues,0,-1);
			$dalSQL = "insert into ".AddTableWrappers($this->m_TableName)." (".$insertFields.") values (".$insertValues.")";
			db_exec($dalSQL,$conn);
		}
//	cleanup		
	    $this->Reset();
	}

	function QueryAll()
	{
		global $conn;
		$dalSQL = "select * from ".AddFieldWrappers($this->m_TableName);
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}

	function Query($swhere="",$orderby="")
	{
		global $conn;
		if ($swhere)
			$swhere = " where ".$swhere;
		if ($orderby)
			$orderby = " order by ".$orderby;
		$dalSQL = "select * from ".AddTableWrappers($this->m_TableName).$swhere.$orderby;
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}

	function Delete()
	{
		global $conn,$dal_info;
		$deleteFields="";
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].'))
			{
				$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';
			}
			';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				if (NeedQuotes($fld["nType"]))
					$deleteFields.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "' and ";
				else
					$deleteFields.= AddFieldWrappers($fieldname)."=". (0+$value) . " and ";		
				break;
			}
		}
//	do delete
		if ($deleteFields)
		{
			$deleteFields = substr($deleteFields,0,-5);
			$dalSQL = "delete from ".AddFieldWrappers($this->m_TableName)." where ".$deleteFields;
			db_exec($dalSQL,$conn);
		}
	
//	cleanup
	    $this->Reset();
	}

	function Reset()
	{
		$this->Value=array();
		$this->Param=array();
		global $dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='unset($this->'.$fld["varname"].");";
			eval($command);
		}
	}	

	function Update()
	{
		global $conn,$dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];
		$updateParam = "";
		$updateValue = "";

		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].')) { ';
			if($fld["bKey"])
				$command.='$this->Param[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			else
				$command.='$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			$command.=' }';
			eval($command);
			if(!$fld["bKey"] && !array_key_exists($fieldname,$this->Param))
			{
				foreach($this->Value as $field=>$value)
				{
					if (strtoupper($field)!=strtoupper($fieldname))
						continue;
					if (NeedQuotes($fld["nType"]))
						$updateValue.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "', ";
					else
						$updateValue.= AddFieldWrappers($fieldname)."=".(0+$value) . ", ";
					break;
				}
			}
			else
			{
				foreach($this->Param as $field=>$value)
				{
					if (strtoupper($field)!=strtoupper($fieldname))
						continue;
					if (NeedQuotes($fld["nType"]))
						$updateParam.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "' and ";
					else
						$updateParam.= AddFieldWrappers($fieldname)."=".(0+$value) . " and ";
					break;
				}
			}
		}

//	construct SQL and do update	
		if ($updateParam)
			$updateParam = substr($updateParam,0,-5);
		if ($updateValue)
			$updateValue = substr($updateValue,0,-2);
		if ($updateValue && $updateParam)
		{
			$dalSQL = "update ".AddTableWrappers($this->m_TableName)." set ".$updateValue." where ".$updateParam;
			db_exec($dalSQL,$conn);
		}

//	cleanup
		$this->Reset();
	}

	function FetchByID()
	{
		global $conn,$dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];

		$dal_where="";
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].')) { ';
			$command.='$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			$command.=' }';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				if (NeedQuotes($fld["nType"]))
					$dal_where.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "' and ";
				else
					$dal_where.= AddFieldWrappers($fieldname)."=".(0+$value) . " and ";
				break;
			}
		}
//	cleanup
		$this->Reset();
//	construct and run SQL
		if ($dal_where)
			$dal_where = " where ".substr($dal_where,0,-5);
		$dalSQL = "select * from ".AddTableWrappers($this->m_TableName).$dal_where;
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}
}

class class_carsmake extends tDALTable
{
	var $id;
	var $make;

	function class_carsmake()
	{
		$this->m_TableName = "carsmake";
	}
}
$dal->carsmake = new class_carsmake();
class class_carsmodels extends tDALTable
{
	var $id;
	var $make;
	var $model;

	function class_carsmodels()
	{
		$this->m_TableName = "carsmodels";
	}
}
$dal->carsmodels = new class_carsmodels();
class class_carscars extends tDALTable
{
	var $category;
	var $color;
	var $Date_Listed;
	var $descr;
	var $EPACity;
	var $EPAHighway;
	var $features;
	var $Horsepower;
	var $id;
	var $Make;
	var $Model;
	var $Phone__;
	var $Picture;
	var $Price;
	var $UserID;
	var $YearOfMake;
	var $zipcode;

	function class_carscars()
	{
		$this->m_TableName = "carscars";
	}
}
$dal->carscars = new class_carscars();
class class_project39_blocking extends tDALTable
{
	var $id;
	var $tablename;
	var $startdatetime;
	var $confirmdatetime;
	var $keys;
	var $sessionid;
	var $userid;
	var $action;

	function class_project39_blocking()
	{
		$this->m_TableName = "project39_blocking";
	}
}
$dal->project39_blocking = new class_project39_blocking();
class class_project41_audit extends tDALTable
{
	var $id;
	var $datetime;
	var $ip;
	var $user;
	var $table;
	var $action;
	var $description;

	function class_project41_audit()
	{
		$this->m_TableName = "project41_audit";
	}
}
$dal->project41_audit = new class_project41_audit();
class class_carsusers extends tDALTable
{
	var $id;
	var $password;
	var $username;

	function class_carsusers()
	{
		$this->m_TableName = "carsusers";
	}
}
$dal->carsusers = new class_carsusers();

class DalRecordset
{
	
	var $m_rs;
	var $m_fields;
	var $m_eof;
	
	function Fields($field="")
	{
		if(!$field)
			return $this->m_fields;
		return $this->Field($field);
	}
	
	function Field($field)
	{
		if($this->m_eof)
			return false;
		foreach($this->m_fields as $name=>$value)
		{
			if(!strcasecmp($name,$field))
				return $value;
		}
		return false;
	}
	function DalRecordset($rs)
	{
		$this->m_rs=$rs;
		$this->MoveNext();
	}
	function EOF()
	{
		return $this->m_eof;
	}
	
	function MoveNext()
	{
		if(!$this->m_eof)
			$this->m_fields=db_fetch_array($this->m_rs);
		$this->m_eof = !$this->m_fields;
		return !$this->m_eof;
	}
}

function cutprefix($table)
{
	$pos=strpos($table,".");
	if($pos===false)
		return $table;
	return substr($table,$pos+1);
}

function escapesq($str)
{
	return str_replace(array("\\","'"),array("\\\\","\\'"),$str);
}

?>