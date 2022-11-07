<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="" value="viewport" content="width=device-width, initial-scale=1">

  <meta name="" value="author" content="Riley Hartung, Sophia Walton, Giovanni Cianciaruso, Raneem Khan">
  <meta name="" value="description" content="A website for uploading clothing and generating outfits based on user input.">
  <meta name="" value="keywords" content="outfit maker, outfit creator, outfit inspiration, outfit cataloguer, wardorbe organizer">

  <title>Outfit Cataloguer</title>

  <!-- Local CSS file -->
  <link rel="stylesheet" href="styles/main.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
  <header class="col-md-12">
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
                <li><a class="dropdown-item" href="?command=outfit_add">Add Outfits</a></li>
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
          <span class="navbar-text">
            Hello there, <?=$_SESSION["name"]?>!
          </span>
        </div>
      </div>
    </nav>
  </header> 

  <!-- content -->
  <section>
    <!-- Upload clothes form -->
    <form enctype="multipart/form-data" action="?command=clothes_add" method="post" onsubmit="return validate('article_img', 'Type');">

      <!-- Image upload -->
      <div class="col-md-4">
        <div class="container spaced-from-tb">
          <h1 class="display-6 underlined ps-1">Upload Picture</h1>
          <?php
            if (!empty($error_msg)) {
              echo "<div class='alert alert-danger'>$error_msg</div>";
            }
          ?>
          <h6>(Required)</h6>
          <label for="image_input" style="margin-bottom: 1rem;">Images can be no larger than 2 MB.</label>
          <div class="input-group mb-3">
              <input type="file" class="form-control" id="image_input" name="article_img">
            </div>
          <div class="img-container">
            <!-- <input type="file" id="image_input" accept="image/jpeg, image/png, image/jpg" name="article_img"> -->
            <div id="display_image"></div>
          </div>
          <br>
          <!-- upload button -->
          <button class="btn btn-primary submit-button" type="submit">Upload to Wardrobe</button>
        </div>
      </div>

  <!-- Attribute selection -->
  <div class="col-md-8" id="scroll-Div" style="padding-bottom: 2rem;">
      <div class="col-md-6">
        <div class="container spaced-from-tb">
          <div class="container">
            <h1 class="display-6">Generic Item Attributes</h1>
            <hr class="m-2">

            <!-- Brand -->
            <div class="mb-2">
              <label for="Brand" class="form-label">Brand:</label>
              <input type="text" class="form-control" id="Brand" name="Brand" maxlength="20">
            </div>
            <hr class="m-2">

            <!-- Material -->
            <div class="mb-2">
              <label for="Material" class="form-label">Material:</label>
              <input type="text" class="form-control" id="Material" name="Material" maxlength="20">
            </div>
            <hr class="m-2">

            <!-- Pattern -->
            <div class="mb-2">
              <label for="Pattern" class="form-label">Pattern:</label>
              <input type="text" class="form-control" id="Pattern" name="Pattern" maxlength="20">
            </div>
            <hr class="m-2">

            <!-- Primary Color -->
            <div class="mb-2">
              <label for="PrimaryColor" class="form-label">Primary Color:</label>
              <input type="text" class="form-control" id="PrimaryColor" name="PrimaryColor" maxlength="20">
            </div>
            <hr class="m-2">

            <!-- Secondary Colors -->

            <!-- Style -->

            <!-- Formality -->

          </div>
        </div>
      </div>

      <!-- Specific item attributes -->
      <div class="col-md-6">
        <div class="container spaced-from-tb">
          <div class="container">
            <h1 class="display-6">Specific Item Attributes</h1>
            <hr class="m-2">

            <!-- Type Selection -->
            <h6>(Required)</h6>
            <p class="mb-2">Type:</p>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Accessory" id="flexRadioAccessory">
              <label class="form-check-label" for="flexRadioAccessory">
                Accessory
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Dress" id="flexRadioDress">
              <label class="form-check-label" for="flexRadioDress">
                Dress
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Jewelry" id="flexRadioJewelry">
              <label class="form-check-label" for="flexRadioJewelry">
                Jewelry
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Outerwear" id="flexRadioOuterwear">
              <label class="form-check-label" for="flexRadioOuterwear">
                Outerwear
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Pants" id="flexRadioPants">
              <label class="form-check-label" for="flexRadioPants">
                Pants
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Shirt" id="flexRadioShirt">
              <label class="form-check-label" for="flexRadioShirt">
                Shirt
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Skirt" id="flexRadioSkirt">
              <label class="form-check-label" for="flexRadioSkirt">
                Skirt
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="Type" value="Shoes" id="flexRadioShoes">
              <label class="form-check-label" for="flexRadioShoes">
                Shoes
              </label>
            </div>
            <hr class="m-2">

            <!-- Accessory -->
            <div class="AttrGroup" id="AccessoryGroup" style="display: none">
                <!-- Accessory Type -->
                <div class="mb-2">
                    <label for="AccessoryType" class="form-label">Accessory Type:</label>
                    <input type="text" class="form-control" id="AccessoryType" name="AccessoryType" maxlength="20">
                </div>
                <hr class="m-2">
            </div>

            <!-- Dress -->
            <div class="AttrGroup" id="DressGroup" style="display: none">
                <!-- Dress Type -->
                <div class="mb-2">
                    <label for="DressType" class="form-label">Dress Type:</label>
                    <input type="text" class="form-control" id="DressType" name="DressType" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Dress Length -->
                <div class="mb-2">
                    <label for="DressLength" class="form-label">Dress Length:</label>
                    <input type="text" class="form-control" id="DressLength" name="DressLength" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Dress Sleeve Length -->
                <div class="mb-2">
                    <label for="DressSleeveLength" class="form-label">Dress Sleeve Length:</label>
                    <input type="text" class="form-control" id="DressSleeveLength" name="DressSleeveLength" maxlength="20">
                </div>
                <hr class="m-2">
            </div>

            <!-- Jewelry -->
            <div class="AttrGroup" id="JewelryGroup" style="display: none">
                <!-- Jewelry Type -->
                <div class="mb-2">
                    <label for="JewelryType" class="form-label">Jewelry Type:</label>
                    <input type="text" class="form-control" id="JewelryType" name="JeweleryType" maxlength="20">
                </div>
                <hr class="m-2">
            </div>

            <!-- Outerwear -->
            <div class="AttrGroup" id="OuterwearGroup" style="display: none">
                <!-- Outerwear Type -->
                <div class="mb-2">
                    <label for="OuterwearType" class="form-label">Outerwear Type:</label>
                    <input type="text" class="form-control" id="OuterwearType" name="OuterwearType" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Outerwear Length -->
                <div class="mb-2">
                    <label for="OuterwearLength" class="form-label">Outerwear Length:</label>
                    <input type="text" class="form-control" id="OuterwearLength" name="OuterwearLength" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Outerwear Weight -->
                <div class="mb-2">
                    <label for="OuterwearWeight" class="form-label">Outerwear Weight:</label>
                    <input type="text" class="form-control" id="OuterwearWeight" name="OuterwearWeight" maxlength="20">
                </div>
                <hr class="m-2">
            </div>

            <!-- Pants -->
            <div class="AttrGroup" id="PantsGroup" style="display: none">
                <!-- Pants Length -->
                <div class="mb-2">
                    <label for="PantsLength" class="form-label">Pants Length:</label>
                    <input type="text" class="form-control" id="PantsLength" name="PantsLength" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Pants Weight -->
                <div class="mb-2">
                    <label for="PantsWeight" class="form-label">Pants Weight:</label>
                    <input type="text" class="form-control" id="PantsWeight" name="PantsWeight" maxlength="20">
                </div>
                <!-- Pants Fit -->
                <div class="mb-2">
                    <label for="PantsType" class="form-label">Pants Type:</label>
                    <input type="text" class="form-control" id="PantsType" name="PantsType" maxlength="20">
                </div>
                <hr class="m-2">
            </div>

            <!-- Skirt -->
            <div class="AttrGroup" id="SkirtGroup" style="display: none">
                <!-- Skirt Type -->
                <div class="mb-2">
                    <label for="SkirtType" class="form-label">Skirt Type:</label>
                    <input type="text" class="form-control" id="SkirtType" name="SkirtType" maxlength="20">
                </div>
                <hr class="m-2">
                <!-- Skirt Length-->
                <div class="mb-2">
                    <label for="SkirtLength" class="form-label">Skirt Length:</label>
                    <input type="text" class="form-control" id="SkirtLength" name="SkirtLength" maxlength="20">
                </div>
                <hr class="m-2">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <nav class="navbar fixed-bottom navbar-light bg-light" aria-label="breadcrumb">
      <div class="container-fluid" style="padding-top: 0.5rem;">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?command=home">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Clothes</li>
        </ol>
      </div>
    </nav>
  </footer>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    // Displays image after using file input
    const image_input = document.querySelector("#image_input");
    image_input.addEventListener("change", function() {
        const reader = new FileReader();
        reader.addEventListener("load", () => {
            const uploaded_image = reader.result;
            // alert if img larger than 2mb
            if (this.files[0].size > 2097152) {
                alert("Uploaded image cannot be greater than 2mb");
            }
            // else display img
            else {
                document.querySelector("#display_image").style.backgroundImage = `url(${uploaded_image})`;
            }
        });
        reader.readAsDataURL(this.files[0]);
    });

    // hide or show specific clothes attributes depending on what is selected
    var radioButtons = $(".form-check-input");
    var attrGroups = $(".AttrGroup");
    var attrGroup;
    // loop through each checkbox input
    radioButtons.each(
        function () {
            var radio = $(this);
            // when checkbox clicked
            radio.on("change", function() {
                // hide all of the attributes
                attrGroups.each(
                    function () {
                        $(this).hide();
                    }
                )
                attrGroup = radio.attr("value");
                if (radio.attr("checked", "checked")) {
                    console.log(attrGroup + " checked");
                    console.log("#" + attrGroup + "Group");
                    $("#" + attrGroup + "Group").show();
                }
                else {
                    console.log(attrGroup + " unchecked");
                    $("#" + attrGroup + "Group").hide();
                }
            });
        }
    );
  </script>
</body>

</html>