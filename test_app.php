<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once 'curl_rest_template.php';

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
 
        public static function main() {
            $url = "https://url-to-server-cof-core/cof_core";
            $user = "user-name-to-basic-auth";
            $password = "password-to-basic-auth";
 
            $service = new CofCoreClientApp($url, $user, $password);
            $get_status_response = $service->getStatus();
            if ($get_status_response["status"] == "SUCCESS") {
                $new_user = array(
                    "firstName" => "Test",
                    "lastName" => "Test",
                    "phone" => 300200400,
                    "email" => "test.email@test.pl",
                    "organizationId" => 6
                );
                $add_user_response = $service->addUser($new_user);
                if ($add_user_response["status"] == "SUCCESS") {
                    $user_data = array(
                        "id" => $new_user["email"]
                    );
                     
                    // if you have token, you don't have to ask about token in every request.
                    $get_token_response = $service->getToken($user_data);
                    if ($get_token_response["status"] == "SUCCESS") {
                        $token = $get_token_response["data"]["token"];
                         
                        $get_user_response = $service->getUser($token);
                        if ($get_user_response["status"] == "SUCCESS") {
                            $user = $get_user_response["data"];
                            // do something with user
                        }
                        $get_cards_for_user_response = $service->getCardsForUser($token);
                        if ($get_cards_for_user_response["status"] == "SUCCESS") {
                            $list_cards = $get_cards_for_user_response["data"];
                            // do something with list of user cards
                        }
                    }
                }
            }
        }
    }
 
    CofCoreClientApp::main();
?>