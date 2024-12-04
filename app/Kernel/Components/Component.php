<?php
namespace App\Kernel\Components;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class Component
{
  private $container;
  
  /**
   * Constructor
   *
   * @param ContainerInterface $container
   */
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }
  
  /**
   * Get Container Class
   *
   * @param [type] $property
   * @return mixed
   */
  public function __get(string $property = ''): mixed
  {
    return ($this->container->get($property)) ? $this->container->get($property) : null;
  }

  /**
   * Response with JSON
   *
   * @param Response $response
   * @param array $payload
   * @param integer $status
   * @return Response
   */
  public function json(Response $response, array $payload = [], int $status = 200): Response
  {
    $response->getBody()->write(json_encode($payload));
    return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
  }
}
