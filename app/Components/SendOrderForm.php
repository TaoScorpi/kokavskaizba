<?php
namespace App\Components;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Components\Component;
use App\Kernel\Services\EmailSender;

class SendOrderForm extends Component
{
  /** @conf **/
  const ALIAS = "GatsbySoundsOrchestra";
	const FROM = "no-reply@gatsbysoundsorchestra.sk";
	const TO = "info@gatsbysoundsorchestra.sk";
  const SUBJECT = 'Objednávkový formulár';

  /** @var array **/
	protected $bcc = [
		'backuper@taoscorpi.sk',
	];

  public function __invoke(Request $request, Response $response, array $args): Response
  {
    // @data
    $data = $request->getParsedBody();

    // @validate
    if (!isset($data['gdpr']) || 'true' !== $data['gdpr'])
      return $this->json($response, [
        'status'  => 400,
        'message' => 'Bez súhlasu so zásadami ochrany osobných údajov nie je možné odoslať správu'
      ]);
    if (3 > strlen($data['fullname']))
      return $this->json($response, [
        'status'  => 400,
        'message' => 'Zadajte vaše meno a priezvisko'
      ]);
    if (3 > strlen($data['email']))
      return $this->json($response, [
        'status'  => 400,
        'message' => 'Zadajte emailovú adresu'
      ]);
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
      return $this->json($response, [
        'status'  => 400,
        'message' => 'Emailová adresa je v nesprávnom tvare'
      ]);
    if (3 > strlen($data['phone']))
      return $this->json($response, [
        'message' => 'Telefónne číslo je povinné',
      ], 400);
    if (3 > strlen($data['notice']))
      return $this->json($response, [
        'status'  => 400,
        'message' => 'Zadajte Vašu správu'
      ]);

    // @subject
    $subject = $this->subject($data);

    // @body
    $body = $this->view->fetch(
      $this->component->template(), $data
    );

    /**
		* @Request
		*/
    $request = new EmailSender();
    $request->setFrom(self::FROM, self::ALIAS);
    $request->setSubject($subject);
    $request->addTo($data['email']);
    $request->addReplyTo(self::TO, self::ALIAS);
    $request->isHTML(true);
    $request->body($body);
    $request->send();

    /**
		* @Ticket
		*/
    $ticket = new EmailSender();
    $ticket->setFrom(self::FROM, self::ALIAS);
    $ticket->setSubject($subject);
    $ticket->addTo(self::TO, self::ALIAS);
    $ticket->addReplyTo(self::TO, self::ALIAS);
    $ticket->isHTML(true);
    $ticket->body($body);

    // @bcc
    if (is_array($this->bcc) && count($this->bcc) > 0) {
      foreach($this->bcc as $email){
        $ticket->addBcc($email);
      }
    }
    
    // @send
    if(!$ticket->send())
      return $this->json($response, [
        'message' => 'Odoslanie žiadosti nebolo úspešné, skúste to neskôr ešte raz alebo nás kontaktujte telefonicky'
      ], 400);

    // @code
		$code = rand(1111, 9999);
		
    // @response
    return $this->json($response, [
      'code' => $code,
			'codes' => str_split($code),
      'message' => 'Odoslanie žiadosti bolo úspešne'
    ], 200);
  }

   /*****************************************************************************
  * @Internal
  *****************************************************************************/
  
  /**
   * @param array $data
   * @return string
   */
  private function subject(array $data = []): string
  {
    return 'TIKET #'.$data['code'].' | '.date('Y.m.d H:i:s',time()).' | '.$data['fullname'].' | '.self::SUBJECT;
  }
}