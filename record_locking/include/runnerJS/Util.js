Runner.namespace('Runner.util.Event');

Runner.util.Event.getTarget = function(e){
	return e.target || e.srcElement;
}