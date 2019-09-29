<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.09.2019
 * Time: 16:39
 */

namespace Core;


abstract class BaseModel
{

    /** @var \PDO */
    static $db = null;

    protected $values = [];

    abstract function fields(): array;

    abstract static function tableName():string;

    public function __construct($params = [])
    {
        foreach ($this->fields() as $field){
            $this->$field = $params[$field];
        }
    }

    public function __get($name)
    {

        $sGetFunction = 'get'.ucfirst($name);
        if (method_exists($this,$sGetFunction)){
            return $this->$sGetFunction();
        }

        if (in_array($name, $this->fields())){
            return $this->values[$name];
        }

        throw new \Exception(sprintf('Получение неизвестного свойства %s::%s', static::class, $name));

    }

    public function __set($name, $value)
    {
        $sSetFunction = 'set'.ucfirst($name);

        if (method_exists($this, $sSetFunction)){
            return $this->$sSetFunction($value);
        }

        if (in_array($name, $this->fields())){
            return $this->values[$name] = $value;
        }

        throw new \Exception(sprintf('Запись неизвестного свойства %s::%s', static::class, $name));
    }

    static function primaryField():string
    {
        return 'id';
    }

    protected final function isInsert():bool{
        return !$this->{static::primaryField()};
    }

    protected function beforeSave(){

    }

    public final function save():bool
    {

        $this->beforeSave();

        if ( ( $bInsert = $this->isInsert() )? $this->create(): $this->update() ){
            $this->afterSave($bInsert);
            return true;
        }

        return false;
    }

    protected function afterSave($insert){

    }

    protected function create(){

        $aFields = array_filter(
            $this->fields(),
            function ($val) {return $val != static::primaryField();}
        );

        $sFields = implode(',', $aFields);

        $sValues = implode(
            ',',
            array_map(
                function($val) {return ':'.$val;},
                $aFields
            )
        );

        $sSQL = sprintf('INSERT INTO %s (%s) VALUES (%s)', static::tableName(),$sFields, $sValues);

        $oQuery = self::$db->prepare($sSQL);

        foreach ($this->values as $key => $value){
            if ($key == static::primaryField()) continue;

            $oQuery->bindValue(':'.$key, $value);
        }

        if ($oQuery->execute()){
            $this->{static::primaryField()} = BaseModel::$db->lastInsertId();
            return true;
        }else{
            var_dump($oQuery->errorInfo());
            return false;
        }
    }

    protected function update(){

        $aValues = [];

        foreach ($this->fields() as $field){

            if ($field == static::primaryField()) continue;

            $aValues[] = sprintf( '%s=:%s', $field,$field);
        }

        $sValues = implode(',', $aValues);

        $sSQL = sprintf('UPDATE  %s SET %s WHERE %s=%s', static::tableName(), $sValues, static::primaryField(), $this->{static::primaryField()});

        $oQuery = self::$db->prepare($sSQL);

        foreach ($this->values as $key => $value){
            if ($key == static::primaryField()) continue;

            $oQuery->bindValue(':'.$key, $value);
        }

        if ($oQuery->execute()){
            return true;
        }else{
            var_dump($oQuery->errorInfo());
            return false;
        }
    }

    protected static function build($params){
        return new static($params);
    }

    public static final function find($id){
        $sSQL = sprintf('SELECT * FROM %s WHERE %s=:id',static::tableName(), static::primaryField() );

        $oQuery = BaseModel::$db->prepare($sSQL);
        $oQuery->bindValue(':id', $id);
        $oQuery->execute();

        if ($params = $oQuery->fetch(\PDO::FETCH_ASSOC)){
            return static::build($params);
        }else{
            return null;
        }

    }
}