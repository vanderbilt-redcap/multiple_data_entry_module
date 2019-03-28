<?php

$project = $_GET['pid'];

if($project == "") {
	throw new Exception("No project selected");
}

require_once \ExternalModules\ExternalModules::getProjectHeaderPath();

/* @var $module RedcapAfrica\MultipleDataEntry\MultipleDataEntry */
$matchingField = $module->getProjectSetting("record-name-field");
$secondaryMatchingFields = $module->getProjectSetting("matching-fields");

$recordDetails = $module->getData($project,"");

foreach($recordDetails as $recordId => $details) {
	$matchingRecords = [];
	$matchingValue = "";
	$secondaryValues = [];

	foreach($details as $eventId => $eventDetails) {
		if($eventDetails[$matchingField] != "") {
			$matchingValue = $eventDetails[$matchingField];
		}

		foreach($secondaryMatchingFields as $secondaryField) {
			if($eventDetails[$secondaryField] != "") {
				$secondaryValues[$secondaryField] = $eventDetails[$secondaryField];
			}
		}
	}

	echo "Record: $matchingValue <Br />";var_dump($secondaryValues);echo "<Br /><br />";
}

require_once \ExternalModules\ExternalModules::getProjectFooterPath();