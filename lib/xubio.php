<?php

/**
 * Xubio Api Integration Library
 * @author Martin Briglia, MGS Creativa
 * @url http://www.mgscreativa.com
 * @copyright Copyright (C) 2021 MGS Creativa - All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

class XubioApi
{
    const version = "0.1.0";

    private $client_id;
    private $secret_id;
    private $ll_access_token;

    function __construct()
    {
        $i = func_num_args();

        if ($i > 2 || $i < 2) {
            throw new XubioException("Invalid arguments. Use CLIENT_ID and SECRET_ID");
        }

        if ($i == 2) {
            $this->client_id = func_get_arg(0);
            $this->secret_id = func_get_arg(1);
        }
    }

    /**
     * Get Access Token for API use
     */
    public function get_access_token()
    {
        if (isset ($this->ll_access_token) && !is_null($this->ll_access_token)) {
            return $this->ll_access_token;
        }

        $app_client_values = array(
            'client_id' => $this->client_id,
            'secret_id' => $this->secret_id,
            'grant_type' => 'client_credentials'
        );

        $access_data = XubioRestClient::post(array(
            "uri" => "/1.1/TokenEndpoint",
            "data" => $app_client_values,
            "headers" => array(
                "content-type" => "application/x-www-form-urlencoded"
            )
        ));

        if ($access_data["status"] != 200) {
            throw new XubioException ($access_data['response']['message'], $access_data['status']);
        }

        $this->ll_access_token = $access_data['response']['access_token'];

        return $this->ll_access_token;
    }

    /**
     * Get information for client
     * @param string $id
     * @return array(json)
     */
    public function get_client($id = null)
    {
        $request = array(
            "uri" => !empty($id) ? "/1.1/clienteBean/{$id}" : "/1.1/clienteBean",
            "headers" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $result = XubioRestClient::get($request);
        return $result;
    }

    /**
     * Create a client
     * @param array $client
     * @return array(json)
     */
    public function create_client($client)
    {
        $request = array(
            "uri" => "/1.1/clienteBean",
            "headers" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $client
        );

        $result = XubioRestClient::post($request);
        return $result;
    }

    /**
     * Delete a client
     * @param string $id
     * @return array(json)
     */
    public function delete_client($id)
    {
        $request = array(
            "uri" => "/1.1/clienteBean/{$id}",
            "headers" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $result = XubioRestClient::delete($request);
        return $result;
    }

    /**
     * Update a client
     * @param string $id
     * @return array(json)
     */
    public function update_client($id, $client)
    {
        $request = array(
            "uri" => "/1.1/clienteBean/{$id}",
            "headers" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $client
        );

        $result = XubioRestClient::put($request);
        return $result;
    }

    /**
     * Get information for invoice
     * @param string $id
     * @return array(json)
     */
    public function get_invoice($id = null)
    {
        $request = array(
            "uri" => !empty($id) ? "/1.1/comprobanteVentaBean/{$id}" : "/1.1/comprobanteVentaBean",
            "headers" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $result = XubioRestClient::get($request);
        return $result;
    }

    /**
     * Create an invoice
     * @param array $invoice
     * @return array(json)
     */
    public function create_invoice($invoice)
    {
        $request = array(
            "uri" => "/1.1/facturar",
            "headers" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $invoice
        );

        $result = XubioRestClient::post($request);
        return $result;
    }

    /**
     * Create a sales invoice
     * @param array $invoice
     * @return array(json)
     */
    public function create_sales_invoice($invoice)
    {
        $request = array(
            "uri" => "/1.1/comprobanteVentaBean",
            "headers" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $invoice
        );

        $result = XubioRestClient::post($request);
        return $result;
    }

    /**
     * Delete an invoice
     * @param string $id
     * @return array(json)
     */
    public function delete_invoice($id)
    {
        $request = array(
            "uri" => "/1.1/comprobanteVentaBean/{$id}",
            "headers" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $result = XubioRestClient::delete($request);
        return $result;
    }

    /**
     * Update an invoice
     * @param array $invoice
     * @return array(json)
     */
    public function update_invoice($id, $invoice)
    {
        $request = array(
            "uri" => "/1.1/comprobanteVentaBean/{$id}",
            "headers" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $invoice
        );

        $result = XubioRestClient::put($request);
        return $result;
    }

    /**
     * Get information for all products
     * @param string $id
     * @return array(json)
     */
    public function get_product()
    {
        $request = array(
            "uri" => "/1.1/ProductoVentaBean",
            "headers" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $result = XubioRestClient::get($request);
        return $result;
    }

    /* Generic resource call methods */

    /**
     * Generic resource get
     * @param request
     * @param params (deprecated)
     * @param authenticate = true (deprecated)
     */
    public function get($request, $params = null, $authenticate = true)
    {
        if (is_string($request)) {
            $request = array(
                "uri" => $request,
                "params" => $params,
                "authenticate" => $authenticate
            );
        }

        $request["params"] = isset ($request["params"]) && is_array($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = XubioRestClient::get($request);
        return $result;
    }

    /**
     * Generic resource post
     * @param request
     * @param data (deprecated)
     * @param params (deprecated)
     */
    public function post($request, $data = null, $params = null)
    {
        if (is_string($request)) {
            $request = array(
                "uri" => $request,
                "data" => $data,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = XubioRestClient::post($request);
        return $result;
    }

    /**
     * Generic resource put
     * @param request
     * @param data (deprecated)
     * @param params (deprecated)
     */
    public function put($request, $data = null, $params = null)
    {
        if (is_string($request)) {
            $request = array(
                "uri" => $request,
                "data" => $data,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = XubioRestClient::put($request);
        return $result;
    }

    /**
     * Generic resource delete
     * @param request
     * @param data (deprecated)
     * @param params (deprecated)
     */
    public function delete($request, $params = null)
    {
        if (is_string($request)) {
            $request = array(
                "uri" => $request,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = XubioRestClient::delete($request);
        return $result;
    }
}

/* **************************************************************************************** */

/**
 * Xubio cURL RestClient
 */
class XubioRestClient
{
    const API_BASE_URL = "https://xubio.com/API";

    private static function build_request($request)
    {
        if (!extension_loaded("curl")) {
            throw new XubioException("cURL extension not found. You need to enable cURL in your php.ini or another configuration you have.");
        }

        if (!isset($request["method"])) {
            throw new XubioException("No HTTP METHOD specified");
        }

        if (!isset($request["uri"])) {
            throw new XubioException("No URI specified");
        }

        // Set headers
        $headers = array("accept: application/json");

        $json_content = true;
        $form_content = false;
        $default_content_type = true;

        if (isset($request["headers"]) && is_array($request["headers"])) {
            foreach ($request["headers"] as $h => $v) {
                $h = strtolower($h);

                if ($h == 'access_token') {
                    $h = 'authorization';
                    $v = "bearer " . $v;
                } else {
                    $v = strtolower($v);
                }

                if ($h == "content-type") {
                    $default_content_type = false;
                    $json_content = $v == "application/json";
                    $form_content = $v == "application/x-www-form-urlencoded";
                }

                array_push($headers, $h . ": " . $v);
            }
        }

        if ($default_content_type) {
            array_push($headers, "content-type: application/json");
        }

        // Build $connect
        $connect = curl_init();

        curl_setopt($connect, CURLOPT_USERAGENT, "Xubio PHP SDK/ v" . XubioApi::version);
        curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connect, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($connect, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
        curl_setopt($connect, CURLOPT_CUSTOMREQUEST, $request["method"]);
        curl_setopt($connect, CURLOPT_HTTPHEADER, $headers);

        // Set parameters and url
        if (isset ($request["params"]) && is_array($request["params"]) && count($request["params"]) > 0) {
            $request["uri"] .= (strpos($request["uri"], "?") === false) ? "?" : "&";
            $request["uri"] .= self::build_query($request["params"]);
        }
        curl_setopt($connect, CURLOPT_URL, self::API_BASE_URL . $request["uri"]);

        // Set data
        if (isset($request["data"])) {
            if ($json_content) {
                if (gettype($request["data"]) == "string") {
                    json_decode($request["data"], true);
                } else {
                    $request["data"] = json_encode($request["data"]);
                }

                if (function_exists('json_last_error')) {
                    $json_error = json_last_error();
                    if ($json_error != JSON_ERROR_NONE) {
                        throw new XubioException("JSON Error [{$json_error}] - Data: " . $request["data"]);
                    }
                }
            } else if ($form_content) {
                $request["data"] = self::build_query($request["data"]);
            }

            curl_setopt($connect, CURLOPT_POSTFIELDS, $request["data"]);
        }

        return $connect;
    }

    private static function exec($request)
    {
        $connect = self::build_request($request);

        $api_result = curl_exec($connect);
        $api_http_code = curl_getinfo($connect, CURLINFO_HTTP_CODE);

        if ($api_result === FALSE) {
            throw new XubioException (curl_error($connect));
        }

        $response = array(
            "status" => $api_http_code,
            "response" => json_decode($api_result, true)
        );

        if ($response['status'] >= 400) {
            $message = $response['response']['description'];
            if (isset ($response['response']['error'])) {
                if (isset ($response['response']['codeResponse']) && isset ($response['response']['description'])) {
                    $message .= " - " . $response['response']['codeResponse'] . ': ' . $response['response']['error'];
                }
            }

            throw new XubioException ($message, $response['status']);
        }

        curl_close($connect);

        return $response;
    }

    private static function build_query($params)
    {
        if (function_exists("http_build_query")) {
            return http_build_query($params, "", "&");
        } else {
            foreach ($params as $name => $value) {
                $elements[] = "{$name}=" . urlencode($value);
            }

            return implode("&", $elements);
        }
    }

    public static function get($request)
    {
        $request["method"] = "GET";

        return self::exec($request);
    }

    public static function post($request)
    {
        $request["method"] = "POST";

        return self::exec($request);
    }

    public static function put($request)
    {
        $request["method"] = "PUT";

        return self::exec($request);
    }

    public static function delete($request)
    {
        $request["method"] = "DELETE";

        return self::exec($request);
    }
}

class XubioException extends Exception
{
    public function __construct($message, $code = 500, Exception $previous = null)
    {
        // Default code 500
        parent::__construct($message, $code, $previous);
    }
}
