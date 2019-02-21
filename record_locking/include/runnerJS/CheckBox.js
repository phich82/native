
/**
 * Multiple select control class
 * @requires Runner.controls.LookupWizard
 */
Runner.controls.CheckBoxLookup = Runner.extend(Runner.controls.LookupWizard, {
	/**
	 * type hidd element id
	 * @type String
	 */
	typeElemId: "",
	/**
	 * type hidd element jQuery obj
	 * @type {object}
	 */
	typeElem: null,
	/**
	 * Number of checkboxes
	 * @type Number
	 */
	checkBoxCount: 0,
	/**
	 * Array of checkbox jQuery elements
	 * @type {array}
	 */
	checkBoxesArr: [],
	/**
	 * String from which checkbox name attr starts, for getting
	 * @type String
	 */
	checkBoxNameAttr: "",
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */
	constructor: function(cfg){
		//call parent
		Runner.controls.CheckBoxLookup.superclass.constructor.call(this, cfg);
		// add input type
		this.inputType = "checkbox";
		// type hidd element id
		this.typeElemId = "type_"+this.goodFieldName+"_"+this.id;
		// type hidd element jQuery obj
		this.typeElem = $("#"+this.typeElemId);
		// span where situated data of checkbox
		this.dataCheckBoxId = "data_"+this.valContId;
		// add checkbox count property
		this.checkBoxCount = cfg.checkBoxCount;		
		// add checkboxes elements
		this.checkBoxesArr = new Array();
		for(var i=0;i<this.checkBoxCount;i++){
			// arr of jQuery checkboxes
			this.checkBoxesArr.push($("#"+this.valContId+"_"+i));
			//elems for event are checkboxes
			//this.elemsForEvent.push($("#"+this.valContId+"_"+i).get(0));
			this.elemsForEvent.push(this.checkBoxesArr[i].get(0));
		}		
		// initialize control disabled
		if (cfg.disabled==true || cfg.disabled=="true"){
			this.setDisabled();
		}
		// WHICH EVENTS NEED TO ADD ?
		this.addEvent(["click"]);
		// init events
		this.init();
		//set defaultValue
		this.defaultValue = this.getValue();
		// get jQuery array of checkboxes as value element
		this.checkBoxNameAttr = 'value_'+this.goodFieldName+'_'+this.id;
		this.valueElem = $('input[@name^='+this.checkBoxNameAttr+']');
	},
	/**
	 * Sets array of values to checkboxes
	 * @method
	 * @param {array} valsArr
	 * @return {Boolean} true if success otherwise false
	 */	
	setValue: function(valsArr, triggerEvent){
		var checkCount = 0;
		//loop for all checkboxes
		for(var i=0;i<this.checkBoxCount;i++){
			// set unchecked
			this.checkBoxesArr[i].get(0).checked = false;
			// loop for all vals
			for(var j=0; j<valsArr.length;j++){
				// if check box val same as val in arr to check
				if (this.checkBoxesArr[i].val() == valsArr[j]){
					this.checkBoxesArr[i].get(0).checked = true;
					checkCount++;
					break;
				}// eo if
			}// eo for
		}// eo for
		
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on click cb called')
			this.fireEvent("click");
		}
		// check number of checked boxes
		if (checkCount == valsArr.length && checkCount<=this.checkBoxCount && valsArr.length<=this.checkBoxCount){
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Returns array of checked values
	 * @return {array}
	 */
	getValue: function(){
		var checkedArr = this.getCheckedBoxes(), valsArr = [];		
		// get value from each checkbox
		for(var i=0;i<checkedArr.length;i++){
			valsArr.push(checkedArr[i].val());
		}		
		return valsArr;
	},
	/**
	 * Checks if control value is empty. Used for isRequired validation
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		// if length of values arr == 0
		if (this.getValue().length == 0){
			return true;
		}else{
			return false;
		}
	},	
	/**
	 * Sets disable attr true
	 * @method
	 */
	setDisabled: function(){
		for(var i=0;i<this.checkBoxesArr.length;i++){
			this.checkBoxesArr[i].get(0).disabled = true;
		}			
		return true;
	},
	
	/**
	 * Sets disaqble attr false
	 * @method
	 */
	setEnabled: function(){
		for(var i=0;i<this.checkBoxCount;i++){
			this.checkBoxesArr[i].get(0).disabled = false;
		}			
		return true;
	},
	
	setDisabledShowCheckedBoxes: function()
	{
		for(var i=0;i<this.checkBoxCount;i++)
		{
			if(this.checkBoxesArr[i].get(0).checked)
				this.checkBoxesArr[i].get(0).disabled = true;
			else{
					var dataId = $('#'+this.dataCheckBoxId+'_'+i);
					this.checkBoxesArr[i].css("display", "none");
					dataId.css("display", "none");
					dataId.next().css("display", "none");
				}	
		}			
		return true;
	},
	
	/**
	 * Returns array of cheked checkBoxes
	 * @return {array}
	 */
	getCheckedBoxes: function(){
		var chekedArr = [];
		// get value from each checkbox
		for(var i=0;i<this.checkBoxesArr.length;i++){			
			if (this.checkBoxesArr[i].get(0).checked){
				chekedArr.push(this.checkBoxesArr[i]);
			}
		}
		
		return chekedArr;
	},
	/**
	 * Returns array of jQuery object for inline submit
	 * @return {array}
	 */
	getForSubmit: function(){
		var checkedArr = this.getCheckedBoxes(), cloneArr = [];		
		// get clone of each checkbox
		for(var i=0;i<checkedArr.length;i++){
			cloneArr.push(checkedArr[i].clone());
		}		
		return cloneArr;		
	},
	// =============== NEW CODE FROM DD ====================
	/**
	 * Deletes all checkBoxes from ctrl
	 * @method
	 */
	clearCheckBoxes: function(){
		for(var i=0;i<this.checkBoxesArr.length;i++){
			this.checkBoxesArr[i].remove();
		}
		//this.spanContElem.find('div').empty();
		this.checkBoxesArr = [];
		this.checkBoxCount = 0;
	},
	/**
	 * Adds option to select
	 * may be need to add options to specified index?
	 * @param {string} text
	 * @param {string} val
	 */
	addCheckBox: function(text, val){		
		var newCheckBoxId = this.valContId+"_"+this.checkBoxCount;
		// create new checkbox input
		this.spanContElem.find('div').append('<input type="checkbox" id="'+newCheckBoxId+'" name="'+newCheckBoxId+'[]" value="val">'+text+'<br/>');		
		this.checkBoxesArr.push($("#"+newCheckBoxId));		
		this.checkBoxCount++;
	},
	/**
	 * Add options from array.
	 * Array must have such structure:
	 * array[0] = value, array[1] = text,
	 * array[2] = value, array[3] = text,
	 * 2*i - indexes of values; 2*i+1 - indexes of text. I starts from 0   
	 * @param {array} optionsArr
	 */
	addCheckBoxArr: function(optionsArr){			
		for(var i=0; i < optionsArr.length - 1; i=i+2){ 
			this.addOption(optionsArr[i+1], optionsArr[i]);
		}
	},	
	
	
	/**
	 * First loading, without ajax. Should be called directly
	 * @param {string} txt unparsed values for options
	 * @param {string} selectValue unparsed values of selected options
	 */
	preload: function(txt, selectValue){
		// parse input content
		var parsedOptionsContent = this.parseContentToValues(txt), parsedSelected = this.parseContentToValues(selectValue);
		//console.log(parsedOptionsContent, 'parsedOptionsContent');
		// clear all old options
		this.clearCheckBoxes();		
		// load options
		this.addCheckBoxArr(parsedOptionsContent);
		// if only one values except please select, so choose it
		//console.log(this.optionsDOM.length, 'this.optionsDOM.length in preload');
		if (this.checkBoxCount==1){
			this.setValue([this.checkBoxesArr[0].val()], false);	
		}	
		// don't need to use ajax reload call
		this.setValue([selectValue], false);		
	},	
	/**
	 * Reloading dropdown. Called by change event handler
	 * @param {string} value of master ctrl
	 */
	reload: function(masterCtrlValue){	
		//console.log(masterCtrlValue, 'masterCtrlValue');
		var fName = this.fieldName, tName = this.table, rowId = this.id;
		//, valForAjax = typeOf(masterCtrlValue) == 'array' ? masterCtrlValue[0] : masterCtrlValue;
		
		//ajax params
		var ajaxParams = {
			// ctrl fieldName
			field: myEncode(fName),
			// value of master ctrl. Only first val from arr, because multiDrop cannot be master
			value: myEncode(masterCtrlValue),
			// tag name of ctrl
			type: this.valueElem[0].tagName,
			// random value for prevent caching
		    rndVal: (new Date().getTime())
		};		
		// get content		
		$.get(this.shortTableName+"_autocomplete.php", ajaxParams, function(txt, textStatus){
			// get control
			var ctrl = Runner.controls.ControlManager.getAt(tName, rowId, fName);			
			// clear all options
			ctrl.clearOptions();			
			// parse string with new options
			var parsedOptionsContent = ctrl.parseContentToValues(txt);
			//console.log(parsedOptionsContent, 'parsedOptionsContent');
			// if bad data from server, or timeout ends..
			if(parsedOptionsContent===false){
				return false;
			}
			// load options
			ctrl.addOptionsArr(parsedOptionsContent);			
			// if only one values except please select, so choose it
			if (ctrl.checkBoxCount==1){
				ctrl.setValue([ctrl.checkBoxesArr[0].val()], false);	
			}			
			// fire change event, for reload dependent ctrls
			ctrl.fireEvent("change");	
		});
	},
	/**
	 * Drop custom function for blur event
	 * @param {Object} e
	 */
	"blur": Runner.emptyFn,
	
	"click": this["change"]
	
});
