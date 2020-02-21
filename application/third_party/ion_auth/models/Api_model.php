<?php
/**
 * Name:    Ion Auth Model
 * Author:  Ben Edmunds
 *           ben.edmunds@gmail.com
 * @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5.6 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Api Auth Model
 * @property api_model $api_model
 */

class Api_model extends CI_Model
{


	/**
	 * Database object
	 *
	 * @var object
	 */
    protected $db;

    protected $client;




	public function __construct()
	{
        $this->client = new \GuzzleHttp\Client();
        $res = $this->client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        echo $res->getStatusCode();
    }

    function login($email, $password){

        $api_urls = $this->config->item('api_urls');
        $options = [ 
            'url' => $api_urls['login']['magento'],
            'method' => 'POST',
        ];
        $data = [
            "email" => $email,
            "password" => $password
        ];
        if($response = $this->sendRequest($data, $options)) {
            
        }
    }


    function register($data){

        $api_urls = $this->config->item('api_urls');
        $drupalOptions = $magentoOptions = [ 'method' => 'POST'];

        // $drupalOptions['url']   = $api_urls['registration']['drupal'];
        // $drupalData             = $this->getRegisterationDataFormat('drupal', $data); 
        $magentoOptions['url']  = $api_urls['registration']['magento'];
        $magentoData            = $this->getRegisterationDataFormat('magento', $data);


        // $drupalResponse = $this->sendRequest($drupalData, $drupalOptions);
        return $magentoResponse = $this->sendRequest($magentoData, $magentoOptions);
    }




    function sendRequest($data, $options){

        $url = isset($options['url']) ? $options['url'] : '';
        $method = isset($options['method']) ? $options['method'] : 'GET';

        if(in_array($method, ['PUT', 'POST', 'PATCH'])) {
            $data = ['json' => $data];
            $data['headers'] =  [
                'Content-Type'     => 'application/json',
            ];
        }


        if($url != '' && $method != ''){

            try {

                # guzzle post request example with form parameter
                $response = $this->client->request( $method, $url, $data);

                // echo $response->getStatusCode(); // 200
                // echo $response->getReasonPhrase(); // OK
                // echo $response->getProtocolVersion(); // 1.1

                // return $response->getBody();
                return true;
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
                #guzzle repose for future use
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                echo "<hr>";
                echo "ERROR HERE : ";
                echo "<hr>";
                pr($response);
                print_r($responseBodyAsString);
                return false;
            }
        }else{
            return false;
        }
    }

    function getRegisterationDataFormat($siteType, $data) {

        switch($siteType) {

            case 'magento':
                return [
                    'customer' => [
                        'firstname' => $data['first_name'],
                        'lastname' => $data['last_name'],
                        'email' => $data['email']
                    ],
                    'password' => $data['password']
                ];

            case 'drupal':
                return [
                    "name" => [
                        "value" => $data['email']
                    ], 
                    "pass" => [
                        "value" => $data['last_name']
                    ], 
                    "mail" => [
                        "value" => $data['email']
                    ], 
                    "_links" => [
                        "type"=> [
                            "href" => base_url("rest/type/user/user")
                        ]
                    ] 
                ];
        }
    }
}
