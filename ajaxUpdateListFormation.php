<?php

require_once './BuilderFormation.php';
$oBuilderFormation = new BuilderFormation();
$oBuilderFormation->setContentFile(urldecode($_POST["data_json"]));