<?php
class oBlocking
{
	var $blockTableName="project39_blocking";
	var $ConfirmTime=10;
	var $UnblockTime=20;
	var $ConfirmAdmin="Administrator: %s aborted your edit session";
	var $ConfirmUser="Your edit session timed out";
	var $BlockAdmin="Record is edited by %s during %s min";
	var $BlockUser="Record is edited by another user";
	function BlockRecord($strtable,$keys)
	{
		global $dal;
		$skeys="";
		foreach($keys as $ind=>$val)
			$skeys.=rawurlencode($val)."&";
		if($skeys!="")
			$skeys=substr($skeys,0,-1);
		$table=$this->blockTableName;
		$sdate=date("y-m-d H:i:s");
		$dal->$table->Value["startdatetime"]=$sdate;
		$dal->$table->Value["confirmdatetime"]=$sdate;
		$dal->$table->Value["sessionid"]=session_id();
		$dal->$table->Value["tablename"]=$strtable;
		$dal->$table->Value["keys"]=$skeys;
		$dal->$table->Value["userid"]=$_SESSION["UserID"];
		$dal->$table->Value["action"]=1;
		$dal->$table->Add();
		
		$arr = array();
		
		$rstmp=$dal->$table->Query(AddFieldWrappers("tablename")."='".$strtable."' and ".AddFieldWrappers("keys")."='".$skeys."' and action=1","id asc");

		while($data = db_fetch_array($rstmp))
		{
			if(strtotime("now")-strtotime($data["confirmdatetime"])>$this->UnblockTime)
			{
				$arr[]=$data["id"];
			}
			else
			{
				foreach($arr as $ind=>$val)
				{
					$dal->$table->Value["id"]=$val;
					$dal->$table->Delete();
				}
				if($data["sessionid"]==session_id())
					return "false";
				else
				{
					$dal->$table->Value["sessionid"]=session_id();
					$dal->$table->Value["action"]=1;
					$dal->$table->Value["tablename"]=$strtable;
					$dal->$table->Value["keys"]=$skeys;
					$dal->$table->Delete();
					return "true";
				}
			}	
		}
	}

	function UnblockRecord($strtable,$keys,$sid)
	{
		global $dal;
		if($sid=="")
			$sid=session_id();
		$skeys="";
		foreach($keys as $ind=>$val)
			$skeys.=rawurlencode($val)."&";
		if($skeys!="")
			$skeys=substr($skeys,0,-1);
		$table=$this->blockTableName;
		$dal->$table->Value["tablename"]=$strtable;
		$dal->$table->Value["keys"]=$skeys;
		$dal->$table->Value["sessionid"]=$sid;
		$dal->$table->Value["action"]=1;
		$dal->$table->Delete();
	}
	function ConfirmBlock($strtable,$keys)
	{
		global $dal;
		$skeys="";
		foreach($keys as $ind=>$val)
			$skeys.=rawurlencode($val)."&";
		if($skeys!="")
			$skeys=substr($skeys,0,-1);
				
		$table=$this->blockTableName;
		$sdate=date("y-m-d H:i:s");
		$dal->$table->Value["startdatetime"]=$sdate;
		$dal->$table->Value["confirmdatetime"]=$sdate;
		$dal->$table->Value["sessionid"]=session_id();
		$dal->$table->Value["tablename"]=$strtable;
		$dal->$table->Value["keys"]=$skeys;
		$dal->$table->Value["userid"]=$_SESSION["UserID"];
		$dal->$table->Value["action"]=1;
		$dal->$table->Add();
		
		$rstmp=$dal->$table->Query(AddFieldWrappers("tablename")."='".$strtable."' and ".AddFieldWrappers("keys")."='".$skeys."' and action=1","id asc");
		
		$myfound=0;
		$otherfound=0;
		$tempfound=0;
		$newid=0;
		$oldid=0;
		$newdate="";
		$olddate="";
		while($data = db_fetch_array($rstmp))
		{
			if($data["sessionid"]==session_id())
			{
				$oldid=$newid;
				$newid=$data["id"];
				$newdate=$data["confirmdatetime"];
				$olddate=$newdate;
				$myfound++;
				$otherfound=$tempfound;
				$tempfound=0;
				continue;
			}
			$tempfound++;
		}
		if($myfound>1 && !$otherfound)
		{
			$dal->$table->Param["id"]=$oldid;
			$dal->$table->Value["confirmdatetime"]=date("y-m-d H:i:s");
			$dal->$table->Update();
			
			$dal->$table->Value["id"]=$newid;
			$dal->$table->Delete();
		}
		elseif($myfound>1 && $otherfound)
		{
			if(strtotime("now")-strtotime($confirmdataold)>$this->UnblockTime-5)
			{
				$this->UnblockRecord($strtable,$keys,session_id());
				printf($this->ConfirmUser);
			}
			else
			{
				$dal->$table->Param["id"]=$oldid;
				$dal->$table->Value["confirmdatetime"]=date("y-m-d H:i:s");
				$dal->$table->Update();
				
				$dal->$table->Value["id"]=$newid;
				$dal->$table->Delete();
			}
		}
		else
		{
			
			$this->UnblockRecord($strtable,$keys,session_id());
			
			$rstmp=$dal->$table->Query(AddFieldWrappers("tablename")."='".$strtable."' and ".AddFieldWrappers("keys")."='".$skeys."' and ".AddFieldWrappers("sessionid")."!='".session_id()."' and action=2","id asc");
			if($data = db_fetch_array($rstmp))
				printf($this->ConfirmAdmin,$data["userid"]);
			else
				printf($this->ConfirmUser);
			$dal->$table->Value["tablename"]=$strtable;
			$dal->$table->Value["keys"]=$skeys;
			$dal->$table->Value["action"]=2;
			$dal->$table->Delete();
		}
	}
	function GetBlockInfo($strtable,$keys,$links)
	{
		global $dal;
		$page=GetTableURL($strtable)."_edit.php";
		$skeys="";
		$lkeys="";
		$i=1;
		foreach($keys as $ind=>$val)
		{
			$skeys.=rawurlencode($val)."&";
			$lkeys.=rawurlencode("editid".$i."=".postvalue("editid1"))."&";
			$i++;
		}
		if($skeys!="")
			$skeys=substr($skeys,0,-1);
		if($lkeys!="")
			$lkeys=substr($lkeys,0,-1);
		$table=$this->blockTableName;
		$rstmp=$dal->$table->Query(AddFieldWrappers("tablename")."='".$strtable."' and ".AddFieldWrappers("keys")."='".$skeys."' and ".AddFieldWrappers("sessionid")."!='".session_id()."' and action=1","id asc");
		if($data = db_fetch_array($rstmp))
		{
			$str=sprintf($this->BlockAdmin,$data["userid"],round((strtotime("now")-strtotime($data["startdatetime"]))/60,2));
			if($links)
			{
				$str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$str.="<a class=admin_links href='#' onclick=\"UnblockAdmin('".$page."','".$strtable."','".$lkeys."','".$data["sessionid"]."','no');return false;\">Unblock record</a>";
				$str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=admin_links href='#' onclick=\"UnblockAdmin('".$page."','".$strtable."','".$lkeys."','".$data["sessionid"]."','yes');return false;\">Edit record</a>";
			}
			return $str;
		}
		else
			return "";
	}
	function UnblockAdmin($strtable,$keys)
	{
		global $dal;
		$skeys="";
		$lkeys="";
		$i=1;
		foreach($keys as $ind=>$val)
		{
			$skeys.=rawurlencode($val)."&";
			$lkeys.=rawurlencode("editid".$i."=".postvalue("editid1"))."&";
			$i++;
		}
		if($skeys!="")
			$skeys=substr($skeys,0,-1);
		if($lkeys!="")
			$lkeys=substr($lkeys,0,-1);
			
		$table=$this->blockTableName;
		
		$rstmp=CustomQuery("delete from ".$table." where ".AddFieldWrappers("startdatetime")."<'".date("y-m-d H:i:s",strtotime("now")-2*24*60*60)."' and action=2");
		
		$sdate=date("y-m-d H:i:s");
		$dal->$table->Value["startdatetime"]=$sdate;
		$dal->$table->Value["confirmdatetime"]=$sdate;
		$dal->$table->Value["sessionid"]=session_id();
		$dal->$table->Value["tablename"]=$strtable;
		$dal->$table->Value["keys"]=$skeys;
		$dal->$table->Value["userid"]=$_SESSION["UserID"];
		$dal->$table->Value["action"]=2;
		$dal->$table->Add();
		
	}
}

$block=new oBlocking();

?>