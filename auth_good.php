<?php
/*
=====================================
# CREATE SIMPLE RESTFUL WEB SERVICE #
=====================================
*/

# Using Authentication Realm basic
# Using Mysqli connection

/*
init the credential
*/
$valid_passwords = array ("admin" => "admin");
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

/*
is validated?
*/
if (!$validated) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  die ('Not authorized');
}

/*
try to access db with mysqli
*/
$nim = $_GET['nim'];

/*
start connection with mysqli
*/
$conn  = mysqli_connect("127.0.0.1", "database_user", "database_password", "database_name");
	
/*
debug mode
*/
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

/*
set the query
*/
$query = "SELECT nama,prodi FROM mahasiswa WHERE nim = $nim ORDER by id DESC LIMIT 1";

if ($result = mysqli_query($conn, $query)) {

    /* fetch associative array */
    while ($row = mysqli_fetch_row($result)) {
       $data['nama'] = $row[0];
       $data['prodi'] = $row[1];
    }
    /* free result set */
    mysqli_free_result($result);
}

/*
close connection
*/
mysqli_close($conn);


/* encode to json data */
$json = json_encode($data, true);

/* view the json */
echo $json;

?>