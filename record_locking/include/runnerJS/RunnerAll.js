/**
 * Runner main object. 
 * 
 * JS files include order of inheritance, for example:
 * 1. Runner.js (main functionality)
 * 2. Validate.js (validations utilities)
 * 3. ControlManager.js (global object, for controls manage)
 * 4. Control.js (base abstract class for all controls)
 * 5. All controls in any order.
 */
var Runner = {version: '1.0'};
/**
 * Copies all the properties of config to obj.
 * @param {Object} obj The receiver of the properties
 * @param {Object} config The source of the properties
 * @param {Object} defaults object literal that will be applied first
 * @return {Object} returns obj
 * @member Runner apply
 */
Runner.apply = function(obj, cfg, defaults){
	// third argument passed copy it first
    if(defaults){        
        Runner.apply(obj, defaults);
    }
    // copy config and override defaults if they cross
    if(obj && cfg && typeof cfg == 'object'){
        for(var prop in cfg){
            obj[prop] = cfg[prop];
        }
    }
    return obj;
};
/**
 * Reusable empty function
 */
Runner.emptyFn = function(){};
/**
 * Main RunnerJS functionality
 */
(function(){
	
	var idCounter = 0;
	var zIndexMax = window.zindex_max;
    var userAgent = navigator.userAgent.toLowerCase();

    var isOpera = userAgent.indexOf("opera") > -1; 
    var isIE = !isOpera && userAgent.indexOf("msie") > -1; 
    var isIE6 = !isOpera && userAgent.indexOf("msie 6") > -1; 
	var isIE7 = !isOpera && userAgent.indexOf("msie 7") > -1; 
	var isIE8 = !isOpera && userAgent.indexOf("msie 8") > -1; 
	var isChrome = userAgent.indexOf("chrome") > -1; 
	var isSafari = !isChrome && (/webkit|khtml/).test(userAgent); 
	var isSafari3 = isSafari && userAgent.indexOf('webkit/5') != -1; 
	var isGecko = !isSafari && !isChrome && userAgent.indexOf("gecko") > -1; 
    var isGecko3 = isGecko && userAgent.indexOf("rv:1.9") > -1
        
    // copy properties to main object
    Runner.apply(Runner, {
		/**
         * Implements inheritance, on class-based model. 
         * Inherites one class from another and optionally overrides properties with third argument - object literal.
         * Function support three or two arguments call. With two arguments pass superclass as first, and literal with properties to override as second.
         * In three arguments call pass subclass, parent and object literal to copy properties in subclass
         * Example of usage

	    Runner.controls.TextArea = Runner.extend(Runner.controls.Control,{
			constructor: function(cfg){		
				this.addEvent(["change", "keyup"]);		
				// call parent
				Runner.controls.TextArea.superclass.constructor.call(this, cfg);
				// change input type, because textarea don't have type attr
				this.inputType = "textarea";		
			},
			getForSubmit: function(){
				return [this.valueElem.clone().val(this.getValue())]
			}
		});

         * @param {Function} subclass The class which inheriting the functionality
         * @param {Function} superclass The class for extend
         * @param {Object} overrides (optional) A literal object with properties which are copied into the subclass's prototype
         * @return {Function} The subclass constructor.
         * @method extend
         */
		extend: function(){
		    // inline overrides function
		    var overrideFunc = function(obj){
		        for(var prop in obj){
		            this[prop] = obj[prop];
		        }
		    };
		    // constructor of simple Object class
		    var baseObjConstr = Object.prototype.constructor;
			// create closure function
		    return function(subBase, supPar, overrides){
		    	// if function called with 2 paramters, superclass and object literal
		        if(typeof supPar == 'object'){
		        	// change vars, because called with 2 params
		            overrides = supPar;		            
		            supPar = subBase;
		            // if contructor won't overriden, call parent with all passed args
		            subBase = (overrides.constructor != baseObjConstr) ? overrides.constructor : function(){supPar.apply(this, arguments);};
		        }
		        // create temp function and vars with prototypes
		        var F = function(){}, subBaseProt, supParProt = supPar.prototype;
		        // change temp functiion prototype
		        F.prototype = supParProt;
		        // create new incstance of prototype, this will solve problem of one prototype chain
		        subBaseProt = subBase.prototype = new F();
		        // take care of inheritance, reset constructor
		        subBaseProt.constructor=subBase;
		        // make link to parent contructor
		        subBase.superclass=supParProt;
		        // reset parent constructor, don't know for what
		        if(supParProt.constructor == baseObjConstr){
		            supParProt.constructor=supPar;
		        }
		        // add override function
		        subBase.override = function(obj){
		            Runner.override(subBase, obj);
		        };
		        // add override to prototype
		        subBaseProt.override = overrideFunc;
		        // copy properties
		        Runner.override(subBase, overrides);
		        // add extend function
		        subBase.extend = function(obj){Runner.extend(subBase, obj);};
		        // return new class (constructor function)
		        return subBase;
	    	};
        }(),
		/**
         * Copies and replaces properties of one object with another
         * @param {Object} baseclass
         * @param {Object} object literal
         * @method override
         */
		override: function(origClass, overrides){
		    if(overrides){
		        var origProt = origClass.prototype;
		        // copy all properties to prototype
		        for(var method in overrides){
		            origProt[method] = overrides[method];
		        }
		        
		        if(Runner.isIE && overrides.toString != origClass.toString){
		            origProt.toString = overrides.toString;
		        }
		    }
		},
		/**
		 * Loads javascript from file
		 * fileName {string} name of file to be loaded
		 */
		loadJS: function(fileName){
			var js = document.createElement('script');
			js.setAttribute('type', 'text/javascript');
			js.setAttribute('src', "include/"+fileName+".js");
			document.getElementsByTagName('HEAD')[0].appendChild(js);
		},	
		/**
		 * Decodes after encoded str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$jscode);
		 * @param {string}
		 * @return {string}
		 */
		htmlDecode: function(txt){
			txt = txt.replace(/&gt;/ig,"\>");
			txt = txt.replace(/&lt;/ig,"\<");
			txt = txt.replace(/&amp;/ig,"&");	
			return txt;
		},
		/**
		 * Creates namesapce
		 * @method
		 */
		namespace: function(name){
			var params = name.split('.'), current = Runner;
			for(var i=1;i<params.length;i++){
				if (!current[params[i]]){
					current[params[i]] = {};
				}
				current = current[params[i]];
			}
			
			return current;
		},
		/**
		 * Generates unique id
		 */
		genId: function(pref){
			pref = pref || "runner";
            return pref + (++idCounter);
		},
		
		getZindex: function(elObj){
			// old global var. In future better to delete it and use only private zIndexMax;
			++zindex_max;
			zIndexMax = zindex_max;
			if (elObj){
				//if (Runner.isIE6){
					//elObj[0].style.zIndex=zindex_max;
				//}else{
					elObj.css("z-index", zIndexMax);	
				//}				
			}
			return zIndexMax;			
		},
		/**
		 * Replace all except numbers and strings into _
		 */
		goodFieldName: function(fName){	
			return fName.replace(/\W/g, '_');
		},
		/**
         * True if browser is Opera.
         * @type Boolean
         */
        isOpera : isOpera,
        /**
         * True if browser is Mozilla
         * @type Boolean
         */
        isGecko : isGecko,
        /**
         * True if browser is Firefox 2++
         * @type Boolean
         */
        isGecko2 : isGecko && !isGecko3,
        /**
         * True if browser is Firefox 3++
         * @type Boolean
         */
        isGecko3 : isGecko3,        
        /**
         * True if browser is Safari.
         * @type Boolean
         */
        isSafari : isSafari,
        /**
         * True if browser is Safari 3++
         * @type Boolean
         */
        isSafari3 : isSafari3,
        /**
         * True if browser is Safari 2++
         * @type Boolean
         */
        isSafari2 : isSafari && !isSafari3,
        /**
         * True if browser is Internet Explorer.
         * @type Boolean
         */
        isIE : isIE,
        /**
         * True if browser is Internet Explorer 6++
         * @type Boolean
         */
        isIE6 : isIE && !isIE7 && !isIE8,
        /**
         * True if browser is Internet Explorer 7++
         * @type Boolean
         */
        isIE7 : isIE7,
        /**
         * True if browser is Internet Explorer 8++
         * @type Boolean
         */
        isIE8 : isIE8,
        /**
         * True if browser is Chrome.
         * @type Boolean
         */
        isChrome : isChrome
        
	});
})();


/**
 * Controls objects package
 * @type {object}
 */
Runner.namespace('Runner.controls');
/**
 * Search objects package
 * @type {object} 
 */
Runner.namespace('Runner.search');


/**
 * produces a string in which '<', '>', and '&' are replaced with their HTML entity equivalents. 
 * This is essential for placing arbitrary strings into HTML texts. So, "if (a < b && b > c) {".entityify()
 * produces
 * "if (a &lt; b &amp;&amp; b &gt; c) {"
 * @return {string}
 */
String.prototype.entityify = function () {
    return this.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace('"', '&quot;');
};
/**
 * produces a quoted string. 
 * This method returns a string that is like the original string except 
 * that all quote and backslash characters are preceded with backslash.
 * @return {string}
 */
String.prototype.quote = function () {
	return this.replace("\\","\\\\").replace("'","\\'");
};
/**
 * does variable substitution on the string. 
 * It scans through the string looking for expressions enclosed in { } braces. 
 * If an expression is found, use it as a key on the object, and if the key has a string value or number value, 
 * it is substituted for the bracket expression and it repeats. This is useful for automatically fixing URLs. So
 * param = {domain: 'lala.com', media: 'http://zhuzhu.com/'};
 * url = "{media}logo.gif".xTempl(param);
 * produces a url containing "http://zhuzhu.com/logo.gif".
 * @param {object} o
 * @return {string}
 */
String.prototype.xTempl = function (o) {
    return this.replace(/{([^{}]*)}/g,
        function (a, b) {
            var r = o[b];
            return typeof r === 'string' || typeof r === 'number' ? r : a;
        }
    );
};
/**
 * method removes whitespace characters from the beginning and end of the string.
 * @return {string}
 */
String.prototype.trim = function () {
    return this.replace(/^\s+|\s+$/g, "");
}; 

String.prototype.slashdecode = function(){
	var out = '';
	var pos = 0;
	for ( var i = 0; i < this.length - 1; i++ )
	{
		var c = this.charAt(i);
		if( c == '\\' )
		{
			out += this.substr(pos,i-pos);
			pos = i + 2;
			var c1 = this.charAt(i+1);
			i++;
			if ( c1 == '\\' ) {
				out += "\\";
			} else if ( c1 == 'r' ) {
				out += "\r";
			} else if ( c1 == 'n') {
				out += "\n";
			} else {
				i--;
				pos-=2;
			}
		}
	}
	if ( pos < this.length )
		out += this.substr(pos);
	
	return out;
}

/**
 * Checks if value exist in array
 * @param {mixed} value val to search
 * @param {bool} caseSensitive
 * @return {Boolean}
 */
Array.prototype.isInArray = function(value, caseSensitive){	
	for (var i=0; i < this.length; i++) {
		if (caseSensitive) {
			if (this[i] == value) { return true; }
		} else {			
			if (this[i].toString().toLowerCase() == value.toString().toLowerCase()) { return true; }
		}
	}
	return false;
};
/**
 * Return index of element in array. If element doesn't exist in array, returns -1
 * @param {mixed} value value to search
 * @param {} callBack link to function that may used to comparison, 
 * accepts arguemnts:
 * 		value {mixed} value to search
 * 		elem {mixed} current array element in loop
 * 
 * @param {bool} caseSensitive doesn't work with callBack function
 * @return {int} index of element if found, or -1 if element doesn't exist in array
 */
Array.prototype.getIndexOfElem = function(value, callBack, caseSensitive){
	for (var i=0; i < this.length; i++) {
		if (callBack){
			if(callBack(value, this[i])){
				return i;
			}
		}else if (caseSensitive) {
			if (this[i] == value) {
				return i; 
			}
		}else{			
			if (this[i].toString().toLowerCase() == value.toString().toLowerCase()) {
				return i; 
			}
		}
	}
	return -1;
};
Runner.namespace('Runner.util.Event');

Runner.util.Event.getTarget = function(e){
	return e.target || e.srcElement;
} 
// create these classes only for IE6
//if (Runner.isIE6){
	/**
	 * IE utils objects package
	 * @type {object}
	 */
	Runner.namespace('Runner.util.IEHelper');
	/**
	 * iframe class. Used to cover select tags in IE6
	 * for correct work, el wich need to be covered should be child of document body 
	 * and have position absolute and method getPos should work with findPos, not with getAbsolutePosition
	 * 
	 * 
	 * @cfg {int} x
	 * @cfg {int} y
	 * @cfg {int} w
	 * @cfg {int} h
	 * @cfg {int} id options, if not passed, 
	 * @return {obj} iframe obj
	 */
	Runner.util.IEHelper.iframe = function(cfg){
		// init params		
		var cfg = cfg || {}, id;
		// if passed cfg object literal
		if (cfg.constructor === Object){
			cfg.w = cfg.w || 0, cfg.h = cfg.h || 0, cfg.t = cfg.t || 0, cfg.l = cfg.l || 0, id = cfg.id || Runner.genId();
		// deal with DOM element
		}else{
			var el = cfg;
			id = Runner.genId();			
		}
			
		// create iframe jQuery obj
		var iframe = $('#'+id);		
		if (!iframe.length){
			$(document).find('body').append('<iframe id="'+id+'" frameborder="1" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="background:white;position:absolute;display:block;opacity:0;filter:alpha(opacity=0);"></iframe>');
			//$(document).find('body').append('<iframe id="'+id+'" frameborder="1" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="border: 1px solid red;background:white;position:absolute;display:block;"></iframe>');			
			iframe = $('#'+id);
		}	
		// obj with methods
		var iframeObj = {
			/**
			 * Move iframe to coordinates
			 * @param {int} l
			 * @param {int} t
			 * @return {obj} iframe obj
			 */
			move: function(t, l){
				if (t !== undefined && l !== undefined){
					iframe.css('top', t+'px').css('left', l+'px');
				}
				return this;
			},
			/**
			 * Completely removes iframe from DOM
			 */
			destroy: function(){
				iframe.remove();
				return this;
			},
			/**
			 * Move iframe to coordinates
			 * @param {int} w
			 * @param {int} h
			 * @return {obj} iframe obj
			 */
			resize: function(w, h){
				if (w !== undefined && h !== undefined){
					iframe.css('height', h).css('width', w);
				}
				return this;
			},
			/**
			 * Hide iframe, for next use
			 */
			hide: function(){
				iframe.hide();
				return this;
			},
			/**
			 * Show iframe, with old coors
			 */
			show: function(){
				iframe.show();
				return this;
			},
			/**
			 * Move iframe to coordinates
			 * @cfg {int} l
			 * @cfg {int} t
			 * @cfg {int} w
			 * @cfg {int} h
			 * @return {obj} iframe obj
			 */
			reset: function(coors){	
				// use old coors, if not passed new
				coors = coors || this.getPos() || cfg;
				// set postion and size and show iframe
				this.move(coors.t, coors.l).resize(coors.w, coors.h).show();
				// add z-index for iframe
				Runner.getZindex(iframe);
				return this;
			},
			/**
			 * Calculates postion of iframe, when DOM element passed to constructor
			 * @method
			 * @return {obj} literal with coordinates
			 */
			getPos: function(){	
				// lazy init func
				if (!el){
					this.getPos = function(){return false;}
				}else{
					this.getPos = function(){
						var posObj = getAbsolutePosition(el), coors = {};
						coors.w = el.offsetWidth, coors.h = el.offsetHeight, coors.t = posObj.t, coors.l = posObj.l;
						return coors;
					}
				}
				this.getPos();
			}
		}
		// return object
		return iframeObj.reset();
	}	
	/**
	 * Another way to solve IE select element coverage.
	 * 
	 * @param {DOM} el
	 * @return {object}
	 */
	Runner.util.IEHelper.selectsHider = function(el){
		// init private vars
		var selToHide = [], elem = el;
		
		return {
			/**
			 * Method checks intersection of two element by there center coordinates and dimensions
			 */
			checkIntersection: function(selCoors, elCoors){
				if ((Math.abs((elCoors.x-selCoors.x))<=(elCoors.w+selCoors.w)/2) 
					&& (Math.abs((elCoors.y-selCoors.y))<=(elCoors.h+selCoors.h)/2)){
						return true; 
				}
			},
			/**
			 * Calcs center of element
			 * @param {object} coors literal with coordinates
			 */
			getCenter: function(coors){
				coors.x = coors.l+coors.w/2;
				coors.y = coors.t+coors.h/2;
				return coors;
			},
			/**
			 * Hide select that were found in last getSelects call
			 */
			hideSels: function(){
				for(var i=0;i<selToHide.length;i++){
					$(selToHide[i]).hide();
				}
				return this;
			},
			/**
			 * Show hidden selects that were found in last getSelects call
			 */
			showSels: function(){				
				for(var i=0;i<selToHide.length;i++){
					$(selToHide[i]).show();
				}
				return this;
			},
			/**
			 * Check all select for intersection
			 */
			getSelects: function(elPos){
				// init vars
				var elCoors = elPos || {}, selToCheck = $('select');
				// clear old array with selects
				selToHide = [];
				// if element coords not passed
				if (!elPos){
					var pos = findPos(el);
					// add 10px for better coverage 
					elCoors.l = pos[0];
					elCoors.t = pos[1];
					elCoors.w = el.offsetWidth;
					elCoors.h = el.offsetHeight;
				}
				// get center of element
				elCoors = this.getCenter(elCoors);
				// check each select
				var coors = {};
				for(var i=0; i<selToCheck.length;i++){
					// get select position, coordinates and dimension
					pos = findPos(selToCheck[i]);
					coors.l = pos[0];
					coors.t = pos[1];
					coors.w = selToCheck[i].offsetWidth;
					coors.h = selToCheck[i].offsetHeight;
					coors = this.getCenter(coors);
					// check intersection
					if (this.checkIntersection(coors, elCoors)){
						selToHide.push(selToCheck[i]);
					}
				}
				//console.log(selToHide);
				// return array to hide
				return selToHide;
			}			
		}	
	}
//} 
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


 
/**
 * Global validtion object, that used to cheked controls values
 * @type {object}
 */
validation = {	
	/**
	 * Status of validator function. 
	 * @type {object} 
	 */
	validatorConsts:{
		predefined: 1,
		user: 2,
		notFound: 3
	},
	/**
	 * Array of names of user validation functions
	 */
	userValidators: [],
	/**
	 * Array of names of predefined validators 
	 * @type {array}
	 */
	predefinedValidatorsArr: ['isrequired' ,'isnumeric' ,'ispassword' ,'isemail' ,'ismoney', 'iszipcode', 'isphonenumber', 'isstate', 'isssn', 'iscc','istime', 'isdate', 'regexp'],
	/**
	 * Main function that provides object validation
	 * @param {array} validArr
	 * @param {object} control
	 * @return {object}
	 */
	validate: function(validArr, control){
		// total result obj
		var validationRes = false, validatorStatus, result = {result: true, messageArr: []};		
		// loop for all validation on obj
		for(var i=0;i<validArr.length;i++){	
			// to prevent check for undefined values, that mistically appears in IE!
			if (!validArr[i]){
				continue;
			}
			// get status of validator
			validatorStatus = this.getValidatorStatus(validArr[i]); 
			// custom user validation
			if(validatorStatus == this.validatorConsts.user){
				validationRes = window[control.validationArr[i]](control.getValue());
			// validation method in object
			}else if(validatorStatus == this.validatorConsts.predefined){				
				// for IsRequired use isEmpty method
				if(validArr[i] == "IsRequired"){
					// if field not passed IsRequired validation, we need to add text
					if (control.isEmpty()){
						validationRes = TEXT_INLINE_FIELD_REQUIRED;
					}else{
						validationRes = true;
					}
				}else{
					// pass regExp object as second param, for regExp method
					validationRes = this[validArr[i]](control.getValue(), control.regExp);
				}
			}else{
				//alert('validation function not found');
				//console.log('validation function not found');
				validationRes = true;
			}
			// set to final result object
			result = this.setResult(validationRes, result);
		}
		// return result
		return result;
	},
	/**
	 * Check validator function status.
	 * @param {string} validatorName
	 * @return {string} property from validatorConsts object
	 */
	getValidatorStatus: function(validatorName){
		if(this.predefinedValidatorsArr.isInArray(validatorName)){
		//if(IsInArray(this.predefinedValidatorsArr, validatorName, false)){
			return this.validatorConsts.predefined;
		}else if(window[validatorName] && ((typeof(window[validatorName])=='function')||(Runner.isIE&&typeof(window[validatorName])=='object'))){
			return this.validatorConsts.user;
		}else{
			return this.validatorConsts.notFound;
		}
	},
	/**
	 * Set result to final result object
	 * @param {mixed} res result from any validation function true, or error text
	 * @param {object} obj final result object
	 * @return {object}
	 */
	setResult: function(res, obj){
		var len = obj.messageArr.length;		
		if(res!==true){
			// add message and set false to final result
			obj.result = false;
			// if res is array of messages, add each message to array
			if (typeof(res)=='object'){
				for(var i=0;i<res.length;i++){
					obj.messageArr.push(res[i]);
				}
			// add to message array if res is string
			}else{
				obj.messageArr.push(res);
			}				
		}
		return obj;
	},
	/**
	 * Handler loading custom validation function from file.
	 * @param {object} ctrl
	 */
	registerCustomValidation: function(ctrl){
		var validatorStatus;
		// loop for all validations
		for(var i=0;i<ctrl.validationArr.length;i++){		
			// to prevent check undefined vals
			if (!ctrl.validationArr[i]){
				continue;
			}
			// get validator status
			validatorStatus = this.getValidatorStatus(ctrl.validationArr[i]);			
			// if user vvalidator, and defined as function			
			if(validatorStatus == this.validatorConsts.user || validatorStatus == this.validatorConsts.notFound){			
				// check if was added
				var isAdded = false;
				for(var j=0;j<this.userValidators.length;j++){
					if(this.userValidators[j]==ctrl.validationArr[i]){
						isAdded=true;
						break;
					}
				}
				// add if not
				if(!isAdded){					
					// load js from file
					Runner.loadJS('validate/'+ctrl.validationArr[i]);
					// add to validation arr
					this.userValidators.push(ctrl.validationArr[i]);
				}
			}
		}
		
	},
	
	"IsRequired": function(sVal)
	{
		var regexp = /.+/;
		if(typeof(sVal)!='string')
			sVal = sVal.toString();
		if(!sVal.match(regexp) && !this.setRequired) 
		{
			this.setRequired = true;
			return TEXT_INLINE_FIELD_REQUIRED;
		}
		else
			return true;
			
	},
	
	"IsNumeric": function(sVal)
	{
		sVal = sVal.replace(/,/g,"");
		if(isNaN(sVal)) 
			return TEXT_INLINE_FIELD_NUMBER;
		else
			return true;
	},
//	
	"IsPassword": function(sVal)
	{
		var regexp1 = /^password$/;
		var regexp2 = /.{4,}/;
		if(sVal.match(regexp1))
			return TEXT_INLINE_FIELD_PASSWORD1;
		else if(!sVal.match(regexp2)) 
			return TEXT_INLINE_FIELD_PASSWORD2;		
		else
			return	true;	
	},

	"IsEmail": function(sVal)
	{
		var regexp = /^[A-z0-9_-]+([.][A-z0-9_-]+)*[@][A-z0-9_-]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/;
		if(sVal.match(/.+/) && !sVal.match(regexp) ) 
			return TEXT_INLINE_FIELD_EMAIL;
		else
			return true;
	}, 
//	
	"IsMoney": function(sVal)
	{
		var regexp = /^(\d*)\.?(\d*)$/;
		if(sVal.match(/.+/) && !sVal.match(regexp) ) 
			return TEXT_INLINE_FIELD_CURRENCY;
		else
			return true;	
	},  
//	
	"IsZipCode": function(sVal)
	{
		var regexp = /^\d{5}([\-]\d{4})?$/;
		if(sVal.match(/.+/) && !sVal.match(regexp) ) 
			return TEXT_INLINE_FIELD_ZIPCODE;
		else
			return true;	
	},
//	
	"IsPhoneNumber": function(sVal)
	{
		var regexp = /^\(\d{3}\)\s?\d{3}\-\d{4}$/;		
		var stripped = sVal.replace(/[\(\)\.\-\ ]/g, '');    
		if(sVal.match(/.+/) && (isNaN(parseInt(stripped)) || stripped.length != 10) ) 
			return TEXT_INLINE_FIELD_PHONE;
		else
			return true;
	},
//	
	"IsState": function(sVal)
	{
		if(sVal.match(/.+/) && !arrStates.inArray(sVal,false) ) 
			return TEXT_INLINE_FIELD_STATE;
		else
			return true;
	}, 
//	
	"IsSSN": function(sVal)
	{
		// 123-45-6789 or 123 45 6789
		var regexp = /^\d{3}(-|\s)\d{2}(-|\s)\d{4}$/;
		if(sVal.match(/.+/) && !sVal.match(regexp) ) 
			return TEXT_INLINE_FIELD_SSN;
		else
			return true;
	},
//	
	"IsCC": function(sVal)
	{
		//Visa, Master Card, American Express
		var regexp = /^((4\d{3})|(5[1-5]\d{2}))(-?|\040?)(\d{4}(-?|\040?)){3}|^(3[4,7]\d{2})(-?|\040?)\d{6}(-?|\040?)\d{5}/;
		if(sVal.match(/.+/) && !sVal.match(regexp) ) 
			return TEXT_INLINE_FIELD_CC;
		else
			return true;
	},
//	
	"IsTime": function(sVal)
	{
		var regexp = /\d+/g;
		if(!sVal)
			return true;
		var arr = sVal.match(regexp);
		var bFlag = true;
		if(arr==null || arr.length > 3)  
			bFlag = false; 
		while(bFlag && arr.length < 3) 
			arr[arr.length] = 0; 
		if( bFlag && (arr[0]<0 || arr[0]>23 || arr[1]<0 || arr[1]>59 || arr[2]<0 || arr[2]>59) ) 
			bFlag = false; 
		if(!bFlag) 
			return TEXT_INLINE_FIELD_TIME;
		else
			return true;
	},
//
	"IsDate": function(sVal)
	{
		var fmt = "";
		switch (locale_dateformat) 
		{
			case 0 :
					fmt="MDY";
			break;
			case 1 : 
					fmt="DMY";
			break;	
			default:
					fmt="YMD";
			break;				
		};
		if(!this.isValidDate(sVal,fmt)) 
			return TEXT_INLINE_FIELD_DATE;	
		else
			return true;
	},
	
	"RegExp": function(sVal, regExpParamsObj){
		// create regExp obj		
		var re = new RegExp(regExpParamsObj.regex);
		// test against regExp
		if(!re.test(sVal) || re.exec(sVal)[0] != sVal){
			// return error text
			if(regExpParamsObj.messagetype == 'Text'){
				return regExpParamsObj.message;
			}else{
				return GetCustomLabel(regExpParamsObj.message);
			}
		}else{
			return true;
		}			
	},	
//		
	isValidDate: function(dateStr, format){
		if (format == null) 
			format = "MDY"; 
		format = format.toUpperCase();
		if (format.length != 3)  
			format = "MDY"; 
		if ((format.indexOf("M") == -1) || (format.indexOf("D") == -1) || (format.indexOf("Y") == -1) ) 
			format = "MDY"; 
		if (format.substring(0, 1) == "Y") 
		{ // If the year is first
			var reg1 = /^\d{2}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;
			var reg2 = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;
		} 
		else if (format.substring(1, 2) == "Y") 
		{ // If the year is second
			var reg1 = /^\d{1,2}(\-|\/|\.)\d{2}\1\d{1,2}$/;
			var reg2 = /^\d{1,2}(\-|\/|\.)\d{4}\1\d{1,2}$/;
		} 
		else{ // The year must be third
				var reg1 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{2}$/;
				var reg2 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/;
			}
		// If it doesn't conform to the right format (with either a 2 digit year or 4 digit year), fail
		if ((reg1.test(dateStr) == false) && (reg2.test(dateStr) == false)) 
			return false; 
		var parts = dateStr.split(RegExp.$1); // Split into 3 parts based on what the divider was
		// Check to see if the 3 parts end up making a valid date
		if (format.substring(0, 1) == "M") 
			var mm = parts[0];  
		else if (format.substring(1, 2) == "M") 
			var mm = parts[1];  
		else	
			var mm = parts[2]; 
		if (format.substring(0, 1) == "D") 
			var dd = parts[0];  
		else if (format.substring(1, 2) == "D") 
			var dd = parts[1]; 
		else	
			var dd = parts[2]; 
		if (format.substring(0, 1) == "Y") 
			var yy = parts[0];  
		else if (format.substring(1, 2) == "Y") 
			var yy = parts[1];  
		else 
			var yy = parts[2]; 
		if (parseFloat(yy) <= 50) 
			yy = (parseFloat(yy) + 2000).toString();
		if (parseFloat(yy) <= 99) 
			yy = (parseFloat(yy) + 1900).toString(); 
		var dt = new Date(parseFloat(yy), parseFloat(mm)-1, parseFloat(dd), 0, 0, 0, 0);
		if (parseFloat(dd) != dt.getDate()) 
			return false; 
		if (parseFloat(mm)-1 != dt.getMonth()) 
			return false; 
	   return true;
	}
} 
/**
 * Row control manager. Alows to add, delete and manage controls
 * Collection of control for the specific row
 */
Runner.controls.RowManager = Runner.extend(Runner.emptyFn, {
	/**
	 * Fields control collection 
	 * @param {object} fields
	 */
	fields: {},
	/**
	 * Id of row
	 * @type {int}
	 */
	rowId: -1,	
	/**
	 * Count of registred fields
	 * @param {int} fieldsCount
	 */
	fieldsCount: 0,
	/**
	 * Array of names of registered fields controls
	 * @type {array} control
	 */
	fieldNames: [],
	/**
	 * @constructor
	 * @param {int} rowId
	 */
	constructor: function(rowId){
		Runner.controls.RowManager.superclass.constructor.call(this, rowId);	
		this.fields = {};
		this.fieldNames = [];
		this.rowId = rowId;
	},
	
	/**
	 * Control to register
	 * @param {link} control
	 */
	register: function(control){	
		var controlName = control.fieldName;
		// if need to create new field
		if (!this.fields[controlName]) {			
			this.fields[controlName] = [];			
			this.fieldNames.push(controlName);
			this.fieldsCount++;			
		}
		// add control
		this.fields[controlName][control.ctrlInd] = control;
		/*if (control.secondCntrl){
			this.fields[controlName][1] = control;
		}else{
			this.fields[controlName][0] = control;
		}*/
		return true;		
	},
	/**
	 * Return control by following param
	 * @param {string} fName Pass false to get all controls of the row
	 * @param {int} controlIndex Pass false or null to get first control of the field
	 */
	getAt: function(fName, controlIndex){		
		// need to get all controls
		if (!fName){
			// array of row controls
			var rowControlsArr = [];
			// collect all controls from rowManager
			for(var i=0;i<this.fieldNames.length;i++){	
				// get all controls from field. Field may contain more then one
				for(var j=0;j< this.fields[this.fieldNames[i]].length;j++){
					// field control
					var fControl = this.getAt(this.fieldNames[i], j);
					// add to array
					rowControlsArr.push(fControl);
				}					
			}
			return rowControlsArr;
		}
		// if we need specific control
		if (!this.fields[fName]) {
			return false;
		}		
		return this.fields[fName][controlIndex];
	},
	/**
	 * Control which need to unregister
	 * @param {string} fName
	 */
	unregister: function(fName, controlIndex){
		// unreg all rows
		if (fName == null){
			for(var i=0;i<this.fieldsCount;i++){
				this.unregister(this.fieldNames[i], null);
				/*delete this.fields[this.fieldNames[i]];
				this.fieldNames.splice(i,1);
				this.fieldsCount--;*/
			}
			return true;
		// no such row
		}else if(!this.fields[fName]){
			return false;
		// unreg whole field
		}else if(controlIndex==null){
			for (var i=0;i<this.fields[fName].length; i++){
				this.unregister(fName, i);
			};			
			// delete fieldName from names arr
			for(var i=0;i<this.fieldsCount;i++){
				if (this.fieldNames[i]==fName){
					this.fieldNames.splice(i,1);						
					this.fieldsCount--;
				}
			}			
			delete this.fields[fName];
			return true;
		// unreg by params
		}else{
			// call object destructor
			if (this.fields[fName][controlIndex].destructor){
				this.fields[fName][controlIndex].destructor();
			}else if(this.fields[fName][controlIndex]["destructor"]){
				this.fields[fName][controlIndex]["destructor"]();
			}
			// remove from arr
			//this.fields[fName].splice(controlIndex, 1);
			delete this.fields[fName][controlIndex];
			return true;
		}
	},
	
	getMaxFieldIndex: function(fName){
		// if no field with such name
		if(!this.fields[fName]){
			return false;
		}
		
		return this.fields[fName].length;
	}
});
/** 
 * Table controls manager. Alows to add, delete and manage controls
 * Collection of control for the specific table.
 */
Runner.controls.TableManager = Runner.extend(Runner.emptyFn, {
	/**
	 * Row managers collection
	 * @param {object} rows
	 */
	rows: {},
	/**
	 * Name of table
	 * @type {String}
	 */
	tName: "",
	/**
	 * Count of registred rows
	 * @param {int} rowsCount
	 */
	rowsCount: 0,
	/**
	 * Ids of registered rows
	 * @type {array} control
	 */
	rowIds: [],
	/**
	 * Contructor
	 * @param {string} tName
	 */
	constructor: function(tName){
		this.tName = tName;
		this.rows = {};
		this.rowIds = [];
	},
	
	/**
	 * Control to register
	 * @param {#link} control
	 */
	register: function(control){		
		var controlId = control.id;
		// if need to create new row
		if (!this.rows[controlId]){
			this.rows[controlId] = new Runner.controls.RowManager(controlId);
			this.rowIds.push(controlId);
			this.rowsCount++;
		}
		// return register result
		return this.rows[controlId].register(control);
	},
	/**
	 * Return control by following params
	 * @param {string} rowId Pass false or null to get all controls of the table
	 * @param {string} fName Pass false or null to get all controls of the row
	 * @param {int} controlIndex Pass false or null to get first control of the field
	 */
	getAt: function(rowId, fName, controlIndex){		
		// if no rowId, then get all controls from table
		if (rowId==null){
			// array of controls for return
			var tableControlsArr = [];
			// collect all controls from rows managers
			for(var i=0;i<this.rowIds.length;i++){
				//get all controls of the row
				var rowControls = this.rows[this.rowIds[i]].getAt();
				// collect controls from row controls arr 
				for(var j=0;j<rowControls.length;j++){
					tableControlsArr.push(rowControls[j]);
				}	
			}
			return tableControlsArr;
		}
		// if row id defined, but no rows with such id
		if (!this.rows[rowId]) {
			return false;
		}
		// return result
		return this.rows[rowId].getAt(fName, controlIndex);	
	},
	/**
	 * Control which need to unregister
	 * @param {string} rowId
	 * @param {string} fName Pass false or null to clear all controls of the row
	 * @param {int} controlIndex Pass false or null to clear all control of the field
	 * @return {bool} true if success, otherwise false
	 */
	unregister: function(rowId, fName, controlIndex){		
		// unreg all rows
		if (rowId == null){
			for(var i=0;i<this.rowsCount;i++){
				this.rows[this.rowIds[i]].unregister(null, null);
			}
			return true;
		// no such row
		}else if(!this.rows[rowId]){
			return false;
		// unreg by params
		}else{
			var rowUnregStat = this.rows[rowId].unregister(fName, controlIndex);
			if (rowUnregStat && fName==null){
				// delete row id from ids arr
				for(var i=0;i<this.rowsCount;i++){
					if (this.rowIds[i]==rowId){
						this.rowIds.splice(i,1);						
						this.rowsCount--;
					}
				}
				// delete table object
				delete this.rows[rowId];
				return true;
			}else{
				return rowUnregStat;
			}
		}

	},
	
	getMaxFieldIndex: function(rowId, fName){
		// if no row with such id
		if(!this.rows[rowId]){
			return false;
		}
		
		return this.rows[rowId].getMaxFieldIndex(fName);
	}
});
/** 
 * Global control manager. Alows to add, delete and manage controls
 * Collection of controls for the specific table.
 * Should not be created directly, only one instance per page. 
 * Use its instance to get access to any control
 */
Runner.controls.ControlManager = function(){
	/**
	 * Table managers collection
	 * @type {object} private
	 */
	var tables = {};	
	/**
	 * Count of registred tables
	 * @type {int} private
	 */
	var tablesCount = 0;
	/**
	 * Names of registred tables
	 * @type {array} private
	 */
	var tableNames = [];
	
	//console.log(tables, 'tables');
	
	return {
		/**
		 * Control to register
		 * @param {#link} control
		 */
		register: function(control){
			// return false if not control
			if (!control){
				return false;
			}
			// get table name
			var controlTable = control.table;		
			// if table not exists, create new one
			if (!tables[controlTable]){
				tables[controlTable] = new Runner.controls.TableManager(controlTable);	
				tableNames.push(controlTable);
				tablesCount++;		
			}
			//console.log(tables, 'tables before reg');
			// return register result
			return tables[controlTable].register(control);	
			
		},
		/**
		 * Returns control or array of controls by following params
		 * @param {string} tName
		 * @param {string} rowId Pass false or null to get all controls of the table
		 * @param {string} fName Pass false or null to get all controls of the row
		 * @param {int} controlIndex Pass false or null to get first control of the field
		 * @return {object} return control, array of controls or false
		 */
		getAt: function(tName, rowId, fName, controlIndex){
			//console.log(tables, 'tables');
			// if table not exists
			if (!tables[tName]) {
				return false;
			}	
			// if no index passed we return control with 0 index
			controlIndex = controlIndex ? controlIndex : 0;
			// else return by params
			return tables[tName].getAt(rowId, fName, controlIndex);
		},
		/**
		 * Unregister control, row or table
		 * @param {string} tName
		 * @param {string} rowId Pass false or null to clear all controls of the table
		 * @param {string} fName Pass false or null to clear all controls of the row
		 * @param {int} controlIndex Pass false or null to clear first control of the field
		 * @return {bool} true if success, otherwise false
		 */
		unregister: function(tName, rowId, fName, controlIndex){	
			// if no table name passed, return false
			if (!tables[tName]) {
				return false;
			}			
			//controlIndex = controlIndex ? controlIndex : 0;			
			// recursively call unregister through table rows
			var tUnregStat = tables[tName].unregister(rowId, fName, controlIndex);
			// if delete whole table and recursive unreg call success
			if (tUnregStat && rowId==null){
				// delete table name from name arr
				for(var i=0;i<tablesCount;i++){
					if (tableNames[i]==tName){
						tableNames.splice(i,1);						
						tablesCount--;
					}
				}
				// delete table object
				delete tables[tName];
				return true;
			}else{
				return tUnregStat;
			}
		},
		
		getMaxFieldIndex: function(tName, rowId, fName){
			// if no table with such name
			if (!tables[tName]) {
				return false;
			}
			
			return tables[tName].getMaxFieldIndex(rowId, fName);
		},
		
		/**
		 * Resets all controls for specified table
		 * @method
		 * @param {string} tName
		 * @return {bool} true if success, otherwise false
		 */
		resetControlsForTable: function(tName){
			var cntrls = this.getAt(tName);
			if (!cntrls){
				return false;
			}
			for(var i=0;i<cntrls.length;i++){
				cntrls[i].reset();
			}
			return true;
		},
		
		/**
		 * Resets all controls for specified table
		 * @method
		 * @param {string} tName
		 * @return {bool} true if success, otherwise false
		 */
		clearControlsForTable: function(tName){
			var cntrls = this.getAt(tName);
			if (!cntrls){
				return false;
			}
			for(var i=0;i<cntrls.length;i++){
				cntrls[i].clear();
			}
			return true;
		}
	}	
}();
 
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
/**
 * Search form with user interface. 
 * 
 */
Runner.search.SearchFormWithUI = Runner.extend(Runner.search.SearchForm, {	  
    /**
    * Options panel show status indicator
    * @type Boolean
    */
    srchOptShowStatus: false,
    /**
    * Search win show status indicator
    * @type Boolean
    */
    srchWinShowStatus: false,
    /**
    * Show status indicator of div, which contains add filter buttons
    * @type Boolean
    */
    ctrlChooseMenuStatus: false,
    /**
    * Show status indicator of search type combos
    * @type Boolean
    */
    ctrlTypeComboStatus: false,
    /**
    * jQuery obj of search options div
    * @type {obj}
    */
    srchOptDiv: null,
    /**
    * jQuery object of img-button options panel expander
    * @type {obj}
    */
    srchOptExpander: null,
    /**
    * jQuery object of img-button search win expander
    * @type {obj}
    */
    srchWinExpander: null,
    /**
     * jQuery object with div, that contains all search elements
     * @type {obj}
     */
    srchBlock: null,
    /**
     * jQuery object with div, that contains all search controls
     * @type {obj}
     */
    srchCtrlsBlock: null,
    /**
    * Show status indicator of search block
    * @type Boolean
    */
    srchBlockStatus: true,
    
    /**
     * jQuery obj of top div with radio conditions
     * @type {obj}
     */
    topCritCont: null,
    
    /**
    * jQuery object of div with add-filter buttons
    * @type {obj}
    */
    ctrlChooseMenuDiv: null,
    /**
    * jQuery object of div with basic search controls
    * @type {obj}
    */
    srchPanelHeader: null,
    /**
     * jQuery object of div where panel should be placed. Used to toggle window and panel mode
     * @type {obj}
     */
    panelContainer: null,
    /**
     * jQuery object of div with b tags at bottom of the panel. Used on some layouts
     * for example on Madrid
     * @type {obj} 
     */
    bottomPanelRound: null,
    /**
     * jQuery obj of bottom search button
     * @type {obj} 
     */
    bottomSearchButt: null,
    /**
    * Img src attr for minus button
    * @type String
    */
    minusSrc: "images/search/minus.gif",
    /**
    * Img src attr for plus button
    * @type String
    */
    plusSrc: "images/search/plus.gif",
    /**
    * Img src attr for hide opt
    * @type String
    */
    hideOptSrc: "images/search/hideOptions.gif",
    /**
    * Img src attr for show opt
    * @type String
    */
    showOptSrc: "images/search/showOptions.gif",
    /**
	 * Search panel icon switcher text
	 * @type 
	 */
    showOptText: "Show search options",
    /**
	 * Search panel icon switcher text
	 * @type 
	 */
    hideOptText: "Hide search options",
    /**
	 * Search type combos switcher text
	 * @type 
	 */
	showComboText: 'Show options',
	/**
	 * Search type combos switcher text
	 * @type 
	 */
	hideComboText: 'Hide options',
    /**
    * Array of search type combos
    * @type {array}
    */
    searchTypeCombosArr: null,
    /**
    * Array of search type combos
    * appear in window mode
    * @type {array}
    */
    searchTypeCombosWinArr: null,
    /**
     * Array of divs, that used as containers for one search control with its combos, delete buttons etc.
     * @type {array}
     */
    srchFilterRowArr: null,
    /**
     * Array of trs, that used as containers for one search control with its combos, delete buttons etc.
     * Trs appear in window mode
     * @type {array}
     */
    srchFilterRowWinArr: null,
    /**
     * Array of field names
     * @type array
     */
    fNamesArr: null,
    /**
    * ctrls map. Used for indicate which index conected with which search ctrl
    * @type obj
    */    
    ctrlsShowMap: null,
    /**
     * jQuery obj of link-switcher. Toggles search type combos
     * @type obj
     */
    showHideSearchComboButton: null,
    /**
     * Iframe object used for control choose menu coverage in IE6
     * @type {object}
     */
    iframe: null,
    /**
     * Hider object, hide selects in fly div mode
     * @type 
     */
    hider: null,
    /**
     * Fly div id, do not use controller id, to prevent fly div collisions
     * @type Number
     */
    flyDivId: 0,
    /**
     * True if records_block div margin-left was change, to prevent grid coverage
     * @type Boolean
     */
    recBlockMargChange: false,
	/**
    * Constructor
    * @param {obj} cfg
    */
    constructor: function(cfg) {
    	// recreate objects
        this.searchTypeCombosArr = [];
        this.searchTypeCombosWinArr = [];
        this.fNamesArr = [];
        this.srchFilterRowArr = [];
        this.srchFilterRowWinArr = [];
        //call parent
        Runner.search.SearchFormWithUI.superclass.constructor.call(this, cfg);
        // -------------------stuf used only when in panel mode------------------
        // private jQuery obj
        this.srchOptDiv = $("#searchOptions" + this.id);
        this.srchOptExpander = $("#showOptPanel" + this.id);
        this.srchWinExpander = $("#showSrchWin" + this.id);        
        this.ctrlChooseMenuDiv = $("#controlChooseMenu" + this.id);

        // div container with all search stuff
        var srchBlockId = 'search_block'+this.id;
        this.srchBlock = $("#"+srchBlockId);
        // div object with all controls        
        var srchCtrlsBlockId = 'controlsBlock_'+this.id;
        this.srchCtrlsBlock = $("#"+srchCtrlsBlockId);
        // table object with all controls fpr window       
        var srchCtrlsBlockWinId = 'controlsBlock_'+this.id+'_win';
        this.srchCtrlsBlockWin = $("#"+srchCtrlsBlockWinId);
        // add object with basic search controls
        var srchPanelHeaderId = 'searchPanelHeader'+this.id;
        this.srchPanelHeader = $("#"+srchPanelHeaderId);
        
        var showHideSearchComboButtonId = 'showHideSearchType'+this.id;
        this.showHideSearchComboButton = $('#'+showHideSearchComboButtonId); 
        // container where panel placed
        var panelContainerId = 'searchPanelContainer'+this.id;
        this.panelContainer = $('#'+panelContainerId);
        // for some layouts bottom panel round should handled by this class
        var bottomPanelRoundId = 'searchPanelBottomRound'+this.id;
        this.bottomPanelRound = $('#'+bottomPanelRoundId);
        
        this.ctrlChooseMenuDiv.appendTo(document.body);
        
        this.addDelegatedEvets();
        // need to prevent bad rendering of display: none combos
        if (Runner.isIE){        	
        	if ($("#flycontents"+this.id).length){
        		this.srchOptDiv.appendTo($("#flycontents"+this.id));          		
        	}else{
	        	var prevSrchPanelStatus = this.srchOptShowStatus;
	        	this.showSearchWin(undefined, this.id);
	        	this.hideSearchWin(undefined, this.id);
	        	// to prevent opening srch panel, if it was hidden
	        	if (!prevSrchPanelStatus){
	        		this.hideSearchOptions();	
	        	}  
        	}
        }       
    },
    
    /**
     * Binds hover events for table and div. 
     * Use parent containers as delegates
     * Call it in constructor
     */
    addDelegatedEvets: function(){
    	// for event handlers closures
    	var controller = this;
    	// filter div row mouseover event
    	this.srchCtrlsBlock.bind('mouseover', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target && target.id != controller.srchCtrlsBlock.attr('id')) {
				if(target.id && target.id.indexOf('filter_') != -1) {
					// show del image
					$(target).find('img[@class=searchPanelButton]').css('visibility', 'visible');	
					$(target).removeClass('blockBorder').addClass('blockBorderHovered');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	// filter div row mouseout event
    	this.srchCtrlsBlock.bind('mouseout', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target && target.id != controller.srchCtrlsBlock.attr('id')) {
				if(target.id && target.id.indexOf('filter_') != -1) {
					// hide del image
					$(target).find('img[@class=searchPanelButton]').css('visibility', 'hidden');	
					$(target).removeClass('blockBorderHovered').addClass('blockBorder');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	
    	// border hover events
    	this.srchCtrlsBlockWin.bind('mouseover', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target.id != controller.srchCtrlsBlockWin.attr('id')) {				
				if(target.nodeName == "TD") {
					// get row with all tds
					var tr = $(target).parent();
					// show del image
					tr.find('img[@class=searchPanelButton]').css('visibility', 'visible');	
					// all cells
					var tds = tr.children();
					// if second ctrldoesn't exist or is hidden, make right border for last-1 child
	    			var lastVisible = tds.length-1;    		
	    			//console.log($(tds[tds.length-1]).children(), 'child');
	    			if ($(tds[tds.length-1]).children().length === 0 || $(tds[tds.length-1]).find('*:first-child').css('display') == 'none'){
	    				lastVisible--;
	    			}    			
	    			// set style for left element
	    			$(tds[0]).removeClass('cellBorderCenter').removeClass('cellBorderLeft').addClass('cellBorderCenterHovered').addClass('cellBorderLeftHovered');
	    			// set styles for center elements
	    			for(var i=0;i<lastVisible;i++){
	    				// try to remove also right style, because it may come when second ctrl was invisible
	    				$(tds[i]).removeClass('cellBorderCenter').removeClass('cellBorderRightHovered').addClass('cellBorderCenterHovered');
	    			}    
	    			//set style for last elem
	    			$(tds[lastVisible]).removeClass('cellBorderCenter').removeClass('cellBorderRight').addClass('cellBorderCenterHovered').addClass('cellBorderRightHovered');
					break;
				} else {
					target = target.parentNode;
				}
			}
    	});
    	
    	this.srchCtrlsBlockWin.bind('mouseout', function(e){
    		// get event element
   			var target = Runner.util.Event.getTarget(e);
			// traverse to filter parent   			   				
			while (target.id != controller.srchCtrlsBlockWin.attr('id')) {				
				if(target.nodeName == "TD") {
					// get row with all tds
					var tr = $(target).parent();
					// show del image
					tr.find('img[@class=searchPanelButton]').css('visibility', 'hidden');	
					// all cells
					var tds = tr.children();
					// if second ctrldoesn't exist or is hidden, make right border for last-1 child
	    			var lastVisible = tds.length-1;
	    			if ($(tds[tds.length-1]).children().length === 0 || $(tds[tds.length-1]).find('*:first-child').css('display') == 'none'){
	    				lastVisible--;
	    			}
	    			// set style for left element
	    			$(tds[0]).removeClass('cellBorderCenterHovered').removeClass('cellBorderLeftHovered').addClass('cellBorderCenter').addClass('cellBorderLeft');
	    			// set styles for center elements
	    			for(var i=0;i<lastVisible;i++){
	    				$(tds[i]).removeClass('cellBorderCenterHovered').addClass('cellBorderCenter');
	    			}
	    			//set style for last elem
	    			$(tds[lastVisible]).removeClass('cellBorderCenterHovered').removeClass('cellBorderRightHovered').addClass('cellBorderCenter').addClass('cellBorderRight');
					break;
				} else {
					target = target.parentNode;
				}
			}	
    	});  
    },
    /**
     * Return search type combo container id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getComboContId: function(fName, ind, isWin){    	
    	return "searchType_" + ind + "_" + Runner.goodFieldName(fName) + (isWin ? '_win' : '');
    },    
    /**
     * Return filter div id
     * @param {string} fName
     * @param {int} ind
     * @return {string}
     */
    getFilterDivId: function(fName, ind, isWin){    	
    	return "filter_" + ind + "_" + Runner.goodFieldName(fName) + (isWin ? '_win' : '');
    },
    /**
     * Recalc window dimension after change content
     * @param {object} winObj
     * @return {Boolean} true if success
     */
	recalcWindowDim: function(winObj){
		// if no window, return false
		if (!this.srchWinShowStatus){
			return false;
		}
		// if window object not passed, get it
		winObj = (winObj ? winObj : $("#fly"+this.id));		
		// recalc
    	var x = winObj.css('top'), y = winObj.css('left');
    	var flyDivDimAndCoorsObj = getFlyDivSizeAndCoors(this.id, x, y);
   		setFlyDivDimAndCoors(flyDivDimAndCoorsObj, this.id);
        
   		return true;
	},
    /**
    * Create flyDiv with search controls
    * If used as onlick handler pass event object, for get click coords
    * @param {event} e
    */
    showSearchWin: function(e, id) { 
    	
    	// lazy-init vars, redeclare fly div title
        var headerObj = {
        	title: '<span style="color: black;">Search for:&nbsp;</span>',
        	buttons: [{src: 'images/search/windowPin.gif', handler: 'searchController'+this.id+'.hideSearchWin(); ', alt: 'Hide window', title: 'Hide window'}],
        	closeButton: false
        };
        
        var cfgObj = {
        	headerObj: headerObj,
        	border:{
        		color: $('#controlChooseMenu'+this.id).css('background-color')
        	}
        };
        // redeclare function, after lazy-init
        this.showSearchWin = function(e, id){
        	this.hideCtrlChooseMenu();
        	// get click coors
	        var x = 50, y = 50;
	        if (Runner.isIE && e) {
	            y = e.y;
	            x = e.x;
	        } else if (e) {
	            y = e.clientY;
	            x = e.clientX;
	        }
	        // handler text, will fire before window closed
	        var oncloseHandlerCode = '';
	        
	        this.flyDivId = id || ++window.flyid;
	        // create div
	        var divContainer = DisplayFlyDiv("", "", this.flyDivId, "", x, y, 'search', "", this.flyDivId, oncloseHandlerCode, cfgObj);	        
	        $(divContainer).css('padding-top', '10px');
	        // set div color, because in panel mode in IE6 it suddenly covers controls
	        //this.srchOptDiv.css('background-color', this.panelContainer.css('background-color'));
	        $(divContainer).css('background-color', this.panelContainer.css('background-color'));
	        // add to fly div
	        this.srchPanelHeader.appendTo(divContainer);
	        this.srchOptDiv.appendTo(divContainer);   
	        // hide div for panel mode
	        this.srchCtrlsBlock.hide();
	        // move all content to table from divs
	        this.moveCtrlsToTable();
	        // show table for window mode
	        this.srchCtrlsBlockWin.show();	        
	        // resize and set coors with new content
	        var flyDivDimAndCoorsObj = getFlyDivSizeAndCoors(this.flyDivId, x, y);
	        setFlyDivDimAndCoors(flyDivDimAndCoorsObj, this.flyDivId);	 
	        
	        this.showSearchOptions();
	        // set show indicator
	        this.srchWinShowStatus = true;
        }
    	// for first use
    	this.showSearchWin(e, id);        
    },
    /**
     * Move controls when switch to window mode.
     * On each table and div row this method call moveCtrlsToTableRow,
     * which move html and DOM from divs to tds
     */
    moveCtrlsToTable: function(){
    	// loop through div rows
    	for(var i=0; i<this.srchFilterRowArr.length;i++){
    		var divRowId = this.srchFilterRowArr[i].attr('id');
    		var tableRowId = divRowId+'_win';
    		var tableRow = $('#'+tableRowId);
    		if (this.srchFilterRowArr[i].css('display') != 'none'){
    			tableRow.show();   
    		}else{
    			tableRow.css('display', 'none');
    		}
    		 		
    		// move div row content to table row symetrically
    		this.moveCtrlsToTableRow(this.srchFilterRowArr[i], tableRow);
    	}
    },
    /**
     * Used to move html and DOM of each div row to table row
     */
    moveCtrlsToTableRow: function(divRow, tableRow){
    	var divCells = divRow.children();
    	var tds = tableRow.children();
    	// loop through div cells
    	for(var i=0; i<divCells.length;i++){    		
    		// move all content of div cell to td
    		var divCellChildren = $(divCells[i]).children();
    		// clear from script tag, to prevent executing it twice
    		divCellChildren.find('script').remove();
    		// move with DOM objects, not only HTML
    		divCellChildren.appendTo($(tds[i]));
    		
    		if ($(divCells[i]).css('display') == 'none'){
    			$(tds[i]).hide();
    		}else{
    			$(tds[i]).show();
    		}
    	}	  
    	// move field name in this way, because it conatins only text
    	$(tds[1]).html($(divCells[1]).html());
    },
    /**
     * Move controls when switch to panel mode, from window.
     * On each table and div row this method call moveCtrlsToDivRow,
     * which move html and DOM from tds to divs
     */
    moveCtrlsToDiv: function(){
    	// loop through div rows
    	for(var i=0; i<this.srchFilterRowWinArr.length;i++){
    		var tableRowId = this.srchFilterRowWinArr[i].attr('id');
    		var divRowId = tableRowId.substr(0, tableRowId.lastIndexOf('_'));//divRowId+'_win';
    		var divRow = $('#'+divRowId);
    		if (this.srchFilterRowWinArr[i].css('display') != 'none'){
    			divRow.show();   
    		}else{
    			divRow.css('display', 'none');
    		}    		
    		// move div row content to table row symetrically
    		this.moveCtrlsToDivRow(this.srchFilterRowWinArr[i], divRow);
    	}
    },
    /**
     * Used to move html and DOM of each table row to div row
     */
    moveCtrlsToDivRow: function(tableRow, divRow){
    	var divCells = divRow.children();
    	var tds = tableRow.children();
    	// loop through div cells
    	for(var i=0; i<tds.length;i++){    		
    		// move all content of div cell to td
    		var tableCellChildren = $(tds[i]).children();
    		// clear from script tag, to prevent executing it twice
    		tableCellChildren.find('script').remove();
    		// move with DOM objects, not only HTML
    		tableCellChildren.appendTo($(divCells[i]));
    		if ($(tds[i]).css('display') == 'none'){
    			$(divCells[i]).hide();
    		}else{
    			$(divCells[i]).show();
    		}
    	}	  
    	// move field name in this way, because it conatins only text
    	$(divCells[1]).html($(tds[1]).html());
    },
    
    /**
    * Removes fly div, and place controls to search panel
    */
    hideSearchWin: function(id) {
    	this.hideCtrlChooseMenu();
    	// remove color to prevent strange controls coverage in IE6
    	//this.srchOptDiv.css('background-color', '');
    	// move opt div to search panel        
        this.srchOptDiv.prependTo(this.panelContainer);
        // hide table
    	this.srchCtrlsBlockWin.hide();
    	// move controls
    	this.moveCtrlsToDiv();
    	// show panel mode div
    	this.srchCtrlsBlock.show();        
        this.srchPanelHeader.prependTo($("#searchform"+this.id));
        // remove fly win
        RemoveFlyDiv(id || this.flyDivId, true);
        // set status indicator
        this.srchWinShowStatus = false;
    },
    
     

    /**
    * Search win switcher
    * opens and closes search win
    */
    toggleSearchWin: function(e) {
        this.srchWinShowStatus ? this.hideSearchWin() : this.showSearchWin(e);
    },
   /**
    * Showes search options div and changes image expander 
    */
    showSearchBlock: function() {
    	// show div
        this.srchBlock.show();
        this.srchBlockStatus = true;
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideSearchBlock: function() {
    	// hide div
        this.srchBlock.hide();
        this.srchBlockStatus = false;
    },
    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleSearchBlock: function() {
        // can open panel, only if win is hidden
        (this.srchBlockStatus && !this.srchWinShowStatus) ? this.hideSearchBlock() : this.showSearchBlock();
    },
    /**
    * Showes search options div and changes image expander 
    */
    showSearchOptions: function() {
    	// to correct amsterdam layout with no menu
    	var mrgLeft = $('#records_block'+this.id).css('margin-left');
    	if (mrgLeft == 'auto' ||  mrgLeft == '0px'){
    		this.recBlockMargChange = true;
    		$('#records_block'+this.id).css('margin-left', 203);
    	}
        // show div
    	this.srchOptDiv.show();	
    	// show bottom round if exist
        this.bottomPanelRound.css('display',  '');
        // change image
        this.srchOptExpander.attr("src", this.hideOptSrc);
        this.srchOptExpander.attr('alt', this.hideOptText);
        this.srchOptExpander.attr('title', this.hideOptText);
        this.srchOptShowStatus = true;
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideSearchOptions: function() {
    	// to correct amsterdam layout with no menu 
    	if (this.recBlockMargChange){
    		$('#records_block'+this.id).css('margin-left', 0);
    	}
    	// hide div
    	this.srchOptDiv.hide();
        // hide bottom round if exist
        this.bottomPanelRound.css('display',  'none');
        // change image
        this.srchOptExpander.attr("src", this.showOptSrc);
        this.srchOptExpander.attr('alt', this.showOptText);
        this.srchOptExpander.attr('title', this.showOptText);
        this.srchOptShowStatus = false;
    },
    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleSearchOptions: function() {
        // can open panel, only if win is hidden
        (this.srchOptShowStatus && !this.srchWinShowStatus) ? this.hideSearchOptions() : this.showSearchOptions();
    },

    /**
    * Showes search options div and changes image expander 
    */
    showCtrlChooseMenu: function() { 
    	
    	// lazy init function
    	if (Runner.isIE6){    		
    		this.iframe = new Runner.util.IEHelper.iframe(/*this.ctrlChooseMenuDiv[0]*/);
    		this.hider = new Runner.util.IEHelper.selectsHider(this.ctrlChooseMenuDiv[0]);
    	}
    	// redefine
    	this.showCtrlChooseMenu = function(){
			// set menu position, relative to Add criteria link
    		var posObj = findPos($("#showHideControlChooseMenu"+this.id)[0]);
			// calc coordinates
    		var divT = posObj[1]+posObj[3], divL = posObj[0];
	    	// add only in win mode, strange positioning in fly div
	    	this.ctrlChooseMenuDiv.css('top', divT).css('left', divL);
	    	// show it
	        this.ctrlChooseMenuDiv.show();
	         // set div width, after div is visible, for correct offsetWidth data
	        this.ctrlChooseMenuDiv[0].offsetWidth < 80 ? this.ctrlChooseMenuDiv.css('width', '65px') : '';
	        // add iframe in panel mode
	        if (Runner.isIE6 && !this.srchWinShowStatus){
	       		// create iframe for IE6
		        this.iframe.reset({
					l: divL,
					t: divT,
					h: this.ctrlChooseMenuDiv[0].offsetHeight,
					w: this.ctrlChooseMenuDiv[0].offsetWidth
				});      				
			// in window mode hide combos	
	        }else if(Runner.isIE6 && this.srchWinShowStatus){
	        	this.hider.showSels();
	        	this.hider.getSelects();
	        	this.hider.hideSels();
	        }
	        // set max z-index
	        Runner.getZindex(this.ctrlChooseMenuDiv);
	        this.ctrlChooseMenuStatus = true;
    	}
    	// call function, after lazy-init
    	this.showCtrlChooseMenu();
    },
    /**
    * Closes search options div and changes image expander 
    */
    hideCtrlChooseMenu: function() {
        this.ctrlChooseMenuDiv.hide();
        this.ctrlChooseMenuStatus = false;
        if (Runner.isIE6 && !this.srchWinShowStatus && this.iframe){
        	this.iframe.hide();
        }else if(Runner.isIE6 && this.srchWinShowStatus && this.iframe){
        	this.hider.showSels();
        }
    },

    /**
    * Search options switcher
    * opens and closes options in search panel
    */
    toggleCtrlChooseMenu: function() {
        this.ctrlChooseMenuStatus ? this.hideCtrlChooseMenu() : this.showCtrlChooseMenu();        
    },
    
	/**
    * Search type combos show handler
    */
    showCtrlTypeCombo: function() {
        for (var i = 0; i < this.searchTypeCombosArr.length; i++) {        	
	    	this.searchTypeCombosArr[i].show();	
	    	this.searchTypeCombosArr[i].find('select').show();
        }
        for (var i = 0; i < this.searchTypeCombosWinArr.length; i++) {        	
	    	this.searchTypeCombosWinArr[i].show();	
	    	this.searchTypeCombosWinArr[i].find('select').show();
        }
        this.showHideSearchComboButton.html(this.hideComboText);
        this.showHideSearchComboButton.attr('title', this.hideComboText);
        this.ctrlTypeComboStatus = true;
        
    },
    /**
    * Search type combos hide handler
    */
    hideCtrlTypeCombo: function() {
        for (var i = 0; i < this.searchTypeCombosArr.length; i++) {        	
            this.searchTypeCombosArr[i].hide();
            this.searchTypeCombosArr[i].find('select').hide();
        }
        for (var i = 0; i < this.searchTypeCombosWinArr.length; i++) {        	
            this.searchTypeCombosWinArr[i].hide();
            this.searchTypeCombosWinArr[i].find('select').hide();
        }
        this.showHideSearchComboButton.html(this.showComboText);
        this.showHideSearchComboButton.attr('title', this.showComboText);
        this.ctrlTypeComboStatus = false;
    },    
    /**
    * Search type combos show\hide switcher
    */
    toggleCtrlTypeCombo: function() {
        this.ctrlTypeComboStatus ? this.hideCtrlTypeCombo() : this.showCtrlTypeCombo();
    },
    /**
     * Criterias show|hide controller
     * @param {int} ctrlsCount
     */
    toggleCrit: function(ctrlsCount){
    	// lazy init, get conditions containers
        var topCritContId = 'srchCritTop'+this.id;
        this.topCritCont = $('#'+topCritContId);
        var bottomSearchButtId = 'bottomSearchButt'+this.id;
        this.bottomSearchButt = $('#'+bottomSearchButtId); 
        // redefine after first call
        this.toggleCrit = function(ctrlsCount){
        	ctrlsCount > 1 ? this.topCritCont.show() : this.topCritCont.hide();
    		ctrlsCount > 0 ? this.bottomSearchButt.show() : this.bottomSearchButt.hide();
        }
        // for first call
		this.toggleCrit();
    }
}); 
/**
 * search panel controller. Used for manage search on the list page
 * for multiple search classes use id param.
 * @class
 * @param {object} cfg
 */
Runner.search.SearchController = Runner.extend(Runner.search.SearchFormWithUI, {
   /**
     * Indicator. True when simple search edit box get focus 
     * @type Boolean
     */
    usedSrch: false,
    /**
     * jQuery obj of simple search edit box
     * @type {obj}
     */
    smplSrchBox: null,
   /**
    * Ajax add filter cache url
    * @type String
    */
    ajaxSearchUrl: "",  
    /**
     * Reusable style display none
     * @type String
     */
    styleDispNoneText: 'display: none;',
    /**
     * Short table name, used for create urls
     */
    shortTName: "",
    /**
     * Override parent contructor
     * Add interaction with server
     * @param {obj} cfg
     */
    constructor: function(cfg){    	
    	//call parent
    	Runner.search.SearchController.superclass.constructor.call(this, cfg);	
    	// set search url, for ajax
        this.ajaxSearchUrl = this.shortTName + '_search.php';
        // edit box any field contains search
        this.smplSrchBox = $('#ctlSearchFor'+this.id);
    },
    
    /**
     * Get index of last added from cache control. 
     * @param {string} filterName
     * @return {int}
     */    
    getLastAddedInd: function(filterName){
    	// if no map for this field
    	if (!this.ctrlsShowMap[filterName]){
    		return false;
    	}
    	// get last added and not cached ctrls block index
    	var maxInd = 0, beforeMaxInd=false, i=0;
		for(var ind in this.ctrlsShowMap[filterName]){			
			// need to convert to int from string. May be because object property name is string, typeof return string
			ind = parseInt(ind);
			// get max index, it will give last cached
			if (maxInd < ind){
				beforeMaxInd = maxInd;
				maxInd = ind;
			}
			// at first time take maxInd, because 0 may not appear
			if (i===0){
				beforeMaxInd = maxInd;
			}
			i++;
		}
		return beforeMaxInd;
    },
    /**
     * returns last added filter, usefull when add new
     * 
     * @param {string} filterName field name
     * @return {obj} true if success otherwise false
     */
    getLastAdded: function(filterName){
    	var beforeMaxInd = this.getLastAddedInd(filterName);
    	if (!beforeMaxInd){
    		return false;
    	}
    	// get obj
    	var filterObj = $('#'+this.getFilterDivId(filterName, beforeMaxInd, this.srchWinShowStatus));    	
    	if (filterObj.length){
    		return filterObj;
    	}else{
    		return false;
    	}
    },
    
    /**
     * Adds ctrls block HTML to DOM
     * @param {string} fName
     * @param {string} ind
     * @param {object} blockHTML
     */
    addCtrlsHtml: function(fName, ind, blockHTML){
    	this.addPanelHtml(fName, ind);
    	this.addTableHtml(fName, ind);
    	// take div container, or tr
    	var rowCont = $('#'+this.getFilterDivId(fName, ind, this.srchWinShowStatus))
    	// put into cells block html
    	var cells = rowCont.children();
    	$(cells[0]).html(blockHTML.delButt);
    	$(cells[2]).html(blockHTML.comboHtml);
    	$(cells[3]).html(blockHTML.control1);
    	$(cells[4]).html(blockHTML.control2);

  		// execute additional js code
		eval(blockHTML.jsCode);	
    },
    
    addTableHtml: function(fName, ind){
    	// ctrl main container id
    	var newSrchCtrlContId = this.getFilterDivId(fName, ind, true);
    	// add ctrl main container
    	var filterRowHtml = this.createTableRow(newSrchCtrlContId, 'winRow', this.styleDispNoneText, '');
    	this.srchCtrlsBlockWin.append(filterRowHtml);
    	// main container obj
    	var newSrchCtrlCont = $("#"+newSrchCtrlContId);
    	// add del button
    	newSrchCtrlCont.append(this.createTableCell('', 'srchWinCell', '', ''));
    	// add div with field name
    	var fNameCellHtml = this.createTableCell('', 'srchWinCell', '', fName+':&nbsp;');
    	newSrchCtrlCont.append(fNameCellHtml);
    	// combo type container id
    	var comboHtml = this.createTableCell(this.getComboContId(fName, ind, true), 'srchWinCell', (this.ctrlTypeComboStatus ? '' : this.styleDispNoneText), '');    	
    	newSrchCtrlCont.append(comboHtml);
    	// add first control obj with html
    	newSrchCtrlCont.append(this.createTableCell('', 'srchWinCell', '', ''));
    	// add second ctrl obj if exists, or set empty container, for border puposes
    	newSrchCtrlCont.append(this.createTableCell('', 'srchWinCell', '', '')); 
    	
    	return newSrchCtrlCont;
    },
    
    addPanelHtml: function(fName, ind){
    	// ctrl main container id
    	var newSrchCtrlContId = this.getFilterDivId(fName, ind);
    	// add ctrl main container
    	var filterDivHtml = this.createDivCont(newSrchCtrlContId, 'srchPanelRow blockBorder', this.styleDispNoneText, '');
    	this.srchCtrlsBlock.append(filterDivHtml);
    	// main container obj
    	var newSrchCtrlCont = $("#"+newSrchCtrlContId);
    	// add del button
    	newSrchCtrlCont.append(this.createDivCont('', 'srchPanelCell', '', ''));
    	// add div with field name
    	var fNameDivHtml = this.createDivCont('', 'srchPanelCell', '', fName+':&nbsp;');
    	newSrchCtrlCont.append(fNameDivHtml);
    	// combo type container id
    	var comboHtml = this.createDivCont(this.getComboContId(fName, ind), 'srchPanelCell srchPanelCell2', (this.ctrlTypeComboStatus ? '' : this.styleDispNoneText), '');    	
    	newSrchCtrlCont.append(comboHtml);
    	// add first control obj with html
    	newSrchCtrlCont.append(this.createDivCont('', 'srchPanelCell srchPanelCell2', '', ''));
    	// add second ctrl obj if exists, or set empty container, for border puposes
    	newSrchCtrlCont.append(this.createDivCont('', 'srchPanelCell srchPanelCell2', '', '')); 
    	
    	return newSrchCtrlCont;
    },
    /**
     * Adds block to map, regs its components and ands HTML
     * @param {} fName
     * @param {} ind
     * @param {} ctrlIndArr
     * @param {} blockHTML
     */
    addRegCtrlsBlock: function(fName, ind, ctrlIndArr, blockHTML){
    	// call parent
    	Runner.search.SearchController.superclass.addRegCtrlsBlock.call(this, fName, ind, ctrlIndArr);
    	//add to DOM
    	blockHTML ? this.addCtrlsHtml(fName, ind, blockHTML) : "";    	
    	// set links for parent and child if lookup ctrl
    	var ctrl = Runner.controls.ControlManager.getAt(this.tName, ind, fName);
    	// if ctrl hidden it's used for cache, than, do not add link
    	if (!ctrl.hidden){
    		//this.setDependences(ctrl, true);	
    		this.setDependences(ctrl);
    	}    	
    	// reg combos
    	this.searchTypeCombosArr.push($("#"+this.getComboContId(fName, ind)));
    	// reg td combos
    	this.searchTypeCombosWinArr.push($("#"+this.getComboContId(fName, ind, true)));
    	// reg filter div block
    	this.srchFilterRowArr.push($("#"+this.getFilterDivId(fName, ind)));
    	// reg filter tr row
    	this.srchFilterRowWinArr.push($("#"+this.getFilterDivId(fName, ind, true)));
    	// call crit controller
  		this.toggleCrit(this.getVisibleBlocksCount());	
    },
   
    /**
     * Creates div container html
     * @param {string} id
     * @param {string} cssClass
     * @param {string} style
     * @param {string} innerHtml
     * @return {string}
     */
    createDivCont: function(id, cssClass, style, innerHtml){
    	return '<div class="'+cssClass+'" id="'+id+'" style="'+style+'">'+innerHtml+'</div>';
    },
    
    createTableRow: function(id, cssClass, style, innerHtml){
    	return '<tr class="'+cssClass+'" id="'+id+'" style="'+style+'">'+innerHtml+'</tr>';
    },
    
    createTableCell: function(id, cssClass, style, innerHtml){
    	return '<td class="'+cssClass+'" id="'+id+'" style="'+style+'">'+innerHtml+'</td>';
    },
    
    /**
     * Put block into right place depending on ctrl type. 
     * If parent field name passed, ctrl will be placed bellow parent
     * If no parent passed, ctrl will be placed above last added for this field
     * 
     * @param {string} filterName
     * @param {int} cachedInd
     * @param {string} parentFieldName
     */
    putCachedBlock: function(filterName, cachedInd, parentFieldName){
    	// get control from cache
        var cachedRow = $("#"+this.getFilterDivId(filterName, cachedInd, this.srchWinShowStatus));        
    	// move cached div to top, insert it after control choose menu
        var lastAdded = this.getLastAdded(filterName);
        // if use parent
        if (parentFieldName){
        	cachedRow.insertAfter(this.getLastAdded(parentFieldName));
        }else if(lastAdded){
        	cachedRow.insertBefore(lastAdded);
        }else{
        	// if no parent, add to window
        	if (this.srchWinShowStatus){
        		this.srchCtrlsBlockWin.prepend(cachedRow);
        	// or to panel container
        	}else{
        		this.srchCtrlsBlock.prepend(cachedRow);
        	}
        	
        }
        // show row with controls
    	cachedRow.show();	
        // make window height bigger
        /*if (this.srchWinShowStatus){
        	this.recalcWindowDim();	
        }*/        
    },
    
    /**
     * Set dependent and parent links to ctrls. 
     * If passed triggerReload, will invoke event of parent ctrl, to reload dependent ctrls
     * 
     * @param {obj} ctrl dependent control
     * @param {string} parentFieldName field name of parent ctrl
     * @param {Boolean} triggerReload pass true to reload dependent ctrls
     * @return {Boolean} true if success otherwise false
     */
    setDependences: function(ctrl, triggerReload){
    	
    	if (!ctrl.parentFieldName || !ctrl.isLookupWizard){
    		return false;
    	}
    	// get parent index
    	var parentInd = this.getLastAddedInd(ctrl.parentFieldName);
    	// get parent ctrl
		var parentCtrl = Runner.controls.ControlManager.getAt(this.tName, parentInd, ctrl.parentFieldName, this.ctrlsShowMap[ctrl.parentFieldName][parentInd][0]);
				
		// add link to child
		if (parentCtrl.showStatus){
			ctrl.setParentCtrl(parentCtrl);		
			// add to dependent array
			parentCtrl.addDependentCtrls([ctrl]);
			// reload all children
			if (triggerReload===true){
				parentCtrl.fireEvent('change');
			}		
		}else{
			ctrl.reload();
		}
		return true;		
    },
    /**
     * Adds filter to panel or window, and loads another one for cache
     * @param {string} filterName
     */
    addFilter: function(filterName) {
    	// index of div, that cached and we need to show it
    	var cachedInd = 0;
		for(var ind in this.ctrlsShowMap[filterName]){
			// need to convert to int from string. May be because object property name is string, typeof return string
			ind = parseInt(ind);
			// get max index, it will give last cached
			cachedInd = cachedInd < ind ? ind : cachedInd;
		}        
		// index of last cached ctrl for this field
    	var cachedCtrlIndArr = this.ctrlsShowMap[filterName][cachedInd];    
        //------------------------------------------------------------------------------------------
        // process controls
        var objIndForCM, parentFieldName, parentCtrl = null, parentInd = false, ctrl1;
    	// scan each object
		for(var i=0;i<cachedCtrlIndArr.length;i++){
        	// index of object that stored in CM
        	objIndForCM = cachedCtrlIndArr[i];
        	// get ctrl
        	var ctrlFromCache = Runner.controls.ControlManager.getAt(this.tName, cachedInd, filterName, objIndForCM);
        	// save link to first ctrl, at the end use it to set focus on it
        	if (i===0){
        		ctrl1 = ctrlFromCache;
        		// show ctrl
        		ctrl1.show();
        	}        	
        	// get parentFieldName for lookup ctrls and add dependeces to lookup ctrls
        	parentFieldName = ctrlFromCache.parentFieldName;
        	// set dependeces between child and parent if these links could be
    		this.setDependences(ctrlFromCache, true);
        	// clear javascript, to prevent it executing second time
        	ctrlFromCache.spanContElem.find('script').remove();
        }        
        //------------------------------------------------------------------------------------------
        // place ctrl depend on it's type: lookup or simple
        this.putCachedBlock(filterName, cachedInd, parentFieldName);        
        // show type combo, if it shown in others ctrl
        if (this.ctrlTypeComboStatus){
        	$("#"+this.getComboContId(filterName, cachedInd)).show();	
        }
        // set focus to added ctrl, turned off in window mode, because it cause bad visual effects in bottom control in window mode
        if (!this.srchWinShowStatus){
        	ctrl1.setFocus();
        }        
        //------------------------------------------------------------------------------------------
        
        // ajax params
        var ajaxParams = {
            searchControllerId: this.id,
            rndval: Math.random(),
            mode: "inlineLoadCtrl",
            ctrlField: myEncode(filterName),
            id: flyid
        };

        
        // create var for ajax handler closure
        var controller = this;
        // ajax query and callback func 
        $.getJSON(this.ajaxSearchUrl, ajaxParams, function(ctrlJSON, queryStatus){
        	// register new ctrl block        	
        	controller.addRegCtrlsBlock(filterName, ctrlJSON.divInd, (ctrlJSON.control2 ? [0, 1] : [0]), ctrlJSON);
        });
    },
    /**
     * Deletes controls, its objects add html from DOM
     * @param {string} fName
     * @param {int} ind
     */
    delCtrl: function(fName, ind){    	
    	var objIndForCM;

        // ureg ctrls, loop will delete also second ctrl, if it was created
		for(var i=0;i<this.ctrlsShowMap[fName][ind].length;i++){
        	// index of object that stored in CM
        	objIndForCM = this.ctrlsShowMap[fName][ind][i];
        	// for lookup ctrls, clear links from children and trigger reload them with all values
        	if (objIndForCM.isLookupWizard){
        		objIndForCM.clearChildrenLinks(true);
        	}
        	// delete each object
        	Runner.controls.ControlManager.unregister(this.tName, this.id, fName, objIndForCM);
        }        
        
        // remove element from dom
        this.removeComboById(this.getComboContId(fName, ind));
        this.removeFilterById(this.getFilterDivId(fName, ind));
        // set new window dimensions
        if (this.srchWinShowStatus){
        	this.recalcWindowDim();	
        }        
        // call crit controller
        this.toggleCrit(this.getVisibleBlocksCount());
        // remove from ctrl show map
        delete this.ctrlsShowMap[fName][ind];
    },
    /**
     * Deletes filter by id, removes from array and DOM element
     * @param {string} id
     */
    removeFilterById: function(id){
    	var isUseWinId = (id.lastIndexOf('_win') != -1);
    	id = (isUseWinId ? id.substr(0, id.lastIndexOf('_')) : id);
		// del from panel arr
    	var elemInd = this.srchFilterRowArr.getIndexOfElem(id, function(val, elem){
    		return elem.attr('id')==val;
    	});
    	if (elemInd !== -1){
    		this.srchFilterRowArr[elemInd].remove();       
    		this.srchFilterRowArr.splice(elemInd, 1);
    	}
    	/*for(var i=0; i<this.srchFilterRowArr.length; i++){
    		if (this.srchFilterRowArr[i].attr('id')==id){
    			this.srchFilterRowArr[i].remove();        
    			this.srchFilterRowArr.splice(i, 1);
    		}
    	}*/
    	id += '_win';
    	// del from win arr
    	var elemInd = this.srchFilterRowWinArr.getIndexOfElem(id, function(val, elem){
    		return elem.attr('id')==val;
    	});
    	if (elemInd !== -1){
    		this.srchFilterRowWinArr[elemInd].remove();        
    		this.srchFilterRowWinArr.splice(elemInd, 1);
    	}
    	/*for(var i=0; i<this.srchFilterRowWinArr.length; i++){
    		if (this.srchFilterRowWinArr[i].attr('id')==id){
    			this.srchFilterRowWinArr[i].remove();        
    			this.srchFilterRowWinArr.splice(i, 1);
    		}
    	}*/
    },
    /**
     * Deletes combo cont by id, removes from array and DOM element
     * @param {string} id
     */
    removeComboById: function(id){
    	/*for(var i=0; i<this.searchTypeCombosArr.length; i++){
    		if (this.searchTypeCombosArr[i].attr('id')==id){
    			this.searchTypeCombosArr[i].remove();        
    			this.searchTypeCombosArr.splice(i, 1);
    		}
    	}*/
    	var isUseWinId = (id.lastIndexOf('_win') != -1);
    	id = (isUseWinId ? id.substr(0, id.lastIndexOf('_')) : id);
		// del from panel arr
    	var elemInd = this.searchTypeCombosArr.getIndexOfElem(id, function(val, elem){
    		return elem.attr('id')==val;
    	});
    	if (elemInd !== -1){
    		this.searchTypeCombosArr.splice(elemInd, 1);
    	}
    	id += '_win';
    	// del from win arr
    	var elemInd = this.searchTypeCombosWinArr.getIndexOfElem(id, function(val, elem){
    		return elem.attr('id')==val;
    	});
    	if (elemInd !== -1){
    		this.searchTypeCombosWinArr.splice(elemInd, 1);
    	}
    },
    /**
     * Get number of visible ctrls blocks
     * @return {int}
     */
    getVisibleBlocksCount: function(){
    	var visCount = 0;
    	// use tr arr if window mode, or div arr if panel
    	var rowArr = (this.srchWinShowStatus ? this.srchFilterRowWinArr : this.srchFilterRowArr);
    	// loop through all filters to get which are visible
    	for(var i=0; i<rowArr.length; i++){
    		if (rowArr[i].css('display') != 'none'){
    			visCount++;
    		}
    	}
    	return visCount;
    },
     /**
     * Create and submit form 
     */
    submitSearch: function(){    	 
    	// clear any field contains search if it wasn't used
    	if (!this.usedSrch && this.smplSrchBox.val().trim() === 'search'){
    		this.smplSrchBox.val('');
    	}
    	// add fields thats appear only on list panel mode
    	this.addToForm(this.smplSrchBox.val(), 'ctlSearchFor');
    	// search controller params
    	this.addToForm(this.srchOptShowStatus ? 1 : 0, 'srchOptShowStatus');
    	this.addToForm(this.ctrlTypeComboStatus ? 1 : 0, 'ctrlTypeComboStatus');
    	this.addToForm(this.srchWinShowStatus ? 1 : 0, 'srchWinShowStatus');
    	
    	Runner.search.SearchController.superclass.submitSearch.call(this);
    },
    /**
     * Resets form ctrls, for panel
     * @return {Boolean}
     */
    resetCtrls: function(){
    	var objIndForCM;
    	
    	for(var fName in this.ctrlsShowMap){
			for(var ind in this.ctrlsShowMap[fName]){
				for(var i=0;i<this.ctrlsShowMap[fName][ind].length;i++){
					// index of object that stored in CM
		        	objIndForCM = this.ctrlsShowMap[fName][ind][i];
		        	// delete each object
		        	var ctrl = Runner.controls.ControlManager.getAt(this.tName, this.id, fName, objIndForCM);
		        	ctrl.reset();
				}
			}
        }
		return false;
    }  
});
 
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



 
/**
 * TextArea control class
 */
Runner.controls.TextArea = Runner.extend(Runner.controls.Control,{
	/**
	 * Override constructor
	 * @param {Object} cfg
	 */
	constructor: function(cfg){		
		this.addEvent(["change", "keyup"]);		
		// call parent
		Runner.controls.TextArea.superclass.constructor.call(this, cfg);
		// change input type, because textarea don't have type attr
		this.inputType = "textarea";		
	},
	/**
	 * Clone html for iframe submit
	 * @return {array}
	 */
	getForSubmit: function(){
		return [this.valueElem.clone().val(this.getValue())]
	}
});



 
/**
 * Class for text fields control
 */
Runner.controls.TextField = Runner.extend(Runner.controls.Control, {
	constructor: function(cfg){
		this.addEvent(["change", "keyup"]);		
		Runner.controls.TextField.superclass.constructor.call(this, cfg);		
	}	
});


 
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


/**
 * Radio control class
 * @requires Runner.controls.Control
 */
Runner.controls.RadioControl = Runner.extend(Runner.controls.Control, {
	/**
	 * Radio DOM elem id, starts from + _i 
	 * where i index of element, starts from 0
	 * @type {string} 
	 */
	radioElemsId: "",
	/**
	 * Radio jQuery obj
	 * @type {Object} 
	 */
	radioElemsArr: [],
	/**
	 * checkbox name attr 
	 * @type String
	 */
	radioElemsNameAttr: "",
	/**
	 * jQuery object which contains all radios
	 * @type {object}
	 */
	radioElem: null,
	/**
	 * Count of radio buttons
	 * @type {int}
	 */
	radioElemsCount: 0,
	/**
	 * Override parent contructor
	 * @constructor
	 * @param {Object} cfg
	 */
	constructor: function (cfg){
		this.radioElemsArr = new Array();
		cfg.stopEventInit = true;
		//call parent
		Runner.controls.RadioControl.superclass.constructor.call(this, cfg);	
		// id starts from
		this.radioElemsId = "radio_"+this.goodFieldName+"_"+this.id+"_";
		// radio elems name attr
		this.radioElemsNameAttr = "radio_"+this.goodFieldName+"_"+this.id; 
		// add radio DOM jQuery elem		
		this.radioElem = $('input[@name='+this.radioElemsNameAttr+']');
		// count of elems get from jQuery obj
		this.radioElemsCount = this.radioElem.length;
		// array of radios		
		for(var i=0;i<this.radioElemsCount;i++){
			this.radioElemsArr.push($("#"+this.radioElemsId+i));
			//elems for event are radios
			this.elemsForEvent.push(this.radioElemsArr[i].get(0));
		}		
		// initialize control disabled
		if (cfg.disabled==true || cfg.disabled=="true"){
			this.setDisabled();
		}
		// add events
		this.addEvent(["click"]);
		// init events
		this.init();
	},
	/**
	 * Set value to the control
	 * @param {string} val
	 * @param {bool} triggerEvent
	 * @return {bool}
	 */
	setValue: function(val, triggerEvent){
		var choosen = false;
		// loop for all radio elements
		for(var i=0;i<this.radioElemsCount;i++){
			if(this.radioElemsArr[i].val() == val){
				// set checked radio element
				this.radioElemsArr[i].get(0).checked = true;
				//set value in hidden eleme
				this.valueElem.val(val);
				choosen = true;
			}else{
				this.radioElemsArr[i].get(0).checked = false;
			}				
		}
		if(triggerEvent===true){
			this.fireEvent("change");
		}	
		return choosen;
	},
	/**
	 * Sets disable radio control
	 * @method
	 */
	setDisabled: function(){
		for(var i=0;i<this.radioElemsCount;i++){
			this.radioElemsArr[i].get(0).disabled = true;		
		}			
		return true;
	},	
	/**
	 * Sets disaqble attr false
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	setEnabled: function(){
		for(var i=0;i<this.radioElemsCount;i++){
			this.radioElemsArr[i].get(0).disabled = false;		
		}			
		return true;
	},	
	/**
	 * Clear blur event handler
	 */
	"blur": Runner.emptyFn,

	/*"change": function(e){
		// stop event
		this.stopEvent(e);				
		console.log(e, "change fired. This event object should used to change data in hidden element");
		//this.setValue(e.selected);
		// validate and return validation result
		return this.validate();		
	},*/
	
	"click": function(e){
		if (e.currentTarget.value != this.getValue()){	
			// set new val to hidden elem
			this.setValue(e.currentTarget.value, false);
			// validate and return validation result
			return this.validate().result;
		}else{
			return true;
		}
	}
	
});
/**
 * Abstract base class for LookupWizard fields, should not created directly.
 * Contains common functionality for dependent lookup wizard controls
 * @class 
 * @requires Runner.controls.Control
 */
Runner.controls.LookupWizard = Runner.extend(Runner.controls.Control, {
	/**
	 * Lookup wizard indicator
	 * @type Boolean
	 */
	isLookupWizard: true,
	/**
	 * Array dropDownControls which are dependent to this ctrl
	 * @type 
	 */
	dependentCtrls: [],
	/**
	 * Parent ctrl object. Used to get values in lookupSuggest
	 * @type {object}
	 */
	parentCtrl: null,
	/**
	 * Name of parent field
	 * @type String
	 */
	parentFieldName: '',
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */
	constructor: function(cfg){
		// stop event init
		cfg.stopEventInit=true;		
		// recreate object
		this.dependentCtrls = new Array();
		//call parent
		Runner.controls.LookupWizard.superclass.constructor.call(this, cfg);
		//link for add new record or not
		this.addNew = $("#addnew_"+this.valContId);	
		// add change event for reload dependences
		this.addEvent(["change"]);
	},
	/**
	 * Method that called just before ControlManager deleted link on this object
	 */
	destructor: function(){
		// may be need to clear each array element
		delete this.dependentCtrls;
	},
	/**
	 * Add dependent controls to array of controls
	 * @method 
	 * @param {array} ctrlDD array of control objects
	 */
	addDependentCtrls: function(ctrlsArr){
		for(var i=0;i<ctrlsArr.length;i++){
			this.dependentCtrls.push(ctrlsArr[i]);
		}
	},
	/**
	 * Clear links from children to there's parent ctrl	 * 
	 * @param {bool} triggerReload pass true to call reload function on children
	 */
	clearChildrenLinks: function(triggerReload){
		// reload all children
		for(var i=0;i<this.dependentCtrls.length;i++){
			// if children exists
			if(this.dependentCtrls[i]){
				this.dependentCtrls[i].clearParent(triggerReload);
			}			
		}
	},
	/**
	 * Deletes link to parent ctrl, and optionaly reloads this
	 * @param {bool} triggerReload pass true to call reload method
	 */
	clearParent: function(triggerReload){
		this.parentCtrl = null;
		if (triggerReload===true){
			this.reload();
		}
	},
	/**
	 * Set parent ctrl property
	 * @param {object} ctrl
	 */
	setParentCtrl: function(ctrl){
		this.parentCtrl = ctrl;
	},
	/**
	 * Call reload method of each dependent DD
	 * @method
	 */
	reloadDependeces: function(){
		// value of parent ctrl
		var masterCtrlVal = this.getValue();
		// if parent ctrl returns array value, we need to pass only first element of array
		masterCtrlVal = typeof(masterCtrlVal) == 'object' ? masterCtrlVal[0] : masterCtrlVal;
		// reload all children
		for(var i=0;i<this.dependentCtrls.length;i++){
			// if children exists
			if(this.dependentCtrls[i]){
				this.dependentCtrls[i].reload(masterCtrlVal);
			}			
		}
	},
	
	/**
	 * Parse string to array. Used for parse preload and reload params
	 * @param {string} txt
	 * @return {array}
	 */
	parseContentToValues: function(txt){
		if (txt.length==0){
			return false;
		}		
		return txt.split('\n');
	},	
	/**
	 * Override simple dropDown event,
	 * add reloading for dependent dropDowns
	 * @event
	 * @param {event} e
	 */
	"change": function(e){	
		//call parent
		var vRes = this.validate();
		// call reload if value pass validation
		if (vRes.result){
			this.reloadDependeces();
			return true;
		}else{
			return false;
		}
	}
});

/**
 * Select control class. 
 * @requires Runner.controls.LookupWizard
 */
Runner.controls.DropDownLookup = Runner.extend(Runner.controls.LookupWizard, {
	/**
	 * Number of values to select.
	 * @type {Number}
	 */
	multiSel: 1,
	/**
	 * DropDown DOM options array
	 * @type {array}
	 */
	optionsDOM: [],
	/**
	 * Override parent contructor 
	 * @param {object} cfg
	 * @param {int} cfg.multiSelect number of values to select. Must be >= 1
	 */
	constructor: function(cfg){
		// add multiSelect property
		this.multiSel = cfg.multiSel ? cfg.multiSel : 1;		
		// call parent
		Runner.controls.DropDownLookup.superclass.constructor.call(this, cfg);			
		// set input type
		this.inputType = "select";
		//set defaultValue
		this.defaultValue = this.getValue();
		//event elem 
		this.elemsForEvent = [this.valueElem.get(0)];		
		// init events handling
		this.init();	
		// add options array property
		this.optionsDOM = this.valueElem.get(0).options;
		
	},
	/**
	 * Sets value to DropDown. Tries to set all values from array if multiselect control
	 * @param {array} val
	 * @return {bool} true if success otherwise false
	 */
	setValue: function(vals, triggerEvent){
		// number of choosen options
		var choosen = 0;
		for(var i=0; i<this.valueElem.get(0).options.length;i++){
			for(var j=0;j<vals.length;j++){
				if(this.valueElem.get(0).options[i].value==vals[j]){
					if(this.multiSel==1){
						this.valueElem.get(0).selectedIndex=i;
					}//else{
						//console.log(this.optionsDOM, 'opt in setVal');
						this.optionsDOM[i].selected=true;
					//}
					choosen++;					
				}else{
					this.optionsDOM[i].selected=false;
				}// eo if
			}// eo for			
		}// eo for
		
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change in DD called')
			this.fireEvent("change");
		}
		
		// if selected all than success
		if (choosen == vals.length && choosen <= this.multiSel){
			return true;
		}else{
			return false;	
		}		
	},
	/**
	 * Returns values from dropDown. 
	 * @method
	 * @return {array}
	 */
	getValue: function(){
		var selVals = [];
		// loop for all options
		for (var i=0; i<this.optionsDOM.length;i++){
			if (this.optionsDOM[i].selected){
				selVals.push(this.optionsDOM[i].value)
			}
		}
		return selVals;
	},
	/**
	 * Checks if control value is empty. Used for isRequired validation
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		var selVals = this.getValue();
		for(var i=0;i<selVals.length;i++){
			if (selVals[i] == ""){
				return true;
			}
		}
		return false;
	},
	/**
	 * Deletes all options from ctrl
	 * @method
	 */
	clearOptions: function(){
		//this.valueElem.get(0).innerHTML = "";
		this.valueElem.empty();
	},
	/**
	 * Adds option to select
	 * may be need to add options to specified index?
	 * @param {string} text
	 * @param {string} val
	 */
	addOption: function(text, val){
		this.optionsDOM[this.optionsDOM.length]= new Option(unescape(text),unescape(val));
	},
	/**
	 * Add options from array.
	 * Array must have such structure:
	 * array[0] = value, array[1] = text,
	 * array[2] = value, array[3] = text,
	 * 2*i - indexes of values; 2*i+1 - indexes of text. I starts from 0   
	 * @param {array} optionsArr
	 */
	addOptionsArr: function(optionsArr){		
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
		this.clearOptions();	
		// add empty option for non multiple select
		if (this.multiSel==1){
			// add empty option for non multiselect
			this.addOption(TEXT_PLEASE_SELECT, "");				
		}
		// load options
		this.addOptionsArr(parsedOptionsContent);
		// if only one values except please select, so choose it
		//console.log(this.optionsDOM.length, 'this.optionsDOM.length in preload');
		if (this.optionsDOM.length==2){
			//console.log('set 1 val' + this.optionsDOM[1].value);
			this.setValue([this.optionsDOM[1].value], false);	
		}else if(this.optionsDOM.length>0){
			//console.log('set 0 val' + this.optionsDOM[0].value);
			this.setValue([this.optionsDOM[0].value], false);	
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
		// can't reload if no parent ctrl - for safety use
		if (masterCtrlValue && !this.parentCtrl){
			return false;
		}	
		//ajax params
		var ajaxParams = {
			// ctrl fieldName
			field: myEncode(fName),
			// value of master ctrl. Only first val from arr, because multiDrop cannot be master
			value: myEncode((masterCtrlValue !== undefined ? masterCtrlValue : '')),
			// is exist parent, indicator
			isExistParent: (this.parentCtrl ? 1 : 0),
			// tag name of ctrl
			type: this.valueElem[0].tagName,
			// page mode add, edit, etc..
			mode: this.mode,
			// random value for prevent caching
		    rndVal: (new Date().getTime())
		};				
		// for handler closure
		var ctrl = this;	
		// get content		
		$.get(this.shortTableName+"_autocomplete.php", ajaxParams, function(txt, textStatus){	
			// clear all options
			ctrl.clearOptions();	
			// add empty option for non multiple select if it doesn't comes from server data
			if (ctrl.multiSel==1){
				// add empty option for non multiselect
				ctrl.addOption(TEXT_PLEASE_SELECT, "");				
			}
			// parse string with new options
			var parsedOptionsContent = ctrl.parseContentToValues(txt);
			// load options
			if(parsedOptionsContent){
				ctrl.addOptionsArr(parsedOptionsContent);
			}
			// if only one values except please select, so choose it
			if (ctrl.optionsDOM.length==2){
				ctrl.setValue([ctrl.optionsDOM[1].value], false);	
			}else if(ctrl.optionsDOM.length>0){
				ctrl.setValue([ctrl.optionsDOM[0].value], false);	
			}			
			// fire change event, for reload dependent ctrls
			ctrl.fireEvent("change");	
		});
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr true
	 * Sets hidden css style true for link add New
	 * @method
	 */
	setDisabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = true;
			this.addNew.css('visibility','hidden');
			return true;
		}else{
			return false;
		}			
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr false
	 * Sets visible css style true for link add New
	 * @method
	 */
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			this.addNew.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Clone html for iframe submit.
	 * jQuery clone method won't clone object with new selected values
	 * that's why we need to set values in clone object separetely
	 * @return {array}
	 */
	getForSubmit: function()
	{
		var clone = this.valueElem.clone(), selVals = this.getValue();
		var cloneOpt = clone.get(0).options;
		for(var i=0;i<cloneOpt.length;i++)
		{
			for(var j=0;j<selVals.length;j++)
			{
				if(cloneOpt[i].value==selVals[j])
				{
					if(this.multiSel==1)
						clone.get(0).selectedIndex = i;
					cloneOpt[i].selected = true;	
				}
				else
				{
					cloneOpt.selected = false;
				}// eo if
			}// eo for	
		}// eo for
		
		return [clone];
	},

	/**
	 * Drop custom function for blur event
	 * @param {Object} e
	 */
	"blur": Runner.emptyFn	
});

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


/**
 * Base abstract class for lookups with textFields
 * Contains text box editor as display field and hidden field for submit values
 * @class
 * @requires Runner.controls.LookupWizard
 */
Runner.controls.TextFieldLookup = Runner.extend(Runner.controls.LookupWizard, {
	/**
	 * id of jQuery element that display value
	 * Value element in EditBoxLookup is hidden, and used for submit data
	 * @type {string}
	 */
	displayId: "",
	/**
	 * jQuery element that display value
	 * Value element in EditBoxLookup is hidden, and used for submit data
	 * @type {object}
	 */
	displayElem: null,	
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */	
	constructor: function(cfg){
		// call parent
		Runner.controls.TextFieldLookup.superclass.constructor.call(this, cfg);	
		// add display elem id
		this.displayId = "display_"+this.valContId;
		// display jQuery elem
		this.displayElem = $("#"+this.displayId);	
		// set input type
		this.inputType = "text";				
		//event elem 
		this.elemsForEvent = [this.displayElem.get(0)];	
		// initialize control disabled
		if (cfg.disabled==true || cfg.disabled=="true"){
			this.setDisabled();
		}
	},
	/**
	 * Set value to display element
	 * @param {mixed} val
	 * @return {bool} true if success otherwise false
	 */
	setDisplayValue: function(val){
		if (this.displayElem){
			return this.displayElem.val(val);
		}else{
			return false;
		}		
	},
	/**
	 * Get value from value element. 
	 * Should be overriden for sophisticated controls
	 * @method
	 */
	getDisplayValue: function(){			
		if (this.displayElem){
			return this.displayElem.val();
		}else{
			return false;
		}
	},
	/**
	 * Overrides parent method. Value in editBoxLookup is pair of display and hidden values
	 * @method
	 * @param {mixed} dispVal
	 * @param {mixed} hiddVal
	 * @return {Boolean} true if success otherwise false
	 */	
	setValue: function(dispVal, hiddVal, triggerEvent){
		// set hidden value, if all ok
		var isSetHiddVal = this.valueElem.val(unescape(hiddVal));
		if (isSetHiddVal === false){
			return false;
		}
		// set display value, if all ok
		var isSetDispVal = this.setDisplayValue(unescape(dispVal));
		if (isSetDispVal === false){
			return false;
		}
		// trigger event if needed
		if(triggerEvent===true){
			//console.log(triggerEvent, 'triggerEvent on change cb called')
			this.fireEvent("change");
		}		
		return true;
	},
	/**
	 * Overrides parent method. Value in editBoxLookup is pair of display and hidden values
	 * @method
	 * @return {array} pair of values if success otherwise false
	 */	
	getValue: function(){
		//return [this.getDisplayValue(), this.valueElem.val()];
		return [this.valueElem.val(), this.getDisplayValue()];
	},
	
	
	/**
	 * Sets disable attr true
	 * @method
	 */
	setDisabled: function(){
		if (this.displayElem.get(0)){
			this.displayElem.get(0).disabled = true;
			return true;
		}else{
			return false;
		}			
	},

	/**
	 * Sets disable attr false
	 * @method
	 */
	setEnabled: function(){
		if (this.displayElem.get(0)){
			this.displayElem.get(0).disabled = false;
			return true;
		}else{
			return false;
		}
	},	
	/**
	 * Sets focus to the element
	 * @method
	 * @param {bool}
	 * @return {bool}
	 */
	setFocus: function(triggerEvent){
		if (this.displayElem.get(0).disabled != true){
			this.displayElem.get(0).focus();
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
	 * Removes css class to value element
	 * @param {string} className
	 */
	removeCSS: function(className){
		this.displayElem.removeClass(className);
	},
	/**
	 * Adds css class to value element
	 * @param {string} className
	 */
	addCSS: function(className){
		this.displayElem.addClass(className);
	},
	/**
	 * Returns specified attribute from value element
	 * @param {string} attrName
	 */
	getAttr: function(attrName){
		return this.displayElem.attr(attrName);
	},
	/**
	 * Return element that used as display.
	 * Usefull for suggest div positioning
	 * @return {object}
	 */
	getDispElem: function(){
		return this.displayElem;
	},
	/**
	 * Checks if control value is empty. Used for isRequired validation
	 * @method
	 * @return {bool}
	 */
	isEmpty: function(){
		return this.getDisplayValue().toString()=="";
	},	
	/**
	 * First loading, without ajax. Should be called directly
	 * @param {string} txt unparsed values for options
	 * @param {string} selectValue unparsed values of selected options
	 */
	preload: function(txt, selectValue){
		// parse content
		var parsedOptionsContent = this.parseContentToValues(txt);
		// search val
		for(var i=0;i<parsedOptionsContent.length-1;i=i+2){
			if (unescape(parsedOptionsContent[i]) == selectValue){					
				// set values
				this.setValue(parsedOptionsContent[i+1], parsedOptionsContent[i]);
			}
		}		
	},
	/**
	 * Reloading dropdown. Called by change event handler
	 * @param {string} value of master ctrl
	 */
	reload: function(masterCtrlValue){		
		var fName = this.fieldName, tName = this.table, rowId = this.id;
		// can't reload if no parent ctrl - for safety use
		if (masterCtrlValue && !this.parentCtrl){
			return false;
		}		
		var ajaxParams = {
			// ctrl fieldName
			field: myEncode(fName),
			// value of master ctrl
			value: myEncode(masterCtrlValue),
			// is exist parent, indicator
			isExistParent: (this.parentCtrl ? 1 : 0),
			// page mode add, edit, etc..
			mode: this.mode,
			// tag name of ctrl
			type: this.valueElem[0].tagName,
			// random value for prevent caching
		    rndVal: (new Date().getTime())
		};
		// for handler closure
		var ctrl = this;
		// get content		
		$.get(this.shortTableName+"_autocomplete.php", ajaxParams, 
		function(txt, reqStatus){
			// parse content
			var parsedOptionsContent = ctrl.parseContentToValues(txt);
			// set values if from server comes only one value
			if(parsedOptionsContent.length==3){
				// if value changed, so fire change event
				if (ctrl.getValue()[0]!=parsedOptionsContent[0]){
					ctrl.setValue(parsedOptionsContent[1], parsedOptionsContent[0], true);
				}else{
					ctrl.setValue(parsedOptionsContent[1], parsedOptionsContent[0]);
				}	
			// if no vals from server than clear ctrl
			}else{
				ctrl.setValue("", "", true);
			}								
		});
		
	}
});

/**
 * Edit box with ajax popup class with suggest div handling
 * @requires Runner.controls.TextFieldLookup
 */
Runner.controls.EditBoxLookup = Runner.extend(Runner.controls.TextFieldLookup, {
	/**
	 * Focus indicator
	 * @type Boolean
	 */
	focusState: false,
	/**
	 * Don't know for what
	 * @type Boolean
	 */
	isLookupError: false,
	/**
	 * suggestDiv cursor ind
	 * @type 
	 */
	cursor: -1,
	/**
	 * Array of suggest vals
	 * @type {array}
	 */
	suggestValues: [],
	/**
	 * Array of lookup vals
	 * @type {array}
	 */
	lookupValues: [],
	/**
	 * Lookup div id
	 * @type String
	 */	
	lookupDivId: "",
	/**
	 * Lookup div jQuery object
	 * @type {object}
	 */
	lookupDiv: null,
	/**
	 * Lookup div id
	 * @type String
	 */	
	lookupIframeId: "",
	/**
	 * Lookup div jQuery object
	 * @type {object}
	 */
	lookupIframe: null,
	/**
	 * Override parent contructor
	 * @param {object} cfg
	 */	
	constructor: function(cfg){
		// recreate objects
		this.lookupValues = new Array();
		this.suggestValues = new Array();		
		// call parent
		Runner.controls.EditBoxLookup.superclass.constructor.call(this, cfg);
		// set lookup div id
		this.lookupDivId = 'lookupSuggest_'+this.valContId;
		// set lookup iframe id, for IE6
		if (Runner.isIE6){
			this.lookupIframeId = 'iFrame_'+this.valContId;	
		}		
		// events array
		this.addEvent(["keyup", "focus", "keydown", "blur"]);	
		// init events handling
		this.init();				
	},
	/**
	 * Destructor with suggest div remove
	 */
	destructor: function(){
		// call parent
		Runner.controls.EditBoxLookup.superclass.destructor.call(this);
		// destroy div
		this.destroyDiv();
	},
	/**
	 * Keycode after which lookupSuggest should start
	 * @param {} keyCode
	 * @return {}
	 */
	checkKeyCodeForRunSuggest: function(keyCode){
		return (((keyCode >= 65) && (keyCode <= 90)) || ((keyCode >= 48) && (keyCode <= 57))
			|| ((keyCode >= 96) && (keyCode <= 105)) || (keyCode==8) || (keyCode==46) || (keyCode==32)
			|| (keyCode==222));
	},
	/**
	 * Keyup event handler, for call lookupsuggest
	 * Do all work after keypressed
	 */
	"keyup": function(e){		
		this.stopEvent(e);	
		/*console.log(e.keyCode, 'e.keyCode');
		console.log(e, 'e');*/
		
		if (this.getDisplayValue() == ""){
			// remove div
			this.destroyDiv();
			// no errors then
			this.isLookupError = false;
			// remove error highlight
			this.removeCSS("highlight");
			// set empty val and trigger error
			this.setValue("", "", true);
			// return from handler
			return;
		}
		// filter keys
		if (e && this.checkKeyCodeForRunSuggest(e.keyCode)) {			
			//this.showDiv();
			// do request for suggest div data
			this.lookupAjax();
		}		
	},
	/**
	 * Keydown event handler, for make select in suggest
	 * @return {bool}
	 */
	"keydown": function(e){
		// key code
		var keyCode=e.keyCode;
		//console.log(keyCode, 'keyCode in key down');
		switch(keyCode){	
			case 38: //up arrow
				this.moveUp();		
				break;
			case 40: //down arrow
				this.moveDown();
				break;
			case 13: //enter 
				this.destroyDiv();
				return false; 		
				break;				
			case 9: // tab
				this.destroyDiv();
				break;
		}
		return true;		
	},
	/**
	 * Creates and set position of lookup div.
	 * Also set suggest vals
	 */
	showDiv: function(lookupSuggestHtml){
		// create div with html
		$(document).find('body').append('<div id="'+this.lookupDivId+'" class="search_suggest">'+lookupSuggestHtml+'</div>');
		// create iframe for IE6
		if (Runner.isIE6){
			$(document).find('body').append('<iframe id="'+this.lookupIframeId+'" frameborder="1" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="background:white;position:absolute;display:block;opacity:0;filter:alpha(opacity=0);"></iframe>');	
			this.lookupIframe = $("#"+this.lookupIframeId);
		}		
		// get div 
		this.lookupDiv = $("#"+this.lookupDivId);		
		// set div coors
		this.setDivPos();	
		// for compatibility with old way of use search suggest
		this.lookupDiv.css("visibility", "visible")
	},
	/**
	 * Destroys lookupDiv from DOM
	 */
	destroyDiv: function(){
		//console.log('call destroy div', this.lookupDiv);
		// if it wasn't destroyed before
		if (this.lookupDiv){
			this.lookupDiv.remove();
		}
		// destroy iframe for IE6
		if (Runner.isIE6 && this.lookupIframe){
			this.lookupIframe.remove();
			this.lookupIframe = null;
		}
		// clear link 
		this.lookupDiv = null;
	},
	/**
	 * Set div coords
	 */
	setDivPos: function(){
		// get coordinates from global func
		var coors = findPos(this.getDispElem().get(0));
		coors[1] += coors[3];
		this.lookupDiv.css("top",coors[1] + "px");
		this.lookupDiv.css("left",coors[0] + "px");		
		// add highest z index
		if(Runner.isIE){
			this.lookupDiv.css("zIndex",++zindex_max);
		}else{
			this.lookupDiv.css("z-index",++zindex_max);
		}
		// set iframe postition for IE6.
		if (Runner.isIE6 && this.lookupIframe){			
			this.lookupIframe.css("top", coors[1] + "px");
			this.lookupIframe.css("left", coors[0] + "px");
			// for debug and testing
			//alert(this.lookupDiv.css("width")+"---width for set");
			//alert(this.lookupDiv.css("height")+"---height for set");
			/*this.lookupIframe.css("width", this.lookupDiv.css("width") + "px");
			this.lookupIframe.css("height", this.lookupDiv.css("height") + "px");*/
			this.lookupIframe.css("width", this.lookupDiv.css("width"));
			this.lookupIframe.css("height", this.lookupDiv.css("height"));
			/*// don't need to change iframe z-index
			this.lookupIframe.css("zIndex", --this.lookupDiv.css("zIndex"));*/			
		}
	},
	/**
	 * On hover suggest div value div handler
	 * @param {object} divHovered
	 */
	suggestOver: function (divHovered){
		// remake for inner div
		this.lookupDiv.find("div.suggest_link_over").each(function(){
			this.className = 'suggest_link';
		}) ;
		// set highlight style
		divHovered.className = 'suggest_link_over';
		// set new cursor index
		this.cursor = divHovered.id.substring(10);
	},
	/**
	 * On unhover suggest div value div
	 * @param {object} div_value
	 */
	suggestOut: function (divValue){
		divValue.className = 'suggest_link';
	},
	/**
	 * Function that makes request to server and parse content
	 */
	lookupAjax: function(){
		// vars for after request function closure
		var table = this.table, recId = this.id, fName = this.fieldName;
		
		var ajaxParams = {
			searchFor: myEncode(this.getValue()[1]), 
			searchField: myEncode(this.fieldName),
			lookupValue: myEncode(this.getValue()[0]),
			// GET DISP VAL OT HIDD VAL???
			category : (this.parentCtrl ? this.parentCtrl.getDisplayValue() : ""),
			rndVal: (new Date().getTime())
		}
		// do request
		$.get(this.shortTableName+"_lookupsuggest.php", ajaxParams,
		function(txt, textStatus){			
			// prepare vars
			var ctrl = Runner.controls.ControlManager.getAt(table, recId, fName, 0);			
			var hiddVal = ctrl.getValue()[0], dispVal = ctrl.getDisplayValue(), valArr = ctrl.getValue();
			// parse data from server
			var str = txt.split("\n");
			$.each( str, function(i, n){
				str[i] = unescape(n);
			})			
			// if values correct, in recieved data exist looup hidden value
			if (str.isInArray(hiddVal)){
			//if (IsInArray(str,hiddVal,false)) {
				// remove error and highlight
				this.isLookupError = false;
				ctrl.removeCSS("highlight");
				// change new value pair and fire event
				$.each(str, function(i, n){
					if((n.toLowerCase()==valArr[1]) && (str[i-1]!=valArr[0])) {
						// setValue with firing change event which will call reloadDependences
						ctrl.setValue(valArr[1], str[i-1], true);
					}
				});
			}else{
				// make error if no hidden value
				this.isLookupError = true;
				if (!ctrl.focusState){
					ctrl.addCSS("highlight");
				}					
			}
			// if no text from server than exit from handler
			if (!txt){
				return false;
			}
			
			// get suggest and lookup values, concatinate suggest div inner html
			var suggest = "";						
			for(var i=0, j=0; i < str.length-1; i=i+2,j++) {
				// div html, value and event handlers
				suggest += '<div id="suggestDiv'+i+'" style="cursor:pointer;" onmouseover="' +
						'var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +						
						'ctrl.suggestOver(this);" '+
						'onmouseout="var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +
						'ctrl.suggestOut(this);" '+
						'onclick="var ctrl = Runner.controls.ControlManager.getAt(\''+table+'\', '+recId+', \''+fName+'\', 0);' +
						'ctrl.isLookupError = false;' +
						//'console.log(ctrl, \'ctrl\');' +
						'ctrl.removeCSS(\'highlight\');' +
						'ctrl.setValue(ctrl.suggestValues[' + j + '], \'' + str[i] + '\', true);' +
						'ctrl.destroyDiv();" ' +
						'class="suggest_link">' + str[i+1] + '</div>';	
				// change data in arrays
				ctrl.suggestValues[j] = str[i+1];
				ctrl.lookupValues[j] = str[i];
			}
			// show div
			ctrl.showDiv(suggest);
			// set postition
			ctrl.setDivPos();
		});
	},
	/**
	 * Down arrow handler
	 */
	moveDown: function(){		
		// if there are any suggest vals and cursor not on last of them
		if(this.lookupDiv.children().length>0 && this.cursor<this.lookupDiv.children().length){
			// add cursor count - same to move down
			this.cursor++;
			// loop for all suggest vals
			for(var i=0;i<this.lookupDiv.children().length;i++){
				// if val that should be highlighted
				if(i==this.cursor){
					// remove error 
					this.isLookupError = false;					
					this.removeCSS("highlight");
					// make highlight style
					this.lookupDiv.children().get(i).className = "suggest_link_over";
					// get new values
					var suggestVal = this.suggestValues[this.cursor].replace(/\<(\/b|b)\>/gi,""), lookupVal = this.lookupValues[this.cursor];
					// if lookup val changes, than fireEvent
					if (this.getValue()[0] != lookupVal){
						this.setValue(suggestVal, lookupVal, true);	
					}else{
						this.setValue(suggestVal, lookupVal);
					}			
				}
				// set simple suggest val style
				else{					
					this.lookupDiv.children().get(i).className = "suggest_link";
				}
			}
			// for cursor loop
			if (this.cursor==(this.lookupDiv.children().length)) {
				this.cursor=-1;
				this.focus(); 
			}
		}
	},
	/**
	 * Up arrow handler
	 */
	moveUp: function(){
		// there are any suugest vals and dont't know why check that cursor >= -1
		if(this.lookupDiv.children().length>0 && this.cursor>=-1){
			// move up same as make cursor less
			this.cursor--;
			// set cursor on the last values, for loop
			if (this.cursor==-2) {
				this.cursor=this.lookupDiv.children().length-1; 
				this.focus(); 
			}			
			// set styles and values
			for(var i=0;i<this.lookupDiv.children().length;i++){
				// if selected value
				if(i==this.cursor){
					// make highlight styles
					this.lookupDiv.children().get(i).className = "suggest_link_over";
					// get values
					var suggestVal = this.suggestValues[this.cursor].replace(/\<(\/b|b)\>/gi,""), lookupVal = this.lookupValues[this.cursor];
					// if lookup values changes, need to fire change event
					if (this.getValue()[0] != lookupVal){
						this.setValue(suggestVal, lookupVal, true);	
					}else{
						this.setValue(suggestVal, lookupVal);
					}
					// remove error
					this.isLookupError = false;
					this.removeCSS("highlight");
				}
				// remove highlight
				else{
					this.lookupDiv.children().get(i).className = "suggest_link";
				}
			}
		}
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr true
	 * Sets hidden css style true for link add New
	 * @method
	 */
	setDisabled: function()
	{
		if (this.displayElem.get(0))
		{
			this.displayElem.get(0).disabled = true;
			this.addNew.css('visibility','hidden');
			return true;
		}else{
			return false;
		}			
	},
	/**
	 * Overrides parent function for element control
	 * Sets disable attr false
	 * Sets visible css style true for link add New
	 * @method
	 */
	setEnabled: function()
	{
		if (this.valueElem.get(0))
		{
			this.valueElem.get(0).disabled = false;
			this.addNew.css('visibility','visible');
			return true;
		}else{
			return false;
		}
	},
	/**
	 * Blur event handler
	 * @event
	 */
	"blur": function(e){
		this.focusState=false;		
		if (this.isLookupError) {
			this.addCSS("highlight");
		} else {
			this.removeCSS("highlight");
		}
		Runner.controls.EditBoxLookup.superclass["blur"].call(this, e);	
	},
	/**
	 * Focus event handler
	 * @event
	 */
	"focus": function(e){
		this.stopEvent(e);	
		this.focusState=true;
	}	
});

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