<?php
namespace App\Kernel\Bundles\User;

use App\Models\UserModel;

/**
 * @package ListOfUsers
 */
class ListOfUsers
{
  const LIMIT = 5;

  /**
   * @param int $limit
   * @return array
   */
  public static function getWithLimit(int $limit = self::LIMIT): array
  {
    return UserModel::take($limit)->get()->toArray();
  }
}