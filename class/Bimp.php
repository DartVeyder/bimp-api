<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Bimp
{
    private $accessToken;
    private $companyAccessToken;
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->setAccessToken();
        $this->setCompanyAccessToken();
    }

    public function makeOrder()
    {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $formattedDate = $now->format('Y-m-d\TH:i:s.u\Z');

        $data = [
            'dateTime' =>  $formattedDate,
            'shipmentDateTime' =>  $formattedDate,
            'projectUuid' => '36d8f75e-73ba-4f98-9a97-15171f9e7022', 
            'managerUuid' => 'bbdcb7e8-92a4-4b23-bcb6-054da4e3bb5e', //Менеджер
            'contractUuid' => '4daeb111-e014-4d71-bdf2-6a25f66728f7',  
            'lineOfBusinessUuid' => '6d742e47-f17b-47f9-9134-9c1e5426cbcc', //Напрям діяльності
            'organizationUuid' => '56874f6e-b28e-4b99-957a-740acfc9b970', //Організація
            'warehouseUuid' => 'ab317a83-6e9c-477c-ab3c-ca48b2ef2b84', //Склад
            'customerUuid' => 'a34fad2d-6ba1-11eb-9d1a-00155db7d90a',  //Статус оплати
            'statusUuid' => 'a34fad2d-6ba1-11eb-9d1a-00155db7d90a', //Статус замовлення
            'addVAT' => true,
            'setUpByContract' => true,
            'stocks' => [
                0 => [
                    'nomenclatureUuid' => '7ec5555f-41ed-4ddb-b136-740e1971553f',
                    'percentageDiscount' => 0,
                    'reserve' => 0.2,
                    'count' => 0.2,
                    'cost' => 1000,
                ],
            ],
        ];

        $response =  $this->makeRequest('/invoiceForCustomerPayment/api-insert', $data, ['access-token' => $this->getCompanyAccessToken(), 'accept-language' => 'uk-UA']);
        print_r( $response);
        return $this;
    }

    /**
     * Summary of makeRequest
     * @param mixed $endpoint
     * @param mixed $body
     * @param mixed $header
     * @return mixed
     */
    private function makeRequest($endpoint, $body, $header = "")
    {
        $data = [];
        if ($header) {
            $data['headers'] = $header;
        }

        if ($body) {
            $data['json'] = $body;
        }

        try {
            $response = $this->client->post(URL_API . $endpoint, $data);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Обробка помилок
            return 'Помилка при виконанні запиту: ' . $e->getMessage();
        }
    }

    /**
     * @param mixed $accessToken 
     * @return self
     */
    private function setAccessToken(): self
    {
        $response =  $this->makeRequest('/auth/api-login', ['email' => EMAIL, 'password' => PASSWORD]);
        $this->accessToken = $response['data']['accessToken'];
        return $this;
    }



    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return mixed
     */
    public function getCompanyAccessToken()
    {
        return $this->companyAccessToken;
    }

    /**
     * @param mixed $companyAccessToken 
     * @return self
     */
    private function setCompanyAccessToken(): self
    {
        $response =  $this->makeRequest('/auth/api-selectCompany', ['uuid' => COMPANY_UUID], ['access-token' => $this->accessToken, 'accept-language' => 'uk-UA']);
        $this->companyAccessToken = $response['data']['companyAccessToken'];
        return $this;
    }
}
