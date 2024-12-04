<?php
namespace App\Kernel\Bundles\User;

use App\Models\UserModel;

/**
 * @package User
 */
final class User
{
  /**
   * @param string $id
   * @return object
   */
  public static function getById(string $id = ''): object
  {
    return UserModel::where('id', $id)->get();
  }

  public function create(array $data = []): bool
  {
    return false;
  }
}