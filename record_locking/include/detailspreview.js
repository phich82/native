//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Object dpInline for work on pages list
 * 
 */
function detailsPreviewInline(params)
{
	/**
	 * Details preview params
	 * params:  pageId:integer - id of current (master's) page, 
				mode:string - mode for displaying dpInline,
				ext:string - current extend of page for project
				mSTable:string - master short table name
				mTable:string - master data source table name
	 */
	this.prm = params;
	/**
	 * Is show error happend for dpInline or not
	 */
	this.isShowErrorHappend = false;
	/**
	 * Get current row id
	 * return string	
	 */
	this.getRowId =  function(rPrm)
	{
		var tr = this.getCurrentTr(rPrm);
		if(!tr)
		{
			//console.log('error_1');
			//alert('error_1');
			this.ShowErrorHappend();
			return;
		}	
		if($(tr).attr("rowid"))
			return $(tr).attr("rowid");
		else
			return $(tr).attr("id").substring(7);
	}
	/**
	 * Get current TR element
	 * return object	
	 */
	this.getCurrentTr = function(rPrm)
	{
		var trParents = $(rPrm.linkId).parents("tr");
		if(!trParents.length)
		{
			//console.log('error_2');
			//alert('error_2');
			this.ShowErrorHappend();
			return;
		}
		for(var i=0;i<trParents.length;i++)
			if($(trParents[i]).attr("rowid") || $(trParents[i]).attr("id"))
				break;
		if(i == trParents.length)
		{
			//console.log('error_3');
			//alert('error_3');
			this.ShowErrorHappend();
			return;
		}
		return trParents[i];	
	}
	/**
	 * Prepare for show dpInline
	 */
	this.showDPInline = function(dTable,recId)	
	{
		//Current link used for showing dpInline
		var linkId = "#" +dTable + "_preview" + recId; 
		var linkObj = $(linkId)[0];
		var id = ++flyid;
		var rPrm = {'id':id,'recId':recId,'linkId':linkId,'dTable':dTable};
		
		//check if preview TR is created
		this.createPreviewTr(rPrm);
		
		//get details page contents
		var tdPreview = $("#dpreview_"+this.prm.mSTable+"_"+recId)[0];
		if(!tdPreview)
		{
			//console.log('error_4');
			//alert('error_4');
			this.ShowErrorHappend();
			return;
		}
		var mData = this.getMasterData(linkObj);
		if(!mData)
		{
			//console.log('error_5');
			//alert('error_5');
			this.ShowErrorHappend();
			return false;
		}
		var url = dTable+"_list.php"+mData.query;
		
		tdPreview.style.borderWidth = "1px";
		tdPreview.style.borderStyle = "solid";
		tdPreview.style.borderColor = "darkgray";
		
		if(!tdPreview.innerHTML.length)
			$(tdPreview).html(TEXT_LOADING + "...");
		
		var dpInline = this;
	
		$.get(url, 
		{
			counter: 0,
			id: id,
			masterid: dpInline.prm.pageId,
			mode: "dpinline",
			firsttime: 1,
			rndVal: (new Date().getTime())
		}, 
		function(xml)
		{ 
			var i=xml.indexOf("\n");
			var js="";
			if(i>=0)
			{
				js = slashdecode(xml.substr(0,i));
				txt = xml.substr(i+1);
			}
			dpInline.DisplayPreview(txt,js,rPrm);
		});				
		
	}
	/**
	 * If there isn't dpPreview row, that create new 
	 */
	this.createPreviewTr = function(rPrm)
	{	
		var rowId = this.getRowId(rPrm);	
		var dpInline = this;
		if(!rowId)
		{
			//console.log('error_6');
			//alert('error_6');
			this.ShowErrorHappend();
			return;
		}
		if(!$("#dpreviewrow_"+this.prm.mSTable+"_"+rowId)[0])
		{
			//count number if cells in TR, find current record in row
			var colsCount = new Array();
			var start = 0, myplace=0;;
			var tr = this.getCurrentTr(rPrm);
			if(!tr)
			{
				//console.log('error_7');
				//alert('error_7');
				this.ShowErrorHappend();
				return;
			}
			var trChildren = $(tr).children("td");
			var tdParents = $(rPrm.linkId).parents("td");
			if(!tdParents.length)
			{
				//console.log('error_8');
				//alert('error_8');
				this.ShowErrorHappend();
				return;
			}
			var tdParent = tdParents[0];
			for(i=0;i<trChildren.length;i++)
			{
				if(tdParent == trChildren[i])
					myplace = colsCount.length;
				if($(trChildren[i]).attr("colid") == "endrecord")
				{
					colsCount[colsCount.length] = i-start;
					start = i+1;
				}
			}
			colsCount[colsCount.length] = i-start;
			
			//create new TR
			var previewTr = $(tr).clone();
			$(previewTr).attr("id","dpreviewrow_"+this.prm.mSTable+"_"+rowId);
			$(previewTr).insertAfter(tr);
			previewTr = $("#dpreviewrow_"+this.prm.mSTable+"_"+rowId)[0];
			
			//remove all unnecessary TDs
			$("td[@colid!=endrecord]",previewTr).remove();
			
			//fill row with new TDs
			trChildren = $(previewTr).children("td");
			for(i=0;i<trChildren.length;i++)
				$(trChildren[i]).before("<td id=\"dpreview_"+this.prm.mSTable+"_"+(rPrm.recId+i-myplace)+"\" colspan="+colsCount[i]+"></td>");
			if(i)
				$(trChildren[i-1]).after("<td id=\"dpreview_"+this.prm.mSTable+"_"+(rPrm.recId+i-myplace)+"\" colspan="+colsCount[i]+"></td>");
			else
				$(previewTr).html("<td id=\"dpreview_"+this.prm.mSTable+"_"+(rPrm.recId+i-myplace)+"\" colspan="+colsCount[i]+"></td>");
		}
		else{
				$("a[id$=_preview"+rPrm.recId+"]").each(function()
				{
					if(this != $(rPrm.linkId)[0])
					{
						var pos = this.id.lastIndexOf("_preview");
						if(pos<0) 
							return;
						var dTable = this.id.substring(0,pos);
						$(this).html(TEXT_PREVIEW);
						$("#dpreview_"+dpInline.prm.mSTable+"_"+rPrm.recId).html(TEXT_LOADING + "...");
						this.onclick = function()
						{
							dpInline.showDPInline(dTable,rPrm.recId); 
							return false;
						};
					}
				});
			}
	}
	/**
	 * Display dpPreview
	 * params - html code, js code	
	 */
	this.DisplayPreview = function(html,js,rPrm)
	{
		if(this.prm.mode == 'dpinline_edit_add' || rPrm.mode == 'dpinline_edit_add')
				var dPreview = $("#detailPreview"+this.prm.pageId)[0];
		else if(this.prm.mode == 'dpinline_list')
		{
			var dPreview = $("#dpreview_"+this.prm.mSTable+"_"+rPrm.recId)[0];
			var dpInline = this;
			$(rPrm.linkId).html(TEXT_HIDE);
			$(rPrm.linkId)[0].onclick = function() 
			{
				dpInline.hideDPInline(rPrm); 
				return false;
			};
			var io = this.createPreviewIframe(rPrm);
			var form = this.createPreviewForm(rPrm);
		}
		else{
				//console.log('error_9');
				//alert('error_9');
				this.ShowErrorHappend();
				return;
			}
		$(dPreview).empty();
		$(dPreview).html(html);
		//alert(js);	
		if(js.length)
			eval(js);
	
	}
		
	/**
	 * Hide dpInline row 
	 */
	this.hideDPInline = function(rPrm)
	{
		var dpInline = this;
		$(rPrm.linkId).html(TEXT_PREVIEW);
		$(rPrm.linkId)[0].onclick = function() 
		{
			dpInline.showDPInline(rPrm.dTable,rPrm.recId); 
			return false;
		};
	
		var tdPreview = $("#dpreview_" + this.prm.mSTable + "_" + rPrm.recId);
		if(!tdPreview)
		{
			//console.log('error_10');
			//alert('error_10');
			this.ShowErrorHappend();
			return;
		}
		$(tdPreview).html();
		$(tdPreview).css('border', 'none');
		
		//check if whole row can be removed
		var trParents = $(tdPreview).parents("tr");
		if(!trParents.length)
		{
			//console.log('error_11');
			//alert('error_11');
			this.ShowErrorHappend();
			return;
		}
		var previewTr = trParents[0];
		
		var trChildren = $(previewTr).children("td");
		for(var i=0;i<trChildren.length;i++)
		{
			if($(trChildren[i]).attr("colid") && $(trChildren[i]).attr("colid")!="endrecord" && trChildren[i].innerHTML.length)
				break;
		}
		if(i<trChildren.length)
		{
			//console.log('error_12');
			//alert('error_12');
			this.ShowErrorHappend();
			return;
		}
		$(previewTr).remove();
		var frameId = this.getIdForIframe(rPrm);
		removeFlyFrame = $("#" + frameId)[0];
		removeForm = $("#frmAdmin" + rPrm.id)[0];
		setTimeout('$(removeFlyFrame).remove(); $(removeForm).remove();',100);
	}
	
	/**
	 * Define ID for iframe id
	 * If mode simple_add, then ID which adding to iframe it's pageId of page add
	 * If mode dpinline_list, then ID which adding to iframe it's increasing flyid on 1
	 * If mode dpinline_edit_add, then ID which adding to iframe it's pageId of detail list page
	 * return string - name of iframe
	 */
	this.getIdForIframe = function(rPrm)
	{
		if(this.prm.mode == 'simple_add' || this.prm.mode == 'dpinline_edit_add' || (rPrm && rPrm.mode == 'dpinline_edit_add'))
			id = this.prm.pageId;
		else if(rPrm)
			id = rPrm.id;
		return	'flyframe' + id;
	}
	
	/**
	 * Create for dpInline iframe
	 * return object
	 */	
	this.createPreviewIframe = function(rPrm)
	{
		// create frame
		var frameId = this.getIdForIframe(rPrm);
		var dpInline = this;
		// iframe already exists - reset load counter only
		if($('#'+frameId).length)
		{
			delete $('#'+frameId).loadCount;
			return;
		}
		if(window.ActiveXObject)
		{
			var strPrm = "";
			if(rPrm)
			{
				strPrm = "{";
				for(var el in rPrm)
					strPrm += "'" + el + "':" + (el!='recId' && el!='id' ? "'" + rPrm[el] + "'" : rPrm[el]) + ",";	
				var pos = strPrm.lastIndexOf(",");
				strPrm = strPrm.substring(0, pos) +"}";
			}	
			var onload = "if(typeof this.loadCount == 'undefined') \n"+ 
						 "{ \n"+
						 " 	this.loadCount = 0; \n"+
						 "	return; \n"+
						 "} \n"+
						 "var ioDocument = window.frames['" + frameId + "'].document; \n"+
						 "dpInline" + this.prm.pageId + ".processPreviewReturn(ioDocument," + (!strPrm ? "''" : strPrm) + ");";
			
			var iframetxt="<iframe style = \"position:absolute;\""+ 
						  "onload=\"" + onload + "\""+ 
						  "id = \"" + frameId + "\""+
						  "name = \"" + frameId + "\""+
						  "frameborder = \"0\" vspace = \"0\" hspace = \"0\" marginwidth = \"0\" marginheight = \"0\" scrolling = \"no\"/>";
			
			var io = document.createElement(iframetxt);
		}
		else{
				var io = document.createElement('iframe');
				io.id = frameId;
				io.name = frameId;
				$(io).load(function()
				{
					if (typeof this.loadCount == 'undefined') 
					{
						this.loadCount = 0;
						return;
					}
					var ioDocument = $("#"+frameId).get(0).contentDocument;
					dpInline.processPreviewReturn(ioDocument,rPrm);
				});
			}
		io.style.position = 'absolute';
		io.style.top = '-10000px';
		io.style.left = '-10000px';
		document.body.appendChild(io);
		return io;
	}
	
	/**
	 * Create preview form
	 * It's frmAdmin, wich was created on list page
	 * It's creating was moved in this object for work with dpInline on list page
	 * params - html code, js code	
	 */
	this.createPreviewForm = function (rPrm)
	{
		//get iframe id
		var frameId = this.getIdForIframe(rPrm);
		//set form id 
		var id = (rPrm && rPrm.id ? rPrm.id : this.prm.pageId);
		//set master Table Name
		var mTable = (rPrm && rPrm.mTable ? rPrm.mTable : this.prm.mTable);
		//set form id and name
		var formId = 'frmAdmin' + id;
		
		// form already exists - remove checkboxes for delete
		if($('#'+formId).length)
		{
			return;
		}
		//set form action url
		var server_url = rPrm.dTable + '_list.' + this.prm.ext;
		var form = $('<form method="POST" action="' + server_url + '" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');	
		
		$('<input type="hidden" id="a' + id + '" name="a" value="delete">').appendTo(form);
		$('<input type="hidden" name="mode" value="dpinline">').appendTo(form);
		$('<input type="hidden" name="id" value="' + id + '">').appendTo(form);
		if(rPrm && rPrm.id && this.prm.pageId && rPrm.id!=this.prm.pageId)
			$('<input type="hidden" name="masterid" value="' + this.prm.pageId + '">').appendTo(form);
		$('<input type="hidden" name="mastertable" value="' + htmlSpecialChars(mTable) + '">').appendTo(form);	
		
		var mKeys = new Array();
		
		if(rPrm.mKeys)
			mKeys = rPrm.mKeys;
		else{
				var mData = this.getMasterData($(rPrm.linkId)[0]);
				mKeys = mData.keys;
			}
				
		for(var i=0;i<mKeys.length;i++)
			$('<input type="hidden" name="masterkey'+(i+1)+'" value="'+htmlSpecialChars(mKeys[i])+'">').appendTo(form);	
		
		//set attributes
		$(form).css('position', 'absolute');
		$(form).css('top', '-10000px');
		$(form).css('left', '-10000px');
		$(form).attr('target', frameId);
		$(form).appendTo('body');
		return form;
	}
		
	/**
	 * Get masters table data: masterquery and masterkeys
	 *
	 * params - linkObj	
	 */
	this.getMasterData = function(linkObj)
	{
		pos = linkObj.href.indexOf("?");
		if(pos<0)
			return false;
		var masterData = {};
		masterData.query = linkObj.href.substr(pos);
		arr = masterData.query.split("&");
		masterData.keys = [];
		for(var i=1;i<arr.length;i++)
		{
			arr1 = arr[i].split("=");
			masterData.keys[i-1] = unescape(arr1[1]);
		}
		return masterData;
	}
	/**
	 * Submit preview form
	 * Clone checked checkbox elements for submit
	 * param: id
	 */	
	this.submitPreviewForm = function(id)
	{
		var id = (id ? id : this.prm.pageId);
		if($('input[@type=checkbox][@checked][@id^=check'+id+'_]').length && confirm('Do you really want to delete these records?'))	
		{
			var form = $('#frmAdmin' + id);
			$('input[@type=checkbox][@id^=check'+id+'_]',form).each(function()
			{
				$(this).remove();
			});
			$('input[@type=checkbox][@checked][@id^=check'+id+'_]').each(function()
			{
				var clone = $(this).clone();
				var id = $(clone).attr('id');
				$(clone).appendTo(form);
				$('#'+id,form).attr('checked','checked');
			});			
			$(form).submit(); 
		}
		return false;
	}
	
	/**
	 * Process data to return in dpInline
	 */
	this.processPreviewReturn = function(doc,rPrm)
	{
		if($("#data",doc).length)
			txt = $("#data",doc).text();
		else
			txt="error"+doc.body.innerHTML;
		if(this.prm.mode == 'simple_add')	
			eval(txt);
		else{
				if(txt.substr(0,5)=='decli')
				{
					txt = txt.substr(5);
					$("#data",doc).remove();
					this.DisplayPreview(doc.body.innerHTML,txt,rPrm);
				}
				else
				{
					txt = txt.substr(5);
					this.DisplayPreview(txt,"",rPrm);
				}
			}	
	}	
	
	/**
	 * Show error happend for mode dpInline (use if variable isShowErrorHappend = true)
	 * If there isn't clone row for dpInline, then show div with text error
	 * If there is clone row for dpInline, then show text error into this row
	 */
	this.showErrorHappend = function()
	{
		
	}
	
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Object dpInline for work on pages add and edit
 * 
 */
function dpInlineOnAddEdit(dpParams)
{
	/**
	 * Options of detail tables and master table
	 *
	 */
	this.Opts = dpParams;
	/**
	 * Prepare for save all adding records for detail table on add page
	 *
	 */
	this.prepareForSaveAllDetail = function()
	{
		var validAll = true;
		for(var i=0;i<this.Opts.dInlineObjs.length;i++)
		{
			if(!this.checkValidationAll(this.Opts.dInlineObjs[i]))
				validAll = false;
		}
		if(validAll)
		{
			this.Opts.mMessage = ''; 
			$(this.Opts.mForm).attr('target','flyframe'+this.Opts.mId);
			if(this.isAllSubmitRecords())
			{
				//console.log('submit to master iframe, get id saved masters record');
				$(this.Opts.mForm).get(0).submit();
				//window.frames['flyframe'+this.Opts.mId].location = this.Opts.mShortTableName+'_add.'+this.Opts.ext;
			}
			else{
					//console.log('transition on current page in iframe with parameters');
					window.frames['flyframe'+this.Opts.mId].location = this.Opts.mShortTableName+'_add.'+this.Opts.ext+'?editType=dpinline&isSbmSuc=0';
				}
		}
	}
	/**
	 * Save all detail table
	 * If happend so that there aren't any detail's inline objects, 
	 * then do submit master's form
	 */
	this.saveAllDetail = function()
	{
		if(this.Opts.dInlineObjs.length)
		{	
			for(var i=0;i<this.Opts.dInlineObjs.length;i++)
				this.saveAll(i);
		}
		else
			this.submitMasterForm();
	}
	/**
	 * Save all editing records for current detail table
	 *
	 */
	this.saveAll = function(io)
	{
		var obj = this.Opts.dInlineObjs[io];
		obj.isSbmSuc = true;
		obj.readySbmMaster = false;
		this.getEditRows(obj);
		//console.log('obj.recIds_'+obj.pageid+' = ',obj.recIds)
		this.Opts.dMessages = ''; 
		if(obj.recIds.length)
		{
			for(var i=0;i<obj.recIds.length;i++)
			{
				if(this.Opts.mPageType=="edit")
					$(obj.recIds[i][1]).click();
				else
					obj.submitInputContent(obj.recIds[i][0], "", "add");
			}
			setTimeout('window.dpObj.onLoadIframes('+io+')',1000);
		}
		else{
				obj.readySbmMaster = true;
				if(this.isAllReadySbmMaster())
					this.submitMasterForm()
			}
	}	
	/**
	 * Check on loading iframes for editing rows
	 * If all iframes were load, than submit master's form
	 * Else show massege about happend error at saving detal records 
	 * 
	 */
    this.onLoadIframes = function(io)
	{	
		var obj = this.Opts.dInlineObjs[io];
		//console.log('onLoadIframes_'+obj.pageid);
		for(var i=0;i<obj.recIds.length;i++)
		{
			if(window['uploadFrame'+obj.recIds[i][0]] && obj.isSbmSuc)
			{
				obj.recIds.splice(i,1);
				i--;
			}
		}
		
		if(obj.recIds.length && obj.isSbmSuc)
			setTimeout('window.dpObj.onLoadIframes('+io+')',1000);
		
		if(!obj.isSbmSuc)
			this.prepareMessage(io);
			
		if(obj.isSbmSuc && !obj.recIds.length)
		{
			obj.readySbmMaster = true;
			if(this.isAllReadySbmMaster())
				this.submitMasterForm()
		}	
	}
	
	/**
	 * Prepare message for detail's records
	 * Set disabled fields for master's record if  page type is "add" and record was saved 
	 */
	this.prepareMessage = function(io)
	{
		var obj = this.Opts.dInlineObjs[io];
		var url = document.URL, txt="";
		var pos = url.indexOf("#dt"+obj.pageid);
		if(pos == -1)
			url = url+"#dt"+obj.pageid;
		txt = "<div class='message'><<< Records of Detail Table \""+this.Opts.dCaptions[io]+"\" haven't been saved >>> <br><a href='"+url+"' >go to Detail Table \""+this.Opts.dCaptions[io]+"\"</a></div><br>";	
		this.Opts.dMessages += txt;
		this.showMessage();	
		if(this.Opts.mPageType == "add")
		{
			var arrCntrl = Runner.controls.ControlManager.getAt(this.Opts.mTableName);
			for(var i=0;i<arrCntrl.length;i++)
			{
				if(arrCntrl[i].inputType == "checkbox")
					arrCntrl[i].setDisabledShowCheckedBoxes();
				else
					arrCntrl[i].setDisabled();
			}	
		}	
		window.scroll(0,0); 
	}
	/**
	 * Check all details table for saved all its editing records
	 * 
	 * return boolean
	 */
	this.showMessage = function()
	{
		
		var mes = $("div[@id^=message_block]");
		var mtb = $("div.main_table_border");
		var txt = "";
		if(this.Opts.mMessage)
			txt = this.Opts.mMessage;
		if(!this.isAllReadySbmMaster()) 
			txt = txt+'<br>'+this.Opts.dMessages;
		if($(mes).length)
			$(mes).empty().html(txt);
		else	
			$(mtb).prepend('<div class="downedit" id="message_block"  style="padding:5px;text-align:center;">'+txt+'</div>');
	}
	/**
	 * Get editing rows: id and object - link save
	 *
	 */
	this.getEditRows = function(obj)
	{
		var inlineObject = obj, btn = "";
		obj.recIds = new Array();
		if(this.Opts.mPageType == "add")
			btn = "revert"+obj.pageid;
		else 
			btn = "save"+obj.pageid;
		$('#detailPreview'+obj.pageid+' a[@id^='+btn+'_]').each(function()
		{
			var len = inlineObject.recIds.length;
			var id = $(this).attr('id');
			var pos = id.indexOf("_");
			if(pos>0)
				inlineObject.recIds[len] = [id.substr(pos+1),this];
		});
	}
	/**
	 * Check validation for all editing rows
	 * 
	 * return boolean
	 */
	this.checkValidationAll = function(obj)
	{
		var valResAll = true;
		this.getEditRows(obj);
		for(var i=0;i<obj.recIds.length;i++)
		{
			var arrCntrls = Runner.controls.ControlManager.getAt(obj.tName, obj.recIds[i][0]);
			if(!obj.checkValidation(arrCntrls))
				valResAll = false;
		}
		return valResAll;
	}
	/**
	 * Submit master's form
	 * If page edit - do submit master's form
	 * If page add - do location on master's page add
	 */
	this.submitMasterForm = function()
	{
		//console.log('submit edit form');
		if(this.Opts.mPageType == "edit")
			$(this.Opts.mForm).submit();
		else if(this.Opts.mPageType == "add")
			window.location = this.Opts.mShortTableName+'_add.php';
	}
	/**
	 * Check all details table ready for submit master
	 * 
	 * return boolean
	 */
	this.isAllReadySbmMaster = function()
	{
		var readySbmAll = true;
		for(var i=0;i<this.Opts.dInlineObjs.length;i++)
		{
			var obj = this.Opts.dInlineObjs[i];
			if(!obj.readySbmMaster)
				readySbmAll = false;
		}
		//console.log('readySbmAll = ',readySbmAll);
		return readySbmAll;
	}
	/**
	 * Check all details table for saved all its editing records
	 * 
	 * return boolean
	 */
	this.isAllSubmitRecords = function()
	{
		var sbmAllRec = true;
		for(var i=0;i<this.Opts.dInlineObjs.length;i++)
		{
			var obj = this.Opts.dInlineObjs[i];
			if(!obj.isSbmSuc && obj.recIds.length)
				sbmAllRec = false;
		}
		//console.log('sbmAllRec = ',sbmAllRec);
		return sbmAllRec;
	}
	
}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
