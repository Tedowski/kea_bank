<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

    </style>
</head>
<body>
    <form action="test.php" method="GET">
        <input name="txtLoginEmail" type="text" placeholder="Phone" 
        data-validate="yes" data-type="integer" data-min="8" data-max="8">
        <select name="txtLoginSelect" id="txtLoginSelect">
            <option value="">Select &hellip;</option>
            <option value="5c63e61939ec9">Main Account</option>
            <option value="5c63e61939ec9">Saving Account</option>
            <option value="5c63e61939ec9">Loan Account</option>
        </select>
        <button>login</button>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>

        

        function fnbValidateInput(formToValidate) {
            let isValid = true;
            // which elements to validate?
            $(formToValidate).find('input[data-validate=yes]').each(function () {
                $(this).removeClass('invalid');
                //what to validate?

                let sDataType = $(this).attr('data-type');

                let iMin = $(this).attr('data-min');
                let iMax = $(this).attr('data-max');

                switch (sDataType) {
                    case "string":
                        if ($(this).val().length < iMin || $(this).val().length > iMax) {
                            $(this).addClass('invalid');
                            isValid = false;
                        }
                        break
                    case "integer":
                        let reN = /^[0-9]*$/
                        if (!reN.test(String($(this).val())) || $(this).val().length < iMin || $(this).val().length > iMax) {
                            $(this).addClass('invalid');
                            isValid = false;
                        }
                        break
                    case "email":
                        let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        if ($(this).val().length < iMin || $(this).val().length > iMax || !re.test(String($(this).val()).toLowerCase())) {
                            $(this).addClass('invalid');
                            isValid = false;
                        }
                        break
                    default:
                        console.log('No idea how to validate that')

                        break
                }
            });

            if (isValid) {
                return true
            } else {
                return false
            }
        }
    </script>
</body>
</html>