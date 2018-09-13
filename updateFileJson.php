<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 12/09/2018
 * Time: 21:56
 */

require_once './BuilderFormation.php';

BuilderFormation::setContentFile(urldecode($_POST["data_json"]));