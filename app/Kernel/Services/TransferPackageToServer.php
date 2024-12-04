<?php
namespace App\Kernel\Services;

use GuzzleHttp\Client;

/**
 * @package TransferFileToServer
 */
final class TransferFileToServer
{
  public static function make()
  {
    $client   = new Client();
		$filename = "package.18hehesh72h172hdhwuhd72hdhw7h72hd7.rar";
		$filepath = realpath(dirname(__DIR__).'//.uni_modules/'.$filename);
		
		return $client->request('POST', 'http://localhost:3000', [
			'multipart' => [
				[
					'name'     => 'package',
					'contents' => fopen($filepath, 'r'),
					'filename' => $filename
				],
				[
					'name'     => 'payload',
					'contents' => 'JSON_STRING'
				]
			],
		]);
  }
}