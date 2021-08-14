<?php
//Use scss in php
require_once "scssphp/scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;
use ScssPhp\Server\Server;

$scss = new Compiler();
$scss->setImportPaths('assets/sass');
$scss->setOutputStyle(OutputStyle::EXPANDED);

$myfile = fopen("assets/css/style.css", "w");
fwrite($myfile, $scss->compile('@import "main.scss";'));
fclose($myfile);
?>
