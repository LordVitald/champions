<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 14:00
 */

namespace Models;


class SemifinalStage extends StagePrototype
{
    protected function beforeSave()
    {
        if ($this->isInsert()){
            $this->type = self::STAGE_SEMIFINAL;
        }
    }

    protected function afterSave($insert)
    {
        parent::afterSave($insert);

        if ($insert){
            for($i=0; $i<2; $i++) {
                (new QuarterFinalStage([
                    'parent_id' => $this->id,
                    'tournament_id' => $this->tournament_id
                ]))->save();
            }
        }
    }

    public function applyResults($firstTeamScore, $secondTeamScore)
    {
        parent::applyResults($firstTeamScore, $secondTeamScore);

        $oLoser = ( $firstTeamScore < $secondTeamScore )? $this->firstTeam : $this->secondTeam;
        $oThirdStage = ThirdPlaceStage::findThirdPlaceStage4Tournament($this->tournament_id);
        if ($this->id % 2) {
            $oThirdStage->s_team = $oLoser->id;
        }else{
            $oThirdStage->f_team = $oLoser->id;
        }
        $oThirdStage->save();

//        ReportRow::addReportRow2Team($this->firstTeam, $this->tournament,$firstTeamScore);
//        ReportRow::addReportRow2Team($this->secondTeam, $this->tournament,$secondTeamScore);

    }


}