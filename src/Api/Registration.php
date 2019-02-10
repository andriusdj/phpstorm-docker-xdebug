<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Api;

use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

/**
 * Class Registration
 * @package AndriusJankevicius\Supermetrics\Api
 *  API DOCS
 * ============
 * 1. Use the following endpoint to register a token:
 *
 * POST: https://api.supermetrics.com/assignment/register
 *
 * PARAMS:
 * - client_id : ju16a6m81mhid5ue1z3v2g0uh
 * - email : your@email.address
 * - name : Your Name
 *
 * RETURNS
 * - sl_token : This token string should be used in the subsequent query. Please note that this token will only last 1 hour from when the REGISTER call happens. You will need to register and fetch a new token as you need it.
 * - client_id : returned for informational purposes only
 * - email : returned for informational purposes only
 */
class Registration
{
    private $clientId = 'ju16a6m81mhid5ue1z3v2g0uh';
    private $email = 'your@email.address';
    private $name = 'Your Name';
    private $registrationUrl = 'https://api.supermetrics.com/assignment/register';
    /**
     * @var Client
     */
    private $client;

    /**
     * Registration constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     * @throws InvalidApiResponseException
     */
    public function getToken(): string
    {
        $options = [
            RequestOptions::JSON => [
                'client_id' => $this->clientId,
                'email' => $this->email,
                'name' => $this->name,
            ],
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        try {
            $response = $this->client->post( $this->registrationUrl, $options);
        } catch (GuzzleException $e) {

            throw new InvalidApiResponseException($e->getMessage(), $e->getCode(), $e);
        }

        $content = $response->getBody()->getContents();
        $registration = json_decode($content, true);
        $token = $registration['sl_token'] ?? '';

        if (empty($token)) {

            throw new InvalidApiResponseException('Empty sl_token received!');
        }

        return $token;
    }

}
