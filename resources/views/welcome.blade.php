<!doctype html>
<html>
<head>
    <title>Check a number</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/intlTelInput.css" />
    <link rel="stylesheet" href="css/formValidation.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript">
        var $jq1_11 = jQuery.noConflict(true);
    </script>
    <script src="js/formValidation.min.js" type="text/javascript"></script>
    <script src="js/intlTelInput.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
    <h1>Type a phone number</h1>
    <form id="phoneForm" role="form">
        {!! csrf_field() !!}
        <div class="form-group">
            <input type="tel" class="form-control" id="phone">
        </div>
        <button type="button" class="btn btn-primary" onclick="checkNumber();">Verify</button>
    </form>
    <div id="result"></div>

    <p>
        <button class="btn btn-group" onclick="callNumber();">Call</button>
    </p>
</div>
<script>
    function checkNumber() {
        var token = $("input[name='_token']").val();
        var phone = $("#phone").val();
        $("#result").text("Please wait...");
        $.post('/checkNumber',{phone:phone, _token: token},function(data){
            $("#result").html(data);
        });
    }
    function callNumber() {
        var token = $("input[name='_token']").val();
        var phone = $("#phone").val();
        $.post('/callNumber',{phone:phone, _token: token},function(data){
            $("#result").html(data);
        });
    }
    function buyNumber(phone) {
        var token = $("input[name='_token']").val();
        $("#result").text("Please wait...");
        $.post('/buyNumber',{phone:phone, _token: token},function(data){
            $("#result").html(data);
        });
    }
    $(document).ready(function() {
        (function($) {
            $('#phone').intlTelInput({
                utilsScript: 'js/utils.js',
                autoPlaceholder: true,
                preferredCountries: [],
                onlyCountries: ['us', 'ie', 'dk']
            });
            $('#phoneForm')
                    .formValidation({
                        framework: 'bootstrap',
                        icon: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
                            phoneNumber: {
                                validators: {
                                    callback: {
                                        message: 'The phone number is not valid',
                                        callback: function(value, validator, $field) {
                                            return value === '' || $field.intlTelInput('isValidNumber');
                                        }
                                    }
                                }
                            }
                        }
                    })
                // Revalidate the number when changing the country
                    .on('click', '.country-list', function() {
                        $('#phoneForm').formValidation('revalidateField', 'phoneNumber');
                    });
        }($jq1_11));
    });
</script>
</body>
</html>