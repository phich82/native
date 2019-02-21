<?php

class ListPage_Embed extends ListPage
{
	/**
	 * Use this mode first time or not
	 *
	 * @var integer
	 */
	var $firstTime = 0;
	/**
	 * Which type of master page was called detail table
	 *
	 * @var string
	 */
	var $masterPageType = "";
	/**
	 * Constructor, set initial params
	 *
	 * @param array $params
	 */
	function ListPage_Embed(&$params)
	{
		// copy properties to object
		//RunnerApply($this, $params);
		// call parent constructor
		parent::ListPage($params);
		$this->pageSize = 20;
	}
	/**
	 * Add common html code for curent mode
	 *
	 */
	function addCommonHtml()
	{
		parent::addCommonHtml();
		$this->xt->assign("footer","");
	}
	
	/**
	 * Add common javascript code
	 *
	 */
	function addCommonJs() 
	{
		parent::addCommonJs();
		
		if ($this->isUseInlineAdd && $this->permis[$this->tName]['add'] && ! $this->numRowsFromSQLFromSQL) 			
			$this->addJSCode ( "$('[@name=maintable]',$('#fly" . $this->id . "')).hide();" );		
	}
	
	/**
	 * Add common assign for simple mode on list page
	 */	
	function commonAssign() 
	{
		parent::commonAssign();	
		if ($this->isDispGrid)
			$this->xt->assign_section ("grid_block", '', '');
	}	
	
	/**
	 * Get search form target html for lookup or dp-inline
	 *
	 * @return string
	 */
	function getSearchFormTargetHTML()
	{
		return  'target="flyframe'.$this->id.'"';
	}	
		
	/**
      * Show page method
      *
      */
	function showPage()
	{
		$jscode = $this->PrepareJs();
		if($this->firstTime)
		{
			if($this->masterPageType!=PAGE_EDIT && $this->masterPageType!=PAGE_ADD)
			{
				echo str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$jscode);
				echo "\n";
			}	
		}
		else
		{		
			echo "<textarea id=data>decli";
			echo htmlspecialchars($jscode);
			echo "</textarea>";
		}
		if($this->masterPageType==PAGE_EDIT || $this->masterPageType==PAGE_ADD)
		{
			echo'<br><div style="padding-left:10px;"><a name="dt'.$this->id.'" class="dt">Detail Table: '.$this->strCaption.'</a></div>
				<div id="detailPreview'.$this->id.'">';
		}
		$this->xt->load_template($this->templatefile);
		$this->displayAfterLoadTempl();		
		if($this->firstTime && ($this->masterPageType==PAGE_EDIT || $this->masterPageType==PAGE_ADD))
			echo'<s'.'cript>'.$jscode.'</script></div>';
	}
	/**
      * Display blocks after loaded template of page
      *
      */
	function displayAfterLoadTempl() 
	{
		$this->xt->display_loaded("style_block");
		$this->xt->display_loaded("iestyle_block");
		$this->xt->display_loaded("body");
	}
}
?>