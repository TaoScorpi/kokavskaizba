<?php
namespace App\Kernel\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Kernel\Logger\RequestLog;

class PermissionMiddleware
{
  public function __invoke(Request $request, RequestHandler $handler)
  {
    // @log
    if("true" === $_ENV['REQUEST_LOG']) {
      (new RequestLog(
        (string) $request->getUri(),
        $request->getMethod(),
        $request->getServerParams(),
        $request->getHeaders(),
      ))->put();
    }
    
    // @do permission logic...
    
    // @return
    return $handler->handle($request);
  }
}