var pdf_included=true;

var maxwidth,maxheight;
function CalcMaxPage()
{
	maxwidth=0;
	maxheight=0;
	var pages;
	if($.browser.msie)
		pages=document.all;
	else
		pages=document.getElementsByTagName('*'); 
	for(i=0;i<pages.length;i++)
	{
		if(pages[i].name!="page")
			continue;
		if(pages[i].offsetWidth>maxwidth)
			maxwidth = pages[i].offsetWidth;
		if(pages[i].offsetHeight>maxheight)
			maxheight = pages[i].offsetHeight;
	}
	if(!maxwidth || !maxheight)
	{
		maxwidth=document.body.scrollWidth;
		maxheight=document.body.scrollHeight;
	}
}
function RunPDF()
{
	CalcMaxPage();
	window.frames['pdf'].location.href=window.page+'&width='+maxwidth+'&height='+maxheight;
//	display progress div	
	var pdiv=document.getElementById("progress");
	pdiv.innerHTML = "<p>"+TEXT_PDF_BUILD1+"<br><span style=\"display:block;background:white;border:solid black 1px;width:100px;height:20px;\"><span id=progress_bar style=\"display:block;background:#6080FF;width:1px;height:100%\"></span></span><br><span id=progress_percent></span>% "+TEXT_PDF_BUILD2+"</p>";
	CheckProgress();
}
window.pdfbuilt=0;
window.counter=0;
var pr_time=(new Date()).valueOf();
var speed=-1;
function CheckProgress()
{
	if(window.pdfbuilt)
		return;
	$.get("pdfprogress.php",{rndval: Math.random()},
		function(txt)
		{
			setTimeout("CheckProgress();",1000);
			var numbers=txt.split(" ");
			if(numbers.length!=2)
				return;
			var total = parseInt(numbers[0]);
			var progress = parseInt(numbers[1]);
			if(isNaN(total) || isNaN(progress))
				return;
			var count = Math.floor(progress*100/total);
			if(speed>0)
			{
				var alpha = 0.5-count*0.5/100;
				count = Math.floor(speed*alpha*((new Date()).valueOf()-pr_time) + count*(1-alpha));
			}
			var pbar=document.getElementById("progress_bar");
			var dtime = (new Date()).valueOf()-pr_time;
			if(dtime)
				speed = 1.0*count/dtime;
			pbar.style.width=""+count+"px";
			document.getElementById("progress_percent").innerHTML=""+count;
		});
}
