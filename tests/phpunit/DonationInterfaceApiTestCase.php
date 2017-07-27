<?php

use SmashPig\Core\Context;
use SmashPig\Tests\TestingContext;
use SmashPig\Tests\TestingGlobalConfiguration;

class DonationInterfaceApiTestCase extends ApiTestCase {

	public function setUp() {
		parent::setUp();
		$config = TestingGlobalConfiguration::create();
		TestingContext::init( $config );
		$ctx = TestingContext::get();
		$ctx->setSourceType( 'payments' );
		$ctx->setSourceName( 'DonationInterface' );
		$this->setMwGlobals( array(
			'wgDonationInterfaceEnableQueue' => true,
			'wgDonationInterfaceDefaultQueueServer' => array(
				'type' => 'TestingQueue',
			),
		) );
		DonationLoggerFactory::$overrideLogger = new TestingDonationLogger();
	}

	public function tearDown() {
		parent::tearDown();
		Context::set( null );
		TestingQueue::clearAll();
		// Clear out our HashBagOStuff
		wfGetMainCache()->clear();
		DonationLoggerFactory::$overrideLogger = null;
	}
}
