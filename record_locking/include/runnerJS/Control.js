/**
 * Base abstract class for all controls, should not be created directly
 * @requires runner, ControlManager, validate, Event
 */
Runner.controls.Control = Runner.extend(Runner.Event, {
	/**
	 * Name of control
	 * @type string
	 */
 	fieldName: "",
 	/**
 	 * Name used for HTML tags, attrs
 	 * @type String
 	 */
 	goodFieldName: "",
 	/**
 	 * table name for urls request
 	 * @type String
 	 */
 	shortTableName: "",
	/**
	 * Control id
	 * @type string
	 */
	id: "",
	/**
	 * custom CSS classes
	 * @type string
	 */
	css: "",
	/**
	 * Custom css styles
	 * @type String
	 */
	style: "",
	/**
	 * Value DOM element id
	 * @type string
	 */
	valContId: "",
	/**
	 * Object, value DOM element
	 * @type {object}
	 */
	valueElem: null,	
	/**
	 * Span container element id
	 * @type {string}
	 */
	spanContId: "",
	/**
	 * Span jQuery object
	 * @type {object}
	 */
	spanContElem: null,
	/**
	 * Error container id
	 * @type {string}
	 */
	errContId: "",
	/**
	 * Error container, div
	 * @type {object}
	 */	
	errContainer: null,
	/**
	 * Array of validation types
	 * @type array of string
	 */
	validationArr: [],
	/**
	 * Value after initialization
	 */
	defaultValue: null,
	/**
	 * Source table
	 */
	table: "",
	/**
	 * Defined regExp with ,message, messageType, allowEmpty, regExp 
	 * @type {object}
	 */
	regExp: null,
	/**
	 * Type attr
	 * @type {string}
	 */
	inputType: "",
	/**
	 * Edit type of control, that used to process data on server
	 * Was created for search submit
	 * @type String
	 */
	ctrlType: "",
	/**
	 * Is editable elems shown
	 * @type {bool}
	 */
	showStatus: true,
	/**
	 * Number of control for the field. In advanced search page only 2 controls may appear for the field.
	 * But ControlManager can add any ammount of controls to the field 
	 * @type number
	 */
	ctrlInd: -1,
	/**
	 * Indicator, is focused element or not
	 * @type Boolean
	 */
	isSetFocus: false,
	/**
	 * Hidden property
	 * @type Boolean
	 */
	hidden: false,
	/**
	 * Mode of using control add|adit|search
	 * @type String
	 */
	mode: '',
	/**
	 * Class constructor
	 * @constructor
	 * @extends Runner.emptyFn
	 * @param {Mixed} cfg
	 * @param {string} cfg.fieldName
	 * @param {string} cfg.id
	 * @param {array} cfg.validationArr
	 * @param {object} cfg.regExp
	 */	
	constructor: function(cfg) {		
		this.validationArr = new Array();	
		// copy properties from cfg to controller obj
        Runner.apply(this, cfg);
		//call parent
		Runner.controls.Control.superclass.constructor.call(this, cfg);	
		// value element id
		this.valContId = "value"+(cfg.ctrlInd || "")+"_"+this.goodFieldName+"_"+this.id;
		// value elem
		this.valueElem = (this.valueElem == null) ? $("#"+this.valContId) : this.valueElem;	
		// span container id
		this.spanContId = "edit"+this.id+"_"+this.goodFieldName+"_"+cfg.ctrlInd;
		// add span elem
		this.spanContElem = $("#"+this.spanContId);
		// error DOM element id
		this.errContId = "errorCont"+cfg.ctrlInd+"_"+this.valContId;
		// create error container
		this.spanContElem.append('<div id="'+this.errContId+'" class="errorText" style="display: none;"></div>');			
		// add error container 
		this.errContainer = this.spanContElem.find('#'+this.errContId);
		// initialize control disabled
		if (cfg.disabled===true || cfg.disabled==="true"){
			this.setDisabled();
		}
		// initialize control hidden
		if (cfg.hidden===true || cfg.hidden==="true"){
			this.hide();
		}
		// there we can also apply custom css classes		
		this.ccs ? valueElem.addclass(this.css) : '' ;
		// there we can also apply custom css styles
		this.addStyle(this.style);		
		// get default value
		this.defaultValue = this.getValue();
		// add input type attr, if it exist
		if (this.valueElem.attr && this.valueElem.attr("type")){
			this.inputType = this.valueElem.attr("type");
		}		
		// need for use focus indicator
		this.addEvent(["click"]);
		// if not passed stop event init param
		if (cfg.stopEventInit!==true) {			
			//event elem
			this.elemsForEvent = [this.valueElem.get(0)];
			//adding events
			this.addEvent(["blur"]);
			// init events
			this.init();
		}
		// register new control in manager
		Runner.controls.ControlManager.register(this);
		// register in validator for custom user validation functions loading
		validation.registerCustomValidation(this);
		//console.log(Runner.controls.ControlManager.getAt(this.table, this.id, this.fieldName), "from CM");		
	},
	/**
	 * Add styles to value element
	 * @param {string} styleToAdd
	 * @return {Boolean} true in success, otherwise false
	 */
	addStyle: function(styleToAdd){
		if (!styleToAdd){
			return false;
		}
		
		var stylesArr = styleToAdd.split(';');
		
		for(var i=0; i<stylesArr.length; i++){			
			var style = stylesArr[i].split(":");
			style[0] = style[0].toString().trim();
			if (!style[0]){
				continue;
			}
			style[1] = style[1].toString().trim();
			this.valueElem.css(style[0], style[1]);
		}
		
		
		/*// style that was on element
		var oldStyle = this.valueElem.attr('style');
		// new style, with added
		var newStyle = (oldStyle ? oldStyle + ' ' : '') + styleToAdd;
		// set new style
		this.valueElem.attr('style', newStyle);	*/
		return true;
	},
	/**
	 * Validates control against validation types, defined in validationArr
	 * @method validate
	 * @params valArr - array of validation for event blur only
	 * @return {object} if success true, otherwise false	 
	 */
	validate:function(valArr)
	{
		var vRes = validation.validate(valArr || this.validationArr, this);		
		if (!vRes.result)
			this.markInvalid(vRes.messageArr);
		else
			this.clearInvalid();
		// return validation result
		return vRes;		
	},
	/**
	 * removes validation from control. 
	 * @param {string} vType
	 * @return {bool} If success true, false otherwise
	 */
	removeValidation: function(vType){
		for(var i=0;i<this.validationArr.length;i++){
			if (this.validationArr[i] == vType){
				this.validationArr.splice(i,1);
				return true;
			}
		}
		return false;
	},
	/**
	 * Adds validation to control
	 * @param {string} vType
	 */
	addValidation: function (vType){
		if (!this.isSetValidation(vType)){
			this.validationArr[this.validationArr.length] = vType;
		}
	},
	/**
	 * Checks if validation added
	 * private
	 * @param {string} vType
	 * @return {bool} If success true, false otherwise
	 */
	isSetValidation: function (vType)
	{
		// checks if this vType defined
		if (!validation[vType])
		{
			return false;
		}
		for(var i=0;i<this.validationArr.length;i++)
		{
			if (this.validationArr[i] == vType)
			{				
				return true;
			}
		}
		return false;
	},
	/**
	 * Validates control value against vType validation
	 * @param {string} vType
	 * @return {mixed}
	 */
	validateAs: function(vType){
		return validation[vType](this.getValue());
	},
	/**
	 * Sets error messages after validation
	 * @param {array} messArr
	 */
	markInvalid: function(messArr){
		var divInnerHtml = "";
		this.errContainer.show();
		for(var i=0;i<messArr.length;i++){
			divInnerHtml += messArr[i]+"</br>";
		}
		// add message to container
		this.errContainer.html(divInnerHtml);
	},
	/**
	 * Clears invalid state
	 * @method
	 */
	clearInvalid: function(){	
		this.errContainer.hide();
		this.errContainer.empty();
	},	
	
	/**
	 * sets default value to control
	 * return true if success. otherwise false
	 * @method
	 */
	reset: function(){
		this.setValue(this.defaultValue);
		this.clearInvalid();		
		return true;
	},
	/**
	 * Sets empty value to control
	 * return true if success. otherwise false
	 * @method
	 * @return bool
	 */
	clear: function(){
		this.setValue('');
		this.clearInvalid();		
		return true;
	},
	
	/**
	 * Hide control - set display attr none
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	hide: function(){
		this.spanContElem.css("display", "none");
		this.showStatus = false;
	},
	
	/**
	 * Show control - set display attr block
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	show: function(){
		this.spanContElem.css("display", "");
		this.showStatus = true;
	},	
	/**
	 * Toggle show/hide status
	 */
	toggleHide: function(){
		if (this.showStatus){
			this.hide();
		}else{
			this.show();
		}
	},
	/**
	 * Get value from value element. 
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	getValue: function(){		
		if (this.valueElem.val){
			return this.valueElem.val();
		}else{
			return false;
		}
		//return this.valueElem.val();
	},
	
	/**
	 * Return value as string
	 * @return {string}
	 */
	getStringValue: function(){
		return this.getValue();
	},
	
	/**
	 * Sets value to value DOM elem
	 * Should be overriden for sophisticated controls
	 * @method
	 * @param {mixed} val
	 */
	setValue: function(val, triggerEvent){
		if (this.valueElem.val){			
			this.valueElem.val(val);
			// trigger event
			//if(triggerEvent===true){
			if(triggerEvent===true){
				//console.log(triggerEvent, 'triggerEvent on change cb called');
				this.fireEvent("change");
			}
		}else{
			return false;
		}
	},
	
	
	/**
	 * Sets disable attr true
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	setDisabled: function(){
		if (this.valueElem.get(0)){
			this.valueElem.get(0).disabled = true;
			return true;
		}else{
			return false;
		}			
	},
	
	/**
	 * Sets disaqble attr false
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	setEnabled: function(){
		if (this.valueElem.get(0)){
			this.valueElem.get(0).disabled = false;
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Returns input tag type attribute.
	 * @method
	 * @return {string}
	 */
	getControlType: function(){
		return this.inputType;
	},	
	/**
	 * Sets focus to the element
	 * @method
	 * @return {bool}
	 */
	setFocus: function(triggerEvent){
		
		var cType = this.getControlType();
		if (cType != "" && (cType == 'text' || cType == 'password' || cType == 'file' || cType=='textarea')){
			// can't set focus on disabled element. This may cause IE error
			if (this.valueElem.get(0).disabled == true){
				return false;
			}
			this.valueElem.get(0).focus();
			// trigger event
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
	 * Checks if control value is empty. Used for isRequired validation
	 * @method
	 * @return {bool}
	 */
	isEmpty: function()
	{
		return this.getValue().toString()=="";
	},
	
	/**
	 * Custom function for onblur event
	 * @param {Object} e
	 */
	"blur": function(e)
	{
		this.stopEvent(e);		
		this.isSetFocus = false;
		var len = this.validationArr.length;
		if(this.validationArr[len-1] == 'IsRequired')
			valArr = this.validationArr.slice(0,len-1)
		else 
			valArr = this.validationArr;
		return this.validate(valArr);		
	},
	/**
	 * Sets focus indicator true when click on elem
	 * @param {event} e
	 */
	"click": function(e){		
		this.isSetFocus = true;
	},
	/**
	 * Removes css class to value element
	 * @param {string} className
	 */
	removeCSS: function(className)
	{
		this.valueElem.removeClass(className);
	},
	/**
	 * Adds css class to value element
	 * @param {string} className
	 */
	addCSS: function(className)
	{
		this.valueElem.addClass(className);
	},
	/**
	 * Returns specified attribute from value element
	 * @param {string} attrName
	 */
	getAttr: function(attrName){
		return this.valueElem.attr(attrName);
	},
	/**
	 * Return element that used as display.
	 * Usefull for suggest div positioning
	 * @return {object}
	 */
	getDispElem: function(){
		return this.valueElem;
	},
	/**
	 * Clone html for iframe submit
	 * @return {array}
	 */
	getForSubmit: function(){
		return [this.valueElem.clone()];
	}
	
});



