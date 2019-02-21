<?php
// json support
if(!function_exists('json_encode'))
{
	include("json.php");
	$GLOBALS['JSON_OBJECT'] = new Services_JSON();
               
    function json_encode($value){
    	return $GLOBALS['JSON_OBJECT']->encode($value);
    }
   
    function json_decode($value){
        return $GLOBALS['JSON_OBJECT']->decode($value);
	}
}
          
          
/**
 * That function  copies all elements from associative array to object, as object properties with same names
 * Usefull when you need to copy many properties
 *
 * @param link $obj
 * @param link $argsArr
 */
function RunnerApply (&$obj, &$argsArr)
{	
	//$i=0;
	foreach ($argsArr as $key=>$var)
	{
		//if (isset($obj->$key))		
			$obj->$key = &$argsArr[$key];		
		//$i++;
	}
}
/**
 *  Define constants of name page 
 *
 * @var constants
 */
define('PAGE_LIST',"list");
define('PAGE_ADD',"add");
define('PAGE_EDIT',"edit");
define('PAGE_VIEW',"view");
define('PAGE_REGISTER',"register");
define('PAGE_SEARCH',"search");
define('PAGE_REPORT',"report");
define('PAGE_CHART',"chart");
/**
 * Abstract base class for all pages. Contains main functionality
 *
 */
class RunnerPage
{
	/**
      * Id on page
      *
      * @var integer
      */
	var $id = 1;
	/**
      * Total js code for page
      *
      * @var string
      */
	var $totalCode = "";
	/**
      * If use calendar or not
      *
      * @var bool
      */
	var $calendar = false;
	/**
      * Type of page
      *
      * @var string
      */
	var $pageType = "";
	/**
      * Mode of page
      *
      * @var integer
      */
	var $mode = 0;
	/**
      * Original table name
      *
      * @var string
      */
	var $strOriginalTableName = "";
	/**
      * Short table name
      *
      * @var string
      */
	var $shortTableName = '';
	/**
      * Prefix for session variable
      *
      * @var integer
      */
	var $sessionPrefix = "";
	/**
      * Name of current table
      *
      * @var string
      */	
	var $tName = "";
	/**
      * Connect to database
      *
      * @var string
      */
	var $conn = "";
	/**
      * Array of order index in table
      *
      * @var array()
      */
	var $gOrderIndexes = array();
	/**
      * String of OrderBy for query
      *
      * @var string
      */
	var $gstrOrderBy = "";
	/**
      * Page size
      *
      * @var integer
      */
	var $gPageSize = 0;
	/**
      * Extence of class Xtempl
      *
      * @var object
      */
	var $xt = null;
	
	var $flyId = 1;
	/*
	 *	The list of including js files 
	 */	  
	var $includes_js = array();
	/*
	 *	The list of including js files 
	 */
	var $includes_jsreq = array();
	/*
	 *	The list of including css files
	 */
	var $includes_css = array();
	/*
	 *	Loacale tunes
	 */
	var $locale_info = array();
	/**
	  * Constructor, set initial params
	  *
	  * @param array $params
	  */
	function RunnerPage(&$params)
	{
		// copy properties to object
		RunnerApply($this, $params);	
		$this->flyId = $this->id+1;
	}
		
	/**
	 * Generates new id, same as flyId on front-end
	 *
	 * @return int
	 */
	function genId()
	{
		return ++$this->flyId;
	}
	
	/**
	  * Get page type
	  */
	function getPageType()
	{
		return $this->pageType;
	}
	/**
	  * Accumulation of js code for page
	  */
	function AddJSCode($jscode)
	{
		$this->totalCode .=$jscode;
	}
	/**
	  * Add js files for page
	  */	
	function AddJSFile($file,$req1="",$req2="",$req3="")
	{
		$this->includes_js[]=$file;
		if($req1!="")
			$this->includes_jsreq[$file]=array($req1);
		if($req2!="")
			$this->includes_jsreq[$file][]=$req2;
		if($req3!="")
			$this->includes_jsreq[$file][]=$req3;
	}
	/**
	  * Add css files for page
	  */	
	function AddCSSFile($file)
	{
		$this->includes_css[]=$file;
	}
	/**
	  * Load js and css files
	  */	
	function LoadJS_CSS()
	{
		$this->includes_js = array_unique($this->includes_js);
		$this->includes_css = array_unique($this->includes_css);
		$sl = "sl".$this->id;
		$out="var ".$sl." = new ScriptLoader('".$this->id."');\r\n";
		foreach($this->includes_css as $file)
			$out.=$sl.".loadCSS('".$file."');";
		foreach($this->includes_js as $file)
		{
			$out .= $sl.".addJS('".$file."'";
			if(array_key_exists($file,$this->includes_jsreq))
			{
				foreach($this->includes_jsreq[$file] as $req)
					$out.=",'".$req."'";
			}
			$out.=");\r\n";
		}
		$out.=$sl.".load();";
		return $out;
	}
	/**
	  * Set languge params for page
	  */	
	function setLangParams()
	{
		SetLangVars($this->shortTableName."_list");
	}
	/**
	  * Accumulate general code for page
	  *
	  * @return generalCode
	  */		
	function getTextVariables()
	{
		$generalCode="";
		
		if($this->pageType == PAGE_LIST || $this->pageType == PAGE_REPORT)
		{
			$generalCode.="\nwindow.TEXT_FIRST = '"."First"."';".
			"\nwindow.TEXT_PREVIOUS = '"."Previous"."';".
			"\nwindow.TEXT_NEXT = '"."Next"."';".
			"\nwindow.TEXT_LAST = '"."Last"."';";
		}	
		
		//for calendar
		if($this->pageType == PAGE_EDIT || $this->pageType == PAGE_ADD || $this->pageType == PAGE_SEARCH || $this->pageType == PAGE_REGISTER || GetTableData($this->tName, ".isUseCalendarForSearch", true))
		{
			$generalCode.="window.TEXT_MONTH_JAN='".jsreplace("January")."';\r\n";
			$generalCode.="window.TEXT_MONTH_FEB='".jsreplace("February")."';\r\n";
			$generalCode.="window.TEXT_MONTH_MAR='".jsreplace("March")."';\r\n";
			$generalCode.="window.TEXT_MONTH_APR='".jsreplace("April")."';\r\n";
			$generalCode.="window.TEXT_MONTH_MAY='".jsreplace("May")."';\r\n";
			$generalCode.="window.TEXT_MONTH_JUN='".jsreplace("June")."';\r\n";
			$generalCode.="window.TEXT_MONTH_JUL='".jsreplace("July")."';\r\n";
			$generalCode.="window.TEXT_MONTH_AUG='".jsreplace("August")."';\r\n";
			$generalCode.="window.TEXT_MONTH_SEP='".jsreplace("September")."';\r\n";
			$generalCode.="window.TEXT_MONTH_OCT='".jsreplace("October")."';\r\n";
			$generalCode.="window.TEXT_MONTH_NOV='".jsreplace("November")."';\r\n";
			$generalCode.="window.TEXT_MONTH_DEC='".jsreplace("December")."';\r\n";
			$generalCode.="window.TEXT_DAY_SU='".jsreplace("Su")."';\r\n";
			$generalCode.="window.TEXT_DAY_MO='".jsreplace("Mo")."';\r\n";
			$generalCode.="window.TEXT_DAY_TU='".jsreplace("Tu")."';\r\n";
			$generalCode.="window.TEXT_DAY_WE='".jsreplace("We")."';\r\n";
			$generalCode.="window.TEXT_DAY_TH='".jsreplace("Th")."';\r\n";
			$generalCode.="window.TEXT_DAY_FR='".jsreplace("Fr")."';\r\n";
			$generalCode.="window.TEXT_DAY_SA='".jsreplace("Sa")."';\r\n";
			$generalCode.="window.TEXT_TODAY='".jsreplace("today")."';\r\n";
		}
		
		
		if($this->pageType == PAGE_EDIT || $this->pageType == PAGE_ADD || $this->pageType == PAGE_SEARCH || $this->pageType == PAGE_REGISTER)
		{
			$generalCode.="\r\nwindow['tName".$this->id."'] = '".jsreplace($this->tName)."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_REQUIRED='".jsreplace("Required field")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_ZIPCODE='".jsreplace("Field should be a valid zipcode")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_EMAIL='".jsreplace("Field should be a valid email address")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_NUMBER='".jsreplace("Field should be a valid number")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_CURRENCY='".jsreplace("Field should be a valid currency")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_PHONE='".jsreplace("Field should be a valid phone number")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_PASSWORD1='".jsreplace("Field can not be 'password'")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_PASSWORD2='".jsreplace("Field should be at least 4 characters long")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_STATE='".jsreplace("Field should be a valid US state name")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_DATE='".jsreplace("Field should be a valid date")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_TIME='".jsreplace("Field should be a valid time in 24-hour format")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_CC='".jsreplace("Field should be a valid credit card number")."';\r\n";
			$generalCode.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";			
		}

		$generalCode.="\nwindow.TEXT_PLEASE_SELECT='".jsreplace("Please select")."';".
				"\nwindow.locale_dateformat = '".$this->locale_info["LOCALE_IDATE"]."';".
		"\nwindow.locale_datedelimiter = '".$this->locale_info["LOCALE_SDATE"]."';".
		"\nwindow.bLoading=false;\r\n";
		
		$generalCode.="\nwindow.TEXT_CTRL_CLICK = \""."CTRL + click for multiple sorting"."\";".
		"\nwindow.TEXT_SAVE='".jsreplace("Save")."';".
		"\nwindow.TEXT_CANCEL='".jsreplace("Cancel")."';".
		"\nwindow.TEXT_INLINE_ERROR='".jsreplace("Error occurred")."';".
		"\nwindow.TEXT_PREVIEW='".jsreplace("preview")."';".
		"\nwindow.TEXT_HIDE='".jsreplace("hide")."';".
		"\nwindow.TEXT_LOADING='".jsreplace("loading")."';";
				
		return $generalCode;	
	}
	/**
	  * Add general js or css files for pages
	  */
	function addCommonJs() 
	{
		$this->AddJSFile("runnerJS/Runner");
		$this->AddJSFile("runnerJS/Util", "runnerJS/Runner");
		$this->AddJSFile("runnerJS/IEHelper", "runnerJS/Runner");
		$this->AddJSFile("runnerJS/Event", "runnerJS/IEHelper");
		$this->AddJSFile("runnerJS/Validate","runnerJS/Event");
		$this->AddJSFile('runnerJS/ControlManager','runnerJS/Validate');		
		$this->AddJSFile("runnerJS/SearchForm", "runnerJS/ControlManager");
		$this->AddJSFile("runnerJS/SearchFormWithUI", "runnerJS/SearchForm");
		$this->AddJSFile("runnerJS/SearchController", "runnerJS/SearchFormWithUI");		
		$this->AddJSFile("runnerJS/Control", "runnerJS/ControlManager");
		$this->AddJSFile("runnerJS/TextAreaControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/TextFieldControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/TimeFieldControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/RteControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/FileControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/DateFieldControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/RadioControl", "runnerJS/Control");
		$this->AddJSFile("runnerJS/LookupWizard", "runnerJS/Control");
		$this->AddJSFile("runnerJS/DropDown", "runnerJS/LookupWizard");
		$this->AddJSFile("runnerJS/CheckBox", "runnerJS/LookupWizard");
		$this->AddJSFile("runnerJS/TextFieldLookup", "runnerJS/LookupWizard");
		$this->AddJSFile("runnerJS/EditBoxLookup", "runnerJS/TextFieldLookup");
		$this->AddJSFile("runnerJS/ListPageLookup", "runnerJS/TextFieldLookup");
		//$this->AddJSFile("runnerJS/RunnerAll");
			
		$this->AddJSCode("
			window.MODE_ADD = 0;
			window.MODE_EDIT = 1;
			window.MODE_SEARCH = 2;
			window.MODE_LIST = 3;
			window.MODE_PRINT = 4;
			window.MODE_VIEW = 5;
			window.MODE_INLINE_ADD = 6;
			window.MODE_INLINE_EDIT = 7;
			window.MODE_EXPORT = 8;
		");
	}
	/**
	  * Prepare js code
	  */
	function PrepareJs()
	{
		// set new flyId for js
		$this->AddJSCode("if(flyid<".($this->flyId + 1).") flyid=".($this->flyId + 1).";\r\n"); 		
		$js="window['postloadstep".($this->id ? "_".$this->id : "")."_worked'] = undefined;
		window.postloadstep".($this->id!=='' ? "_".$this->id : '')." = function(){".$this->getTextVariables().$this->totalCode."};\r\n ";
		return $js.=$this->LoadJS_CSS($this->id);
	}
	/**
	  * Grab all js code
	  */
	function grabAllJsCode()
	{
		$jscode = $this->totalCode;
		$this->totalCode = "";
		return $jscode;
	}
	/**
	  * Grab all js code
	  */
	function grabAllJsFiles()
	{
		$jsFiles = $this->includes_js;
		$this->includes_js = array();
		return $jsFiles;
	}
	/**
	  * Grab all js code
	  */
	function grabAllCssFiles()
	{
		$cssFiles = $this->includes_css;
		$this->includes_css = array();
		return $cssFiles;
	}
	/**
	  * Prepare code for event "onSubmit" on simple pages add and edit
	  *
	  * @return $onsubmit
	  */	
	function onSubmitForEditingPage($formname)
	{
		$onsubmit = "$('#message_block').html('');".
		"var valRes = checkValidSimplePage('".$formname."','".jsreplace($this->tName)."');";
		if(isShowDetailTable())
		{
			$onsubmit.= "if(valRes){";
			if($this->pageType==PAGE_ADD)
				$onsubmit.= "window.dpObj.prepareForSaveAllDetail();";
			else if($this->pageType==PAGE_EDIT)
				$onsubmit.= "window.dpObj.saveAllDetail();";
			$onsubmit.= "return false;}";
		}	
		$onsubmit.= " return valRes;";
		return $onsubmit;
	}
}

?>
