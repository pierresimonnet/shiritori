<?php

/**
 * Class JishoAPI
 * https://jisho.org/
 */
class JishoAPI
{
    public function getJisho(String $input): bool
    {
        $wordEncode = urlencode($input);

        $data = $this->callApi('words?keyword='.$wordEncode);

        $result = $data['data'][0]['japanese'][0]['word'];
        if($result === $input){
            return true;
        }else{
            return false;
        }
    }

    private function callApi(String $endpoint): Array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://jisho.org/api/v1/search/'.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3
        ]);

        $data = curl_exec($curl);

        if($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200){
            return null;
        }

        return json_decode($data, true);
    }
}
