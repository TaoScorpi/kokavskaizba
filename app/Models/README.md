# Eloquent: Getting Started

Universal includes Eloquent, an object-relational mapper (ORM) that makes it enjoyable to interact with your mongo database. When using Eloquent, each database document has a corresponding "Model" that is used to interact with that document. In addition to retrieving records from the database document, Eloquent models allow you to insert, update, and delete records from the document as well.

### Documentation

[Basic usage](https://github.com/jenssegers/laravel-mongodb/blob/master/README.md#basic-usage)

[MongoDB-specific operators](https://github.com/jenssegers/laravel-mongodb/blob/master/README.md#mongodb-specific-operators)

[MongoDB-specific Geo operations](https://github.com/jenssegers/laravel-mongodb/blob/master/README.md#mongodb-specific-geo-operations)

[Inserts, updates and deletes](https://github.com/jenssegers/laravel-mongodb/blob/master/README.md#inserts-updates-and-deletes)

[Relationships](https://github.com/jenssegers/laravel-mongodb/blob/master/README.md#relationships)

#### Level directory layout
    .
    └── app                        # Application Root Folder
      └── Kernel                   # Kernel Folder
        └── Models                 # Models Folder
            └── UserModel.php      # MongoDB Model classes
            
#### Create simple database User model class:

```PHP
<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @package UserModel
 */
class UserModel extends Model
{
  protected $connection = 'mongodb';
  protected $collection = 'users';
  protected $fillable = [
    'status',
    'titleBefore',
    'titleAfter',
    'firstname',
    'lastname',
    'fullname',
    'email',
    'phone',
    'avatar',
    'picture',
    'background',
    'password',
    'resetExpire',
    'updatedAt',
    'createdAt'
  ];
}
```

#### Let's look at a simple custom component with database model:

```PHP
<?php
namespace App\Components;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Components\Component;
use App\Model\Users;

/**
 * @package CustomComponent
 */
class CustomComponent extends Component
{
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    // @request
    $this->component->request($request);

    // @args
    $this->component->args($args);
    
    // @data
    $data = [
      'user' => Users::find('<_id>')
    ];

    // @response
    return $this->view->render(
      $response, 
      $this->component->template(), 
      $this->component->data($data)
    );
  }
}
```