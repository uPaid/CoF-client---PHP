<?php
    require_once('curl_rest_template.php');

    class CofCoreClientApp {
        private $url = null;
        private $user = null;
        private $password = null;

        function __construct($url, $user, $password) {
            $this->url = $url;
            $this->user = $user;
            $this->password = $password;
        }

        public function getStatus() {
            $service_url = $this->url."/status";
            return CurlRestTemplate::get($service_url, null);
        }

        public function getToken($data) {
            $service_url = $this->url."/client/security/users/token";
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode($this->user.":".$this->password)
            );
            return CurlRestTemplate::put($service_url, $data, $headers);
        }

        public function addUser($data) {
            $service_url = $this->url."/client/security/users";
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode($this->user.":".$this->password)
            );
            return CurlRestTemplate::post($service_url, $data, $headers);
        }

        public function getUser($token) {
            $service_url = $this->url."/client/users";
            $headers = array(
                'Content-Type:application/json; charset=utf-8',
                'tokenUUID: '. $token
            );
            return CurlRestTemplate::get($service_url, $headers);
        }

        public function getCardsForUser($token) {
            $service_url = $this->url."/client/cards";
            $headers = array(
                'Content-Type:application/json; charset=utf-8',
                'tokenUUID: '. $token
            );
            return CurlRestTemplate::get($service_url, $headers);
        }

    }
?>
