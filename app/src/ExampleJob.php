<?php

use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;

class ExampleJob extends AbstractQueuedJob implements QueuedJob
{
	public function __construct () {
	}

	public function setup () {
		$this->currentStep = 0;
		$this->totalSteps = 3;
		Injector::inst()->get(LoggerInterface::class)->info('JOB SETUP');
	}

	public function getJobType() {
		return QueuedJob::QUEUED;
	}

	public function getTitle() {
		return 'Example job';
	}

	/**
	 * Return a signature for this queued job
	 * For this job we only ever want one instance running, so just use the class name
	 * @return String
	 */
	public function getSignature() {
		return md5(get_class($this));
	}

	/**
	 * Execute each importer.
	 * Increments the process step and checks if the job is complete.
	 */
	public function process() {
		Injector::inst()->get(LoggerInterface::class)->info("RUNNING PROCESS STEP {$this->currentStep})");
		sleep(1);
		$t = microtime(true);
		Injector::inst()->get(LoggerInterface::class)->info("DONE STEP {$this->currentStep} AT T: {$t}");
		$this->currentStep++;
		$this->isComplete = $this->currentStep >= $this->totalSteps;
	}
}
