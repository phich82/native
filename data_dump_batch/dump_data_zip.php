<?php

$username = "root";
$password = "";
$hostname = "localhost";
$dbname   = "native";

// if mysqldump is on the system path you do not need to specify the full path
// simply use "mysqldump --add-drop-table ..." in this case
$dumpfname = $dbname . "_" . date("Y-m-d_H-i-s").".sql";
$command = "C:\\wamp\\bin\\mysql\\mysql5.7.23\\bin\\mysqldump --add-drop-table --host=$hostname --user=$username ";
if ($password) {
    $command.= "--password=". $password ." ";
}

$command.= $dbname;
$command.= " > " . $dumpfname;
system($command);

// zip the dump file
$zipfname = $dbname . "_" . date("Y-m-d_H-i-s").".zip";
$zip = new ZipArchive();
if ($zip->open($zipfname, ZIPARCHIVE::CREATE)) {
   $zip->addFile($dumpfname, $dumpfname);
   $zip->close();
}

// read zip file and send it to standard output
if (file_exists($zipfname)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($zipfname));
    flush();
    readfile($zipfname);
    exit;
}
