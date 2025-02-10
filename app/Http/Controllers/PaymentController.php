<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TblTransaction;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Log;

class PaymentController extends Controller
{

    public function index()
    {
        return view('payment');
    }

    public function errorCodeMessage($code){
        $message = TblTransaction::errorCode($code);
    }



    ///////////////////////// Authorize Payment API ////////////////////////
    public function new_createCustomerProfile($data)
    {
        // dd($data);
        $amount = $data['info']['amount'];
        //  dd($data['billing_info']['credit_card']['expiration_year']."-".$data['billing_info']['credit_card']['expiration_month']);
        $arr = array(
            "description" => $data['user']['description'],
            "email" => $data['user']['email'],
            "paymentProfiles" => array(
                "customerType" => "individual",
                "billTo" => array(
                    "firstName" => $data['user']['firstname'],
                    "lastName" => $data['user']['lastname'],
                    "company" => "none",
                    "address" => $data['billing_info']['billing_address']['street_address'],
                    "city" => $data['billing_info']['billing_address']['city'],
                    "state" => $data['billing_info']['billing_address']['state'],
                    "zip" => $data['billing_info']['billing_address']['zip'],
                    "country" => "US",
                    "phoneNumber" => $data['user']['phoneNumber']
                ),
                "payment" => array(
                    "creditCard" => array(
                        "cardNumber" => $data['billing_info']['credit_card']['number'],
                        "expirationDate" => $data['billing_info']['credit_card']['expiration_year'] . "-" . $data['billing_info']['credit_card']['expiration_month'],
                        "cardCode" => $data['billing_info']['csc']
                    ),

                ),
            ),
        );
        // dd($arr,$amount);
        $arr = json_encode($arr);
        $curl = curl_init();


        if (env('APP_TYPE')=='local')
        {
            $user = DB::table('users')->where('user_type','admin')->first();
            $status = $user->status;
            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "profile": ' . $arr . ',
                    "validationMode": "'.$status.'"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='testing')
        {
            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "profile": ' . $arr . ',
                    "validationMode": "testMode"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='staging')
        {
            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "profile": ' . $arr . ',
                    "validationMode": "testMode"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='production')
        {
            $user = DB::table('services')->where('name','authorize_api_mode')->first();
            if($user!=null){$status = $user->status;}else{$status='liveMode';}
            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://api.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "profile": ' . $arr . ',
                    "validationMode": "'.$status.'"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }




        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);

        // $response = utf8_encode($response);
        // $response = json_decode($response);


        if ($response["messages"]["resultCode"] == "Error") {
            if($response["messages"]["message"][0]['code'] == "E00039"){
                $customerProfileId = explode(" ", $response["messages"]["message"][0]['text'])[5];
                return $this->new_createCustomerPaymentProfile($amount,$customerProfileId,$arr);
            }
            else{
                return ($response);
            }
        } else {
            //     var_dump($response['messages']['message'][0]);
            //     var_dump($response['customerProfileId']);
            //     var_dump($response['customerPaymentProfileIdList']);
            // dd('ok');
            $customerId = $response['customerProfileId'];
            return $this->new_getCustomerProfile($amount, $customerId);
        }
    }

    public function new_createCustomerPaymentProfile($amount,$customerProfileId,$arr){
        $arr = json_decode($arr);
        $curl = curl_init();
        $user = DB::table('users')->where('user_type','admin')->first();
        $status = $user->status;
        if (env('APP_TYPE')=='local')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "createCustomerPaymentProfileRequest": {
                      "merchantAuthentication": {
                          "name": "' . env('AUTHORIZE_NAME') . '",
                          "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                },
                  "customerProfileId": "'.$customerProfileId.'",
                  "paymentProfile": {
                    "billTo": '.json_encode($arr->paymentProfiles->billTo).',
                    "payment": '.json_encode($arr->paymentProfiles->payment).',
                    "defaultPaymentProfile": false
                  },
                  "validationMode": "testMode"
                }
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                  ),
              ));
        }
        if (env('APP_TYPE')=='testing')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "createCustomerPaymentProfileRequest": {
                      "merchantAuthentication": {
                          "name": "' . env('AUTHORIZE_NAME') . '",
                          "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                },
                  "customerProfileId": "'.$customerProfileId.'",
                  "paymentProfile": {
                    "billTo": '.json_encode($arr->paymentProfiles->billTo).',
                    "payment": '.json_encode($arr->paymentProfiles->payment).',
                    "defaultPaymentProfile": false
                  },
                  "validationMode": "testMode"
                }
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                  ),
              ));
        }
        if (env('APP_TYPE')=='staging')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "createCustomerPaymentProfileRequest": {
                      "merchantAuthentication": {
                          "name": "' . env('AUTHORIZE_NAME') . '",
                          "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                },
                  "customerProfileId": "'.$customerProfileId.'",
                  "paymentProfile": {
                    "billTo": '.json_encode($arr->paymentProfiles->billTo).',
                    "payment": '.json_encode($arr->paymentProfiles->payment).',
                    "defaultPaymentProfile": false
                  },
                  "validationMode": "testMode"
                }
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                  ),
              ));
        }
        if (env('APP_TYPE')=='production')
        {
            $user = DB::table('services')->where('name','authorize_api_mode')->first();
            if($user!=null){$status = $user->status;}else{$status='liveMode';}
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "createCustomerPaymentProfileRequest": {
                      "merchantAuthentication": {
                          "name": "' . env('AUTHORIZE_NAME') . '",
                          "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                },
                  "customerProfileId": "'.$customerProfileId.'",
                  "paymentProfile": {
                    "billTo": '.json_encode($arr->paymentProfiles->billTo).',
                    "payment": '.json_encode($arr->paymentProfiles->payment).',
                    "defaultPaymentProfile": false
                  },
                  "validationMode": "'.$status.'"
                }
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                  ),
              ));

        }

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);

        if ($response['messages']['resultCode'] == "Error") {
            return ($response);
        } else {
            $paymentProfileId = ($response['customerPaymentProfileId']);
            return $this->new_createPaymentwithCustomerProfile($amount, $customerProfileId, $paymentProfileId);
        }


    }

    // $amount,$customerProfileId
    public function new_getCustomerProfile($amount, $customerProfileId)
    {
        $curl = curl_init();
        if (env('APP_TYPE')=='local')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "getCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "customerProfileId": ' . $customerProfileId . ',
                    "includeIssuerInfo": "true"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='testing')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "getCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "customerProfileId": ' . $customerProfileId . ',
                    "includeIssuerInfo": "true"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='staging')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "getCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "customerProfileId": ' . $customerProfileId . ',
                    "includeIssuerInfo": "true"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='production')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "getCustomerProfileRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "customerProfileId": ' . $customerProfileId . ',
                    "includeIssuerInfo": "true"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }








        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);
        // dd($response);
        if ($response['messages']['resultCode'] == "Error") {
            return ($response);
        } else {
            $paymentProfileId = ($response['profile']['paymentProfiles'][0]['customerPaymentProfileId']);
            return $this->new_createPaymentwithCustomerProfile($amount, $customerProfileId, $paymentProfileId);
        }
    }

    public function new_createPaymentwithCustomerProfile($amount, $customerProfileId, $paymentProfileId)
    {
        $curl = curl_init();
        $amount = str_replace(',','',$amount);
        $amount = (float)$amount;

        if (env('APP_TYPE')=='local')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createTransactionRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "transactionRequest": {
                        "transactionType": "authCaptureTransaction",
                        "amount": ' . $amount . ',
                          "profile": {
                              "customerProfileId": "' . $customerProfileId . '",
                              "paymentProfile": { "paymentProfileId": ' . $paymentProfileId . ' }
                          },

                        "authorizationIndicatorType": {
                        "authorizationIndicator": "final"
                        }
                    }
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='testing')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createTransactionRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "transactionRequest": {
                        "transactionType": "authCaptureTransaction",
                        "amount": ' . $amount . ',
                          "profile": {
                              "customerProfileId": "' . $customerProfileId . '",
                              "paymentProfile": { "paymentProfileId": ' . $paymentProfileId . ' }
                          },

                        "authorizationIndicatorType": {
                        "authorizationIndicator": "final"
                        }
                    }
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='staging')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apitest.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createTransactionRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "transactionRequest": {
                        "transactionType": "authCaptureTransaction",
                        "amount": ' . $amount . ',
                          "profile": {
                              "customerProfileId": "' . $customerProfileId . '",
                              "paymentProfile": { "paymentProfileId": ' . $paymentProfileId . ' }
                          },

                        "authorizationIndicatorType": {
                        "authorizationIndicator": "final"
                        }
                    }
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }
        if (env('APP_TYPE')=='production')
        {
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.authorize.net/xml/v1/request.api',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "createTransactionRequest": {
                    "merchantAuthentication": {
                        "name": "' . env('AUTHORIZE_NAME') . '",
                        "transactionKey": "' . env('AUTHORIZE_TRANSACTION_KEY') . '"
                    },
                    "transactionRequest": {
                        "transactionType": "authCaptureTransaction",
                        "amount": ' . $amount . ',
                          "profile": {
                              "customerProfileId": "' . $customerProfileId . '",
                              "paymentProfile": { "paymentProfileId": ' . $paymentProfileId . ' }
                          },

                        "authorizationIndicatorType": {
                        "authorizationIndicator": "final"
                        }
                    }
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }




        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);
        // dd($response);
        if ($response['messages']['resultCode'] == "Error") {
            return $response;
        } else {
            return $response;
        }
    }
    ///////////////////////// Authorize Payment API ////////////////////////


    public function getTokenForPayment()
    {
        $getTokenForPayment = Curl::to('' . env('P_URL') . '/oauth/token?grant_type=' . env('P_GRANDTYPE') . '&username=' . env('P_USERNAME') . '&password=' . env('P_PASSWORD') . '')->post();
        $validateToken = isset(json_decode($getTokenForPayment)->access_token);
        if ($validateToken) {
            $extractToken = json_decode($getTokenForPayment);
            $BearerToken = $extractToken->access_token;
            $msg = 'Token created successfully.';
            $status = true;
        } else {
            $BearerToken = "";
            $msg = 'No Transaction token generated.';
            $status = false;
        }
        return [
            'token_' => $BearerToken,
            'message' => $msg,
            'success' => $status,
        ];
    }
    public function createCustomerProfile($customerData, $recurringData, $uid)
    {
        $getTokenForPayment = $this->getTokenForPayment();
        $BearerToken = $getTokenForPayment['token_'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('P_URL') . '/v1/customer/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($customerData),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $BearerToken
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        if ($res->success == 'true') {


            $this->chargeRecurringPayment($BearerToken, $recurringData, $res, $uid);
        } else {
            return $response;
        }
    }
    public function chargeRecurringPayment($BearerToken, $recurringData, $profile_response, $uid)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('P_URL') . '/v1/recurrence/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($recurringData),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $BearerToken,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        if ($res->success == 'true') {
            DB::table('subscription_transactions')
                ->insert(
                    [
                        'uid' => $uid,
                        'customer_id' => $profile_response->customer_id,
                        'masked_card_number' => $profile_response->masked_card_number,
                        'recurrence_id' => $res->recurrence->id,
                        'total_amount' => $recurringData['recurrence']['amount'],
                        'start_date' => $recurringData['recurrence']['start_date'],
                        'response_code' => $res->response_code,
                        'status_message' => $res->status_message
                    ]
                );
            return $response;
        } else {
            // remove customer profile from paytrace

            $paramData = [
                'customer_id' => $profile_response->customer_id,
                'integrator_id' => $profile_response->customer_id
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('P_URL') . '/v1/customer/delete',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($paramData),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $BearerToken,
                ),
            ));
            $res = curl_exec($curl);
            curl_close($curl);
            return $response;
        }
    }
    public function stopRecurryPayment($data)
    {

        $getTokenForPayment = $this->getTokenForPayment();
        $token = $getTokenForPayment['token_'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('P_URL') . '/v1/recurrence/delete',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return  $response;
    }

    public function paymentToPayTrace($BearerToken, $data)
    {
        $BearerToken = $BearerToken['token_'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('P_URL') . '/v1/transactions/sale/keyed',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $BearerToken,
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        return json_decode($response);
    }

    public function proceedToPay(Request $request)
    {
        $input = $request;

        // Get Token for payment
        $getTokenForPayment = $this->getTokenForPayment();

        if ($getTokenForPayment['success']) {
            $createTransaction = $this->paymentToPayTrace($getTokenForPayment, $input['billing_info']);

            $paymentResult = (array) $createTransaction;

            if ($paymentResult['success'] == 'true') {
                $transactionArr = [
                    'transaction_id' => $paymentResult['transaction_id'],
                    'subject' => $input['info']['subject'],
                    'description' => $input['info']['description'],
                    'total_amount' => $input['billing_info']['amount'],
                    'user_id' => $input['info']['user_id'],
                    'approval_code' => $paymentResult['approval_code'],
                    'approval_message' => $paymentResult['approval_message'],
                    'avs_response' => $paymentResult['avs_response'],
                    'csc_response' => $paymentResult['csc_response'],
                    'external_transaction_id' => $paymentResult['external_transaction_id'],
                    'masked_card_number' => substr($paymentResult['masked_card_number'], -7),
                ];

                TblTransaction::create($transactionArr);
            }
        } else {
            $paymentResult = $getTokenForPayment;
        }

        return json_encode($paymentResult);
    }

    public function doc_wallet(Request $request)
    {
        //dd($request);
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $getpercentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();


        //start here total doctor earning

        $getSessionTotals = DB::table("sessions")->where('doctor_id', $user_id)->where('status', 'ended')->get();
        $totalDoctorSessionIncom = 0;
        // dd($getSessionTotals);
        foreach ($getSessionTotals as $getSessionTotal) {

            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotal->price;
            $totalDoctorSessionIncom += $doc_price;
        }
        $totalEarning = $totalDoctorSessionIncom;
        //end here total doctor earning

        //start here current Year doctor earning
        $currentYear = date('Y');
        $getDoctorSessionTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->get();
        // dd($getDoctorSessionTotals);
        $currentYeartotalsessionAmount = 0;
        foreach ($getDoctorSessionTotals as $getDoctorSessionTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $getDoctorSessionTotal->price;
            $currentYeartotalsessionAmount += $doc_price;
        }
        $totalEarningCurrentYear = $currentYeartotalsessionAmount;

        //start here current month doctor earning
        $currentMonth = date('m');
        $currentMonthDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->get();

        $month_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->groupBy('lab_orders.order_id')
            ->get();
        $month_lab_approval_earning = count($month_lab_approval_earning)*3;

        $currentMonthDoctorTotalAmount = 0;
        foreach ($currentMonthDoctorTotals as $currentMonthDoctorTotal) {
            $doc_price = ($getpercentage->percentage / 100) * $currentMonthDoctorTotal->price;
            $currentMonthDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentMonth = $currentMonthDoctorTotalAmount + $month_lab_approval_earning;
        //end here current month doctor earning

        //start here current Day doctor earning
        $currentDay = date('d');
        $currentDayDoctorTotals = DB::table("sessions")
            ->where('doctor_id', $user_id)
            ->where('status', 'ended')
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->get();

        $day_lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->whereRaw('DAY(created_at) = ?', [$currentDay])
            ->groupBy('lab_orders.order_id')
            ->get();
        $day_lab_approval_earning = count($day_lab_approval_earning)*3;

        $currentDayDoctorTotalAmount = 0;
        foreach ($currentDayDoctorTotals as $currentDayDoctorTotals) {
            $doc_price = ($getpercentage->percentage / 100) * $currentDayDoctorTotals->price;
            $currentDayDoctorTotalAmount += $doc_price;
        }
        $totalEarningCurrentDay = $currentDayDoctorTotalAmount + $day_lab_approval_earning;

        $lab_approval_earning = DB::table('lab_orders')
            ->where('lab_orders.status', 'essa-forwarded')
            ->where('lab_orders.type', 'Counter')
            ->where('lab_orders.doc_id', $user_id)
            ->groupBy('lab_orders.order_id')
            ->get();
        $lab_approval_earning = count($lab_approval_earning)*3;

        //end here current Day doctor earning

        if ($request->from_date != null && $request->to_date != null) {
            // $fromDate = $request->from_date;
            // $toDate = $request->to_date;
            // $fromDate = explode("-", str_replace('/', '-', $fromDate));
            // $toDate = explode("-", str_replace('/', '-', $toDate));
            $doctorHistory = DB::table("sessions")
                ->join("users", 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', $user_id)
                ->where('sessions.status', 'ended')
                ->where('sessions.date', '>=', $request->from_date)
                ->where('sessions.date', '<=', $request->to_date)
                ->select('sessions.*', 'users.*')
                ->paginate(10);
            //dd($doctorHistory);
            // ->whereRaw('MONTH(sessions.created_at) >= ?', [$fromDate[0]])
            // ->whereRaw('DAY(sessions.created_at) >= ?', [$fromDate[1]])
            // ->whereRaw('YEAR(sessions.created_at) >= ?', [$fromDate[2]])
            // ->whereRaw('MONTH(sessions.created_at) <= ?', [$toDate[0]])
            // ->whereRaw('DAY(sessions.created_at) <= ?', [$toDate[1]])
            // ->whereRaw('YEAR(sessions.created_at) <= ?', [$toDate[2]])
            foreach ($doctorHistory as $doc_history) {
                $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
                $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];

                $date = new DateTime($doc_history->start_time);
                $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
                $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
                // $date->setTimezone(new DateTimeZone($user_time_zone));
                $doc_history->start_time = $date;

                $date1 = new DateTime($doc_history->end_time);
                $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
                $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
                // $date1->setTimezone(new DateTimeZone($user_time_zone));
                $doc_history->end_time = $date1;
            }

            // end doctor select date range session history

        } else {
            //start here current month doctor history
            $doctorHistory = DB::table("sessions")
                ->join("users", 'users.id', '=', 'sessions.patient_id')
                ->where('sessions.doctor_id', $user_id)
                ->where('sessions.status', 'ended')
                ->whereRaw('MONTH(sessions.created_at) = ?', [$currentMonth])
                ->select('sessions.*', 'users.name', 'users.last_name')
                ->orderBy('sessions.id', 'DESC')
                ->paginate(10);

            foreach ($doctorHistory as $doc_history) {
                $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;
                $doc_history->date = User::convert_utc_to_user_timezone($user_id, $doc_history->start_time)['date'];
                $date = new DateTime($doc_history->start_time);
                $date = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->start_time)));
                $date = User::convert_utc_to_user_timezone($user_id,$date)['time'];
                // $date->setTimezone(new DateTimeZone($user_time_zone));
                $doc_history->start_time = $date;

                $date1 = new DateTime($doc_history->end_time);
                $date1 = date('h:i A',strtotime('-15 minutes',strtotime($doc_history->end_time)));
                $date1 = User::convert_utc_to_user_timezone($user_id,$date1)['time'];
                // $date1->setTimezone(new DateTimeZone($user_time_zone));
                $doc_history->end_time = $date1;
            }

            //end here current month doctor history
        }
        //return dd($currentMonthDoctorHistory);


        return view('dashboard_doctor.wallet.index', compact('user_type', 'totalEarning', 'getpercentage', 'totalEarningCurrentMonth', 'totalEarningCurrentYear', 'totalEarningCurrentDay','lab_approval_earning', array('doctorHistory')));
    }

    public function wallet(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if ($user_type == "doctor") {

            $getpercentage = DB::table('doctor_percentage')->where('doc_id', $user_id)->first();


            //start here total doctor earning

            $getSessionTotals = DB::table("sessions")->where('doctor_id', $user_id)->where('status', 'ended')->get();
            $totalDoctorSessionIncom = 0;
            foreach ($getSessionTotals as $getSessionTotal) {

                $doc_price = ($getpercentage->percentage / 100) * $getSessionTotal->price;
                $totalDoctorSessionIncom += $doc_price;
            }
            $totalEarning = $totalDoctorSessionIncom;
            //end here total doctor earning

            //start here current Year doctor earning
            $currentYear = date('Y');
            $getDoctorSessionTotals = DB::table("sessions")
                ->where('doctor_id', $user_id)
                ->where('status', 'ended')
                ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                ->get();
            $currentYeartotalsessionAmount = 0;
            foreach ($getDoctorSessionTotals as $getDoctorSessionTotal) {
                $doc_price = ($getpercentage->percentage / 100) * $getDoctorSessionTotal->price;
                $currentYeartotalsessionAmount += $doc_price;
            }
            $totalEarningCurrentYear = $currentYeartotalsessionAmount;

            //start here current month doctor earning
            $currentMonth = date('m');
            $currentMonthDoctorTotals = DB::table("sessions")
                ->where('doctor_id', $user_id)
                ->where('status', 'ended')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentMonthDoctorTotalAmount = 0;
            foreach ($currentMonthDoctorTotals as $currentMonthDoctorTotal) {
                $doc_price = ($getpercentage->percentage / 100) * $currentMonthDoctorTotal->price;
                $currentMonthDoctorTotalAmount += $doc_price;
            }
            $totalEarningCurrentMonth = $currentMonthDoctorTotalAmount;
            //end here current month doctor earning

            //start here current Day doctor earning
            $currentDay = date('d');
            $currentDayDoctorTotals = DB::table("sessions")
                ->where('doctor_id', $user_id)
                ->where('status', 'ended')
                ->whereRaw('DAY(created_at) = ?', [$currentDay])
                ->get();

            $currentDayDoctorTotalAmount = 0;
            foreach ($currentDayDoctorTotals as $currentDayDoctorTotals) {
                $doc_price = ($getpercentage->percentage / 100) * $currentDayDoctorTotals->price;
                $currentDayDoctorTotalAmount += $doc_price;
            }
            $totalEarningCurrentDay = $currentDayDoctorTotalAmount;

            //end here current Day doctor earning

            if ($request['daterange'] != null) {
                // start doctor select date range session history
                $dateRange = $request['daterange'];
                $margeDate = explode("-", $dateRange);
                $fromDate = $margeDate[0];
                $toDate = $margeDate[1];
                $fromDate = explode("-", str_replace('/', '-', $fromDate));
                $toDate = explode("-", str_replace('/', '-', $toDate));
                $doctorHistory = DB::table("sessions")
                    ->join("users", 'users.id', '=', 'sessions.patient_id')
                    ->where('sessions.doctor_id', $user_id)
                    ->where('sessions.status', 'ended')
                    ->whereRaw('MONTH(sessions.created_at) >= ?', [$fromDate[0]])
                    ->whereRaw('DAY(sessions.created_at) >= ?', [$fromDate[1]])
                    ->whereRaw('YEAR(sessions.created_at) >= ?', [$fromDate[2]])
                    ->whereRaw('MONTH(sessions.created_at) <= ?', [$toDate[0]])
                    ->whereRaw('DAY(sessions.created_at) <= ?', [$toDate[1]])
                    ->whereRaw('YEAR(sessions.created_at) <= ?', [$toDate[2]])
                    ->select('sessions.*', 'users.*')
                    ->get();
                foreach ($doctorHistory as $doc_history) {
                    $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;

                    $date = new DateTime($doc_history->start_time);
                    $date->setTimezone(new DateTimeZone($user_time_zone));
                    $doc_history->start_time = $date->format('h:i:s A');

                    $date1 = new DateTime($doc_history->end_time);
                    $date1->setTimezone(new DateTimeZone($user_time_zone));
                    $doc_history->end_time = $date1->format('h:i:s A');
                }

                // end doctor select date range session history

            } else {
                //start here current month doctor history
                $doctorHistory = DB::table("sessions")
                    ->join("users", 'users.id', '=', 'sessions.patient_id')
                    ->where('sessions.doctor_id', $user_id)
                    ->where('sessions.status', 'ended')
                    ->whereRaw('MONTH(sessions.created_at) = ?', [$currentMonth])
                    ->select('sessions.*', 'users.*')
                    ->get();

                foreach ($doctorHistory as $doc_history) {
                    $doc_history->price = ($getpercentage->percentage / 100) * $doc_history->price;

                    $date = new DateTime($doc_history->start_time);
                    $date->setTimezone(new DateTimeZone($user_time_zone));
                    $doc_history->start_time = $date->format('h:i:s A');

                    $date1 = new DateTime($doc_history->end_time);
                    $date1->setTimezone(new DateTimeZone($user_time_zone));
                    $doc_history->end_time = $date1->format('h:i:s A');
                }

                //end here current month doctor history
            }
            //return dd($currentMonthDoctorHistory);


            return view('wallet_page', compact('user_type', 'totalEarning', 'getpercentage', 'totalEarningCurrentMonth', 'totalEarningCurrentYear', 'totalEarningCurrentDay', array('doctorHistory')));
        } else if ($user_type == "admin") {

            //start here total session earning
            $getSessionTotalSessions = DB::table("sessions")->where('status', 'ended')->get();
            $totalAdminSessionIncom = 0;
            foreach ($getSessionTotalSessions as $getSessionTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
                $totalAdminSessionIncom += $getSessionTotalSession->price - $doc_price;
            }
            $totalSessionPrice = $totalAdminSessionIncom;

            //end here total orders earning

            //start here total orders earning
            $getOrderTotal = DB::table("tbl_orders")
                ->where('order_status', 'complete')
                ->sum('total');
            //dd($getOrderTotal);
            //end here total orders earning

            //start here total lab orders earning
            $getLabOrderTotal = DB::table("tbl_products")
                ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
                ->where('lab_orders.status', 'complete')
                ->sum('tbl_products.sale_price');
            //dd($getLabOrderTotal);
            //end here total lab orders earning

            //start here total lab orders earning
            $currentMonth = date('m');
            $currentDay = date('d');
            $currentMonthTotalSessions = DB::table("sessions")
                ->where('status', 'ended')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $totalAdminSessionIncomMonth = 0;
            foreach ($currentMonthTotalSessions as $currentMonthTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $currentMonthTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $currentMonthTotalSession->price;
                $totalAdminSessionIncomMonth += $currentMonthTotalSession->price - $doc_price;
            }
            $currentMonthTotal = $totalAdminSessionIncomMonth;

            $getOrderMonthTotal = DB::table("tbl_orders")
                ->where('order_status', 'complete')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->sum('total');

            $getLabOrderMonthTotal = DB::table("tbl_products")
                ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
                ->where('lab_orders.status', 'complete')
                ->whereRaw('MONTH(lab_orders.created_at) = ?', [$currentMonth])
                ->sum('tbl_products.sale_price');

            //dd($totalMonthBalance);
            //end here total lab orders earning

            $currentTodayTotalSessions = DB::table("sessions")
                ->where('status', 'ended')
                ->whereRaw('DAY(created_at) = ?', [$currentDay])
                ->get();

            $totalAdminSessionIncomToday = 0;
            foreach ($currentTodayTotalSessions as $currentTodayTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $currentTodayTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $currentTodayTotalSession->price;
                $totalAdminSessionIncomToday += $currentTodayTotalSession->price - $doc_price;
            }
            $currentTodayTotal = $totalAdminSessionIncomToday;

            $getOrderTodayTotal = DB::table("tbl_orders")
                ->where('order_status', 'complete')
                ->whereRaw('DAY(created_at) = ?', [$currentDay])
                ->sum('total');

            $getLabOrderTodayTotal = DB::table("tbl_products")
                ->join('lab_orders', 'tbl_products.id', '=', 'lab_orders.product_id')
                ->where('lab_orders.status', 'complete')
                ->whereRaw('DAY(lab_orders.created_at) = ?', [$currentDay])
                ->sum('tbl_products.sale_price');

            $totalTodayBalance = $getLabOrderTodayTotal + $getOrderTodayTotal + $currentTodayTotal;
            $totalMonthBalance = $currentMonthTotal + $getOrderMonthTotal + $getLabOrderMonthTotal;
            $totalBalance = $getLabOrderTotal + $getOrderTotal + $totalSessionPrice;

            if ($request['daterange'] != null && $request['filtertype'] != null) {

                // start doctor select date range session history
                $dateRange = $request['daterange'];
                $filterType = $request['filtertype'];
                // dd($dateRangedd);

                $margeDate = explode("-", $dateRange);
                $fromDate = $margeDate[0];
                $toDate = $margeDate[1];
                $fromDate = explode("-", str_replace('/', '-', $fromDate));
                $toDate = explode("-", str_replace('/', '-', $toDate));

                if ($filterType == "evisit") {
                    $doctorHistory = DB::table("sessions")
                        ->join("users", 'users.id', '=', 'sessions.patient_id')
                        ->where('sessions.status', 'ended')
                        ->whereRaw('MONTH(sessions.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(sessions.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(sessions.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(sessions.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(sessions.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(sessions.created_at) <= ?', [$toDate[2]])
                        ->select('sessions.*', 'users.*')
                        ->paginate(9);
                    //return dd($doctorHistory);
                } else if ($filterType == "pharmacy") {
                    $doctorHistory = DB::table("tbl_orders")
                        ->join("users", 'users.id', '=', 'tbl_orders.customer_id')
                        ->where('tbl_orders.order_status', 'complete')
                        ->whereRaw('MONTH(tbl_orders.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(tbl_orders.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(tbl_orders.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(tbl_orders.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(tbl_orders.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(tbl_orders.created_at) <= ?', [$toDate[2]])
                        ->select('tbl_orders.*', 'users.*')
                        ->paginate(9);
                    // dd($doctorHistory);
                } else if ($filterType == "labs") {
                    $doctorHistory = DB::table("lab_orders")
                        ->join("users", 'users.id', '=', 'lab_orders.user_id')
                        ->join("tbl_products", 'tbl_products.id', '=', 'lab_orders.product_id')
                        ->where('lab_orders.status', 'complete')
                        ->whereRaw('MONTH(lab_orders.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(lab_orders.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(lab_orders.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(lab_orders.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(lab_orders.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(lab_orders.created_at) <= ?', [$toDate[2]])
                        ->select('lab_orders.*', 'users.*', 'tbl_products.name as product_name', 'tbl_products.sale_price')
                        ->paginate(9);
                    //  dd($doctorHistory);
                } else if ($filterType == "imaging") {
                    $doctorHistory = [];
                }
            } else {
                $filterType = "evisit";
                // //start here current month doctor history
                $doctorHistory = DB::table("sessions")
                    ->join("users", 'users.id', '=', 'sessions.patient_id')
                    ->where('sessions.status', 'ended')
                    ->whereRaw('MONTH(sessions.created_at) = ?', [$currentMonth])
                    ->select('sessions.*', 'users.*')
                    ->get();
                foreach ($doctorHistory as $doctor_his) {
                    $date = new DateTime($doctor_his->start_time);
                    $date->setTimezone(new DateTimeZone($user_time_zone));
                    $doctor_his->start_time = $date->format('h:i:s A');

                    $date1 = new DateTime($doctor_his->end_time);
                    $date1->setTimezone(new DateTimeZone($user_time_zone));
                    $doctor_his->end_time = $date1->format('h:i:s A');

                    $getpercentage = DB::table('doctor_percentage')->where('doc_id', $doctor_his->doctor_id)->first();
                    $doc_price = ($getpercentage->percentage / 100) * $doctor_his->price;
                    $doctor_his->price = $doctor_his->price - $doc_price;
                }

                //return dd($doctorHistory);

                // //end here current month doctor history
            }
            //return dd($currentMonthDoctorHistory);
            $count = count($doctorHistory);
            return view('admin_wallet', compact('totalBalance', 'totalTodayBalance', 'totalMonthBalance', 'totalSessionPrice', 'getOrderTotal', 'getLabOrderTotal', 'filterType', array('doctorHistory'), 'count'));
        }
    }






    //new_wallet_function

    public function wallet_graph_values()
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $data['EvisitEarningsForGraf'] = [0, 0, 0, 0, 0, 0];
        $data['OnlineEarningsForGraf'] = [0, 0, 0, 0, 0, 0];
        $data['PrescriptionEarningsForGraf'] = [0, 0, 0, 0, 0, 0];
        $data['evisit_ear'] = 0;
        $data['pres_ear'] = 0;
        $data['online_ear'] = 0;
        $data['yaxis'] = [0, 0, 0, 0];
        $data['MonthsForGraph'] = ['', '', '', '', '', ''];
        $count = 5;
        $c_date = date('Y-m');
        $mon = date('m', strtotime($c_date));
        $year = date('Y', strtotime($c_date));
        for ($i = 0; $i < 6; $i++) {
            $mon = date('m', strtotime($c_date));
            $data['MonthsForGraph'][$count] = date('M Y', strtotime($c_date));
            $Sessions_check = DB::table("sessions")
                ->where('status','!=','pending')
                ->whereRaw('YEAR(created_at) = ?', [$year])
                ->whereRaw('MONTH(created_at) = ?', [$mon])
                ->get();
            foreach ($Sessions_check as $currentMonthTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $currentMonthTotalSession->doctor_id)->first();
                $card_fee = (2 / 100) * $currentMonthTotalSession->price;
                $doc_price = ($getpercentage->percentage / 100) * $currentMonthTotalSession->price;
                $data['EvisitEarningsForGraf'][$count] += $currentMonthTotalSession->price - $doc_price - $card_fee;
            }

            $data['OnlineEarningsForGraf'][$count] = DB::table("lab_orders")
            ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
            ->where('lab_orders.type', 'Counter')
            ->whereRaw('YEAR(lab_orders.created_at) = ?', [$year])
            ->whereRaw('MONTH(lab_orders.created_at) = ?', [$mon])
            ->sum('quest_data_test_codes.SALE_PRICE');

            $c_date = date('Y-m', strtotime('- ' . ($i + 1) . ' Month', strtotime(date('Y-m'))));
            $count--;
        }

        $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            ->get();
            foreach ($prescriptions as $pres) {
                if ($pres->type == 'lab-test') {
                    $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                    $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                    $pres->name = $test->DESCRIPTION;
                    $pres->sale_price = $test->SALE_PRICE;
                    $pres->price = $test->PRICE;
                    $pres->order_id = $order->order_id;
                    $pres->pro_id = $pres->test_id;
                } else if ($pres->type == 'imaging') {
                    $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                    $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                    $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                    $pres->name = $test->name;
                    $pres->sale_price = $price->price;
                    $pres->price = $price->actual_price;
                    $pres->order_id = $order->order_id;
                    $pres->pro_id = $pres->imaging_id;
                } else if ($pres->type == 'medicine') {
                    $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                    $order = DB::table('medicine_order')->where('session_id', $pres->session_id)->first();
                    $price = DB::table('medicine_pricings')
                    ->where('id', $pres->price)
                    ->first();
                    $pres->name = $test->name;
                    $pres->sale_price = $price->sale_price;
                    $pres->price = $price->price;
                    if ($order != null) {
                        $pres->order_id = $order->order_main_id;
                    } else {
                        $pres->order_id = $pres->id;
                    }
                    $pres->pro_id = $pres->medicine_id;
                }
                if(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][0])))
                {
                    $data['PrescriptionEarningsForGraf'][0] += $pres->sale_price;
                }
                elseif(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][1])))
                {
                    $data['PrescriptionEarningsForGraf'][1] += $pres->sale_price;
                }
                elseif(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][2])))
                {
                    $data['PrescriptionEarningsForGraf'][2] += $pres->sale_price;
                }
                elseif(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][3])))
                {
                    $data['PrescriptionEarningsForGraf'][3] += $pres->sale_price;
                }
                elseif(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][4])))
                {
                    $data['PrescriptionEarningsForGraf'][4] += $pres->sale_price;
                }
                elseif(date('Y-m',strtotime($pres->created_at))==date('Y-m',strtotime($data['MonthsForGraph'][5])))
                {
                    $data['PrescriptionEarningsForGraf'][5] += $pres->sale_price;
                }

            }
            $data['yaxis'][0] =  max($data['EvisitEarningsForGraf']);
            $data['yaxis'][1] =  max($data['OnlineEarningsForGraf']);
            $data['yaxis'][2] =  max($data['PrescriptionEarningsForGraf']);
            $data['yaxis'][3] =  max($data['yaxis']);
            $add = (20/100)*$data['yaxis'][3];
            $data['yaxis'][3] = (int)$data['yaxis'][3] + (int)$add;
            $check = strval($data['yaxis'][3]);
            $check[0] = strval($data['yaxis'][3])[0] + 1;
            for($i=1;$i<strlen($check);$i++)
            {
                $check[$i] = '0';
            }
            $data['yaxis'][3] = (int)$check;
            $data['evisit_ear'] = array_sum($data['EvisitEarningsForGraf']);
            $data['pres_ear'] = array_sum($data['PrescriptionEarningsForGraf']);
            $data['online_ear'] = array_sum($data['OnlineEarningsForGraf']);
        return $data;
    }

    public function Wallet_Pay(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;

        if ($user_type == "admin" || $user_type == "admin_finance") {
            //start here total session earning
            $getSessionTotalSessions = DB::table("sessions")->where('status','!=','pending')->orderBy('id','DESC')->paginate(10,['*'],'session');
            $totalAdminSessionIncom = 0;
            foreach ($getSessionTotalSessions as $getSessionTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->doc_percent = $getpercentage->percentage;
                $getSessionTotalSession->doc_price = $doc_price;
                $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
                $totalAdminSessionIncom += $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
                $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
                $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
                $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
                $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
                $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);
            }
            $totalSessionPrice = $totalAdminSessionIncom;

            //end here total orders earning
            $getOrderTotal = 0;
            $getOrderMonthTotal=0;
            $getOrderTodayTotal = 0;
            $currentMonth = date('Y-m');
            $currentDay = date('Y-m-d');
            if ($request['daterange'] != null && $request['filtertype'] != null) {

                // start doctor select date range session history
                $dateRange = $request['daterange'];
                $filterType = $request['filtertype'];
                // dd($dateRangedd);

                $margeDate = explode("-", $dateRange);
                $fromDate = $margeDate[0];
                $toDate = $margeDate[1];
                $fromDate = explode("-", str_replace('/', '-', $fromDate));
                $toDate = explode("-", str_replace('/', '-', $toDate));

                if ($filterType == "evisit") {
                    $doctorHistory = DB::table("sessions")
                        ->join("users", 'users.id', '=', 'sessions.patient_id')
                        ->where('sessions.status', 'ended')
                        ->whereRaw('MONTH(sessions.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(sessions.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(sessions.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(sessions.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(sessions.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(sessions.created_at) <= ?', [$toDate[2]])
                        ->select('sessions.*', 'users.*')
                        ->get();
                    //return dd($doctorHistory);
                } else if ($filterType == "pharmacy") {
                    $doctorHistory = DB::table("tbl_orders")
                        ->join("users", 'users.id', '=', 'tbl_orders.customer_id')
                        ->where('tbl_orders.order_status', 'complete')
                        ->whereRaw('MONTH(tbl_orders.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(tbl_orders.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(tbl_orders.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(tbl_orders.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(tbl_orders.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(tbl_orders.created_at) <= ?', [$toDate[2]])
                        ->select('tbl_orders.*', 'users.*')
                        ->get();
                    // dd($doctorHistory);
                } else if ($filterType == "labs") {
                    $doctorHistory = DB::table("lab_orders")
                        ->join("users", 'users.id', '=', 'lab_orders.user_id')
                        ->join("tbl_products", 'tbl_products.id', '=', 'lab_orders.product_id')
                        ->where('lab_orders.status', 'complete')
                        ->whereRaw('MONTH(lab_orders.created_at) >= ?', [$fromDate[0]])
                        ->whereRaw('DAY(lab_orders.created_at) >= ?', [$fromDate[1]])
                        ->whereRaw('YEAR(lab_orders.created_at) >= ?', [$fromDate[2]])
                        ->whereRaw('MONTH(lab_orders.created_at) <= ?', [$toDate[0]])
                        ->whereRaw('DAY(lab_orders.created_at) <= ?', [$toDate[1]])
                        ->whereRaw('YEAR(lab_orders.created_at) <= ?', [$toDate[2]])
                        ->select('lab_orders.*', 'users.*', 'tbl_products.name as product_name', 'tbl_products.sale_price')
                        ->get();
                    //  dd($doctorHistory);
                } else if ($filterType == "imaging") {
                    $doctorHistory = [];
                }
            } else {
                $filterType = "evisit";
                // //start here current month doctor history
                $doctorHistory = DB::table("sessions")
                    ->join("users", 'users.id', '=', 'sessions.patient_id')
                    ->where('sessions.status', 'ended')
                    ->whereRaw('MONTH(sessions.created_at) = ?', [date('m')])
                    ->select('sessions.*', 'users.*')
                    ->get();
                foreach ($doctorHistory as $doctor_his) {
                    $date = new DateTime($doctor_his->start_time);
                    $date->setTimezone(new DateTimeZone($user_time_zone));
                    $doctor_his->start_time = $date->format('h:i:s A');

                    $date1 = new DateTime($doctor_his->end_time);
                    $date1->setTimezone(new DateTimeZone($user_time_zone));
                    $doctor_his->end_time = $date1->format('h:i:s A');

                    $getpercentage = DB::table('doctor_percentage')->where('doc_id', $doctor_his->doctor_id)->first();
                    $doc_price = ($getpercentage->percentage / 100) * $doctor_his->price;
                    $doctor_his->price = $doctor_his->price - $doc_price;
                }

                //return dd($doctorHistory);

                // //end here current month doctor history
            }

            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->groupby('sessions.id')
            ->select('sessions.session_id as ses_id','sessions.id as sessi_id','sessions.created_at')
            ->paginate(10,['*'],'pres');
            // foreach ($prescriptions as $press) {
            //     $press->prescriptions = DB::table('prescriptions')
            //     ->join('sessions','prescriptions.session_id','sessions.id')
            //     ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            //     ->where('tbl_cart.item_type','prescribed')
            //     ->where('tbl_cart.status','purchased')
            //     ->where('sessions.id',$press->sessi_id)
            //     ->orderBy('prescriptions.id','DESC')
            //     ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            //     ->get();
            //     $orderid=null;
            //     foreach($press->prescriptions as $pres)
            //     {
            //         if ($pres->type == 'lab-test') {
            //             $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
            //             $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
            //             $pres->name = $test->DESCRIPTION;
            //             $pres->sale_price = $test->SALE_PRICE;
            //             $pres->price = $test->PRICE;
            //             $pres->order_id = $order->order_id;
            //             $pres->pro_id = $pres->test_id;
            //         } else if ($pres->type == 'imaging') {
            //             $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
            //             $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
            //             $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
            //             $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();;
            //             $pres->name = $test->name;
            //             $pres->sale_price = $price->price;
            //             $pres->price = $price->actual_price;
            //             $pres->order_id = $order->order_id;
            //             $pres->pro_id = $pres->imaging_id;
            //         } else if ($pres->type == 'medicine') {
            //             $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
            //             $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
            //             $price = DB::table('medicine_pricings')
            //             ->where('id', $pres->price)
            //             ->first();
            //             $pres->name = $test->name;
            //             $pres->sale_price = $price->sale_price;
            //             $pres->price = $price->price;
            //             $pres->order_id = $order->order_main_id;
            //             $pres->pro_id = $pres->medicine_id;
            //         }
            //         if(date('Y-m',strtotime($pres->created_at))==$currentMonth)
            //         {
            //             $getOrderMonthTotal += $pres->sale_price;
            //         }
            //         if(date('Y-m-d',strtotime($pres->created_at))==$currentDay)
            //         {
            //             $getOrderTodayTotal += $pres->sale_price;
            //         }
            //         $getOrderTotal += $pres->sale_price;
            //         $orderid = $pres->order_id;
            //     }
            //     $press->order_id = $orderid;
            //     $press->datetime = User::convert_utc_to_user_timezone($user_id, $press->created_at);
            // }
            //start here total orders earning

            //dd($getOrderTotal);
            //end here total orders earning

            //start here total lab orders earning
            $getLabOrderTotal = DB::table("lab_orders")
            ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
            ->where('lab_orders.type', 'Counter')
            ->sum('quest_data_test_codes.SALE_PRICE');
            //dd($getLabOrderTotal);
            //end here total lab orders earning

            //start here total lab orders earning
            $currentMonthTotalSessions = DB::table("sessions")
                ->where('status', 'ended')
                ->orwhere('status', 'paid')
                ->whereRaw('MONTH(created_at) = ?', [date('m')])
                ->get();

            $totalAdminSessionIncomMonth = 0;
            foreach ($currentMonthTotalSessions as $currentMonthTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $currentMonthTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $currentMonthTotalSession->price;
                // dd($doc_price);
                $currentMonthTotalSession->card_fee = (2 / 100) * $currentMonthTotalSession->price;
                $totalAdminSessionIncomMonth += $currentMonthTotalSession->price - $doc_price -$currentMonthTotalSession->card_fee;
            }
            $currentMonthTotal = $totalAdminSessionIncomMonth;



            $getLabOrderMonthTotal = DB::table("lab_orders")
            ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
            ->where('lab_orders.type', 'Counter')
            ->whereRaw('MONTH(lab_orders.created_at) = ?', [date('m')])
            ->sum('quest_data_test_codes.SALE_PRICE');

            //dd($totalMonthBalance);
            //end here total lab orders earning

            $currentTodayTotalSessions = DB::table("sessions")
                ->where('status', 'ended')
                ->whereRaw('DAY(created_at) = ?', [date('d')])
                ->get();

            $totalAdminSessionIncomToday = 0;
            foreach ($currentTodayTotalSessions as $currentTodayTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $currentTodayTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $currentTodayTotalSession->price;
                $currentTodayTotalSession->card_fee = (2 / 100) * $currentTodayTotalSession->price;
                $totalAdminSessionIncomToday += $currentTodayTotalSession->price - $doc_price - $currentTodayTotalSession->card_fee;
            }
            $currentTodayTotal = $totalAdminSessionIncomToday;



            $getLabOrderTodayTotal = DB::table("lab_orders")
                ->join('quest_data_test_codes', 'quest_data_test_codes.TEST_CD', 'lab_orders.product_id')
                ->where('lab_orders.type', 'Counter')
                ->whereRaw('DAY(lab_orders.created_at) = ?', [date('d')])
                ->sum('quest_data_test_codes.SALE_PRICE');
            $data = $this->pres_earn();
            $totalSessionPrice = $this->evisit_earn();
            $totalTodayBalance = $getLabOrderTodayTotal + $data['today'] + $currentTodayTotal;
            $totalMonthBalance = $currentMonthTotal + $data['month'] + $getLabOrderMonthTotal;
            $totalBalance = $getLabOrderTotal + $data['total'] + $totalSessionPrice;



            $OnlineItems = DB::table('lab_orders')->where('type','Counter')->orderBy('id','DESC')->paginate(10,['*'],'online');
            foreach ($OnlineItems as $ot) {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
                $ot->price = $test->PRICE;
                $ot->sale_price = $test->SALE_PRICE;
                $ot->datetime = User::convert_utc_to_user_timezone($user_id, $ot->created_at);
                $ot->name = $test->DESCRIPTION;
            }

            //return dd($currentMonthDoctorHistory);
            $count = count($doctorHistory);
            if($user_type == "admin")
            {
                return view('dashboard_admin.wallet_pay', compact('totalBalance', 'totalTodayBalance', 'totalMonthBalance', 'totalSessionPrice', 'getOrderTotal', 'getLabOrderTotal', 'filterType', array('doctorHistory'), 'count', 'getSessionTotalSessions', 'prescriptions', 'OnlineItems'));
            }
            elseif($user_type == "admin_finance")
            {
                return view('dashboard_finance_admin.finance_admin', compact('totalBalance', 'totalTodayBalance', 'totalMonthBalance', 'totalSessionPrice', 'getOrderTotal', 'getLabOrderTotal', 'filterType', array('doctorHistory'), 'count', 'getSessionTotalSessions', 'prescriptions', 'OnlineItems'));
            }

        } else {
            return redirect(url()->previous());
        }
    }

    public function filtered_values(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if ($request->msg == 'session') {
            if($request->id != null)
            {
                $request->id = explode('-',$request->id);
                $s_id = $request->id[1];
                $getSessionTotalSessions = DB::table("sessions")->where('session_id',$s_id)->where('status', 'ended')->orwhere('status', 'paid')->get();
            }
            elseif($request->date != null)
            {
                $request->date = explode('-', $request->date);
                $startdate = date('Y-m-d', strtotime($request->date[0]));
                $enddate = date('Y-m-d', strtotime($request->date[1]));
                $getSessionTotalSessions = DB::table("sessions")->where('date', '>=', $startdate)->where('date', '<=', $enddate)->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
            }
            else
            {
                $getSessionTotalSessions = DB::table("sessions")->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
            }
            foreach ($getSessionTotalSessions as $getSessionTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->doc_percent = $getpercentage->percentage;
                $getSessionTotalSession->doc_price = $doc_price;
                $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
                $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
                $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
                $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
                $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
                $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);
            }
            $data = $getSessionTotalSessions;
        } else if ($request->msg == 'pres') {
            if($request->id != null)
            {
                $request->id = explode('-',$request->id);
                $s_id = $request->id[1];
                $prescriptions = DB::table('prescriptions')
                ->join('sessions','prescriptions.session_id','sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('sessions.session_id',$s_id)
                ->where('tbl_cart.item_type','prescribed')
                ->where('tbl_cart.status','purchased')
                ->orderBy('prescriptions.id','DESC')
                ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
                ->get();
            }
            elseif($request->date != null)
            {
                $request->date = explode('-', $request->date);
                $startdate = date('Y-m-d', strtotime($request->date[0]));
                $enddate = date('Y-m-d', strtotime($request->date[1]));
                $prescriptions = DB::table('prescriptions')
                ->join('sessions','prescriptions.session_id','sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->whereDate('prescriptions.created_at', '>=', $startdate)
                ->whereDate('prescriptions.created_at', '<=', $enddate)
                ->where('tbl_cart.item_type','prescribed')
                ->where('tbl_cart.status','purchased')
                ->orderBy('prescriptions.id','DESC')
                ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
                ->get();
            }
            else
            {
                $prescriptions = DB::table('prescriptions')
                ->join('sessions','prescriptions.session_id','sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('tbl_cart.item_type','prescribed')
                ->where('tbl_cart.status','purchased')
                ->orderBy('prescriptions.id','DESC')
                ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
                ->get();
            }
            foreach ($prescriptions as $pres) {
                if ($pres->type == 'lab-test') {
                    $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                    $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                    $pres->name = $test->DESCRIPTION;
                    $pres->sale_price = $test->SALE_PRICE;
                    $pres->price = $test->PRICE;
                    $pres->order_id = $order->order_id;
                    $pres->pro_id = $pres->test_id;
                } else if ($pres->type == 'imaging') {
                    $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                    $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                    $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                    $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                    $pres->name = $test->name;
                    $pres->sale_price = $price->price;
                    $pres->price = $price->actual_price;
                    $pres->order_id = $order->order_id;
                    $pres->pro_id = $pres->imaging_id;
                } else if ($pres->type == 'medicine') {
                    $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                    $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                    $price = DB::table('medicine_pricings')
                    ->where('id', $pres->price)
                    ->first();
                    $pres->name = $test->name;
                    $pres->sale_price = $price->sale_price;
                    $pres->price = $price->price;
                    $pres->order_id = $order->order_main_id;
                    $pres->pro_id = $pres->medicine_id;
                }
                $pres->datetime = User::convert_utc_to_user_timezone($user_id, $pres->created_at);
            }
            $data = $prescriptions;
        } elseif ($request->msg == 'online') {
            if($request->date!=null)
            {
                $request->date = explode('-', $request->date);
                $startdate = date('Y-m-d', strtotime($request->date[0]));
                $enddate = date('Y-m-d', strtotime($request->date[1]));
                $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->where('date', '>=', $startdate)->where('date', '<=', $enddate)->orderBy('id','DESC')->get();
            }
            else
            {
                $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->orderBy('id','DESC')->get();
            }
            foreach ($OnlineItems as $ot) {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
                $ot->price = $test->PRICE;
                $ot->sale_price = $test->SALE_PRICE;
                $ot->datetime = User::convert_utc_to_user_timezone($user_id, $ot->created_at);
                $ot->name = $test->DESCRIPTION;
            }
            $data = $OnlineItems;
        }
        return $data;
    }

    public function fetch_session_data(Request $request)
    {
        if($request->ajax())
        {
            $user_time_zone = auth()->user()->timeZone;
            $user_type = Auth::user()->user_type;
            $user_id = Auth::user()->id;
                //start here total session earning
            $getSessionTotalSessions = DB::table("sessions")->where('status','!=','pending')->orderBy('id','DESC')->paginate(10,['*'],'session');
            $totalAdminSessionIncom = 0;
            foreach ($getSessionTotalSessions as $getSessionTotalSession) {
                $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
                $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->doc_percent = $getpercentage->percentage;
                $getSessionTotalSession->doc_price = $doc_price;
                $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
                $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
                $totalAdminSessionIncom += $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
                $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
                $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
                $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
                $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
                $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);
            }
            return view('dashboard_finance_admin.pagination_pages.sessions',compact('getSessionTotalSessions'))->render();
        }
    }
    public function fetch_pres_data(Request $request)
    {
        if($request->ajax())
        {
            $user_time_zone = auth()->user()->timeZone;
            $user_type = Auth::user()->user_type;
            $user_id = Auth::user()->id;
            $getOrderTotal = 0;
            $getOrderMonthTotal=0;
            $getOrderTodayTotal = 0;
            $currentMonth = date('Y-m');
            $currentDay = date('Y-m-d');
            //start here total session earning
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->groupby('sessions.id')
            ->select('sessions.session_id as ses_id','sessions.id as sessi_id','sessions.created_at')
            ->paginate(10,['*'],'pres');
            foreach ($prescriptions as $press) {
                $press->prescriptions = DB::table('prescriptions')
                ->join('sessions','prescriptions.session_id','sessions.id')
                ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
                ->where('tbl_cart.item_type','prescribed')
                ->where('tbl_cart.status','purchased')
                ->where('sessions.id',$press->sessi_id)
                ->orderBy('prescriptions.id','DESC')
                ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
                ->get();
                $orderid=null;
                foreach($press->prescriptions as $pres)
                {
                    if ($pres->type == 'lab-test') {
                        $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                        $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                        $pres->name = $test->DESCRIPTION;
                        $pres->sale_price = $test->SALE_PRICE;
                        $pres->price = $test->PRICE;
                        $pres->order_id = $order->order_id;
                        $pres->pro_id = $pres->test_id;
                    } else if ($pres->type == 'imaging') {
                        $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                        $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                        $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                        $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->price;
                        $pres->price = $price->actual_price;
                        $pres->order_id = $order->order_id;
                        $pres->pro_id = $pres->imaging_id;
                    } else if ($pres->type == 'medicine') {
                        $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                        $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                        $price = DB::table('medicine_pricings')
                        ->where('id', $pres->price)
                        ->first();
                        $pres->name = $test->name;
                        $pres->sale_price = $price->sale_price;
                        $pres->price = $price->price;
                        $pres->order_id = $order->order_main_id;
                        $pres->pro_id = $pres->medicine_id;
                    }
                    if(date('Y-m',strtotime($pres->created_at))==$currentMonth)
                    {
                        $getOrderMonthTotal += $pres->sale_price;
                    }
                    if(date('Y-m-d',strtotime($pres->created_at))==$currentDay)
                    {
                        $getOrderTodayTotal += $pres->sale_price;
                    }
                    $getOrderTotal += $pres->sale_price;
                    $orderid = $pres->order_id;
                }
                $press->order_id = $orderid;
                $press->datetime = User::convert_utc_to_user_timezone($user_id, $press->created_at);
            }
            return view('dashboard_finance_admin.pagination_pages.prescription',compact('prescriptions'))->render();
        }
    }
    public function fetch_online_data(Request $request)
    {
        if($request->ajax())
        {
            $user_time_zone = auth()->user()->timeZone;
            $user_type = Auth::user()->user_type;
            $user_id = Auth::user()->id;

            $OnlineItems = DB::table('lab_orders')->where('type','Counter')->orderBy('id','DESC')->paginate(10,['*'],'online');
            foreach ($OnlineItems as $ot) {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
                $ot->price = $test->PRICE;
                $ot->sale_price = $test->SALE_PRICE;
                $ot->datetime = User::convert_utc_to_user_timezone($user_id, $ot->created_at);
                $ot->name = $test->DESCRIPTION;
            }
            return view('dashboard_finance_admin.pagination_pages.online',compact('OnlineItems'))->render();
        }
    }

    public function pres_earn()
    {
        $prescriptions = DB::table('prescriptions')
        ->join('sessions','prescriptions.session_id','sessions.id')
        ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
        ->where('tbl_cart.item_type','prescribed')
        ->where('tbl_cart.status','purchased')
        ->orderBy('prescriptions.id','DESC')
        ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
        ->get();
        $getOrderTotal = 0;
        $getOrderMonthTotal = 0;
        $getOrderTodayTotal = 0;
        foreach($prescriptions as $pres)
        {
            if ($pres->type == 'lab-test') {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                $pres->name = $test->DESCRIPTION;
                $pres->sale_price = $test->SALE_PRICE;
                $pres->price = $test->PRICE;
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->test_id;
            } else if ($pres->type == 'imaging') {
                $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                $loc = DB::table('imaging_selected_location')->where('session_id', $pres->sessi_id)->where('product_id',$pres->imaging_id)->first();
                $price = DB::table('imaging_prices')->where('location_id', $loc->imaging_location_id)->where('product_id',$loc->product_id)->first();
                $pres->name = $test->name;
                $pres->sale_price = 0;
                $pres->price = 0;
                if($price!=null)
                {
                    $pres->sale_price = $price->price;
                    $pres->price = $price->actual_price;
                }
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->imaging_id;
            } else if ($pres->type == 'medicine') {
                $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                $price = DB::table('medicine_pricings')
                ->where('id', $pres->price)
                ->first();
                $pres->name = $test->name;
                $pres->sale_price = 0;
                $pres->price = 0;
                if($price!=null)
                {
                    $pres->sale_price = $price->sale_price;
                    $pres->price = $price->price;
                }
                $pres->order_id = $order->order_main_id;
                $pres->pro_id = $pres->medicine_id;
            }
            if(date('Y-m',strtotime($pres->created_at))==date('Y-m'))
            {
                $getOrderMonthTotal += $pres->sale_price;
            }
            if(date('Y-m-d',strtotime($pres->created_at))==date('Y-m-d'))
            {
                $getOrderTodayTotal += $pres->sale_price;
            }
            $getOrderTotal += $pres->sale_price;
        }
        $data['total'] = $getOrderTotal;
        $data['month'] = $getOrderMonthTotal;
        $data['today'] = $getOrderTodayTotal;
        return $data;
    }

    public function evisit_earn()
    {
        $getSessionTotalSessions = DB::table("sessions")->where('status','!=','pending')->orderBy('id','DESC')->get();
        $totalAdminSessionIncom = 0;
        foreach ($getSessionTotalSessions as $getSessionTotalSession) {
            $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->doc_percent = $getpercentage->percentage;
            $getSessionTotalSession->doc_price = $doc_price;
            $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
            $totalAdminSessionIncom += $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
        }
        return $totalAdminSessionIncom;
    }
}
