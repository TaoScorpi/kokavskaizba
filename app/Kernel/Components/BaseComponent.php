<?php
namespace App\Kernel\Components;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Components\Component;

class BaseComponent extends Component
{
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    // @request
    $this->component->request($request);

    // @args
    $this->component->args($args);

    // @response
    return $this->view->render(
      $response, 
      $this->component->template(), 
      $this->component->data([
        'params' => $request->getQueryParams()
      ])
    );
  }
}