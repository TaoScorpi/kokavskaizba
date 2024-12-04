<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @package UserModel
 */
class UserModel extends Model
{
  protected $connection = 'mongodb';
  protected $collection = 'users';
  protected $fillable = [
    'status',
    'titleBefore',
    'titleAfter',
    'firstname',
    'lastname',
    'fullname',
    'email',
    'phone',
    'avatar',
    'picture',
    'background',
    'password',
    'resetExpire',
    'updatedAt',
    'createdAt'
  ];
}