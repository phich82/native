<?php
/**
 * Base class for all search control builders
 *
 */
class SearchPanel {
	/**
	 * strTableName of searchPanel's table
	 *
	 * @var string
	 */		
	var $tName = '';
	var $dispNoneStyle = 'style="display: none;"';
	/**
	 * Object of page for output. Used for call xt methods for current page
	 *
	 * @var object
	 */
	var $pageObj = null;
	/**
	 * Object of searchClause class.
	 *
	 * @var object
	 */
	var $searchClause = null;
	/**
	 * Object of PanelSearchControl class.
	 *
	 * @var object
	 */
	var $searchControlBuilder = null;
	/**
	 * Panel id
	 *
	 * @var int
	 */
	var $id = 1;	
	/**
	 * Array of panel state parametres, such as open|close menu etc.
	 *
	 * @var array
	 */
	var $panelState = array();
	/**
	 * Arr of fields for search
	 *
	 * @var array
	 */
	var $srchFieldsArr = array();
	/**
	 * Indicator use suggest or not
	 *
	 * @var bool
	 */
	var $isUseAjaxSuggest = false;
	/**
	 * Permissions for search
	 *
	 * @var bool
	 */
	var $searchPerm = false;
	/**
	 * Constructor, accepts array of parametres, which will be copied to object properties by link
	 *
	 * @param array $params
	 * @return SearchPanel
	 */
	function SearchPanel(&$params)
	{
		// copy properties to object
		RunnerApply($this, $params);
		
		$this->searchClause = &$this->pageObj->searchClause;	
		
		$this->id = $this->pageObj->id;
		$this->tName = $this->pageObj->tName;
		$this->panelState = $this->searchClause->getSrchPanelAttrs();	
		$this->isUseAjaxSuggest = GetTableData($this->tName, ".isUseAjaxSuggest", true);
		
		
		
		$this->searchControlBuilder = new PanelSearchControl($this->id, $this->tName, $this->searchClause, $this->pageObj);	
		
				
		// get search permissions if not passed to constructor
		if (!isset($params['searchPerm'])){
			$this->searchPerm = $this->getSearchPerm();
		}
		// get search fields if not passed to contructor
		if (!isset($params['srchFieldsArr'])){
			$this->srchFieldsArr = GetTableData($this->tName,".advSearchFieldsArr",array());	
		}
		
	}	
	
	function getSearchPerm($tName = "")
	{
		$tName = $tName ? $tName : $this->tName;
		
		$isGroupSec = GetTableData($tName,".isGroupSecurity",false);
		if (!$isGroupSec)
		{
			return true;
		}
		else
		{
			
			$strPerm = GetUserPermissions($tName);
			return (strpos($strPerm, "S") !== false);
		}		
	}
	
	/**
	 * Main method, call to build search panel
	 *
	 */
	function buildSearchPanel() 
	{
		$srchPanelAttrs = $this->searchClause->getSrchPanelAttrs();
				
		$fNamesJsArr = $this->searchControlBuilder->fNamesJSArr($this->srchFieldsArr);

		$this->pageObj->addJsCode("
			window.searchController".$this->id." = new Runner.search.SearchController({
				id: ".$this->id.",
				tName: '".jsreplace($this->tName)."',
				fNamesArr:[".$fNamesJsArr."],
				shortTName: '".$this->pageObj->shortTableName."',
				srchOptShowStatus: ".($srchPanelAttrs['srchOptShowStatus'] ? 'true' : 'false').",
				ctrlTypeComboStatus: ".($srchPanelAttrs['ctrlTypeComboStatus'] ? 'true' : 'false').",
				usedSrch: ".($this->searchClause->isUsedSrch() ? 'true' : 'false')."
			});
		");
		
		$this->searchAssign();
	}
	
	
		
	function searchAssign() 
	{
		
		$this->pageObj->xt->assign('searchform', true);
		$this->pageObj->xt->assign("asearch_link", $this->searchPerm);
		$this->pageObj->xt->assign("asearchlink_attrs", "href=\"".$this->pageObj->shortTableName."_search.php\" onclick=\"window.location.href='".$this->pageObj->shortTableName."_search.php';return false;\"");
		$this->pageObj->xt->assign("search_records_block", $this->searchPerm);
		
		if(isEnableSection508())
			$this->pageObj->xt->assign_section("search_records_block", "<a name=\"skipsearch\"></a>", "");
		
		$this->pageObj->xt->assign("search_records_block", $this->searchPerm);
		
		
		$this->pageObj->xt->assign("searchform_text", true);
		$this->pageObj->xt->assign("searchform_search", true);
		
		$this->pageObj->xt->assign("searchform_showall", $this->searchClause->isUsedSrch());	
		
		$srchButtStyles = 'style="width: 18px; height: 18px; background-image: url(images/search/search.gif);"';
		$this->pageObj->xt->assign("searchbutton_attrs", $srchButtStyles."onClick=\"javascript: searchController".$this->id.".submitSearch();\"");
		$this->pageObj->xt->assign("showallbutton_attrs", "onClick=\"searchController".$this->id.".showAllSubmit()\"");
	}
	
	
	
	
	
	
	
	
}

?>
