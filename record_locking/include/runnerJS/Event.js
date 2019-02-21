/**
 * @class Runner.Event
 * Abstract base class that provides event functionality. 
 * Example of usage:
Employee = function(name){
    this.name = name;
    this.addEvent(["blur", "change"]);
    this.init();
 }
Runner.extend(Employee, Runner.Event);

=================================================================

Predefined, javascript events:

abort	Loading of an image is interrupted
blur	An element loses focus
change	The user changes the content of a field
click	Mouse clicks an object	1	3
dblclick	Mouse double-clicks an object	1	4
error	An error occurs when loading a document or an image	1	4
focus	An element gets focus	1	3
keydown	A keyboard key is pressed	1	3
keypress	A keyboard key is pressed or held down	1	3
keyup	A keyboard key is released	1	3
load	A page or an image is finished loading	1	3
mousedown	A mouse button is pressed	1	4
mousemove	The mouse is moved	1	3
mouseout	The mouse is moved off an element	1	4
mouseover	The mouse is moved over an element	1	3
mouseup	A mouse button is released	1	4
reset	The reset button is clicked	1	4
resize	A window or frame is resized	1	4
select	Text is selected	1	3
submit	The submit button is clicked	1	3
unload The user exits the page
 */


Runner.Event = Runner.extend(Runner.emptyFn,{
	/**
	 * Array of predefined events
	 * @type {array}
	 */
	events: [],
	/**
	 * Array of predefined listeners
	 * @type {array}
	 */
	listeners: [],
	/**
	 * Array of elements, on which listeners should be added
	 * @type {array}
	 */
	elemsForEvent: [],
	/**
	 * @constructor
	 */
	constructor: function(){
		// recreate objects, to prevent memory mix
		this.events = new Array();
		this.listeners = new Array();
		this.elemsForEvent = new Array();
	},
	/**
	 * Init method, should be called by class contructor, for event initialization
	 * @method
	 */	
	init: function(){
		if (this.events.length == 0){
			return false;
		}
		for(var i=0;i<this.events.length;i++){
			this.on(this.events[i]);
		}
		
		return true;
	},
	/**
	 * Add events to the object. Events names should be similar to predefined
	 * javascript DOM element event names.
	 * @method
	 * @param {string} eventName
	 * @param {link} fn
	 * @param {array} options.args Optional. Array of arguments, that should be passed to event handler
	 * @param {bool} options.single Optional. Pass true to fire event only once
	 * @param {int} options.timeout Optional. Pass number of miliseconds to create delayed handler
	 * @param {int} options.buffer Optional. Pass number of miliseconds to buffer. Usefull for keypress events and validations. Not fully work at now.
	 * @param {link} scope
	 */
	on: function(eventName, fn, options, scope){//single, timeout, scope, args){
		this.addEvent([eventName]);
		
		if (!this.elemsForEvent){
			//console.log("no elems");
			return false;
		}
		if (this.isDefinedEvent(eventName)===false){
			//console.log(this.events, 'events not in list = '+this.fieldName+", eventName = "+eventName);
			return false;
		}
		// prepare event name, for DOM scpecifications
		var onEventName = "";
		if (eventName.indexOf("on", 0) == 0){
			onEventName = eventName;
			eventName = eventName.substring(2);
		}else{
			onEventName = "on"+eventName;			
		}
		
		if (this.isDefinedEvent(eventName) === false){
			//console.log("not def ="+eventName, this.events);
			return false;
		}
		//add event to event array, if needed 
		//this.addEvent([eventName]);
		// predefine scope and func params for creating closure
		var scope = scope ? scope : this, fn = fn ? fn : null, objScope = this, options = options ? options : {};
		// predefine additional params
		var args = options.args ? options.args : [], single = options.single ? options.single : false, timeout = options.timeout ? options.timeout : 0, buffer = options.buffer ? options.buffer : 0;	
		
		// creating delayed handler, usefull for validations etc.
		if (timeout != 0){
			//console.log(timeout, 'timeout');
			var callHandler = function(e){	
				setTimeout(function(e){
					//console.log(timeout, 'timeout2');
	                // call scopes event handler
					if (objScope[eventName]){
						objScope[eventName](e);
					}				
					// call additional function, handler
					if (fn){
						fn.call(scope, args, e);
					}					
	            }, timeout);					
				// clear event handler if function should be called only once
				if (single){
					objScope.clearEvent(eventName);
				}
			}
		// creating handler without delay
		}else{
			var callHandler = function(e){	
				//console.log('call handler');
				// call scopes event handler
				if (objScope[eventName]){
					objScope[eventName](e);
				}				
				// call additional function, handler
				if (fn){
					fn.call(scope, args, e);
				}
				// clear event handler if function should be called only once
				if (single){
					objScope.clearEvent(eventName);
				}
			}
		}
		
		// add to listeners array
		var listener = this.getListener(eventName);
		
		
		if (!listener){
			this.listeners.push({
				name: eventName,
				handler: fn,
				callHandler: callHandler,
				options: options,
				scope: scope,
				index: this.listeners.length
			});
		}else{
			this.listeners[listener.index].handler = fn;
			this.listeners[listener.index].callHandler = callHandler;
			this.listeners[listener.index].options = options;
			this.listeners[listener.index].scope = scope;
		}
				
		// adding listeners for all elems for event		
		for(var i=0;i<this.elemsForEvent.length;i++){				
			var el = this.elemsForEvent[i];
			$(el).bind(eventName, callHandler);
			/*if (el.addEventListener) {
				el.addEventListener(eventName, callHandler, false);
				//console.log(el ,'el')
			} else if (el.attachEvent) {
				el.attachEvent(onEventName, callHandler);
			} else {
				el[onEventName] = callHandler;
			}*/			
		}
		return true;
    },
	/**
	 * Add events to object, make list of predefined events, before call init method
	 * @method
	 * @param {array} eventNameArr
	 */
	addEvent: function(eventNameArr){	
		
		var isAddedEvent = false;		
		
		for(var i=0;i<eventNameArr.length;i++){
			// check if this event already added
			for(var j=0;j<this.events.length;j++){
				if (eventNameArr[i] == this.events[j]){
					isAddedEvent = true;
					break;
				}
			}
			// if not, we add it to events arr
			if (isAddedEvent){
				isAddedEvent = false;	
			}else{
				this.events.push(eventNameArr[i]);		
			}			
		}		
	},
	/**
	 * Kill event handling, sets empty fn as handler
	 * @method
	 * @param {string} eventName
	 * @return {bool} true if success otherwise false
	 */
	killEvent: function(eventName){
		// search event
		for(var i=0;i<this.events.length;i++){
			if (this.events[i]==eventName){
				// search for listener object
				var listener = this.getListener(eventName);				
				if (listener){
					// clear handlers
					for (var j = 0; j < this.elemsForEvent.length; j++) {
						var el = this.elemsForEvent[j];		
						
						//for(var k=0; k<;k++){
							$(el).unbind(eventName, listener.callHandler);
						//}
						/*// do in this way to prevent memory leaks
						if (el.removeEventListener) {
							el.removeEventListener(eventName, this.listeners[j].callHandler, false);
							//console.log(el ,'el123')
						} else if (el.attachEvent) {
							el.detachEvent(onEventName, this.listeners[j].callHandler);
						} else {
							el[onEventName] = null;
						}*/
					}	
					// do in this way to prevent memory leaks	
					this.listeners.splice(j,1);
					// delete event handler from object
					delete this[eventName];
					// in success
					return true;
				}
				//kill event
				this.events.splice(i,1);				
			}// eo if
		}// eo for
		return false;		
	},
	/**
	 * Clear custom event handling, sets only base class handler
	 * @method
	 * @param {string} eventName
	 * @return {bool} true if success otherwise false
	 */
	clearEvent: function(eventName){
		// search event
		for(var i=0;i<this.events.length;i++){
			if (this.events[i]==eventName){
				// search for listener object
				var listener = this.getListener(eventName);				
				if (listener){
					// clear handlers
					for (var j = 0; j < this.elemsForEvent.length; j++) {
						var el = this.elemsForEvent[j];		
						//for(var k=0; k<;k++){
							$(el).unbind(eventName, listener.callHandler);
						//}
						/*// do in this way to prevent memory leaks
						if (el.removeEventListener) {
							el.removeEventListener(eventName, this.listeners[j].callHandler, false);
							//console.log(el ,'el123')
						} else if (el.attachEvent) {
							el.detachEvent(onEventName, this.listeners[j].callHandler);
						} else {
							el[onEventName] = null;
						}*/														
					}	
					// do in this way to prevent memory leaks, clear custom handlers
					//sets only base class handler
					this.on(eventName);
					// in success
					return true;					
				}
			}// eo if
		}// eo for
		return false;	
	},	
	/**
	 * Stop event 
	 * @method
	 * @param {Object} e
	 */
	stopEvent: function (e){		
		if (e && e.cancelable) {
			e.preventDefault();
		}		
		if(e && e.eventPhase){
			e.stopPropagation();
		} 
	},
	/**
	 * Fires the specified event with the passed parameters (minus the event name).
     * @param {String} eventName
	 * @return {bool} True if hadler called, otherwise false.
	 */
	fireEvent : function(eventName){
		var eventIndex = this.isDefinedEvent(eventName);
		if (eventIndex === false){
			return false;
		}
		
		var listener = this.getListener(eventName);
		if (listener === false){
			return false;
		}
		listener.callHandler.apply(this, Array.prototype.slice.call(arguments, 1));
		return true;
    
    },
	/**
	 * Checks if event defined
	 * @param {string} eventName
	 * @return {mixed} false if not found otherwise arrray index
	 */
	isDefinedEvent: function(eventName){		
		
		for (var i = 0; i < this.events.length; i++) {
			/*if (this.fieldName == 'make'){
				console.log(this.events[i]+"--"+eventName, 'this.events[i]');				
			}*/
			//console.log(this.events[i]+"--"+eventName, 'this.events[i]');	
			if (this.events[i] == eventName) {
				//console.log(this.events[i]+"--"+eventName, 'this.events[i]!!!!!!!!!!!');	
				return i;
			}
		}
		return false;
	},
	
	getListener: function(eventName){
		for (var i = 0; i < this.listeners.length; i++) {
			if (this.listeners[i].name == eventName) {					
				return this.listeners[i];
			}
		}
		return false;
	}
});


