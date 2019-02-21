
/**
 * Base abstract class for lookups with textFields
 * Contains text box editor as display field and hidden field for submit values
 * @class
 * @requires Runner.controls.LookupWizard
 */
Runner.controls.TextFieldLookup = Runner.extend(Runner.controls.LookupWizard, {
	/**
	 * id of jQuery element that display value
	 * Value element in EditBoxLookup is hidden, and used for submit data
	 * @type {string}
	 */
	displayId: "",
	/**
	 * jQuery element that display value
	 * Value element in EditBoxLookup is hidden, and used for submit data
	 * @type {object}
	 */
	displayElem: null,	
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */	
	constructor: function(cfg){
		// call parent
		Runner.controls.TextFieldLookup.superclass.constructor.call(this, cfg);	
		// add display elem id
		this.displayId = "display_"+this.valContId;
		// display jQuery elem
		this.displayElem = $("#"+this.displayId);	
		// set input type
		this.inputType = "text";				
		//event elem 
		this.elemsForEvent = [this.displayElem.get(0)];	
		// initialize control disabled
		if (cfg.disabled==true || cfg.disabled=="true"){
			this.setDisabled();
		}
	},
	/**
	 * Set value to display element
	 * @param {mixed} val
	 * @return {bool} true if success otherwise false
	 */
	setDisplayValue: function(val){
		if (this.displayElem){
			return this.displayElem.val(val);
		}else{
			return false;
		}		
	},
	/**
	 * Get value from value element. 
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	getDisplayValue: function(){			
		if (this.displayElem){
			return this.displayElem.val();
		}else{
			return false;
		}
	},
	/**
	 * Overrides parent method. Value in editBoxLookup is pair of display and hidden values
	 * @method
	 * @param {mixed} dispVal
	 * @param {mixed} hiddVal
	 * @return {Boolean} true if success otherwise false
	 */	
	setValue: function(dispVal, hiddVal, triggerEvent){
		// set hidden value, if all ok
		var isSetHiddVal = this.valueElem.val(unescape(hiddVal));
		if (isSetHiddVal === false){
			return false;
		}
		// set display value, if all ok
		var isSetDispVal = this.setDisplayValue(unescape(dispVal));
		if (isSetDispVal === false){
			return false;
		}
		// trigger event if needed
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change cb called')
			this.fireEvent("change");
		}		
		return true;
	},
	/**
	 * Overrides parent method. Value in editBoxLookup is pair of display and hidden values
	 * @method
	 * @return {array} pair of values if success otherwise false
	 */	
	getValue: function(){
		//return [this.getDisplayValue(), this.valueElem.val()];
		return [this.valueElem.val(), this.getDisplayValue()];
	},
	
	
	/**
	 * Sets disable attr true
	 * @method
	 */
	setDisabled: function(){
		if (this.displayElem.get(0)){
			this.displayElem.get(0).disabled = true;
			return true;
		}else{
			return false;
		}			
	},

	/**
	 * Sets disable attr false
	 * @method
	 */
	setEnabled: function(){
		if (this.displayElem.get(0)){
			this.displayElem.get(0).disabled = false;
			return true;
		}else{
			return false;
		}
	},	
	/**
	 * Sets focus to the element
	 * @method
	 * @param {bool}
	 * @return {bool}
	 */
	setFocus: function(triggerEvent){
		if (this.displayElem.get(0).disabled != true){
			this.displayElem.get(0).focus();
			if(triggerEvent===true){
				//console.log(triggerEvent, 'triggerEvent on focus ctrl called');
				this.fireEvent("focus");
			}
			this.isSetFocus = true;
			return true;
		}else{
			this.isSetFocus = false;
			return false;
		}		
	},
	/**
	 * Removes css class to value element
	 * @param {string} className
	 */
	removeCSS: function(className){
		this.displayElem.removeClass(className);
	},
	/**
	 * Adds css class to value element
	 * @param {string} className
	 */
	addCSS: function(className){
		this.displayElem.addClass(className);
	},
	/**
	 * Returns specified attribute from value element
	 * @param {string} attrName
	 */
	getAttr: function(attrName){
		return this.displayElem.attr(attrName);
	},
	/**
	 * Return element that used as display.
	 * Usefull for suggest div positioning
	 * @return {object}
	 */
	getDispElem: function(){
		return this.displayElem;
	},
	/**
	 * Checks if control value is empty. Used for isRequired validation
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		return this.getDisplayValue().toString()=="";
	},	
	/**
	 * First loading, without ajax. Should be called directly
	 * @param {string} txt unparsed values for options
	 * @param {string} selectValue unparsed values of selected options
	 */
	preload: function(txt, selectValue){
		// parse content
		var parsedOptionsContent = this.parseContentToValues(txt);
		// search val
		for(var i=0;i<parsedOptionsContent.length-1;i=i+2){
			if (unescape(parsedOptionsContent[i]) == selectValue){					
				// set values
				this.setValue(parsedOptionsContent[i+1], parsedOptionsContent[i]);
			}
		}		
	},
	/**
	 * Reloading dropdown. Called by change event handler
	 * @param {string} value of master ctrl
	 */
	reload: function(masterCtrlValue){		
		var fName = this.fieldName, tName = this.table, rowId = this.id;
		// can't reload if no parent ctrl - for safety use
		if (masterCtrlValue && !this.parentCtrl){
			return false;
		}		
		var ajaxParams = {
			// ctrl fieldName
			field: myEncode(fName),
			// value of master ctrl
			value: myEncode(masterCtrlValue),
			// is exist parent, indicator
			isExistParent: (this.parentCtrl ? 1 : 0),
			// page mode add, edit, etc..
			mode: this.mode,
			// tag name of ctrl
			type: this.valueElem[0].tagName,
			// random value for prevent caching
		    rndVal: (new Date().getTime())
		};
		// for handler closure
		var ctrl = this;
		// get content		
		$.get(this.shortTableName+"_autocomplete.php", ajaxParams, 
		function(txt, reqStatus){
			// parse content
			var parsedOptionsContent = ctrl.parseContentToValues(txt);
			// set values if from server comes only one value
			if(parsedOptionsContent.length==3){
				// if value changed, so fire change event
				if (ctrl.getValue()[0]!=parsedOptionsContent[0]){
					ctrl.setValue(parsedOptionsContent[1], parsedOptionsContent[0], true);
				}else{
					ctrl.setValue(parsedOptionsContent[1], parsedOptionsContent[0]);
				}	
			// if no vals from server than clear ctrl
			}else{
				ctrl.setValue("", "", true);
			}								
		});
		
	}
});