<?php
/**
 * Class for list page with mode simple
 *
 */
class ListPage_Simple extends ListPage 
{
	/**
	 * Constructor, set initial params
	 *
	 * @param array $params
	 */	
	function ListPage_Simple(&$params) 
	{
		// call parent constructor
		parent::ListPage ($params);	
	}
	/**
	 * Add common assign for simple mode on list page
	 */	
	function commonAssign() 
	{
		parent::commonAssign();
		
		// adds style displat none to hiderecord controls, edit selected, delete selected, export selected and print selected if found 0 recs
		$this->xt->assign("details_block", $this->permis [$this->tName] ['search'] && $this->rowsFound );
		$this->xt->assign("recordspp_block", $this->permis [$this->tName] ['search'] && $this->rowsFound );
		$this->xt->assign("recordspp_attrs", "onchange=\"javascript: document.location='" . $this->shortTableName . "_list.php?pagesize='+this.options[this.selectedIndex].value;\"" );
		$this->xt->assign("pages_block", $this->permis [$this->tName] ['search'] && $this->rowsFound );
		$this->xt->assign("shiftstyle_block", true);
		$this->xt->assign("security_block", true);
		$this->xt->assign("left_block", true);
		$this->xt->assign("toplinks_block", true);
		
		// print links and attrs
		$this->xt->assign("print_link", $this->permis[$this->tName]['export']);
		$this->xt->assign("printall_link", $this->permis[$this->tName]['export']);
		$this->xt->assign("printlink_attrs", 
						  "href='".$this->shortTableName."_print.php' 
						   onclick=\"window.open('".$this->shortTableName."_print.php','wPrint');return false;\"");
		
		// add style to button span to hide it if no recs found
		$this->xt->assign("printSelectedPar_attrs", $this->buttonShowHideStyle());
		$this->xt->assign("printalllink_attrs", 
						  "href='".$this->shortTableName."_print.php?all=1' 
						   onclick=\"window.open('".$this->shortTableName."_print.php?all=1','wPrint');return false;\"");
		
		//export link and attr
		$this->xt->assign("export_link", $this->permis[$this->tName]['export']);
		$this->xt->assign("exportlink_attrs", 
						  "href='".$this->shortTableName."_export.php' 
						   onclick=\"window.open('".$this->shortTableName."_export.php','wExport');return false;\"");
		
		//print selected link and attr
		$this->xt->assign("printselected_link", $this->permis[$this->tName]['export']);
		$this->xt->assign("printselectedlink_attrs", $this->buttonShowHideStyle().$this->getPrintExportLinkAttrs('print'));
		
		//export selected link and attr
		$this->xt->assign("exportselected_link", $this->permis[$this->tName]['export']);
		
		// add style to button span to hide it if no recs found
		$this->xt->assign("exportSelectedPar_attrs", $this->buttonShowHideStyle());
		$this->xt->assign("exportselectedlink_attrs", $this->buttonShowHideStyle().$this->getPrintExportLinkAttrs('export'));
		
		//add link and attr
		$this->xt->assign("add_link", $this->permis[$this->tName]['add']);
		$this->xt->assign("addlink_attrs", "href='".$this->shortTableName."#_add.php' onClick=\"window.location.href='".$this->shortTableName."_add.php'\"");
		
		//copy link
		$this->xt->assign("copy_column", $this->permis[$this->tName]['add']);
		
		//select all link and attr
		$this->selectAllLinkAttrs();	
		
		//checkbox column				
		$this->checkboxColumnAttrs();
	
		//edit selected link and attr	
		$this->editSelectedLinkAttrs();		
		
		// add style to button span to hide it if no recs found
		$this->xt->assign("editSelectedPar_attrs", $this->buttonShowHideStyle());
				
		//save all link, attr, span	
		$this->saveAllLinkAttrs();
		
		//cansel all link, attr, span	
		$this->cancelAllLinkAttrs();
		
		//edit column
		$this->xt->assign("edit_column", $this->permis[$this->tName]['edit']);
		$this->xt->assign("edit_headercolumn", $this->permis[$this->tName]['edit']);
		$this->xt->assign("edit_footercolumn", $this->permis[$this->tName]['edit']);
		
		//inline edit column	
		$this->xt->assign("inlineedit_column", $this->permis[$this->tName]['edit']);
		$this->xt->assign("inlineedit_headercolumn", $this->permis[$this->tName]['edit']);
		$this->xt->assign("inlineedit_footercolumn", $this->permis[$this->tName]['edit']);
		
		//view column	
		$this->xt->assign("view_column", $this->permis[$this->tName]['search']);
		
		$allDetailsTablesArr = GetDetailTablesArr($this->tName);			
		for($i = 0; $i < count($allDetailsTablesArr); $i ++) {	
			$permis =($this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['add'] || $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['search']);
			$this->xt->assign($allDetailsTablesArr[$i]['dShortTable']."_dtable_column", $permis);
		}
				
		//delete link and attr
		$this->xt->assign("delete_link", $this->permis[$this->tName]['delete']);
		
		// add style to button span to hide it if no recs found
		$this->xt->assign("deleteSelectedPar_attrs", $this->buttonShowHideStyle());
		$this->xt->assign("deletelink_attrs", $this->buttonShowHideStyle()."onclick=\"
				if(\$('input[@type=checkbox][@checked][@name^=selection]').length && confirm('"."Do you really want to delete these records?"."')) frmAdmin".$this->id.".submit(); 
					return false;\"");
			
		if ($this->isDispGrid)
			$this->xt->assign_section ( "grid_block", $this->getAdminFormHTML(), "</form>" );
			
		//$this->xt->assign('searchform', true);
		$this->xt->assign('menu_block', $this->isCreateMenu);
		
		$this->xt->assign("languages_block",true);
	}
	/**
	 * Get Admin form with hidden fields
	 *
	 */
	function getAdminFormHTML()
	{
		return '<form method="POST" action="'.$this->shortTableName.'_list.php" name="frmAdmin'.$this->id.'" id="frmAdmin'.$this->id.'">
				<input type="hidden" id="a'.$this->id.'" name="a" value="delete">'.
				($this->is508 == true ? '<a name="skipdata"></a>' : '');
	}
	/**
	 * Add common html code for simple mode on list page
	 */	
	function addCommonHtml() 
	{
		$this->body ["begin"] .= "<script type=\"text/javascript\" src=\"include/jquery.js\"></script>";
		$this->body ["begin"] .= "<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
				
		if ($this->isDisplayLoading)
		{
			$this->body["begin"] .= loadindicator();
			$this->xt->assign("bodyattrs", "onload=\"if(!window.stopload){document.getElementById('loading').className='load_hide';window.stopload=true;}\"" );
		}
		$this->AddJSFile('customlabels');
		
		//add parent common html code
		parent::addCommonHtml();
		if($this->permis[$this->tName]['search'])
			$this->body["begin"].= $this->getSeachFormHTML();
		
		//$this->body['end'] = "<script>".$this->PrepareJS()."</script>";
		// assign body end
		$this->body['end'] = array();
		$this->body['end']["method"] = "assignBodyEnd";		
		$this->body['end']["object"] = &$this;		
	}
	
	
	
	
	/**
	 * Add common javascript code for simple mode on list page
	 */	
	function addCommonJs()
	{
		//add parent common js code
		parent::addCommonJs();
		
		$this->addJSCode("if(!$('[@disptype=control1]').length && $('[@disptype=controltable1]').length)
				$('[@disptype=controltable1]').hide();");
		if ($this->isUseInlineAdd && $this->permis[$this->tName]['add'] && !$this->numRowsFromSQLFromSQL) 
			$this->addJSCode ( "$('[@name=maintable]').hide();" );			
	}
	
	function buildSearchPanel($xtVarName) 
	{
		$params = array();
		$params['pageObj'] = &$this;
		$params['srchFieldsArr'] = $this->advSearchFieldsArr;
		$this->searchPanel = new SearchPanelSimple($params);
		$this->searchPanel ->buildSearchPanel($xtVarName);
	}	
}


?>