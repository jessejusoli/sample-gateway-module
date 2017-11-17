<?php
/**
 * WHMCS Goleto Payment Gateway Module
 * Version 1.1 = (0.0.0.1-build-1)
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function gatewaymodule_MetaData()
{
    return array(
        'DisplayName' => 'WHMCS Goleto',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function gatewaymodule_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'WHMCS Goleto Payment Gateway Module',
        ),
        // a text field type allows for single line text input
        'accountID' => array(
            'FriendlyName' => 'Account ID',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your account ID (email), here',
        ),
        // a password field type allows for masked text input
        'secretKey' => array(
            'FriendlyName' => 'Secret Key',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter secret key(TOKEN), here',
        ),
        // a profile field type allows input your profile of billing
        'issuerProfile' => array(
            'FriendlyName' => 'Issuer Profile',
            'Type' => 'text',
            'Size' => '25',
            'Default' =>'Primary',
            'Description' => 'Enter your default profile of billing, here',
        ),
        // the yesno field type displays a single checkbox option
        'testMode' => array(
            'FriendlyName' => 'Test Mode',
            'Type' => 'yesno',
            'Description' => 'Tick to enable test mode',
        ),
        // the dropdown field type renders a select menu of options
        'apiVersion' => array(
            'FriendlyName' => 'API Version',
            'Type' => 'dropdown',
            'Options' => array(
                'option1' => '1',
                'option2' => '2',
                'option3' => '3',
            ),
            'Description' => 'Choose one',
        ),
        // the radio field type displays a series of radio button options
        'typeCurrency' => array(
            'FriendlyName' => 'Currency',
            'Type' => 'radio',
            'Options' => 'USD,EUR,BRL,BTC,NRU,APP',
            'Description' => 'Choose your option!',
        ),
        // the textarea field type allows for multi-line text input
        'payDescription' => array(
            'FriendlyName' => 'Payment description',
            'Type' => 'textarea',
            'Rows' => '3',
            'Cols' => '60',
            'Description' => 'Freeform multi-line text input field',
        ),
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */
function gatewaymodule_link($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $issuerProfil = $params['issuerProfil'];
    $testMode = $params['testMode'];
    $apiVersion = $params['apiVersion'];
    $typeCurrency = $params['typeCurrency'];
    $payDescription = $params['payDescription'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    $url = 'http://ec2-34-216-83-38.us-west-2.compute.amazonaws.com/api/payment/webservice.php';
    
    $postfields = array(
                'issueremail' => $accountId,// Fixo nao mudar
			  	'issuertoken' => $secretKey,// Fixo nao mudar
			  	'issuerprofile' => $issuerProfil,// Fixo nao mudar
			    'invoiceid' => $invoiceId, //Invoice ID [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'invoicetitle' => 'Compra',// Fixo nao mudar
			  	'invoicedescription' => $description,// Fixo nao mudar
			  	'invoiceamount' => number_format( (float) $amount, 2, '.', '' ),//Amount [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			    'invoicedate' => $vencimento,//Timestamp or Date and Time. [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'invoicecurrency' => $currencyCode,// Fixo nao mudar
			  	'invoicecurrencytype' => 'ISO',// Fixo nao mudar
			  	'invoicetaxforissuer' => '0',// Fixo nao mudar
			  	'invoiceforcenetamount' => '1',// Fixo nao mudar
			  	'userfirstname' => $firstname,//User's first name [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userlastname' => $lastname,//User's last name [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'useremail' => $email,//User's email [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userdoc' => $customer_document,//User's Tax id number [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'useraddress1' => $address1,// User's address 1 [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'useraddress2' => $address2,// User's address 2 [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'usercity' => $$city,// [ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userstate' => $state, //[ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userpostalcode' => $postcode,//[ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'usercountry' => $country, //[ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userphone1' => $phone, //[ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'userphone2' => '', //[ATENÇÃO ESTE É UM CAMPO VARIAVEL, ALTERAR AQUI]
			  	'usersocialprofile' => '', //Perfil de rede Social (faceook/twitter/G+/linkedin/etc) Pode ser: Fixo nao mudar
			  	'gatewayv' => '1', // Fixo nao mudar
			  	'birthdate' => '1989-11-23');

    $htmlOutput = '<form method="post" action="' . $url . '">';
    foreach ($postfields as $k => $v) {
        $htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . urlencode($v) . '" />';
    }
    $htmlOutput .= '<input type="submit" value="' . $langPayNow . '" />';
    $htmlOutput .= '</form>';

    return $htmlOutput;
}

/**
 * Refund transaction.
 *
 * Called when a refund is requested for a previously successful transaction.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/refunds/
 *
 * @return array Transaction response status
 */
function gatewaymodule_refund($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Transaction Parameters
    $transactionIdToRefund = $params['transid'];
    $refundAmount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // perform API call to initiate refund and interpret result

    return array(
        // 'success' if successful, otherwise 'declined', 'error' for failure
        'status' => 'success',
        // Data to be recorded in the gateway log - can be a string or array
        'rawdata' => $responseData,
        // Unique Transaction ID for the refund transaction
        'transid' => $refundTransactionId,
        // Optional fee amount for the fee value refunded
        'fees' => $feeAmount,
    );
}

/**
 * Cancel subscription.
 *
 * If the payment gateway creates subscriptions and stores the subscription
 * ID in tblhosting.subscriptionid, this function is called upon cancellation
 * or request by an admin user.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/subscription-management/
 *
 * @return array Transaction response status
 */
function gatewaymodule_cancelSubscription($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Subscription Parameters
    $subscriptionIdToCancel = $params['subscriptionID'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // perform API call to cancel subscription and interpret result

    return array(
        // 'success' if successful, any other value for failure
        'status' => 'success',
        // Data to be recorded in the gateway log - can be a string or array
        'rawdata' => $responseData,
    );
}
