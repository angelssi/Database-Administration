<?php
include("process.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>PUP Airport | Flight Logs</title>
    <link rel="icon" href="images/logo6.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="table.css">
</head>

<body>
    <!-- Navbar -->
    <div class="container p-0 m-0">
        <nav class="navbar navbar-expand-lg shadow fixed-top p-3" style="background-color: #800000;"
            data-bs-toggle="collapse">
            <div class="container-fluid">
                <a href="index.php">
                    <img src="images/logo3.png" alt="logo" class="img-fluid" width="200">
                </a>
            </div>
        </nav>
    </div>

    <!-- Header -->
    <div class="container text-center mt-5 pt-5">
        <h1 class="fw-bold" style="color: #800000; font-family: Archivo; font-size: 7vh;">Flight Logs</h1>
    </div>

    <!-- Filters and Table -->
    <div class="container-fluid my-4">
        <form method="GET" action="">
            <!-- Buttons and Filters -->
            <div class="row g-3 p-0 m-0 align-items-center">
                <!-- Sort and Order -->
                <div class="col-12 col-md-6 d-flex justify-content-md-start gap-2">
                    <select name="sort" class="form-control" style="width:fit-content; background-color:#ffcc00">
                        <option value="" selected disabled>Sort</option>
                        <option value="">None</option>
                        <option <?php if ($sort == "flightNumber") {
                            echo "selected";
                        } ?> value="flightNumber">
                            Flight Number
                        </option>
                        <option <?php if ($sort == "departureAirportCode") {
                            echo "selected";
                        } ?>
                            value="departureAirportCode">
                            Departure Airport Code
                        </option>
                        <option <?php if ($sort == "arrivalAirportCode") {
                            echo "selected";
                        } ?>
                            value="arrivalAirportCode">
                            Arrival Airport Code
                        </option>
                        <option <?php if ($sort == "departureDatetime") {
                            echo "selected";
                        } ?> value="departureDatetime">
                            Departure Date Time
                        </option>
                        <option <?php if ($sort == "arrivalDatetime") {
                            echo "selected";
                        } ?> value="arrivalDatetime">
                            Arrival Date Time
                        </option>
                        <option <?php if ($sort == "flightDurationMinutes") {
                            echo "selected";
                        } ?>
                            value="flightDurationMinutes">
                            Flight Duration
                        </option>
                        <option <?php if ($sort == "airlineName") {
                            echo "selected";
                        } ?> value="airlineName">
                            Airline Name
                        </option>
                        <option <?php if ($sort == "aircraftType") {
                            echo "selected";
                        } ?> value="aircraftType">
                            Aircraft Type
                        </option>
                        <option <?php if ($sort == "passengerCount") {
                            echo "selected";
                        } ?> value="passengerCount">
                            Passenger Count
                        </option>
                        <option <?php if ($sort == "pilotName") {
                            echo "selected";
                        } ?> value="pilotName">
                            Pilot Name
                        </option>
                    </select>

                    <select name="order" class="form-select" style="width: fit-content; background-color:#ffcc00">
                        <option value="" selected disabled>Order</option>
                        <option value="ASC">Ascending</option>
                        <option value="DESC">Descending</option>
                    </select>
                </div>
                <!-- Buttons -->
                <div class="col-12 col-md-6 d-flex justify-content-md-end gap-1">
                    <button type="submit" class="btn rounded-start-5 text-dark" style="background-color: #ffcc00">
                        Apply Filters
                    </button>
                    <a href="index.php" class="btn btn-secondary rounded-end-5">Clear Filters</a>
                </div>
            </div>
    </div>


    <!-- Table -->
    <div class="container-fluid overflow-auto p-3 m-0">
        <table class="table-responsive">
            <thead style="white-space: nowrap">
                <tr>
                    <th> Flight Number <br>
                        <select id="flightNumSelect" name="flightNumber" class="small form-control">
                            <option value="">0</option>
                            <?php
                            if (mysqli_num_rows($flightNumberResults) > 0) {
                                while ($flightNumberRow = mysqli_fetch_assoc($flightNumberResults)) {
                                    ?>
                                    <option <?php if ($flightNumberFilter == $flightNumberRow['flightNumber']) {
                                        echo "selected";
                                    } ?> value="<?php echo $flightNumberRow['flightNumber'] ?>">
                                        <?php echo $flightNumberRow['flightNumber'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th> Departure Airport <br>
                        <select id="departureSelect" name="departureAirportCode" class="small form-control">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($departureResults) > 0) {
                                while ($departureRow = mysqli_fetch_assoc($departureResults)) {
                                    ?>
                                    <option <?php if ($departureAirportFilter == $departureRow['departureAirportCode']) {
                                        echo "selected";
                                    } ?> value="<?php echo $departureRow['departureAirportCode'] ?>">
                                        <?php echo $departureRow['departureAirportCode'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th> Arrival Airport <br>
                        <select id="arrivalSelect" name="arrivalAirportCode" class="form-control">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($arrivalResults) > 0) {
                                while ($arrivalRow = mysqli_fetch_assoc($arrivalResults)) {
                                    ?>
                                    <option <?php if ($arrivalAirportFilter == $arrivalRow['arrivalAirportCode']) {
                                        echo "selected";
                                    } ?> value="<?php echo $arrivalRow['arrivalAirportCode'] ?>">
                                        <?php echo $arrivalRow['arrivalAirportCode'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th> Departure Date Time
                        <input type="date" id="departureDateInput" name="departureDatetime"
                            value="<?= isset($_GET['departureDatetime']) ? $_GET['departureDatetime'] : '' ?>"
                            class="form-control">
                    </th>
                    <th> Arrival Date Time
                        <input type="date" id="arrivalDateInput" name="arrivalDatetime"
                            value="<?= isset($_GET['arrivalDatetime']) ? $_GET['arrivalDatetime'] : '' ?>"
                            class="form-control">
                    </th>
                    <th> Duration(Minutes)
                        <input type="number" placeholder="0" id="durationInput" name="flightDurationMinutes"
                            value="<?= isset($_GET['flightDurationMinutes']) ? $_GET['flightDurationMinutes'] : '' ?>"
                            class="form-control">
                    </th>
                    <th> Airline Name <br>
                        <select id="airlineSelect" name="airlineName" class="small form-control">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($airlineResults) > 0) {
                                while ($airlineRow = mysqli_fetch_assoc($airlineResults)) {
                                    ?>
                                    <option <?php if ($airlineNameFilter == $airlineRow['airlineName']) {
                                        echo "selected";
                                    } ?> value="<?php echo $airlineRow['airlineName'] ?>">
                                        <?php echo $airlineRow['airlineName'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th> Aircraft Type <br>
                        <select id="aircraftSelect" name="aircraftType" class="small form-control">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($aircraftResults) > 0) {
                                while ($aircraftRow = mysqli_fetch_assoc($aircraftResults)) {
                                    ?>
                                    <option <?php if ($aircraftFilter == $aircraftRow['aircraftType']) {
                                        echo "selected";
                                    } ?> value="<?php echo $aircraftRow['aircraftType'] ?>">
                                        <?php echo $aircraftRow['aircraftType'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th> Passenger Count
                        <input type="number" placeholder="0" id="passengerCountInput" name="passengerCount"
                            value="<?= isset($_GET['passengerCount']) ? $_GET['passengerCount'] : '' ?>"
                            class="form-control">
                    </th>
                    <th> Pilot Name <br>
                        <select id="pilotNameSelect" name="pilotName" class="small form-control">
                            <option value="">Any</option>
                            <?php
                            if (mysqli_num_rows($pilotNameResults) > 0) {
                                while ($pilotNameRow = mysqli_fetch_assoc($pilotNameResults)) {
                                    ?>
                                    <option <?php if ($pilotNameFilter == $pilotNameRow['pilotName']) {
                                        echo "selected";
                                    } ?> value="<?php echo $pilotNameRow['pilotName'] ?>">
                                        <?php echo $pilotNameRow['pilotName'] ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($filterResult) > 0) {
                    while ($flightLogsRow = mysqli_fetch_assoc($filterResult)) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo $flightLogsRow["flightNumber"] ?></th>
                            <td><?php echo $flightLogsRow["departureAirportCode"] ?></td>
                            <td><?php echo $flightLogsRow["arrivalAirportCode"] ?></td>
                            <td><?php echo $flightLogsRow["departureDatetime"] ?></td>
                            <td><?php echo $flightLogsRow["arrivalDatetime"] ?></td>
                            <td><?php echo $flightLogsRow["flightDurationMinutes"] ?></td>
                            <td><?php echo $flightLogsRow["airlineName"] ?></td>
                            <td><?php echo $flightLogsRow["aircraftType"] ?></td>
                            <td><?php echo $flightLogsRow["passengerCount"] ?></td>
                            <td><?php echo $flightLogsRow["pilotName"] ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#departureSelect, #arrivalSelect, #airlineSelect, #aircraftSelect, #pilotNameSelect, #flightNumSelect').select2();
        });
    </script>
</body>

</html>