<?php
//DISPLAY PHP ERRORS
// error_reporting(0);
error_reporting(E_ALL); ini_set('display_errors', 1);

//INCLUDE APPLICATIONS SCRIPT
include 'ApplicationFunctions.php';

//DEFINE CURRENT DATE
date_default_timezone_set('GMT');
$time = date('Y-m-d H:i:s');
$ussd = new ApplicationFunctions();
$number = $_GET['number'];
$data = $_GET['body'];
$sessionID = $_GET['sessionID'];
$amount = "";
$network="";
$recipient="";

$tp = rand(10,1000000);


//FUNCTION TO CHECK IF SESSION EXISTS AND THE SESSION COUNT
/*GET SESSION STATE OF THE USER*/
$sess = intval($ussd -> sessionManager($number));

//CREATING LOG
// $write = $time . "|Request|" . $number . "|" . $sessionID . "|" . $data ."|".$sess. PHP_EOL;
// file_put_contents('ussd_access.log', $write, FILE_APPEND);

if ($sess == "0") {#NO SESSION WAS FOUND -> DISPLAY WELCOME MENU
    $ussd -> IdentifyUser($number);
    $reply = "Welcome to Insta-Money Transfer" . "\r\n" . "1.  Send Money" . "\r\n" ."2. Exit";
    $type = "1";
  }


else {
  switch($sess) {
        case 1 : #SESSION COUNT =1 #SERVICE LEVEL 1
          if ($data=='1')
          {
                $reply = "Send Money" . "\r\n" ."1. To MTN" . "\r\n" ."2. To Vodafone" . "\r\n" ."3. To Airtel". "\r\n" ."4. To Tigo". "\r\n" ."5. Exit";
                $type = "1";
                $ussd -> UpdateTransactionType($number, "transaction_type", "Debit");
              }
          elseif ($data=='2')
          {
              $reply = "Thank you for using Insta-Money transfer";
              $type = "0";
              $ussd -> deleteSession($number);
          }
          else {
            $reply = "Invalid option selected";
            $type="0";
            $ussd->deleteSession($number);
          }
          break;

    case 2: #SESSION COUNT =2 #SERVICE LEVEL 2
    //get recipient's number and save in a variable

      if($data=='1')
      {
        $reply = "Enter recipient's number";
        $type = '1';
        $ussd -> UpdateTransactionType($number, "network", "MTN");
        $network = "MTN";
      }
      else if( $data=='2')
      {
        $reply = "Enter recipient's number";
        $type = '1';
        $ussd -> UpdateTransactionType($number, "network", "Vodafone");
        $network = "Vodafone";
      }
      else if( $data=='3')
      {
        $reply = "Enter recipient's number";
        $type = '1';
        $ussd -> UpdateTransactionType($number, "network", "Airtel");
        $network = "Airtel";
      }
      else if( $data=='4')
      {
        $reply = "Enter recipient's number";
        $type = '1';
        $ussd -> UpdateTransactionType($number, "network", "Tigo");
        $network = "Tigo";
      }
      else if( $data=='5')
      {
        $reply = "Thank you for using Insta-Money Transfer";
        $type = '0';
        $ussd ->deleteSession($number);
      }
      else {
        $reply="Invalid option selected";
        $type='0';
        $ussd->deleteSession($number);
      }
      break;

  case 3: #SESSION COUNT 3 SERVICE LEVEL 3
  //get amount from user and save it in a variable

  if (!empty($data))
  {
    $reply = "Enter amount";
    $type = '1';
    $ussd -> UpdateTransactionType($number, "recipientcol", $data);
    $recipient = $number;
  }
  else {
    $reply="invalid option selected";
    $type='0';
    $ussd->deleteSession($number);
  }
  break;

  case 4: #SESSION COUNT 4 = SERVICE LEVEL 4
  if (!empty($data))
  {
    $reply = "Are you sure you wish to continue with this transaction?" . "\r\n" . "Enter 1 for yes and 2 for no";
    $type = '1';
    $ussd ->UpdateTransactionType($number, "amountcol", $data);
    $amount = $data;
  }
  else {
    $reply="invalid option selected";
    $type='0';
    $ussd->deleteSession($number);
  }
  break;

  //get answer from user - yes or no
  //if yes, continue. if no, exit
  case 5: #SESSION COUNT 5 SERVICE LEVEL 5
  //if yes:
  if ($data=='1')
  {
    $reply = "Transaction in Progress.";
    $type = '1';
    $ussd->UpdateTransactionType($number, "confirmcol", "confirmed");

    $recipient=$ussd->GetTransactionType($number,"recipientcol");
    $trans=$ussd->GetTransactionType($number,"transaction_type");
    $net=$ussd->GetTransactionType($number,"network");
    $amt=$ussd->GetTransactionType($number,"amountcol");
    $conf=$ussd->GetTransactionType($number,"confirmcol");

    $network = $ussd->getVendor($number);

    $response = $ussd->sendMoney($recipient, $amount, $tp, $network);
    var_dump($response);

    $ussd->transactions($tp, $number, $trans, $net, $recipient, $amt, $conf);

    $ussd->sendSMS($recipient);
    $ussd->deleteSession($number);
}
  else if( $data=='2')
  {
    $reply = "Unfortunately, your transaction has stopped.";
    $type = '0';
    $ussd ->deleteSession($number);
  }
    break;
}
}

  $response = $number.'|'.$reply.'|'.$sessionID.'|'.$type;
  // $write = $time . "|Request_reply|". $response . PHP_EOL;
  // file_put_contents('ussd_access.log', $write, FILE_APPEND);
  echo $response;

  ?>
