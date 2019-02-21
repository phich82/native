/**
 * Search form with user interface. 
 * 
 */
Runner.search.SearchFormWithUI = Runner.extend(Runner.search.SearchForm, {	  
    /**
    * Options panel show status indicator
    * @type Boolean
    */
    srchOptShowStatus: false,
    /**
    * Search win show status indicator
    * @type Boolean
    */
    srchWinShowStatus: false,
    /**
    * Show status indicator of div, which contains add filter buttons
    * @type Boolean
    */
    ctrlChooseMenuStatus: false,
    /**
    * Show status indicator of search type combos
    * @type Boolean
    */
    ctrlTypeComboStatus: false,
    /**
    * jQuery obj of search options div
    * @type {obj}
    */
    srchOptDiv: null,
    /**
    * jQuery object of img-button options panel expander
    * @type {obj}
    */
    srchOptExpander: null,
    /**
    * jQuery object of img-button search win expander
    * @type {obj}
    */
    srchWinExpander: null,
    /**
     * jQuery object with div, that contains all search elements
     * @type {obj}
     */
    srchBlock: null,
    /**
     * jQuery object with div, that contains all search controls
     * @type {obj}
     */
    srchCtrlsBlock: null,
    /**
    * Show status indicator of search block
    * @type Boolean
    */
    srchBlockStatus: true,
    
    /**
     * jQuery obj of top div with radio conditions
     * @type {obj}
     */
    topCritCont: null,
    
    /**
    * jQuery object of div with add-filter buttons
    * @type {obj}
    */
    ctrlChooseMenuDiv: null,
    /**
    * jQuery object of div with basic search controls
    * @type {obj}
    */
    srchPanelHeader: null,
    /**
     * jQuery object of div where panel should be placed. Used to toggle window and panel mode
     * @type {obj}
     */
    panelContainer: null,
    /**
     * jQuery object of div with b tags at bottom of the panel. Used on some layouts
     * for example on Madrid
     * @type {obj} 
     */
    bottomPanelRound: null,
    /**
     * jQuery obj of bottom search button
     * @type {obj} 
     */
    bottomSearchButt: null,
    /**
    * Img src attr for minus button
    * @type String
    */
    minusSrc: "images/search/minus.gif",
    /**
    * Img src attr for plus button
    * @type String
    */
    plusSrc: "images/search/plus.gif",
    /**
    * Img src attr for hide opt
    * @type String
    */
    hideOptSrc: "images/search/hideOptions.gif",
    /**
    * Img src attr for show opt
    * @type String
    */
    showOptSrc: "images/search/showOptions.gif",
    /**
	 * Search panel icon switcher text
	 * @type 
	 */
    showOptText: "Show search options",
    /**
	 * Search panel icon switcher text
	 * @type 
	 */
    hideOptText: "Hide search options",
    /**
	 * Search type combos switcher text
	 * @type 
	 */
	showComboText: 'Show options',
	/**
	 * Search type combos switcher text
	 * @type 
	 */
	hideComboText: 'Hide options',
    /**
    * Array of search type combos
    * @type {array}
    */
    searchTypeCombosArr: null,
    /**
    * Array of search type combos
    * appear in window mode
    * @type {array}
    */
    searchTypeCombosWinArr: null,
    /**
     * Array of divs, that used as containers for one search control with its combos, delete buttons etc.
     * @type {array}
     */
    srchFilterRowArr: null,
    /**
     * Array of trs, that used as containers for one search control with its combos, delete buttons etc.
     * Trs appear in window mode
     * @type {array}
     */
    srchFilterRowWinArr: null,
    /**
     * Array of field names
     * @type array
     */
    fNamesArr: null,
    /**
    * ctrls map. Used for indicate which index conected with which search ctrl
    * @type obj
    */    
    ctrlsShowMap: null,
    /**
     * jQuery obj of link-switcher. Toggles search type combos
     * @type obj
     */
    showHideSearchComboButton: null,
    /**
     * Iframe object used for control choose menu coverage in IE6
     * @type {object}
     */
    iframe: null,
    /**
     * Hider object, hide selects in fly div mode
     * @type 
     */
    hider: null,
    /**
     * Fly div id, do not use controller id, to prevent fly div collisions
     * @type Number
     */
    flyDivId: 0,
    /**
     * True if records_block div margin-left was change, to prevent grid coverage
     * @type Boolean
     */
    recBlockMargChange: false,
	/**
    * Constructor
    * @param {obj} cfg
    */
    constructor: function(cfg) {
    	// recreate objects
        this.searchTypeCombosArr = [];
        this.searchTypeCombosWinArr = [];
        this.fNamesArr = [];
        this.srchFilterRowArr = [];
        this.srchFilterRowWinArr = [];
        //call parent
        Runner.search.SearchFormWithUI.superclass.constructor.call(this, cfg);
        // -------------------stuf used only when in panel mode------------------
        // private jQuery obj
        this.srchOptDiv = $("#searchOptions" + this.id);
        this.srchOptExpander = $("#showOptPanel" + this.id);
        this.srchWinExpander = $("#showSrchWin" + this.id);        
        this.ctrlChooseMenuDiv = $("#controlChooseMenu" + this.id);

        // div container with all search stuff
        var srchBlockId = 'search_block'+this.id;
        this.srchBlock = $("#"+srchBlockId);
        // div object with all controls        
        var srchCtrlsBlockId = 'controlsBlock_'+this.id;
        this.srchCtrlsBlock = $("#"+srchCtrlsBlockId);
        // table object with all controls fpr window       
        var srchCtrlsBlockWinId = 'controlsBlock_'+this.id+'_win';
        this.srchCtrlsBlockWin = $("#"+srchCtrlsBlockWinId);
        // add object with basic search controls
        var srchPanelHeaderId = 'searchPanelHeader'+this.id;
        this.srchPanelHeader = $("#"+srchPanelHeaderId);
        
        var showHideSearchComboButtonId = 'showHideSearchType'+this.id;
        this.showHideSearchComboButton = $('#'+showHideSearchComboButtonId); 
        // container where panel placed
        var panelContainerId = 'searchPanelContainer'+this.id;
        this.panelContainer = $('#'+panelContainerId);
        // for some layouts bottom panel round should handled by this class
        var bottomPanelRoundId = 'searchPanelBottomRound'+this.id;
        this.bottomPanelRound = $('#'+bottomPanelRoundId);
        
        this.ctrlChooseMenuDiv.appendTo(document.body);
        
        this.addDelegatedEvets();
        // need to prevent bad rendering of display: none combos
        if (Runner.isIE){        	
        	if ($("#flycontents"+this.id).length){
        		this.srchOptDiv.appendTo($("#flycontents"+this.id));          		
        	}else{
	        	var prevSrchPanelStatus = this.srchOptShowStatus;
	        	this.showSearchWin(undefined, this.id);
	        	this.hideSearchWin(undefined, this.id);
	        	// to prevent opening srch panel, if it was hidden
	        	if (!prevSrchPanelStatus){
	        		this.hideSearchOptions();	
	        	}  
        	}
        }       
    },
    
    /**
     * Binds hover events for table and div. 
     * Use parent containers as delegates
     * Call it in constructor
     */
    addDelegatedEvets: function(){
    	// for event handlers closures
    	var controller = this;
    	// filter div row mouseover event
    	this.srchCtrlsBlock.bind('mouseover', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target && target.id != controller.srchCtrlsBlock.attr('id')) {
				if(target.id && target.id.indexOf('filter_') != -1) {
					// show del image
					$(target).find('img[@class=searchPanelButton]').css('visibility', 'visible');	
					$(target).removeClass('blockBorder').addClass('blockBorderHovered');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	// filter div row mouseout event
    	this.srchCtrlsBlock.bind('mouseout', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target && target.id != controller.srchCtrlsBlock.attr('id')) {
				if(target.id && target.id.indexOf('filter_') != -1) {
					// hide del image
					$(target).find('img[@class=searchPanelButton]').css('visibility', 'hidden');	
					$(target).removeClass('blockBorderHovered').addClass('blockBorder');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	
    	// border hover events
    	this.srchCtrlsBlockWin.bind('mouseover', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target.id != controller.srchCtrlsBlockWin.attr('id')) {				
				if(target.nodeName == "TD") {
					// get row with all tds
					var tr = $(target).parent();
					// show del image
					tr.find('img[@class=searchPanelButton]').css('visibility', 'visible');	
					// all cells
					var tds = tr.children();
					// if second ctrldoesn't exist or is hidden, make right border for last-1 child
	    			var lastVisible = tds.length-1;    		
	    			//console.log($(tds[tds.length-1]).children(), 'child');
	    			if ($(tds[tds.length-1]).children().length === 0 || $(tds[tds.length-1]).find('*:first-child').css('display') == 'none'){
	    				lastVisible--;
	    			}    			
	    			// set style for left element
	    			$(tds[0]).removeClass('cellBorderCenter').removeClass('cellBorderLeft').addClass('cellBorderCenterHovered').addClass('cellBorderLeftHovered');
	    			// set styles for center elements
	    			for(var i=0;i<lastVisible;i++){
	    				// try to remove also right style, because it may come when second ctrl was invisible
	    				$(tds[i]).removeClass('cellBorderCenter').removeClass('cellBorderRightHovered').addClass('cellBorderCenterHovered');
	    			}    
	    			//set style for last elem
	    			$(tds[lastVisible]).removeClass('cellBorderCenter').removeClass('cellBorderRight').addClass('cellBorderCenterHovered').addClass('cellBorderRightHovered');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	this.srchCtrlsBlockWin.bind('mouseout', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target.id != controller.srchCtrlsBlockWin.attr('id')) {				
				if(target.nodeName == "TD") {
					// get row with all tds
					var tr = $(target).parent();
					// show del image
					tr.find('img[@class=searchPanelButton]').css('visibility', 'hidden');	
					// all cells
					var tds = tr.children();
					// if second ctrldoesn't exist or is hidden, make right border for last-1 child
	    			var lastVisible = tds.length-1;
	    			if ($(tds[tds.length-1]).children().length === 0 || $(tds[tds.length-1]).find('*:first-child').css('display') == 'none'){
	    				lastVisible--;
	    			}
	    			// set style for left element
	    			$(tds[0]).removeClass('cellBorderCenterHovered').removeClass('cellBorderLeftHovered').addClass('cellBorderCenter').addClass('cellBorderLeft');
	    			// set styles for center elements
	    			for(var i=0;i<lastVisible;i++){
	    				$(tds[i]).removeClass('cellBorderCenterHovered').addClass('cellBorderCenter');
	    			}
	    			//set style for last elem
	    			$(tds[lastVisible]).removeClass('cellBorderCenterHovered').removeClass('cellBorderRightHovered').addClass('cellBorderCenter').addClass('cellBorderRight');
					break;
				} else {
					target = target.parentNode;
				}
			}	
    	});  
    },
    /**
     * Return search type combo container id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getComboContId: function(fName, ind, isWin){    	
    	return "searchType_" + ind + "_" + Runner.goodFieldName(fName) + (isWin ? '_win' : '');
    },    
    /**
     * Return filter div id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getFilterDivId: function(fName, ind, isWin){    	
    	return "filter_" + ind + "_" + Runner.goodFieldName(fName) + (isWin ? '_win' : '');
    },
    /**
     * Recalc window dimension after change content
     * @param {object} winObj
     * @return {Boolean} true if success
     */
	recalcWindowDim: function(winObj){
		// if no window, return false
		if (!this.srchWinShowStatus){
			return false;
		}
		// if window object not passed, get it
		winObj = (winObj ? winObj : $("#fly"+this.id));		
		// recalc
    	var x = winObj.css('top'), y = winObj.css('left');
    	var flyDivDimAndCoorsObj = getFlyDivSizeAndCoors(this.id, x, y);
   		setFlyDivDimAndCoors(flyDivDimAndCoorsObj, this.id);
        
   		return true;
	},
    /**
    * Create flyDiv with search controls
    * If used as onlick handler pass event object, for get click coords
    * @param {event} e
    */
    showSearchWin: function(e, id) { 
    	
    	// lazy-init vars, redeclare fly div title
        var headerObj = {
        	title: '<span style="color: black;">Search for:&nbsp;</span>',
        	buttons: [{src: 'images/search/windowPin.gif', handler: 'searchController'+this.id+'.hideSearchWin(); ', alt: 'Hide window', title: 'Hide window'}],
        	closeButton: false
        };
        
        var cfgObj = {
        	headerObj: headerObj,
        	border:{
        		color: $('#controlChooseMenu'+this.id).css('background-color')
        	}
        };
        // redeclare function, after lazy-init
        this.showSearchWin = function(e, id){
        	this.hideCtrlChooseMenu();
        	// get click coors
	        var x = 50, y = 50;
	        if (Runner.isIE && e) {
	            y = e.y;
	            x = e.x;
	        } else if (e) {
	            y = e.clientY;
	            x = e.clientX;
	        }
	        // handler text, will fire before window closed
	        var oncloseHandlerCode = '';
	        
	        this.flyDivId = id || ++window.flyid;
	        // create div
	        var divContainer = DisplayFlyDiv("", "", this.flyDivId, "", x, y, 'search', "", this.flyDivId, oncloseHandlerCode, cfgObj);	        
	        $(divContainer).css('padding-top', '10px');
	        // set div color, because in panel mode in IE6 it suddenly covers controls
	        //this.srchOptDiv.css('background-color', this.panelContainer.css('background-color'));
	        $(divContainer).css('background-color', this.panelContainer.css('background-color'));
	        // add to fly div
	        this.srchPanelHeader.appendTo(divContainer);
	        this.srchOptDiv.appendTo(divContainer);   
	        // hide div for panel mode
	        this.srchCtrlsBlock.hide();
	        // move all content to table from divs
	        this.moveCtrlsToTable();
	        // show table for window mode
	        this.srchCtrlsBlockWin.show();	        
	        // resize and set coors with new content
	        var flyDivDimAndCoorsObj = getFlyDivSizeAndCoors(this.flyDivId, x, y);
	        setFlyDivDimAndCoors(flyDivDimAndCoorsObj, this.flyDivId);	 
	        
	        this.showSearchOptions();
	        // set show indicator
	        this.srchWinShowStatus = true;
        }
    	// for first use
    	this.showSearchWin(e, id);        
    },
    /**
     * Move controls when switch to window mode.
     * On each table and div row this method call moveCtrlsToTableRow,
     * which move html and DOM from divs to tds
     */
    moveCtrlsToTable: function(){
    	// loop through div rows
    	for(var i=0; i<this.srchFilterRowArr.length;i++){
    		var divRowId = this.srchFilterRowArr[i].attr('id');
    		var tableRowId = divRowId+'_win';
    		var tableRow = $('#'+tableRowId);
    		if (this.srchFilterRowArr[i].css('display') != 'none'){
    			tableRow.show();   
    		}else{
    			tableRow.css('display', 'none');
    		}
    		 		
    		// move div row content to table row symetrically
    		this.moveCtrlsToTableRow(this.srchFilterRowArr[i], tableRow);
    	}
    },
    /**
     * Used to move html and DOM of each div row to table row
     */
    moveCtrlsToTableRow: function(divRow, tableRow){
    	var divCells = divRow.children();
    	var tds = tableRow.children();
    	// loop through div cells
    	for(var i=0; i<divCells.length;i++){    		
    		// move all content of div cell to td
    		var divCellChildren = $(divCells[i]).children();
    		// clear from script tag, to prevent executing it twice
    		divCellChildren.find('script').remove();
    		// move with DOM objects, not only HTML
    		divCellChildren.appendTo($(tds[i]));
    		
    		if ($(divCells[i]).css('display') == 'none'){
    			$(tds[i]).hide();
    		}else{
    			$(tds[i]).show();
    		}
    	}	  
    	// move field name in this way, because it conatins only text
    	$(tds[1]).html($(divCells[1]).html());
    },
    /**
     * Move controls when switch to panel mode, from window.
     * On each table and div row this method call moveCtrlsToDivRow,
     * which move html and DOM from tds to divs
     */
    moveCtrlsToDiv: function(){
    	// loop through div rows
    	for(var i=0; i<this.srchFilterRowWinArr.length;i++){
    		var tableRowId = this.srchFilterRowWinArr[i].attr('id');
    		var divRowId = tableRowId.substr(0, tableRowId.lastIndexOf('_'));//divRowId+'_win';
    		var divRow = $('#'+divRowId);
    		if (this.srchFilterRowWinArr[i].css('display') != 'none'){
    			divRow.show();   
    		}else{
    			divRow.css('display', 'none');
    		}    		
    		// move div row content to table row symetrically
    		this.moveCtrlsToDivRow(this.srchFilterRowWinArr[i], divRow);
    	}
    },
    /**
     * Used to move html and DOM of each table row to div row
     */
    moveCtrlsToDivRow: function(tableRow, divRow){
    	var divCells = divRow.children();
    	var tds = tableRow.children();
    	// loop through div cells
    	for(var i=0; i<tds.length;i++){    		
    		// move all content of div cell to td
    		var tableCellChildren = $(tds[i]).children();
    		// clear from script tag, to prevent executing it twice
    		tableCellChildren.find('script').remove();
    		// move with DOM objects, not only HTML
    		tableCellChildren.appendTo($(divCells[i]));
    		if ($(tds[i]).css('display') == 'none'){
    			$(divCells[i]).hide();
    		}else{
    			$(divCells[i]).show();
    		}
    	}	  
    	// move field name in this way, because it conatins only text
    	$(divCells[1]).html($(tds[1]).html());
    },
    
    /**
    * Removes fly div, and place controls to search panel
    */
    hideSearchWin: function(id) {
    	this.hideCtrlChooseMenu();
    	// remove color to prevent strange controls coverage in IE6
    	//this.srchOptDiv.css('background-color', '');
    	// move opt div to search panel        
        this.srchOptDiv.prependTo(this.panelContainer);
        // hide table
    	this.srchCtrlsBlockWin.hide();
    	// move controls
    	this.moveCtrlsToDiv();
    	// show panel mode div
    	this.srchCtrlsBlock.show();        
        this.srchPanelHeader.prependTo($("#searchform"+this.id));
        // remove fly win
        RemoveFlyDiv(id || this.flyDivId, true);
        // set status indicator
        this.srchWinShowStatus = false;
    },
    
     

    /**
    * Search win switcher
    * opens and closes search win
    */
    toggleSearchWin: function(e) {
        this.srchWinShowStatus ? this.hideSearchWin() : this.showSearchWin(e);
    },
   /**
    * Showes search options div and changes image expander 
    */
    showSearchBlock: function() {
    	// show div
        this.srchBlock.show();
        this.srchBlockStatus = true;
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideSearchBlock: function() {
    	// hide div
        this.srchBlock.hide();
        this.srchBlockStatus = false;
    },
    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleSearchBlock: function() {
        // can open panel, only if win is hidden
        (this.srchBlockStatus && !this.srchWinShowStatus) ? this.hideSearchBlock() : this.showSearchBlock();
    },
    /**
    * Showes search options div and changes image expander 
    */
    showSearchOptions: function() {
    	// to correct amsterdam layout with no menu
    	var mrgLeft = $('#records_block'+this.id).css('margin-left');
    	if (mrgLeft == 'auto' ||  mrgLeft == '0px'){
    		this.recBlockMargChange = true;
    		$('#records_block'+this.id).css('margin-left', 203);
    	}
        // show div
    	this.srchOptDiv.show();	
    	// show bottom round if exist
        this.bottomPanelRound.css('display',  '');
        // change image
        this.srchOptExpander.attr("src", this.hideOptSrc);
        this.srchOptExpander.attr('alt', this.hideOptText);
        this.srchOptExpander.attr('title', this.hideOptText);
        this.srchOptShowStatus = true;
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideSearchOptions: function() {
    	// to correct amsterdam layout with no menu 
    	if (this.recBlockMargChange){
    		$('#records_block'+this.id).css('margin-left', 0);
    	}
    	// hide div
    	this.srchOptDiv.hide();
        // hide bottom round if exist
        this.bottomPanelRound.css('display',  'none');
        // change image
        this.srchOptExpander.attr("src", this.showOptSrc);
        this.srchOptExpander.attr('alt', this.showOptText);
        this.srchOptExpander.attr('title', this.showOptText);
        this.srchOptShowStatus = false;
    },
    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleSearchOptions: function() {
        // can open panel, only if win is hidden
        (this.srchOptShowStatus && !this.srchWinShowStatus) ? this.hideSearchOptions() : this.showSearchOptions();
    },

    /**
    * Showes search options div and changes image expander 
    */
    showCtrlChooseMenu: function() { 
    	
    	// lazy init function
    	if (Runner.isIE6){    		
    		this.iframe = new Runner.util.IEHelper.iframe(/*this.ctrlChooseMenuDiv[0]*/);
    		this.hider = new Runner.util.IEHelper.selectsHider(this.ctrlChooseMenuDiv[0]);
    	}
    	// redefine
    	this.showCtrlChooseMenu = function(){
			// set menu position, relative to Add criteria link
    		var posObj = findPos($("#showHideControlChooseMenu"+this.id)[0]);
			// calc coordinates
    		var divT = posObj[1]+posObj[3], divL = posObj[0];
	    	// add only in win mode, strange positioning in fly div
	    	this.ctrlChooseMenuDiv.css('top', divT).css('left', divL);
	    	// show it
	        this.ctrlChooseMenuDiv.show();
	         // set div width, after div is visible, for correct offsetWidth data
	        this.ctrlChooseMenuDiv[0].offsetWidth < 80 ? this.ctrlChooseMenuDiv.css('width', '65px') : '';
	        // add iframe in panel mode
	        if (Runner.isIE6 && !this.srchWinShowStatus){
	       		// create iframe for IE6
		        this.iframe.reset({
					l: divL,
					t: divT,
					h: this.ctrlChooseMenuDiv[0].offsetHeight,
					w: this.ctrlChooseMenuDiv[0].offsetWidth
				});      				
			// in window mode hide combos	
	        }else if(Runner.isIE6 && this.srchWinShowStatus){
	        	this.hider.showSels();
	        	this.hider.getSelects();
	        	this.hider.hideSels();
	        }
	        // set max z-index
	        Runner.getZindex(this.ctrlChooseMenuDiv);
	        this.ctrlChooseMenuStatus = true;
    	}
    	// call function, after lazy-init
    	this.showCtrlChooseMenu();
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideCtrlChooseMenu: function() {
        this.ctrlChooseMenuDiv.hide();
        this.ctrlChooseMenuStatus = false;
        if (Runner.isIE6 && !this.srchWinShowStatus && this.iframe){
        	this.iframe.hide();
        }else if(Runner.isIE6 && this.srchWinShowStatus && this.iframe){
        	this.hider.showSels();
        }
    },

    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleCtrlChooseMenu: function() {
        this.ctrlChooseMenuStatus ? this.hideCtrlChooseMenu() : this.showCtrlChooseMenu();        
    },
    
	/**
    * Search type combos show handler
    */
    showCtrlTypeCombo: function() {
        for (var i = 0; i < this.searchTypeCombosArr.length; i++) {        	
	    	this.searchTypeCombosArr[i].show();	
	    	this.searchTypeCombosArr[i].find('select').show();
        }
        for (var i = 0; i < this.searchTypeCombosWinArr.length; i++) {        	
	    	this.searchTypeCombosWinArr[i].show();	
	    	this.searchTypeCombosWinArr[i].find('select').show();
        }
        this.showHideSearchComboButton.html(this.hideComboText);
        this.showHideSearchComboButton.attr('title', this.hideComboText);
        this.ctrlTypeComboStatus = true;
        
    },
    /**
    * Search type combos hide handler
    */
    hideCtrlTypeCombo: function() {
        for (var i = 0; i < this.searchTypeCombosArr.length; i++) {        	
            this.searchTypeCombosArr[i].hide();
            this.searchTypeCombosArr[i].find('select').hide();
        }
        for (var i = 0; i < this.searchTypeCombosWinArr.length; i++) {        	
            this.searchTypeCombosWinArr[i].hide();
            this.searchTypeCombosWinArr[i].find('select').hide();
        }
        this.showHideSearchComboButton.html(this.showComboText);
        this.showHideSearchComboButton.attr('title', this.showComboText);
        this.ctrlTypeComboStatus = false;
    },    
    /**
    * Search type combos show\hide switcher
    */
    toggleCtrlTypeCombo: function() {
        this.ctrlTypeComboStatus ? this.hideCtrlTypeCombo() : this.showCtrlTypeCombo();
    },
    /**
     * Criterias show|hide controller
     * @param {int} ctrlsCount
     */
    toggleCrit: function(ctrlsCount){
    	// lazy init, get conditions containers
        var topCritContId = 'srchCritTop'+this.id;
        this.topCritCont = $('#'+topCritContId);
        var bottomSearchButtId = 'bottomSearchButt'+this.id;
        this.bottomSearchButt = $('#'+bottomSearchButtId); 
        // redefine after first call
        this.toggleCrit = function(ctrlsCount){
        	ctrlsCount > 1 ? this.topCritCont.show() : this.topCritCont.hide();
    		ctrlsCount > 0 ? this.bottomSearchButt.show() : this.bottomSearchButt.hide();
        }
        // for first call
		this.toggleCrit();
    }
});