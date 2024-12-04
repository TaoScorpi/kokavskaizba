<?php
namespace App\Kernel\Router\Schema;

use App\Kernel\Router\Enum\Method;
use App\Kernel\Router\Enum\RouteObject;
use App\Kernel\Router\Enum\Components;
use App\Kernel\Router\Exception\MissingParameterExeception;

/**
 * @package Schema
 */
abstract class Schema
{
  public $method;
  public $url;
  public $component;
  public $template;
  public $data;

  /**
   * Initialize
   *
   * @param array $route
   */
  public function __construct(array $route = [])
  {
    $this->setMethod($route);
    $this->setURL($route);
    $this->setComponent($route);
    $this->setTemplate($route);
    $this->setData($route);
  }
  
  /**
   * Http Request Method
   *
   * @param array $route
   * @return void
   */
  public function setMethod(array $route = []): void
  {
    if(isset($route[RouteObject::method]) &&
      in_array($route[RouteObject::method],[Method::GET,Method::POST])
    ) {
      $this->method = $route[RouteObject::method];
    } else  {
      $this->method = Method::GET;
    }
  }

  /**
   * URL
   *
   * @param array $route
   * @return void
   */
  public function setURL(array $route = []): void
  {
    if(!isset($route[RouteObject::url]))
      throw new MissingParameterExeception('Missing parameter url in routes configuration file');

    $this->url = $route[RouteObject::url];
  }

  /**
   * Component
   *
   * @param array $route
   * @return void
   */
  public function setComponent(array $route = []): void
  {
    if(isset($route[RouteObject::component]) &&
      0 < strlen($route[RouteObject::component])
    ) {
      $this->component = $route[RouteObject::component];
    } else {
      $this->component = Components::base;
    }
  }

  /**
   * Template
   *
   * @param array $route
   * @return void
   */
  abstract public function setTemplate(array $route = []): void;

  /**
   * Data
   *
   * @param array $route
   * @return void
   */
  public function setData(array $route = []): void
  {
    if(isset($route[RouteObject::data]) &&
      is_array($route[RouteObject::data]) &&
      0 < count($route[RouteObject::data])
    ) {
      $this->data = $route[RouteObject::data];
    } else {
      $this->data = [];
    }
  }
}