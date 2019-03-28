<?php
namespace RedcapAfrica\MultipleDataEntry;

class MultipleDataEntry extends \ExternalModules\AbstractExternalModule {
	public $matchingField = false;
	public $secondaryMatchingFields = false;

	public function __construct() {
		parent::__construct();
		// Other code to run when object is instantiated
	}

	public function findMatchingFields($details) {
		/* @var $module RedcapAfrica\MultipleDataEntry\MultipleDataEntry */
		if(!$this->matchingField) {
			$this->matchingField = $this->getProjectSetting("record-name-field");
			$this->secondaryMatchingFields = $this->getProjectSetting("matching-fields");
		}

		$matchingValue = "";
		$secondaryValues = [];

		foreach($details as $eventId => $eventDetails) {
			if(array_key_exists($this->matchingField,$eventDetails)) {
				$matchingValue = $eventDetails[$this->matchingField];
			}

			foreach($this->secondaryMatchingFields as $secondaryField) {
				if(array_key_exists($secondaryField,$eventDetails)) {
					$secondaryValues[$secondaryField] = $eventDetails[$secondaryField];
				}
			}

		}

		return [$matchingValue, $secondaryValues];
	}
}