<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 13:57
 */

namespace Models;


class OneEighthFinalStage extends StagePrototype
{
    protected function getPlace(): string
    {
        return '1/8';
    }


    protected function beforeSave()
    {
        if ($this->isInsert()){
            $this->type = self::STAGE_ONE_EIGHTH_FINAL;
        }
    }

}