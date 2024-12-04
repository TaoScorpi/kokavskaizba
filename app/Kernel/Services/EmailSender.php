<?php
namespace App\Kernel\Services;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * @package EmailSender
 */
final class EmailSender
{
  /**
  * @Inject
  * @var PHPMailer
  */
	private $mailer;
  
  /** @var bool **/
  protected $isHTML = false;
  
  /** @var string **/
  protected $subject = '';
  
  /** @var string **/
  protected $body = '';

  /** @var string **/
  protected $fromEmail = '';
  
  /** @var string **/
  protected $fromAlias = '';
  
  /** @var string **/
  protected $toEmail = '';
  
  /** @var string **/
  protected $toAlias = '';
  
  /** @var string **/
  protected $replyEmail = '';
  
  /** @var string **/
  protected $replyAlias = '';
  
  /** @var array **/
  protected $bcc = [];
  
  /** @var array **/
  protected $attachments = [];

  public function __construct() 
  {
    // @instance
    $this->mailer = new PHPMailer();
    
    // @charset
    $this->mailer->CharSet = $_ENV['EMAIL_CHARSET'];
    
    // @smtp
    $this->mailer->isSMTP();
    $this->mailer->SMTPAuth   = ("false" === $_ENV['EMAIL_AUTH']) ? false : true;
    $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
    $this->mailer->Port       = $_ENV['EMAIL_PORT'];
    
    // @settings
    $this->mailer->Host     = $_ENV['EMAIL_HOST'];                       
    $this->mailer->Username = $_ENV['EMAIL_USERNAME'];                     
    $this->mailer->Password = $_ENV['EMAIL_PASSWORD'];
  }
  
  /**
   * @param string $subject
   */
  public function setSubject(string $subject = '')
  {
    $this->subject = $subject;
  }
  
  /**
   * @param string $email
   * @param string $alias
   */
  public function setFrom(string $email = '', string $alias = '')
  {
    $this->fromEmail = $email;
    $this->fromAlias = $alias;
  }
  
  /**
   * @param string $email
   * @param string $alias
   */
  public function addTo(string $email = '', string $alias = '')
  {
    $this->toEmail = $email;
    $this->toAlias = $alias;
  }
  
  /**
   * @param string $email
   */
  public function addBcc(string $email = '')
  {
    $this->bcc[] = $email;
  }
  
  /**
   * @param string $email
   * @param string $alias
   */
  public function addReplyTo(string $email = '', string $alias = '')
  {
    $this->replyEmail = $email;
    $this->replyAlias = $alias;
  }
  
  /**
   * @param string $path
   * @param string $filename
   */
  public function addAttachment(string $path = '', string $filename = '')
  {
    // @attachment
    $attachment = new \stdClass();
    $attachment->path = $path;
    $attachment->filename = $filename;
    
    // @push
    $this->attachments[] = $attachment;
  }
  
  /**
   * @param bool $state
   */
  public function isHTML(bool $state = false)
  {
    $this->isHTML = $state;
  }
  
  /**
   * @param string $body
   */
  public function body(string $body = '')
  {
    $this->body = $body;
  }
  
  /**
   * @return bool
   */
  public function send(): bool
  {
    // @from
    $this->mailer->setFrom($this->fromEmail, $this->fromAlias);
    
    // @to
    $this->mailer->addAddress($this->toEmail, $this->toAlias);

    // @reply
    $this->mailer->addReplyTo($this->replyEmail, $this->replyAlias);

    // @bcc
    if(is_array($this->bcc) && 0 !== count($this->bcc)){
      foreach($this->bcc as $email){
        $this->mailer->addBCC($email);
      }
    }

    // @attachments
    if(is_array($this->attachments) && 0 !== count($this->attachments)){
      foreach($this->attachments as $attachment){
        $this->mailer->addAttachment($attachment->path, $attachment->filename);
      }
    }
    
    // @html
    if($this->isHTML) {
      $this->mailer->isHTML(true);
    }

    // @subject         
    $this->mailer->Subject = $this->subject;
    
    // @body
    $this->mailer->Body = $this->body;

    // @return
    return $this->mailer->send();
  }
}