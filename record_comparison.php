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
$matchedRecords = [];
$recordsToCompare = [];


foreach($recordDetails as $recordId => $details) {
	if(array_key_exists($recordId,$matchedRecords)) {
		continue;
	}

	$matchingData = $module->findMatchingFields($details);

	$thisMatchingRecords = [];

	foreach($recordDetails as $thisId => $thisDetails) {
		if($thisId == $recordId || array_key_exists($recordId,$matchedRecords)) {
			continue;
		}

		$thisMatchingData = $module->findMatchingFields($thisDetails);

		if($matchedData[0] == $thisMatchingData[0]) {
			$thisMatchingRecords[] = $thisId;
			$matchedRecords[$recordId] = 1;
			$matchedRecords[$thisId] = 1;
		}
	}
	$recordsToCompare[$recordId] = $matchedRecords;
}

var_dump($recordsToCompare);

require_once \ExternalModules\ExternalModules::getProjectFooterPath();