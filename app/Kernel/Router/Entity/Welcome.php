<?php
namespace App\Kernel\Router\Entity;

use App\Kernel\Router\Entity\Entity;

/**
 * @package Welcome
 */
class Welcome extends Entity
{
  public function __construct()
  {
    parent::__construct(Welcome::class);
  }
}