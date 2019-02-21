<?php
class AuditTrailTable
{
	var $logTableName="project41_audit";
			var $attLogin=0;
		var $timeLogin=0;
	
    function LogLogin()
    {
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]="carsusers";
		$dal->$table->Value["action"]="login";
		$dal->$table->Value["description"]="";
		$dal->$table->Add();
    }
    function LogLoginFailed()
    {
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]="carsusers";
		$dal->$table->Value["action"]="failed login";
		$dal->$table->Value["description"]="";
		$dal->$table->Add();
    }
    function LogLogout()
    {
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");		
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]="carsusers";
		$dal->$table->Value["action"]="logout";
		$dal->$table->Value["description"]="";
		$dal->$table->Add();
    }
    function LogChPassword()
    {
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");		
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]="carsusers";
		$dal->$table->Value["action"]="Change password";
		$dal->$table->Value["description"]="";
		$dal->$table->Add();
    }
    function LogAdd($str_table,$values,$keys)
    {
		$str="";
   		if($this->logValueEnable($str_table))
		{
			if(count($keys)>0)
			{
				$str.="---Keys\r\n";
				foreach($keys as $idx=>$val)
					$str.=$idx." : ".$val."\r\n";
			}
			$str.="---Fields\r\n";
			foreach($values as $idx=>$val)
			{
				if($val!="" && !array_key_exists($idx,$keys))
				{
					$str.=$idx." [new]: ";
					if(IsBinaryType(GetFieldType($idx)))
						$v="<binary value>";
					else
					{
						$v=str_replace(array("\r\n","\n","\t")," ",$val);
						if(is_string($v) && strlen($v)>300)
							$v=substr($val,0,300);
					}
					$str.=$v."\r\n";
				}
			}
		}
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]=$str_table;
		$dal->$table->Value["action"]="add";
		$dal->$table->Value["description"]=$str;
		$dal->$table->Add();
    }
    function LogEdit($str_table,$newvalues,$oldvalues,$keys)
    {
		$str="";
		if($this->logValueEnable($str_table))
		{
			if(count($keys)>0)
			{
				$str.="---Keys\r\n";
				foreach($keys as $idx=>$val)
					$str.=$idx." : ".$val."\r\n";
			}
			
			$str.="---Fields\r\n";
			$v="";
			foreach($newvalues as $idx=>$val)
			{
				if($val!=$oldvalues[$idx] && !array_key_exists($idx,$keys))
				{
					$str.=$idx." [old]: ";
					if(IsBinaryType(GetFieldType($idx)))
						$v="<binary value>";
					else
					{
						$v=str_replace(array("\r\n","\n","\t")," ",$oldvalues[$idx]);
						if(is_string($v) && strlen($v)>300)
							$v=substr($v,0,300);
					}
					$str.=$v."\r\n";
											
					$str.=$idx." [new]: ";
					if(IsBinaryType(GetFieldType($idx)))
						$v="<binary value>";
					else
					{
						$v=str_replace(array("\r\n","\n","\t")," ",$val);
						if(is_string($v) && strlen($v)>300)
							$v=substr($v,0,300);
					}
					$str.=$v."\r\n";
				}
			}
			$v="";
		}
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]=$str_table;
		$dal->$table->Value["action"]="edit";
		$dal->$table->Value["description"]=$str;
		$dal->$table->Add();
    }
    function LogDelete($str_table,$values,$keys)
    {
		$str="";
		if($this->logValueEnable($str_table))
		{
			if(count($keys)>0)
			{
				$str.="---Keys\r\n";
				foreach($keys as $idx=>$val)
					$str.=$idx." : ".$val."\r\n";
			}
		
			$str.="---Fields\r\n";
			$v="";
			foreach($values as $idx=>$val)
			{
				if($val!="" && !array_key_exists($idx,$keys))
				{
					$str.=$idx." [old]: ";
					if(IsBinaryType(GetFieldType($idx)))
						$v="<binary value>";
					else
					{	
						$v=str_replace(array("\r\n","\n","\t")," ",$val);
						if(is_string($v) && strlen($v)>300)
							$v=substr($v,0,300);
					}
					$str.=$v."\r\n";
				}
			}
		}
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]=$str_table;
		$dal->$table->Value["action"]="delete";
		$dal->$table->Value["description"]=$str;
		$dal->$table->Add();
    }
    
    function LogAddEvent($message,$description="",$stable="")
    {
		global $dal;
		$table=$this->logTableName;
		$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
		$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
		$dal->$table->Value["user"]=$_SESSION["UserID"];
		$dal->$table->Value["table"]=$stable;
		$dal->$table->Value["action"]=$message;
		$dal->$table->Value["description"]=$description;
		$dal->$table->Add();
    }
    function LoginSuccessful()
    {
		if($this->attLogin>0 && $this->timeLogin>0)
		{
			global $dal;
			$table=$this->logTableName;
			$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
			$dal->$table->Value["action"]="access";
			$dal->$table->Delete();
		}
		
    }
    function LoginUnsuccessful()
    {
		if($this->attLogin>0 && $this->timeLogin>0)
		{
			global $dal;
			$table=$this->logTableName;
			$dal->$table->Value["datetime"]=date("y-m-d H:i:s");
			$dal->$table->Value["ip"]=$_SERVER["REMOTE_ADDR"];
			$dal->$table->Value["user"]="";
			$dal->$table->Value["table"]="";
			$dal->$table->Value["action"]="access";
			$dal->$table->Value["description"]="";
			$dal->$table->Add();
		}
    }
    
	function LoginAccess()
	{
		if($this->attLogin>0 && $this->timeLogin>0)
		{
			global $dal;
			$table=$this->logTableName;
			$rstmp=$dal->$table->Query("ip='".$_SERVER["REMOTE_ADDR"]."'","id asc");
			$i=0;
			$dt=now();
			while($data = db_fetch_array($rstmp))
			{
				if((strtotime("now")-strtotime($data["datetime"]))/60<=$this->timeLogin)
				{
					if($i==0)
						$dt=$data["datetime"];
					$i+=1;
				}
			}
			if($i>=$this->attLogin)
				return ceil($this->timeLogin-strtotime("now")/60+strtotime($dt)/60);
			else
				return "";
		}
		else
			return "";
	}
	function logValueEnable($table)
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
}

class AuditTrailFile
{
	var $logfile="audit.log";

    function LogLogin()
    {
				$fp=$this->CreateLogFile();
		$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9)."carsusers".chr(9)."login\r\n";
		fputs($fp,$str);
		fclose($fp);
    }
    function LogLoginFailed()
    {
				$fp=$this->CreateLogFile();
		$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9)."carsusers".chr(9)."login failed\r\n";
		fputs($fp,$str);
		fclose($fp);
    }
    function LogLogout()
    {
				$fp=$this->CreateLogFile();
		$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9)."carsusers".chr(9)."logout\r\n";
		fputs($fp,$str);
		fclose($fp);
    }
    function LogChPassword()
    {
				$fp=$this->CreateLogFile();
		$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9)."carsusers".chr(9)."Change password\r\n";
		fputs($fp,$str);
		fclose($fp);
    }
    function LogAdd($str_table,$values,$keys)
    {
		$key="";
		foreach($keys as $idx=>$val)
			$key.=$val.",";
		if($key)
			$key=substr($key,0,-1);

		$fp=$this->CreateLogFile();
		foreach($values as $idx=>$val)
		{
			$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9).$str_table.chr(9)."add".chr(9).
			$key.chr(9).$idx.chr(9)." ";
			$v="";
			if($this->logValueEnable($str_table))
			{
				if(IsBinaryType(GetFieldType($idx)))
					$v=chr(9)."<binary value>"."\r\n";
				else
				{
					$v=str_replace(array("\r\n","\n","\t")," ",$val);
					if(is_string($v) && strlen($v)>300)
						$v=substr($v,0,300);
					$v=chr(9).$v;
				}
				$str.=$v;
			}
		}
//		if($v!="" && !array_key_exists($idx,$keys))
		fputs($fp,$str."\r\n");
		fclose($fp);
    }
    function LogEdit($str_table,$newvalues,$oldvalues,$keys)
    {
		$key="";
		foreach($keys as $idx=>$val)
			$key.=$val.",";
		if($key)
			$key=substr($key,0,-1);

		$fp=$this->CreateLogFile();
		foreach($newvalues as $idx=>$val)
		{
			if($val!=$oldvalues[$idx] && !array_key_exists($idx,$keys))
			{
				$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9).$str_table.chr(9)."edit".chr(9).
				$key.chr(9).$idx;
				$v="";
				if($this->logValueEnable($str_table))
				{
					if(IsBinaryType(GetFieldType($idx)))
						$v=chr(9)."<binary value>";
					else
					{
						$v=str_replace(array("\r\n","\n","\t")," ",$oldvalues["$idx"]);
						if(is_string($v) && strlen($v)>300)
							$v=substr($v,0,300);
						$v=chr(9).$v;
					}
					$str.=$v;
					
					$v=" ";
					if(IsBinaryType(GetFieldType($idx)))
						$v=chr(9)."<binary value>";
					else
					{
						$v=str_replace(array("\r\n","\n","\t")," ",$val);
						if(is_string($v) && strlen($v)>300)
							$v=substr($v,0,300);
						$v=chr(9).$v;
					}
				}
				$str.=$v."\r\n";
				fputs($fp,$str);
			}
		}
		fclose($fp);
    }
    function LogDelete($str_table,$values,$keys)
    {
		$key="";
		foreach($keys as $idx=>$val)
			$key.=$val.",";
		if($key)
			$key=substr($key,0,-1);

		$fp=$this->CreateLogFile();
		foreach($values as $idx=>$val)
		{
			$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9).$str_table.chr(9)."delete".chr(9).
			$key.chr(9).$idx;
			$v="";
			if($this->logValueEnable($str_table))
			{
				if(IsBinaryType(GetFieldType($idx)))
					$v=chr(9)."<binary value>";
				else
				{
					$v=str_replace(array("\r\n","\n","\t")," ",$val);
					if(is_string($v) && strlen($v)>300)
						$v=substr($v,0,300);
					$v=chr(9).$v;
				}
			}
			$str.=$v."\r\n";
//			if($v!="" && !array_key_exists($idx,$keys))
			fputs($fp,$str);
		}
		fclose($fp);
    }
	function CreateLogFile()
	{
		$p=strrpos($this->logfile,".");
		$logfileName=substr($this->logfile,0,$p);
		$logfileExt=substr($this->logfile,$p+1);
		$tn=$logfileName."_".date("Ymd").".".$logfileExt;
		$fp=fopen($tn,"a");
		if(!filesize($tn))
		{
			$str="Date".chr(9)."Time".chr(9)."IP".chr(9)."User".chr(9)."Table".chr(9)."Action".chr(9)."Key field".chr(9)."Field".chr(9)."Old value".chr(9)."New value\r\n";
			fputs($fp,$str);
		}			
		return $fp;
	}
	function LogAddEvent($message,$description="",$table="")
    {
		$fp=$this->CreateLogFile();
		$str=date("Y-m-d").chr(9).date("H:i:s").chr(9).$_SERVER["REMOTE_ADDR"].chr(9).$_SESSION["UserID"].chr(9).$table.chr(9).$message.chr(9).$description."\r\n";
		fputs($fp,$str);
		fclose($fp);
    }
    
    function LoginAccess()
	{
		return "";
	}
	function LoginSuccessful()
    {
		return true;
    }
    function LoginUnsuccessful()
    {	
		return true;
	}
	function logValueEnable($table)
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
}
$audit=new AuditTrailTable();

?>