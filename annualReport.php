<?php
namespace Stanford\HemeTissue;
# Render left hand side navigation and PID / project name banner
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

// http://localhost/redcap_v10.8.2/ExternalModules/?prefix=redcap-em-heme-tissue&page=annualReport&pid=32
global $module ;

function readQueryResults($sql) {
    global $module;

    $rptdata = db_query($sql);

    $result["status"] = 1; // when status = 0 the client will display the error message
    if (strlen(db_error()) > 0) {
        $dberr = db_error();
        error_log($dberr);
        $module->emlog($dberr);
        $result["status"] = 0;
        $result["message"] = $dberr;
    } else {
        $data = array();
        while ($row = db_fetch_assoc($rptdata)) {
//            $module->emDebug(print_r($row,TRUE));
            $data[]  = $row;
        }
        $result["data"]  = $data;
    }
    return $result;
}

// SQL to generate the histogram query by year for the given sample type
$sql= "select substr(rd1.value, 1, 4) year, count(1) cnt
            from redcap_data rd1
                     join redcap_data rd2
                          on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                              and rd1.record = rd2.record and
                             (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))
                     join redcap_data rd3
                          on rd1.project_id = rd3.project_id and rd1.event_id = rd3.event_id
                              and rd1.record = rd3.record and
                             (rd1.instance = rd3.instance or (rd1.instance is null and rd3.instance is null))
            where rd1.field_name = 'sample_date'
              and rd1.project_id = ".$_GET['pid']."
              and rd2.field_name = 'lab_source'
              and rd2.value = '1'
              and rd3.field_name = 'tis_type'
              and rd3.value = ?
            group by substr(rd1.value, 1, 4)";

$rptdata = readQueryResults(str_replace('?', "'4'", $sql));

$result["status"] = $rptdata["status"]; // when status = 0 the client will display the error message
$result["message"] = $rptdata["message"];
$result["cellsByYear"] = $rptdata["data"];

$rptdata = readQueryResults(str_replace('?', "'1'", $sql));
$result["plasmaByYear"] = $rptdata["data"];

$sql = "select rd1.value, count(1) cnt
from redcap_data rd1
         join redcap_data rd2
              on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                  and rd1.record = rd2.record and
                 (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

where rd1.field_name = 'tis_type'
  and rd1.project_id = ".$_GET['pid']."
  and rd2.field_name = 'lab_source'
  and rd2.value = '1'
group by rd1.value
order by rd1.value";

$rptdata = readQueryResults($sql);
$result["sampleTypes"] = $rptdata["data"];

$sql = "select  count(1) sample_cnt, count(distinct rd1.record) pt_cnt
from redcap_data rd1
         join redcap_data rd2
              on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                  and rd1.record = rd2.record and
                 (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

where rd1.field_name = 'tis_type'
  and rd1.project_id = ".$_GET['pid']."
  and rd2.field_name = 'lab_source'
  and rd2.value = '1'
group by rd1.project_id";

$rptdata = readQueryResults($sql);
$result["totalCounts"] = $rptdata["data"];

$sql = "select rd1.value, count(1) cnt
from redcap_data rd1
         join redcap_data rd2
              on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                  and rd1.record = rd2.record and
                 (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

where rd1.field_name = 'gender'
  and rd1.project_id = 32
  and rd2.field_name = 'lab_source'
  and rd2.value = '1'
group by rd1.value
order by rd1.value";


$rptdata = readQueryResults($sql);
$result["genderCounts"] = $rptdata["data"];

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title></title>

</head>
<body>
<div class="container-fluid ">
    <div class="row ">
        <div style="color: #800000;font-size: 16px;font-weight: bold;float:left;margin-bottom: 20px;"><i class="fas fa-file-alt "></i>&nbsp;&nbsp;&nbsp;Annual Report</div>
    </div>
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
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

</body>
</html>
