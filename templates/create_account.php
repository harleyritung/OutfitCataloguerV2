<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Riley Hartung, Sophia Walton, Giovanni Cianciaruso, Raneem Khan">
  <meta name="description" content="A website for uploading clothing and generating outfits based on user input.">
  <meta name="keywords"
    content="outfit maker, outfit creator, outfit inspiration, outfit cataloguer, wardorbe organizer">

  <title>Outfit Cataloguer</title>

  <!-- Local CSS file -->
  <link rel="stylesheet" href="styles/main.css">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

    <body>
    <header class="col-12">
    <!-- Home and settings navbar -->
    <nav id="topnav" class="navbar navbar-expand-lg navbar-light bg-transparent">
      <div class="container-fluid">
        <a class="navbar-brand" href="?command=login">Outfit Cataloguer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </nav>
    </header>

    <div class="container" style="margin-top: 15px;">
    <div class="row col-xs-8 text-center" style="float: none; width: auto">
        <h1>Create an Account</h1>
        <p> Welcome to Outfit Cataloguer. To create an account, enter your information below.</p>
    </div>
    <div class="row justify-content-center">
        <?php
            if (!empty($error_msg)) {
                echo "<div class='alert alert-danger'>$error_msg</div>";
            }
        ?>
        <div class="col-4">
        <form action="?command=create_account" method="post" onsubmit="return validate('email_address', 'name', 'password1', 'password2');">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_address" name="email_address"/>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"/>
                <div id="nameError" class="form-text" style="color:red;"></div>
            </div>
            <div class="mb-3">
                <p>
                    Password must be at least 8 characters, contain uppercase and lowercase letters, and have at least one of the 
                    following special characters: !@#$%&*?
                </p>
                <label for="password1" class="form-label">Password</label>
                <input type="password" class="form-control" id="password1" name="password1"/>
                <div id="pw1Error" class="form-text" style="color:red;"></div>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="password2"/>
                <div id="pw2Error" class="form-text" style="color:red;"></div>
            </div>
            <div class="text-center">                
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
        </div>
    </div>
</div>

        <footer>
        <nav class="navbar fixed-bottom navbar-light bg-light" aria-label="breadcrumb">
            <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?command=login">Login</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a class="active-link"
                    href="?command=create_account">Create Account</a></li>
                </ol>
            </nav>
            </div>
        </nav>
        </footer>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script type="text/javascript">
            // name regex
            var regex = new RegExp("[^a-zA-Z' -]");
            // regex = new RegExp('!@#$%^&*(){}[]~`:;\d<>,.?/|=+_"');
            $("#name").keyup(() => {
            // if name contains something other than letters, -, or '
            if (regex.test($("#name").val())) {
                $("#nameError").text("⚠ Name cannot contain special characters.");
            }
            else {
                $("#nameError").text("");
            }
            });

            // check passwords match
            $("#password2").focusout(function() {
                var pw1Val = $("#password1").val();
                var pw2Val = $(this).val();
                if (pw1Val != pw2Val) {
                    $("#pw2Error").text("⚠ Passwords must match");
                }
                else {
                    console.log("hi");
                    $("#pw2Error").text("");
                }
            });
        </script>
    </body>
</html>