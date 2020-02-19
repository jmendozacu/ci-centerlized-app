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

    function login($identity, $password){

        // echo $identity;
        // echo $password;


        $options = [
            'url' => 'https://test-app.free.beeceptor.com/rest/V1/customers',
            'method' => 'POST',
            "data" => [
                "customer" => [
                    "email" => "jdoe@example.com",
                    "firstname" => "Jane",
                    "lastname" => "Doe",
                ]
            ],
        ];
        echo $response = $this->sendRequest($options);
    }


    function register($data){

        $api_urls = $this->config->item('api_urls');

        $options = [
            'method' => 'POST',
            'data' => $data,
            // "data" => [
            //     "customer" => [
            //         "email" => "jdoe@example.com",
            //         "firstname" => "Jane",
            //         "lastname" => "Doe",
            //     ]
            // ],
        ];

        // request for drupal
        $options['url'] = $api_urls['registration']['drupal'];
        $drupalResponse = $this->sendRequest($options);

        // request for magento
        $options['url'] = $api_urls['registration']['magento'];
        $magentoResponse = $this->sendRequest($options);
    }

    function sendRequest($options){

        $url = isset($options['url']) ? $options['url'] : '';
        $data = isset($options['data']) ? $options['data'] : [];
        $method = isset($options['method']) ? $options['method'] : 'GET';

        if(in_array($method, ['PUT', 'POST', 'PATCH'])) {
            $data = ['form_params' => $data];
        }
        
        
        if($url != '' && $method != ''){

            
            try {

                # guzzle post request example with form parameter
                $response = $this->client->request( $method, $url, $data);

                // echo $response->getStatusCode(); // 200
                // echo $response->getReasonPhrase(); // OK
                // echo $response->getProtocolVersion(); // 1.1

                return $response->getBody();
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
                #guzzle repose for future use
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                // echo "<hr>";
                // echo "ERROR HERE : ";
                // echo "<hr>";
                print_r($responseBodyAsString);
                return $responseBodyAsString;
            }
        }else{
            return false;
        }
    }
    
}
