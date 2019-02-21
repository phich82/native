<?php

class ListPage_Lookup extends ListPage_Embed
{
	/**
      * String where for query
      *
      * @var string
      */
	var $lookupWhere = "";
	/**
      * Field of category
      *
      * @var string
      */
	var $categoryField = "";
	/**
      * Field of link
      *
      * @var string
      */
	var $linkField = "";
	/**
      * Parent id
      *
      * @var integer
      */
	var $parId =0;
	/**
      * Field of lookup
      *
      * @var string
      */
	var $lookupField = "";
	/**
      * Control of lookup
      *
      * @var string
      */
	var $lookupControl = "";
	/**
      * Categoru of lookup
      *
      * @var string
      */
	var $lookupCategory = "";
	/**
      * Table of lookup
      *
      * @var string
      */
	var $lookupTable = "";
	/**
      * Params of lookup
      *
      * @var string
      */
	var $lookupParams = "";
	/**
      * Select field of lookup
      *
      * @var string
      */
	var $lookupSelectField = "";
	/**
      * Field customed
      *
      * @var string
      */
	var $customField = "";
	/**
      * Field displayed
      *
      * @var string
      */
	var $dispField = "";
	
	/**
      * Constructor, set initial params
      *
      * @param array $params
      */
    
     
	function ListPage_Lookup(&$params)
	{
		// copy properties to object
		RunnerApply($this, $params);
		// init params
		$this->initLookupParams();	
		// call parent constructor
		parent::ListPage_Embed($params);
		$this->isUseAjaxSuggest = false;	
	}
	
	function initLookupParams()
	{
		$this->parId = postvalue("parId");
		$this->firstTime = postvalue("firsttime");
		$this->mainField = postvalue("field");
		$this->lookupControl = postvalue("control");
		$this->lookupCategory = postvalue("category");
		$this->mainTable = postvalue("table");
		$this->lookupParams = "mode=lookup&id=".$this->id."&parId=".$this->parId."&field=".rawurlencode($this->mainField)
			."&control=".rawurlencode($this->lookupControl)."&category=".rawurlencode($this->lookupCategory)
			."&table=".rawurlencode($this->mainTable);
		$this->sessionPrefix = $this->tName."_lookup_".$this->mainTable.'_'.$this->mainField;	
	
		
		if(AppearOnListPage($this->dispField))
			$this->lookupSelectField=$this->dispField;

		if($this->categoryField)
		{
			if(!strlen(GetFullFieldName($this->categoryField)))
				$this->categoryField="";
		}
		
		if(!$this->categoryField)
			$this->lookupCategory="";
	}
// clear lookup session data, while loading at first time
	function clearLookupSessionData()
	{
		if($this->firstTime)
		{
			$sessLookUpUnset = array();
			foreach($_SESSION as $key=>$value)
				if(strpos($key, "_lookup_")!== false)
					$sessLookUpUnset[] = $key;
					
			foreach($sessLookUpUnset as $key)
				unset($_SESSION[$key]);			
		}
	}
	
	/**
	 * Add common html code for simple mode on list page
	 */	
	function addCommonHtml() 
	{
		//add parent common html code
		parent::addCommonHtml();
		
		if($this->permis[$this->tName]['search'])
			$this->body["begin"].= $this->getSeachFormHTML();
	}
	
	function addCommonJs()
	{
		parent::addCommonJs();
		//	this code must be executed after the inlineedit.js is loaded		
		if($this->lookupSelectField)
		{
			$select_onclick='var Cntrl = Runner.controls.ControlManager.getAt("'.$this->mainTable.'", '.$this->parId.', "'.$this->mainField.'");
			Cntrl.setValue($("#edit"+id+"_'.GoodFieldName($this->dispField).'").attr("val"),$("#edit"+id+"_'.GoodFieldName($this->linkField).'").attr("val"), true);
			RemoveFlyDiv('.$this->id.');';
			
			$afteredited_handler = 'window.inlineEditing'.$this->id.'.afterRecordEdited = function(id){
				var span=$("#edit"+id+"_'.GoodFieldName($this->lookupSelectField).'");
				
				if(!span.length)
					return;
				$(span).html("<a href=#>"+$(span).html()+"</a>"); 
				$("a:first",span).click(function(){'.$select_onclick.'});
			};';
			
			$this->addJSCode($afteredited_handler);			
		}
		
		if(strlen($this->lookupCategory))
		{
			$this->addJSCode('window.inlineEditing'.$this->id.'.lookupfield = \''.jsreplace($this->mainField).'\';'.
			'window.inlineEditing'.$this->id.'.lookuptable = \''.jsreplace($this->mainTable).'\';'.
			'window.inlineEditing'.$this->id.'.categoryvalue = \''.jsreplace($this->lookupCategory).'\';');
		}		
	}
	
	function addJSPagination(){
		$this->addJSCode("window.GotoPage".$this->id." = function(nPageNumber)
			{
				window.frames['flyframe".$this->id."'].location='".$this->shortTableName."_list.php?".$this->lookupParams."&goto='+nPageNumber;
			};");
	}
		
	function getSearchFormInputsHTML()
	{
		return '<input type="Hidden" name="mode" value="lookup">
				<input type="Hidden" name="parId" value="'.$this->parId.'">
				<input type="Hidden" name="field" value="'.htmlspecialchars($this->mainField).'">
				<input type="Hidden" name="control" value="'.htmlspecialchars($this->lookupControl).'">
				<input type="Hidden" name="category" value="'.htmlspecialchars($this->lookupCategory).'">
				<input type="Hidden" name="table" value="'.htmlspecialchars($this->mainTable).'">';
	}

	/**
	 * Order links attribute for order on list page
	 */	
	function orderLinksAttr()
	{
		parent::orderLinksAttr();
	}
	/**
	 * Set order links attribute for order on list page
	 *
	 *@param string - name field, which is ordering
	 *@param string - how is filed ordering, "a" - asc or "d" - desc, default is "a"
	 */
	function setLinksAttr($field,$sort="")
	{
		$href=$this->shortTableName."_list.php?orderby=".($sort!="" ?($sort=="a" ? "d" : "a"): "a").$field."&".$this->lookupParams;
		$orderlinkattrs="onclick=\"window.frames['flyframe".$this->id."'].location='".$href."';return false;\" href=\"".$href."\"";
		return $orderlinkattrs;
	}
	/**
	 * Add spans with the link and display field values to the row for 
	 *
	 *@param array - Array of prepare records for data
	 */
	function addSpansForLinks(&$record)
	{
		if($this->lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($this->linkField))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$this->id."_".GoodFieldName($this->linkField)."\" ";
				$span.="></span>";
			}
			if($this->dispField!=$this->linkField && !AppearOnListPage($this->dispField))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$this->id."_".GoodFieldName($this->dispField)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldName($this->lookupSelectField)."_value"].=$span;
		}
	}
	/**
	 * Add spans with the link and display field values to the row for not use inline
	 *
	 *@param array - Array of prepare records for data
	 *@param array - Array of prepare data for list
	 */
	function addSpansForLinksNotInline(&$record,$data)
	{
		if($this->lookupSelectField)
		{
			$spanlink="<span ";
			$spanlink.="id=\"edit".$this->recId."_".GoodFieldName($this->linkField)."\" ";
			$spanlink.="val=\"".htmlspecialchars($data[$this->linkField])."\" ";
			$spanlink.=">";
			$spandisp="<span ";
			$spandisp.="id=\"edit".$this->recId."_".GoodFieldName($this->dispField)."\" ";
			$spandisp.="val=\"".htmlspecialchars($data[GoodFieldName($this->dispField)])."\" ";
			$spandisp.=">";
			$spanselect="<span ";
			$spanselect.="id=\"edit".$this->recId."_".GoodFieldName($this->lookupSelectField)."\" ";
			$spanselect.=">";
			if($this->lookupSelectField==$this->linkField)
			{
				$record[GoodFieldName($this->lookupSelectField)."_value"]=$spanlink.$record[GoodFieldName($this->lookupSelectField)."_value"]."</span>";
				if($this->linkField!=$this->dispField)
					$record[GoodFieldName($this->lookupSelectField)."_value"].=$spandisp."</span>";
			}
			elseif($this->lookupSelectField==$this->dispField)
			{
				$record[GoodFieldName($this->lookupSelectField)."_value"]=$spandisp.$record[GoodFieldName($this->lookupSelectField)."_value"]."</span>";
				if($this->linkField!=$this->dispField)
					$record[GoodFieldName($this->lookupSelectField)."_value"].=$spanlink."</span>";
			}
			else
			{
				$record[GoodFieldName($this->lookupSelectField)."_value"]=$spanselect.$record[GoodFieldName($this->lookupSelectField)."_value"]."</span>";
				$record[GoodFieldName($this->lookupSelectField)."_value"].=$spanlink."</span>";
				if($this->linkField!=$this->dispField)
					$record[GoodFieldName($this->lookupSelectField)."_value"].=$spandisp."</span>";
			}
		}
	
	}
	
	function buildLookupWhereClause()
	{
		if(strlen($this->lookupCategory))
			$this->strWhereClause = whereAdd($this->strWhereClause,GetFullFieldName($this->categoryField)."=".make_db_value($this->categoryField,$this->lookupCategory));
		if(strlen($this->lookupWhere))
			$this->strWhereClause = whereAdd($this->strWhereClause,$this->lookupWhere);
	}
	
	function buildSQL()
	{
		$this->buildLookupWhereClause();
		parent::buildSQL();
	}
	
	function buildSearchPanel() 
	{
		$params = array();
		$params['pageObj'] = &$this;
		$params['srchFieldsArr'] = $this->advSearchFieldsArr;
		$this->searchPanel = new SearchPanelLookup($params);
		$this->searchPanel->buildSearchPanel();
		
	}
	
	function displayAfterLoadTempl() 
	{
		
		$lookupSearchControls = $this->xt->fetch_loaded('searchform_text').$this->xt->fetch_loaded('searchform_search').$this->xt->fetch_loaded('searchform_showall');
			
		$this->xt->assign("lookupSearchControls", $lookupSearchControls);
		parent::displayAfterLoadTempl();
	}
		
	
	function prepareForBuildPage()
	{	
		//Sorting fields
		$this->buildOrderParams();
		
		// delete record
		$this->deleteRecords();
		
		// build search panel
		$this->buildSearchPanel();
		
		// build sql query
		$this->buildSQL();
		
		// build pagination block
		$this->buildPagination();
		
		// seek page must be executed after build pagination
		$this->seekPageInRecSet($this->querySQL);
		
		// add common js code
		$this->addCommonJs();
		
		// checks if need to display grid
		$this->isDispGrid();
		
		// fill grid data
		$this->fillGridData();
		
		// add common html code
		$this->addCommonHtml();
		
		// Set common assign
		$this->commonAssign();
	}
	
		// stroit checkbox, esli eto vozmogno
	function fillCheckAttr(&$record,$data,$keyblock)
	{
		$checkbox_attrs="name=\"selection[]\" value=\"".htmlspecialchars(@$data[$this->linkField])."\" id=\"check".$this->recId."\"";
		$record["checkbox"]=array("begin"=>"<input type='checkbox' ".$checkbox_attrs.">", "data"=>array());
	}
	
	function addJSCodeAfterRecordEdited()
	{
		if($this->lookupSelectField)
				$this->addJSCode("inlineEditing".$this->id.".afterRecordEdited(".$this->recId.");");
	}
		
	function addSpansForGridCells(&$record, $data){
		parent::addSpansForGridCells($record, $data);
		
		if($this->isUseInlineEdit){
			//	add spans with the link and display field values to the row
			$this->addSpansForLinks($record);
		} else {
			//	add spans with the link and display field values to the row
			$this->addSpansForLinksNotInline($record, $data);
		}
	}
	
	function proccessRecordValue(&$data, &$keylink, $listFieldInfo)
	{
		if(NeedEncode($listFieldInfo['fName'], $this->tName)&& $this->customField == $listFieldInfo['fName'])
		{
			$value = ProcessLargeText(GetData($data, GoodFieldName($this->dispField), $listFieldInfo['viewFormat']), "field=".rawurlencode($listFieldInfo['fName']).$keylink, "", MODE_LIST);				
		}
		else 
		{
			$value = parent::proccessRecordValue($data, $keylink, $listFieldInfo);
		}
		return $value;
	}
	
}
?>