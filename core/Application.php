<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.09.2019
 * Time: 11:28
 */

namespace Core;


use Controllers\AdminController;
use Controllers\ContentController;

class Application
{
    const ADMIN_MODE = 'admin';
    const CONTENT_MODE = 'content';

    private $mode;
    private $oController;
    private $sAction;

    public function __construct($mode = self::CONTENT_MODE)
    {

        $dbConfig = include_once 'config/db.php';

        BaseModel::$db = new \PDO( $dbConfig['dsn'],$dbConfig['user'],$dbConfig['password']);

        if ($mode == self::CONTENT_MODE){
            $this->oController = new ContentController();
            $this->sAction = 'actionIndex';
        }else{
            $this->oController = new AdminController();
            $this->sAction = 'actionIndex';
        }

        $this->mode = $mode;
    }

    public function run(){
        foreach ($this->oController->{$this->sAction}() as $key => $value){
            ${$key} = $value;
        }

        include_once 'views/'.$this->mode.'.php';
    }
}