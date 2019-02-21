/**
 * Base abstract class for all file controls. Should not be created directly.
 * @requires Runner.controls.Control
 */
Runner.controls.FileControl = Runner.extend(Runner.controls.Control, {
	/**
	 * Radio DOM elem id
	 * @type {string} 
	 */
	radioElemsName: "",
	/**
	 * Radio jQuery obj
	 * @type {Object} 
	 */
	radioElems: {},
	
	/**
	 * Override parent contructor
	 * @constructor
	 * @param {Object} cfg
	 */
	constructor: function (cfg){
		cfg.stopEventInit = true;
		//call parent
		Runner.controls.FileControl.superclass.constructor.call(this, cfg);		
		// add radio DOM elem ID		
		this.radioElemsName = "type_"+this.goodFieldName+"_"+this.id;
		// add radio DOM elem ID,
		this.getRadioControls();
		// add events
		this.events = ["change"];
		// clear blur event
		delete this["blur"];
		//event elem
		this.elemsForEvent = [this.valueElem.get(0)];
		// init events
		this.init();
	},
	/**
	 * Clear blur event handler
	 */
	"blur": Runner.emptyFn,
	/**
	 * Add change event base handler
	 * @param {Object} e
	 */
	"change": function(e){
		// stop event
		this.stopEvent(e);		
		//console.log(this.goodFieldName, '1');
		// set radio button to update
		this.changeRadio("updateRadio");
		// validate and return validation result
		return this.validate();		
	},
	/**
	 * Radio buttons switcher. Call when need change radio
	 * @param {string} radioToCheck Name of radio button.
	 */
	changeRadio: function(radioToCheck){
		for(var radio in this.radioElems){			
			// if exists radio button
			if (radio == radioToCheck && this.radioElems[radio]!=false){
				this.radioElems[radio].elem.get(0).checked = true;
				this.radioElems[radio].cheked = true;
			// if not exists return false	
			}else if(radio == radioToCheck && this.radioElems[radio]==false){
				return false;
			// switch other radios	
			}else if(this.radioElems[radio]!=false){
				this.radioElems[radio].elem.get(0).checked = false;
				this.radioElems[radio].cheked = false;
			}
		}		
		// in success
		return true;
	},
	/**
	 * Get object which contains radio elems
	 * @method
	 * @return {bool}
	 */
	getRadioControls: function(){
		this.radioElems = new Object();
		var keepRadio = $('#'+this.radioElemsName+'_keep');
		var deleteRadio = $('#'+this.radioElemsName+'_delete');
		var updateRadio = $('#'+this.radioElemsName+'_update');
		// create radioElems obj
		this.radioElems["keepRadio"] = keepRadio.length ? {elem: keepRadio, cheked: true} : false;
		
		this.radioElems["deleteRadio"] = deleteRadio.length ? {elem: deleteRadio, cheked: false} : false;
		this.radioElems["updateRadio"] = updateRadio.length ? {elem: updateRadio, cheked: false} : false;
		return true;
	},
	/**
	 * Return name of cheked radio
	 * @return {string}
	 */
	getChekedRadio: function(){
		for(var radio in this.radioElems){		
			if (this.radioElems[radio]!=false && this.radioElems[radio].cheked === true){
				return radio;
			}
		}
		return false;
	}
});

/**
 * Class for image field controls.
 * @requires Runner.controls.FileControl
 */
Runner.controls.ImageField = Runner.extend(Runner.controls.FileControl, {
	/**
	 * Override parent contructor
	 * @constructor
	 * @param {Object} cfg
	 */
	constructor: function(cfg){
		//call parent
		Runner.controls.ImageField.superclass.constructor.call(this, cfg);	
	},
	/**
	 * Returns array of jQuery object for inline submit
	 * @return {array}
	 */
	getForSubmit: function(){
		return [this.valueElem.clone()];
	}
	
});

/**
 * Class for file field controls. For images use Runner.controls.ImageField
 * @requires Runner.controls.FileControl
 */
Runner.controls.FileField = Runner.extend(Runner.controls.FileControl, {	
	/**
	 * Indicates if need to add timeStamp to fileName
	 * @type {bool} 
	 */
	addTimeStamp: false,
	/**
	 * ID of filename elem
	 * @type {string}
	 */
	fileNameElemId: "",
	/**
	 * Filename textfield jQuery object
	 * @param {Object} 
	 */
	fileNameElem: null,
	/**
	 * ID of hidden fileName DOM elem
	 * @type String
	 */
	fileHiddElemId: "",
	/**
	 * jQuery object of hidden fileName DOM elem
	 * @type {object} 
	 */
	fileHiddElem: null,
	/**
	 * Override parent contructor
	 * @constructor
	 * @param {Object} cfg
	 * @param {bool} cfg.addTimeStamp
	 */
	constructor: function (cfg){
		cfg.stopEventInit = true;
		//call parent
		Runner.controls.FileField.superclass.constructor.call(this, cfg);		
		// add fileName DOM elem	
		this.fileNameElemId = "filename_"+this.goodFieldName+"_"+this.id;	
		this.fileNameElem = $("#"+this.fileNameElemId).length ? $("#"+this.fileNameElemId) : null; 
		// add fileName hidden DOM elem
		this.fileHiddElemId = "filenameHidden_"+this.goodFieldName+"_"+this.id;	
		this.fileHiddElem = $("#"+this.fileHiddElemId).length ? $("#"+this.fileHiddElemId) : null;
		//timeStamp to fileName indicator
		this.addTimeStamp = cfg.addTimeStamp ? cfg.addTimeStamp : false;
		// add radio buttons style switchers
		for (radio in this.radioElems){		
			// if exists radio	
			if (this.radioElems[radio]){
				// create closure event handler
				var objScope = this;
				var onRadioClickHandler = function(e){					
					// get name of radio object
					var radioTypeStartFrom = this.id.lastIndexOf('_');
					var radioTypeName = this.id.substring(radioTypeStartFrom+1)+'Radio';
					// change styles
					objScope.changeControlsStyles(radioTypeName);
				}
				// add handler
				this.radioElems[radio].elem[0].onclick = onRadioClickHandler//.call(this, this)
			}
		}
	},
	
	/**
	 * Override addValidation
	 * @method
	 * @param {string} type
	 */
	addValidation: function(type){
		// date field can be validated only as isRequired
		if (type!="isRequired"){
			return false;
		}
		// call parent
		Runner.controls.FileField.superclass.addValidation.call(this, type);
	},
	/**
	 * Cuts name of file from path
	 * @param {string} path
	 * @return {string}
	 */
	getFileNameFromPath: function(path){
		var wpos=path.lastIndexOf('\\'); 
		var upos=path.lastIndexOf('/'); 
		var pos=wpos; 
		if(upos>wpos)
			pos=upos; 
		return path.substr(pos+1);
	},
	/**
	 * Override setValue function, for files need to change radio control status
	 * @method
	 * @param {file} val
	 */
	setValue: function(val, triggerEvent){
		var valWithStamp = "", fileName = "";
		// if need to get filename without path
		if (this.fileNameElem != null || this.addTimeStamp){
			fileName = this.getFileNameFromPath(this.valueElem.val());
		}
		// add timestamp if needed
		if (this.addTimeStamp){			
			var valWithStamp = addTimestamp(fileName);
		}
		// if name element exists, set new value		
		if (this.fileNameElem != null){
			//console.log(path.substr(pos+1), 'path.substr(pos+1)');
			this.fileNameElem.val(valWithStamp || fileName);
		}
		// call change handler if needed
		//if (triggerEvent===null || triggerEvent===true){
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change called')
			this.fireEvent("change");
		}
	},	
	/**
	 * Change file value event handler. 
	 * Changes radio to update, validates, and change fileName if file pass validation
	 * @method
	 * @param {Object} e
	 */
	"change": function(e){
		this.stopEvent(e);		
		this.changeRadio("updateRadio");
		var vRes = this.validate();		
		if (vRes.result){			
			var vl = this.getValue();
			this.setValue(vl, false);
		}
		return vRes.result;
	},
	/**
	 * Clone html for iframe submit
	 * @method
	 * @return {array}
	 */
	getForSubmit: function(){
		// array of fileValue, and cheked radio
		var submitElemsArr = [this.valueElem.clone(), this.radioElems.find('[@cheked=true]').clone()]; 
		// also add fileName textField if it exists
		if (this.fileNameElem != null){
			submitElemsArr[submitElemsArr.length] = this.fileNameElem.clone();
		}		
		return submitElemsArr;
	},
	/**
	 * Override radio buttons switcher, add call change styles method
	 * @param {string} radioToCheck
	 */
	changeRadio: function(radioToCheck){
		// change styles
		this.changeControlsStyles(radioToCheck);
		// call parent
		Runner.controls.FileField.superclass.changeRadio.call(this, radioToCheck);		
	},
	/**
	 * Change styles and set disabled filename field
	 * @param {Object} radioToCheck
	 */
	changeControlsStyles: function(radioToCheck){
		//console.log(radioToCheck, 'radioToCheck');
		// if such radio button defined
		if (!this.radioElems[radioToCheck]){
			//console.log(this.radioElems[radioToCheck], radioToCheck + ' is null');
			return false;
		}
		// if there is filename that need to be changed
		if (this.fileNameElem == null) {
			//console.log(this.fileNameElem, 'fileNameElem is null');
			return false;
		}		
		// if choosed delete
		if (radioToCheck == "deleteRadio"){
			//console.log(radioToCheck, 'if deleteRadio');
			this.fileNameElem.css('backgroundColor','gainsboro');
			this.fileNameElem[0].disabled=true;
			return true;
		// if choosed update or keep
		}else if(radioToCheck == "updateRadio" || radioToCheck == "keepRadio"){
			//console.log(radioToCheck, 'if updateRadio || keepRadio');			
			this.fileNameElem.css('backgroundColor','white');
			this.fileNameElem[0].disabled=false;
			return true;
		// in other way return false
		}else{
			return false;
		}
	},
	/**
	 * Checks if control value is empty. Used for isRequired validation
	 * For files has specific criterias
	 * @override
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		if (this.fileHiddElem && this.fileHiddElem.val()!=""){
			return this.radioElems["deleteRadio"].cheked === false;
		}else{
			return this.getValue.toString != "" && this.radioElems["updateRadio"].cheked === true;
		}
	},
	/**
	 * Get fileName from fileName type text elem.
	 * @return {string}
	 */
	getFileName: function(){
		if (this.fileHiddElem){
			return this.fileHiddElem.val();
		}else{
			return false;
		}
	},
	/**
	 * Set fileName to fileName type text elem.
	 * @param {string} fileName
	 * @return {Boolean}
	 */
	setFileName: function(fileName){
		if (this.fileHiddElem){
			this.fileHiddElem.val(fileName);
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Returns array of jQuery object for inline submit
	 * @return {array}
	 */
	getForSubmit: function(){
		var radio = this.getChekedRadio();
		var cloneArr = [this.valueElem.clone()];
		
		if (radio){
			cloneArr.push(this.radioElems[radio].elem.clone());
		}
		if (this.fileNameElem){
			cloneArr.push(this.fileNameElem.clone());
		}
		if (this.fileHiddElem){
			cloneArr.push(this.fileHiddElem.clone());
		}
		return cloneArr;
	}
	
	
});