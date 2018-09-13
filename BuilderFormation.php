<?php

/**
 * Created by PhpStorm.
 * User: franck
 * Date: 19/09/2017
 * Time: 20:16
 */
class BuilderFormation
{
    public $aFormations =[];

    function __construct($nameFile)
    {
        $this->aFormations = json_decode(file_get_contents($nameFile), true);
    }


    public static function setContentFile($json_date) {
        $handle = fopen("./listFormations.json", "w+");
        fwrite($handle, $json_date);
        fclose($handle);
    }


    public function toHTMLOption() {
        $html = '<option value=""></option>'."\n";
        $aListOptgroup = array_unique(array_column($this->aFormations, 'optgroup'));

        foreach( $aListOptgroup  as $key => $sDiplome) {
            $html .= "<optgroup label=".$sDiplome.">";

            foreach( $this->aFormations  as $key1 => $sformation) {
                if ($sDiplome === $sformation['optgroup']) {
                    $html .= "<option value='".$sformation['name']."'>".$sformation['optdescription']."</option>";
                }
            }
            $html .= "</optgroup>";
        }
        return $html;
    }
}