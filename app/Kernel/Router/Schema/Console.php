<?php
namespace App\Kernel\Router\Schema;

use App\Kernel\Router\Schema\Schema;
use App\Kernel\Router\Enum\RouteObject;

/**
 * @package ConsoleSchema
 */
final class Console extends Schema
{
  /**
   * Initialize
   *
   * @param array $route
   */
  public function __construct(array $route = [])
  {
    parent::__construct($route);
  }
  
  /**
   * Template
   *
   * @param array $route
   * @return void
   */
  public function setTemplate(array $route = []): void
  {
    if(isset($route[RouteObject::template])) {
      $this->template = '@app/'.$route[RouteObject::template];
    } else {
      $this->template = '';
    }
  }
}