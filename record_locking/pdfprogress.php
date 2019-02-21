<?php
include("include/dbcommon.php");
$filename=@$_SESSION["pdf_filename"];
if(!$filename || !myfile_exists($filename))
	return;
echo @file_get_contents($filename);
?>