<?php

/**
 * Donation Interface
 *
 *  To install the DontaionInterface extension, put the following line in LocalSettings.php:
 *	require_once( "\$IP/extensions/DonationInterface/DonationInterface.php" );
 *
 */


# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install the DonationInterface extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/DonationInterface/DonationInterface.php" );
EOT;
	exit( 1 );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Donation Interface',
	'author' => array( 'Elliott Eggleston', 'Katie Horn', 'Ryan Kaldari' , 'Arthur Richards', 'Sherah Smith', 'Matt Walker', 'Adam Wight', 'Peter Gehres', 'Jeremy Postlethwaite' ),
	'version' => '2.1.0',
	'descriptionmsg' => 'donationinterface-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:DonationInterface',
);

$donationinterface_dir = dirname( __FILE__ ) . '/';

// Test mode (not for production!)
// Set it if not defined
if ( !isset( $wgDonationInterfaceTestMode) || $wgDonationInterfaceTestMode !== true ) {
	$wgDonationInterfaceTestMode = false;
}


/**
 * CLASSES
 */
$wgAutoloadClasses['CurrencyRates'] = $donationinterface_dir . 'gateway_common/CurrencyRates.php';
$wgAutoloadClasses['DonationData'] = $donationinterface_dir . 'gateway_common/DonationData.php';
$wgAutoloadClasses['DonationLoggerFactory'] = $donationinterface_dir . 'gateway_common/DonationLoggerFactory.php';
$wgAutoloadClasses['DonationLogProcessor'] = $donationinterface_dir . 'gateway_common/DonationLogProcessor.php';
$wgAutoloadClasses['DonationQueue'] = $donationinterface_dir . 'gateway_common/DonationQueue.php';
$wgAutoloadClasses['EncodingMangler'] = $donationinterface_dir . 'gateway_common/EncodingMangler.php';
$wgAutoloadClasses['FinalStatus'] = $donationinterface_dir . 'gateway_common/FinalStatus.php';
$wgAutoloadClasses['GatewayAdapter'] = $donationinterface_dir . 'gateway_common/gateway.adapter.php';
$wgAutoloadClasses['GatewayPage'] = $donationinterface_dir . 'gateway_common/GatewayPage.php';
$wgAutoloadClasses['GatewayType'] = $donationinterface_dir . 'gateway_common/gateway.adapter.php';
$wgAutoloadClasses['DataValidator'] = $donationinterface_dir . 'gateway_common/DataValidator.php';
$wgAutoloadClasses['LogPrefixProvider'] = $donationinterface_dir . 'gateway_common/gateway.adapter.php';
$wgAutoloadClasses['NationalCurrencies'] = $donationinterface_dir . 'gateway_common/NationalCurrencies.php';
$wgAutoloadClasses['PaymentMethod'] = $donationinterface_dir . 'gateway_common/PaymentMethod.php';
$wgAutoloadClasses['PaymentResult'] = $donationinterface_dir . 'gateway_common/PaymentResult.php';
$wgAutoloadClasses['PaymentTransactionResponse'] = $donationinterface_dir . 'gateway_common/PaymentTransactionResponse.php';
$wgAutoloadClasses['ResponseCodes'] = $donationinterface_dir . 'gateway_common/ResponseCodes.php';
$wgAutoloadClasses['WmfFramework_Mediawiki'] = $donationinterface_dir . 'gateway_common/WmfFramework.mediawiki.php';
$wgAutoloadClasses['WmfFrameworkLogHandler'] = $donationinterface_dir . 'gateway_common/WmfFrameworkLogHandler.php';

//load all possible form classes
$wgAutoloadClasses['Gateway_Form'] = $donationinterface_dir . 'gateway_forms/Form.php';
$wgAutoloadClasses['Gateway_Form_Mustache'] = $donationinterface_dir . 'gateway_forms/Mustache.php';
$wgAutoloadClasses['Gateway_Form_RapidHtml'] = $donationinterface_dir . 'gateway_forms/RapidHtml.php';
$wgAutoloadClasses['CountryCodes'] = $donationinterface_dir . 'gateway_forms/includes/CountryCodes.php';
$wgAutoloadClasses['ProvinceAbbreviations'] = $donationinterface_dir . 'gateway_forms/includes/ProvinceAbbreviations.php';
$wgAutoloadClasses['StateAbbreviations'] = $donationinterface_dir . 'gateway_forms/includes/StateAbbreviations.php';

//GlobalCollect gateway classes
$wgAutoloadClasses['GlobalCollectGateway'] = $donationinterface_dir . 'globalcollect_gateway/globalcollect_gateway.body.php';
$wgAutoloadClasses['GlobalCollectGatewayResult'] = $donationinterface_dir . 'globalcollect_gateway/globalcollect_resultswitcher.body.php';

$wgAutoloadClasses['GlobalCollectAdapter'] = $donationinterface_dir . 'globalcollect_gateway/globalcollect.adapter.php';
$wgAutoloadClasses['GlobalCollectOrphanAdapter'] = __DIR__ . '/globalcollect_gateway/scripts/orphan_adapter.php';

// Amazon
$wgAutoloadClasses['AmazonGateway'] = $donationinterface_dir . 'amazon_gateway/amazon_gateway.body.php';
$wgAutoloadClasses['AmazonAdapter'] = $donationinterface_dir . 'amazon_gateway/amazon.adapter.php';

//Adyen
$wgAutoloadClasses['AdyenGateway'] = $donationinterface_dir . 'adyen_gateway/adyen_gateway.body.php';
$wgAutoloadClasses['AdyenGatewayResult'] = $donationinterface_dir . 'adyen_gateway/adyen_resultswitcher.body.php';
$wgAutoloadClasses['AdyenAdapter'] = $donationinterface_dir . 'adyen_gateway/adyen.adapter.php';

// Astropay
$wgAutoloadClasses['AstropayGateway'] = $donationinterface_dir . 'astropay_gateway/astropay_gateway.body.php';
$wgAutoloadClasses['AstropayGatewayResult'] = $donationinterface_dir . 'astropay_gateway/astropay_resultswitcher.body.php';
$wgAutoloadClasses['AstropayAdapter'] = $donationinterface_dir . 'astropay_gateway/astropay.adapter.php';

// Paypal
$wgAutoloadClasses['PaypalGateway'] = $donationinterface_dir . 'paypal_gateway/paypal_gateway.body.php';
$wgAutoloadClasses['PaypalGatewayResult'] = $donationinterface_dir . 'paypal_gateway/paypal_resultswitcher.body.php';
$wgAutoloadClasses['PaypalAdapter'] = $donationinterface_dir . 'paypal_gateway/paypal.adapter.php';

// WorldPay
$wgAutoloadClasses['WorldPayGateway'] = $donationinterface_dir . 'worldpay_gateway/worldpay_gateway.body.php';
$wgAutoloadClasses['WorldPayAdapter'] = $donationinterface_dir . 'worldpay_gateway/worldpay.adapter.php';

$wgAPIModules['di_wp_validate'] = 'WorldPayValidateApi';
$wgAutoloadClasses['WorldPayValidateApi'] = $donationinterface_dir . 'worldpay_gateway/worldpay.api.php';

//Extras classes - required for ANY optional class that is considered an "extra".
$wgAutoloadClasses['Gateway_Extras'] = $donationinterface_dir . "extras/extras.body.php";

//Custom Filters classes
$wgAutoloadClasses['Gateway_Extras_CustomFilters'] = $donationinterface_dir . "extras/custom_filters/custom_filters.body.php";

//Conversion Log classes
$wgAutoloadClasses['Gateway_Extras_ConversionLog'] = $donationinterface_dir . "extras/conversion_log/conversion_log.body.php";

$wgAutoloadClasses['Gateway_Extras_CustomFilters_MinFraud'] = $donationinterface_dir . "extras/custom_filters/filters/minfraud/minfraud.body.php";
$wgAutoloadClasses['Gateway_Extras_CustomFilters_Referrer'] = $donationinterface_dir . "extras/custom_filters/filters/referrer/referrer.body.php";
$wgAutoloadClasses['Gateway_Extras_CustomFilters_Source'] = $donationinterface_dir . "extras/custom_filters/filters/source/source.body.php";
$wgAutoloadClasses['Gateway_Extras_CustomFilters_Functions'] = $donationinterface_dir . "extras/custom_filters/filters/functions/functions.body.php";
$wgAutoloadClasses['Gateway_Extras_CustomFilters_IP_Velocity'] = $donationinterface_dir . "extras/custom_filters/filters/ip_velocity/ip_velocity.body.php";

$wgAutoloadClasses['Gateway_Extras_SessionVelocityFilter'] = $donationinterface_dir . "extras/session_velocity/session_velocity.body.php";
$wgAutoloadClasses['GatewayFormChooser'] = $donationinterface_dir . 'special/GatewayFormChooser.php';
$wgAutoloadClasses['SystemStatus'] = $donationinterface_dir . 'special/SystemStatus.php';

/**
 * GLOBALS
 */

/**
 * Global form dir
 */
$wgDonationInterfaceHtmlFormDir = dirname( __FILE__ ) . "/gateway_forms/rapidhtml/html";
$wgDonationInterfaceTest = false;

/**
 * Default top-level template file.
 */
$wgDonationInterfaceTemplate = __DIR__ . '/gateway_forms/mustache/index.html.mustache';

//all of the following variables make sense to override directly,
//or change "DonationInterface" to the gateway's id to override just for that gateway.
//for instance: To override $wgDonationInterfaceUseSyslog just for GlobalCollect, add
// $wgGlobalCollectGatewayUseSyslog = true
// to LocalSettings.
//

$wgDonationInterfaceDisplayDebug = false;
$wgDonationInterfaceUseSyslog = false;
$wgDonationInterfaceSaveCommStats = false;

$wgDonationInterfaceCSSVersion = 1;
$wgDonationInterfaceTimeout = 5;
$wgDonationInterfaceDefaultForm = 'RapidHtml';

/**
 * If set to a currency code, gateway forms will try to convert amounts
 * in unsupported currencies to the fallback instead of just showing
 * an unsupported currency error.
 */
$wgDonationInterfaceFallbackCurrency = false;

/**
 * When this is true and an unsupported currency has been converted to the
 * fallback (see above), we show an interstitial page notifying the user
 * of the conversion before sending the donation to the gateway.
 */
$wgDonationInterfaceNotifyOnConvert = true;

/**
 * A string or array of strings for making tokens more secure
 *
 * Please set this!  If you do not, tokens are easy to get around, which can
 * potentially leave you and your users vulnerable to CSRF or other forms of
 * attack.
 */
$wgDonationInterfaceSalt = $wgSecretKey;

/**
 * A string that can contain wikitext to display at the head of the credit card form
 *
 * This string gets run like so: $wg->addHtml( $wg->Parse( $wgGlobalCollectGatewayHeader ))
 * You can use '@language' as a placeholder token to extract the user's language.
 *
 */
$wgDonationInterfaceHeader = NULL;

/**
 * A string containing full URL for Javascript-disabled credit card form redirect
 */
$wgDonationInterfaceNoScriptRedirect = null;

/**
 * Configure price ceiling and floor for valid contribution amount.  Values
 * should be in USD.
 */
$wgDonationInterfacePriceFloor = 1.00;
$wgDonationInterfacePriceCeiling = 10000.00;

/**
 * Default Thank You and Fail pages for all of donationinterface - language will be calc'd and appended at runtime.
 */
//$wgDonationInterfaceThankYouPage = 'https://wikimediafoundation.org/wiki/Thank_You';
$wgDonationInterfaceThankYouPage = 'Donate-thanks';
$wgDonationInterfaceFailPage = 'Donate-error'; 

/**
 * Retry Loop Count - If there's a place where the API can choose to loop on some retry behavior, do it this number of times. 
 */
$wgDonationInterfaceRetryLoopCount = 3;

/**
 * Orphan Cron settings global
 */
$wgDonationInterfaceOrphanCron = array(
	'enable' => true,
//	'override_command_line_params' => true,
//	'function' => 'orphan_stomp',
//	'target_execute_time' => 300,
//	'max_per_execute' => '',
);

/**
 * Forbidden countries. No donations will be allowed to come in from countries 
 * in this list.
 * All should be represented as all-caps ISO 3166-1 alpha-2
 * This one global shouldn't ever be overridden per gateway. As it's probably 
 * going to only conatin countries forbidden by law, there's no reason
 * to override by gateway and as such it's always referenced directly. 
 */
$wgDonationInterfaceForbiddenCountries = array();

/**
 * 3D Secure enabled currencies (and countries) for Credit Card.
 * An array in the form of currency => array of countries 
 * (all-caps ISO 3166-1 alpha-2), or an empty array for all transactions in that
 * currency regardless of country of origin.
 * As this is a mandatroy check for all INR transactions, that rule made it into
 * the default.  
 */
$wgDonationInterface3DSRules = array(
	'INR' => array(), //all countries
);

//GlobalCollect gateway globals
$wgGlobalCollectGatewayURL = 'https://ps.gcsip.nl/wdl/wdl';
$wgGlobalCollectGatewayTestingURL = 'https://'; // GlobalCollect testing URL

#	$wgGlobalCollectGatewayAccountInfo['example'] = array(
#		'MerchantID' => '', // GlobalCollect ID
#	);

$wgGlobalCollectGatewayHtmlFormDir = $donationinterface_dir . 'globalcollect_gateway/forms/html';

$wgGlobalCollectGatewayCvvMap = array(
	'M' => true, //CVV check performed and valid value.
	'N' => false, //CVV checked and no match.
	'P' => true, //CVV check not performed, not requested
	'S' => false, //Card holder claims no CVV-code on card, issuer states CVV-code should be on card. 
	'U' => true, //? //Issuer not certified for CVV2.
	'Y' => false, //Server provider did not respond.
	'0' => true, //No service available.
	'' => false, //No code returned. All the points.
);

$wgGlobalCollectGatewayAvsMap = array(
	'A' => 50, //Address (Street) matches, Zip does not.
	'B' => 50, //Street address match for international transactions. Postal code not verified due to incompatible formats.
	'C' => 50, //Street address and postal code not verified for international transaction due to incompatible formats.
	'D' => 0, //Street address and postal codes match for international transaction.
	'E' => 100, //AVS Error.
	'F' => 0, //Address does match and five digit ZIP code does match (UK only).
	'G' => 50, //Address information is unavailable; international transaction; non-AVS participant. 
	'I' => 50, //Address information not verified for international transaction.
	'M' => 0, //Street address and postal codes match for international transaction.
	'N' => 100, //No Match on Address (Street) or Zip.
	'P' => 50, //Postal codes match for international transaction. Street address not verified due to incompatible formats.
	'R' => 100, //Retry, System unavailable or Timed out.
	'S' => 50, //Service not supported by issuer.
	'U' => 50, //Address information is unavailable.
	'W' => 50, //9 digit Zip matches, Address (Street) does not.
	'X' => 0, //Exact AVS Match.
	'Y' => 0, //Address (Street) and 5 digit Zip match.
	'Z' => 50, //5 digit Zip matches, Address (Street) does not.
	'0' => 25, //No service available.
	'' => 100, //No code returned. All the points.
);	


//n.b. "-Testing-" urls are not wired to anything, they're just here for
// your copy n paste pleasure.

$wgAmazonGatewayURL = "https://authorize.payments.amazon.com/pba/paypipeline";
$wgAmazonGatewayTestingURL = "https://authorize.payments-sandbox.amazon.com/pba/paypipeline";

$wgAmazonGatewayFpsURL = "https://fps.amazonaws.com/";
$wgAmazonGatewayFpsTestingURL = "https://fps.sandbox.amazonaws.com/";

#	$wgAmazonGatewayAccountInfo['example'] = array(
#		'AccessKey' => "",
#		'SecretKey' => "",
#
#		// the long one, not the AWS account ID
#		'PaymentsAccountID' => "",
#	);

// e.g. http://payments.wikimedia.org/index.php/Special:AmazonGateway  --
// does NOT accept unroutable development names, use the number instead
// even if it's 127.0.0.1
$wgAmazonGatewayReturnURL = "";

$wgAmazonGatewayHtmlFormDir = $donationinterface_dir . 'amazon_gateway/forms/html';

$wgPaypalGatewayURL = 'https://www.paypal.com/cgi-bin/webscr';
$wgPaypalGatewayTestingURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$wgPaypalGatewayReturnURL = ''; //'http://127.0.0.1/index.php/Special:PaypalGatewayResult';
$wgPaypalGatewayRecurringLength = '0'; // 0 should mean forever

$wgPaypalGatewayHtmlFormDir = $donationinterface_dir . 'paypal_gateway/forms/html';

$wgPaypalGatewayXclickCountries = array();

#	$wgPaypalGatewayAccountInfo['example'] = array(
#		'AccountEmail' => "",
#	);

$wgAdyenGatewayHtmlFormDir = $donationinterface_dir . 'adyen_gateway/forms/html';

$wgAdyenGatewayBaseURL = 'https://live.adyen.com';
$wgAdyenGatewayBaseTestingURL = 'https://test.adyen.com'; // unused

#	$wgAdyenGatewayAccountInfo['example'] = array(
#		'AccountName' => ''; // account identifier, not login name
#		'SharedSecret' => ''; // entered in the skin editor
#		'SkinCode' => '';
#	);

$wgAstropayGatewayHtmlFormDir = $donationinterface_dir . 'astropay_gateway/forms/html';
// Set base URLs here.  Individual transactions have their own paths
$wgAstropayGatewayURL = 'https://astropaycard.com/';
$wgAstropayGatewayTestingURL = 'https://sandbox.astropaycard.com/';
#	$wgAstropayGatewayAccountInfo['example'] = array(
#		'Create' => array( // For creating invoices
#			'Login' => '',
#			'Password' => '',
#		),
#		'Status' => array( // For checking payment status
#			'Login' => '',
#			'Password' => '',
#		),
#		'SecretKey' => '', // For signing requests and verifying responses
#	);

$wgWorldPayGatewayHtmlFormDir = $donationinterface_dir . 'worldpay_gateway/forms/html';

$wgWorldPayGatewayURL = 'https://some.url.here';

/**
 * Set this to true if fraud checks should be disabled for integration testing
 */
$wgWorldPayGatewayNoFraudIntegrationTest = false;

/*
$wgWorldPayGatewayAccountInfo['default'] = array(
	'Test' => 1,
	'MerchantId' => 00000,
	'Username' => 'suchuser',
	'Password' => 'suchsecret',

	'DefaultCurrency' => CURRENCY

	'StoreIDs' => array(
		CURRENCY => StoreID
	),
);
*/

$wgWorldPayGatewayCvvMap = array (
	'0' => false, //No Match
	'1' => true, //Match
	'2' => false, //Not Checked
	'3' => false, //Issuer is Not Certified or Unregistered
	'4' => false, //Should have CVV2 on card - ??
	'5' => false, //CVC1 Incorrect
	'6' => false, //No service available.
	'7' => false, //No code returned. All the points.
	'8' => false, //No code returned. All the points.
	'9' => false, //Not Performed
	//(occurs when CVN value was not present in the STN string
	//or when transaction was not sent to the acquiring bank)
	'' => false, //No code returned. All the points.
);

$wgWorldPayGatewayAvsAddressMap = array (
	'0' => 50, //No Match
	'1' => 0, //Match
	'2' => 12, //Not Checked/Not Available
	'3' => 50, //Issuer is Not Certified or Unregistered
	'4' => 12, //Not Supported
	'9' => 12, //Not Performed (occurs when Address1, Address2 and Address3 values were not present in the STN string or when transaction was not sent to the acquiring bank)
	'' => 50, //No code returned. All the points.
);

$wgWorldPayGatewayAvsZipMap = array (
	'0' => 50, //No Match
	'1' => 0, //Match
	'2' => 12, //Not Checked/Not Available
	'3' => 0, //9 digit zipcode match
	'4' => 0, //5 digit zipcode match
	'5' => 12, //Not Supported
	'9' => 12, //Not Performed (occurs when ZipCode value was not present in the STN string or when transaction was not sent to the acquiring bank)
	'' => 50, //No code returned. All the points.
);

$wgStompServer = "";

// In this array, 'default', 'pending', and 'limbo' are required keys for those categories of
// transactions. The value is the name of the queue. To single out a transaction type, ie:
// credit cards, prepend 'cc-' to the base key name.
//
// If the resultant queue name evaluates to false, the message will not be queued on the server.
$wgStompQueueNames = array(
	'default' => 'test-default',    // Previously known as $wgStompQueueName
	'pending' => 'test-pending',    // Previously known as $wgPendingStompQueueName
	'limbo' => 'test-limbo', // Previously known as $wgLimboStompQueueName
	'payments-antifraud' => 'payments-antifraud', //noncritical: Basically shoving the fraud log into a database.
	'payments-init' => 'payments-init', //noncritical: same as above with the payments-initial log
);

/**
 * @global array $wgDonationInterfaceDefaultQueueServer
 *
 * Common development defaults for the queue server.
 * TODO: Default to a builtin backend such as PDO?
 */
$wgDonationInterfaceDefaultQueueServer = array(
	'type' => '\PHPQueue\Backend\Stomp',
	'uri' => 'tcp://localhost:61613',
	'read_timeout' => '1',
	'expiry' => '30 days',
);

/**
 * @global array $wgDonationInterfaceQueues
 *
 * This is a mapping from queue name to attributes.  It's not necessary to
 * list queues here, but the built-in queues are listed for convenience.
 *
 * Default values are taken from $wgDonationInterfaceDefaultQueueServer, and
 * values given here will override the defaults.
 *
 * The array key is the queue name as it is referred to from code, although the
 * actual queue name used in the backend may be overridden, see below.
 *
 * Unrecognized options will be passed along to the queue backend constructor,
 * but the following have special meaning to DonationQueue:
 *     type - Class name of the queue backend.
 *     expiry - The default lifespan of messages in this queue (days).
 *     name - Backend can map to a named queue, rather than default to the
 *         queue key as it appears in the $wgDonationInterfaceQueues array.
 */
$wgDonationInterfaceQueues = array(
	// Incoming donations that we think have been paid for.
	'completed' => array(),
	// So-called limbo queue for GlobalCollect, where we store donor personal
	// information while waiting for the donor to return from iframe or a
	// redirect.  It's very important that this data is not stored anywhere
	// permanent such as logs or the database, until we know this person
	// finished making a donation.
	'globalcollect-cc-limbo' => array(),
	// The general limbo queue (see above). FIXME: deprecated?
	'limbo' => array(),
	// Where limbo messages go to die, if the orphan slayer decides they are
	// still in one of the pending states.  FIXME: who reads from this queue?
	'pending' => array(),

	// Non-critical queues

	// These messages will be shoved into the fraud database (see
	// crm/modules/fredge).
	'payments-antifraud' => array(),
	// These are shoved into the payments-initial database.
	'payments-init' => array(),
);

//Custom Filters globals
//Define the action to take for a given $risk_score
$wgDonationInterfaceCustomFiltersActionRanges = array(
	'process' => array( 0, 100 ),
	'review' => array( -1, -1 ),
	'challenge' => array( -1, -1 ),
	'reject' => array( -1, -1 ),
);

/**
 * A value for tracking the 'riskiness' of a transaction
 *
 * The action to take based on a transaction's riskScore is determined by
 * $action_ranges.  This is built assuming a range of possible risk scores
 * as 0-100, although you can probably bend this as needed.
 */
$wgDonationInterfaceCustomFiltersRiskScore = 0;

//Minfraud globals
/**
 * Your minFraud license key.
 */
$wgMinFraudLicenseKey = '';

/**
 * Set the risk score ranges that will cause a particular 'action'
 *
 * The keys to the array are the 'actions' to be taken (eg 'process').
 * The value for one of these keys is an array representing the lower
 * and upper bounds for that action.  For instance,
 *   $wgDonationInterfaceMinFraudActionRanges = array(
 * 		'process' => array( 0, 100)
 * 		...
 * 	);
 * means that any transaction with a risk score greather than or equal
 * to 0 and less than or equal to 100 will be given the 'process' action.
 *
 * These are evauluated on a >= or <= basis.  Please refer to minFraud
 * documentation for a thorough explanation of the 'riskScore'.
 */
$wgDonationInterfaceMinFraudActionRanges = array(
	'process' => array( 0, 100 ),
	'review' => array( -1, -1 ),
	'challenge' => array( -1, -1 ),
	'reject' => array( -1, -1 )
);

/**
 * This allows setting where to point the minFraud servers.
 *
 * As of February 21st, 2012 minfraud.maxmind.com will route to the east or
 * west server, depending on you location.
 *
 * minfraud-us-east.maxmind.com: 174.36.207.186
 * minfraud-us-west.maxmind.com: 50.97.220.226
 *
 * The minFraud API requires an array of servers.
 *
 * You do not have to specify a server.
 *
 * @see CreditCardFraudDetection::$server
 */
$wgDonationInterfaceMinFraudServers = array();

// Timeout in seconds for communicating with MaxMind
$wgMinFraudTimeout = 2;

/**
 * When to send an email to $wgEmergencyContact that we're
 * running low on minfraud queries. Will continue to send
 * once per day until the limit is once again over the limit.
 */
$wgDonationInterfaceMinFraudAlarmLimit = 25000;

//Referrer Filter globals
$wgDonationInterfaceCustomFiltersRefRules = array();

//Source Filter globals
$wgDonationInterfaceCustomFiltersSrcRules = array();

//Functions Filter globals
$wgDonationInterfaceCustomFiltersFunctions = array();

//IP velocity filter globals
$wgDonationInterfaceMemcacheHost = 'localhost';
$wgDonationInterfaceMemcachePort = '11211';
$wgDonationInterfaceIPVelocityFailScore = 100;
$wgDonationInterfaceIPVelocityTimeout = 60 * 5;	//5 minutes in seconds
$wgDonationInterfaceIPVelocityThreshhold = 3;	//3 transactions per timeout
//$wgDonationInterfaceIPVelocityToxicDuration can be set to penalize IP addresses
//that attempt to use cards reported stolen.
//$wgDonationInterfaceIPVelocityFailDuration is also something you can set...
//If you leave it blank, it will use the VelocityTimeout as a default.

// Session velocity filter globals
$wgDonationInterfaceSessionVelocity_HitScore = 10;  // How much to add to the score per API hit
$wgDonationInterfaceSessionVelocity_DecayRate = 1;  // Linear decay rate; pts / sec
$wgDonationInterfaceSessionVelocity_Threshold = 50; // Above this score, we deny users the page

/**
 * $wgDonationInterfaceCountryMap
 *
 * A score of 0 for a country means no risk.
 * A score of 100 means this country is extremely risky for fraud.
 *
 * The score for a country has the following range:
 *
 * 0 <= $score <= 100
 *
 * To enable this filter add this to your LocalSettings.php:
 *
 * @code
 * <?php
 *
 * $wgCustomFiltersFunctions = array(
 * 	'getScoreCountryMap' => 100,
 * );
 *
 * $wgDonationInterfaceCountryMap = array(
 * 	'CA' =>  1,
 * 	'US' => 5,
 * );
 * ?>
 * @endcode
 */
$wgDonationInterfaceCountryMap = array();

/**
 * $wgDonationInterfaceEmailDomainMap
 *
 * A score of 0 for an email domain means no risk.
 * A score of 100 means this email domain is extremely risky for fraud.
 * Scores may be negative.
 *
 * To enable this filter add this to your LocalSettings.php:
 *
 * @code
 * <?php
 *
 * $wgCustomFiltersFunctions = array(
 * 	'getScoreEmailDomainMap' => 100,
 * );
 *
 * $wgDonationInterfaceEmailDomainMap = array(
 * 	'gmail.com' =>  5,
 * 	'wikimedia.org' => 0,
 * );
 * ?>
 * @endcode
 */
$wgDonationInterfaceEmailDomainMap = array();

/**
 * $wgDonationInterfaceUtmCampaignMap
 *
 * A score of 0 for utm_campaign means no risk.
 * A score of 100 means this utm_campaign is extremely risky for fraud.
 * Scores may be negative
 *
 * To enable this filter add this to your LocalSettings.php:
 *
 * @code
 * <?php
 *
 * $wgCustomFiltersFunctions = array(
 * 	'getScoreUtmCampaignMap' => 100,
 * );
 *
 * $wgDonationInterfaceUtmCampaignMap = array(
 * 	'' =>  20,
 * 	'some-odd-string' => 100,
 * );
 * ?>
 * @endcode
 */
$wgDonationInterfaceUtmCampaignMap = array();

/**
 * $wgDonationInterfaceUtmMediumMap
 *
 * A score of 0 for utm_medium means no risk.
 * A score of 100 means this utm_medium is extremely risky for fraud.
 * Scores may be negative
 *
 * To enable this filter add this to your LocalSettings.php:
 *
 * @code
 * <?php
 *
 * $wgCustomFiltersFunctions = array(
 * 	'getScoreUtmMediumMap' => 100,
 * );
 *
 * $wgDonationInterfaceUtmMediumMap = array(
 * 	'' =>  20,
 * 	'some-odd-string' => 100,
 * );
 * ?>
 * @endcode
 */
$wgDonationInterfaceUtmMediumMap = array();

/**
 * $wgDonationInterfaceUtmSourceMap
 *
 * A score of 0 for utm_source means no risk.
 * A score of 100 means this utm_source is extremely risky for fraud.
 * Scores may be negative
 *
 * To enable this filter add this to your LocalSettings.php:
 *
 * @code
 * <?php
 *
 * $wgCustomFiltersFunctions = array(
 * 	'getScoreUtmSourceMap' => 100,
 * );
 *
 * $wgDonationInterfaceUtmSourceMap = array(
 * 	'' =>  20,
 * 	'some-odd-string' => 100,
 * );
 * ?>
 * @endcode
 */
$wgDonationInterfaceUtmSourceMap = array();

// Perform initialization that depends on user configuration.
$wgExtensionFunctions[] = function() {
	global $wgDonationInterfaceEnabledGateways,
		$wgDonationInterfaceEnableCustomFilters,
		$wgSpecialPages,
		$wgHooks,
		$wgDonationInterfaceFormDirs,
		$wgDonationInterfaceHtmlFormDir,
		$wgAdyenGatewayHtmlFormDir,
		$wgAmazonGatewayHtmlFormDir,
		$wgGlobalCollectGatewayHtmlFormDir,
		$wgPaypalGatewayHtmlFormDir,
		$wgPayflowProGatewayHtmlFormDir,
		$wgWorldPayGatewayHtmlFormDir;

	/**
	 * Figure out what we've got enabled.
	 */
	$optionalParts = array( //define as fail closed.
		'CustomFilters' => false, //Gets set if at least one filter is enabled.
		'Stomp' => false,
		'Queue' => false,
		'ConversionLog' => false, //this is definitely an Extra
		'Minfraud' => true, //this is definitely an Extra
		'GlobalCollect' => true,
		'Amazon' => true,
		'Adyen' => true,
		'Astropay' => true,
		'Paypal' => true,
		'WorldPay' => true,
		'FormChooser' => true,
		'ReferrerFilter' => true, //extra
		'SourceFilter' => true, //extra
		'FunctionsFilter' => true, //extra
		'IPVelocityFilter' => false, //extra
		'SessionVelocityFilter' => false, //extra
		'SystemStatus' => false, //extra
	);

	// FIXME: Crude plugin type mechanism.
	$customFilters = array(
		'ReferrerFilter',
		'SourceFilter',
		'Minfraud',
		'IPVelocityFilter',
		'SessionVelocityFilter',
	);

	foreach ($optionalParts as $subextension => $enabled){
		$globalname = 'wgDonationInterfaceEnable' . $subextension;
		global $$globalname;
		if ( isset( $$globalname ) ) {
			$optionalParts[$subextension] = $$globalname;
		}

		if ( $optionalParts[$subextension] === true ) {
			//this is still annoying.
			if ( in_array( $subextension, $customFilters ) ) {
				$optionalParts['CustomFilters'] = true;
				$wgDonationInterfaceEnableCustomFilters = true; //override this for specific gateways to disable
			}
		}
	}

	/**
	 * SPECIAL PAGES
	 */
	if ( $optionalParts['FormChooser'] === true ){
		$wgSpecialPages['GatewayFormChooser'] = 'GatewayFormChooser';
	}
	if ( $optionalParts['SystemStatus'] === true ){
		$wgSpecialPages['SystemStatus'] = 'SystemStatus';
	}

	//GlobalCollect gateway special pages
	if ( $optionalParts['GlobalCollect'] === true ){
		$wgSpecialPages['GlobalCollectGateway'] = 'GlobalCollectGateway';
		$wgSpecialPages['GlobalCollectGatewayResult'] = 'GlobalCollectGatewayResult';
		$wgDonationInterfaceEnabledGateways[] = 'globalcollect';
	}
	//Amazon Simple Payment gateway special pages
	if ( $optionalParts['Amazon'] === true ){
		$wgSpecialPages['AmazonGateway'] = 'AmazonGateway';
		$wgDonationInterfaceEnabledGateways[] = 'amazon';
	}
	//Adyen gateway special pages
	if ( $optionalParts['Adyen'] === true ){
		$wgSpecialPages['AdyenGateway'] = 'AdyenGateway';
		$wgSpecialPages['AdyenGatewayResult'] = 'AdyenGatewayResult';
		$wgDonationInterfaceEnabledGateways[] = 'adyen';
	}
	//Astropay gateway special pages
	if ( $optionalParts['Astropay'] === true ){
		$wgSpecialPages['AstropayGateway'] = 'AstropayGateway';
		$wgSpecialPages['AstropayGatewayResult'] = 'AstropayGatewayResult';
		$wgDonationInterfaceEnabledGateways[] = 'astropay';
	}
	//PayPal
	if ( $optionalParts['Paypal'] === true ){
		$wgSpecialPages['PaypalGateway'] = 'PaypalGateway';
		$wgSpecialPages['PaypalGatewayResult'] = 'PaypalGatewayResult';
		$wgDonationInterfaceEnabledGateways[] = 'paypal';
	}
	//WorldPay
	if ( $optionalParts['WorldPay'] === true ){
		$wgSpecialPages['WorldPayGateway'] = 'WorldPayGateway';
		$wgDonationInterfaceEnabledGateways[] = 'worldpay';
	}

	//Stomp hooks
	if ( $optionalParts['Stomp'] === true ) {
		$wgHooks['ParserFirstCallInit'][] = 'efStompSetup';
		$wgHooks['gwStomp'][] = 'sendSTOMP';
		$wgHooks['gwPendingStomp'][] = 'sendPendingSTOMP';
		$wgHooks['gwFreeformStomp'][] = 'sendFreeformSTOMP';
	}

	//Custom Filters hooks
	if ( $optionalParts['CustomFilters'] === true ) {
		$wgHooks["GatewayValidate"][] = array( 'Gateway_Extras_CustomFilters::onValidate' );
	}

	//Referrer Filter hooks
	if ( $optionalParts['ReferrerFilter'] === true ){
		$wgHooks["GatewayCustomFilter"][] = array( 'Gateway_Extras_CustomFilters_Referrer::onFilter' );
	}

	//Source Filter hooks
	if ( $optionalParts['SourceFilter'] === true ){
		$wgHooks["GatewayCustomFilter"][] = array( 'Gateway_Extras_CustomFilters_Source::onFilter' );
	} 

	//Functions Filter hooks
	if ( $optionalParts['FunctionsFilter'] === true ){
		$wgHooks["GatewayCustomFilter"][] = array( 'Gateway_Extras_CustomFilters_Functions::onFilter' );
	} 

	//Minfraud as Filter globals
	if ( $optionalParts['Minfraud'] === true ){
		$wgHooks["GatewayCustomFilter"][] = array( 'Gateway_Extras_CustomFilters_MinFraud::onFilter' );
	}

	//Conversion Log hooks
	if ($optionalParts['ConversionLog'] === true){
		// Sets the 'conversion log' as logger for post-processing
		$wgHooks["GatewayPostProcess"][] = array( "Gateway_Extras_ConversionLog::onPostProcess" );
	}

	//Functions Filter hooks
	if ( $optionalParts['IPVelocityFilter'] === true ){
		$wgHooks["GatewayCustomFilter"][] = array( 'Gateway_Extras_CustomFilters_IP_Velocity::onFilter' );
		$wgHooks["GatewayPostProcess"][] = array( 'Gateway_Extras_CustomFilters_IP_Velocity::onPostProcess' );
	}

	if ( $optionalParts['SessionVelocityFilter'] === true ) {
		$wgHooks['DonationInterfaceCurlInit'][] = array( 'Gateway_Extras_SessionVelocityFilter::onCurlInit' );
	}

	// Set up form template directories.
	$form_dirs = array(
		'default' => $wgDonationInterfaceHtmlFormDir,
		'gc' => $wgGlobalCollectGatewayHtmlFormDir,
		'paypal' => $wgPaypalGatewayHtmlFormDir,
		'amazon' => $wgAmazonGatewayHtmlFormDir,
	//	'pfp' => $wgPayflowProGatewayHtmlFormDir,
	);

	if ( $wgDonationInterfaceEnableAdyen === true ) {
		$form_dirs['adyen'] = $wgAdyenGatewayHtmlFormDir;
	}
	if ( $wgDonationInterfaceEnableWorldPay === true ) {
		$form_dirs['worldpay'] = $wgWorldPayGatewayHtmlFormDir;
	}
	$wgDonationInterfaceFormDirs = array_merge(
		$form_dirs,
		$wgDonationInterfaceFormDirs
	);

	// Load the default form settings
	require_once __DIR__ . '/DonationInterfaceFormSettings.php';
};

//Unit tests
$wgHooks['UnitTestsList'][] = 'efDonationInterfaceUnitTests';

/**
 * APIS
 */
// enable the API
$wgAPIModules['donate'] = 'DonationApi';
$wgAutoloadClasses['DonationApi'] = $donationinterface_dir . 'gateway_common/donation.api.php';


/**
 * ADDITIONAL MAGICAL GLOBALS
 */

// Resource modules
$wgResourceTemplate = array(
	'localBasePath' => $donationinterface_dir . 'modules',
	'remoteExtPath' => 'DonationInterface/modules',
);
$wgResourceModules['iframe.liberator'] = array(
	'scripts' => 'iframe.liberator.js',
	'position' => 'top'
	) + $wgResourceTemplate;

$wgResourceModules['donationInterface.skinOverride'] = array(
	'scripts' => 'js/skinOverride.js',
	'styles' => array(
		'css/gateway.css',
		'css/skinOverride.css',
	),
	'position' => 'top'
	) + $wgResourceTemplate;

$wgResourceModules['donationInterface.test.rapidhtml'] = array(
	'scripts' => 'tests/modules/gc.testinterface.js',
	'dependencies' => array(
		'mediawiki.Uri',
		'gc.normalinterface'
	)
) + $wgResourceTemplate;

$wgResourceModules['jquery.payment'] = array(
	'scripts' => 'jquery.payment/jquery.payment.js',
) + $wgResourceTemplate;;

// load any rapidhtml related resources
require_once( $donationinterface_dir . 'gateway_forms/rapidhtml/RapidHtmlResources.php' );


$wgResourceTemplate = array(
	'localBasePath' => $donationinterface_dir . 'gateway_forms',
	'remoteExtPath' => 'DonationInterface/gateway_forms',
);

$wgResourceModules[ 'ext.donationInterface.errorMessages' ] = array(
	'messages' => array(
		'donate_interface-noscript-msg',
		'donate_interface-noscript-redirect-msg',
		'donate_interface-error-msg-general',
		'donate_interface-error-msg-js',
		'donate_interface-error-msg-validation',
		'donate_interface-error-msg-invalid-amount',
		'donate_interface-error-msg-email',
		'donate_interface-error-msg-card-num',
		'donate_interface-error-msg-amex',
		'donate_interface-error-msg-mc',
		'donate_interface-error-msg-visa',
		'donate_interface-error-msg-discover',
		'donate_interface-error-msg-amount',
		'donate_interface-error-msg-emailAdd',
		'donate_interface-error-msg-fname',
		'donate_interface-error-msg-lname',
		'donate_interface-error-msg-street',
		'donate_interface-error-msg-city',
		'donate_interface-error-msg-state',
		'donate_interface-error-msg-zip',
		'donate_interface-error-msg-postal',
		'donate_interface-error-msg-country',
		'donate_interface-error-msg-card_type',
		'donate_interface-error-msg-card_num',
		'donate_interface-error-msg-expiration',
		'donate_interface-error-msg-cvv',
		'donate_interface-error-msg-fiscal_number',
		'donate_interface-error-msg-captcha',
		'donate_interface-error-msg-captcha-please',
		'donate_interface-error-msg-cookies',
		'donate_interface-error-msg-account_name',
		'donate_interface-error-msg-account_number',
		'donate_interface-error-msg-authorization_id',
		'donate_interface-error-msg-bank_check_digit',
		'donate_interface-error-msg-bank_code',
		'donate_interface-error-msg-branch_code',
		'donate_interface-smallamount-error',
		'donate_interface-donor-fname',
		'donate_interface-donor-lname',
		'donate_interface-donor-street',
		'donate_interface-donor-city',
		'donate_interface-donor-state',
		'donate_interface-donor-zip',
		'donate_interface-donor-postal',
		'donate_interface-donor-country',
		'donate_interface-donor-emailAdd',
		'donate_interface-state-province',
		'donate_interface-cvv-explain',
	)
);

// minimum amounts for all currencies
$wgResourceModules[ 'di.form.core.minimums' ] = array(
	'scripts' => 'validate.currencyMinimums.js',
	'localBasePath' => $donationinterface_dir . 'modules',
	'remoteExtPath' => 'DonationInterface/modules'
);

// form validation resource
$wgResourceModules[ 'di.form.core.validate' ] = array(
	'scripts' => 'validate_input.js',
	'dependencies' => array( 'di.form.core.minimums', 'ext.donationInterface.errorMessages' ),
	'localBasePath' => $donationinterface_dir . 'modules',
	'remoteExtPath' => 'DonationInterface/modules'
);


// Load the interface messages that are shared across multiple gateways
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/gateway_common/i18n/interface';
$wgExtensionMessagesFiles['DonateInterface'] = $donationinterface_dir . 'gateway_common/interface.i18n.php';
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/gateway_common/i18n/country-specific';
$wgExtensionMessagesFiles['DonateInterfaceAlt'] = $donationinterface_dir . 'gateway_common/country.specific.i18n.php';
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/gateway_common/i18n/countries';
$wgExtensionMessagesFiles['GatewayCountries'] = $donationinterface_dir . 'gateway_common/countries.i18n.php';
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/gateway_common/i18n/us-states';
$wgExtensionMessagesFiles['GatewayUSStates'] = $donationinterface_dir . 'gateway_common/us-states.i18n.php';
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/gateway_common/i18n/canada-provinces';
$wgExtensionMessagesFiles['GatewayCAProvinces'] = $donationinterface_dir . 'gateway_common/canada-provinces.i18n.php';
$wgExtensionMessagesFiles['GatewayAliases'] = $donationinterface_dir . 'DonationInterface.alias.php';

$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/amazon_gateway/i18n';
$wgExtensionMessagesFiles['AmazonGateway'] = $donationinterface_dir . 'amazon_gateway/amazon_gateway.i18n.php';
$wgExtensionMessagesFiles['AmazonGatewayAlias'] = $donationinterface_dir . 'amazon_gateway/amazon_gateway.alias.php';

//GlobalCollect gateway magical globals
// @todo All the bits where we make the i18n make sense for multiple gateways. This is clearly less than ideal.
$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/globalcollect_gateway/i18n';
$wgExtensionMessagesFiles['GlobalCollectGateway'] = $donationinterface_dir . 'globalcollect_gateway/globalcollect_gateway.i18n.php';
$wgExtensionMessagesFiles['GlobalCollectGatewayAlias'] = $donationinterface_dir . 'globalcollect_gateway/globalcollect_gateway.alias.php';

$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/adyen_gateway/i18n';
$wgExtensionMessagesFiles['AdyenGateway'] = $donationinterface_dir . 'adyen_gateway/adyen_gateway.i18n.php';
$wgExtensionMessagesFiles['AdyenGatewayAlias'] = $donationinterface_dir . 'adyen_gateway/adyen_gateway.alias.php';

$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/astropay_gateway/i18n';
$wgExtensionMessagesFiles['AstropayGateway'] = $donationinterface_dir . 'astropay_gateway/astropay_gateway.i18n.php';
$wgExtensionMessagesFiles['AstropayGatewayAlias'] = $donationinterface_dir . 'astropay_gateway/astropay_gateway.alias.php';

$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/paypal_gateway/i18n';
$wgExtensionMessagesFiles['PaypalGateway'] = $donationinterface_dir . 'paypal_gateway/paypal_gateway.i18n.php';
$wgExtensionMessagesFiles['PaypalGatewayAlias'] = $donationinterface_dir . 'paypal_gateway/paypal_gateway.alias.php';

$wgMessagesDirs['DonationInterface'][] = __DIR__ . '/worldpay_gateway/i18n';
$wgExtensionMessagesFiles['WorldPayGateway'] = $donationinterface_dir . 'worldpay_gateway/worldpay_gateway.i18n.php';
$wgExtensionMessagesFiles['WorldPayGatewayAlias'] = $donationinterface_dir . 'worldpay_gateway/worldpay_gateway.alias.php';

/**
 * See default values in DonationInterfaceFormSettings.php.  Note that any values
 * set in LocalSettings.php are array_merged into the defaults, which allows you
 * to override specific forms.  Please completely specify forms when overriding,
 * or disable by setting to an empty array or false.
 */
$wgDonationInterfaceAllowedHtmlForms = array();

/**
 * Base directories for each gateway's form templates.
 */
$wgDonationInterfaceFormDirs = array();

/**
 * FUNCTIONS
 */

//---Stomp functions---
// TODO: Encapsulate in a class, or deprecate.
require_once( $donationinterface_dir . 'activemq_stomp/activemq_stomp.php'  );
$wgAutoloadClasses['Stomp'] = $donationinterface_dir . 'activemq_stomp/Stomp.php';

function efDonationInterfaceUnitTests( &$files ) {
	global $wgAutoloadClasses;

	$testDir = __DIR__ . '/tests/';

	$files[] = $testDir . 'AllTests.php';

	$wgAutoloadClasses['DonationInterfaceTestCase'] = $testDir . 'DonationInterfaceTestCase.php';
	$wgAutoloadClasses['TestingQueue'] = $testDir . 'includes/TestingQueue.php';
	$wgAutoloadClasses['TestingAdyenAdapter'] = $testDir . 'includes/test_gateway/TestingAdyenAdapter.php';
	$wgAutoloadClasses['TestingAmazonAdapter'] = $testDir . 'includes/test_gateway/TestingAmazonAdapter.php';
	$wgAutoloadClasses['TestingAstropayAdapter'] = $testDir . 'includes/test_gateway/TestingAstropayAdapter.php';
	$wgAutoloadClasses['TestingAmazonGateway'] = $testDir . 'includes/test_page/TestingAmazonGateway.php';
	$wgAutoloadClasses['TestingDonationLogger'] = $testDir . 'includes/TestingDonationLogger.php';
	$wgAutoloadClasses['TestingGatewayPage'] = $testDir . 'includes/TestingGatewayPage.php';
	$wgAutoloadClasses['TestingGenericAdapter'] = $testDir . 'includes/test_gateway/TestingGenericAdapter.php';
	$wgAutoloadClasses['TestingGlobalCollectAdapter'] = $testDir . 'includes/test_gateway/TestingGlobalCollectAdapter.php';
	$wgAutoloadClasses['TestingGlobalCollectGateway'] = $testDir . 'includes/test_page/TestingGlobalCollectGateway.php';
	$wgAutoloadClasses['TestingGlobalCollectOrphanAdapter'] = $testDir . 'includes/test_gateway/TestingGlobalCollectOrphanAdapter.php';
	$wgAutoloadClasses['TestingPaypalAdapter'] = $testDir . 'includes/test_gateway/TestingPaypalAdapter.php';
	$wgAutoloadClasses['TestingWorldPayAdapter'] = $testDir . 'includes/test_gateway/TestingWorldPayAdapter.php';
	$wgAutoloadClasses['TestingWorldPayGateway'] = $testDir . 'includes/test_page/TestingWorldPayGateway.php';

	$wgAutoloadClasses['TestingLanguage'] = $testDir . 'includes/test_language/test.language.php';
	$wgAutoloadClasses['TestingRequest'] = $testDir . 'includes/test_request/test.request.php';

	return true;
}

// Include composer's autoload if the vendor directory exists.  If we have been
// included via Composer, our dependencies should already be autoloaded at the
// top level.
// Note that in WMF's continuous integration, we can still only use stuff from
// Composer if it is already in Mediawiki's vendor directory, such as monolog
$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $vendorAutoload ) ) {
	require_once ( $vendorAutoload );
} else {
	require_once ( 'gateway_common/WmfFramework.php' );
}
