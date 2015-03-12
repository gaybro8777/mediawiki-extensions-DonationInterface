<?php

/**
 * Contains donation workflow UI hints
 *
 * After each donation request or gateway response, the adapter produces
 * a PaymentResult which wraps one of the following:
 *
 *   - Success: Send donor to the Thank You page.
 *
 *   - Failure (unrecoverable): Send donor to the failure page.
 *
 *   - Refresh form: After validation or other recoverable errors, display the
 *     donation form again and give the donor a chance to correct any errors,
 *     usually with helpful notices.  This PaymentResult object will contain
 *     a map of field names to errors.
 *       If we're feel really feisty, we can make the form name dynamic, as
 *     well as other parameters to the view template--so one form may send the
 *     donor to a more appropriate form.
 *
 *   - Iframe: FIXME, this is almost a variation on refreshForm.
 *
 *   - Gateway redirect: Send donor to the gateway, usually with a ton of data
 *     in the URL's GET params.
 */
class PaymentResult {
	protected $iframe;
	protected $form;
	protected $redirect;
	protected $refresh;
	protected $errors = array();
	protected $failed;

	protected function __construct() {
	}

	static public function newIframe( $name ) {
		$response = new PaymentResult();
		$response->iframe = $name;
		return $response;
	}

	static public function newForm( $name ) {
		$response = new PaymentResult();
		$response->form = $name;
		return $response;
	}

	static public function newRedirect( $url ) {
		$response = new PaymentResult();
		$response->redirect = $url;
		return $response;
	}

	static public function newRefresh( $errors = array() ) {
		$response = new PaymentResult();
		$response->refresh = true;
		$response->errors = $errors;
		return $response;
	}

	static public function newSuccess() {
		$response = new PaymentResult();
		return $response;
	}

	static public function newFailure() {
		$response = new PaymentResult();
		$response->failed = true;
		return $response;
	}

	static public function newEmpty() {
		$response = new PaymentResult();
		$response->errors = array(
			'internal-0000' => 'Internal error: no results yet.',
		);
		$response->failed = true;
		return $response;
	}

	public function getIframe() {
		return $this->iframe;
	}

	public function getForm() {
		return $this->form;
	}

	public function getRedirect() {
		return $this->redirect;
	}

	public function getRefresh() {
		return $this->refresh;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function isFailed() {
		return $this->failed;
	}

	/**
	 * Build a PaymentResult object from adapter results
	 *
	 * @param array $data getTransactionAllResults contents.
	 * @param string $finalStatus final transaction status.
	 */
	static public function fromResults( $data, $finalStatus ) {
		if ( $finalStatus === 'failed' ) {
			return PaymentResult::newFailure();
		}
		if ( $data === false ) {
			return PaymentResult::newEmpty();
		}
		if ( array_key_exists( 'errors', $data )
			&& $data['errors']
		) {
			// TODO: We will probably want the ability to refresh to a new form
			// as well and display errors at the same time.
			return PaymentResult::newRefresh( $data['errors'] );
		}
		if ( array_key_exists( 'redirect', $data ) ) {
			return PaymentResult::newRedirect( $data['redirect'] );
		}
		return PaymentResult::newSuccess();
	}
}
