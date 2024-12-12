<?php
include("connect.php");

$flightNumberFilter = isset($_GET['flightNumber']) ? $_GET['flightNumber'] : '';
$departureAirportFilter = isset($_GET['departureAirportCode']) ? $_GET['departureAirportCode'] : '';
$arrivalAirportFilter = isset($_GET['arrivalAirportCode']) ? $_GET['arrivalAirportCode'] : '';
$departureDateTimeFilter = isset($_GET['departureDatetime']) ? $_GET['departureDatetime'] : '';
$arrivalDateTimeFilter = isset($_GET['arrivalDatetime']) ? $_GET['arrivalDatetime'] : '';
$flightDurationFilter = isset($_GET['flightDurationMinutes']) ? $_GET['flightDurationMinutes'] : '';
$airlineNameFilter = isset($_GET['airlineName']) ? $_GET['airlineName'] : '';
$aircraftFilter = isset($_GET['aircraftType']) ? $_GET['aircraftType'] : '';
$passengerCountFilter = isset($_GET['passengerCount']) ? $_GET['passengerCount'] : '';
$pilotNameFilter = isset($_GET['pilotName']) ? $_GET['pilotName'] : '';

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : '';

$conditions = [];

$flightLogsQuery = "SELECT flightNumber, departureAirportCode, arrivalAirportCode, departureDatetime, 
                    arrivalDatetime, flightDurationMinutes, airlineName, aircraftType, passengerCount, 
                    pilotName FROM flightlogs";

if ($flightNumberFilter != '') {
    $conditions[] = "flightNumber = '$flightNumberFilter'";
}

if ($departureAirportFilter != '') {
    $conditions[] = "departureAirportCode = '$departureAirportFilter'";
}

if ($arrivalAirportFilter != '') {
    $conditions[] = "arrivalAirportCode = '$arrivalAirportFilter'";
}

if ($departureDateTimeFilter != '') {
    $conditions[] = "departureDatetime = '$departureDateTimeFilter'";
}

if ($arrivalDateTimeFilter != '') {
    $conditions[] = "arrivalDatetime = '$arrivalDateTimeFilter'";
}

if ($flightDurationFilter != '') {
    $conditions[] = "flightDurationMinutes = '$flightDurationFilter'";
}

if ($airlineNameFilter != '') {
    $conditions[] = "airlineName = '$airlineNameFilter'";
}

if ($aircraftFilter != '') {
    $conditions[] = "aircraftType = '$aircraftFilter'";
}

if ($passengerCountFilter != '') {
    $conditions[] = "passengerCount = '$passengerCountFilter'";
}

if ($pilotNameFilter != '') {
    $conditions[] = "pilotName = '$pilotNameFilter'";
}

if (count($conditions) > 0) {
    $filterQuery = $flightLogsQuery . " WHERE " . implode(" AND ", $conditions);
} else {
    $filterQuery = $flightLogsQuery;
}

if ($sort != '') {
    $filterQuery .= " ORDER BY $sort";
    if ($order != '') {
        $filterQuery .= " $order";
    }
}

$filterResult = executeQuery($filterQuery);

$flightNumberQuery = "SELECT DISTINCT(flightNumber) FROM flightlogs";
$flightNumberResults = executeQuery($flightNumberQuery);

$departureQuery = "SELECT DISTINCT(departureAirportCode) FROM flightlogs";
$departureResults = executeQuery($departureQuery);

$arrivalQuery = "SELECT DISTINCT(arrivalAirportCode) FROM flightlogs";
$arrivalResults = executeQuery($arrivalQuery);

$airlineQuery = "SELECT DISTINCT(airlineName) FROM flightlogs";
$airlineResults = executeQuery($airlineQuery);

$aircraftQuery = "SELECT DISTINCT(aircraftType) FROM flightlogs";
$aircraftResults = executeQuery($aircraftQuery);

$pilotNameQuery = "SELECT DISTINCT(pilotName) FROM flightlogs";
$pilotNameResults = executeQuery($pilotNameQuery);

?>
