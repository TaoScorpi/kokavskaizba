<?php
/**
* Copyright 2020
* 
* @package    Universal Sandbox
* @version		4.0.1
*	@access			private
* @see				https://github.com/TaoScorpi/universal-sandbox
* @author     Henrich Barkoczy | <abrakadabrask@protonmail.com>
* @license    https://www.taoscorpi.sk/universal/licencia
*/
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Middleware\ContentLengthMiddleware;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Components\AppComponent;
use App\Kernel\Router\Enum\Method;
use App\Kernel\Router\Enum\RouteObject;
use App\Kernel\Router\Router;
use App\Kernel\Extensions\Guard;
use App\Kernel\Middlewares\PermissionMiddleware;
use App\Kernel\Extensions\Twig\Csrf as TwigCsrf;
use App\Kernel\Extensions\Twig\BasePath as TwigBasePath;
use App\Kernel\Extensions\Twig\Whitespace as TwigWhitespace;

/**
 * @package Bootstrap
 */
final class Bootstrap
{
	private static $_instance = null;

	protected $runtime;
	protected $endtime;
	protected $container;
	protected $app;

	private function __construct() 
	{
		// @Runtime
		$this->runtime = $this->getmicrotime();

		// @Dotenv
		(\Dotenv\Dotenv::createImmutable(dirname(__DIR__)))->load();

		// @Container
		$this->container = new Container();

		// @Database
		$this->database();

		// @Template
		$this->template();

		// @setContainer
		AppFactory::setContainer($this->container);

		/**
		 * Instantiate App
		 *
		 * In order for the factory to work you need to ensure you have installed
		 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
		 * ServerRequest creator (included with Slim PSR-7)
		 */
		$this->app = AppFactory::create();
		
		// @Middlewares
		$this->middlewares();

		// @route
		$this->route();
	}

	/**
	 * UNIVERSAL
	 *
	 * @return Bootstrap
	 */
	public static function boot(): Bootstrap
	{
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * Undocumented function
	 *
	 * @return Bootstrap
	 */
	public function run(): Bootstrap
	{
		// @run
		$this->app->run();
		
		// @self
		return $this;
	}

	/**
	 * Metrics
	 *
	 * @return void
	 */
	public function metrics(): void
	{
		// @Endtime
		$this->endtime = $this->getmicrotime();

		// @Print
		printf("Load: % .2f ms", ($this->endtime - $this->runtime) * 1000);
	}

	/**
	 * Database
	 *
	 * @return void
	 */
	private function database(): void
	{
		// @client
		$capsule = new Capsule;

		// @extend
		$capsule->getDatabaseManager()->extend('mongodb', function($config, $name) {
			$config['name'] = $name;

			return new \Jenssegers\Mongodb\Connection($config);
		});

		// @settings
		$capsule->addConnection([
			'host'     => $_ENV['DB_HOST'],
			'port'     => $_ENV['DB_PORT'],
			'database' => $_ENV['DB_DATABASE'],
			'username' => $_ENV['DB_USERNAME'],
			'password' => $_ENV['DB_PASSWORD'],
		], $_ENV['DB_DRIVER']);

		// Set the event dispatcher used by Eloquent models... (optional)
		$capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(
			new \Illuminate\Container\Container
		));

		// Make this Capsule instance available globally via static methods... (optional)
		$capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
		$capsule->bootEloquent();

		// Put to container
		$this->container->set('db', function () use ($capsule){
			return $capsule;
		});
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function template(): void
	{
		$this->container->set('view', function() {
			// @twig
			$twig = Twig::create([
				'theme' => '../themes/'.$_ENV['APP_THEME'],
				'app' => '../resources/templates'
			], [
				'cache' => ("false" === $_ENV['TWIG_CACHE']) ? false : '../cache/twig'
			]);

			// @extensions
			$twig->addExtension(new \Fullpipe\TwigWebpackExtension\WebpackExtension(
				Folder::getStaticPath().'/manifest.json', Folder::getPublicPath()
			));
			$twig->addExtension(new \voku\twig\MinifyHtmlExtension((new \voku\helper\HtmlMin()), true));
			$twig->addExtension(new TwigBasePath());
			$twig->addExtension(new TwigWhitespace());

			// @return
			return $twig;
		});
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function middlewares(): void
	{
		// Response Factory
		$responseFactory = $this->app->getResponseFactory();

		// Register Middleware On Container
		$this->container->set('csrf', function () use ($responseFactory) {
			$guard = new Guard($responseFactory);
			$guard->setPersistentTokenMode(true);
			return $guard;
		});

		// Twig Csrf
		$this->container->get('view')->addExtension(new TwigCsrf($this->container->get('csrf')));

		// Add Twig-View Middleware
		$this->app->add(TwigMiddleware::createFromContainer($this->app));

		// Register Middleware To Be Executed On All Routes
		$this->app->add('csrf');

		// Register Middleware Parser JSON body
		$this->app->addBodyParsingMiddleware();

		/**
		 * The two modes available are
		 * OutputBufferingMiddleware::APPEND (default mode) - Appends to existing response body
		 * OutputBufferingMiddleware::PREPEND - Creates entirely new response body
		 */
		// $mode = OutputBufferingMiddleware::APPEND;
		// $outputBufferingMiddleware = new OutputBufferingMiddleware($mode);

		// ContentLengthMiddleware
		$contentLengthMiddleware = new ContentLengthMiddleware();
		$this->app->add($contentLengthMiddleware);

		// Add Routing Middleware
		$this->app->addRoutingMiddleware();

		/**
		 * Add Error Handling Middleware
		 *
		 * @param bool $displayErrorDetails -> Should be set to false in production
		 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
		 * @param bool $logErrorDetails -> Display error details in error log
		 * which can be replaced by a callable of your choice.
		 
		* Note: This middleware should be added last. It will not handle any exceptions/errors
		* for middleware added after it.
		*/
		$displayErrorDetails = ("false" === $_ENV['DISPLAY_ERROR_DETAILS']) ? false : true;
  	$logErrors = ("false" === $_ENV['LOG_ERRORS']) ? false : true;
    $logErrorDetails = ("false" === $_ENV['LOG_ERROR_DETAILS']) ? false : true;

		// Error Middleware
		$errorMiddleware = $this->app->addErrorMiddleware(
			$displayErrorDetails, 
			$logErrors, 
			$logErrorDetails
		);

		// Set the Not Found Handler
		$errorMiddleware->setErrorHandler(
			HttpNotFoundException::class,
			function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
				$response = new Response();
				$response->getBody()->write('404 NOT FOUND');

				return $response->withStatus(404);
			});

		// Set the Not Allowed Handler
		$errorMiddleware->setErrorHandler(
			HttpMethodNotAllowedException::class,
			function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
				$response = new Response();
				$response->getBody()->write('405 NOT ALLOWED');

				return $response->withStatus(405);
			});
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function route(): void
	{
		// @route
		$route = (new Router())->findByURL();
		
		// @validate
		if([] === $route)
			return;

		// @component
		$this->container->set(RouteObject::component, function() use ($route) {
			return new AppComponent($route);
		});

		// @doGet
		if(Method::GET === $route[RouteObject::method]){
			$this->doGet($route[RouteObject::url], $route[RouteObject::component]);
		}

		// @doPost
		if(Method::POST === $route[RouteObject::method]){
			$this->doPost($route[RouteObject::url], $route[RouteObject::component]);
		}
	}

	/**
	 * doGet
	 *
	 * @param string $url
	 * @param string $component
	 * @return void
	 */
	private function doGet(string $url = '', string $component = ''): void
	{
		$this->app->get($url, $component)->add(PermissionMiddleware::class);
	}

	/**
	 * doPost
	 *
	 * @param string $url
	 * @param string $component
	 * @return void
	 */
	private function doPost(string $url = '', string $component = ''): void
	{
		$this->app->post($url, $component)->add(PermissionMiddleware::class);
	}

	/**
	 * Calculate runtime 
	 *
	 * @return float
	 */
	private function getmicrotime(): float
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}