<?php
namespace Stanford\HemeTissue;
# Render left hand side navigation and PID / project name banner
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

// http://localhost/redcap_v10.8.2/ExternalModules/?prefix=redcap-em-heme-tissue&page=annualReport&pid=32
global $module ;
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
    <input type="hidden" name="report-submit" id="report-submit"
           value="<?php echo $module->getUrl("getReport.php") ?>">
    <div class="row" id="ui-loading">
        <div class="col-9">
            <h5>Loading the report, please be patient...</h5>
        </div>
        <div id="error-message"></div>
    </div>
    <div id="insert-report-here"></div>

</div>
<script type="application/javascript">
    $(function () {

        var struct = {};
        // launch a callback to the server to render the javascript used
        // to display the left hand navigation, since this seems to take a long time
        $.ajax({
            url: $("#report-submit").val(),
            timeout: 60000000,
            type: 'GET',
            data: struct,
            dataType: 'html',
            success: function (response) {
                $("#ui-loading").hide();
                if (response.status === 0) {
                    console.log("Error: " + response.message);
                    $("#error-message").replaceWith('<div id="data-error-message"  class="alert alert-danger mt-5" >' + message + '</div>');
                } else {
                   // console.log(response);
                    $("#insert-report-here").replaceWith(response);
                }

            },
            error: function (request, error) {
                $("#ui-loading").hide();
                showError("STARTUP Server Error: " + JSON.stringify(error));
                // console.log(request);
                console.log(error);
            }
        });
    });

</script>
</body>
</html>
