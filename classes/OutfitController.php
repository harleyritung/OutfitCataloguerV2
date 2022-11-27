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
        // $params = array_merge($_GET, array('test' => 'testvalue'));
        // $new_query_string = http_build_query($params);
        // print_r($_SESSION["command"]);
        switch ($this->command) {
            case "logout":
                $this->delete_session();
                session_start();
                $this->login();
                break;
            case "login":
                // print("login");
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
                $accessory = "select itemID from Accessory where AccessoryType like ?";
                $dress = "select itemID from Dress 
                where DressLength like ?
                or DressSleeveLength like ?
                or DressType like ?";
                $jewelry ="select itemID from Jewelry where jewelryType like ?";
                $outerwear = "select itemID from Outerwear 
                where OuterwearLength like ?
                or OuterwearWeight like ?
                or OuterWearType like ?";
                $pants = "select itemID from Pants 
                where PantsLength like ?
                or PantsWeight like ?
                or PantsFit like ?";
                $shirt = "select itemID from Shirt 
                where ShirtLength like ?
                or ShirtSleeveLength like ?
                or ShirtType like ?";
                $shoes = "select itemID from Shoes where ShoesType like ?";
                $skirt = "select itemID from Skirt 
                where SkirtLength like ?
                or SkirtType like ?";
                $formality = "select itemID from clothes_Formality where Formality like ?";
                $secondaryColor = "select itemID from Clothes_SecondaryColor where secondaryColor like ?";
                $style = "select itemID from Clothes_Style where Style like ?";
    
                $data = $this->db->query("select itemID, image from Clothes
                where UserID = ?
                and (pattern like ?
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
                $outfitIDs = $this->db->query("select outfitID from Outfit
                where UserID = ? and
                (season = ? or
                outfitName = ? or
                formality = ?);",
                "isss",
                $_SESSION["UserID"], $searchString, $searchString, $searchString
                );
                // find all of the itemIDs for each of the outfits
                foreach ($outfitIDs[0] as $outfitID) {
                    print($outfitID);
                    $itemID = $this->db->query("select itemID from MakeUp where outfitID = ? and UserID = ?;", 
                    "ii", 
                    $outfitID, 3
                    );
                    print_r($itemID);
                    array_push($itemIDs, $itemID);
                    $outfit = array();
                    // get the images from Clothes for the itemIDs
                    foreach ($itemIDs[0][0] as $itemID) {
                        print($itemID);
                        $item = $this->db->query("select itemID, image from Clothes where itemID = ? and UserID = ?;", 
                        "ii",
                        $itemID, 3
                        );
                        array_push($outfit, $item);
                    }
                    array_push($data, $outfit);
                    $itemIDs = array();
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
            $data = $this->db->query("select * from User where email_address = ?;", "s", $_POST["email_address"]);
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
            $data = $this->db->query("select * from User where email_address = ?;", "s", $_POST["email_address"]);
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
                            "insert into User (name, password, email_address) values (?, ?, ?);",
                            "sss",
                            $_POST["name"],
                            password_hash($_POST["password1"], PASSWORD_DEFAULT),
                            $_POST["email_address"]
                        );
                        if ($insert === false) {
                            $error_msg = "Error inserting user";
                        } else {
                            $data = $this->db->query("select * from User where email_address = ?;", "s", $_POST["email_address"]);
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
        $outfits = $this->db->query("select * from Outfit where UserID = ?;", "i", $_SESSION["UserID"]);
        if (empty($data)) {
            $error_msg = "You haven't created any outfits.";
        }
        include("templates/home.php");
    }

    public function clothes_home() {
        $search = False;
        if (isset($_GET["search"])) {
            $search = True;
            // replace black with search string
            $data = $this->search($_GET["search"], "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // display all of the users uploaded clothes
            $data = $this->db->query("select itemID, image from Clothes where UserID = ?;", "i", $_SESSION["UserID"]);
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
        // pulls data from Clothes table for item
        $clothes_table_data = $this->db->query("select * from Clothes where itemID = ?;", "i", $_SESSION['itemID'])[0];
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
        while (sizeof($specific_table_data) === 0 and $i < sizeof($article_types)) {
            $table = $article_types[$i];
            $specific_table_data = $this->db->query("select * from $table where itemID=?;", "i", $_SESSION['itemID']);
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


                print('file uploaded');
                $tempname = $_FILES["article_img"]["tmp_name"];
                $image_name = explode("/", $tempname)[5];
                $folder = "/Applications/XAMPP/xamppfiles/htdocs/cs4750/OutfitCataloguerV2/images/" . $image_name;
            
                // move the uploaded image into the folder
                if (!copy($tempname, $folder)) {
                    echo "<h3> Failed to upload image!</h3>";
                }
            }

            // update Clothes table
            $this->db->query("update Clothes set image=?, brand=?, material=?, pattern=?, primaryColor=? where itemID=?;",
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
                case "Skirt":
                    $this->db->query("update Skirt set SkirtType=?, SkirtLength=? where itemID=?;",
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
        // delete item from Clothes
        $this->db->query("delete from Clothes where itemID=?;", "i", $_SESSION["itemID"]);

        // finds the article type table that this item belongs to
        $article_types = array('Accessory', 'Dress', 'Jewelry', 'Outerwear', 'Pants', 'Shirt', 'Shoes', 'Skirt');
        $i = 0;
        $specific_table_data = array();
        // loops through tables until one contains this itemID
        while (sizeof($specific_table_data) === 0 and $i < sizeof($article_types)) {
            $table = $article_types[$i];
            $specific_table_data = $this->db->query("select * from $table where itemID=?;", "i", $_SESSION['itemID']);
            $i++;
        }

        // delete item from specific clothes item table
        $this->db->query("delete from $table where itemID=?;", "i", $_SESSION["itemID"]);

        header("Location: ?command=clothes_home");
    }


    public function clothes_add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $filename = "";
            // image uploaded
            if ($_FILES["article_img"]["error"] !== 4) {
                $tempname = $_FILES["article_img"]["tmp_name"];
                $filename = explode("/", $tempname)[5];
                $folder = "/Applications/XAMPP/xamppfiles/htdocs/cs4750/OutfitCataloguerV2/images/" . $filename;
            
                // move the uploaded image into the folder
                if (!copy($tempname, $folder)) {
                    echo "<h3> Failed to upload image!</h3>";
                }
            }

            switch ($_POST["Type"]) {
                case "Accessory":
                    $this->db->query("call InsertAccessory(?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertDress(?, ?, ?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertJewelry(?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertOuterwear(?, ?, ?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertPants(?, ?, ?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertShirt(?, ?, ?, ?, ?, ?, ?, ?, ?);",
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
                    $this->db->query("call InsertDress(?, ?, ?, ?, ?, ?, ?, ?, ?);",
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
                case "Skirt":
                    $this->db->query("call InsertSkirt(?, ?, ?, ?, ?, ?, ?, ?);",
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
            header("Location: ?command=clothes_home");
        }
        include("templates/clothes_add.php");
    }

    public function outfit_home() {
        if (isset($_GET["search"])) {
            $data = $this->search($_GET["search"], "Outfit");
            if (sizeof($data) === 0) {
                $error_msg = "No matching outfits found";
            }
        }
        else {
            $data = array();
            // display all of the users uploaded outfits
            $itemIDs = array();
            // get outfits for the user
            $outfitIDs = $this->db->query("select outfitID from Outfit where UserID = ?;", "i", $_SESSION["UserID"]);
            // print_r($outfitIDs);
            if (sizeof($outfitIDs) !== 0) {
                // find all of the itemIDs for each of the outfits
                foreach ($outfitIDs[0] as $outfitID) {
                    print($outfitID);
                    $itemID = $this->db->query("select itemID from MakeUp where outfitID = ? and UserID = ?;", 
                    "ii", 
                    $outfitID, 3
                    );
                    print_r($itemID);
                    array_push($itemIDs, $itemID);
                    $outfit = array();
                    // get the images from Clothes for the itemIDs
                    foreach ($itemIDs[0][0] as $itemID) {
                        print($itemID);
                        $item = $this->db->query("select itemID, image from Clothes where itemID = ? and UserID = ?;", 
                        "ii",
                        $itemID, 3
                        );
                        array_push($outfit, $item);
                    }
                    array_push($data, $outfit);
                    $itemIDs = array();
                }
            }
            else {
                $error_msg = "You haven't saved any outfits";
            }
        }
        include("templates/outfit_home.php");
    }

    public function outfit_create() {
        $search = False;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            print_r($_POST);
            $postArr = $_POST;
        }
        if (isset($_GET["search"])) {
            print("search");
            $search = True;
            // replace black with search string
            $data = $this->search($_GET["search"], "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // display all of the users uploaded clothes
            $data = $this->db->query("select itemID, image from Clothes where UserID = ?;", "i", $_SESSION["UserID"]);
            if (sizeof($data) === 0) {
                $error_msg = "You haven't uploaded any clothes";
            }
        }
        include("templates/outfit_create.php");
    }
}