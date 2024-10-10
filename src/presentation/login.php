<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Iniciar Sesión</title>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
                        <div class="text w-100">
                            <h2>Bienvenido</h2>
                            <p>No tienes una cuenta?</p>
                            <a href="registro.php" class="btn btn-white btn-outline-white">Regístrate</a>
                        </div>
                    </div>
                    <div class="login-wrap p-4 p-lg-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Iniciar Sesión</h3>
                            </div>
                        </div>
                        <form action="../business/procesarLogin.php" method="POST" class="signin-form">
                            <div class="form-group mb-3">
                                <label class="label" for="username">Usuario</label>
                                <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
