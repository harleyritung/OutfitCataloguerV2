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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>
  <header class="col-12">
    <!-- Home and settings navbar -->
    <nav id="homenav" class="navbar navbar-expand-lg navbar-light bg-transparent">
      <div class="container-fluid">
        <a class="navbar-brand" href="?command=home">Outfit Cataloguer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="?command=home">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="?command=clothes_home" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Clothes
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="?command=clothes_home">Your Clothes</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="?command=clothes_add">Add Clothes</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="?command=outfit_home" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Outfits
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="?command=outfit_home">Your Outfits</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="?command=outfit_create">Create Outfits</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Settings
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="?command=profile">Profile</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="?command=logout">Logout</a></li>
              </ul>
            </li>
          </ul>
          <span class="navbar-text" style="padding-right: 30px">
            Hello there, <?=$_SESSION["name"]?>!
          </span>
          <form class="d-flex" role="search">
            <input type="hidden" value="clothes_home" name="command">
            <input class="form-control me-2" type="search" name="search" placeholder="Search Your Clothes" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
  </header> 


  <!-- Page content begins -->
  <div class="col-md-12" id="scroll-Div">
    <div class="container spaced-from-tb">
      <?php 
          if ($search) { 
              echo '<h1 class="display-4 underlined">Clothes with "' . $_GET["search"] . '"</h1>'; 
          }
          else {
              echo '<h1 class="display-4 underlined">Create an Outfit</h1>';
          }
      ?>
      <div class="row">
      <div class="col-md-9">
        <h3 class="subheader">Click to Add Clothes to Outfit</h3>
        <div class="row justify-content-center">
            <?php
                if (!empty($error_msg)) {
                    echo "<div class='alert alert-warning'>$error_msg</div>";
                }
            ?>
        </div>
        <?php
            foreach ($data as $image) {
        ?>
            <a id="<?=$image['itemID']?>" class="image-link" role="button">
                <img src="./images/<?php echo $image['image']; ?>" class="img-thumbnail">
            </a>
            <?php
            }
        ?>
      </div>
      <div class="col-md-3" id="outfit-container">
        <h3 class="subheader">Your Outfit</h3>
        <form action="?command=outfit_home" method="post" id="outfit-form">
          
        </form>
      </div>
      </div>

    </div>
  </div>

  <footer>
    <!-- Bottom nav -->
    <nav class="navbar fixed-bottom navbar-light bg-light" aria-label="breadcrumb">
      <div class="container-fluid" style="padding-top: 0.5rem;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?command=home">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Outfits</li>
        </ol>
      </div>
    </nav>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
    crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    // make a function to add to imgs in outfit builder that removes them when clicked
    // function here


    
    var anchor;
    var img;
    var src;
    var input;
    var input_num = 0;
    // move item image to outfit builder when clicked
    $(".image-link").each(
      function () {
        anchor = $(this);
        anchor.on("click", function () {
          // get img src
          src = $(this).children(":first").attr("src");
          // create new img element
          img = $('<img>')
          .attr('src', src)
          .addClass("img-thumbnail");
          // create hidden input for img
          input = $('<input>')
          .attr('type', 'hidden')
          .attr('name', input_num)
          .attr('value', $(this).attr('id'));
          // put img and input in outfit form
          $("#outfit-form").append(img);
          $("#outfit-form").append(input);
          // increment num of hidden inputs
          input_num++;
        });
      }
    );
  </script>
</body>

</html>