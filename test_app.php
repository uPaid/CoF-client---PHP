<?php
    header('Content-Type: text/html; charset=utf-8');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    class CofService {
        private $url = null;
        private $user = null;
        private $password = null;
        function __construct($url, $user, $password) {
            $this->url = $url;
            $this->user = $user;
            $this->password = $password;
        }
        function getStatus() {
            $service_url = $this->url."/status";
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
        function getToken($data) {
            $service_url = $this->url."/client/security/users/token";
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode($this->user.":".$this->password)
            );
            $json = json_encode($data, true);
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
        function addUser($data) {
            $service_url = $this->url."/client/security/users";
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode($this->user.":".$this->password)
            );
            $json = json_encode($data, true);
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
        function getUser($token) {
            $service_url = $this->url."/client/users";
            $headers = array(
                'Content-Type:application/json; charset=utf-8',
                'tokenUUID: '. $token
            );
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
        function getCardsForUser($token) {
            $service_url = $this->url."/client/cards";
            $headers = array(
                'Content-Type:application/json; charset=utf-8',
                'tokenUUID: '. $token
            );
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            echo $curl_response;
            return json_decode($curl_response, true);
        }
    }

    $url = "https://url-to-server-cof-core/cof_core";
    $user = "user-name-to-basic-auth";
    $password = "password-to-basic-auth";
 
    $service = new CofService($url, $user, $password);
    $get_status_response = $service->getStatus();
    if ($get_status_response["status"] == "SUCCESS") {
        $new_user = array(
            "firstName" => "Test",
            "lastName" => "Test",
            "phone" => 300200400,
            "email" => "test.email@test.pl",
            "organizationId" => 6
        );
        $add_user_response = $service->addUser($new_user));
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
                    var_dump($user);
                }
                $get_cards_for_user_response = $service->getCardsForUser($token);
                if ($get_cards_for_user_response["status"] == "SUCCESS") {
                    $list_cards = $get_cards_for_user_response["data"];
                    var_dump($list_cards);
                }
            }
        }
    }
?>