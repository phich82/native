/**
 * List page with search lookup control class
 * @requires Runner.controls.TextFieldLookup
 */
Runner.controls.ListPageLookup = Runner.extend(Runner.controls.TextFieldLookup, {
	/**
	 * id of a tag, which opens search div
	 * @type {String}
	 */
	selectLinkId: "",
	/**
	 * jQuery object of a tag, which opens search div
	 * @type {object}
	 */
	selectLinkElem: null,
	
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */
	constructor: function(cfg){
		//call parent
		Runner.controls.ListPageLookup.superclass.constructor.call(this, cfg);
		// init events handling
		this.init();	
		// a select tag id
		this.selectLinkId = "open_lookup_"+this.goodFieldName+"_"+this.id;
		// a select tag jQuery element
		this.selectLinkElem = $("#"+this.selectLinkId);
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr true
	 * Sets hidden css style true for image selectLinkElem
	 * @method
	 */
	setDisabled: function()
	{
		if (this.displayElem.get(0))
		{
			this.displayElem.get(0).disabled = true;
			this.selectLinkElem.css('visibility','hidden');
			return true;
		}else{
			return false;
		}			
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr false
	 * Sets visible css style true for image selectLinkElem
	 * @method
	 */
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			this.selectLinkElem.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	}
});