<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 27/09/2018
 * Time: 02:45
 */

class CollectionTypesCours
{
    private $CollectionTypesCours = [];

    public function __construct()
    {
        $this->CollectionTypesCours = [
            new TypeCour('tp'),
            new TypeCour('td'),
            new TypeCour('anglais'),
            new TypeCour('cm'),
            new TypeCour('Evaluation'),
        ];
    }

    /**
     * @return array
     */
    public function getCollectionTypesCours(): array
    {
        return $this->CollectionTypesCours;
    }

}