<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 12:45
 */

namespace Models;


use Core\BaseModel;

/**
 * Class Tournament
 * @package Models
 * @property $name
 * @property $begin_date
 * @property $first_place
 * @property $second_place
 * @property $third_place
 */
class Tournament extends BaseModel
{
    static function tableName(): string
    {
        return 'tournament';
    }

    function fields(): array
    {
        return [
            static::primaryField(),
            'name',
            'begin_date',
            'first_place',
            'second_place',
            'third_place',
        ];
    }

    protected function afterSave($insert)
    {
        $oFinal = new FinalStage();
        $oFinal->tournament_id= $this->id;
        $oFinal->save();

        $oThirdPlace = new ThirdPlaceStage();
        $oThirdPlace->tournament_id= $this->id;
        $oThirdPlace->save();
    }


}