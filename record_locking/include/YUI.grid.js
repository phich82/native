/**
 * Create new resizable data table
 * param: 'div' - id of element div, inside of it there is a table
		  'table' - id of old data table, which became new resizable data table 	
 */
function createTable(div,table)
{
	var num_cell = 0, id_cell = 0;
	var fields = {fields : []};
	var myColumnDefs = new Array();
	var initCookies = '[';
	var notInclTd = new Array();
	$("#"+table+" tr:eq(0)").find('th').each(function()
	{
		if(this.className == "headerlist" || this.className == "blackshade")
		{
			fields.fields[id_cell] = "column"+id_cell;
			var content = $(this).html();
			if (!YAHOO.util.Cookie.get(window.location.pathname))
			{
				var obj = {key:'column'+id_cell,label:content,width:this.offsetWidth,resizeable:true,sortable:false};
				myColumnDefs[id_cell] = obj;
				if (initCookies != '[') 
					initCookies += ',';
				initCookies += this.offsetWidth + ' ';
			}
			else{
					var tablecookie = YAHOO.util.Cookie.get(window.location.pathname);
					var obj = {key:'column'+id_cell,label:content,width:tablecookie[id_cell],resizeable:true,sortable:false};
					myColumnDefs[id_cell] = obj;
				}
			id_cell++;	
		}
		else 
			notInclTd[notInclTd.length] = num_cell;
		num_cell ++;
	});
	//console.log('notInclTd = ',notInclTd);
	if (!YAHOO.util.Cookie.get(window.location.pathname))
		YAHOO.util.Cookie.set(window.location.pathname,initCookies+']');
	
	YAHOO.example.Data = {areacodes: []};
	
	$("#"+table+" tr:gt(0)").each(function()
	{
		//console.log('$(td[@class^=headerlistdown],this).length = ',$('td[@class^=headerlistdown]',this).length);
		//$(this).attr('rowid')!='add' &&
		if(!$('td[@class^=headerlistdown]',this).length)
		{
			var areaCodes = {};
			var lenData = YAHOO.example.Data.areacodes.length;
			var num_cell = 0, id_cell = 0;
			$('td',this).each(function()
			{
				var st = true;
				for(var i=0;i<notInclTd.length;i++)
				{
					if(num_cell == notInclTd[i])
						st = false;
				}
				if(st)
				{
					var content = $(this).html();
					areaCodes[myColumnDefs[id_cell].key] = content;
					id_cell++;
				}
				num_cell ++;
			});
			YAHOO.example.Data.areacodes[lenData] = areaCodes;
		}
	});
	//console.log('YAHOO.example.Data = ',YAHOO.example.Data);
	
	YAHOO.example.MultipleFeatures = function() 
	{
		var myDataSource = new YAHOO.util.DataSource(YAHOO.example.Data.areacodes);
		myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
		myDataSource.responseSchema = fields;
			
		// draggable Columns
		var myConfigs = {draggableColumns:false};

		var myDataTable = new YAHOO.widget.DataTable(div, myColumnDefs, myDataSource, myConfigs);
		myDataTable.addListener("columnResizeEvent",function(params) 
		{
			var value = new String(params["column"]);
			var num = parseInt(value.substr(23));
			var tablecookie = YAHOO.util.Cookie.get(window.location.pathname);
			var updateCookie = '[';
			var tableLength = tablecookie.length-1;
			for (var cookieIn in tablecookie)
			{
				if (cookieIn == num)
					tablecookie[cookieIn] = params["width"];
				if (cookieIn != tableLength) 
					updateCookie += tablecookie[cookieIn] + ',';
				else 
					updateCookie += tablecookie[cookieIn] + ']';
			}
			YAHOO.util.Cookie.set(window.location.pathname,updateCookie);
		});
			    
		return{
				oDS: myDataSource,
				oDT: myDataTable
			};
	}();
}
/**
 * Prepare for create table with resize
 * param: {'id' - id of page,
		  'useInlineAdd' - Is use inlineAdd or not,
		  'permisAdd' - Has user permission for add or not,
		  'numRows' - Count of rows on the list page in data table}
 */
function prepareForCreateTable(param)
{
	//Old data table on page before became to resize
	var old_table = $('table[@name=maintable]');
	//Id of old data table
	var id_table = $(old_table).attr('id');
	//Add class for body
	$(document.body).attr('class','yui-skin-sam');
	//Determine the id for future resizable data table
	if(!id_table)
	{
		$(old_table).attr('id','tabledata'+param.id);
		id_table = 'tabledata'+param.id;
	}
	//Call function, which create new resizable data table
	createTable($('table[@name=maintable]').parent().attr('id'),id_table);
	//Check if use InlineAdd on page and user has a permission for add
	if(param.useInlineAdd && param.permisAdd)
	{
		//Get new resizable data table
		var table = getTableObj(param.id);
		// if true, then hide first row in new table for future add row
		$('.yui-dt-data tr:first',table).hide();
		//If there isn't any rows in old data table, then hide new resizable data table
		if(!param.numRows)
			$(table).hide();
	}		
}
/**
 * Prepare for show inlineAdd in new resizable data table
 * param: 'id' - id of page,
 */
function inlineAddIfUseResize(id)
{
	var table = getTableObj(id)
	//console.log('table = ',table);
	if(!table)
		return false;
	var yuiRec = $('.yui-dt-data tr:first',table);
	//console.log('yuiRec = ',yuiRec);
	//console.log('len = ',$(yuiRec).length);
	//console.log('display = ',$(yuiRec).css('display'));
	if(!$(yuiRec).length)	
		return false;
	else if($(yuiRec).css('display') == 'none')
	{
		$(table).show();
		return {'id':$(yuiRec).attr('id'),'name':"yui-rec-add"};
	}
	else
		return false
}























