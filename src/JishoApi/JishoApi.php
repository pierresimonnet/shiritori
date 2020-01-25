<?php


namespace App\JishoApi;


use PHPUnit\Runner\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class JishoApi
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $endpoint
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function callJishoApi(string $endpoint)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://jisho.org/api/v1/search/words?keyword='.$endpoint);

        if(200 !== $response->getStatusCode()){
            throw new Exception('Connection error');
        }

        return $response;
    }

    /**
     * @return mixed|null
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getJishoResult()
    {
        $result = null;
        $valueEncode = urlencode($this->value);
        $data = $this->callJishoApi($valueEncode);
        if(!empty($data->toArray()['data'])){
            $result = $data->toArray()['data'];
        }

        return $result;
    }
}