
/**
 * Edit box with ajax popup class with suggest div handling
 * @requires Runner.controls.TextFieldLookup
 */
Runner.controls.EditBoxLookup = Runner.extend(Runner.controls.TextFieldLookup, {
	/**
	 * Focus indicator
	 * @type Boolean
	 */
	focusState: false,
	/**
	 * Don't know for what
	 * @type Boolean
	 */
	isLookupError: false,
	/**
	 * suggestDiv cursor ind
	 * @type 
	 */
	cursor: -1,
	/**
	 * Array of suggest vals
	 * @type {array}
	 */
	suggestValues: [],
	/**
	 * Array of lookup vals
	 * @type {array}
	 */
	lookupValues: [],
	/**
	 * Lookup div id
	 * @type String
	 */	
	lookupDivId: "",
	/**
	 * Lookup div jQuery object
	 * @type {object}
	 */
	lookupDiv: null,
	/**
	 * Lookup div id
	 * @type String
	 */	
	lookupIframeId: "",
	/**
	 * Lookup div jQuery object
	 * @type {object}
	 */
	lookupIframe: null,
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */	
	constructor: function(cfg){
		// recreate objects
		this.lookupValues = new Array();
		this.suggestValues = new Array();		
		// call parent
		Runner.controls.EditBoxLookup.superclass.constructor.call(this, cfg);
		// set lookup div id
		this.lookupDivId = 'lookupSuggest_'+this.valContId;
		// set lookup iframe id, for IE6
		if (Runner.isIE6){
			this.lookupIframeId = 'iFrame_'+this.valContId;	
		}		
		// events array
		this.addEvent(["keyup", "focus", "keydown", "blur"]);	
		// init events handling
		this.init();				
	},
	/**
	 * Destructor with suggest div remove
	 */
	destructor: function(){
		// call parent
		Runner.controls.EditBoxLookup.superclass.destructor.call(this);
		// destroy div
		this.destroyDiv();
	},
	/**
	 * Keycode after which lookupSuggest should start
	 * @param {} keyCode
	 * @return {}
	 */
	checkKeyCodeForRunSuggest: function(keyCode){
		return (((keyCode >= 65) && (keyCode <= 90)) || ((keyCode >= 48) && (keyCode <= 57))
			|| ((keyCode >= 96) && (keyCode <= 105)) || (keyCode==8) || (keyCode==46) || (keyCode==32)
			|| (keyCode==222));
	},
	/**
	 * Keyup event handler, for call lookupsuggest
	 * Do all work after keypressed
	 */
	"keyup": function(e){		
		this.stopEvent(e);	
		/*console.log(e.keyCode, 'e.keyCode');
		console.log(e, 'e');*/
		
		if (this.getDisplayValue() == ""){
			// remove div
			this.destroyDiv();
			// no errors then
			this.isLookupError = false;
			// remove error highlight
			this.removeCSS("highlight");
			// set empty val and trigger error
			this.setValue("", "", true);
			// return from handler
			return;
		}
		// filter keys
		if (e && this.checkKeyCodeForRunSuggest(e.keyCode)) {			
			//this.showDiv();
			// do request for suggest div data
			this.lookupAjax();
		}		
	},
	/**
	 * Keydown event handler, for make select in suggest
	 * @return {bool}
	 */
	"keydown": function(e){
		// key code
		var keyCode=e.keyCode;
		//console.log(keyCode, 'keyCode in key down');
		switch(keyCode){	
			case 38: //up arrow
				this.moveUp();		
				break;
			case 40: //down arrow
				this.moveDown();
				break;
			case 13: //enter 
				this.destroyDiv();
				return false; 		
				break;				
			case 9: // tab
				this.destroyDiv();
				break;
		}
		return true;		
	},
	/**
	 * Creates and set position of lookup div.
	 * Also set suggest vals
	 */
	showDiv: function(lookupSuggestHtml){
		// create div with html
		$(document).find('body').append('<div id="'+this.lookupDivId+'" class="search_suggest">'+lookupSuggestHtml+'</div>');
		// create iframe for IE6
		if (Runner.isIE6){
			$(document).find('body').append('<iframe id="'+this.lookupIframeId+'" frameborder="1" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="background:white;position:absolute;display:block;opacity:0;filter:alpha(opacity=0);"></iframe>');	
			this.lookupIframe = $("#"+this.lookupIframeId);
		}		
		// get div 
		this.lookupDiv = $("#"+this.lookupDivId);		
		// set div coors
		this.setDivPos();	
		// for compatibility with old way of use search suggest
		this.lookupDiv.css("visibility", "visible")
	},
	/**
	 * Destroys lookupDiv from DOM
	 */
	destroyDiv: function(){
		//console.log('call destroy div', this.lookupDiv);
		// if it wasn't destroyed before
		if (this.lookupDiv){
			this.lookupDiv.remove();
		}
		// destroy iframe for IE6
		if (Runner.isIE6 && this.lookupIframe){
			this.lookupIframe.remove();
			this.lookupIframe = null;
		}
		// clear link 
		this.lookupDiv = null;
	},
	/**
	 * Set div coords
	 */
	setDivPos: function(){
		// get coordinates from global func
		var coors = findPos(this.getDispElem().get(0));
		coors[1] += coors[3];
		this.lookupDiv.css("top",coors[1] + "px");
		this.lookupDiv.css("left",coors[0] + "px");		
		// add highest z index
		if(Runner.isIE){
			this.lookupDiv.css("zIndex",++zindex_max);
		}else{
			this.lookupDiv.css("z-index",++zindex_max);
		}
		// set iframe postition for IE6.
		if (Runner.isIE6 && this.lookupIframe){			
			this.lookupIframe.css("top", coors[1] + "px");
			this.lookupIframe.css("left", coors[0] + "px");
			// for debug and testing
			//alert(this.lookupDiv.css("width")+"---width for set");
			//alert(this.lookupDiv.css("height")+"---height for set");
			/*this.lookupIframe.css("width", this.lookupDiv.css("width") + "px");
			this.lookupIframe.css("height", this.lookupDiv.css("height") + "px");*/
			this.lookupIframe.css("width", this.lookupDiv.css("width"));
			this.lookupIframe.css("height", this.lookupDiv.css("height"));
			/*// don't need to change iframe z-index
			this.lookupIframe.css("zIndex", --this.lookupDiv.css("zIndex"));*/			
		}
	},
	/**
	 * On hover suggest div value div handler
	 * @param {object} divHovered
	 */
	suggestOver: function (divHovered){
		// remake for inner div
		this.lookupDiv.find("div.suggest_link_over").each(function(){
			this.className = 'suggest_link';
		}) ;
		// set highlight style
		divHovered.className = 'suggest_link_over';
		// set new cursor index
		this.cursor = divHovered.id.substring(10);
	},
	/**
	 * On unhover suggest div value div
	 * @param {object} div_value
	 */
	suggestOut: function (divValue){
		divValue.className = 'suggest_link';
	},
	/**
	 * Function that makes request to server and parse content
	 */
	lookupAjax: function(){
		// vars for after request function closure
		var table = this.table, recId = this.id, fName = this.fieldName;
		
		var ajaxParams = {
			searchFor: myEncode(this.getValue()[1]), 
			searchField: myEncode(this.fieldName),
			lookupValue: myEncode(this.getValue()[0]),
			// GET DISP VAL OT HIDD VAL???
			category : (this.parentCtrl ? this.parentCtrl.getDisplayValue() : ""),
			rndVal: (new Date().getTime())
		}
		// do request
		$.get(this.shortTableName+"_lookupsuggest.php", ajaxParams,
		function(txt, textStatus){			
			// prepare vars
			var ctrl = Runner.controls.ControlManager.getAt(table, recId, fName, 0);			
			var hiddVal = ctrl.getValue()[0], dispVal = ctrl.getDisplayValue(), valArr = ctrl.getValue();
			// parse data from server
			var str = txt.split("\n");
			$.each( str, function(i, n){
				str[i] = unescape(n);
			})			
			// if values correct, in recieved data exist looup hidden value
			if (str.isInArray(hiddVal)){
			//if (IsInArray(str,hiddVal,false)) {
				// remove error and highlight
				this.isLookupError = false;
				ctrl.removeCSS("highlight");
				// change new value pair and fire event
				$.each(str, function(i, n){
					if((n.toLowerCase()==valArr[1]) && (str[i-1]!=valArr[0])) {
						// setValue with firing change event which will call reloadDependences
						ctrl.setValue(valArr[1], str[i-1], true);
					}
				});
			}else{
				// make error if no hidden value
				this.isLookupError = true;
				if (!ctrl.focusState){
					ctrl.addCSS("highlight");
				}					
			}
			// if no text from server than exit from handler
			if (!txt){
				return false;
			}
			
			// get suggest and lookup values, concatinate suggest div inner html
			var suggest = "";						
			for(var i=0, j=0; i < str.length-1; i=i+2,j++) {
				// div html, value and event handlers
				suggest += '<div id="suggestDiv'+i+'" style="cursor:pointer;" onmouseover="' +
						'var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +						
						'ctrl.suggestOver(this);" '+
						'onmouseout="var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +
						'ctrl.suggestOut(this);" '+
						'onclick="var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +
						'ctrl.isLookupError = false;' +
						//'console.log(ctrl, \'ctrl\');' +
						'ctrl.removeCSS(\'highlight\');' +
						'ctrl.setValue(ctrl.suggestValues[' + j + '], \'' + str[i] + '\', true);' +
						'ctrl.destroyDiv();" ' +
						'class="suggest_link">' + str[i+1] + '</div>';	
				// change data in arrays
				ctrl.suggestValues[j] = str[i+1];
				ctrl.lookupValues[j] = str[i];
			}
			// show div
			ctrl.showDiv(suggest);
			// set postition
			ctrl.setDivPos();
		});
	},
	/**
	 * Down arrow handler
	 */
	moveDown: function(){		
		// if there are any suggest vals and cursor not on last of them
		if(this.lookupDiv.children().length>0 && this.cursor<this.lookupDiv.children().length){
			// add cursor count - same to move down
			this.cursor++;
			// loop for all suggest vals
			for(var i=0;i<this.lookupDiv.children().length;i++){
				// if val that should be highlighted
				if(i==this.cursor){
					// remove error 
					this.isLookupError = false;					
					this.removeCSS("highlight");
					// make highlight style
					this.lookupDiv.children().get(i).className = "suggest_link_over";
					// get new values
					var suggestVal = this.suggestValues[this.cursor].replace(/\<(\/b|b)\>/gi,""), lookupVal = this.lookupValues[this.cursor];
					// if lookup val changes, than fireEvent
					if (this.getValue()[0] != lookupVal){
						this.setValue(suggestVal, lookupVal, true);	
					}else{
						this.setValue(suggestVal, lookupVal);
					}			
				}
				// set simple suggest val style
				else{					
					this.lookupDiv.children().get(i).className = "suggest_link";
				}
			}
			// for cursor loop
			if (this.cursor==(this.lookupDiv.children().length)) {
				this.cursor=-1;
				this.focus(); 
			}
		}
	},
	/**
	 * Up arrow handler
	 */
	moveUp: function(){
		// there are any suugest vals and dont't know why check that cursor >= -1
		if(this.lookupDiv.children().length>0 && this.cursor>=-1){
			// move up same as make cursor less
			this.cursor--;
			// set cursor on the last values, for loop
			if (this.cursor==-2) {
				this.cursor=this.lookupDiv.children().length-1; 
				this.focus(); 
			}			
			// set styles and values
			for(var i=0;i<this.lookupDiv.children().length;i++){
				// if selected value
				if(i==this.cursor){
					// make highlight styles
					this.lookupDiv.children().get(i).className = "suggest_link_over";
					// get values
					var suggestVal = this.suggestValues[this.cursor].replace(/\<(\/b|b)\>/gi,""), lookupVal = this.lookupValues[this.cursor];
					// if lookup values changes, need to fire change event
					if (this.getValue()[0] != lookupVal){
						this.setValue(suggestVal, lookupVal, true);	
					}else{
						this.setValue(suggestVal, lookupVal);
					}
					// remove error
					this.isLookupError = false;
					this.removeCSS("highlight");
				}
				// remove highlight
				else{
					this.lookupDiv.children().get(i).className = "suggest_link";
				}
			}
		}
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr true
	 * Sets hidden css style true for link add New
	 * @method
	 */
	setDisabled: function()
	{
		if (this.displayElem.get(0))
		{
			this.displayElem.get(0).disabled = true;
			this.addNew.css('visibility','hidden');
			return true;
		}else{
			return false;
		}			
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr false
	 * Sets visible css style true for link add New
	 * @method
	 */
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			this.addNew.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Blur event handler
	 * @event
	 */
	"blur": function(e){
		this.focusState=false;		
		if (this.isLookupError) {
			this.addCSS("highlight");
		} else {
			this.removeCSS("highlight");
		}
		Runner.controls.EditBoxLookup.superclass["blur"].call(this, e);	
	},
	/**
	 * Focus event handler
	 * @event
	 */
	"focus": function(e){
		this.stopEvent(e);	
		this.focusState=true;
	}	
});
