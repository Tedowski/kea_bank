<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>

<body>
    <form id="frmLogin">
        <input type="text" id="txtLoginPhone" name="txtLoginPhone" placeholder="Phone">
        <input type="password" id="txtLoginPassword" name="txtLoginPassword" placeholder="Password">
        <button>Login</button>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $('#frmLogin').submit(function () {

            $.ajax({
                    method: "POST",
                    url: "api-test-login.php",
                    data: $('#frmLogin').serialize(),
                    dataType: "JSON"
                })
                .done(function (jData) {
                    console.log(jData);
                    if (jData.status == 0) {
                        console.log(jData);
                        return
                    } else if (jData.status == 1) {
                        console.log(jData);
                        console.log('Login Success');
                    } else if (jData.status == -1) {
                        console.log('User blocked');
                    }
                })
                .fail(function () {
                    console.log('Ajax error');
                });

            return false;
        });
    </script>
</body>

</html>