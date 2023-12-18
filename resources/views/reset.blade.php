<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body style="margin: 100px;">
    <h1>Ha solicitado restablecer su contraseña</h1>
    <hr>
    <p>No podemos enviarle su contraseña antigua por seguridad. Se ha generado un enlace para que pueda restablecer su contraseña, para ello haga click en el siguiente enlace y siga las instrucciones.</p>
    <h1><a href="http://127.0.0.1:3000/api/auth/reset/{{$token}}">Haga Click aqui para Restablecer su Password</a></h1>
</body>

</html>
