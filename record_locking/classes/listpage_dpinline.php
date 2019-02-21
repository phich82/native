<?php

class ListPage_DPInline extends ListPage_Embed
{
	/**
	 * DP params
	 *
	 * @var string
	 */
	var $dpParams = "";
	/**
	 * Array of details preview master key
	 *
	 * @var integer
	 */
	var $dpMasterKey = array ();
	/**
	 * Short name of master table
	 *
	 * @var string
	 */
	var $masterShortTable = "";
	/**
	 * Master's form name
	 *
	 * @var string
	 */	
	var $masterFormName = "";
	/**
	 * Master's id use only for dpInline on list page
	 * (don't confuse with dpInline on add edit pages)
	 * @var string
	 */
	var $masterId = "";
	/**
	 * Constructor, set initial params
	 *
	 * @param array $params
	 */
	function ListPage_DPInline(&$params)
	{
		// copy properties to object
		//RunnerApply($this, $params);
		// call parent constructor
		parent::ListPage_Embed($params);
		$this->initDPInlineParams();
		$this->searchClause->clearSearch();
	}
	/**
      * Assigne Import Links or not
      *
	  * @return boolean
      */
	function assignImportLinks() 
	{
		return true;
	}
	/**
      * Display master table info or not
      *
	  * @return boolean
      */
	function displayMasterTableInfo() 
	{
		return true;
	}
	/**
      * Process master key value
      * Set master key for create DPInline params
	  */
	function processMasterKeyValue() 
	{
		parent::processMasterKeyValue();
		for($i=1;$i<=count($this->masterKeysReq);$i++)
			$this->dpMasterKey[] = $this->masterKeysReq[$i];
	}
	/**
      * Initialization DPInline params
      * 
      */
	function initDPInlineParams()
	{
		$strkey="";
		for($i=0;$i<count($this->dpMasterKey);$i++)
			$strkey.="&masterkey".($i+1)."=".rawurlencode($this->dpMasterKey[$i]);
		$this->dpParams = "mode=dpinline&id=".$this->id."&mastertable=".rawurlencode($this->masterTable).$strkey.($this->masterId ? "&masterid=".$this->masterId : "");
	}
	
	function getStrMasterKey()
	{
		$strkey = "[";
		for($i=0;$i<count($this->dpMasterKey);$i++)
			$strkey .= "'".jsreplace($this->dpMasterKey[$i])."',";
		$strkey =  substr($strkey, 0, -1);	
		return $strkey."]";	
	}
	
	/**
	 * Set order links attribute for order on list page
	 *
	 *@param string - name field, which is ordering
	 *@param string - how is filed ordering, "a" - asc or "d" - desc, default is "a"
	 */
	function setLinksAttr($field,$sort="")
	{
		$href=$this->shortTableName."_list.php?orderby=".($sort!="" ? ($sort=="a" ? "d" : "a") : "a").$field."&".$this->dpParams;
		$orderlinkattrs="onclick=\"window.frames['flyframe".$this->id."'].location='".$href."';return false;\" href=\"".$href."\"";
		return $orderlinkattrs;
	}
	/**
	 * Add common js files and code
	 */
	function addCommonJs() 
	{
		parent::addCommonJs();
		$strKey = $this->getStrMasterKey();
		if($this->masterPageType==PAGE_EDIT || $this->masterPageType==PAGE_ADD)
		{
			if($this->useDetailsPreview)
			{
				$this->addJSCode("dpInline".$this->id.".createPreviewIframe({'mode':'dpinline_edit_add'});");
				if($this->masterPageType==PAGE_EDIT)
					$this->addJSCode("dpInline".$this->id.".createPreviewForm({'id':".$this->id.",'dTable':'".$this->shortTableName."','mTable':'".jsreplace($this->masterTable)."','mKeys':".$strKey."});");
			}		
			else{
					$this->addJSCode("window.dpInline".$this->id." = new detailsPreviewInline(
									{'pageId':".$this->id.",
									 'mode':'dpinline_edit_add',
									 'ext':'php',
									 'mTable':'".jsreplace($this->masterTable)."'}); 
									 dpInline".$this->id.".createPreviewIframe();");
					if($this->masterPageType==PAGE_EDIT)
						$this->addJSCode("dpInline".$this->id.".createPreviewForm({'dTable':'".$this->shortTableName."','mKeys':".$strKey."});");
				}
			$this->addJSCode("var opts =  window.dpObj.Opts;
							  len = opts.dInlineObjs.length;
							  opts.dCaptions[len] = '".jsreplace($this->strCaption)."';");		
			if($this->masterPageType==PAGE_ADD && $this->isUseInlineAdd)
				$this->addJSCode("var obj = window.inlineEditing".$this->id.";
								  opts.dInlineObjs[len] = obj; obj.inlineAdd(flyid++,true);");
			elseif($this->masterPageType==PAGE_EDIT && $this->isUseInlineJs)
				$this->addJSCode("opts.dInlineObjs[len] = window.inlineEditing".$this->id.";");
		}
	}		
	/**
      * Add javascript pagination code for current mode
      *
	  */
	function addJSPagination()
	{
		$this->addJSCode("window.GotoPage".$this->id." = function (nPageNumber)
			{
				window.frames['flyframe".$this->id."'].location='".$this->shortTableName."_list.php?".$this->dpParams."&goto='+nPageNumber;
			};");
	}
	/**
	 * show inline add link
	 * Add inline add attributes
	 */
	function inlineAddLinksAttr()
	{
		//inline add link and attr
		$this->xt->assign("inlineadd_link", $this->permis[$this->tName]['add']);
		$this->xt->assign("inlineaddlink_attrs", "href='".$this->shortTableName."_add.php' onclick=\"return inlineEditing".$this->id.".inlineAdd(flyid++);\"");
	}
	
	/**
      * Add common assign for current mode
      *
	  */
	function commonAssign()
	{
		parent::commonAssign();
			
		//inline edit column	
			$this->xt->assign("inlineedit_column",$this->permis[$this->tName]['edit']);
			$this->xt->assign("left_block", false);
		//select all link and attr	
			if($this->masterPageType==PAGE_ADD)
			{
				$this->xt->assign("selectall_link",false);
				$this->xt->assign("checkbox_column",false);
				$this->xt->assign("checkbox_header",false);
				$this->xt->assign("editselected_link",false);
				$this->xt->assign("delete_link",false);
				$this->xt->assign("saveall_link",false);
			}
			else{	
					//selectall link attrs
					$this->selectAllLinkAttrs();		
					
					//checkbox column	
					$this->checkboxColumnAttrs();
						
					//edit selected link and attr	
					$this->editSelectedLinkAttrs();	
					
					//save all link, attr, span	
					$this->saveAllLinkAttrs();
					
					//delete link and attr	
					$this->xt->assign("delete_link",$this->permis[$this->tName]['delete']);
					$href = $this->shortTableName."_list.php?".$this->dpParams;
					$this->xt->assign("deletelink_attrs","onclick=\"dpInline".($this->masterId ? $this->masterId : $this->id).".submitPreviewForm(".($this->masterId ? $this->id : "").")\"");	
				}
			
			//cancel all link, attr, span	
			$this->cancelAllLinkAttrs();
					
			$allDetailsTablesArr = GetDetailTablesArr($this->tName);
			for($i=0;$i<count($allDetailsTablesArr);$i++) 
			{
				$permis = ($this->isGroupSecurity && $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['add'] && $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['search'])||(!$this->isGroupSecurity);	
				$this->xt->assign($allDetailsTablesArr[$i]['dShortTable']."_dtable_column", $permis);
			}
	}
	/**
      * Final build page
      *
	  */
	function prepareForBuildPage() 
	{	
		//orderlinkattrs for fields
		$this->orderLinksAttr();
		
		//Sorting fields
		$this->buildOrderParams();
		
		// delete record
		$this->deleteRecords();
		
		// build sql query
		$this->buildSQL();
		
		// build pagination block
		$this->buildPagination();
		
		// seek page must be executed after build pagination
		$this->seekPageInRecSet($this->querySQL);
		
		// checks if need to display grid
		$this->isDispGrid();
		
		// fill grid data
		$this->fillGridData();
		
		// add common js code
		$this->addCommonJs();
		
		// add common html code
		$this->addCommonHtml();
		
		// Set common assign
		$this->commonAssign();
	}
}
?>
