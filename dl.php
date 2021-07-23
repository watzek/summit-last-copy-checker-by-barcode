<?php
$f=$_GET["file"];

$file = "files/$f";

// Quick check to verify that the file exists
if (!file_exists($file)) die("File not found");

// Force the download
header('Content-Disposition: attachment; filename="'.$f.'' );
header("Content-Length: " . filesize($file));
header("Content-Type: application/octet-stream;");
readfile($file);





 ?>
