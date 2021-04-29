<?php
namespace Stanford\HemeTissue;

// http://localhost/redcap_v10.8.2/ExternalModules/?prefix=redcap-em-heme-tissue&page=annualReport&pid=32
global $module ;

$result = $module->runQueries();

?>

    <div class="row ">
        <div  class="pl-0 col-3">
            <div class="card  align-top">
                <div class="card-body">
                    <h6 class="card-title px-2 py-2" style="background-color:rgb(131, 19, 16);color:white;">Total Counts</h6>
                    <div class="card-text">
                        <table id="cellsTable" class="table table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Total Samples</th>
                                <th>Total Patients</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($result["totalCounts"]  as $key => $eventData) {
                                ?>
                                <tr>
                                    <td><?php echo $eventData["sample_cnt"] ?></td>
                                    <td><?php echo $eventData["pt_cnt"] ?></td>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>
                        <table id="cellsTable" class="table table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Gender</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($result["genderCounts"]  as $key => $eventData) {
                                ?>
                                <tr>
                                    <td><?php echo $eventData["value"] ?></td>
                                    <td><?php echo $eventData["cnt"] ?></td>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-0 col-3">
            <div class="card   align-top ">
                <div class="card-body">
                    <h6 class="card-title px-2 py-2" style="background-color:rgb(131, 19, 16);color:white;">Cell Samples by Year</h6>
                    <div class="card-text">
                        <table id="cellsTable" class="table table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Year</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($result["cellsByYear"]  as $key => $eventData) {
                                ?>
                                <tr>
                                    <td><?php echo $eventData["year"] ?></td>
                                    <td><?php echo $eventData["cnt"] ?></td>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-0 col-3">
            <div class="card  align-top">
                <div class="card-body">
                    <h6 class="card-title px-2 py-2" style="background-color:rgb(131, 19, 16);color:white;">Plasma Samples by Year</h6>
                    <div class="card-text">
                        <table id="cellsTable" class="table table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Year</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($result["plasmaByYear"]  as $key => $eventData) {
                                ?>
                                <tr>
                                    <td><?php echo $eventData["year"] ?></td>
                                    <td><?php echo $eventData["cnt"] ?></td>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-0 col-3">
            <div class="card align-top">
                <div class="card-body">
                    <h6 class="card-title px-2 py-2" style="background-color:rgb(131, 19, 16);color:white;">Totals by Sample Type</h6>
                    <div class="card-text">
                        <table id="cellsTable" class="table table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Sample Type</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($result["sampleTypes"]  as $key => $eventData) {
                                switch ($eventData["value"]) {
                                    case '1':
                                        $value = "PB (peripheral blood)";
                                        break;
                                    case '2':
                                        $value = "BMA (bone marrow aspirate)";
                                        break;
                                    case '3':
                                        $value = "Serum";
                                        break;
                                    case '4':
                                        $value = "Plasma";
                                        break;
                                    case '5':
                                        $value = "Whole Blood";
                                        break;
                                    case '6':
                                        $value = "Buffy Coat";
                                        break;
                                    case '7':
                                        $value = "Granulocyte";
                                        break;
                                    case '8':
                                        $value = "Eosinophil";
                                        break;
                                    case '999':
                                        $value = "Unknown";
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $value ?></td>
                                    <td><?php echo $eventData["cnt"] ?></td>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


