<?php
namespace App\Kernel\Components\Api\Form;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Components\Component;

/**
 * @package Config
 */
class Config extends Component
{
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    return $this->json($response, $request->getParsedBody(), 201);
  }
}
