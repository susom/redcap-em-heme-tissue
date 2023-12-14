<?php
namespace Stanford\HemeTissue;

require_once "emLoggerTrait.php";

class HemeTissue extends \ExternalModules\AbstractExternalModule {

    use emLoggerTrait;

    public function __construct() {
        parent::__construct();
        // Other code to run when object is instantiated
    }


    private function readQueryResults($sql) {
        global $module;

        $rptdata = db_query($sql);

        $result["status"] = 1; // when status = 0 the client will display the error message
        if (strlen(db_error()) > 0) {
            $dberr = db_error();
            error_log("ERROR: " . $dberr);
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
    public function runQueries() {

        // SQL to generate the histogram query by year for the given sample type
        $data_table = method_exists('\REDCap', 'getDataTable') ? \REDCap::getDataTable($_GET['pid']) : "redcap_data";

        $sql= "select substr(rd1.value, 1, 4) year, count(1) cnt
            from $data_table rd1
                     join $data_table rd2
                          on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                              and rd1.record = rd2.record and
                             (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))
                     join $data_table rd3
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

        $rptdata = $this->readQueryResults(str_replace('?', "'4'", $sql));

        $result["status"] = $rptdata["status"]; // when status = 0 the client will display the error message
        $result["message"] = $rptdata["message"];
        $result["cellsByYear"] = $rptdata["data"];

        // if the very first query attempt failed, there must be a system problem, so return immediately
        if ($result["status"] === 0) {
            return $result;
        }
        $rptdata = $this->readQueryResults(str_replace('?', "'1'", $sql));
        $result["plasmaByYear"] = $rptdata["data"];

        $sql = "select rd1.value, count(1) cnt
        from $data_table rd1
                 join $data_table rd2
                      on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                          and rd1.record = rd2.record and
                         (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

        where rd1.field_name = 'tis_type'
          and rd1.project_id = ".$_GET['pid']."
          and rd2.field_name = 'lab_source'
          and rd2.value = '1'
        group by rd1.value
        order by rd1.value";

        $rptdata = $this->readQueryResults($sql);
        $result["sampleTypes"] = $rptdata["data"];

        $sql = "select  count(1) sample_cnt, count(distinct rd1.record) pt_cnt
        from $data_table rd1
                 join $data_table rd2
                      on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                          and rd1.record = rd2.record and
                         (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

        where rd1.field_name = 'tis_type'
          and rd1.project_id = ".$_GET['pid']."
          and rd2.field_name = 'lab_source'
          and rd2.value = '1'
        group by rd1.project_id";

        $rptdata = $this->readQueryResults($sql);
        $result["totalCounts"] = $rptdata["data"];

        $sql = "select rd1.value, count(1) cnt
        from $data_table rd1
                 join $data_table rd2
                      on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                          and rd1.record = rd2.record and
                         (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))

        where rd1.field_name = 'gender'
          and rd1.project_id = ".$_GET['pid']."
          and rd2.field_name = 'lab_source'
          and rd2.value = '1'
        group by rd1.value
        order by rd1.value";


        $rptdata = $this->readQueryResults($sql);
        $result["genderCounts"] = $rptdata["data"];

        return $result;
    }


}
