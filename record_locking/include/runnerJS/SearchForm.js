/// <reference path="Runner.js" />
/**
 * Search form controller. Need for submit form in advanced and panel mode
 */
Runner.search.SearchForm = Runner.extend(Runner.emptyFn, {
	/**
    * Id of page, used when page loades dynamicly
    * @type {int}
    */
    id: -1,  
	/**
     * Name of table for which instance of class was created
     * @type string
     */
    tName: "",
	/**
	 * Type of search: panel on list, or advanced search page
	 * @type String
	 */
	searchType: "panel",  
	/**
     * jQuery obj of top radio with conditions
     * @type {obj}
     */
    conditionRadioTop: null,    
	/**
     * jQuery obj
     * @type 
     */
    srchForm: null,
	/**
    * ctrls map. Used for indicate which index conected with which search ctrl
    * @type obj
    */    
    ctrlsShowMap: null,
    
	/**
     * Override parent contructor
     * Add interaction with server
     * @param {obj} cfg
     */
    constructor: function(cfg){  
    	this.ctrlsShowMap = {};
    	// copy properties from cfg to controller obj
        Runner.apply(this, cfg);
    	//call parent
    	Runner.search.SearchForm.superclass.constructor.call(this, cfg);
        // get form object
        var srchFormId = 'frmSearch'+this.id;
        this.srchForm = $('#'+srchFormId);
        // radio with contion choose or|and
        this.conditionRadioTop = $('input:radio[name=srchType]');
    },
    /**
     * Add to hidden fields to search form
     * @param {string} val
     * @param {string} id
     * @param {string} type
     */
    addToForm: function(val, id, type){
    	if (typeof val == 'undefined'){
    		return false;
    	}
    	// lookup ctrls may return array value, for submit take only first
		val = (typeof val === 'object') ? val[0] : val;
    	type = (type ? type : 'hidden');
    	
    	var formElem = this.srchForm.find('input[name='+id+']');
    	if (formElem.length){
    		formElem.val(val.toString().entityify());
    	}else{
    		var elemHtml = '<input type="'+type+'" name="'+id+'" value="'+val.toString().entityify()+'" />';
    		this.srchForm.append(elemHtml);
    	}
    	
    	return true;
    },
    /**
     * Create and submit form 
     */
    submitSearch: function(){    	
    	// add common search params
    	this.addToForm('integrated', 'a');
    	this.addToForm(this.id, 'id');
    	    	
    	// add radio values
    	for (var i=0;i<this.conditionRadioTop.length;i++){
    		if(this.conditionRadioTop[i].checked == true){
    			this.addToForm(this.conditionRadioTop[i].value, 'criteria');
    			break;
    		}
    	}
    	    	
    	// for interator, field counter
		var j=1, notVal=''; 
		// add search params for each field
    	for(var fName in this.ctrlsShowMap){    		
    		// loop through all ctrls, except cached and deleted
    		for(var ind in this.ctrlsShowMap[fName]){    			
    			// get ctrls map for field name
    			var fMap = this.ctrlsShowMap[fName][ind];
    			// add ctrls vals    			
    			var ctrl1 = Runner.controls.ControlManager.getAt(this.tName, ind, fName, fMap[0]);
    			// add only non empty vals
    			if (ctrl1.isEmpty()){
    				continue;
    			}
    			// add first value and type
    			this.addToForm(ctrl1.ctrlType, 'type'+j);    	
    			var ctrl1Val = ctrl1.getStringValue();    			
    			this.addToForm(ctrl1Val, 'value'+j+'1');
    			// add fName to form
    			this.addToForm(fName, 'field'+j);
    			// add option to form
    			var srchCombo = $('#'+this.getComboId(fName, ind)); 
    			var comboVal = srchCombo.val();
    			
    			if (srchCombo.val().indexOf('NOT') == 0){
    				comboVal = comboVal.substr(4);
    			}
    			this.addToForm(comboVal, 'option'+j);
    			// add not checkBox to form
    			var srchCheckBox = $('#'+this.getCheckBoxId(fName, ind));
    			notVal = '';
    			// if there is any checkbox, then use its value, else parse value from combo
    			if (srchCheckBox.length){
    				notVal = srchCheckBox[0].checked ? 'on' : '';
    			}else{
    				notVal = srchCombo.val().indexOf('NOT') == 0 ? 'on' : '';
    			}
    			this.addToForm(notVal, 'not'+j); 
    			// if search type between and exists second ctrl
    			if (srchCombo.val().toLowerCase() == 'between' && fMap[1]){
    				var ctrl2 = Runner.controls.ControlManager.getAt(this.tName, ind, fName, fMap[1]);
    				var ctrl2Val = ctrl2.getStringValue();	    			
    				this.addToForm(ctrl2Val, 'value'+j+'2');
    			}    			
    			j++;
    		}    		
    	} 
    	//console.log(this.srchForm, 'form submit turned off');
    	// submit
    	this.srchForm.submit();
    },
    /**
     * Register ctrl in show map
     * @param {string} fName
     * @param {string} ind
     * @param {string} ctrlIndArr
     */
    addToShowMap: function(fName, ind, ctrlIndArr){
    	// create field names and indexes if they not created
    	!this.ctrlsShowMap[fName] ? this.ctrlsShowMap[fName] = {} : '';
    	!this.ctrlsShowMap[fName][ind] ? this.ctrlsShowMap[fName][ind] = {} : '';
    	// add ctrls indexes array
    	this.ctrlsShowMap[fName][ind] = ctrlIndArr;    	
    },
    /**
     * Adds block to map, regs its components and ands HTML
     * @param {} fName
     * @param {} ind
     * @param {} ctrlIndArr
     * @param {} blockHTML
     */
    addRegCtrlsBlock: function(fName, ind, ctrlIndArr){
    	// add to map
    	ctrlIndArr ? this.addToShowMap(fName, ind, ctrlIndArr) : '';
    },
    /**
     * Return search type combo id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getComboId: function(fName, ind){
    	return "srchOpt_" + ind + "_" + Runner.goodFieldName(fName);
    },
    /**
     * Return search checkbox id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getCheckBoxId: function(fName, ind){
    	return "not_" + ind + "_" + fName;
    },
    showAllSubmit: function(){
    	this.addToForm(this.id, 'id');
    	this.addToForm('showall', 'a');
    	// submit
    	this.srchForm.submit();
    },
    /**
     * Submit for for return on list page
     */
    returnSubmit: function(){
    	this.addToForm(this.id, 'id');
    	this.addToForm('return', 'a');
    	// submit
    	this.srchForm.submit();
    },
    /**
     * Resets form ctrls, for panel should be overriden
     * @return {Boolean}
     */
    resetCtrls: function(){
    	Runner.controls.ControlManager.resetControlsForTable(this.tName);
		return false;
    }    
});