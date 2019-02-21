<?php
/**
 * Search panel builder for LIST_SIMPLE mode
 *
 */
class SearchPanelSimple extends SearchPanel {

	function SearchPanelSimple(&$params) {
		parent::SearchPanel($params);
	}
	
	function buildSearchPanel($xtVarName) 
	{
		
		parent::buildSearchPanel();
		
		$this->addPanelFiles();
		
		// create search panel
		$searchPanel = array();
		$searchPanel["method"] = "DisplaySearchPanel";		
		$searchPanel["object"] = &$this;
		
		$srchPanelAttrs = $this->searchClause->getSrchPanelAttrs();
		
		$params = array();
		$searchPanel["params"] = $params;
		$this->pageObj->xt->assignbyref($xtVarName, $searchPanel);
	}
	
	
	function addPanelFiles()
	{
		
		if ($this->isUseAjaxSuggest)
			$this->pageObj->AddJSFile("ajaxsuggest");
			
		$this->pageObj->AddJSFile("ui");
		$this->pageObj->AddJSFile("ui.core", "ui");
		$this->pageObj->AddJSFile('ui.resizable', 'ui.core');
		$this->pageObj->AddJSFile("onthefly");
		
		
		if (GetTableData($this->tName, ".isUseTimeForSearch", true))
		{		
			$this->pageObj->AddJSFile("jquery.utils","ui");
			$this->pageObj->AddJSFile("ui.dropslide","jquery.utils");
			$this->pageObj->AddJSFile("ui.timepickr","ui.dropslide");
			$this->pageObj->AddCSSFile("ui.dropslide");
		}
		
		
		if (GetTableData($this->tName, ".isUseCalendarForSearch", true))
			$this->pageObj->AddJSFile("calendar");
	}
	
	function searchAssign() {
		
		parent::searchAssign();
		
		$searchGlobalParams = $this->searchClause->getSearchGlobalParams();	
		$searchPanelAttrs = $this->searchClause->getSrchPanelAttrs();
		// show hide window	
		$this->pageObj->xt->assign("showHideSearchWin_attrs", 'align=ABSMIDDLE class="searchPanelButton" src="images/search/showWindowPin.gif" title="Floating window" alt="Floating window"  onclick="e=event; searchController'.$this->id.'.toggleSearchWin(e);"');
		$searchOpt_mess = ($searchPanelAttrs['srchOptShowStatus'] ? 'Hide search options' : 'Show search options');
		$this->pageObj->xt->assign("showHideSearchPanel_attrs", 'align=ABSMIDDLE class="searchPanelButton" src="images/search/'.($searchPanelAttrs['srchOptShowStatus'] ? 'hideOptions' : 'showOptions').'.gif" title="'.$searchOpt_mess.'" alt="'.$searchOpt_mess.'"  onclick="searchController'.$this->id.'.toggleSearchOptions();"');
		
		if($this->isUseAjaxSuggest)
			$searchforAttrs = "autocomplete=off ".$this->searchControlBuilder->nonCtrlSearchSuggestJS();
		else
		{
			$searchforAttrs = $this->searchControlBuilder->createNoSuggestJs();
		}
				
				
		$skruglAttrs = 'style="';
		$skruglAttrs .= $searchPanelAttrs['srchOptShowStatus'] ? '"' : 'display: none;"'; 
		$this->pageObj->xt->assignbyref("searchPanelBottomRound_attrs", $skruglAttrs); 
		
		$this->pageObj->xt->assign("searchForSize10", 'size=10');
		//size=".($this->searchClause->isUsedSrch() ? '8' : '20')." 
		
		!$this->searchClause->isUsedSrch() ? $searchforAttrs .= 'style="color: #C0C0C0;"' : '';		
		$searchforAttrs .= 'onfocus="if (this.value==\'search\'){ this.value = \'\'; searchController'.$this->id.'.smplUsed = true; $(this).css(\'color\', \'\');}"';
		$searchforAttrs.= " name=\"ctlSearchFor".$this->id."\" id=\"ctlSearchFor".$this->id."\"";
		
		$valSrchFor = $this->searchClause->isUsedSrch() ? $searchGlobalParams["simpleSrch"] : 'search';
		$searchforAttrs.= " value=\"".htmlspecialchars($valSrchFor)."\"";
		$this->pageObj->xt->assignbyref("searchfor_attrs", $searchforAttrs);
		
		$this->pageObj->xt->assign('searchPanelTopButtons', true);
	}
	
	/**
	 * Search panel on list template handler
	 *
	 * @param array $params
	 */
	function DisplaySearchPanel(&$params)
	{		
		
		
		$dispNoneStyle = 'style="display: none;"';
		$xt = new Xtempl();
		$xt->assign('id', $this->id);			
		// search panel radio button assign
		$searchRadio = $this->searchControlBuilder->getSearchRadio();
		$xt->assign_section("all_checkbox_label", $searchRadio['all_checkbox_label'][0], $searchRadio['all_checkbox_label'][1]);
		$xt->assign_section("any_checkbox_label", $searchRadio['any_checkbox_label'][0], $searchRadio['any_checkbox_label'][1]);
		$xt->assignbyref("all_checkbox",$searchRadio['all_checkbox']);
		$xt->assignbyref("any_checkbox",$searchRadio['any_checkbox']);
		
			
		$xt->assign("searchbutton_attrs", "onClick=\"javascript: searchController".$this->id.".submitSearch();\"");
		
		
		// show hide panel	
		$srchPanelAttrs = $this->searchClause->getSrchPanelAttrs(); 
		
		
		$showHideOpt_mess = $srchPanelAttrs['ctrlTypeComboStatus'] ? 'Hide options' : 'Show options';
		// show criteries div
		$xt->assign("showHideCtrls_attrs", 'onclick="searchController'.$this->id.'.toggleCtrlChooseMenu();"');
		// show search type opt
		$xt->assign("showHideCtrlsOpt_attrs", /*'title="'.$showHideOpt_mess.'"*/' onclick="searchController'.$this->id.'.toggleCtrlTypeCombo();"');
		// show hide search type opt message
		$xt->assign("showHideOpt_mess", $showHideOpt_mess);
		
		// control choose menu div		
		$xt->assign("controlChooseMenuDiv_attrs", 'onmouseout="setTimeout(\'searchController'.$this->id.'.hideCtrlChooseMenu();\', 50);" onmouseover="setTimeout(\'searchController'.$this->id.'.showCtrlChooseMenu();\', 50);"');
		// render panel open if it was opened, may be better to show open if there are any stuff in it
		$srchPanelAttrs = $this->searchClause->getSrchPanelAttrs();
		$srchPanelAttrs['srchOptShowStatus'] ? '' : $xt->assign("srchOpt_attrs", 'style="display: none;"');	
		
		$this->searchClause->getUsedCtrlsCount()>0 ? $xt->assign("srchCritTopCont_attrs", '') : $xt->assign("srchCritTopCont_attrs", 'style="display: none;"');
		$this->searchClause->getUsedCtrlsCount()>1 ? $xt->assign("srchCritBottomCont_attrs", '') : $xt->assign("srchCritBottomCont_attrs", 'style="display: none;"');
		$this->searchClause->getUsedCtrlsCount()>1 ? $xt->assign("bottomSearchButt_attrs", '') : $xt->assign("bottomSearchButt_attrs", 'style="display: none;"');
		
		
		
		
				
		// string with JS for register block in searchController
		$regBlocksJS = '';
		// code for preload dependent
		$preloadDependentJS = '';	
		// search suggest js code
		$searchSuggestJS = '';
		// array for assign
		$srchCtrlBlocksArr = array();
		
		$recId = $this->pageObj->genId();
		
		// build search controls for each field, first we need to build used controls, because cached must have last index	
		for($j=0;$j<count($this->srchFieldsArr);$j++)
		{
			$srchFields = $this->searchClause->getSearchCtrlParams($this->srchFieldsArr[$j]);
			$ctrlInd = 0;
			
			$isFieldNeedSecCtrl = $this->searchControlBuilder->isNeedSecondCtrl($this->srchFieldsArr[$j]);
			
			
			
			// build used ctrls
			for($i=0; $i<count($srchFields); $i++)
			{		
				// build used ctrl														
				$srchCtrlBlocksArr[] = $this->searchControlBuilder->buildSearchCtrlBlockArr($recId, $this->srchFieldsArr[$j], $ctrlInd, $srchFields[$i]['opt'], $srchFields[$i]['not'], false, $srchFields[$i]['value1'], $srchFields[$i]['value2']);
				// build used ctrls rows for window table
				$srchCtrlBlocksWinArr[] = $this->searchControlBuilder->buildSearchCtrlWinBlockArr($recId, $this->srchFieldsArr[$j]);
				// add suggest
				if ($this->isUseAjaxSuggest)
				{
					$searchSuggestJS .= $this->searchControlBuilder->createSearchSuggestJS($this->srchFieldsArr[$j], $recId);
				}
								

				if ($isFieldNeedSecCtrl) {			
					$ctrlsMap = "[".$ctrlInd.", ".($ctrlInd+1)."]";
					$ctrlInd+=2;
				}else{				
					$ctrlsMap = "[".$ctrlInd."]";
					$ctrlInd++;
				}
				$regBlocksJS .= "searchController".$this->id.".addRegCtrlsBlock('".jsreplace($this->srchFieldsArr[$j])."', ".$recId.", ".$ctrlsMap.");";
				// get content for preload and create JS code				
				$preloadDependentJS .= $this->searchControlBuilder->createPreloadJS($this->srchFieldsArr[$j], $srchFields[$i]['value1'], $recId);
				// increment ID
				$recId = $this->pageObj->genId();
				// make 0 for cached ctrls and build cache ctrls
				$ctrlInd = 0;
			}
			
			// add filter button
			$xt->assign("addSearchControl_".GoodFieldName($this->srchFieldsArr[$j])."_attrs", $this->searchControlBuilder->addSearchCtrlJSEvent($this->srchFieldsArr[$j]));
			// add cached ctrl													
			$srchCtrlBlocksArr[] = $this->searchControlBuilder->buildSearchCtrlBlockArr($recId, $this->srchFieldsArr[$j], $ctrlInd, '', false, true, '', '');
			// add cached ctrl rows for window table
			$srchCtrlBlocksWinArr[] = $this->searchControlBuilder->buildSearchCtrlWinBlockArr($recId, $this->srchFieldsArr[$j]);
			
			
			if ($this->isUseAjaxSuggest)
			{
				$searchSuggestJS .= $this->searchControlBuilder->createSearchSuggestJS($this->srchFieldsArr[$j], $recId);
			}
			
			if ($isFieldNeedSecCtrl) {
				$ctrlsMap = "[".$ctrlInd.", ".($ctrlInd+1)."]";	
				$ctrlInd+=2;
			}else{
				$ctrlsMap = "[".$ctrlInd."]";			
				$ctrlInd++;
			}
			$regBlocksJS .= "searchController".$this->id.".addRegCtrlsBlock('".jsreplace($this->srchFieldsArr[$j])."', ".$recId.", ".$ctrlsMap.");";
			$recId = $this->pageObj->genId();		
		}
		// assign blocks with ctrls
		$xt->assign_loopsection('searchCtrlBlock', $srchCtrlBlocksArr);	
		$xt->assign_loopsection('searchCtrlBlock_win', $srchCtrlBlocksWinArr);	
		
		AddScript2Postload($searchSuggestJS, $this->pageObj->id);
		
		AddScript2Postload($regBlocksJS, $this->pageObj->id);		
		AddScript2Postload($preloadDependentJS, $this->pageObj->id);
		
		// display templ
		$xt->display($this->pageObj->shortTableName."_search_panel.htm");
	}
	
}

?>
	