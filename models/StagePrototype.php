<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 13:19
 */

namespace Models;


use Core\BaseModel;

/**
 * Class StagePrototype
 * @package Models
 * @property $id
 * @property $tournament_id
 * @property $parent_id
 * @property $f_team
 * @property $s_team
 * @property $f_score
 * @property $s_score
 * @property Tournament $tournament
 * @property StagePrototype $nextStage
 * @property FootballTeam $firstTeam
 * @property FootballTeam $secondTeam
 */
abstract class StagePrototype extends BaseModel
{
    const STAGE_FINAL = 'final';
    const STAGE_THIRD_PLACE = 'third_place';
    const STAGE_SEMIFINAL = 'semifinal';
    const STAGE_QUARTER_FINAL = 'quarter_final';
    const STAGE_ONE_EIGHTH_FINAL = 'one_eighth_final';

    /** @var Tournament */
    protected $oTournament;

    /** @var FootballTeam */
    protected $oFirstTeam;

    /** @var FootballTeam */
    protected $oSecondTeam;

    /** @var StagePrototype */
    protected $oNextStage;

    function fields(): array
    {
        return [
            static::primaryField(),
            'type',
            'tournament_id',
            'parent_id',
            'f_team',
            's_team',
            'f_score',
            's_score'
        ];
    }

    static function tableName(): string
    {
        return 'stage';
    }

    protected function getPlace():string
    {
        return '';
    }

    protected static function build($params)
    {
        switch ($params['type']){
            case self::STAGE_FINAL:
                return new FinalStage($params);
            case self::STAGE_THIRD_PLACE:
                return new ThirdPlaceStage($params);
            case self::STAGE_SEMIFINAL:
                return new SemifinalStage($params);
            case self::STAGE_QUARTER_FINAL:
                return new QuarterFinalStage($params);
            case self::STAGE_ONE_EIGHTH_FINAL:
                return new OneEighthFinalStage($params);
        }

        return null;
    }

    public function getTournament()
    {
        if (is_null($this->oTournament)){
            $this->oTournament = Tournament::find($this->tournament_id);
        }

        return $this->oTournament;
    }

    public function getFirstTeam(){

        if (is_null($this->oFirstTeam) && ($this->f_team)){
            $this->oFirstTeam = FootballTeam::find($this->f_team);
        }

        return $this->oFirstTeam;
    }

    public function getSecondTeam(){

        if (is_null($this->oSecondTeam) && ($this->s_team)){
            $this->oSecondTeam = FootballTeam::find($this->s_team);
        }

        return $this->oSecondTeam;
    }

    protected function getNextStage(){
        if (is_null($this->oNextStage) && $this->parent_id){
            $this->oNextStage = StagePrototype::find($this->parent_id);
        }


        return $this->oNextStage;
    }

    protected function addWinner($oTeam, $oStage){
        if ($this->id % 2){
            if ($oStage->id % 2){
                $this->s_team = $oTeam->id;
            }else{
                $this->f_team = $oTeam->id;
            }
        }else{
            if ($oStage->id % 2){
                $this->f_team = $oTeam->id;
            }else{
                $this->s_team = $oTeam->id;
            }
        }
    }

    public function applyResults($firstTeamScore, $secondTeamScore){
        if ( $this->firstTeam && $this->secondTeam){
            $this->f_score = $firstTeamScore;
            $this->s_score = $secondTeamScore;
            $this->save();

            if ($firstTeamScore > $secondTeamScore){
                $oWinner = $this->firstTeam;
                $oWinnerScore = $firstTeamScore;
                $oLoser = $this->secondTeam;
                $oLoserScore = $secondTeamScore;
            }else{
                $oWinner = $this->secondTeam;
                $oWinnerScore = $secondTeamScore;
                $oLoser = $this->firstTeam;
                $oLoserScore = $firstTeamScore;
            }

            $this->nextStage->addWinner($oWinner, $this);
            $this->nextStage->save();

            ReportRow::addReportRow2Team($oWinner, $this->tournament,$oWinnerScore);
            ReportRow::addReportRow2Team($oLoser, $this->tournament,$oLoserScore, $this->getPlace());
        }
    }
}