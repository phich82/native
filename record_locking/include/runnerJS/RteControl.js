/**
 * Common base class for rte fields
 */
Runner.controls.RTEField = Runner.extend(Runner.controls.Control, {
	
	iframeElemId: "",
	iframeElem: null,
	
	constructor: function(cfg){
		// may be need to turn off event initialization before iframe loaded
		cfg.stopEventInit=true;
		Runner.controls.RTEField.superclass.constructor.call(this, cfg);
		this.inputType = "RTE";
		this.iframeElemId = this.valContId;
		// clear old vars
		this.valContId = "";
		if ('undefined' == typeof String.prototype.trim) 
		{
			String.prototype.trim = function() 
			{
				return this.replace(/^\s+/, '').replace(/\s+$/, '');
			}
		}
	},
	/**
	 * Indicates used datepicker with control or not
	 * @type {bool} cfg
	 */
	useRTE: false,
	/**
	 * Override addValidation
	 * @param {string} type
	 */
	addValidation: function(type)
	{
		// date field can be validated only as isRequired
		if (type!="isRequired")
			return false;
		// call parent
		Runner.controls.RTEField.superclass.addValidation.call(this, type);
	},
	/**
	 * Disabled for RTE it's inpossible
	 */
	setDisabled: Runner.emptyFn,
	/**
	 * Enabled for RTE it's inpossible
	 */
	setEnabled: Runner.emptyFn,
	/**
	 * Clone html for iframe submit
	 */
	getForSubmit: function()
	{
		var clElem = $('<input type="hidden" name="'+this.iframeElemId+'">').clone();
		$(clElem).val(this.getValue());
		return [clElem];
	},
	setDisabled: function()
	{
		var val = this.getValue();
		this.iframeElem.css('display','none');
		this.spanContElem.prepend('<div id="disabledRTE'+this.fieldName+'_'+this.id+'">'+val+'</div>')
		return true;
	},
	setEnabled: function()
	{
		$("#disabledRTE"+this.fieldName+'_'+this.id).remove();
		this.iframeElem.css('display','block');
		return true;
	}
});


Runner.controls.RTEInnova = Runner.extend(Runner.controls.RTEField, 
{
	constructor: function(cfg)
	{
		Runner.controls.RTEInnova.superclass.constructor.call(this, cfg);
		this.useRTE = cfg.useRTE ? cfg.useRTE : false;
		this.iframeElem = $('#'+this.iframeElemId);	
		if(this.useRTE == "INNOVA")
			this.innerIframeId = 'idContentoEdit'+this.goodFieldName+'_'+this.id;
	},
	
	getValue: function()
	{	
		var val;
		if(this.iframeElem)
		{	
			if(this.useRTE=='RTE')
				val = this.iframeElem.contents().find('#'+this.iframeElemId).contents().find('body').html();
			else
				val = this.iframeElem.contents().find('#'+this.innerIframeId).contents().find('body').html();
			if(val)
				val=val.trim();
			if(val=='<br>')
				val='';
			return val;
		}
		else 
			return false;
	},
		
	setValue: function(val)
	{
		if(this.useRTE=='RTE')
			this.iframeElem.contents().find('#'+this.iframeElemId).contents().find('body').html(val);
		else
			this.iframeElem.contents().find('#'+this.innerIframeId).contents().find('body').html(val);
	}
	
});

Runner.controls.RTEFCK = Runner.extend(Runner.controls.RTEField, {
	
	constructor: function(cfg)
	{	
		Runner.controls.RTEFCK.superclass.constructor.call(this, cfg);
		this.iframeElem = $('#'+this.iframeElemId+'___Frame');	
	},
	
	getValue: function()
	{
		var val;
		if(this.iframeElem)
		{
			var fckval = FCKeditorAPI.GetInstance(this.iframeElemId);
			if(fckval!=undefined)
				val = fckval.GetXHTML();
			else 
				val = $(elem).val();
			return val;
		}
		else 
			return false;	
	},
	
	setValue: function(val)
	{
		this.iframeElem.contents().find('#xEditingArea').find('iframe').contents().find('body').text(val);
	}

});


