<?php
    class CurlRestTemplate {
 
        public static function get($url, $headers) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (isset($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
 
        public static function put($url, $data, $headers) {
            $json = json_encode($data, true);
            $curl = curl_init($url);
            if (isset($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
 
        public static function post($url, $data, $headers) {
            $json = json_encode($data, true);
            $curl = curl_init($url);
            if (isset($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            return json_decode($curl_response, true);
        }
 
    }
?>