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
            case "outfit_home":
                $this->outfit_home();
                break;
            default:
                $this->login();
                break;
        }
    }

        // finds rows that have attributes like *searchString* in table (Outfit or Clothes)
        public function search($searchString, $table) {
            // search in Clothes table
            $data = NULL;
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
                where pattern like ?
                or primaryColor like ?
                or material like ?
                or brand like ?
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
            else if ($table === "Outfit") {
    
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
        if (!isset($_SESSION["name"])) {
            header("Location: ?command=login");
        }
        // check if any rows in outfits for this user, then display them or say no outfits created
        $outfits = $this->db->query("select * from Outfit where UserID = ?;", "i", $_SESSION["UserID"]);
        if (empty($data)) {
            $error_msg = "You haven't created any outfits.";
        }
        include("templates/home.php");
    }

    public function clothes_home() {
        $data = $this->search("black", "Clothes");
        include("templates/clothes_home.php");
    }

    public function outfit_home() {

    }
}