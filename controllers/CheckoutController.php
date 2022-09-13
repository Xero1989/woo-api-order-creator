<?php

class XUP_CheckoutController
{


    function add_checkout_fields($fields)
    {

        wp_enqueue_style('css_datetime', plugin_dir_url(__FILE__) . '../assets/libraries/datetime/datetime.css');
        wp_enqueue_script('js_datetime', plugin_dir_url(__FILE__) . '../assets/libraries/datetime/datetime.js');

        // unset($fields['billing']);
        // unset($fields['shipping']);

        // OC_Useful::log("add_checkout_fields");
        // OC_Useful::log($fields);

        $data = array(
            // array("pickupInstruction", "Pickup Instruction"),
            array("recipientName", "Recipient Name"),
            array("recipientMobileNumber", "Recipient Mobile Number"),
            array("recipientEmail", "Recipient Email"),
            array("driverNote", "Driver Note"),
            array("dropLocation", "Drop Location"),
            array("governorate", "Governorate"),
            array("area", "Area"),
            array("blockNumber", "Block Number"),
            array("street", "Street"),
            array("avenue", "Avenue"),
            array("houseOrbuilding", "House Or Building"),
            array("apartmentOrOffice", "Apartment Or Office"),
            array("floor", "Floor"),
            array("latlong", "Latitud, Longitud"),
            array("scheduledStartTime", "Scheduled Start Time", "fdate","required"),


        );

        for ($i = 0; $i < count($data); $i++) {
            $key = $data[$i][0];
            $label = $data[$i][1];            

            if (!isset($data[$i][2]))
                $id = "";
            else  $id = $data[$i][2];

            $required = false;
            if (isset($data[$i][3]))
                $required = true;            

            $fields['billing']['billing_' . $key] = [
                'type' => 'text',
                'label' => $label,
                'placeholder' => $label,
                'priority' => ($i + 150),
                'id' => $id,
                'required' => $required
            ];
        }

        return $fields;
    }


    function woocommerce_checkout_process()
    {
        OC_Useful::log('woocommerce_checkout_process');
        // OC_Useful::log($_POST);

        $url = "http://staging.quickdeliveryco.com/api/v1/partner/getToken";

        $data = array(
            "clientId" => "kamikazefnaitees220801",
            "clientCode" => "Test@api"
        );

        OC_Useful::log("step 1");

        $response = wp_remote_request(
            $url,
            array(
                'headers' => array('Content-Type' => 'application/json'),
                'body' => json_encode($data),
                'method'    => 'POST'
            )
        );

        OC_Useful::log("step 2");
        OC_Useful::log($response);

        $response = OC_Useful::proccess_remote_request($response);

        if ($response === false) {
            wc_add_notice(__('<b>Sending Order:</b> An error occurs sending the order data...'), 'error');
            OC_Useful::log("ERROR creting token");
            return false;
        }

        OC_Useful::log("response token");
        OC_Useful::log($response);

        $token = $response["result"]["access_token"];
        
        // OC_Useful::log("token");
        // OC_Useful::log($token);

        $cart = WC()->cart->get_cart();

        $products = array();
        foreach ($cart as $cart_item_key => $cart_item) {

            $product = $cart_item['data'];
            $id_product = $product->get_id();
            $price = $product->get_price();
            $quantity = $cart_item['quantity'];
            $name = $product->get_name();

            array_push($products, array($name, $price));
        }

        // OC_Useful::log("products");
        // OC_Useful::log($products);

        $url = "http://staging.quickdeliveryco.com/api/v1/partner/create-order";

        $scheduledStartTime = $_POST["billing_scheduledStartTime"];
        if($scheduledStartTime == ""){
     //       wc_add_notice(__('<b>Scheduled Start Time:</b> This field is required...'), 'error');
            //OC_Useful::log("ERROR creting order");
            return false;
        }

        $temp = explode(" ", $scheduledStartTime);
        $date = $temp[0];
       
     //   $date = str_replace("\","-",$scheduledStartTime);
        $time = $temp[1];

        $scheduledStartTime = str_replace("/","-",$scheduledStartTime);

        // $time_input = strtotime($scheduledStartTime);
        // $date_input = getDate($time_input);
        // $date_input = date('Y-m-d H:i:s', $time_input);

        // OC_Useful::log("date time");
        // OC_Useful::log($date . " " . $time);

        $now = date("Y-m-d H:i");
        OC_Useful::log("now");
        OC_Useful::log($now);

        OC_Useful::log("input date");
        OC_Useful::log($scheduledStartTime);

       // $scheduledStartTime = strtotime($scheduledStartTime);  
       // $scheduledStartTime = date('Y-m-d H:i',$scheduledStartTime); 

        // OC_Useful::log("scheduledStartTime");
        // OC_Useful::log($scheduledStartTime);

        $now = DateTime::createFromFormat('Y-m-d H:i', $now);
        //$scheduledStartTime = date("Y-m-d H:i",$scheduledStartTime);
        $scheduledStartTime = DateTime::createFromFormat('Y-m-d H:i', $scheduledStartTime);

    //    $diff= $now - $scheduledStartTime;
        $diff = $scheduledStartTime->diff($now);
        OC_Useful::log("diff");
        OC_Useful::log($diff);

        $pickupInstruction = "";
        if($diff->h <= 1)
        $pickupInstruction = 'ASAP/Now order';
        else $pickupInstruction = "Later/scheduled order";


        $data = array(
            "orderId" => "123456",
            "vendorId" => "123456789",
            "scheduledStartTime" => date('Y-m-d\TH:i:s'), //"2021-09-30T12:35:00",
            "pickupInstruction" => $pickupInstruction,
            "dropDetails" => array(
                "paymentMode" => "PREPAID",
                "recipientName" => $_POST['billing_recipientName'],
                "recipientMobileNo" => $_POST['billing_recipientMobileNumber'],
                "recipientEmail" => $_POST['billing_recipientEmail'], //"test@test.com",
                "driverNote" => $_POST['billing_driverNote'],
                "dropLocation" => $_POST['billing_dropLocation'],
                "isLocationVerified" => false,
                "governorate" => $_POST['billing_governorate'],
                "area" => $_POST['billing_area'],
                "blockNumber" => $_POST['billing_blockNumber'],
                "street" => $_POST['billing_street'],
                "avenue" => $_POST['billing_avenue'],
                "houseOrbuilding" => $_POST['billing_houseOrbuilding'],
                "apartmentOrOffice" => $_POST['billing_apartmentOrOffice'],
                "floor" => $_POST['billing_floor'],
                "latlong" => $_POST['billing_latlong'],
                // "item" => array(
                //     "itemDescription" => "cookies",
                //     "itemPrice" => 12.0
                // )
                "item" => $products
            )
        );

        OC_Useful::log("data");
        OC_Useful::log($data);

        $response = wp_remote_request(
            $url,
            array(
                'headers' => array('Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token),
                'body' => json_encode($data),
                'method'    => 'POST'
            )
        );

        $response = OC_Useful::proccess_remote_request($response);

        OC_Useful::log("response create ot");
        OC_Useful::log($response);

        if ($response === false) {
            wc_add_notice(__('<b>Sending Order:</b> An error occurs sending the order data...'), 'error');
            OC_Useful::log("ERROR creting order");
            return false;
        }



      //  wc_add_notice(__('<b>Success Order:</b>'), 'error');
        return;
    }
}
