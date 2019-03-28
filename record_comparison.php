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
//	echo "<bR />Initial Record: <Br />";
//	var_dump($matchingData);

	$thisMatchingRecords = [];

	foreach($recordDetails as $thisId => $thisDetails) {
		if($thisId == $recordId || array_key_exists($thisId,$matchedRecords)) {
			continue;
		}

		$thisMatchingData = $module->findMatchingFields($thisDetails);
//		echo "<br />Secondary Records: <Br />";
//		var_dump($thisMatchingData);
		if($matchingData[0] == $thisMatchingData[0]) {
			$thisMatchingRecords[] = $thisId;
			$matchedRecords[$recordId] = 1;
			$matchedRecords[$thisId] = 1;
		}
	}
	$recordsToCompare[$recordId] = $thisMatchingRecords;
}

$comparisonData = [];
foreach($recordsToCompare as $initialRecord => $matchingRecords) {
	$thisComparisonData = [];

	$recordIds = array_merge([$initialRecord],$matchingRecords);

	foreach($recordIds as $recordId) {
		foreach($recordDetails[$recordId] as $eventId => $eventDetails) {
			foreach($eventDetails as $fieldName => $fieldValue) {
				if(!array_key_exists($fieldName,$thisComparisonData)) {
					$thisComparisonData[$fieldName] = [];
				}
				$thisComparisonData[$fieldName][$recordId] = $eventDetails[$fieldName];
			}
		}
	}
	$comparisonData[] = $thisComparisonData;
}

$metadata = $module->getMetadata($project);
$firstField = reset($metadata);

foreach($comparisonData as $dataToCompare) {
	echo "<table class='table'>
		<thead>
			<tr>
				<th>Field Name</th>";

	$headerData = reset($dataToCompare);
	foreach($headerData as $recordId) {
		echo "<th>".$recordId."</th>";
	}

	echo "</tr>
		</thead>
		<tbody>";

	foreach($dataToCompare as $fieldName => $fieldValues) {
		if($fieldName == $firstField["field_name"]) continue;

		echo "<tr>
			<td>$fieldName</td>";

		foreach($fieldValues as $thisValue) {
			echo "<td>$thisValue</td>";
		}
		echo "</tr>";
	}

	echo "</tbody>
	</table>";
}

require_once \ExternalModules\ExternalModules::getProjectFooterPath();