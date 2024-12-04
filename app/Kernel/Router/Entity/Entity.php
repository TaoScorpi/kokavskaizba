<?php
namespace App\Kernel\Router\Entity;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Router\Enum\Method;
use App\Kernel\Router\Enum\RouteObject;
use App\Kernel\Router\Enum\Components;
use App\Kernel\Router\Exception\CannotReadConfFileExeception;
use App\Kernel\Router\Exception\CannotFindFileSpecifiedExeception;
use App\Kernel\Router\Exception\MissingParameterExeception;

/**
 * @package Entity
 */
abstract class Entity
{
  public $method;
  public $url;
  public $component;
  public $template;
  public $data;

  /**
   *
   * @param string $class
   */
  public function __construct(string $class = '')
  {
    // @validate
    if(!is_file($this->filepath()))
      throw new CannotReadConfFileExeception('Cannot read routes file due to insufficient permissions');

    // @conf
		$conf = Yaml::parseFile($this->filepath());

    // @validate
    if(!isset($conf[$class]))
      throw new CannotFindFileSpecifiedExeception('Entity '.$class.' not found in entity configuration file');

    // @route
    $route = $conf[$class];

    // @set
    $this->setMethod($route);
    $this->setURL($route);
    $this->setComponent($route);
    $this->setTemplate($route);
    $this->setData($route);
  }

  /**
   * Return config file path
   *
   * @return string
   */
  private function filepath(): string
  {
    return Folder::getRouterConfPath().'/entity.yaml';
  }

  /**
   * Http Request Method
   *
   * @param array $route
   * @return void
   */
  private function setMethod(array $route = []): void
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
   * Undocumented function
   *
   * @param array $route
   * @return void
   */
  private function setURL(array $route = []): void
  {
    if(!isset($route[RouteObject::url]))
      throw new MissingParameterExeception('Missing parameter url in routes configuration file');

    $this->url = $route[RouteObject::url];
  }

  /**
   * Undocumented function
   *
   * @param array $route
   * @return void
   */
  private function setComponent(array $route = []): void
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
   * Undocumented function
   *
   * @param array $route
   * @return void
   */
  private function setTemplate(array $route = []): void
  {
    if(!isset($route[RouteObject::template]))
      throw new MissingParameterExeception('Missing parameter template in routes configuration file');

    $this->template = '@app/'.$route[RouteObject::template];
  }

  /**
   * Undocumented function
   *
   * @param array $route
   * @return void
   */
  private function setData(array $route = []): void
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