<?php
namespace App\Kernel\Logger;

use Carbon\Carbon;
use App\Kernel\Logger\Exception\RequestLogExeception;

final class RequestLog
{   
  const DIRECTORY = '/../logs/';
  const FILENAME = 'log_requests';
  const FILETYPE = '.txt';
  const FORMAT = 'Y-m-d H:i:s';
  const GLUE = ' | ';
  
  protected $uri;
  protected $method;
  protected $serverParams;
  protected $headers;

  /**
   * Undocumented function
   *
   * @param string $uri
   * @param string $method
   * @param array $serverParams
   * @param array $headers
   */
  public function __construct(
      string $uri = '',
      string $method = '', 
      array $serverParams = [],
      array $headers = []
  ) {
      $this->setServerParams($serverParams);
      $this->setHeaders($headers);

      $this->uri    = $uri;
      $this->method = $method;
  }

  /**
   * Undocumented function
   *
   * @param array $serverParams
   * @return void
   */
  public function setServerParams($serverParams = [])
  {
    foreach($serverParams as $name => $value){
      if (!is_array($value)) {
        $this->serverParams .= $name .self::GLUE. $value .self::GLUE;
      }
    }
  }

  /**
   * Undocumented function
   *
   * @param array $headers
   * @return void
   */
  public function setHeaders($headers = [])
  {
    foreach($headers as $name => $values){
      $this->headers .= $name .self::GLUE. implode(", ", $values);
    }
  }

  /**
   * Undocumented function
   *
   * @return boolean
   */
  public function put(): bool
  {
    return false !== file_put_contents(
      $this->filepath(), 
      $this->line(), 
      FILE_APPEND
    );
  }

  /**
   * Undocumented function
   *
   * @return string
   */
  private function line(): string
  {
    return $this->datetime()
      .self::GLUE.
      $this->uri
      .self::GLUE.
      $this->method
      .self::GLUE.
      $this->serverParams
      .self::GLUE.
      $this->headers
      .self::GLUE.
      PHP_EOL;
  }

  /**
   * Undocumented function
   *
   * @return string
   */
  private function datetime(): string
  {
    return Carbon::now()->format(self::FORMAT);
  }

  /**
   * Undocumented function
   *
   * @return string
   */
  private function filepath(): string
  {
    return $this->dirpath().self::FILENAME.self::FILETYPE;
  }

  /**
   * Undocumented function
   *
   * @return string
   */
  private function dirpath(): string
  {
    $dir = $_SERVER['DOCUMENT_ROOT'].self::DIRECTORY;

    if(!$this->hasDirectory($dir))
      if(!$this->createDirectory($dir))
        throw new RequestLogExeception('Cannot write to logs directory');

    return $dir;
  }

  /**
   * Undocumented function
   *
   * @param string $dir
   * @return boolean
   */
  private function hasDirectory(string $dir = ''): bool
  {
    return is_dir($dir);
  }

  /**
   * Undocumented function
   *
   * @param string $dir
   * @return boolean
   */
  private function createDirectory(string $dir = ''): bool
  {
    return mkdir($dir, 0777, true);
  }
}