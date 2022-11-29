<?php

class Database {
    private $mysqli;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli(Config::$db["host"], 
                Config::$db["user"], Config::$db["pass"], 
                Config::$db["database"]);
    }

    public function query($query, $bparam=null, ...$params) {
        $stmt = $this->mysqli->prepare($query);

        $query_command = explode(" ", $query)[0];

        if ($bparam != null) {
            // if command is insert
            if ($query_command === "INSERT" or $query_command === "UPDATE") {
                // surround string parameters with quotes to prevent spaces from being removed
                for ($i = 0; $i < sizeof($params); $i++) {
                    // if param is a string and not an image file name
                    if ($bparam[$i] === "s" && (substr($params[$i], 0, 3) !== "php")) {
                        // surround string with double quotes
                        $params[$i] = '"'.$params[$i].'"';
                    }
                }
            }
            $stmt->bind_param($bparam, ...$params);
        }

        if (!$stmt->execute()) {
            return false;
        }

        if (($res = $stmt->get_result()) !== false) {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

        return true;
    }
}