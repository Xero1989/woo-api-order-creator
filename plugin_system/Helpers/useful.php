<?php

class OC_Useful
{

    static function ajax_server_response($server_response = array())
    {
        if (!isset($server_response["status"])) {
            $server_response["status"] = "success";
        }
        if (!isset($server_response["message"])) {
            $server_response["message"] = "The operation has been successful";
        }

        echo json_encode($server_response);
        die();
    }

    static function log($data, $to_json = true)
    {

        $path = ABSPATH . 'log.json';
        //$path = plugin_dir_url(__FILE__) . 'log.json';

        //$size = filesize($path);

        //$obj = fopen($path, 'a');

        $obj = fopen($path, 'a');

        // session_start();

        // if ($_SESSION["is_first_write_log"]) {
        //     $obj = fopen($path, 'w');
        //     $_SESSION["is_first_write_log"] = false;
        // } else {
        //     $obj = fopen($path, 'a');
        // }

        if ($to_json)
            $data = json_encode($data);
        // fwrite($obj, "sicze ".$size);
        fwrite($obj, $data);
        fwrite($obj, "\n\n");

        fclose($obj);
    }

    static function proccess_remote_request($response, $extra_data = array())
    {
        OC_Useful::log("step 3");
        $response_code = wp_remote_retrieve_response_code($response);
        OC_Useful::log("step 4");
        $body = wp_remote_retrieve_body($response);

        OC_Useful::log("step 5");

        if ($response_code == 200 || $response_code == 201) {
            $body = wp_remote_retrieve_body($response);

            $body = json_decode($body, true);

            OC_Useful::log("Response OK -> 200");
            OC_Useful::log($body);

            return $body;
        } else {
            $body = json_decode($body, true);
            OC_Useful::log("Response ERROR -> " . $response_code);

            OC_Useful::log($body);

            foreach ($extra_data as $message) {
                OC_Useful::log($message);
            }

            return false;
        }
    }
}
