# You instantiate and run your app in this PHP file.

### Let's look at a simple custom component example:

```PHP
<?php
namespace App\Components;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Components\Component;

class CustomComponent extends Component
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
      $this->component->data()
    );
  }
}
```
