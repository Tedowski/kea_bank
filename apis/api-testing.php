<?php

// header('Access-Control-Allow-Origin: *');

ini_set('user_agent', 'any');

// ini_set('user_agent', 'any');
// ini_set('display_errors', 0);
// ini_set('user_agent, 'any);
// ini_set('display_errors', 0);


// session_start();
// session_start();
// session_destroy();
// $_SESSION['sName'] = $_GET['name'] ?? '';
// $_SESSION['name'] = 'Tadeas';

// $sNewVarForNameFromSession = $_SESSION['sName'];
// $sSeshName = $_SESSION['name'];

// if(isset($_SESSION['name'])) {
//     echo "Session is set with name $sSeshName";
// }

// checks if set
// checks if set and empty
// check if var is integer
// check if var has numeric value
// check if var is only digits
// get number value of a string
// get length of string

// if(!empty($_SESSION['name'])) {
//     echo 'not empty';
// }

// $iNumber = '23a';

// if(is_int($iNumber)) {
//     echo 'it is an integer';
// } else if(ctype_digit($iNumber)) {
//     echo 'It is a string but all the characters are numbers';
// } else {
//     echo 'It is a string that has letters';
// }

// $iValNumber = '40.00';

// echo is_numeric($iValNumber);

// echo print_r($iValNumber);

// function returnR($variable) {
//     return print_r($variable);
// }

// $sPrint = returnR($iValNumber);

// echo $sPrint;

// $sEmail = 'a@a.c';

// if(filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
//     echo 'That is a valid E-mail';
// } else {
//     echo 'That is not a valid E-mail';
// };

// if(intval($iValNumber) > 2000) {
//     echo 'it is above 2000';
// } else {
//     echo 'it is less';
// }

// $sName = $_GET['name'] ?? '';
// $sLastName = $_GET['lastName'] ?? '';

// $jObject = json_decode('{}');
// $jObject = new stdClass();
// $jObject->name = $sName;
// $jObject->lastName = $sLastName;

// $jObjectTwo->name = $sName;
// $jObjectTwo->lastName = $sLastName;

//echo json_encode($jObject);

// echo json_encode($jObject);
// $sFullName = $jObject->name.' '.$jObject->lastName;

//echo json_encode($sFullName);

$jArray = array(1, 2, 3);
// array_push($jArray, $jObject, $jObjectTwo);

// echo json_encode($jArray, JSON_PRETTY_PRINT);

echo json_encode($jArray);



// array_push($jArray, 1, 2, 3, 4, 5);
// echo json_encode($jArray);
// $iCount = 0;

// foreach($jArray as $iNumber) {
//     $iCount += $iNumber;
// }

// echo $iCount;

// for($i = 0; $i < count($jArray); $i++) {
//     $i += $iCount;
// }

// for($i = 1; $i < 6; $i++) {
//     $iCount += $i;
// }
// echo $iCount;

// echo file_get_contents('https://www.kea.dk/');