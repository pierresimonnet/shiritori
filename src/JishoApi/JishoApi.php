<?php


namespace App\JishoApi;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class JishoApi
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $reading;

    /**
     * @var array
     */
    private $senses;

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
     * @return bool
     */
    public function getJishoExist()
    {
        $valueEncode = urlencode($this->value);
        $response = $this->callJishoApi($valueEncode);
        if(!empty($response->toArray()['data']) && $response->toArray()['data'][0]['japanese'][0]['word'] === $this->value){
            $this->data = $response->toArray()['data'][0];
            $this->reading = $response->toArray()['data'][0]['japanese'][0]['reading'];
            $this->senses = $response->toArray()['data'][0]['senses'][0]['english_definitions'];
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getReading()
    {
        return $this->reading;
    }

    /**
     * @return array
     */
    public function getSenses()
    {
        return $this->senses;
    }
}