/**
 * Class for time fields with textField value editor, and timepicker optional
 */
Runner.controls.TimeField = Runner.extend(Runner.controls.Control, {
	/**
	 * Id of type elem. Need for submit, which used on serverside
	 * @type {string}
	 */
	typeHiddId: "",
	/**
	 * jQuery object of type elem format hidden element, which used on serverside
	 * @type {Object} 
	 */
	typeHiddElem: null,	
	/**
	 * Overrides parent constructor
	 * @param {Object} cfg
	 * @param {bool} cfg.useDatePicker
	 */
	constructor: function(cfg){
		// call parent
		Runner.controls.TimeField.superclass.constructor.call(this, cfg);	
		// add hidden field for date format on serverside
		this.typeHiddId = "type_"+this.goodFieldName+"_"+this.id;
		this.typeHiddElem = $("#"+this.typeHiddId);
		this.imgTime = $("#trigger-test-"+this.valContId);
		this.addEvent(["change"]);
		this.init();
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
		Runner.controls.TimeField.superclass.addValidation.call(this, type);
	},
	/**
	 * Clone html for iframe submit
	 * @method
	 * @return {array}
	 */
	getForSubmit: function(){
		return [this.valueElem.clone(), this.typeHiddElem.clone()];
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr true
	 * Sets hidden css style true for image "time"
	 * @method
	 */
	setDisabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = true;
			this.imgTime.css('visibility','hidden');
			return true;
		}else{
			return false;
		}			
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr false
	 * Sets visible css style true for image "time"
	 * @method
	 */
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			this.imgTime.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	},
	"change":function(e)
	{
		return this.validate().result;
	}
});