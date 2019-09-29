<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 13:51
 */

namespace Models;


class FinalStage extends StagePrototype
{
    protected function beforeSave()
    {
        if ($this->isInsert()){
            $this->type = self::STAGE_FINAL;
        }
    }

    protected function afterSave($insert)
    {
        parent::afterSave($insert);

        if ($insert){
            for($i=0; $i<2; $i++) {
                (new SemifinalStage([
                    'parent_id' => $this->id,
                    'tournament_id' => $this->tournament_id
                ]))->save();
            }
        }
    }

    public function applyResults($firstTeamScore, $secondTeamScore)
    {

        if ( $this->firstTeam && $this->secondTeam){
            $this->f_score = $firstTeamScore;
            $this->s_score = $secondTeamScore;
            $this->save();

            if ( $firstTeamScore > $secondTeamScore){
                $this->tournament->first_place = $this->firstTeam->id;
                ReportRow::addReportRow2Team(
                    $this->firstTeam,
                    $this->tournament,
                    $firstTeamScore,
                    'first'
                );
                $this->tournament->second_place = $this->secondTeam->id;
                ReportRow::addReportRow2Team(
                    $this->secondTeam,
                    $this->tournament,
                    $secondTeamScore,
                    'second'
                );
            }else {
                $this->tournament->second_place = $this->firstTeam->id;
                ReportRow::addReportRow2Team(
                    $this->firstTeam,
                    $this->tournament,
                    $firstTeamScore,
                    'second'
                );
                $this->tournament->first_place = $this->secondTeam->id;
                ReportRow::addReportRow2Team(
                    $this->secondTeam,
                    $this->tournament,
                    $secondTeamScore,
                    'first'
                );
            }

            $this->tournament->save();
        }




    }


}