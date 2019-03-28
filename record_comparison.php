<?php

$project = $_GET['pid'];

if($project == "") {
	throw new Exception("No project selected");
}

