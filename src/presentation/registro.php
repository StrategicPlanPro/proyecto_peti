<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="assets/css/style.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-10">
					<div class="wrap d-md-flex">
						<div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
							<div class="text w-100">
								<h2>Create an Account</h2>
								<p>Already have an account?</p>
								<a href="login.php" class="btn btn-white btn-outline-white">Sign In</a>
							</div>
			      </div>
						<div class="login-wrap p-4 p-lg-5">
			      	<div class="d-flex">
			      		<div class="w-100">
			      			<h3 class="mb-4">Register</h3>
			      		</div>
			      	</div>
                      <form action="../business/procesarRegistro.php" method="POST" class="signin-form">
    <div class="form-group mb-3">
        <label class="label" for="username">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="form-group mb-3">
        <label class="label" for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="form-group mb-3">
        <label class="label" for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
    </div>
    <div class="form-group">
        <br>
        <button type="submit" class="form-control btn btn-primary submit px-3">Register</button>
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
