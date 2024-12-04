<?php
namespace App\Kernel\Router;

use App\Kernel\Filesystem\Folder;
use App\Kernel\Router\Enum\RouteObject;
use App\Kernel\Router\EventHttpRequest;
use App\Kernel\Router\RouteList;

/**
 * @package PageRouteList
 */
final class PageRouteList extends RouteList
{
  public function __construct()
  {
    parent::__construct(Folder::getConfigPath().'/pages.yaml');
  }

  /**
   * Return route object by url
   *
   * @return array
   */
  public function findByURL(): array
  {
    return $this->find(RouteObject::url, EventHttpRequest::getUri());
  }

  /**
   * Return route object by component
   *
   * @param string $component
   * @return array
   */
  public function findByComponent(string $component = ''): array
  {
    return $this->find(RouteObject::component, $component);
  }
}