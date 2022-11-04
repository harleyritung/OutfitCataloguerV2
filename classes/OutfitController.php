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
        $params = array_merge($_GET, array('test' => 'testvalue'));
        $new_query_string = http_build_query($params);
        // print_r($_SESSION["command"]);
        switch ($this->command) {
            case "logout":
                $this->delete_session();
                session_start();
                $this->login();
                $_SESSION["command"] = "logout";
                break;
            case "login":
                // print("login");
                $this->login();
                $_SESSION["command"] = "login";
                break;
            case "create_account":
                $this->create_account();
                $_SESSION["command"] = "create_account";
                break;
            case "home":
                $this->home();
                $_SESSION["command"] = "home";
                break;
            case "clothes_home":
                $this->clothes_home();
                $_SESSION["command"] = "clothes_home";
                break;
            case "clothes_add":
                $this->clothes_add();
                $_SESSION["command"] = "clothes_add";
                break;
            case "outfit_home":
                $this->outfit_home();
                $_SESSION["command"] = "outfit_home";
                break;
            default:
                print("default");
                switch($_SESSION["command"]) {
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
                    case "outfit_home":
                        $this->outfit_home();
                        break;
                    default:
                        $this->login();
                        break;
                }
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
                $skirt = "select itemID from Skirt 
                where SkirtLength like ?
                or SkirtType like ?";
                $shoes = "select itemID from Shoes where ShoesType like ?";
                $formality = "select itemID from clothes_Formality where Formality like ?";
                $secondaryColor = "select itemID from Clothes_SecondaryColor where secondaryColor like ?";
                $style = "select itemID from Clothes_Style where Style like ?";
    
                $data = $this->db->query("select itemID, image from Clothes
                where (pattern like ?
                or primaryColor like ?
                or material like ?
                or brand like ?)
                and UserID = ?
                and itemID in (
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
                ");",
                "ssssissssssssssssssssssss",
                $searchString, $searchString, $searchString, $searchString,
                // this will get replaced with UserID from session
                $_SESSION["UserID"],
                $searchString,
                $searchString, $searchString, $searchString,
                $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString, $searchString,
                $searchString, $searchString,
                $searchString, $searchString, $searchString, $searchString
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
                3, $searchString, $searchString, $searchString
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
        // display all of the users uploaded clothes
        $data = $this->db->query("select itemID, image from Clothes where UserID = ?;", "i", $_SESSION["UserID"]);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            print_r($_GET);
            // replace black with search string
            $data = $this->search("black", "Clothes");
            if (sizeof($data) == 0) {
                $error_msg = "No matching clothes found";
            }
        }
        else {
            // select all clothes with this UserID and display
        }
        include("templates/clothes_home.php");
    }

    public function clothes_add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $filename = "";
            // print_r($_POST);
            // image uploaded
            if ($_FILES["article_img"]["error"] !== 4) {
                $filename = $_FILES["article_img"]["name"];
                $tempname = $_FILES["article_img"]["tmp_name"];
                $folder = "/Applications/XAMPP/xamppfiles/htdocs/cs4750/OutfitCataloguerV2/images/" . $filename;
            
                // move the uploaded image into the folder
                if (copy($tempname, $folder)) {
                    // echo "<h3> Image uploaded successfully!</h3>";
                } else {
                    echo "<h3> Failed to upload image!</h3>";
                }
            }

            switch ($_POST["Type"]) {
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
        else {
            include("templates/clothes_add.php");
        }
    }

    public function outfit_home() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            print_r($_GET);
            // replace black with search string
            $data = $this->search("casual", "Outfit");
            if (sizeof($data) == 0) {
                $error_msg = "No matching outfits found";
            }
        }
        include("templates/outfit_home.php");
    }
}