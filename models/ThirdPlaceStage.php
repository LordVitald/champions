<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 14:01
 */

namespace Models;


use PDO;

class ThirdPlaceStage extends StagePrototype
{
    protected function beforeSave()
    {
        if ($this->isInsert()){
            $this->type = self::STAGE_THIRD_PLACE;
        }
    }

    public function applyResults($firstTeamScore, $secondTeamScore)
    {

        if ( $this->firstTeam && $this->secondTeam){
            $this->f_score = $firstTeamScore;
            $this->s_score = $secondTeamScore;
            $this->save();

            if ( $firstTeamScore > $secondTeamScore){
                $this->tournament->third_place = $this->firstTeam->id;
                ReportRow::addReportRow2Team(
                    $this->firstTeam,
                    $this->tournament,
                    $firstTeamScore,
                    'third'
                );
                ReportRow::addReportRow2Team(
                    $this->secondTeam,
                    $this->tournament,
                    $secondTeamScore,
                    'forth'
                );
            }else {
                $this->tournament->third_place = $this->secondTeam->id;
                ReportRow::addReportRow2Team(
                    $this->secondTeam,
                    $this->tournament,
                    $secondTeamScore,
                    'third'
                );
                ReportRow::addReportRow2Team(
                    $this->firstTeam,
                    $this->tournament,
                    $firstTeamScore,
                    'forth'
                );
            }

            $this->tournament->save();
        }

    }

    public static function findThirdPlaceStage4Tournament($idTournament){
        $sSQL = sprintf("SELECT * FROM %s WHERE `tournament_id`=:id AND `type`='%s'",static::tableName(), self::STAGE_THIRD_PLACE );

        $oQuery = self::$db->prepare($sSQL);
        $oQuery->bindValue(':id',$idTournament);
        $oQuery->execute();

        if ($params = $oQuery->fetch(PDO::FETCH_ASSOC)){
            return static::build($params);
        }else{
            return null;
        }
    }
}