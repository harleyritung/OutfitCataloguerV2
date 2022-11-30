<?php

class OutfitController
{
    private $command;

    private $db;

    public function __construct($command)
    {
        $this->command = $command;

        $this->db = new Database();
    }

    public function run()
    {
        switch ($this->command) {
            case "logout":
                $this->delete_session();
                session_start();
                $this->login();
                break;
            case "login":
                $this->login();
                break;
            case "create_account":
                $this->create_account();
                break;
            case "home":
                $this->home();
                break;
            case "clothes_home":
                $this->clothes_home();
                break;
            case "clothes_detail":
                $this->clothes_detail();
                break;
            case "clothes_delete":
                $this->clothes_delete();
                break;
            case "clothes_add":
                $this->clothes_add();
                break;
            case "outfit_home":
                $this->outfit_home();
                break;
            case "outfit_detail":
                $this->outfit_detail();
                break;
            case "outfit_delete":
                $this->outfit_delete();
                break;
            case "outfit_create":
                $this->outfit_create();
                break;
            default:
                $this->login();
                break;
        }
    }

        // finds rows that have attributes like searchString in table (Outfit or Clothes)
        public function search($searchString, $table) {
            $data = array();
            // search in Clothes table
            // returns a 2D array of the itemIDs and image paths for all of the clothes matching the search
            if ($table === "Clothes") {
                $accessory = "SELECT itemID FROM Accessory WHERE AccessoryType like ?";
                $dress = "SELECT itemID FROM Dress 
                WHERE DressLength like ?
                or DressSleeveLength like ?
                or DressType like ?";
                $jewelry ="SELECT itemID FROM Jewelry WHERE jewelryType like ?";
                $outerwear = "SELECT itemID FROM Outerwear 
                WHERE OuterwearLength like ?
                or OuterwearWeight like ?
                or OuterWearType like ?";
                $pants = "SELECT itemID FROM Pants 
                WHERE PantsLength like ?
                or PantsWeight like ?
                or PantsFit like ?";
                $shirt = "SELECT itemID FROM Shirt 
                WHERE ShirtLength like ?
                or ShirtSleeveLength like ?
                or ShirtType like ?";
                $shoes = "SELECT itemID FROM Shoes WHERE ShoesType like ?";
                $skirt = "SELECT itemID FROM Skirt 
                WHERE SkirtLength like ?
                or SkirtType like ?";
                $formality = "SELECT itemID FROM clothes_Formality WHERE Formality like ?";
                $secondaryColor = "SELECT itemID FROM Clothes_SecondaryColor WHERE secondaryColor like ?";
                $style = "SELECT itemID FROM Clothes_Style WHERE Style like ?";
    
                $data = $this->db->query("SELECT itemID, image FROM Clothes
                WHERE UserID = ?
                AND (pattern like ?
                or primaryColor like ?
                or material like ?
                or brand like ?
                or itemID in (
                    " . $accessory . " union
                    " . $dress . " union
                    " . $jewelry . " union
                    " . $outerwear . " union
                    " . $pants . " union
                    " . $shirt . " union
                    " . $skirt . " union
                    " . $shoes . " union
                    " . $formality . " union
                    " . $secondaryColor . " union
                    " . $style .
                "));",
                "issssssssssssssssssssssss",
                $_SESSION["UserID"],
                $searchString, $searchString, $searchString, 
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString 
                );
            }

            // Search in Outfit table
            // returns a 3D array of the clothes' itemIDs and image paths corresponding to the outfits that match the search
            else if ($table === "Outfit") {
                // find outfitIDs matching search
                $itemIDs = array();
                $outfitIDs = $this->db->query("SELECT outfitID, outfitName FROM Outfit
                WHERE UserID = ? AND
                (season = ? or
                outfitName = ? or
                formality = ?);",
                "isss",
                $_SESSION["UserID"], $searchString, $searchString, $searchString
                );
                // find all of the itemIDs for each of the outfits
                if (sizeof($outfitIDs) !== 0) {
                    // find all of the itemIDs for each of the outfits
                    foreach ($outfitIDs as $outfitID) {
                        // get itemID for item from MakeUp table
                        $itemIDs = $this->db->query("SELECT itemID FROM MakeUp WHERE outfitID = ? AND UserID = ?;", 
                        "ii", 
                        $outfitID["outfitID"],
                        $_SESSION["UserID"]
                        );
                        $outfit = array();
                        // add the outfit id to array to retrieve it later when the outfit is clicked
                        array_push($outfit, $outfitID["outfitID"]);
                        // add the outfit name to array for it to be displayed
                        array_push($outfit, substr($outfitID["outfitName"], 1, -1));
                        // get the images FROM Clothes for the itemIDs
                        foreach ($itemIDs as $itemID) {
                            $item = $this->db->query("SELECT image FROM Clothes WHERE itemID = ? AND UserID = ?;", 
                            "ii",
                            $itemID["itemID"],
                            $_SESSION["UserID"]
                            )[0]["image"];
                            array_push($outfit, $item);
                        }
                        array_push($data, $outfit);
                    }
                }
            }
            return $data;
        }

    public function delete_session()
    {
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
    }

    // Display the login page (and handle login logic)
    private function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = $this->db->query("SELECT * FROM User WHERE email_address = ?;", "s", $_POST["email_address"]);
            if ($data === false) {
                $error_msg = "Error checking for user";
            } else if (!empty($data)) {
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    $_SESSION["name"] = $data[0]["name"];
                    $_SESSION["email_address"] = $data[0]["email_address"];
                    $_SESSION["UserID"] = $data[0]["UserID"];
                    header("Location: ?command=home");
                } else {
                    $error_msg = "Wrong password";
                }
            }
            // user doesn't exist
            else {
                $error_msg = "No user with that email exists";
            }
        }
        include ("templates/login.php");
    }

    public function create_account()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = $this->db->query("SELECT * FROM User WHERE email_address = ?;", "s", $_POST["email_address"]);
            if ($data === false) {
                $error_msg = "Error checking for user";
            }

            // user already exists
            if (!empty($data)) {
                $error_msg = "Account with this email already exists";
            }
            // user doesn't exist
            else {
                if ($_POST["password1"] === $_POST["password2"]) {
                    $pw_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d!@#$%&*?]{8,}$/";
                    // password meets requirements
                    if (preg_match($pw_regex, $_POST["password1"]) === 1) {
                        $insert = $this->db->query(
                            "INSERT into User (name, password, email_address) values (?, ?, ?);",
                            "sss",
                            $_POST["name"],
                            password_hash($_POST["password1"], PASSWORD_DEFAULT),
                            $_POST["email_address"]
                        );
                        if ($insert === false) {
                            $error_msg = "Error inserting user";
                        } else {
                            $data = $this->db->query("SELECT * FROM User WHERE email_address = ?;", "s", $_POST["email_address"]);
                            $_SESSION["name"] = $_POST["name"];
                            $_SESSION["email_address"] = $_POST["email_address"];
                            $_SESSION["UserID"] = $data[0]["UserID"];
                            header("Location: ?command=home");
                        }
                    }
                    // password fails regex
                    else {
                        $error_msg = "Make sure password meets requirements";
                    }
                }
                // passwords don't match
                else {
                    $error_msg = "Passwords don't match";
                }
            }
        }
        include("templates/create_account.php");
    }

    public function home()
    {
        // check if any rows in outfits for this user, then display them or say no outfits created
        $outfits = $this->db->query("SELECT * FROM Outfit WHERE UserID = ?;", "i", $_SESSION["UserID"]);
        if (empty($data)) {
            $error_msg = "You haven't created any outfits.";
        }
        include("templates/home.php");
    }

    public function clothes_home() {
        $search = False;
        // display clothes matching search
        if (isset($_GET["search"])) {
            $search = True;
            $data = $this->search($_GET["search"], "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // display all of the users uploaded clothes
            $data = $this->db->query("SELECT itemID, image FROM Clothes WHERE UserID = ?;", "i", $_SESSION["UserID"]);
            if (sizeof($data) === 0) {
                $error_msg = "You haven't uploaded any clothes";
            }
        }
        // if item was clicked on
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // save item ID of item that was clicked on
            $_SESSION['itemID'] = $_POST['itemID'];
            header("Location: ?command=clothes_detail");
        }
        include("templates/clothes_home.php");
    }

    public function clothes_detail() {
        // pulls data FROM Clothes table for item
        $clothes_table_data = $this->db->query("SELECT * FROM Clothes WHERE itemID = ? AND UserID=?;", 
        "ii", 
        $_SESSION['itemID'],
        $_SESSION["UserID"]
        )[0];
        $image_name = $clothes_table_data['image'];
        $brand = $clothes_table_data['brand'];
        $material = $clothes_table_data['material'];
        $pattern = $clothes_table_data['pattern'];
        $primaryColor = $clothes_table_data['primaryColor'];

        // finds the article type table that this item belongs to
        $article_types = array('Accessory', 'Dress', 'Jewelry', 'Outerwear', 'Pants', 'Shirt', 'Shoes', 'Skirt');
        $i = 0;
        $specific_table_data = array();
        // loops through tables until one contains this itemID
        while (sizeof($specific_table_data) === 0 && $i < sizeof($article_types)) {
            $table = $article_types[$i];
            $specific_table_data = $this->db->query("SELECT * FROM $table WHERE itemID=? AND UserID=?;", 
            "ii", 
            $_SESSION['itemID'],
            $_SESSION["UserID"]
            );
            $i++;
        }
        $specific_table_data = $specific_table_data[0];

        // store specific table attrs
        next($specific_table_data);
        $attr_1 = current($specific_table_data);
        next($specific_table_data);
        $attr_2 = current($specific_table_data);
        next($specific_table_data);
        $attr_3 = current($specific_table_data);

        // if update button is clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // image uploaded
            if ($_FILES["article_img"]["error"] !== 4) {
                // DELETE OLD FILE HERE!!!!


                $tempname = $_FILES["article_img"]["tmp_name"];
                $image_name = explode("/", $tempname)[5];
                $folder = dirname(__DIR__, 1) . "/images/" . $image_name;
            
                // move the uploaded image into the folder
                if (!copy($tempname, $folder)) {
                    echo "<h3> Failed to upload image!</h3>";
                }
            }

            // update Clothes table
            $this->db->query("UPDATE Clothes SET image=?, brand=?, material=?, pattern=?, primaryColor=? WHERE itemID=?;",
            "sssssi",
            $image_name,
            $_POST["Brand"],
            $_POST["Material"],
            $_POST["Pattern"],
            $_POST["PrimaryColor"],
            $_SESSION["itemID"]
            );

            // update specific clothes item table
            switch ($table) {
                case "Accessory":
                    $this->db->query("update Accessory set AccessoryType=? WHERE itemID=?;",
                    "si",
                    $_POST["AccessoryType"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Dress":
                    $this->db->query("update Dress set DressType=?, DressLength=?, DressSleeveLength=? WHERE itemID=?;",
                    "sssi",
                    $_POST["DressType"],
                    $_POST["DressLength"],
                    $_POST["DressSleeveLength"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Jewelry":
                    $this->db->query("update Jewelry set jewelryType=? WHERE itemID=?;",
                    "si",
                    $_POST["JewelryType"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Outerwear":
                    $this->db->query("update Outerwear set OuterwearType=?, OuterwearLength=?, OuterwearWeight=? WHERE itemID=?;",
                    "sssi",
                    $_POST["OuterwearType"],
                    $_POST["OuterwearLength"],
                    $_POST["OuterwearWeight"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Pants":
                    $this->db->query("update Pants set PantsLength=?, PantsWeight=?, PantsFit=? WHERE itemID=?;",
                    "sssi",
                    $_POST["PantsLength"],
                    $_POST["PantsWeight"],
                    $_POST["PantsFit"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Shirt":
                    $this->db->query("update Shirt set ShirtType=?, ShirtLength=?, ShirtSleeveLength=? WHERE itemID=?;",
                    "sssi",
                    $_POST["ShirtType"],
                    $_POST["ShirtLength"],
                    $_POST["ShirtSleeveLength"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Shoes":
                    $this->db->query("update Shoes set ShoesType=? WHERE itemID=?;",
                    "si",
                    $_POST["ShoesType"],
                    $_SESSION["itemID"]
                    );
                    break;
                case "Skirt":
                    $this->db->query("update Skirt set SkirtType=?, SkirtLength=? WHERE itemID=?;",
                    "ssi",
                    $_POST["SkirtType"],
                    $_POST["SkirtLength"],
                    $_SESSION["itemID"]
                    );
                    break;
                default:
                    print("default");
                    break;
            }
            header("Location: ?command=clothes_home");
        }

        include("templates/clothes_detail.php");
    }

    public function clothes_delete() {
        // finds the article type table that this item belongs to
        $article_types = array('Accessory', 'Dress', 'Jewelry', 'Outerwear', 'Pants', 'Shirt', 'Shoes', 'Skirt');
        $i = 0;
        $specific_table_data = array();
        // loops through tables until one contains this itemID
        while (sizeof($specific_table_data) === 0 && $i < sizeof($article_types)) {
            $table = $article_types[$i];
            $specific_table_data = $this->db->query("SELECT * FROM $table WHERE itemID=? AND UserID=?;", 
            "ii", 
            $_SESSION['itemID'],
            $_SESSION["UserID"]
            );
            $i++;
        }

        $this->db->query("DELETE FROM MakeUp WHERE itemID=? and UserID=?;", "ii", $_SESSION["itemID"], $_SESSION["UserID"]);
        // delete item FROM specific clothes item table
        $this->db->query("DELETE FROM $table WHERE itemID=? and UserID=?;", "ii", $_SESSION["itemID"], $_SESSION["UserID"]);

        // delete item FROM Clothes
        $this->db->query("DELETE FROM Clothes WHERE itemID=? and UserID=?;", "ii", $_SESSION["itemID"], $_SESSION["UserID"]);



        header("Location: ?command=clothes_home");
    }


    public function clothes_add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $filename = "";
            // image uploaded
            if ($_FILES["article_img"]["error"] !== 4) {
                $tempname = $_FILES["article_img"]["tmp_name"];
                $filename = explode("/", $tempname)[5];
                $folder = dirname(__DIR__, 1) . "/images/" . $filename;
            
                // move the uploaded image into the folder
                if (!copy($tempname, $folder)) {
                    echo "<h3> Failed to upload image!</h3>";
                }
            }

            switch ($_POST["Type"]) {
                case "Accessory":
                    $this->db->query("CALL InsertAccessory(?, ?, ?, ?, ?, ?, ?);",
                    "ssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["AccessoryType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Dress":
                    $this->db->query("CALL InsertDress(?, ?, ?, ?, ?, ?, ?, ?, ?);",
                    "ssssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["DressLength"],
                    $_POST["DressSleeveLength"],
                    $_POST["DressType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Jewelry":
                    $this->db->query("CALL InsertJewelry(?, ?, ?, ?, ?, ?, ?);",
                    "ssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["JewelryType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Outerwear":
                    $this->db->query("CALL InsertOuterwear(?, ?, ?, ?, ?, ?, ?, ?, ?);",
                    "ssssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["OuterwearLength"],
                    $_POST["OuterwearWeight"],
                    $_POST["OuterwearType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Pants":
                    $this->db->query("CALL InsertPants(?, ?, ?, ?, ?, ?, ?, ?, ?);",
                    "ssssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["PantsLength"],
                    $_POST["PantsWeight"],
                    $_POST["PantsFit"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Shirt":
                    $this->db->query("CALL InsertShirt(?, ?, ?, ?, ?, ?, ?, ?, ?);",
                    "ssssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["ShirtLength"],
                    $_POST["ShirtSleeveLength"],
                    $_POST["ShirtType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Shoes":
                    $this->db->query("CALL InsertShoes(?, ?, ?, ?, ?, ?, ?);",
                    "ssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["ShoesType"],
                    $_SESSION["UserID"]
                    );
                    break;
                case "Skirt":
                    $this->db->query("CALL InsertSkirt(?, ?, ?, ?, ?, ?, ?, ?);",
                    "sssssssi",
                    $_POST["Pattern"],
                    $_POST["PrimaryColor"],
                    $_POST["Material"],
                    $filename,
                    $_POST["Brand"],
                    $_POST["SkirtLength"],
                    $_POST["SkirtType"],
                    $_SESSION["UserID"]
                    );
                    break;
                default:
                    print("default");
                    break;

            }
            // !! TODO: we need to make the post an array here for each of these !!
            
            // loop through all the secondary colors
            if (!empty($_POST["SecondaryColor"])) {
                foreach($_POST["SecondaryColor"] as $secondary) {
                    $this->db->query("CALL AddSecondaryColor($secondary)");
                }
            }
            // loop through all the formalities
            if (!empty($_POST["Formality"])) {
                foreach($_POST["Formality"] as $formal) {
                    $this->db->query("CALL AddFormality($formal)");
                }
            }
            // loop through all the styles
            if (!empty($_POST["Style"])) {
                foreach($_POST["Style"] as $style) {
                    $this->db->query("CALL AddStyle($style)");
                }
            }

            header("Location: ?command=clothes_home");
        }
        include("templates/clothes_add.php");
    }

    public function outfit_home() {
        $search = false;

        // display outfits matching search
        if (isset($_GET["search"])) {
            $search = true;
            $data = $this->search($_GET["search"], "Outfit");
            if (sizeof($data) === 0) {
                $error_msg = "No matching outfits found";
            }
        }
        // display all of the users uploaded outfits
        else {
            $data = array();
            // get outfits for the user
            $outfitIDs = $this->db->query("SELECT outfitID, outfitName FROM Outfit WHERE UserID = ?;", "i", $_SESSION["UserID"]);
            if (sizeof($outfitIDs) !== 0) {
                // find all of the itemIDs for each of the outfits
                foreach ($outfitIDs as $outfitID) {
                    // get itemID for item from MakeUp table
                    $itemIDs = $this->db->query("SELECT itemID FROM MakeUp WHERE outfitID = ? AND UserID = ?;", 
                    "ii", 
                    $outfitID["outfitID"],
                    $_SESSION["UserID"]
                    );
                    $outfit = array();
                    // add the outfit id to array to retrieve it later when the outfit is clicked
                    array_push($outfit, $outfitID["outfitID"]);
                    // add the outfit name to array for it to be displayed
                    array_push($outfit, $outfitID["outfitName"]);
                    // get the images FROM Clothes for the itemIDs
                    foreach ($itemIDs as $itemID) {
                        $item = $this->db->query("SELECT image FROM Clothes WHERE itemID = ? AND UserID = ?;", 
                        "ii",
                        $itemID["itemID"],
                        $_SESSION["UserID"]
                        )[0]["image"];
                        array_push($outfit, $item);
                    }

                    array_push($data, $outfit);
                }
            }
            // no outfits saved
            else {
                $error_msg = "You haven't saved any outfits";
            }
        }

        // if item was clicked on
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // save item ID of item that was clicked on
            $_SESSION['outfitID'] = $_POST['outfitID'];
            header("Location: ?command=outfit_detail");
        }

        include("templates/outfit_home.php");
    }

    public function outfit_detail() {
        $search = False;

        // get the attributes for the outfit
        $outfit = $this->db->query("SELECT outfitName, season, formality FROM Outfit WHERE outfitID = ? and UserID = ?;", 
        "ii", 
        $_SESSION["outfitID"],
        $_SESSION["UserID"])[0];

        $outfitName = $outfit["outfitName"];
        $formality = $outfit["formality"];
        $season = $outfit["season"];

        // get outfit items' pics
        // get itemID for item from MakeUp table
        $itemIDs = $this->db->query("SELECT itemID FROM MakeUp WHERE outfitID = ? AND UserID = ?;", 
        "ii", 
        $_SESSION["outfitID"],
        $_SESSION["UserID"]
        );
        $outfitItems = array();
        // get the images FROM Clothes for the itemIDs
        foreach ($itemIDs as $itemID) {
            $item = $this->db->query("SELECT itemID, image FROM Clothes WHERE itemID = ? AND UserID = ?;", 
            "ii",
            $itemID["itemID"],
            $_SESSION["UserID"]
            )[0];
            array_push($outfitItems, $item);
        }

        // save outfit button clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // update Outfit table with new attributes
            $this->db->query("UPDATE Outfit SET season=?, outfitName=?, formality=? WHERE outfitID=? AND UserID=?;",
            "sssii",
            $_POST["Season"],
            $_POST["Name"],
            $_POST["Formality"],
            $_SESSION["outfitID"],
            $_SESSION["UserID"]
            );

            // delete items from MakeUp table
            $itemIDs = $this->db->query("DELETE FROM MakeUp WHERE outfitID = ? AND UserID = ?;", 
            "ii", 
            $_SESSION["outfitID"],
            $_SESSION["UserID"]
            );

            // use outfitID to insert each item into MakeUp table
            for ($i = 0; $i < sizeof($_POST) - 3; $i++) {
                $this->db->query("INSERT INTO MakeUp VALUES (?, ?, ?);", 
                "iii", 
                $_SESSION["UserID"], 
                $_SESSION["outfitID"], 
                $_POST[$i]
                );
            }

            header("Location: ?command=outfit_home");
        }



        // get all matching search results
        if (isset($_GET["search"])) {
            $search = True;
            $data = $this->search($_GET["search"], "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // display all of the users uploaded clothes
            $data = $this->db->query("SELECT itemID, image FROM Clothes WHERE UserID = ?;", "i", $_SESSION["UserID"]);
            if (sizeof($data) === 0) {
                $error_msg = "You haven't uploaded any clothes";
            }
        }

        include("templates/outfit_detail.php");
    }

    public function outfit_delete() {
        // delete items from MakeUp table
        $itemIDs = $this->db->query("DELETE FROM MakeUp WHERE outfitID = ? AND UserID = ?;", 
        "ii", 
        $_SESSION["outfitID"],
        $_SESSION["UserID"]
        );

        // delete outfit from Outfit
        $this->db->query("DELETE FROM Outfit WHERE outfitID=? AND UserID=?;", "ii", 
        $_SESSION["outfitID"], 
        $_SESSION["UserID"]
        );

        header("Location: ?command=outfit_home");
    }

    public function outfit_create() {
        $search = False;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // insert into Outfit table using generated outfitID
            $this->db->query("CALL InsertOutfit(?, ?, ?, ?);",
            "sssi",
            $_POST["Season"],
            $_POST["Name"],
            $_POST["Formality"],
            $_SESSION["UserID"]
            );

            // get that outfitID from the table
            $outfitID = current($this->db->query("SELECT MAX(outfitID) FROM Outfit WHERE UserID=?;", "i", $_SESSION["UserID"])[0]);

            // use outfitID to insert each item into MakeUp table
            for ($i = 0; $i < sizeof($_POST) - 3; $i++) {
                $this->db->query("INSERT INTO MakeUp VALUES (?, ?, ?);", "iii", $_SESSION["UserID"], $outfitID, $_POST[$i]);
            }

            header("Location: ?command=outfit_home");
        }

        // get all matching search results
        if (isset($_GET["search"])) {
            $search = True;
            $data = $this->search($_GET["search"], "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // display all of the users uploaded clothes
            $data = $this->db->query("SELECT itemID, image FROM Clothes WHERE UserID = ?;", "i", $_SESSION["UserID"]);
            if (sizeof($data) === 0) {
                $error_msg = "You haven't uploaded any clothes";
            }
        }

        include("templates/outfit_create.php");
    }
}