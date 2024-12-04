<?php
namespace App\Kernel\Filesystem;

use App\Kernel\Filesystem\Enum\Directory;

/**
 * @package Folder
 */
final class Folder
{
  /**
   * Root path
   *
   * @return string
   */
  public static function getRootPath(): string
  {
    return realpath(dirname(__DIR__).'/../../');
  }

  /**
   * Config path
   *
   * @return string
   */
  public static function getConfigPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::CONF);
  }

  /**
   * Public path
   *
   * @return string
   */
  public static function getPublicPath(): string
  {
    return realpath(self::getRootPath().'/public');
  }

  /**
   * Config path
   *
   * @return string
   */
  public static function getStaticPath(): string
  {
    return realpath(self::getPublicPath().'/static');
  }

  /**
   * Config path
   *
   * @return string
   */
  public static function getAssetsPath(): string
  {
    return realpath(self::getPublicPath().'/'.Directory::ASSETS);
  }

  /**
   * Kernel path
   *
   * @return string
   */
  public static function getKernelPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::KERNEL);
  }

  /**
   * Router path
   *
   * @return string
   */
  public static function getRouterPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::ROUTER);
  }

  /**
   * Router config path
   *
   * @return string
   */
  public static function getRouterConfPath(): string
  {
    return realpath(self::getRouterPath().'/'.Directory::CONF);
  }

  /**
   * Components path
   *
   * @return string
   */
  public static function getComponentsPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::COMPONENTS);
  }

  /**
   * Components config path
   *
   * @return string
   */
  public static function getComponentsConfigPath(): string
  {
    return realpath(self::getComponentsPath().'/'.Directory::CONF);
  }
}