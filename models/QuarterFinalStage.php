<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 13:58
 */

namespace Models;


class QuarterFinalStage extends StagePrototype
{
    protected function beforeSave()
    {
        if ($this->isInsert()){
            $this->type = self::STAGE_QUARTER_FINAL;
        }
    }

    protected function getPlace(): string
    {
        return '1/4';
    }


    protected function afterSave($insert)
    {
        parent::afterSave($insert);

        if ($insert){
            for($i=0; $i<2; $i++) {
                (new OneEighthFinalStage([
                    'parent_id' => $this->id,
                    'tournament_id' => $this->tournament_id
                ]))->save();
            }
        }
    }

}