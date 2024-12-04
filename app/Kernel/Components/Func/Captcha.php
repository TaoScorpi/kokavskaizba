<?php
namespace App\Kernel\Components\Func;

use App\Kernel\Components\Func\BaseDataFunction;

/**
 * @package Captcha
 */
final class Captcha extends BaseDataFunction
{
  protected $code;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->code = rand(1111,9999);
  }

  /**
   *
   * @return array
   */
  public function get(): array
  {
    return [
      'code' => $this->code,
      'codes' => str_split($this->code)
    ];
  }
}