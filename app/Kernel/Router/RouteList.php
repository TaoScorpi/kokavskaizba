<?php
namespace App\Kernel\Router;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Router\Exception\CannotReadConfFileExeception;

/**
 * @package RouteList
 */
abstract class RouteList
{
  public $conf;

  /**
   * Set filepath for configuration file
   *
   * @param string $filepath
   */
  public function __construct(string $filepath = '')
  {
    // @validate
    if(!is_file($filepath))
      throw new CannotReadConfFileExeception('Cannot read configuration file due to insufficient permissions');

    // @conf
		$this->conf = Yaml::parseFile($filepath);
  }

  abstract public function findByURL(): array;
  abstract public function findByComponent(string $component = ''): array;

  /**
   * Find key in configuration file
   *
   * @param string $key
   * @param string $value
   * @return mixed
   */
  public function find(string $key = '', string $value = ''): mixed
  {
    // @not-exists
    if(!is_array($this->conf) || 0 === count($this->conf))
      return [];

    // @index
    $index = array_search($value, array_column(
      $this->conf, $key
    ));

    // @not-found
    if(false === $index)
      return [];

    // @return
    return $this->conf[$index];
  }
}