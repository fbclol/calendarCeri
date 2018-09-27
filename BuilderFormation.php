<?php

/**
 * Created by PhpStorm.
 * User: franck
 * Date: 19/09/2017
 * Time: 20:16
 */

require_once './IBuilder.php';
require_once './TraitementFile.php';
class BuilderFormation extends TraitementFile implements IBuilder
{
    protected $nameFile = "listFormations";

    function __construct()
    {
        $this->path = $this->nameFile.".json";
        $this->aFormations = self::getContentFile();
    }

    public function toHTMLOption() {
        $html = '<option value=""></option>'."\n";
        $aListOptgroup = array_unique(array_column($this->getFormations(), 'optgroup'));

        foreach( $aListOptgroup  as $key => $sDiplome) {
            $html .= "<optgroup label=".$sDiplome.">";

            foreach( $this->aFormations  as $key1 => $sFormation) {
                if ($sDiplome === $sFormation['optgroup']) {
                    $html .= "<option value='".$sFormation['name']."'>".$sFormation['optdescription']."</option>";
                }
            }
            $html .= "</optgroup>";
        }
        return $html;
    }


}