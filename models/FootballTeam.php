<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.09.2019
 * Time: 17:45
 */

namespace Models;


use Core\BaseModel;

/**
 * Class FootballTeam
 * @package Models
 * @property $name
 */
class FootballTeam extends BaseModel
{
    public $myParam = 'sfdsd';

    static function  tableName(): string
    {
        return 'football_team';
    }


    function fields(): array
    {
        return [
            static::primaryField(),
            'name'
        ];
    }

}