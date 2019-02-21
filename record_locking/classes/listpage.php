<?php
class ListPage extends RunnerPage 
{
	
	var $recordsOnPage = 0;
	
	var $colsOnPage = 1;
	
	var $dataSourceTable = "";
	
	var $gsqlHead = "";

	var $gsqlFrom = "";
	
	var $gsqlWhereExpr = "";
	
	var $gsqlTail="";	
	
	var $querySQL = "";
	/**
	 * Display grid with data or not
	 *
	 * @var bool
	 */
	var $isDispGrid = false;
	/**
	 * Use subqueries or not
	 *
	 * @var bool
	 */
	var $subQueriesSupp = true;
	/**
	 * Array of fields for sorting on list pagr
	 *
	 * @var array
	 */
	var $arrFieldForSort = array();
	/**
	 * Array of how fields will be sorting
	 *
	 * @var array
	 */
	var $arrHowFieldSort = array();
	/**
	 * Do export or not
	 *
	 * @var bool
	 */
	var $exportTo = false;
	/**
	 * Array of fields that is shown on list
	 *
	 * @var array()
	 * @var array['listFields']= array('fName'=>"@f.strName s", 'viewFormat'=>'@f.strViewFormat');
	 */
	var $listFields = array();
	/**
	 * Array of field names that used for totals
	 *
	 * @var array
	 * @var array['totalsFields']= array('fName'=>"@f.strName s", 'totalsType'=>'@f.strTotalsType', 'viewFormat'=>"@f.strViewFormat");
	 */
	var $totalsFields = array();
	/**
	 * Record set, that retrieved from DB
	 *
	 * @var link
	 */
	var $recSet = null;
	/**
	 * Array of body begin, end code and body attributs
	 *
	 * @var array
	 */
	var $body = array();
	/**
	 * If use group scurity or not
	 *
	 * @var integer
	 */
	var $nSecOptions = 0;
	/**
	 * If use group scurity or not
	 *
	 * @var bool
	 */
	var $isGroupSecurity = true;
	/**
	 * Array of permissions for pages
	 *
	 * @var array
	 */
	var $permis = array();
	/**
	 * Indicator is permissions dynamic
	 *
	 * @var bool
	 */
	var $isDynamicPerm = false;
	/**
	 * If use vertical layout or not
	 *
	 * @var bool
	 */
	var $isVerLayout = false;
	/**
	 * Master table name
	 *
	 * @var string
	 */
	var $masterTable = "";
	/**
	 * Master table requested keys
	 *
	 * @var array
	 */
	var $masterKeysReq = array();
	/**
	 * Master table keys
	 *
	 * @var array
	 */
	var $masterKeys = array();
	/**
	 * Detail table keys
	 *
	 * @var array
	 */
	var $detailKeys = array();
	/**
	 * String of OrderBy for query
	 *
	 * @var string
	 */
	var $strOrderBy = "";
	/**
	 * Number of record
	 *
	 * @var integer
	 */
	var $recNo = 1;
	/**
	 * Id of record
	 *
	 * @var integer
	 */
	var $recId = 0;
	/**
	 * Number of maximum pages
	 *
	 * @var integer
	 */
	var $maxPages = 1;
	/**
	 * Number of maximum records
	 *
	 * @var integer
	 */
	var $maxRecs = 0;
	/**
	 * Id of row
	 *
	 * @var integer
	 */
	var $rowId = 0;
	/**
	 * Number of my page
	 *
	 * @var integer
	 */
	var $myPage = 0;
	/**
	 * Number of page size
	 *
	 * @var integer
	 */
	var $pageSize = 0;
	/**
	 * Array of selected records for delete
	 *
	 * @var array
	 */
	var $selectedRecs = array();
	/**
	 * Number of record for delete
	 *
	 * @var integer
	 */
	var $recordsDeleted = 0;
	/**
	 * Number of founed rows
	 *
	 * @var bool
	 */
	var $rowsFound = false;
	/**
	 * String of part query Where for make sql "select" string
	 *
	 * @var string
	 */
	var $strWhereClause = "";
	/**
	 * String of part query Where for make sql "select" string
	 *
	 * @var string
	 */
	var $subQueriesSupAccess = false;
	/**
	 * Original details table name
	 *
	 * @var string
	 */
	var $origTName = "";
	/**
	 * If nedd add web report or not
	 *
	 * @var bool
	 */
	var $isAddWebRep = true;	
	/**
	 * If use is Admin table or not
	 *
	 * @var bool
	 */
	var $isAdminTable = false;
	/**
	 * Array of basic search fields
	 *
	 * @var array
	 */
	//var $basicSearchFieldsArr = array();
	/**
	 * If use iBox files or not
	 *
	 * @var bool
	 */
	var $useIbox = false;
	/**
	 * If use Details Preview js file or not
	 *
	 * @var bool
	 */
	var $useDetailsPreview = false;
	/**
	 * If use Inline Edit js file or not
	 *
	 * @var bool
	 */
	var $isUseInlineJs = false;
	/**
	 * If use Inline Add on page or not
	 *
	 * @var bool
	 */
	var $isUseInlineAdd = false;
	/**
	 * If use Inline Edit on page or not
	 *
	 * @var bool
	 */
	var $isUseInlineEdit = false;
	/**
	 * If use Ajax Suggest js file or not
	 *
	 * @var bool
	 */
	var $isUseAjaxSuggest = true;
	/**
	 * If use Custom Labels js file or not
	 *
	 * @var bool
	 */
	var $useCustomLabels = false;
	/**
	 * Fields that used for advSearch
	 *
	 * @var unknown_type
	 */
	var $advSearchFieldsArr = array();
	/**
	 * Array of key's fields
	 *
	 * @var array
	 */
	var $arrKeyFields = array();
	/**
	 * Number of rows
	 *
	 * @var integer
	 */
	var $numRowsFromSQLFromSQL = 0;
	/**
	 * Indicator, is used section 508 
	 *
	 * @var bool
	 */
	var $is508 = false;
	/**
	 * If use display loading or not
	 *
	 * @var bool
	 */
	var $isDisplayLoading = false;
	/**
	 * Array of menu tables
	 *
	 * @var array
	 */
	var $menuTablesArr = array();
	/**
	 * Are there records on first page or not
	 *
	 * @var bool
	 */
	var $noRecordsFirstPage = false;
	/**
	 * String Group By of current table 
	 *
	 * @var string
	 */
	var $groupBy = "";
	/**
	 * Number of records per row list
	 *
	 * @var integer
	 */
	var $recsPerRowList = 0;
	/**
	 * Set language params or not
	 *
	 * @var bool
	 */
	var $setLangParams = false;
	/**
	 * Delete associated uploaded files or not
	 *
	 * @var bool
	 */
	var $delFile = false;
	/**
	 * Database type
	 *
	 * @var integer
	 */
	var $dbType = 0;
	/**
	 * Add style or not for row highlite
	 *
	 * @var bool
	 */
	var $rowHighlite = false;
	/**
	 * String за main table owner's Id
	 *
	 * @var string
	 */
	var $mainTableOwnerID = "";
	/**
	 * Use print friendly or not
	 *
	 * @var bool
	 */
	var $printFriendly = false;
	/**
	 * Create login page or not
	 *
	 * @var bool
	 */
	var $createLoginPage = false;
	/**
	 * Create menu or not
	 *
	 * @var bool
	 */
	var $isCreateMenu = false;
	/*
	 * Editing record or not for edit and edit-inline
	 */
	var $editable = "";
	/**
	 * Instance of SearchClause class
	 *
	 * @var object
	 */
	var $searchClause = null;
	
	/**
	 * String caption of table
	 *
	 * @var string
	 */
	var $strCaption = "";
	/**
	 * Array of files for including
	 *
	 * @var array
	 */
	var $includesArr = array();
	/**
	 * Is columns will be resizable or not
	 *
	 * @var boolean
	 */
	var $resizableColumns = false;
	
	var $searchControlBuilder = null;
	/**
	 * Name of the templete file
	 *
	 * @var string
	 */
	var $templatefile = "";
	/**
	 * Searchpanel class builder
	 *
	 * @var object
	 */
	var $searchPanel = null;
	/**
	 * Constructor, set initial params
	 *
	 * @param array $params
	 */
	function ListPage(&$params) {
		// call parent constructor
		parent::RunnerPage($params);
		
		//Clear session keys
		$this->clearSessionKeys();
		//	Before Process event
		$this->beforeProcessEvent();
		// process master key value
		if(count($this->menuTablesArr))
			$this->processMasterKeyValue();
					
		//fill session variables
		$this->setSessionVariables();
		
		// Set language params, if have more than one language
		if($this->setLangParams)
			$this->setLangParams();
			
		// get permissions 
		$this->permis[$this->tName]= $this->getPermissions();
		// get perm for det tables
		$allDetailsTablesArr = GetDetailTablesArr($this->tName);
		for($i = 0; $i < count($allDetailsTablesArr); $i ++) {
			$this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]= $this->getPermissions($allDetailsTablesArr[$i]['dDataSourceTable']);
		}
		// get perm for menu tables
		for($i = 0; $i < count($this->menuTablesArr); $i ++) {
			$this->permis[$this->menuTablesArr[$i]["dataSourceTName"]]= $this->getPermissions($this->menuTablesArr[$i]["dataSourceTName"]);
		}
		
		//$this->recId = $this->recNo + $this->id;
		$this->genId();
		
		$this->is508 = isEnableSection508();	
		
		$this->isCreateMenu();
		
		// template file name
		$this->templatefile = $this->shortTableName."_list.htm";
		
		$this->isAdminTable = $this->isAdminTable();
	}
	/**
	 * Generates new id, same as flyId on front-end
	 *
	 * @return int
	 */
	function genId()
	{
		$this->recId = parent::genId();
		return $this->recId;
	}
	/**
	 * Add common html code for all modes on list page
	 */	
	function addCommonHtml() 
	{
		
		$this->body["begin"].= "<div id=\"search_suggest\" class=\"search_suggest\"></div>";
		$this->body["begin"].= "<div id=\"master_details\" onmouseover=\"RollDetailsLink.showPopup();\" onmouseout=\"RollDetailsLink.hidePopup();\"> </div>";
		
		if($this->is508) {
			$this->body["begin"].= "<a href=\"#skipdata\" title=\"Skip to table data\" style=\"width:1px;height:1px;overflow:hidden;display:block;\">Skip to table data</a>";
			$this->body["begin"].= "<a href=\"#skipmenu\" title=\"Skip to menu\" style=\"width:1px;height:1px;overflow:hidden;display:block;\">Skip to menu</a>";
			$this->body["begin"].= "<a href=\"#skipsearch\" title=\"Skip to search\" style=\"width:1px;height:1px;overflow:hidden;display:block;\">Skip to search</a>";
			
			$this->xt->assign("header", "<a name=\"skipmenu\"></a>");
		}
		
		//prepare for dispaly master table info on details table		
		$this->displayMasterTableInfo();
				
		if($this->searchClause->isUsedSrch())
			$this->addJSCode("if($('#ctlSearchFor".$this->id."').length) $('#ctlSearchFor".$this->id."').focus();");
		
	}
	/**
	 * display Back to Master link and master table info
	 */		
	function displayMasterTableInfo() 
	{
		$masterTablesInfoArr = GetMasterTablesArr($this->tName);
		for($i = 0; $i < count($masterTablesInfoArr); $i ++) 
		{
			if($this->masterTable == $masterTablesInfoArr[$i]['mDataSourceTable']) 
			{
				if($masterTablesInfoArr[$i]['dispInfo']) 
				{
					$detailKeys = $masterTablesInfoArr[$i]['detailKeys'];
					for($j = 0; $j < count($detailKeys); $j ++)
						$masterKeys[]= @$_SESSION[$this->sessionPrefix."_masterkey".($j + 1)];
					
					$params = array("detailtable" => $this->tName, "keys" => $masterKeys);
					$master = array();
					$master["func"]= "DisplayMasterTableInfo_".$masterTablesInfoArr[$i]['mShortTable'];
					$master["params"]= $params;
					$this->xt->assignbyref("showmasterfile", $master);
				}
				$this->xt->assign("mastertable_block", true);
				$this->xt->assign("backtomasterlink_attrs", "href=\"".$masterTablesInfoArr[$i]['mShortTable']."_list.php?a=return\"");
			}
		}
	}
	
	
	/**
	 * Add common js files and code
	 */
	function addCommonJs() 
	{
		parent::addCommonJs();
		
		if($this->useIbox) 
		{
			$this->AddJSFile("ibox");
			$this->AddCSSFile("ibox");	
			$this->AddJsCode("init_ibox();");	
		}
		
		if($this->useDetailsPreview)
		{
			$this->AddJSFile("detailspreview");
			//It's create for dpInline on list page
			$this->AddJsCode("window.dpInline".$this->id." = new detailsPreviewInline(
							{'pageId':".$this->id.",
							 'mSTable':'".$this->shortTableName."',
							 'mTable':'".jsreplace($this->tName)."',
							 'mode':'dpinline_list',
							 'ext':'php'
							});");
		}
		if($this->isUseInlineJs)
			$this->AddJSFile("inlineedit");
		
		$this->AddJSFile("ajaxsuggest");
		$this->AddJSFile("ui");
		$this->AddJSFile("ui.core", "ui");
		$this->AddJSFile('ui.resizable', 'ui.core');
		$this->AddJSFile("onthefly");
		$this->AddJSCode("\nwindow.bSelected=false;");
		
				
		if($this->resizableColumns)
			$this->prepareForResizeColumns();
		
		if($this->isUseInlineJs)
			$this->AddJSCode("window.inlineEditing".$this->id." = new InlineEditing('".$this->shortTableName."','".jsreplace($this->tName)."','php',".$this->id.");");
		
		if(!$this->resizableColumns && $this->isUseInlineAdd && $this->permis[$this->tName]['add']) 
		{
			$this->AddJSCode("\$(\"#addarea".$this->id."\").each(function(i) { \$(this).hide();});\r\n");
			if(!$this->numRowsFromSQLFromSQL) 
				$this->addJSCode("$('#record_controls".$this->id."').hide();");
		}
		
		$this->addJSPagination();
	
	}
	
	/**
      * Add javascript pagination code for current mode
      *
	  */
	function addJSPagination() 
	{
		$this->addJSCode("window.GotoPage".$this->id." = function(nPageNumber)
			{
				window.location='".$this->shortTableName."_list.php?goto='+nPageNumber;
			};");
	}
	/**
      * If use resizable columns
	  * Prepare for resize main table
	  */
	function prepareForResizeColumns()
	{
		$this->AddJSFile("combo");
		$this->AddJSFile("YUI.grid");
		$this->AddCSSFile("styleDialogYui");
		$this->AddCSSFile("stylesheets");
		$this->AddJSCode("prepareForCreateTable({
			'id':".$this->id.",
			'useInlineAdd':".($this->isUseInlineAdd ? 1 : 0).",
			'permisAdd':".($this->permis[$this->tName]['add'] ? 1 : 0).",
			'numRows':".$this->numRowsFromSQLFromSQL."});");
	}
	
	/**
	 * Get permissions for pages
	 */
	function getPermissions($tName = "") 
	{
		$resArr = array();
		
		if(!$this->isGroupSecurity) 
		{
			$resArr["add"]= true;
			$resArr["delete"]= true;
			$resArr["edit"]= true;
			$resArr["search"]= true;
			$resArr["export"]= true;
			$resArr["import"]= true;
		}
		else{
				if(!$tName)
					$tName = $this->tName;
				$strPerm = GetUserPermissions($tName);
				
				$resArr["add"]=(strpos($strPerm, "A") !== false);
				$resArr["delete"]=(strpos($strPerm, "D") !== false);
				$resArr["edit"]=(strpos($strPerm, "E") !== false);
				$resArr["search"]=(strpos($strPerm, "S") !== false);
				$resArr["export"]=(strpos($strPerm, "P") !== false);
				$resArr["import"]=(strpos($strPerm, "I") !== false);
			}
		if(! $this->isUseInlineJs)
			$resArr["add"]= false;
		return $resArr;
	}
	/**
	 * Clear session kyes
	 */
	function clearSessionKeys() 
	{
		if(! count($_POST) &&(! count($_GET) || count($_GET) == 1 && isset($_GET["menuItemId"]))) 
		{
			$sess_unset = array();
			foreach($_SESSION as $key => $value)
				if(substr($key, 0, strlen($this->tName) + 1) == $this->tName."_" && strpos(substr($key, strlen($this->tName) + 1), "_") ===false)
					$sess_unset[]= $key;
			
			foreach($sess_unset as $key)
				unset($_SESSION[$key]);
		}
	}
	/**
	 * Process master key value from request params
	 * For DPInline mode and use this mode on edit or add page
	 */
	function processMasterKeyValue() 
	{
		if($this->masterTable != "") 
		{
			$_SESSION[$this->sessionPrefix."_mastertable"]= $this->masterTable;
			//	copy keys to session
			for($i = 1;$i<=count($this->masterKeysReq);$i++)
				$_SESSION[$this->sessionPrefix."_masterkey".$i]= $this->masterKeysReq[$i];
			
			if(isset($_SESSION[$this->sessionPrefix."_masterkey".$i]))
				unset($_SESSION[$this->sessionPrefix."_masterkey".$i]);
				//	reset search and page number
			$_SESSION[$this->sessionPrefix."_search"]= 0;
			if($this->firstTime)
				$_SESSION[$this->sessionPrefix."_pagenumber"]= 1;
		} else
			$this->masterTable = $_SESSION[$this->sessionPrefix."_mastertable"];
	}
	
	/**
	 * Add event before process list
	 */
	function beforeProcessEvent() 
	{
		if(function_exists("BeforeProcessList"))
			BeforeProcessList($this->conn);
	}
	/**
	 * Set session variables
	 */
	function setSessionVariables() 
	{
		// SearchClause class stuff
		if (isset($_SESSION[$this->sessionPrefix.'_advsearch']))
		{
			$this->searchClause = unserialize($_SESSION[$this->sessionPrefix.'_advsearch']);
				
		}else{
			$this->searchClause = new SearchClause($this->tName, $this->advSearchFieldsArr, $this->sessionPrefix);
		}
		
		
		$this->searchClause->parseRequest();
		
		$_SESSION[$this->sessionPrefix.'_advsearch'] = serialize($this->searchClause);	
		
				
		//set session order by
		if(@$_REQUEST["orderby"])
			$_SESSION[$this->sessionPrefix."_orderby"]= @$_REQUEST["orderby"];
			//set session page size
		if(@$_REQUEST["pagesize"]) 
		{
			$_SESSION[$this->sessionPrefix."_pagesize"]= @$_REQUEST["pagesize"];
			$_SESSION[$this->sessionPrefix."_pagenumber"]= 1;
		}
		//set session goto
		if(@$_REQUEST["goto"])
			$_SESSION[$this->sessionPrefix."_pagenumber"]= @$_REQUEST["goto"];
			
			
		//	page number
		$this->myPage =(integer) $_SESSION[$this->sessionPrefix."_pagenumber"];
		if(! $this->myPage)
			$this->myPage = 1;
			
		//	page size
		$this->pageSize =(integer) $_SESSION[$this->sessionPrefix."_pagesize"];
		if(! $this->pageSize)
			$this->pageSize = $this->gPageSize;	
	}
	/**
	 * Order links attribute for order on list page
	 */
	function orderLinksAttr() 
	{
		for($i = 0; $i < count($this->listFields); $i ++) 
		{
			$this->xt->assign(GoodFieldName($this->listFields[$i]['fName'])."_orderlinkattrs", $this->setLinksAttr(GoodFieldName($this->listFields[$i]['fName'])));
			$this->xt->assign(GoodFieldName($this->listFields[$i]['fName'])."_fieldheader", true);
		}
	}
	/**
	 * Set order links attribute for order on list page
	 *
	 *@param string - name field, which is ordering
	 *@param string - how is filed ordering, "a" - asc or "d" - desc, default is "a"
	 */
	function setLinksAttr($field, $sort = "") 
	{
		$href = $this->shortTableName."_list.php?orderby=".($sort != "" ?($sort == "a" ? "d" : "a") : "a").$field;
		$orderlinkattrs = " href=\"".$href."\" OnMouseDown=\"sort(event,this.href);\" OnMouseOver=\"addspan(event);\" OnMouseMove=\"movespan(event);\" OnMouseOut=\"delspan();\"";
		return $orderlinkattrs;
	}
	/**
	 * Builde order params
	 */
	function buildOrderParams() 
	{
		//orderlinkattrs for fields
		$this->orderLinksAttr();
		
		$order_ind = - 1;
		//Array fields for sort	
		$this->arrFieldForSort = array();
		//Array how fields sort	
		$this->arrHowFieldSort = array();
		$arrImplicitSortFields = array();
		$key = array();
		//Session field for sort		
		if(@$_SESSION[$this->sessionPrefix."_arrFieldForSort"])
			$this->arrFieldForSort = $_SESSION[$this->sessionPrefix."_arrFieldForSort"];
			//Session how field sort
		if(@$_SESSION[$this->sessionPrefix."_arrHowFieldSort"])
			$this->arrHowFieldSort = $_SESSION[$this->sessionPrefix."_arrHowFieldSort"];
			//Session key fields for sort		
		if(@$_SESSION[$this->sessionPrefix."_key"])
			$key = $_SESSION[$this->sessionPrefix."_key"];
		else{
				$tKeys = GetTableKeys($this->tName);
				for($i = 0; $i < count($tKeys); $i ++) 
				{
					if(GetFieldIndex($tKeys[$i]))
						$key[] = GetFieldIndex($tKeys[$i]);
				}
				$_SESSION[$this->sessionPrefix."_key"]= $key;
			}
		$lenkey = count($key);
		if(! isset($_SESSION[$this->sessionPrefix."_order"])) 
		{
			$this->arrFieldForSort = array();
			$this->arrHowFieldSort = array();
			if(count($this->gOrderIndexes)) 
			{
				for($i = 0; $i < count($this->gOrderIndexes); $i ++) 
				{
					$this->arrFieldForSort[] = $this->gOrderIndexes[$i][0];
					$this->arrHowFieldSort[] = $this->gOrderIndexes[$i][1];
				}
			}
			elseif($this->gstrOrderBy != '')
				$_SESSION[$this->sessionPrefix."_noNextPrev"]= 1;
			//add sorting on key fields
			if(count($key)) 
			{
				for($i = 0; $i < $lenkey; $i ++) 
				{
					$idsearch = array_search($key[$i], $this->arrFieldForSort);
					if($idsearch ===false) 
					{
						$this->arrFieldForSort[]= $key[$i];
						$this->arrHowFieldSort[]= "ASC";
						$arrImplicitSortFields[]= $key[$i];
					}
				}
			}
		}
		$lenArr = count($this->arrFieldForSort);
		//Sorting defined on the sheet
		if(@$_SESSION[$this->sessionPrefix."_orderby"]) 
		{
			$order_field = GetFieldByGoodFieldName(substr($_SESSION[$this->sessionPrefix."_orderby"], 1));
			$order_dir = substr($_SESSION[$this->sessionPrefix."_orderby"], 0, 1);
			$order_ind = GetFieldIndex($order_field);
			if($order_ind) 
			{
				if(! @$_REQUEST["a"]and ! @$_REQUEST["goto"]and ! @$_REQUEST["pagesize"]) 
				{
					if(@$_REQUEST["ctrl"]) 
					{
						$idsearch = array_search($order_ind, $this->arrFieldForSort);
						if($idsearch ===false) 
						{
							if($order_dir == "a") 
							{
								$this->arrFieldForSort[]= $order_ind;
								$this->arrHowFieldSort[]= "ASC";
							} 
							else{
									$this->arrFieldForSort[]= $order_ind;
									$this->arrHowFieldSort[]= "DESC";
								}
						} 
						else
							$this->arrHowFieldSort[$idsearch]=($order_dir == "a" ? "ASC" : "DESC");
					} 
					else{
							$this->arrFieldForSort = array();
							$this->arrHowFieldSort = array();
							if(! empty($_SESSION[$this->sessionPrefix."_orderNo"]))
								unset($_SESSION[$this->sessionPrefix."_orderNo"]);
							$_SESSION[$this->sessionPrefix."_noNextPrev"]= 0;
							if($order_dir == "a") {
								$this->arrFieldForSort[]= $order_ind;
								$this->arrHowFieldSort[]= "ASC";
							} else {
								$this->arrFieldForSort[]= $order_ind;
								$this->arrHowFieldSort[]= "DESC";
							}
						}
				}
			}
		}
		$lenArr = count($this->arrFieldForSort);
		//Draw pictures of fields for sorting
		$condition = true;
		if(! count($this->arrFieldForSort))
			$condition = false;
		elseif(! $this->arrFieldForSort[0])
			$condition = false;
		if($condition) 
		{
			for($i = 0; $i < $lenArr; $i ++) 
			{
				$order_field = GetFieldByIndex($this->arrFieldForSort[$i]);
				$order_dir = $this->arrHowFieldSort[$i] == "ASC" ? "a" : "d";
				$idsearch = array_search($this->arrFieldForSort[$i], $arrImplicitSortFields);
				if($idsearch ===false) 
					$this->xt->assign_section(GoodFieldName($order_field)."_fieldheader", "", "<img ".($this->is508 == true ? "alt=\" \" " : "")."src=\"images/".($order_dir == "a" ? "up" : "down").".gif\" border=0>");
				// default ASC for key fields	
				if($idsearch !== false && in_array(GetFieldIndex($order_field), $arrImplicitSortFields))
					$orderlinkattrs = $this->setLinksAttr(GoodFieldName($order_field));
				else
					$orderlinkattrs = $this->setLinksAttr(GoodFieldName($order_field), $order_dir);
				$this->xt->assign(GoodFieldName($order_field)."_orderlinkattrs", $orderlinkattrs);
			}
		}
		//Shape sorting line for a request
		if($lenArr > 0) 
		{
			for($i = 0; $i < $lenArr; $i ++)
				$this->strOrderBy.=(GetFieldByIndex($this->arrFieldForSort[$i]) ?($this->strOrderBy != "" ? ", " : " ORDER BY ").$this->arrFieldForSort[$i]." ".$this->arrHowFieldSort[$i]: "");
		}
		if($_SESSION[$this->sessionPrefix."_noNextPrev"] == 1)
			$this->strOrderBy = $this->gstrOrderBy;
	}
	/**
	 * Delete selected records
	 */
	function deleteRecords() 
	{
		if(@$_REQUEST["mdelete"]) 
		{
			foreach(@$_REQUEST["mdelete"]as $ind) 
			{
				for($i = 0; $i < count($this->arrKeyFields); $i ++)
					$keys[$this->arrKeyFields[$i]]= refine($_REQUEST["mdelete".($i + 1)][mdeleteIndex($ind)]);
				$this->selectedRecs[]= $keys;
			}
		} 
		elseif(@$_REQUEST["selection"]) 
		{
			foreach(@$_REQUEST["selection"]as $keyblock) 
			{
				$arr = explode("&", refine($keyblock));
				if(count($arr) < count($this->arrKeyFields))
					continue;
				for($i = 0; $i < count($this->arrKeyFields); $i ++)
					$keys[$this->arrKeyFields[$i]]= urldecode(@$arr[$i]);
				$this->selectedRecs[]= $keys;
			}
		}
		
		$this->recordsDeleted = 0;
		foreach($this->selectedRecs as $keys) 
		{
			$where = KeyWhere($keys);
			//	delete only owned records			
			if($this->nSecOptions != ADVSECURITY_ALL && $this->nLoginMethod == SECURITY_TABLE && $this->createLoginPage)
				$where = whereAdd($where, SecuritySQL("Delete"));
			
			$strSQl = "delete from ".AddTableWrappers($this->origTName)." where ".$where;
			$retval = true;
			if(function_exists("AfterDelete") || function_exists("BeforeDelete") || isAuditEnable($this->tName)) 
			{
				$deletedrs = db_query(gSQLWhere_int($this->gsqlHead,$this->gsqlFrom,$this->gsqlWhereExpr,$this->gsqlTail,$where), $this->conn);
				$deleted_values = db_fetch_array($deletedrs);
			}
			if(function_exists("BeforeDelete"))
				$retval = BeforeDelete($where, $deleted_values);
			if($retval && @$_REQUEST["a"] == "delete") 
			{
				$this->recordsDeleted ++;
				// delete associated uploaded files if any
				if($this->delFile)
					DeleteUploadedFiles($where);
				LogInfo($strSQl);
				db_exec($strSQl, $this->conn);
				
				if(isAuditEnable($this->tName))
					$this->audit->LogDelete($this->tName, $deleted_values, $keys);
				if(function_exists("AfterDelete"))
					AfterDelete($where, $deleted_values);
			}
		}
		if(count($this->selectedRecs)) 
		{
			if(function_exists("AfterMassDelete"))
				AfterMassDelete($this->recordDeleted);
		}
	}
	/**
	 * PRG rule, to avoid POSTDATA resend
	 *
	 */
	function rulePRG() 
	{		
		if(no_output_done() && count($this->selectedRecs)) 
		{	
			// redirect, add a=return param for saving SESSION
			header("Location: ".$this->shortTableName."_".$this->getPageType().".php?a=return");
			// turned on output buffering, so we need to stop script
			exit();
		}
	}
	
	function createOldMenu() 
	{		
		for($i = 0; $i < count($this->menuTablesArr); $i ++) 
		{
			if($this->permis[$this->menuTablesArr[$i]['dataSourceTName']]['add']|| $this->permis[$this->menuTablesArr[$i]['dataSourceTName']]['search'])
			{				
				$this->xt->assign($this->menuTablesArr[$i]['dataSourceTName']."_tablelink", true);
				$page = "";
				if($this->permis[$this->menuTablesArr[$i]['dataSourceTName']]['list']) 
				{
					$page = "list";
					if($this->permis[$this->menuTablesArr[$i]['dataSourceTName']]['add'])
						$strPerm = GetUserPermissions($this->menuTablesArr[$i]['strDataSourceTable']);
					if(strpos($strPerm, "A") !== false && strpos($strPerm, "S") ===false)
						$page = "add";
				} elseif($this->permis[$this->menuTablesArr[$i]['dataSourceTName']]['add'])
					$page = "add";
				elseif($this->menuTablesArr[$i]['nType'] == titREPORT)
					$page = "report";
				elseif($this->menuTablesArr[$i]['nType'] == titCHART)
					$page = "chart";
				$this->xt->assign($this->menuTablesArr[$i]."_tablelink_attrs", "href=\"".$this->menuTablesArr[$i]."_".$page.".php\"");
				$this->xt->assign("".$this->menuTablesArr[$i]."_optionattrs", "value=\"".$this->menuTablesArr[$i]."_".$page.".php\"");
			}
		}		
	}
	
	/**
	 * Add code from program before show list
	 */
	function BeforeShowList() 
	{
		if(function_exists("BeforeShowList"))
			BeforeShowList($this->xt, $this->templatefile);
	}
	
	
	/**
	 * SearchPanel
	 * Builds searchPanel
	 *
	 * @param array $searchFieldNames of fields that used for adv search
	 */
	function buildSearchPanel() 
	{
		
	}
	
	
	/**
	 * Makes assigns for admin 
	 *
	 */
	function assignAdmin() 
	{
		if($this->isAdminTable) 
		{
			$this->xt->assign("html_attrs", "lang=\"en\"");
			$this->xt->assign("exitaalink_attrs", "href=\"menu.php\" onclick=\"window.location.href='menu.php';return false;\"");
			$this->xt->assign("exitaalink_href", "href=\"menu.php\"");
			$this->xt->assign("exitadminarea_link", true);
			$this->xt->assign("admin_rights_tablelink", true);
			$this->xt->assign("admin_rights_tablelink_attrs", "href=\"admin_rights_list.php\"");
			$this->xt->assign("admin_members_tablelink", true);
			$this->xt->assign("admin_members_tablelink_attrs", "href=\"admin_members_list.php\"");
			$this->xt->assign("admin_users_tablelink", true);
			$this->xt->assign("admin_users_tablelink_attrs", "href=\"admin_users_list.php\"");
			$this->xt->assign("admin_rights_optionattrs", "value=\"admin_rights_list.php\"");
			$this->xt->assign("admin_members_optionattrs", "value=\"admin_members_list.php\"");
			$this->xt->assign("admin_users_optionattrs", "value=\"admin_users_list.php\"");
		}
		
		if($this->isDynamicPerm && IsAdmin()) 
		{
			$this->xt->assign("adminarea_link", true);
			$this->xt->assign("adminarealink_attrs", "href=\"admin_rights_list.php\" onclick=\"window.location.href='admin_rights_list.php';return false;\"");
		}
	
	}
	
	function assignImportLinks() 
	{
		$this->xt->assign("import_link", $this->permis[$this->tName]['import']);
		$this->xt->assign("importlink_attrs", "href='".$this->shortTableName."_import.php' onclick=\"window.location.href='".$this->shortTableName."_import.php';return false;\"");
	}
	
	function assignBodyEnd() {
		echo "<script>".$this->PrepareJS()."</script>";
	}
	/**
	 * Common assign for diferent mode on list page
	 * Branch classes add to this method its individualy code
	 */
	function commonAssign() 
	{
		$this->xt->assign("id", $this->id);
		$this->xt->assignbyref("body", $this->body);
		
		$this->xt->assign("style_block", true);
		$this->xt->assign("iestyle_block", true);
		
		$this->xt->assign("recordcontrols_block", $this->permis[$this->tName]['add']|| $this->isDispGrid);		
		$this->xt->assign("newrecord_controls", $this->permis[$this->tName]['add']);				
		$this->xt->assign("grid_controls", $this->isDispGrid);		
		
		$this->assignImportLinks();
				
		$this->xt->assign("usermessage", true);
		
		$this->xt->assign("changepwd_link", $_SESSION["AccessLevel"]!= ACCESS_LEVEL_GUEST);
		$this->xt->assign("changepwdlink_attrs", "href=\"changepwd.php\" onclick=\"window.location.href='changepwd.php';return false;\"");
		
		if($this->isCreateMenu || $this->isAdminTable) 
			$this->xt->assign("quickjump_attrs", "onfocus =\"window.selectcurrent = this.selectedIndex;\" onchange=\"if(this.options[this.selectedIndex].value){window.location.href=this.options[this.selectedIndex].value;}else{this.selectedIndex=window.selectcurrent;}\"");
				
		$this->xt->assign("rpp".$this->pageSize."_selected", "selected");
		
		if($this->is508)
			$this->xt->assign_section("grid_header", "<caption style=\"display:none\">Table data</caption>", "");
		
		if($this->isAddWebRep) 
			$this->xt->assign("webreport_link", true);
		
		
		$this->xt->assign("endrecordblock_attrs", "colid=\"endrecord\"");
		
		if($this->createLoginPage) 
		{
			$this->xt->assign("login_block", true);
			$this->xt->assign("username", htmlspecialchars($_SESSION["UserID"]));
			$this->xt->assign("logoutlink_attrs", "onclick=\"window.location.href='login.php?a=logout';\"");
		}
		
		$this->inlineAddLinksAttr();
		
		for($i = 0; $i < count($this->listFields); $i ++) 
		{
			$this->xt->assign(GoodFieldName($this->listFields[$i]['fName'])."_fieldheadercolumn", true);
			$this->xt->assign(GoodFieldName($this->listFields[$i]['fName'])."_fieldcolumn", true);
			$this->xt->assign(GoodFieldName($this->listFields[$i]['fName'])."_fieldfootercolumn", true);
		}
				
		if($this->isDispGrid) 
		{
			$colsonpage = $this->recsPerRowList;			
			
			$record_header = array("data" => array());
			$record_footer = array("data" => array());
			for($i = 0; $i < $colsonpage; $i ++) 
			{
				$rheader = array();
				$rfooter = array();
				if($i < $colsonpage - 1) 
				{
					$rheader["endrecordheader_block"]= true;
					$rfooter["endrecordfooter_block"]= true;
				}
				$record_header["data"][]= $rheader;
				$record_footer["data"][]= $rfooter;
			}
			$this->xt->assignbyref("record_header", $record_header);
			$this->xt->assignbyref("record_footer", $record_footer);
			$this->xt->assign("grid_header", true);
			// hiding header, if no rows
			if(!$this->numRowsFromSQLFromSQL)
				$this->xt->assign("gridHeader_attrs", 'id="gridHeaderTr'.$this->id.'" style="display: none;"');
			
			$this->xt->assign("grid_footer", true);
			
			$this->xt->assign("record_controls", true);
			
			$this->xt->assign("recordcontrols_block", $this->permis[$this->tName]['add']|| $this->isDispGrid);
			
			$this->xt->assign("newrecord_controls", $this->permis[$this->tName]['add']);
			
			// moved from search panel
			$gridTableStyle = "";
			$gridTableStyle = 'style="';
			$gridTableStyle .= $this->recordsOnPage>0 ? '"' : 'width: 50%;"'; 
			$this->xt->assign('gridTable_attrs', $gridTableStyle);
		}
	}
	/**
	 * Show inline add link
	 * Add inline add attributes
	 */
	function inlineAddLinksAttr()
	{
		//inline add link and attr
		$this->xt->assign("inlineadd_link", $this->permis[$this->tName]['add']);
		$this->xt->assign("inlineaddlink_attrs", "href='".$this->shortTableName."_add.php' onclick=\"return inlineEditing".$this->id.".inlineAdd(flyid++);\"");
	}
	/**
	 * Assign selectAll link and attrs
	 * 
	 */
	function selectAllLinkAttrs()
	{
		$this->xt->assign("selectall_link", $this->permis[$this->tName]['delete']|| $this->permis[$this->tName]['export']|| $this->permis[$this->tName]['edit']);
		$this->xt->assign("selectalllink_attrs", "href=# onclick=\"window.bSelected=!window.bSelected;
					var checks = $('#grid_block".$this->id."').find('input[@name^=selection][@id^=check".$this->id."_]');
					if($(checks).length)
					{
						$(checks).each(function()
						{
							if($(this).attr('id').substr(0,9) != 'check_add')
								$(this).attr('checked',window.bSelected);
						});
					}\"");
	}
	/**
	 * Assign checkbox column, header and header attrs
	 * 
	 */
	function checkboxColumnAttrs()
	{
		$this->xt->assign("checkbox_column", $this->permis[$this->tName]['delete']|| $this->permis[$this->tName]['export']|| $this->permis[$this->tName]['edit']);
		$this->xt->assign("checkbox_header", true);
		$this->xt->assign("checkboxheader_attrs", "onClick = \"var chBoxHeader = this; 
					var checks = $('#grid_block".$this->id."').find('input[@name^=selection][@id^=check".$this->id."_]');
					if($(checks).length)
					{
						$(checks).each(function()
						{
							if($(this).attr('id').substr(0,9) != 'check_add')
								$(this).attr('checked',$(chBoxHeader).attr('checked'));
						});
					}\"");
	}
	/**
	 * Get common attrs for Print and Export links 
	 * 
	 */
	function getPrintExportLinkAttrs($page)
	{
		if(!$page)
			return '';
		return "disptype = 'control1' 
				onclick = \"if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
							return true;
						var form = $('#frmAdmin".$this->id."')[0];
						form.action='".$this->shortTableName."_".$page.".php';
						form.target='_blank';
						form.submit(); 
						form.action='".$this->shortTableName."_list.php'; 
						form.target='_self';return false;\" 
				href = '".$this->shortTableName."_".$page.".php'";
	}
	
	/**
	 * Show or hide current button
	 * 
	 */
	function buttonShowHideStyle()
	{
		return $this->numRowsFromSQLFromSQL > 0 ? '' : ' style="display: none;" ';
	}
	/**
	 * Assign editSelected link and attrs
	 * 
	 */
	function editSelectedLinkAttrs()
	{
		$this->xt->assign("editselected_link", $this->permis[$this->tName]['edit']);
		$this->xt->assign("editselectedlink_attrs", $this->buttonShowHideStyle()."
					href='".$this->shortTableName."_edit.php' 
					disptype=\"control1\" 
					name=\"edit_selected".$this->id."\" 
					onclick=\"\$('#grid_block".$this->id."').find('input[@type=checkbox][@checked][@id^=check".$this->id."_]').each(function(i){
						var arr = this.id.split('_');
						if(!isNaN(arr[1]))
							$('a#ieditlink".$this->id."_' + arr[1]).click();});\"");
	}
	/**
	 * Assign saveAll link and attrs
	 * 
	 */
	function saveAllLinkAttrs()
	{
		$this->xt->assign("saveall_link",$this->permis[$this->tName]['edit']);
		$this->xt->assign("savealllink_attrs","disptype=\"control1\" name=\"saveall_edited".$this->id."\"  onclick=\"\$('a[@id^=save".$this->id."_]').click();\"");
		$this->xt->assign("savealllink_span","style=\"display:none\"");	
	}
	/**
	 * Assign cancelAll link and attrs
	 * 
	 */
	function cancelAllLinkAttrs()
	{
		$this->xt->assign("cancelall_link",$this->permis[$this->tName]['edit']||$this->permis[$this->tName]['edit']);
		$this->xt->assign("cancelalllink_attrs","disptype=\"control1\" name=\"revertall_edited".$this->id."\" onclick=\"\$('a[@id^=revert".$this->id."_]').click();\"");
		$this->xt->assign("canselalllink_span","style=\"display:none\"");
	}
	
	/**
	 * show page at the end of its proccess, depending on mode
	 *
	 */
	function showPage() 
	{
		$this->BeforeShowList();
		$this->xt->display($this->templatefile);
	}
	/**
	 * Get Search form with hidden fields
	 *
	 */
	function getSeachFormHTML() 
	{
		return '<form id="frmSearch'.$this->id.'" name="frmSearch'.$this->id.'" method="GET" '.$this->getSearchFormTargetHTML().' action="'.GetTableURL($this->tName).'_list.php">			
				'.$this->getSearchFormInputsHTML().'
				</form>'; 
	}
		
	function getSearchFormInputsHTML() 
	{
		return '';
	}
	
	function getSearchFormTargetHTML() 
	{
		return '';
	}
	/**
	 * Calcs pagination info
	 *
	 */
	function buildPagination() 
	{
		//	hide colunm headers if needed
		$this->recordsOnPage = $this->numRowsFromSQLFromSQL -($this->myPage - 1) * $this->pageSize;
		if($this->recordsOnPage > $this->pageSize)
			$this->recordsOnPage = $this->pageSize;
		
		$this->colsOnPage = $this->recsPerRowList;
		if($this->colsOnPage > $this->recordsOnPage)
			$this->colsOnPage = $this->recordsOnPage;
		if($this->colsOnPage < 1)
			$this->colsOnPage = 1;
			
		//	 Pagination:
		if(! $this->numRowsFromSQLFromSQL) 
		{
			$this->rowsFound = false;
			$message = "No records found";
			$message_block = array();
			$message_block["begin"]= "<span name=\"notfound_message".$this->id."\">";
			$message_block["end"]= "</span>";
			$this->xt->assignbyref("message_block", $message_block);
			$this->xt->assign("message",($this->is508 == true ? "<a name=\"skipdata\"></a>" : "").$message);
		} 
		else{
				$this->rowsFound = true;
				$maxRecords = $this->numRowsFromSQLFromSQL;
				
				$this->xt->assign("records_found", $this->numRowsFromSQLFromSQL);
				$this->maxPages = ceil($maxRecords / $this->pageSize);
				if($this->myPage > $this->maxPages)
					$this->myPage = $this->maxPages;
				if($this->myPage < 1)
					$this->myPage = 1;
				$this->maxRecs = $this->pageSize;
				$this->xt->assign("page", $this->myPage);
				$this->xt->assign("maxpages", $this->maxPages);
				
				//	write pagination
				if($this->maxPages > 1) 
				{
					$this->xt->assign("pagination_block", true);
					$pagination = "<table rows='1' cols='1' align='center' width='auto' border='0'>";
					$pagination.= "<tr valign='center'><td align='center'>";
					$counterstart = $this->myPage - 9;
					if($this->myPage % 10)
						$counterstart = $this->myPage -($this->myPage % 10) + 1;
					$counterend = $counterstart + 9;
					if($counterend > $this->maxPages)
						$counterend = $this->maxPages;
					if($counterstart != 1) 
					{
						$pagination.= "<a href='JavaScript:GotoPage".$this->id."(1);' style='TEXT-DECORATION: none;'>"."First"."</a>";
						$pagination.= "&nbsp;:&nbsp;";
						$pagination.= "<a href='JavaScript:GotoPage".$this->id."(".($counterstart - 1).");' style='TEXT-DECORATION: none;'>"."Previous"."</a>";
						$pagination.= "&nbsp;";
					}
					$pagination.= "<b>[</b>";
					for($counter = $counterstart; $counter <= $counterend; $counter ++) 
					{
						if($counter != $this->myPage)
							$pagination.= "&nbsp;<a href='JavaScript:GotoPage".$this->id."(".$counter.");' class='pag_n' style='TEXT-DECORATION: none;'>".$counter."</a>";
						else
							$pagination.= "&nbsp;<b>".$counter."</b>";
					}
					$pagination.= "&nbsp;<b>]</b>";
					if($counterend != $this->maxPages) 
					{
						$pagination.= "&nbsp;<a href='JavaScript:GotoPage".$this->id."(".($counterend + 1).");' style='TEXT-DECORATION: none;'>"."Next"."</a>";
						$pagination.= "&nbsp;:&nbsp;";
						$pagination.="&nbsp;<a href='JavaScript:GotoPage".$this->id."(".($this->maxPages).");' style='TEXT-DECORATION: none;'>"."Last"."</a>";
					}
					$pagination.= "</td></tr></table>";
					$this->xt->assign("pagination", $pagination);
				}
			}
	}
	
	/**
	 * add where clause with foreign keys of current table and it's master table master keys
	 *
	 * @return string
	 */
	function addWhereWithMasterTable() 
	{
		$detailKeysForCurrentTable = GetDetailKeysByMasterTable($this->masterTable, $this->tName);
		
		$where = "";
		
		if($detailKeysForCurrentTable) {
			for($i = 0; $i < count($detailKeysForCurrentTable); $i ++) {
				if($i != 0) {
					$where.= " and ";
				}
				$where.= GetFullFieldName($detailKeysForCurrentTable[$i])."=".make_db_value($detailKeysForCurrentTable[$i], $_SESSION[$this->sessionPrefix."_masterkey".($i + 1)]);
			}
		}
		return $where;
	}
	/**
	 * Seeks recs, depending on page number etc.
	 *
	 * @param string $strSQL
	 */
	function seekPageInRecSet($strSQL) {
		if($this->dbType == nDATABASE_MySQL) {			
			if($this->maxPages > 1) {
				$strSQL.= " limit ".(($this->myPage - 1) * $this->pageSize).",".$this->pageSize;
			}
			$this->recSet = db_query($strSQL, $this->conn);
		} elseif($this->dbType == nDATABASE_MSSQLServer) {
			if($this->maxPages > 1) {
				$strSQL = AddTop($strSQL, $this->myPage * $this->pageSize);
			}
			$this->recSet = db_query($strSQL, $this->conn);
			db_pageseek($this->recSet, $this->pageSize, $this->myPage);
		} elseif($this->dbType == nDATABASE_Access) {
			if($this->maxPages > 1) {
				$strSQL = AddTop($strSQL, $this->myPage * $this->pageSize);
			}
			$this->recSet = db_query_direct($strSQL, $this->conn, $this->numRowsFromSQLFromSQL);
			db_pageseek($this->recSet, $this->pageSize, $this->myPage);
		} elseif($this->dbType == nDATABASE_Oracle) {
			if($this->maxPages > 1) {
				$strSQL = AddRowNumber($strSQL, $this->myPage * $this->pageSize);
			}
			$this->recSet = db_query_direct($strSQL, $this->conn, $this->numRowsFromSQLFromSQL);
			db_pageseek($this->recSet, $this->pageSize, $this->myPage);
		} elseif($this->dbType == nDATABASE_PostgreSQL) {
			if($this->maxPages > 1) {
				$maxrecs = $this->pageSize;
				$strSQL.= " limit ".$this->pageSize." offset ".(($this->myPage - 1) * $this->pageSize);
			}
			$this->recSet = db_query($strSQL, $this->conn);
		} else {
			$this->recSet = db_query($strSQL, $this->conn);
			db_pageseek($this->recSet, $this->pageSize, $this->myPage);
		}
	}
	/**
	 * Builds SQL query, for retrieve data from DB
	 *
	 */
	function buildSQL() 
	{
		$this->strWhereClause = whereAdd($this->strWhereClause, $this->searchClause->getWhere());
		
		if($this->noRecordsFirstPage && ! count($_GET) && ! count($_POST))
			$this->strWhereClause = whereAdd($this->strWhereClause, "1=0");
		
		//add where clause with foreign keys of current table and it's master table master keys		
		$where = $this->addWhereWithMasterTable();
		$this->strWhereClause = whereAdd($this->strWhereClause, $where);
		
		$strSQL = gSQLWhere_int($this->gsqlHead,$this->gsqlFrom,$this->gsqlWhereExpr,$this->gsqlTail,$this->strWhereClause);
		
		//	order by
		$strSQL.= " ".trim($this->strOrderBy);
		
		//	save SQL for use in "Export" and "Printer-friendly" pages		
		$_SESSION[$this->sessionPrefix."_sql"]= $strSQL;
		$_SESSION[$this->sessionPrefix."_where"]= $this->strWhereClause;
		$_SESSION[$this->sessionPrefix."_order"]= $this->strOrderBy;
		$_SESSION[$this->sessionPrefix."_arrFieldForSort"]= $this->arrFieldForSort;
		$_SESSION[$this->sessionPrefix."_arrHowFieldSort"]= $this->arrHowFieldSort;
		
		//	select and display records
		if($this->permis[$this->tName]['search']) 
		{
			$this->addMasterDetailSubQuery();
			
			$strSQLbak = $strSQL;
					
			if(function_exists("BeforeQueryList"))
				BeforeQueryList($strSQL, $this->strWhereClause, $this->strOrderBy);
				
			//	Rebuild SQL if needed
			if($strSQL != $strSQLbak) 
			{
				//	changed $strSQL - old style	
				$this->numRowsFromSQLFromSQL = GetRowCount($strSQL);
			}
			else 
				{
					$strSQL = gSQLWhere_int($this->gsqlHead,$this->gsqlFrom,$this->gsqlWhereExpr,$this->gsqlTail,$this->strWhereClause);
					$strSQL.= " ".trim($this->strOrderBy);
					$this->numRowsFromSQLFromSQL = gSQLRowCount_int($this->gsqlHead,$this->gsqlFrom,$this->gsqlWhereExpr,$this->gsqlTail,$this->strWhereClause, $this->groupBy);
				}
			LogInfo($strSQL);
			
			$this->querySQL = $strSQL;
		}
 	}
	/**
	 * Adds sub query for counting details recs number
	 *
	 */
	function addMasterDetailSubQuery() 
	{
		// add count of child records to SQL		
		if($this->subQueriesSupp && $this->subQueriesSupAccess && ! $this->checkDetailAndMasterFieldTypes()) {
			$allDetailsTablesArr = GetDetailTablesArr($this->tName);
			
			for($i = 0; $i < count($allDetailsTablesArr); $i ++) {
				if($allDetailsTablesArr[$i]['dispChildCount']|| $allDetailsTablesArr[$i]['hideChild']) {
					$detailsSqlWhere = $allDetailsTablesArr[$i]['sqlWhere'];
					$detailsTableFrom = $allDetailsTablesArr[$i]['sqlFrom'];
					$origTName = $allDetailsTablesArr[$i]['dOriginalTable'];
					$dataSourceTName = $allDetailsTablesArr[$i]['dDataSourceTable'];
					$shortTName = $allDetailsTablesArr[$i]['dShortTable'];
					
					// field names of master keys of current table for passed details table name
					$masterKeys = $allDetailsTablesArr[$i]['masterKeys'];
					// field names of detail keys of passed detail table, when current is master
					$detailKeys = $allDetailsTablesArr[$i]['detailKeys'];
					
					$masterWhere = "";
					foreach($masterKeys as $idx => $val) {
						if($masterWhere)
							$masterWhere.= " and ";
						
						$masterWhere.= AddTableWrappers("subQuery_cnt").".".AddFieldWrappers($detailKeys[$idx])."=".AddTableWrappers($this->origTName).".".AddFieldWrappers($masterKeys[$idx]);
					}
					
					//	add a key field to the select list
					$subQ = "";
					foreach($detailKeys as $k) {
						if(strlen($subQ))
							$subQ.= ",";
						$subQ.= GetFullFieldName($k, $dataSourceTName);
					}
					$subQ = "SELECT ".$subQ." ".$detailsTableFrom;
					//	add security where clause for sub query	
					$securityClause = SecuritySQL("Search", $dataSourceTName);
					if(strlen($securityClause))
						$subQ.= " WHERE ".whereAdd($detailsSqlWhere, $securityClause);
					elseif(strlen($detailsSqlWhere))
						$subQ.= " WHERE ".whereAdd("", $detailsSqlWhere);
					
					$countsql = "SELECT count(*) FROM(".$subQ.") ".AddTableWrappers("subQuery_cnt")." WHERE ".$masterWhere;
					$this->gsqlHead.= ",(".$countsql.") as ".$shortTName."_cnt ";
				}
			}
		}
	}
	/**
	 * Fills info in array about grid.
	 *
	 * @param array $rowInfoArr array with total info, that assignes grid
	 */
	function fillGridShowInfo(&$rowInfoArr) 
	{
		$rowInfoArr["data"]= array();
		$shade = false;
		$editlink = "";
		$copylink = "";
		//	add inline add row	
		if($this->isUseInlineAdd && $this->permis[$this->tName]['add']) 
		{		
				
			$row = array();
			$row["rowattrs"]= "id=\"addarea".$this->id."\" rowid=\"add\"";
			$row["rowattrs"].= ' style="display: none;"';
			$row["rowspace_attrs"]= "id=\"addarea".$this->id."\"";
			$record = array();
			$record["edit_link"]= true;
			$record["inlineedit_link"]= true;
			$record["view_link"]= true;
			$record["copy_link"]= true;
			$record["checkbox"]= true;
			$record["checkbox"]= true;
			$record["editlink_attrs"]= "id=\"editlink_add".$this->id."\"";
			
			
			if($this->permis[$this->tName]['edit']&& $this->isUseInlineEdit)
                    $record["inlineeditlink_attrs"]= "id=\"ieditlink_add".$this->id."\"";
			
			$record["copylink_attrs"]= "id=\"copylink_add".$this->id."\"";
			$record["viewlink_attrs"]= "id=\"viewlink_add".$this->id."\"";
			$record["checkbox_attrs"]= "id=\"check_add".$this->id."\" name=\"selection[]\"";
			
			$allDetailsTablesArr = GetDetailTablesArr($this->tName);
			
			for($i = 0; $i < count($allDetailsTablesArr); $i ++) 
			{
				//detail tables					
				$record[$allDetailsTablesArr[$i]['dShortTable']."_dtable_link"]=($this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['add'] || $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['search']);
				$record[$allDetailsTablesArr[$i]['dShortTable']."_dtablelink_attrs"]= " href=\"".$allDetailsTablesArr[$i]['dShortTable']."_list.php?\" id=\"master_".$allDetailsTablesArr[$i]['dShortTable']."_add".$this->id."\"";
				if($allDetailsTablesArr[$i]['detailPreviewType'] == DP_POPUP) 
					$record[$allDetailsTablesArr[$i]['dShortTable']."_dtablelink_attrs"].= " onmouseover=\"RollDetailsLink.showPopup(this,'".$allDetailsTablesArr[$i]['dShortTable']."_detailspreview.php'+this.href.substr(this.href.indexOf('?')));\" onmouseout=\"RollDetailsLink.hidePopup();\"";
				else if($allDetailsTablesArr[$i]['detailPreviewType'] == DP_INLINE) 
					$record[$allDetailsTablesArr[$i]['dShortTable']."_preview_link"]= true;
			}
			
			for($i = 0; $i < count($this->listFields); $i ++) 
			{
				$record[GoodFieldName($this->listFields[$i]['fName'])."_value"]= "<span id=\"add".$this->id."_".GoodFieldName($this->listFields[$i]['fName'])."\">&nbsp;</span>";
								                        
                if($i == 0 && !$this->isUseInlineEdit ||(!$this->permis[$this->tName]['edit']|| $this->mode == LIST_LOOKUP))
                	$record[GoodFieldName($this->listFields[$i]['fName'])."_value"]= "<span id=\"ieditlink_add".$this->id."\"></span>".$record[GoodFieldNAme($this->listFields[$i]['fName'])."_value"];
					
				//	add spans for link and display fields if they don't appear on the List page		?????????
				

				if($this->mode == LIST_LOOKUP)
					$this->addSpansForLinks($record);
			}
			if($this->colsOnPage > 1)
				$record["endrecord_block"]= true;
			$record["grid_recordheader"]= true;
			$record["grid_vrecord"]= true;
			$row["grid_record"]= array("data" => array());
			$row["grid_record"]["data"][]= $record;
			for($i = 1; $i < $this->colsOnPage; $i ++) 
			{
				$rec = array();
				if($i < $this->colsOnPage - 1)
					$rec["endrecord_block"]= true;
				$row["grid_record"]["data"][]= $rec;
			}
			
			$row["grid_rowspace"]= true;
			$row["grid_recordspace"]= array("data" => array());
			for($i = 0; $i < $this->colsOnPage * 2 - 1; $i ++)
				$row["grid_recordspace"]["data"][]= true;
			$rowInfoArr["data"][]= $row;
			
		}
	}
	/**
	 * Incapsulates beforeProccessRow event
	 *
	 * @return array
	 */
	function beforeProccessRow() 
	{
		while($data = db_fetch_array($this->recSet)) 
		{
			if(function_exists("BeforeProcessRowList")) 
			{
				if(! BeforeProcessRowList($data))
					continue;
			}
			return $data;
		}
	
	}
	/**
	 * Fills list grid.This method use many other methods
	 *
	 */
	function fillGridData() {
		
		$totals=array();
				
		//	fill $rowinfo array
		$rowinfo = array();
		$this->fillGridShowInfo($rowinfo);
		
		//	add grid data
		$shade = false;
		$data = $this->beforeProccessRow();
		
		while($data && $this->recNo <= $this->pageSize) {
			$row = array();
			if(! $this->isVerLayout) {
				if(! $shade) {
					$row["rowattrs"]= "class='shade'";
					if($this->rowHighlite)
						$row["rowattrs"].= " onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = 'shade';\"";
					$shade = true;
				} else {
					if($this->rowHighlite)
						$row["rowattrs"]= "onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = '';\"";
					$shade = false;
				}
			}
			$row["grid_record"]= array();
			$row["grid_record"]["data"]= array();
			$row["rowattrs"].= " rowid=\"".$this->rowId."\"";
			$this->rowId ++;
			$record = array();
			//$this->recId = $this->recNo + $this->id;
			$this->genId();
			
			for($col = 1; $data && $this->recNo <= $this->pageSize && $col <= $this->colsOnPage; $col ++) {				
				$this->countTotals($totals, $data);			
			}
			$this->editable = CheckSecurity($data[$this->mainTableOwnerID], "Edit");
			$record["edit_link"]= $this->editable;
			$record["inlineedit_link"]= $this->editable;
			$record["view_link"]= true;
			$record["copy_link"]= true;
			
			//	detail tables
			$this->proccessDetailGridInfo($record, $data);
			
			//	key fields
			$keyblock = "";
			$editlink = "";
			$copylink = "";
			$keylink = "";
			$tKeys = GetTableKeys($this->tName);
			
			for($i = 0; $i < count($tKeys); $i ++) {
				if($i != 0) {
					$keyblock.= "&";
					$editlink.= "&";
					$copylink.= "&";
				}
				$keyblock.= rawurlencode($data[$tKeys[$i]]);
				$editlink.= "editid".($i + 1)."=".htmlspecialchars(rawurlencode($data[$tKeys[$i]]));
				$copylink.= "copyid".($i + 1)."=".htmlspecialchars(rawurlencode($data[$tKeys[$i]]));
				$keylink.= "&key".($i + 1)."=".htmlspecialchars(rawurlencode(@$data[$tKeys[$i]]));
			}
			
			$record["editlink_attrs"]= "href='".$this->shortTableName."_edit.php?".$editlink."' id=\"editlink".$this->recId."\"";
			$record["inlineeditlink_attrs"]= "href='".$this->shortTableName."_edit.php?".$editlink."' onclick=\"return inlineEditing".$this->id.".inlineEdit('".$this->recId."','".$editlink."');\" id=\"ieditlink".$this->id."_".$this->recId."\"";
			$record["copylink_attrs"]= "href='".$this->shortTableName."_add.php?".$copylink."' id=\"copylink".$this->recId."\"";
			$record["viewlink_attrs"]= "href='".$this->shortTableName."_view.php?".$editlink."' id=\"viewlink".$this->recId."\"";
			
			$this->fillCheckAttr($record, $data, $keyblock);
			
			for($i = 0; $i < count($this->listFields); $i ++) 
				$record[GoodFieldName($this->listFields[$i]['fName'])."_value"] = $this->proccessRecordValue($data, $keylink, $this->listFields[$i]);
						
			if(function_exists("BeforeMoveNextList"))
				BeforeMoveNextList($data, $row, $record);
			
			$this->addJSCodeAfterRecordEdited();
			
			$this->addSpansForGridCells($record, $data);
			
			if($col < $this->colsOnPage)
				$record["endrecord_block"]= true;
			$record["grid_recordheader"]= true;
			$record["grid_vrecord"]= true;
			$row["grid_record"]["data"][]= $record;
			
			$data = $this->beforeProccessRow();
			
			$this->recNo ++;
			
			while($col <= $this->colsOnPage) {
				$record = array();
				if($col < $this->colsOnPage)
					$record["endrecord_block"]= true;
				$row["grid_record"]["data"][]= $record;
				$col ++;
			}
			//	assign row spacings for vertical layout
			$row["grid_rowspace"]= true;
			$row["grid_recordspace"]= array("data" => array());
			for($i = 0; $i < $this->colsOnPage * 2 - 1; $i ++)
				$row["grid_recordspace"]["data"][]= true;
			
			$rowinfo["data"][]= $row;
		}
		
		if(count($rowinfo["data"])) {
			$rowinfo["data"][count($rowinfo["data"]) - 1]["grid_rowspace"]= false;
			
			if($this->isVerLayout && $this->is508)
				$rowinfo["begin"]= "<caption style=\"display:none\">Table data</caption>";
			
			$this->xt->assignbyref("grid_row", $rowinfo);
		}
		
		$this->buildTotals($totals);
	
	}
	
	/**
	 * Counts totals, depending on theirs type
	 *
	 * @param array $totals
	 * @param array $data
	 */
	function countTotals(&$totals, &$data) 
	{
		for($i = 0; $i < count($this->totalsFields); $i ++) 
		{
			if($this->totalsFields[$i]['totalsType'] == 'COUNT') 
				$totals[$this->totalsFields[$i]['fName']]+=($data[$this->totalsFields[$i]['fName']]!= "");
			else if($this->totalsFields[$i]['viewFormat'] == "Time") 
			{
				$time = array(0 => 0, 1 => 0, 2 => 0);
				$time = GetTotalsForTime($data[$this->totalsFields[$i]['fName']], $time);
				$hor = $time[0];
				$min = $time[1];
				$sec = $time[2];
				if($totals[$this->totalsFields[$i]['fName']])
				{
					$fName = GetTotalsForTime($totals[$this->totalsFields[$i]['fName']], $time);
					$hor += $fName[0];
					$min += $fName[1];
					$sec += $fName[2];
				}
				$total =($hor == 0 ? '00' : $hor).':'.($min > 9 ? $min :($min == 0 ? '00' : '0'.$min)).':'.($sec > 9 ? $sec :($sec == 0 ? '00' : '0'.$sec));
				$totals[$this->totalsFields[$i]['fName']]= $total;
			} 
			else 
				$totals[$this->totalsFields[$i]['fName']]+=($data[$this->totalsFields[$i]['fName']]+ 0);
		}
	}
	/**
	 * Build and shows totals info on page
	 *
	 * @param array $totals info abount totals
	 */
	function buildTotals(&$totals) {
		if(count($this->totalsFields)) 
		{
			//	process totals
			$this->xt->assign("totals_row", true);
			$totals_records = array("data" => array());
			for($i = 0; $i < $this->colsOnPage; $i ++) 
			{
				$record = array();
				if($i == 0) 
				{
					for($i = 0; $i < count($this->totalsFields); $i ++) 
					{
						//	show totals
						$total = GetTotals($this->totalsFields[$i]['fName'], $totals[$this->totalsFields[$i]['fName']], $this->totalsFields[$i]['totalsType'], $this->recNo - 1, $this->totalsFields[$i]['viewFormat']);						
						if($this->isUseInlineJs)
							$total = "<span id=\"total".$this->id.GoodFieldName($this->totalsFields[$i]['fName']).$this->id."\" type=\"".$this->totalsFields[$i]['totalsType']."\" format=\"".$this->totalsFields[$i]['viewFormat']."\">".$total."</span>";
						
						$this->xt->assign(GoodFieldName($this->totalsFields[$i]['fName'])."_total", $total);
						$record[GoodFieldName($this->totalsFields[$i]['fName'])."_showtotal"]= true;
					}
				}
				if($i < $this->colsOnPage - 1)
					$record["endrecordtotals_block"]= true;
				$totals_records["data"][]= $record;
			}
			$this->xt->assignbyref("totals_record", $totals_records);			
		}
	}
	
	/**
	 * Proccess grid cells, also add spans if need them
	 *
	 * @param array $record
	 * @param array $data
	 */
	function addSpansForGridCells(&$record, &$data) 
	{
		if($this->isUseInlineEdit) 
		{
			for($i=0;$i<count($this->listFields);$i++) 
			{
				$fName = GoodFieldName($this->listFields[$i]['fName']);
				$cleanvalue = $record[$fName."_value"];
				$span = "<span ";
				$span.= "id=\"edit".$this->recId."_".$fName."\" ";
				if(! IsBinaryType(GetFieldType($this->listFields[$i]['fName'], $this->tName))) 
					$span.= "val=\"".htmlspecialchars($data[$this->listFields[$i]['fName']])."\" ";
				$span.= ">";
				$record[$fName."_value"]= $span.$record[$fName."_value"]."</span>";
				if(! strlen($cleanvalue))
					$record[$fName."_value"].= "&nbsp;";
			}
		} else {
			for($i = 0; $i < count($this->listFields); $i ++) 
			{
				$fName = GoodFieldName($this->listFields[$i]['fName']);
				if(! strlen($record[$fName."_value"]))
					$record[$fName."_value"]= "&nbsp";
			}
		}
	}
	
	/**
	 * Proccess record values
	 *
	 * @param array $record
	 * @param array $data
	 * @param string $keylink
	 */
	function proccessRecordValue(&$data, &$keylink, $listFieldInfo)
	{		
		$value = "";
		if($listFieldInfo['viewFormat'] == FORMAT_DATABASE_IMAGE) 
		{			
			if(ShowThumbnail($listFieldInfo['fName'], $this->tName)) 
			{
				
				$thumbPref = GetThumbnailPrefix($listFieldInfo['fName'], $this->tName);
				$value.= "<a";
				if(IsUseiBox($listFieldInfo['fName'], $this->tName))
					$value.= " rel='ibox'";
				else
					$value.= " target=_blank";
				
				$value.= " href='".$this->shortTableName."_imager.php?field=".rawurlencode($listFieldInfo['fName']).$keylink."'>";
				$value.= "<img border=0";
				if($this->is508)
					$value.= " alt=\"Image from DB\"";
				$value.= " src='".$this->shortTableName."_imager.php?field=".rawurlencode($thumbPref)."&alt=".rawurlencode($listFieldInfo['fName']).$keylink."'>";
				$value.= "</a>";
			} 
			else 
			{
				$value = "<img";
				if($this->is508)
					$value.= " alt=\"Image from DB\"";
				
				$imgWidth = GetImageWidth($listFieldInfo['fName'], $this->tName);
				$value.=($imgWidth ? " width=".$imgWidth : "");
				
				$imgHeight = GetImageHeight($listFieldInfo['fName'], $this->tName);
				$value.=($imgHeight ? " height=".$imgHeight : "");
				
				$value.= " border=0";
				$value.= " src='".$this->shortTableName."_imager.php?field=".rawurlencode($listFieldInfo['fName']).$keylink."'>";
			}
		} 
		else if($listFieldInfo['viewFormat'] == FORMAT_FILE_IMAGE) 
		{
			if(CheckImageExtension($data[$listFieldInfo['fName']])) 
			{
				if(ShowThumbnail($listFieldInfo['fName'], $this->tName)) 
				{
					// show thumbnail
					$thumbPref = GetThumbnailPrefix($listFieldInfo['fName'], $this->tName);
					$thumbname = $thumbPref.$data[$listFieldInfo['fName']];
					if(substr(GetLinkPrefix($listFieldInfo['fName'], $this->tName), 0, 7) != "http://" && ! myfile_exists(GetUploadFolder($listFieldInfo['fName']).$thumbname))
						$thumbname = $data[$listFieldInfo['fName']];
					$value = "<a";
					if(IsUseiBox($listFieldInfo['fName'], $this->tName))
						$value.= " rel='ibox'";
					else
						$value.= " target=_blank";
					
					$value.= " href=\"".htmlspecialchars(AddLinkPrefix($listFieldInfo['fName'], $data[$listFieldInfo['fName']]))."\">";
					$value.= "<img";
					if($thumbname == $data[$listFieldInfo['fName']]) {
						$imgWidth = GetImageWidth($listFieldInfo['fName'], $this->tName);
						$value.=($imgWidth ? " width=".$imgWidth : "");
						
						$imgHeight = GetImageHeight($listFieldInfo['fName'], $this->tName);
						$value.=($imgHeight ? " height=".$imgHeight : "");
					}
					
					$value.= " border=0";
					if($this->is508)
						$value.= " alt=\"".htmlspecialchars($data[$listFieldInfo['fName']])."\"";
					$value.= " src=\"".htmlspecialchars(AddLinkPrefix($listFieldInfo['fName'], $thumbname))."\"></a>";
				} 
				else 
				{
					$value = "<img";
					
					$imgWidth = GetImageWidth($listFieldInfo['fName'], $this->tName);
					$value.=($imgWidth ? " width=".$imgWidth : "");
					
					$imgHeight = GetImageHeight($listFieldInfo['fName'], $this->tName);
					$value.=($imgHeight ? " height=".$imgHeight : "");
					
					$value.= " border=0";
					if($this->is508)
						$value.= " alt=\"".htmlspecialchars($data[$listFieldInfo['fName']])."\"";
					$value.= " src=\"".htmlspecialchars(AddLinkPrefix($listFieldInfo['fName'], $data[$listFieldInfo['fName']]))."\">";
				}
			}
		} 
		else if($listFieldInfo['viewFormat'] == FORMAT_DATABASE_FILE) 
		{
			
			$fileNameF = GetFilenameField($listFieldInfo['fName'], $this->tName);
			if($fileNameF) {
				$filename = $data[$fileNameF];
				if(! $filename)
					$filename = "file.bin";
			} else {
				$filename = "file.bin";
			}
			if(strlen($data[$listFieldInfo['fName']])) {
				$value = "<a href='".$this->shortTableName."_getfile.php?filename=".rawurlencode($filename)."&field=".rawurlencode($listFieldInfo['fName']).$keylink."'>";
				$value.= htmlspecialchars($filename);
				$value.= "</a>";
			}
		} 
		else if(($listFieldInfo['editFormat'] == EDIT_FORMAT_LOOKUP_WIZARD || $listFieldInfo['editFormat'] == EDIT_FORMAT_RADIO) && GetLookupType($listFieldInfo['fName'], $this->tName) == LT_LOOKUPTABLE && GetLWLinkField($listFieldInfo['fName'], $this->tName) != GetLWDisplayField($listFieldInfo['fName'], $this->tName)) 
		{
			$value = DisplayLookupWizard($listFieldInfo['fName'], $data[$listFieldInfo['fName']], $data, $keylink, MODE_LIST);
		} 
		else if(NeedEncode($listFieldInfo['fName'], $this->tName)) 
		{				
			$value = ProcessLargeText(GetData($data, $listFieldInfo['fName'], $listFieldInfo['viewFormat']), "field=".rawurlencode($listFieldInfo['fName']).$keylink, "", MODE_LIST);
		} 
		else 
		{
			$value = GetData($data, $listFieldInfo['fName'], $listFieldInfo['viewFormat']);
		}
		return $value;
	}
	
	
	/**
	 * Proccess master-details on list grid
	 *
	 * @param array $record
	 * @param array $data
	 */
	function proccessDetailGridInfo(&$record, &$data)
	{
		$allDetailsTablesArr = GetDetailTablesArr($this->tName);
		for($i = 0; $i < count($allDetailsTablesArr); $i ++) 
		{
			
			// field names of master keys of current table for passed details table name
			$masterKeys = GetMasterKeysByDetailTable($allDetailsTablesArr[$i]['dDataSourceTable'], $this->tName);
			// field names of detail keys of passed detail table, when current is master
			$detailKeys = GetDetailKeysByDetailTable($allDetailsTablesArr[$i]['dDataSourceTable'], $this->tName);
			//$masterquery = "mastertable=".$allDetailsTablesArr[$i]['dDataSourceTable'];
			$masterquery = "mastertable=".rawurlencode($this->dataSourceTable);
			
			$detailid = array();
			foreach($masterKeys as $idx => $m) 
			{
				$d = $detailKeys[$idx];
				$masterquery.= "&masterkey".($idx + 1)."=".rawurlencode($data[$m]);
				$detailid[]= make_db_value($d, $data[$m], "", "", $allDetailsTablesArr[$i]['dDataSourceTable']);
			}
		
			//	add count of child records to SQL
			if($allDetailsTablesArr[$i]['dispChildCount']|| $allDetailsTablesArr[$i]['hideChild']) 
			{
				if(! $this->subQueriesSupp || ! $this->subQueriesSupAccess || $this->checkDetailAndMasterFieldTypes()) 
					$this->countDetailsRecsNoSubQ($allDetailsTablesArr[$i], $data, $detailid);
			}
			
			//detail tables			
			$record[$allDetailsTablesArr[$i]['dShortTable']."_dtable_link"]=($this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['add'] || $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['search']);
			$record[$allDetailsTablesArr[$i]['dShortTable']."_dtablelink_attrs"]= "href=\"".$allDetailsTablesArr[$i]['dShortTable']."_list.php?".$masterquery."\" id=\"master_".$allDetailsTablesArr[$i]['dShortTable'].$this->recId."\"";
			$dpreview = true;
			
			if($allDetailsTablesArr[$i]['dispChildCount']) 
			{
				if($data[$allDetailsTablesArr[$i]['dShortTable']."_cnt"]+ 0)
					$record[$allDetailsTablesArr[$i]['dShortTable']."_childcount"]= true;
				else
					$dpreview = false;
				$record[$allDetailsTablesArr[$i]['dShortTable']."_childnumber"]= $data[$allDetailsTablesArr[$i]['dShortTable']."_cnt"];
			}
			
			if($allDetailsTablesArr[$i]['hideChild']) 
			{
				if(!($data[$allDetailsTablesArr[$i]['dShortTable']."_cnt"]+ 0)) 
				{
					$record[$allDetailsTablesArr[$i]['dShortTable']."_dtable_link"]= false;
					$dpreview = false;
				}
			}
			
			if($dpreview) 
			{
				if(GetDPType($this->tName) == DP_POPUP) 
					$record[$allDetailsTablesArr[$i]['dShortTable']."_dtablelink_attrs"].= " onmouseover=\"RollDetailsLink.showPopup(this,'".$allDetailsTablesArr[$i]['dShortTable']."_detailspreview.php'+this.href.substr(this.href.indexOf('?')));\" onmouseout=\"RollDetailsLink.hidePopup();\"";
				
				if(GetDPType($this->tName) == DP_INLINE) 
				{					
					$record[$allDetailsTablesArr[$i]['dShortTable']."_preview_link"] = ($this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['add'] || $this->permis[$allDetailsTablesArr[$i]['dDataSourceTable']]['search']);
					$record[$allDetailsTablesArr[$i]['dShortTable']."_previewlink_attrs"] = 
						"id = \"".$allDetailsTablesArr[$i]['dShortTable']."_preview".$this->recId."\" 
						onclick = \"dpInline".$this->id.".showDPInline('".$allDetailsTablesArr[$i]['dShortTable']."',".$this->recId."); return false;\"
						href = \"".$allDetailsTablesArr[$i]['dShortTable']."_list.php?".$masterquery."\"";
				}
			}
		}
	}
	
	
	
	/**
	 * Use for count details recs number, if subQueryes not supported, or keys have different types
	 *
	 * @param array $detailTableInfo
	 * @param array $data
	 * @param array $detailid
	 */
	function countDetailsRecsNoSubQ(&$detailTableInfo, &$data, &$detailid) 
	{
		$sqlHead = $detailTableInfo['sqlHead'];
		$sqlFrom = $detailTableInfo['sqlHead'];
		$sqlWhere = $detailTableInfo['sqlHead'];
		$sqlTail = $detailTableInfo['sqlTail'];
		
		$securityClause = SecuritySQL("Search", $detailTableInfo['dDataSourceTable']);
		// add where 
		if(strlen($securityClause))
			$sqlWhere.= whereAdd("", $securityClause);
		
		$masterwhere = "";
		foreach($masterKeys as $idx => $val) {
			if($masterwhere)
				$masterwhere.= " and ";
			$masterwhere.= GetFullFieldName($detailKeys[$idx], $detailTableInfo['dDataSourceTable'])."=".$detailid[$idx];
		}
		$data[$detailTableInfo['dDataSourceTable']."_cnt"]= gSQLRowCount_int($this->sqlHead, $this->sqlFrom, $sqlWhere, $this->sqlTail, $masterwhere, $detailTableInfo['groupBy']);
	}
	
	/**
	 * Check details and master tables field for types.They must be the same type.
	 * return true if they are same or if database is mySQL, otherwise returns false
	 * @return bool
	 */
	function checkDetailAndMasterFieldTypes() {
		if($this->dbType == nDATABASE_MySQL) {
			return false;
		} else {
			
			// all details tables for which current table is master
			$allDetailsTablesArr = GetDetailTablesArr($this->tName);
			for($i = 0; $i < count($allDetailsTablesArr); $i ++) {
				// field names of master keys of current table for passed details table name
				$masterKeys = GetMasterKeysByDetailTable($allDetailsTablesArr[$i]['dDataSourceTable'], $this->tName);
				// field names of detail keys of passed detail table, when current is master
				$detailKeys = GetDetailKeysByDetailTable($allDetailsTablesArr[$i]['dDataSourceTable'], $this->tName);
				
				foreach($masterKeys as $idx => $val) {
					// get field types
					$masterFieldType = GetFieldType($masterKeys[$idx]);
					$detailsFieldType = GetFieldType($detailKeys[$idx], $allDetailsTablesArr[$i]['dDataSourceTable']);
					// if different data types we can't use subQ
					if($masterFieldType != $detailsFieldType)
						return true;
				
				}
			}
			return false;
		}
	}
	/**
	 * Check is need to create menu
	 *
	 */
	function isCreateMenu()
	{
		if(!$this->isCreateMenu) 
		{
			foreach($this->menuTablesArr as $menuTable) 
			{				
				if($this->permis[$menuTable['dataSourceTName']]['add'] || $this->permis[$menuTable['dataSourceTName']]['search'])
				{
					$this->isCreateMenu = true;
					break;
				}
			}
		}
		
		return $this->isCreateMenu;
	}
	/**
	 * Checks if need to display grid
	 *
	 */
	function isDispGrid() 
	{
		if(!$this->isDispGrid)
		{
			if(!$this->isUseInlineAdd)
				$this->isDispGrid = $this->permis[$this->tName]['search']&& $this->rowsFound;
			else
				$this->isDispGrid = $this->permis[$this->tName]['add']|| $this->permis[$this->tName]['search']&& $this->rowsFound;
		}
		return $this->isDispGrid;
	}
	
	// stroit checkbox, esli eto vozmogno
	function fillCheckAttr(&$record, $data, $keyblock)
	{
		$record["checkbox"]= $this->editable;
		if($this->exportTo || $this->printFriendly) {
			if($this->permis[$this->tName]['export']|| $this->permis[$this->tName]['delete'])
				$record["checkbox"]= true;
		}
		$record["checkbox_attrs"]= "name=\"selection[]\" value=\"".$keyblock."\" id=\"check".$this->id."_".$this->recId."\"";
	}
	
	function addJSCodeAfterRecordEdited() 
	{
		return true;
	}
		
	function addDivSearchWin()
	{
		return '<div id="searchWin'.$this->id.'" class="searchWin"></div>';
	}
	
	/**
	 * Main function, call to build page
	 * Do not change methods call oreder!!
	 *
	 */
	function prepareForBuildPage() 
	{
		//Sorting fields
		$this->buildOrderParams();
		// delete record
		$this->deleteRecords();
		// PRG rule, to avoid POSTDATA resend
		$this->rulePRG();		
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
		// build search panel
		if ($this->permis[$this->tName]["search"])
			$this->buildSearchPanel("adv_search_panel");
		// add common js code
		$this->addCommonJs();
		// add common html code
		$this->addCommonHtml();
		// Set common assign
		$this->commonAssign();
		// build admin block		
		$this->assignAdmin();
		// create old version menu
		$this->createOldMenu();		
	}
	
	/**
	 * Static function for create list page
	 * Read params from setting 
	 * Create object of class in accordance with mode displaying page 
	 */
	function createListPage($table,$options)
	{
		global $bSubqueriesSupported, $strTableName, $conn;//$strOriginalTableName, $gPageSize, $g_orderindexes, $gstrOrderBy
		$params = array();
		$params = $options;
		$params['origTName'] = GetTableData($table,".OriginalTable",'');//$strOriginalTableName;
		$params['sessionPrefix'] = $strTableName;
		$params['tName'] = $strTableName;
		$params['conn'] = $conn;
		$params['gPageSize'] = GetTableData($table,".pageSize",0);//$gPageSize;
		$params['g_orderindexes'] = GetTableData($table,".orderindexes",array());//$g_orderindexes;
		$params['gstrOrderBy'] = GetTableData($table,".strOrderBy",'');//$gstrOrderBy;
		$params['gsqlHead'] = GetTableData($table,".sqlHead",'');
		$params['gsqlFrom'] = GetTableData($table,".sqlFrom",'');
		$params['gsqlWhereExpr'] = GetTableData($table,".sqlWhereExpr",'');
		$params['gsqlTail'] = GetTableData($table,".sqlTail",'');
		$params['locale_info']=&$locale_info;
		$params["subQueriesSupp"] = $bSubqueriesSupported; 
		$params['shortTableName'] = GetTableData($table,".shortTableName",'');
		$params['dataSourceTable'] = GetTableData($table,".dataSourceTable",'');
		$params['strCaption'] = GetTableData($table,".strCaption",'');
		$params['nSecOptions'] = GetTableData($table,".nSecOptions",0);
		$params['nLoginMethod'] = GetTableData($table,".nLoginMethod",0);
		$params['recsPerRowList'] = GetTableData($table,".recsPerRowList",0);	
		$params['tableGroupBy'] = GetTableData($table,".tableGroupBy",'');
		$params['dbType'] = GetTableData($table,".dbType",0);
		$params['mainTableOwnerID'] = GetTableData($table,".mainTableOwnerID",'');
		$params['exportTo'] = GetTableData($table,".exportTo",false);
		$params['printFriendly'] = GetTableData($table,".printFriendly",false);
		$params['rowHighlite'] = GetTableData($table,".rowHighlite",false);
		$params["delFile"] = GetTableData($table,".delFile",false);
		$params["isGroupSecurity"] = GetTableData($table,".isGroupSecurity",false);
		$params['arrKeyFields'] = GetTableData($table,".arrKeyFields",array());
		//$params["basicSearchFieldsArr"]=GetTableData($table,".advSearchFieldsArr",array());
		$params["useIbox"] = GetTableData($table,".useIbox",false);
		$params["useDetailsPreview"] = GetTableData($table,".useDetailsPreview",false);	
		$params["isUseInlineAdd"] = GetTableData($table,".isUseInlineAdd",false);
		$params["isUseInlineEdit"] = GetTableData($table,".isUseInlineEdit",false);
		$params["isUseInlineJs"] = $params["isUseInlineAdd"] || $params["isUseInlineEdit"];
//		$params["defaultUrl"] = GetTableData($table,".defaultUrl",'');
		$params["advSearchFieldsArr"] = GetTableData($table,".advSearchFieldsArr",array());
		$params["isDynamicPerm"] = GetTableData($table,".isDynamicPerm",false);
		$params['isAddWebRep'] = GetTableData($table,".isAddWebRep",false);
		$params['isVerLayout'] = GetTableData($table,".isVerLayout",false);
		$params['isDisplayLoading'] = GetTableData($table,".isDisplayLoading",false);
		$params['createLoginPage'] = GetTableData($table,".createLoginPage",false);
		$params['menuTablesArr'] = GetTableData($table,".menuTablesArr",array());	
		$params['subQueriesSupAccess'] = GetTableData($table,".subQueriesSupAccess",false);	 
		$params['noRecordsFirstPage'] = GetTableData($table,".noRecordsFirstPage",false);
		$params['totalsFields'] = GetTableData($table,".totalsFields",array());
		$params['isUseAjaxSuggest'] = GetTableData($table, ".isUseAjaxSuggest", true);
		//$params['resizableColumns'] = GetTableData($table,".resizableColumns",false);
		//$params['resizableColumns'] = true;
		
				$params['audit'] = new AuditTrailTable();
		
		$params['listFields'] = array();
		$allfields = GetFieldsList($table);
		foreach($allfields as $f)
		{
			if(!GetFieldData($table,$f,"bListPage",false))
				continue;
			$params['listFields'][]= array(
				"fName"=>$f,
				"viewFormat"=>GetFieldData($table,$f,"ViewFormat",""),
				"editFormat"=>GetFieldData($table,$f,"EditFormat","")
			);
		}
		$params["setLangParams"] = GetTableData($table,".setLangParams",false);
		
		// choose class by mode
		if ($params["mode"]==LIST_SIMPLE)
			$pageObject = new ListPage_Simple($params);	
		else if($params["mode"]==LIST_LOOKUP)
			$pageObject = new ListPage_Lookup($params);
		else if($params["mode"]==DP_INLINE)
			$pageObject = new ListPage_DPInline($params);
		else if($params["mode"]==RIGHTS_PAGE)
			$pageObject = new RightsPage($params);	
		else if($params["mode"]==MEMBERS_PAGE)
			$pageObject = new MembersPage($params);
			
		return $pageObject;
	}
	/**
	 * Check is current table is admin table
	 *
	 * @return bool
	 */
	function isAdminTable()
	{
		return $this->dataSourceTable === 'admin_rights' || $this->dataSourceTable === 'admin_members' || $this->dataSourceTable === 'admin_users';
	}
	
}

?>
