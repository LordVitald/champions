<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 16:16
 */

namespace Models;


use Core\BaseModel;
use PDO;

/**
 * Class ReportRow
 * @package Models
 * @property $id
 * @property $team_id
 * @property $tournament_id
 * @property $place
 * @property $matches
 * @property $points
 * @property $average
 * @property $best
 */
class ReportRow extends BaseModel
{
    function fields(): array
    {
        return [
            static::primaryField(),
            'team_id',
            'tournament_id',
            'place',
            'matches',
            'points',
            'average',
            'best',
        ];
    }

    static function tableName(): string
    {
        return 'report';
    }

    private static function getReportRow4Team($oTeam, $oTournament){
        $sSQL = sprintf("SELECT * FROM `report` WHERE `tournament_id`=:tournament_id AND `team_id`=:team_id");

        $oQuery = self::$db->prepare($sSQL);
        $oQuery->bindValue(':tournament_id',$oTournament->id);
        $oQuery->bindValue(':team_id',$oTeam->id);
        $oQuery->execute();

        if ($params = $oQuery->fetch(PDO::FETCH_ASSOC)){
            return static::build($params);
        }else{
            return new static(
                [
                    'tournament_id' => $oTournament->id,
                    'team_id' => $oTeam->id,
                    'points' => 0,
                    'matches' => 0,
                    'average' => 0,
                    'best' => 0
                ]
            );
        }
    }

    public static function addReportRow2Team($oTeam, $oTournament, $points, $place = ''){

        $oReportRow = self::getReportRow4Team($oTeam, $oTournament);

        $oReportRow->points = $oReportRow->points + $points;
        $oReportRow->matches++;
        $oReportRow->average = $oReportRow->points / $oReportRow->matches;

        if (!empty($place)){
            $oReportRow->place = $place;
        }

        $oReportRow->best = ( $oReportRow->best < $points )? $points : $oReportRow->best;

        $oReportRow->save();
    }

}