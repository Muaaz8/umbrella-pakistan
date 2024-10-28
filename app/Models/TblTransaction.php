<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblTransaction extends Model
{
    public $table = "tbl_transactions";

    public $fillable = [
        'id',
        'transaction_id',
        'subject',
        'description',
        'total_amount',
        'balance',
        'currency',
        'user_id',
        'approval_code',
        'approval_message',
        'avs_response',
        'csc_response',
        'external_transaction_id',
        'masked_card_number',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function errorCode($code){

        $ErrorMessages = array(
              "E00001" => "An error occurred during processing Please try again.",

              "E00002"=> "The content-type specified is not supported.",

              "E00003"=> "The parser found an invalid character in card details(Card Number,CVV or CSV).",

              "E00004"=> "The name of the requested API method is invalid.",

              "E00005"=> "The transaction key or API key is invalid or not present.",

              "E00006"=> "The API user name is invalid or not present.",

              "E00007"=> "User authentication failed due to invalid authentication values.",

              "E00008"=> "User authentication failed. The account or API user is inactive.",

              "E00009"=> "The payment gateway account is in Test Mode. The request cannot be processed.",

              "E00010"=> "User authentication failed. You do not have the appropriate permissions.",

              "E00011"=> "Access denied. You do not have the appropriate permissions.",

              "E00012"=> "A duplicate subscription already exists.",

              "E00013"=> "The field is invalid.",

              "E00014"=> "A required field is not present.",

              "E00015"=> "The field length is invalid.",

              "E00016"=> "The field type is invalid.",

              "E00017"=> "The start date cannot occur in the past.",

              "E00018"=> "The credit card expires before the subscription start date.",

              "E00019"=> "The customer tax id or drivers license information is required.",

              "E00020"=> "The payment gateway account is not enabled for eCheck.Net subscriptions.",

              "E00021"=> "The payment gateway account is not enabled for credit card subscriptions.",

              "E00022"=> "The interval length cannot exceed 365 days or 12 months.",

              "E00023"=> "The subscription duration cannot exceed three years.",

              "E00024"=> "Trial Occurrences is required when Trial Amount is specified.",

              "E00025"=> "Automated Recurring Billing is not enabled.",

              "E00026"=> "Both Trial Amount and Trial Occurrences are required.",

              "E00027"=> "The transaction was unsuccessful.",

              "E00028"=> "Trial Occurrences must be less than Total Occurrences.",

              "E00029"=> "Payment information is required.",

              "E00030"=> "The payment schedule is required.",

              "E00031"=> "The amount is required.",

              "E00032"=> "The start date is required.",

              "E00033"=> "The start date cannot be changed.",

              "E00034"=> "The interval information cannot be changed.",

              "E00035"=> "The subscription cannot be found.",

              "E00036"=> "The payment type cannot be changed.",

              "E00037"=> "The subscription cannot be updated.",

              "E00038"=> "The subscription cannot be canceled.",

              "E00039"=> "The inserted Email is already registered.",

              "E00040"=> "The record cannot be found.",

              "E00041"=> "One or more fields must contain a value.",

              "E00042"=> "You cannot add more than [0] payment profiles.",

              "E00043"=> "You cannot add more than [0] shipping addresses.",

              "E00044"=> "Customer Information Manager is not enabled.",

              "E00045"=> "The root node does not reference a valid XML namespace.",

              "E00046"=> "Generic InsertNewMerchant failure.",

              "E00047"=> "Merchant Boarding API is not enabled.",

              "E00048"=> "At least one payment method must be set in payment types or an echeck service must be provided.",

              "E00049"=> "The operation timed out before it could be completed.",

              "E00050"=> "Sell Rates cannot be less than Buy Rates",

              "E00051"=> "The original transaction was not issued for this payment profile.",

              "E00052"=> "The maximum number of elements for an array [0] is [1].",

              "E00053"=> "Server too busy",

              "E00054"=> "The mobile device is not registered with this merchant account.",

              "E00055"=> "The mobile device has already been registered but is pending approval by the account administrator.",

              "E00056"=> "The mobile device has been disabled for use with this account.",

              "E00057"=> "The user does not have permissions to submit requests from a mobile device.",

              "E00058"=> "The merchant has met or exceeded the number of pending mobile devices permitted for this account.",

              "E00059"=> "The authentication type is not allowed for this method call.",

              "E00060"=> "The transaction type is invalid.",

              "E00061"=> "[0]([1]).",

              "E00062"=> "Fatal error when calling web service.",

              "E00063"=> "Calling web service return error.",

              "E00064"=> "Client authorization denied.",

              "E00065"=> "Prerequisite failed.",

              "E00066"=> "Invalid value.",

              "E00067"=> "An error occurred while parsing the XML request.  Too many [0] specified.",

              "E00068"=> "An error occurred while parsing the XML request.  [0] is invalid.",

              "E00069"=> "The Payment Gateway Account service (id&#x3D;8) has already been accepted.  Decline is not allowed.",

              "E00070"=> "The Payment Gateway Account service (id&#x3D;8) has already been declined.  Agree is not allowed.",

              "E00071"=> "[0] must contain data.",

              "E00072"=> "Node [0] is required.",

              "E00073"=> "[0] is invalid.",

              "E00074"=> "This merchant is not associated with this reseller.",

              "E00075"=> "An error occurred while parsing the XML request.  Missing field(s) [0].",

              "E00076"=> "[0] contains invalid value.",

              "E00077"=> "The value of [0] is too long.  The length of value should be [1]",

              "E00078"=> "Pending Status (not completed).",

              "E00079"=> "The impersonation login ID is invalid or not present.",

              "E00080"=> "The impersonation API Key is invalid or not present.",

              "E00081"=> "Partner account is not authorized to impersonate the login account.",

              "E00082"=> "Country for [0] is not valid.",

              "E00083"=> "Bank payment method is not accepted for the selected business country.",

              "E00084"=> "Credit card payment method is not accepted for the selected business country.",

              "E00085"=> "State for [0] is not valid.",

              "E00086"=> "Merchant has declined authorization to resource.",

              "E00087"=> "No subscriptions found for the given request.",

              "E00088"=> "ProfileIds cannot be sent when requesting CreateProfile.",

              "E00089"=> "Payment data is required when requesting CreateProfile.",

              "E00090"=> "PaymentProfile cannot be sent with payment data.",

              "E00091"=> "PaymentProfileId cannot be sent with payment data.",

              "E00092"=> "ShippingProfileId cannot be sent with ShipTo data.",

              "E00093"=> "PaymentProfile cannot be sent with billing data.",

              "E00094"=> "Paging Offset exceeds the maximum allowed value.",

              "E00095"=> "ShippingProfileId is not provided within Customer Profile.",

              "E00096"=> "Finger Print value is not valid.",

              "E00097"=> "Finger Print can&#x27;t be generated.",

              "E00098"=> "Customer Profile ID or Shipping Profile ID not found.",

              "E00099"=> "Customer profile creation failed. This transaction ID is invalid.",

              "E00100"=> "Customer profile creation failed. This transaction type does not support profile creation.",

              "E00101"=> "Customer profile creation failed.",

              "E00102"=> "Customer Info is missing.",

              "E00103"=> "Customer profile creation failed. This payment method does not support profile creation.",

              "E00104"=> "Server in maintenance. Please try again later.",

              "E00105"=> "The specified payment profile is associated with an active or suspended subscription and cannot be deleted.",

              "E00106"=> "The specified customer profile is associated with an active or suspended subscription and cannot be deleted.",

              "E00107"=> "The specified shipping profile is associated with an active or suspended subscription and cannot be deleted.",

              "E00108"=> "CustomerProfileId cannot be sent with customer data.",

              "E00109"=> "CustomerAddressId cannot be sent with shipTo data.",

              "E00110"=> "CustomerPaymentProfileId is not provided within Customer Profile.",

              "E00111"=> "The original subscription was not created with this Customer Profile.",

              "E00112"=> "The specified month should not be in the future.",

              "E00113"=> "Invalid OTS Token Data.",

              "E00114"=> "Invalid OTS Token.",

              "E00115"=> "Expired OTS Token.",

              "E00116"=> "OTS Token access violation",

              "E00117"=> "OTS Service Error &#x27;[0]&#x27;",

              "E00118"=> "The transaction has been declined.",

              "E00119"=> "Payment information should not be sent to Hosted Payment Page request.",

              "E00120"=> "Payment and Shipping Profile IDs cannot be specified when creating new profiles.",

              "E00121"=> "No default payment/shipping profile found.",

              "E00122"=> "Please use Merchant Interface settings (API Credentials and Keys) to generate a signature key.",

              "E00123"=> "The provided access token has expired",

              "E00124"=> "The provided access token is invalid",

              "E00125"=> "Hash doesnâ€™t match",

              "E00126"=> "Failed shared key validation",

              "E00127"=> "Invoice does not exist",

              "E00128"=> "Requested action is not allowed",

              "E00129"=> "Failed sending email",

              "E00130"=> "Valid Customer Profile ID or Email is required",

              "E00131"=> "Invoice created but not processed completely",

              "E00132"=> "Invoicing or CIM service is not enabled.",

              "E00133"=> "Server error.",

              "E00134"=> "Due date is invalid",

              "E00135"=> "Merchant has not provided processor information.",

              "E00136"=> "Processor account is still in process, please try again later.",

              "E00137"=> "Multiple payment types are not allowed.",

              "E00138"=> "Payment and Shipping Profile IDs cannot be specified when requesting a hosted payment page.",

              "E00139"=> "Access denied. Access Token does not have correct permissions for this API.",

              "E00140"=> "Reference Id not found",

              "E00141"=> "Payment Profile creation with this OpaqueData descriptor requires transactionMode to be set to liveMode.",

              "E00142"=> "RecurringBilling setting is a required field for recurring tokenized payment transactions.",

              "E00143"=> "Failed to parse MerchantId to integer",

              "E00144"=> "We are currently holding the last transaction for review. Before you reactivate the subscription, review the transaction.",

              "E00145"=> "This invoice has been canceled by the sender. Please contact the sender directly if you have questions. ",
            );

        if(array_key_exists($code,$ErrorMessages)){
            return $ErrorMessages[$code];
        }else{
            return "Server Not Responding!! Please Try Later";
        }
    }
}
