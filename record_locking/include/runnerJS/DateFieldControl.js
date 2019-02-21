/**
 * Abstract base class for date fields, should not created directly
 */
Runner.controls.DateField = Runner.extend(Runner.controls.Control, {
	/**
	 * Id of hidden elem, which used by datepicker
	 * @type {string} 
	 */
	datePickerHiddId: "",
	/**
	 * Hidden elem, which used by datepicker
	 * ts element
	 * @type {element} 
	 */
	datePickerHiddElem: null,
	/**
	 * Image and link of datepicker
	 * link element
	 * @type {element} 
	 */
	imgCal: null,
	/**
	 * Indicates used datepicker with control or not
	 * @type {bool} cfg
	 */
	useDatePicker: false,
	/**
	 * Id of date format hidden element, which used on serverside
	 * @type {string}
	 */
	dateFormatHiddId: "",
	/**
	 * Indicates date format with control or not
	 * @type {bool} cfg
	 */
	dateFormat: "",
	/**
	 * Indicates show time with control or not
	 * @type {bool} cfg
	 */
	showTime: false,
	/**
	 * jQuery object of date format hidden element, which used on serverside
	 * @type {Object} 
	 */
	dateFormatHiddElem: null,
	/**
	 * Overrides parent constructor
	 * @param {Object} cfg
	 * @param {bool} cfg.useDatePicker
	 */
	constructor: function(cfg){
		// call parent
		Runner.controls.DateField.superclass.constructor.call(this, cfg);
		// add hidden field for datepicker usege
		this.useDatePicker = cfg.useDatePicker ? cfg.useDatePicker : false;
		this.dateFormat = cfg.dateFormat ? cfg.dateFormat : "";
		this.showTime = cfg.showTime ? cfg.showTime : false;
		if (this.useDatePicker){
			this.datePickerHiddId = "tsvalue_"+this.goodFieldName+"_"+this.id;
			this.datePickerHiddElem = $("#"+this.datePickerHiddId);			
		}
		// add hidden field for date format on serverside
		this.dateFormatHiddId = "type_"+this.goodFieldName+"_"+this.id;
		this.dateFormatHiddElem = $("#"+this.dateFormatHiddId);
		if(this.useDatePicker)
			this.imgCal = $('#imgCal_'+this.valContId);
		//console.log(1234);
	},
	
	/**
	 * Override addValidation
	 * @param {string} type
	 */	
	addValidation: function(type){
		// date field can be validated only as isRequired
		if (type!="isRequired"){
			return false;
		}
		// call parent
		this.superclass.addValidation.call(this, type);
	}
	
	
	
});
/**
 * Class for date fields with textField value editor
 * If there is datePicker, instance of Runner.controls.DateTextField should be passed as target
 */
Runner.controls.DateTextField = Runner.extend(Runner.controls.DateField, {
			
	/**
	 * Overrides parent constructor
	 * @param {Object} cfg
	 */
	constructor: function(cfg){
		this.addEvent(["change", "keyup"]);		
		Runner.controls.DateTextField.superclass.constructor.call(this, cfg);			
	},
	getValue: function(){
		var parsedTime = parse_datetime(this.valueElem.val(),this.dateFormat);		
		if (parsedTime == null){
			return "";
		}else{
			return parsedTime;
		}		
	},
	/**
	 * Set value, also change value in hidden field
	 * @method
	 * @param {Object} val
	 * @return {bool} if passed correct Date object, otherwise false
	 */
	setValue: function(newDate, triggerEvent)	{
		// if we pass Date object, so we use it
		if (typeof newDate == 'object'&&newDate!=null){
			//console.log(newDate, "newDate as obj");
			// call old date parse function, they will change in future
			var dt = print_datetime(newDate,this.dateFormat,this.showTime);
			//set value in edit textfield
			this.valueElem.val(dt);
			// if we need to set new date in hidden fields for datepicker
			if (this.useDatePicker){
				dt = print_datetime(newDate,-1,this.showTime);
				this.datePickerHiddElem.val(dt);
			}
			this.validate();
			return true;
		}else{
			//console.log(newDate, "newDate as ''");
			// set empty value = ""
			this.valueElem.val("");
			// if we need to set new date in hidden fields for datepicker
			if (this.useDatePicker){				
				this.datePickerHiddElem.val("");
			}
			this.validate();
			return false;
		}		
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change cb called')
			this.fireEvent("change");
		}	
	},
	/**
	 * Custom function for onblur event
	 * @param {Object} e
	 */
	"blur": function(e)
	{
		// call parent
		this.stopEvent(e);
		// set values to hidden fields
		var vRes = this.validate();
		if (vRes.result && this.useDatePicker  && this.getValue())
			this.setValue(this.getValue());
	},
	/**
	 * Sets disable attr true
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	setDisabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = true;
			if(this.imgCal!=null)
				this.imgCal.css('visibility','hidden');
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
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			if(this.imgCal!=null)
				this.imgCal.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Clone html for iframe submit
	 */
	getForSubmit: function(){
		return [this.valueElem.clone(), this.dateFormatHiddElem.clone()];
	},
	/**
	 * Return date value as string
	 * @return {string}
	 */
	getStringValue: function(){
		var dateObj = this.getValue();
		if (dateObj===""){
			return "";
		}else{
			return dateObj.getFullYear() + '-' + (dateObj.getMonth()+1) + '-' + dateObj.getDate();
		}
	}	
});

/**
 * Class for date fields with three dropdowns value editor
 * If there is datePicker, instance of Runner.controls.DateDropDown should be passed as target
 */
Runner.controls.DateDropDown = Runner.extend(Runner.controls.DateField, {

	/**
	 * Hidden element for date value
	 * value for server submit
	 * @type {Object} type
	 */
	hiddValueElem: null,
	/**
	 * Hidden element id
	 * @type {string}
	 */
	hiddElemId: "",
	
	/**
	 * Overrides parent constructor
	 * @param {Mixed} cfg
	 */
	constructor: function(cfg){	
		cfg.stopEventInit=true;		
		// call parent
		Runner.controls.DateDropDown.superclass.constructor.call(this, cfg);
		//Overrides value elem. For handling 3 dropdowns
		this.valueElem = {
			"day": $("#day"+this.valContId),
			"month": $("#month"+this.valContId),
			"year": $("#year"+this.valContId)		
		};
		// get default value
		this.defaultValue = this.getValue();
		// use onchange instead onblur for DD
		this.addEvent(["change"]);
		// onblur not usable
		this.killEvent("blur");
		//event elems 
		this.elemsForEvent = [this.valueElem["day"].get(0), this.valueElem["month"].get(0), this.valueElem["year"].get(0)];		
		// init events handling
		this.init();		
		//console.log(this.elemsForEvent, "elemsForEvent in dd constr");
		//console.log(this.events, "DD events")	
		//console.log(this.valueElem, "this.valueElem DD events")	
		// add hidden elems
		this.hiddElemId = "value_"+this.goodFieldName+"_"+this.id;
		this.hiddValueElem = $("#"+this.hiddElemId);
		// add input type attr
		this.inputType = "3dd";
	},	
	/**
	 * Custom function for onchange event
	 * @param {Object} e
	 */
	"change": function(e)
	{
		//console.log("change fired");
		this.stopEvent(e);
		var vRes = this.validate();
		if (vRes.result){
			this.setValue(this.getValue());
		}
		return vRes;
	},			
	"blur": Runner.emptyFn,	
	/**
	 * Gets values from dropdowns and returns it in YYYY-mm-dd-hh-ss format
	 */		
	getValue: function()
	{		
		//console.log('this.valContId = ',this.valContId);
		// date pieces from dropdowns
		if (this.valueElem["day"]){
			var dayVal = this.valueElem["day"].val();
		}else{
			return false;
		}
		if (this.valueElem["month"]){
			var monthVal = this.valueElem["month"].val();
		}else{
			return false;
		}
		if (this.valueElem["year"]){
			var yearVal = this.valueElem["year"].val();
		}else{
			return false;
		}
		
		var date = new Date(yearVal, monthVal-1, dayVal, 00, 00, 00);
		return date;
	},
	/**
	 * Sets value to dropdowns
	 * @param {Date} newDate
	 * @return {bool}Returns true if success, otherwise false
	 */
	setValue: function(newDate, triggerEvent)	{
		// if we pass Date object, so we use it
		if (typeof newDate == 'object'&&newDate!=null){
			this.hiddValueElem.get(0).value =  newDate.getFullYear() + '-' + (newDate.getMonth()+1) + '-' + newDate.getDate();		
			this.valueElem["day"].get(0).selectedIndex = newDate.getDate();
			this.valueElem["month"].get(0).selectedIndex = newDate.getMonth()+1;
			for(var i=0; i<this.valueElem["year"].get(0).options.length;i++)
			{
				if(this.valueElem["year"].get(0).options[i].value==newDate.getFullYear())
				{
					this.valueElem["year"].get(0).selectedIndex=i;
					break;
				}
			}
			if(this.useDatePicker)
				this.datePickerHiddElem.get(0).value = newDate.getDate() + '-' + (newDate.getMonth()+1) + '-' + newDate.getFullYear();
			this.validate();
			return true;
		}else{
			//console.log(newDate, "newDate as ''");
			// set empty value = ""
			this.valueElem["day"].get(0).selectedIndex = 0;
			this.valueElem["month"].get(0).selectedIndex = 0;
			this.valueElem["year"].get(0).selectedIndex = 0;
			// if we need to set new date in hidden fields for datepicker
			if (this.useDatePicker){				
				this.datePickerHiddElem.val("");
			}
			this.validate();
			return false;
		}
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change cb called')
			this.fireEvent("change");
		}	
	},
	/**
	 * Overrides parent function for three element control
	 */
	setDisabled: function()
	{		
		this.valueElem["day"][0].disabled = true;
		this.valueElem["month"][0].disabled = true;
		this.valueElem["year"][0].disabled = true;
		if(this.imgCal!=null)
			this.imgCal.css('visibility','hidden');
		return true;
	},
	/**
	 * Overrides parent function for three element control
	 */
	setEnabled: function()
	{
		this.valueElem["day"][0].disabled = false;
		this.valueElem["month"][0].disabled = false;
		this.valueElem["year"][0].disabled = false;
		if(this.imgCal!=null)	
			this.imgCal.css('visibility','visible');
		return true;
	},
	/**
	 * Overrides parent function for three element control
	 */
	/*hide: function(){
		this.valueElem["day"].css("display", "none");
		this.valueElem["month"].css("display", "none");
		this.valueElem["year"].css("display", "none");
		
		return true;
	},*/
	/**
	 * Overrides parent function for three element control
	 */
	/*show: function(){
		this.valueElem["day"].css("display", "block");
		this.valueElem["month"].css("display", "block");
		this.valueElem["year"].css("display", "block");
		
		return true;
	},*/
	/**
	 * Clone html for iframe submit
	 * @method
	 */
	getForSubmit: function(){
		return [this.hiddValueElem.clone(), this.dateFormatHiddElem.clone()];
	},	
	/**
	 * Sets focus to the element, override
	 * @method
	 * @param bool
	 * @return {bool}
	 */
	setFocus: function(triggerEvent){
		if (this.valueElem["day"].get(0).disabled != true){
			// set focus to first dropdown
			this.valueElem["day"].get(0).focus();
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
	 * Checks if control value is empty. 
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		if (this.valueElem["day"].val() == "" || this.valueElem["month"].val() == "" || this.valueElem["year"].val() == ""){
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Return date value as string
	 * @return {string}
	 */
	getStringValue: function(){
		return this.hiddValueElem.val();
	}
});

