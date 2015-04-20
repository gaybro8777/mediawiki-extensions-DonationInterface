<?php
/**
 * Wikimedia Foundation
 *
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 */

/**
 * GatewayPage
 * This class is the generic unlisted special page in charge of actually 
 * displaying the form. Each gateway will have one or more direct descendants of 
 * this class, with most of the gateway-specific control logic in its handleRequest
 * function. For instance: extensions/DonationInterface/globalcollect_gateway/globalcollect_gateway.body.php
 *
 */
abstract class GatewayPage extends UnlistedSpecialPage {
	/**
	 * An array of form errors
	 * @var array $errors
	 */
	public $errors = array( );

	/**
	 * The gateway adapter object
	 * @var GatewayAdapter $adapter
	 */
	public $adapter;

	/**
	 * Gateway-specific logger
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->logger = DonationLoggerFactory::getLogger( $this->adapter );
		$this->getOutput()->addModules( 'donationInterface.skinOverride' );
		
		$me = get_called_class();
		parent::__construct( $me );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the page or null
	 */
	public function execute( $par ) {
		global $wgContributionTrackingFundraiserMaintenance, $wgContributionTrackingFundraiserMaintenanceUnsched;

		$language = $this->getRequest()->getVal( 'language' );
		if ( $language ) {
			RequestContext::getMain()->setLanguage( $language );
		}

		if( $wgContributionTrackingFundraiserMaintenance
			|| $wgContributionTrackingFundraiserMaintenanceUnsched ){
			$this->getOutput()->redirect( Title::newFromText('Special:FundraiserMaintenance')->getFullURL(), '302' );
			return;
		}
		$this->handleRequest();
	}

	/**
	 * Should be overridden in each derived class to actually handle the request
	 * Performs gateway-specific checks and either redirects or displays form.
	 */
	protected abstract function handleRequest();

	/**
	 * Checks current dataset for validation errors
	 * TODO: As with every other bit of gateway-related logic that should 
	 * definitely be available to every entry point, and functionally has very 
	 * little to do with being contained within what in an ideal world would be 
	 * a piece of mostly UI, this function needs to be moved inside the gateway 
	 * adapter class.
	 *
	 * @return boolean Returns false on an error-free validation, otherwise true.
	 * FIXME: that return value seems backwards to me.
	 */
	public function validateForm() {

		$validated_ok = $this->adapter->revalidate();

		if ( !$validated_ok ) {
			if ( $this->fallbackToDefaultCurrency() ) {
				$validated_ok = $this->adapter->revalidate();
				$notify = $this->adapter->getGlobal( 'NotifyOnConvert' );

				if ( $notify || !$validated_ok ) {
					$this->adapter->addManualError( array(
						'general' => $this->msg( 'donate_interface-fallback-currency-notice', 
												 $this->adapter->getGlobal( 'FallbackCurrency' ) )->text()
					) );
					$validated_ok = false;
				}
			}
		}

		return !$validated_ok;
	}

	/**
	 * Build and display form to user
	 */
	public function displayForm() {
		global $wgOut;

		$form_class = $this->getFormClass();
		// TODO: use interface.  static ctor.
		if ( $form_class && class_exists( $form_class ) ){
			$form_obj = new $form_class( $this->adapter );
			$form = $form_obj->getForm();
			$wgOut->addHTML( $form );
		} else {

			$page = $this->adapter->getGlobal( "FailPage" );

			$log_message = '"Redirecting to [ ' . $page . ' ] "';
			$this->logger->info( $log_message );

			if ( $page ) {

				$language = $this->getRequest()->getVal( 'language' );
				$page = wfAppendQuery( $page, array( 'uselang' => $language ) );
			}

			$wgOut->redirect( $page );
		}
	}

	/**
	 * Get the currently set form class
	 * @return mixed string containing the valid and enabled form class, otherwise false. 
	 */
	public function getFormClass() {
		return $this->adapter->getFormClass();
	}

	/**
	 * displayResultsForDebug
	 *
	 * Displays useful information for debugging purposes.
	 * Enable with $wgDonationInterfaceDisplayDebug, or the adapter equivalent.
	 * @param array $results
	 * @return null
	 */
	protected function displayResultsForDebug( $results = array() ) {
		global $wgOut;
		
		$results = empty( $results ) ? $this->adapter->getTransactionAllResults() : $results;
		
		if ( $this->adapter->getGlobal( 'DisplayDebug' ) !== true ){
			return;
		}
		$wgOut->addHTML( HTML::element( 'span', null, $results['message'] ) );

		if ( !empty( $results['errors'] ) ) {
			$wgOut->addHTML( HTML::openElement( 'ul' ) );
			foreach ( $results['errors'] as $code => $value ) {
				$wgOut->addHTML( HTML::element('li', null, "Error $code: $value" ) );
			}
			$wgOut->addHTML( HTML::closeElement( 'ul' ) );
		}

		if ( !empty( $results['data'] ) ) {
			$wgOut->addHTML( HTML::openElement( 'ul' ) );
			foreach ( $results['data'] as $key => $value ) {
				if ( is_array( $value ) ) {
					$wgOut->addHTML( HTML::openElement('li', null, $key ) . HTML::openElement( 'ul' ) );
					foreach ( $value as $key2 => $val2 ) {
						$wgOut->addHTML( HTML::element('li', null, "$key2: $val2" ) );
					}
					$wgOut->addHTML( HTML::closeElement( 'ul' ) . HTML::closeElement( 'li' ) );
				} else {
					$wgOut->addHTML( HTML::element('li', null, "$key: $value" ) );
				}
			}
			$wgOut->addHTML( HTML::closeElement( 'ul' ) );
		} else {
			$wgOut->addHTML( "Empty Results" );
		}
		if ( array_key_exists( 'Donor', $_SESSION ) ) {
			$wgOut->addHTML( "Session Donor Vars:" . HTML::openElement( 'ul' ));
			foreach ( $_SESSION['Donor'] as $key => $val ) {
				$wgOut->addHTML( HTML::element('li', null, "$key: $val" ) );
			}
			$wgOut->addHTML( HTML::closeElement( 'ul' ) );
		} else {
			$wgOut->addHTML( "No Session Donor Vars:" );
		}

		if ( is_array( $this->adapter->debugarray ) ) {
			$wgOut->addHTML( "Debug Array:" . HTML::openElement( 'ul' ) );
			foreach ( $this->adapter->debugarray as $val ) {
				$wgOut->addHTML( HTML::element('li', null, $val ) );
			}
			$wgOut->addHTML( HTML::closeElement( 'ul' ) );
		} else {
			$wgOut->addHTML( "No Debug Array" );
		}
	}

	/**
	 * Fetch the array of iso country codes => country names
	 * @return array
	 */
	public static function getCountries() {
		return CountryCodes::getCountryCodes();
	}

	/**
	 * Handle the result from the gateway
	 *
	 * If there are errors, then this will return to the form.
	 *
	 * @todo
	 * - This is being implemented in GlobalCollect
	 * - Do we only want to skip the Thank you page on getFinalStatus() => failed?
	 *
	 * @return null
	 */
	protected function resultHandler() {
		
		global $wgOut;

		// If transaction is anything, except failed, go to the thank you page.
		
		if ( in_array( $this->adapter->getFinalStatus(), $this->adapter->getGoToThankYouOn() ) ) {

			$thankyoupage = $this->adapter->getThankYouPage();
	
			if ( $thankyoupage ) {
				
				$queryString = '?payment_method=' . $this->adapter->getPaymentMethod() . '&payment_submethod=' . $this->adapter->getPaymentSubmethod();
				
				return $wgOut->redirect( $thankyoupage . $queryString );
			}
		}
		
		// If we did not go to the Thank you page, there must be an error.
		return $this->resultHandlerError();
	}

	/**
	 * Handle the error result from the gateway
	 *
	 * @todo
	 * - logging may need be added to this method
	 *
	 * @return null
	 */
	protected function resultHandlerError() {

		// Display debugging results
		$this->displayResultsForDebug();

		foreach ( $this->adapter->getTransactionErrors() as $code => $message ) {
			
			$error = array();
			if ( strpos( $code, 'internal' ) === 0 ) {
				$error['retryMsg'][ $code ] = $message;
			}
			else {
				$error['general'][ $code ] = $message;
			}
			$this->adapter->addManualError( $error );
		}
		
		return $this->displayForm();
	}

	/**
	 * If a currency code error exists and fallback currency conversion is 
	 * enabled for this adapter, convert intended amount to default currency.
	 *
	 * @return boolean whether currency conversion was performed
	 */
	protected function fallbackToDefaultCurrency() {
		$defaultCurrency = $this->adapter->getGlobal( 'FallbackCurrency' );
		if ( !$defaultCurrency ) {
			return false;
		}
		$form_errors = $this->adapter->getValidationErrors();
		if ( !$form_errors || !array_key_exists( 'currency_code', $form_errors ) ) {
			return false;
		}
		// If the currency is invalid, fallback to default.
		// Our conversion rates are all relative to USD, so use that as an
		// intermediate currency if converting between two others.
		$oldCurrency = $this->adapter->getData_Unstaged_Escaped( 'currency_code' );
		if ( $oldCurrency === $defaultCurrency ) {
			$adapterClass = $this->adapter->getGatewayAdapterClass();
			throw new MWException( __FUNCTION__ . " Unsupported currency $defaultCurrency set as fallback for $adapterClass." );
		}
		$oldAmount = $this->adapter->getData_Unstaged_Escaped( 'amount' );
		$usdAmount = 0.0;
		$newAmount = 0;

		$conversionRates = CurrencyRates::getCurrencyRates();
		if ( $oldCurrency === 'USD' ) {
			$usdAmount = $oldAmount;
		}
		elseif ( array_key_exists( $oldCurrency, $conversionRates ) ) {
			$usdAmount = $oldAmount / $conversionRates[$oldCurrency];
		}
		else {
			// We can't convert from this unknown currency.
			return false;
		}

		if ( $defaultCurrency === 'USD' ) {
			$newAmount = floor( $usdAmount );
		}
		elseif ( array_key_exists( $defaultCurrency, $conversionRates ) ) {
			$newAmount = floor( $usdAmount * $conversionRates[$defaultCurrency] );
		}

		$this->adapter->addRequestData( array(
			'amount' => $newAmount,
			'currency_code' => $defaultCurrency
		) );

		$this->logger->info( "Unsupported currency $oldCurrency forced to $defaultCurrency" );
		return true;
	}

	/**
	 * Respond to a donation request
	 */
	protected function handleDonationRequest() {
		$this->setHeaders();

		// TODO: this is where we should feed GPCS parameters into DonationData.

		// dispatch forms/handling
		if ( $this->adapter->checkTokens() ) {
			if ( $this->isProcessImmediate() ) {
				// Check form for errors
				// FIXME: Should this be rolled into adapter.doPayment?
				$form_errors = $this->validateForm();

				// If there were errors, redisplay form, otherwise proceed to next step
				if ( $form_errors ) {
					$this->displayForm();
				} else {
					// Attempt to process the payment, and render the response.
					$this->processPayment();
				}
			} else {
				$this->adapter->session_addDonorData();
				$this->displayForm();
			}
		} else { //token mismatch
			$error['general']['token-mismatch'] = $this->msg( 'donate_interface-token-mismatch' );
			$this->adapter->addManualError( $error );
			$this->displayForm();
		}
	}

	/**
	 * Determine if we should attempt to process the payment now
	 *
	 * @return bool True if we should attempt processing.
	 */
	protected function isProcessImmediate() {
		// If the user posted to this form, process immediately.
		if ( $this->adapter->posted ) {
			return true;
		}

		// Otherwise, respect the "redirect" parameter.  If it is "1", try to
		// skip the interstitial page.  If it's "0", do not process immediately.
		$redirect = $this->adapter->getData_Unstaged_Escaped( 'redirect' );
		if ( $redirect !== null ) {
			return ( $redirect === '1' || $redirect === 'true' );
		}

		return false;
	}

	/**
	 * Render a resultswitcher page
	 */
	protected function handleResultRequest() {
		//no longer letting people in without these things. If this is
		//preventing you from doing something, you almost certainly want to be
		//somewhere else.
		$forbidden = false;
		if ( !$this->adapter->session_hasDonorData() ) {
			$forbidden = true;
			$f_message = 'No active donation in the session';
		}

		if ( $forbidden ){
			wfHttpError( 403, 'Forbidden', wfMsg( 'donate_interface-error-http-403' ) );
		}
		$oid = $this->adapter->getData_Unstaged_Escaped( 'order_id' );

		$referrer = $this->getRequest()->getHeader( 'referer' );
		$liberated = false;
		if ( $this->adapter->session_getData( 'order_status', $oid ) === 'liberated' ) {
			$liberated = true;
		}

		// XXX need to know whether we were in an iframe or not.
		global $wgServer;
		if ( ( strpos( $referrer, $wgServer ) === false ) && !$liberated ) {
			$_SESSION[ 'order_status' ][ $oid ] = 'liberated';
			$this->logger->info( "Resultswitcher: Popping out of iframe for Order ID " . $oid );
			//TODO: Move the $forbidden check back to the beginning of this if block, once we know this doesn't happen a lot.
			//TODO: If we get a lot of these messages, we need to redirect to something more friendly than FORBIDDEN, RAR RAR RAR.
			if ( $forbidden ) {
				$this->logger->error( "Resultswitcher: $oid SHOULD BE FORBIDDEN. Reason: $f_message" );
			}
			$this->getOutput()->allowClickjacking();
			$this->getOutput()->addModules( 'iframe.liberator' );
			return;
		}

		$this->setHeaders();

		if ( $forbidden ){
			$this->logger->critical( "Resultswitcher: Request forbidden. " . $f_message . " Adapter Order ID: $oid" );
			return;
		} else {
			$this->logger->info( "Resultswitcher: OK to process Order ID: " . $oid );
		}

		if ( $this->adapter->checkTokens() ) {
			if ( $this->adapter->isResponse() ) {
				$this->getOutput()->allowClickjacking();
				$this->getOutput()->addModules( 'iframe.liberator' );
				if ( NULL === $this->adapter->processResponse() ) {
					switch ( $this->adapter->getFinalStatus() ) {
					case 'complete':
					case 'pending':
						$this->getOutput()->redirect( $this->adapter->getThankYouPage() );
						return;
					}
				}
				$this->getOutput()->redirect( $this->adapter->getFailPage() );
			}
		} else {
			$this->logger->error( "Resultswitcher: Token Check Failed. Order ID: $oid" );
		}
	}

	/**
	 * Ask the adapter to perform a payment
	 *
	 * Route the donor based on the response.
	 */
	protected function processPayment() {
		$this->renderResponse( $this->adapter->doPayment() );
	}

	/**
	 * Take UI action suggested by the payment result
	 */
	protected function renderResponse( PaymentResult $result ) {
		if ( $result->isFailed() ) {
			$this->getOutput()->redirect( $this->adapter->getFailPage() );
		} elseif ( $url = $result->getRedirect() ) {
			$this->getOutput()->redirect( $url );
		} elseif ( $url = $result->getIframe() ) {
			// Show a form containing an iframe.

			// Well, that's sketchy.  See TODO in renderIframe: we should
			// accomplish this entirely by passing an iframeSrcUrl parameter
			// to the template.
			$this->displayForm();

			$this->renderIframe( $url );
		} elseif ( $form = $result->getForm() ) {
			// Show another form.

			$this->adapter->addRequestData( array(
				'ffname' => $form,
			) );
			$this->displayForm();
		} elseif ( $errors = $result->getErrors() ) {
			// FIXME: Creepy.  Currently, the form inspects adapter errors.  Use
			// the stuff encapsulated in PaymentResult instead.
			$this->displayForm();
		} else {
			// Success.
			$this->getOutput()->redirect( $this->adapter->getThankYouPage() );
		}
	}

	/**
	 * Append iframe
	 *
	 * TODO: Should be rendered by the template.
	 *
	 * @param string $url
	 */
	protected function renderIframe( $url ) {
		$attrs = array(
			'id' => 'paymentiframe',
			'name' => 'paymentiframe',
			'width' => '680',
			'height' => '300'
		);

		$attrs['frameborder'] = '0';
		$attrs['style'] = 'display:block;';
		$attrs['src'] = $url;
		$paymentFrame = Xml::openElement( 'iframe', $attrs );
		$paymentFrame .= Xml::closeElement( 'iframe' );

		$this->getOutput()->addHTML( $paymentFrame );
	}
}
