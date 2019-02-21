/**
	 * For Register page add event's handler to controls such like confirm password, captcha  ..... 
	 * @param {string} table name
	 * @param {array} fields for which add events 
	 * @param {int} id
 */
function AddEventRegControl(tName, fields, id)
{
	for(var i=0;i<fields.length;i++)
	{
		var Cntrl = Runner.controls.ControlManager.getAt(tName,id,fields[i]);
		if(i>0 && fields[i]=='confirm' && fields[i-1]=='password')
		{
			var Cntrl1 = Runner.controls.ControlManager.getAt(tName,id,fields[i-1]);
			var args = new Array(Cntrl1);
		}
		else
			var args = new Array(fields[i]);
		Cntrl.on('blur', function(argsArr, e)
		{
			if(typeof(argsArr[0])=='object')
			{	
				if(argsArr[0].getValue()!=this.getValue())
					this.markInvalid([window.TEXT_INLINE_MATCH_PASSWORDS]);	
			}
			else{
					params={
						id:id,
						rndval: Math.random(),
						field: argsArr[0],
						val: this.getValue()
					};
					var Cntrl = this;
					$.get('registersuggest.php',params,function(xml)
					{
						if(xml)
							Cntrl.markInvalid([xml]);
					});
				}	
		},{args: args});
	}
}
/**
	 * For Add and Edit page add event's handler to controls and add function for this event 
	 * @param {string} table name
	 * @param {func} function which must will be execute 
 */
function AddEventForControl(tName, func, id)
{
	var arrCntrl = Runner.controls.ControlManager.getAt(tName);
	var args = [id];
	setEventForControl(arrCntrl,func,args);
	for(var i=0;i<arrCntrl.length;i++)
	{
		if(arrCntrl[i].setFocus())
			break;
	}
}
/**
	 * For add event's handler to controls and add function for this event 
	 * @param {array} controls
	 * @param {func} function which must will be execute 
	 * @param {args} arguments for executing function
 */
function setEventForControl(arrCntrl,func,args)
{
	for (var i = 0; i < arrCntrl.length; i++)
	{
		var cntrlType = arrCntrl[i].getControlType(), eventName = 'change', singleFire = false, delay = 0;
		
		if(cntrlType=='checkbox' || cntrlType=='radio')
		{
			eventName = 'click';
			//singleFire = true;
		}
		else if(cntrlType=='text' || cntrlType=='password' || cntrlType=='textarea')
		{
			eventName = 'keyup';
			//singleFire = true;
			delay = 60;
			arrCntrl[i].on('change', func,{single: true, timeout: 0});
		}
		else if(cntrlType=='RTE')
		{
			eventName = 'blur';
			//singleFire = true;
			delay = 5000;
		}
		arrCntrl[i].on(eventName, func,{single: singleFire, timeout: delay, args: args});
	}
}
/**
	 * For clear event's handler to controls
	 * @param {array} controls
*/
function clearEventForControl(arrCntrl)
{
	for (var i = 0; i < arrCntrl.length; i++)
	{
		var cntrlType = arrCntrl[i].getControlType(), eventName = 'change';
		if(cntrlType=='checkbox' || cntrlType=='radio')
			eventName = 'click';
		else if(cntrlType=='text' || cntrlType=='password' || cntrlType=='textarea')
		{	
			eventName = 'keyup';
			arrCntrl[i].clearEvent('change');
		}
		else if(cntrlType=='RTE')
			eventName = 'blur';
		arrCntrl[i].clearEvent(eventName);
	}
}
/**
	 * For Edit page set Prev Next Button disabled 
	 * @param {array} array of arguments for function on
	 * @param {event} event name 
 */
//For set Prev Next Button disabled
function prevNextButtonHandler(argsArr, e)
{
	// for click event on checkbox, do not use stopEvent, that cause that checkbox won't be checked
	//this.stopEvent(e);
	var prev = $('#prev'+argsArr[0])[0];
	var next = $('#next'+argsArr[0])[0];
	if(prev)
	{
		$(prev).css('background','#dcdcdc url(\"images/sortprev.gif\") center no-repeat');
		$(prev).css('color','#dcdcdc');
		$(prev).css('cursor','default');
		$(prev).attr('disabled','disabled');
	}
	if(next)
	{
		$(next).css('background','#dcdcdc url(\"images/sortnext.gif\") center no-repeat');
		$(next).css('color','#dcdcdc');
		$(next).css('cursor','default');
		$(next).attr('disabled','disabled');
	}
	return true;
}