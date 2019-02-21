<?php
if (ini_get("pcre.backtrack_limit") < 1000000) { ini_set("pcre.backtrack_limit",1000000); };
if(!ini_get("safe_mode"))
	@set_time_limit(10000);

require_once('html2ps/config.inc.php');
require_once('html2ps/pipeline.factory.class.php');

ini_set("user_agent", DEFAULT_USER_AGENT);

$g_css_index = 0;

// Title of styleshee to use (empty if no preferences are set)
$g_stylesheet_title = "";
$out=2;

if(!$pagewidth || $pagewidth<800)
	$pagewidth = 800;
$GLOBALS['g_config'] = array(
                             'compress'      => false,
                             'cssmedia'      => "screen",
                             'debugbox'      => false,
                             'debugnoclip'   => false,
                             'draw_page_border' =>      false,
                             'encoding'      => strtolower($cCharset),
                             'html2xhtml'    => true,
                             'imagequality_workaround' => false,
                             'landscape'     => $landscape,
                             'margins'       => array(
                                                      'left'    => 0,
                                                      'right'   => 0,
                                                      'top'     => 0,
                                                      'bottom'  => 0,
                                                      ),
                             'media'         => "A4",
                             'method'        => "fpdf",
                             'mode'          => 'html',
                             'output'        => $out,
                             'pagewidth'     => $pagewidth,
                             'pdfversion'    => "1.2",
                             'ps2pdf'        => false,
                             'pslevel'       => 3,
                             'renderfields'  => true,
                             'renderforms'   => false,
                             'renderimages'  => true,
                             'renderlinks'   => false,
                             'scalepoints'   => true,
                             'smartpagebreak' => true,
                             'transparency_workaround' => false
                             );


// ========== Entry point
parse_config_file(dirname(__FILE__).'/html2ps/html2ps.config');

// begin processing

$g_media = Media::predefined($GLOBALS['g_config']['media']);
$g_media->set_landscape($GLOBALS['g_config']['landscape']);
$g_media->set_margins($GLOBALS['g_config']['margins']);
$g_media->set_pixels($GLOBALS['g_config']['pagewidth']);

// Initialize the coversion pipeline
$pipeline = new Pipeline();
$pipeline->configure($GLOBALS['g_config']);

// Configure the fetchers

include("fetcher.php");
$pipeline->fetchers[] = new FetcherCustom($page);

// Configure the data filters
$pipeline->data_filters[] = new DataFilterDoctype();
$pipeline->data_filters[] = new DataFilterUTF8($GLOBALS['g_config']['encoding']);
if ($GLOBALS['g_config']['html2xhtml']) {
  $pipeline->data_filters[] = new DataFilterHTML2XHTML();
} else {
  $pipeline->data_filters[] = new DataFilterXHTML2XHTML();
};

$pipeline->parser = new ParserXHTML();

// "PRE" tree filters

$pipeline->pre_tree_filters = array();

$header_html    = "";
$footer_html    = "";
$filter = new PreTreeFilterHeaderFooter($header_html, $footer_html);
$pipeline->pre_tree_filters[] = $filter;

if ($GLOBALS['g_config']['renderfields']) {
  $pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
};

// 

$pipeline->layout_engine = new LayoutEngineDefault();

$pipeline->post_tree_filters = array();

// Configure the output format
if ($GLOBALS['g_config']['pslevel'] == 3) {
  $image_encoder = new PSL3ImageEncoderStream();
} else {
  $image_encoder = new PSL2ImageEncoderStream();
};

switch ($GLOBALS['g_config']['method']) {
 case 'fastps':
   if ($GLOBALS['g_config']['pslevel'] == 3) {
     $pipeline->output_driver = new OutputDriverFastPS($image_encoder);
   } else {
     $pipeline->output_driver = new OutputDriverFastPSLevel2($image_encoder);
   };
   break;
 case 'pdflib':
   $pipeline->output_driver = new OutputDriverPDFLIB16($GLOBALS['g_config']['pdfversion']);
   break;
 case 'fpdf':
   $pipeline->output_driver = new OutputDriverFPDF();
   break;
 case 'png':
   $pipeline->output_driver = new OutputDriverPNG();
   break;
 case 'pcl':
   $pipeline->output_driver = new OutputDriverPCL();
   break;
 default:
   die("Unknown output method");
};

// Setup watermark
$watermark_text = "";
if ($watermark_text != '') {
  $pipeline->add_feature('watermark', array('text' => $watermark_text));
};

if ($GLOBALS['g_config']['debugbox']) {
  $pipeline->output_driver->set_debug_boxes(true);
}

if ($GLOBALS['g_config']['draw_page_border']) {
  $pipeline->output_driver->set_show_page_border(true);
}

if ($GLOBALS['g_config']['ps2pdf']) {
  $pipeline->output_filters[] = new OutputFilterPS2PDF($GLOBALS['g_config']['pdfversion']);
}

if ($GLOBALS['g_config']['compress'] && $GLOBALS['g_config']['method'] == 'fastps') {
  $pipeline->output_filters[] = new OutputFilterGZip();
}

$outfilename = GetTableURL();

switch ($GLOBALS['g_config']['output']) {
 case 0:
   $pipeline->destination = new DestinationBrowser($outfilename);
   break;
 case 1:
   $pipeline->destination = new DestinationDownload($outfilename);
   break;
 case 2:
   $tarr=explode(" ", microtime());
   $usec=$tarr[0];
   $sec=$tarr[1];
   $outfileid=$sec.round($usec*10);
   $outfilename.=$outfileid;
   $pipeline->destination = new DestinationFile($outfilename);
   break;
};


// Start the conversion

?>

<script>
window.onload= function()
{
if(!document.getElementById('done'))
{
	var pdiv = window.parent.document.getElementById("progress");
	window.parent.pdfbuilt=1;
	pdiv.innerHTML='<p class=error>'+document.body.innerHTML+'</p><br>';
}
};	
</script>

<?php
$time = time();

$_SESSION["tagcount"]=0;
$_SESSION["tagprocess"]=0;
$tagprocess=0;
$progressfilename = tempnam(WRITER_TEMPDIR,"pdf_");
$_SESSION["pdf_filename"] = $progressfilename;
session_write_close();

$status = $pipeline->process("", $g_media);

if ($status == null) {
  print($pipeline->error_message());
  error_log("Error in conversion pipeline");
  die();
}
if($GLOBALS['g_config']['output']!=2)
	exit();

//	delete old files
$pdfs=array();
if ($dh = opendir(OUTPUT_FILE_DIRECTORY)) 
{
	while (($file = readdir($dh)) !== false) 
		if(strtolower(substr($file,strlen($file)-4))==".pdf")
			$pdfs[]=$file;
	closedir($dh);
}

foreach($pdfs as $file)
{
	$ftime=substr($file,strlen($file)-15,10);
	{
	if(strlen($ftime)==10 && $ftime>0 && $sec-$ftime>3600*12)
		unlink(OUTPUT_FILE_DIRECTORY."/".$file);
	}
}
unlink($progressfilename);
?>
<script>
var pdiv = window.parent.document.getElementById("progress");
window.parent.pdfbuilt=1;
pdiv.innerHTML='<p><?php echo jsreplace("PDF file created. Download the file using this");?> <a href="getpdf.php?table=<?php echo GetTableURL();?>&id=<?php echo $outfileid;?>"><?php echo jsreplace("link");?></a></p><br>';
</script>
<div id="done"></div>
