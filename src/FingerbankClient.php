<?php

namespace Slatman;

use Httpful\Http;
use Httpful\Mime;
use Httpful\Request;
use Httpful\Response;


class FingerbankClient
{
    const VERSION                       = "0.0.1";
    const USER_AGENT_SUFFIX             = "fingerbank-php-client/";

    const DEFAULT_PORT                  = 443;
    const DEFAULT_HOST                  = 'https://api.fingerbank.org';

    const API_BASE                      = "api/";
    const API_VERSION                   = "v2/";

    const ENDPOINT_DEVICES              = "devices/";
    const ENDPOINT_COMBINATIONS         = "combinations/interrogate";
    const ENDPOINT_OUI                  = "oui/";
    const ENDPOINT_STATIC               = "download/db";
    const ENDPOINT_USERS                = "accounts/";


    /**
     * @var string $api_key
     */
    private $api_key;

    /**
     * @var array $config
     */
    private $config;

    /**
     * FingerbankClient constructor.
     * @param array $config
     */
    public function __construct(array $config = []) {

        if (!array_key_exists('api_key', $config)) {
            echo 'An API key is necessary!';
            die;
        }

        $this->config = array_merge(
            [
                'api_base'          => self::API_BASE,
                'api_version'       => self::API_VERSION,
                'port'              => self::DEFAULT_PORT,
                'host'              => self::DEFAULT_HOST,
            ],
            $config
        );

        $this->api_key = $this->config['api_key'];
    }

    private function createRequestURL($path) {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $url = $host.':'.$port.'/'.$this->config['api_base'].$this->config['api_version'].$path;

        return $url;
    }


    private function prepareAuthenticatedGetRequest(string $url) {

        $authenticatedTemplate = Request::init()
            ->method(Http::GET)
            ->expects(Mime::JSON)
            ->addHeader("User-Agent", $this::USER_AGENT_SUFFIX.$this::VERSION)
        ;

        // Set it as a template
        Request::ini($authenticatedTemplate);

        $url = $url . '?key='.$this->api_key;

        return $url;
    }

    private function hasValidResponseCode(Response $response) {
        return $response->code >= 200 && $response->code < 300;
    }

    public function user() {
        $url = $this->createRequestURL(self::ENDPOINT_USERS);

        $url = $this->prepareAuthenticatedGetRequest($url);

        /** @var Response $response */
        $response = Request::get($url)->send();

        if ($this->hasValidResponseCode($response)) {
            $result = $response->body;

            return $result;
        } else {
            var_dump($response);
        }

        return false;
    }

    public function devices() {
        // Hardcoded to get the device with ID 1 for now...
        $url = $this->createRequestURL(self::ENDPOINT_DEVICES.'1');

        $url = $this->prepareAuthenticatedGetRequest($url);

        /** @var Response $response */
        $response = Request::get($url)->send();

        if ($this->hasValidResponseCode($response)) {
            $result = $response->body;

            return $result;
        } else {
            var_dump($response);
        }

        return false;
    }

    public function interrogate(string $dhcp_fingerprint = null, string $mac = null, array $user_agents = [], array $behaviors = []) {
        $url = $this->createRequestURL(self::ENDPOINT_COMBINATIONS);

        $url = $this->prepareAuthenticatedGetRequest($url);

        $body = [];
        if (!empty($dhcp_fingerprint)) {
            $body['dhcp_fingerprint'] = $dhcp_fingerprint;
        }
        if (!empty($mac)) {
            $body['mac'] = $mac;
        }
        if (count($user_agents) > 0) {
            $body['user_agents'] = $user_agents;
        }

        // TODO: behavioral analysis parameters

        /** @var Response $response */
        $response = Request::get($url)->sendsType(Mime::JSON)->body(json_encode($body))->send();

        if ($this->hasValidResponseCode($response)) {
            $result = $response->body;

            var_dump($response);

            return $result;
        } else {
            var_dump($response);
        }

        return false;
    }

    public function interrogateWithDHCPFingerprint(string $dhcp_fingerprint) {

        $result = $this->interrogate($dhcp_fingerprint);

        return $result;
    }

    public function interrogateWithMacAddress(string $mac) {

        $result = $this->interrogate(null, $mac);

        return $result;
    }

    public function interrogateWithUserAgents(array $user_agents) {

        $result = $this->interrogate(null, null, $user_agents);

        return $result;
    }

    // TODO: add other URLs
}
