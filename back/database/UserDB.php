<?php
require_once __DIR__ . '/../database/Model.php';

class UserDB extends Model
{
  private static $table = 'user';
  private static $idKey = 'userID';
  protected static $uniqueKeyValues = ['userID', 'usuari', 'email'];

  public static function trobarPer(string $uniqueKey, $id)
  {
    return parent::findById(self::$table, $uniqueKey, $id);
  }
}
