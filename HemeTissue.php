<?php
namespace Stanford\HemeTissue;

require_once "emLoggerTrait.php";

class HemeTissue extends \ExternalModules\AbstractExternalModule {

    use emLoggerTrait;

    public function __construct() {
		parent::__construct();
		// Other code to run when object is instantiated
	}

	public function runQuery() {

        // count of sample types
        $sqp= "select rd1.value, count(1)
            from redcap_data rd1
                     join redcap_data rd2
                          on rd1.project_id = rd2.project_id and rd1.event_id = rd2.event_id
                              and rd1.record = rd2.record and
                             (rd1.instance = rd2.instance or (rd1.instance is null and rd2.instance is null))
            where rd1.field_name = 'tis_type'
              and rd1.project_id = 32
              and rd2.field_name = 'lab_source'
              and rd2.value = '1'
            group by rd1.value";
    }


}
