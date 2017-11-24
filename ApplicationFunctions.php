<?php
require 'database.php';

/**
*This class contains core logic of the USSD application
**/
class ApplicationFunctions {

   public function __construct(){
   }
   public function __destruct(){
   }
   public function IdentifyUser($number){
     $db = Database::getInstance();
     try {
      $stmt = $db->prepare("insert into sessionmanager(number) values (:number)");
      $stmt->bindParam(":number",$number);
      $stmt->execute();
        if($stmt->rowCount() > 0)
        {
           return TRUE;
        }
      }
    catch (PDOException $e) {
      #$e->getMessage();
      return FALSE;
   }
 }
 /**
 * Method to delete a user session
 *@param number
 *@return boolean
 **/
 public function deleteSession($number)
{
   $db = Database::getInstance();
   try {
   $stmt = $db->prepare("Delete FROM sessionmanager where number= :number");
   $stmt->bindParam(":number",$number);
   $stmt->execute();
   if($stmt->rowCount() > 0)
   {
      return TRUE;
   }
  }
  catch (PDOException $e)
    {
     #echo $e->getMessage();
     return FALSE;
   }
 }
 /**
  *Method to reset a users session to the first case. (Delete all of the users records except his number)
  *@param number
  *@return Boolean
**/
   public function deleteAllSession($number)
   {
      $db = Database::getInstance();
      try {
         $stmt = $db->prepare("UPDATE sessionmanager SET transaction_type = NULL where number= :number");
         $stmt->bindParam(":number",$number);
         $stmt->execute();
         if($stmt->rowCount() > 0)
         {
           return TRUE;
         }
       }
       catch (PDOException $e)
       {
         #echo $e->getMessage();
         return FALSE;
       }
     }
  /**
   * Method to update user session with the actual type of transaction or details of the transaction *currently being held
   *@param number, collumn, transaction type
   *@param Boolean
  **/
   public function UpdateTransactionType($number, $col, $trans_type)
   {
      $db = Database::getInstance();
      try
      {
         $stmt = $db->prepare("update sessionmanager set " .$col. " = :trans_type where number = :number");
         $params = array(":number"=>$number,":trans_type"=>$trans_type);
         $stmt->execute($params);
         if($stmt->rowCount() > 0)
         {
            return true;
         }
       }
      catch (PDOException $e)
      {
         #echo $e->getMessage();
         return FALSE;
      }
    }
    /**
    * Method to query specific details from the session manager. (Get value held in a specific column)
    *@param msisdn, specific column to query
    *@return string
   **/
      public function GetTransactionType($number, $col)
      {
         $db = Database::getInstance();
         try
         {
            $stmt = $db->prepare("SELECT " .$col. " FROM  sessionmanager WHERE  number = :number");
            $stmt->bindParam(":number",$number);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if($res !== FALSE)
            {
               return $res[$col];
            }
          }
         catch (PDOException $e)
         {
            #echo $e->getMessage();
            return NULL;
         }
      }
/**
  *Method to query users session state. checking if the user has an existing session and if so the session count.
  *@param msisdn, specific column to query
  *@return string
**/
  public function sessionManager($number)
    {
      $db = Database::getInstance();
      try
      {
        $stmt = $db->prepare("SELECT (COUNT(number)+ COUNT(transaction_type) +COUNT(network) + COUNT(recipientcol)+ COUNT(amountcol)+ COUNT(confirmcol)) AS counter FROM sessionmanager WHERE number = :number");
        $stmt->bindParam(":number",$number);
        $stmt->execute();
       $res = $stmt->fetch(PDO::FETCH_ASSOC);
       if($res !== FALSE)
       {
          return $res['counter'];
       }
     }
     catch (PDOException $e)
      {
       #echo $e->getMessage();
       return NULL;
     }
    }

  public function transactions($tp, $num, $trans, $net, $rec, $amt, $conf)
  {
    $db = Database::getInstance();
    try {
      $stmt = $db->prepare("INSERT INTO transactions(id, number, transaction_type, network, recipientcol, amountcol, confirmcol)
      VALUES (:id, :number, :transaction_type, :network, :recipientcol, :amountcol, :confirmcol)");
      $stmt->bindParam(":id", $tp);
      $stmt->bindParam(":number", $num);
      $stmt->bindParam(":transaction_type", $trans);
      $stmt->bindParam(":network", $net);
      $stmt->bindParam(":recipientcol", $rec);
      $stmt->bindParam(":amountcol", $amt);
      $stmt->bindParam(":confirmcol", $conf);

      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if($result !== FALSE)
      {
          return $result;
        }
        else {
          return NULL;
        }
      }
    catch (Exception $e) {
      return NULL;
    }
  }

  /**
  * Method to send money through API
  **/
  public function sendMoney($recipient, $amount, $tp, $network)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL=> "http://pay.npontu.com/api/pay",
      CURLOPT_RETURNTRANSFER=> 1,
      CURLOPT_POST=> 1,
      CURLOPT_POSTFIELDS=>array(
        "amt"=>$amount,
        "number"=>$recipient,
        "pin"=>NULL,
        "vou"=>"",
        "uid"=>"nana.bentil",
        "pass"=>"nana.bentil",
        "tp"=>$tp,
        "trans_type"=>"debit",
        "msg"=>"test1",
        "vendor"=>$network,
        "cbk"=>"http://gmpay.npontu.com/api/tigo"
      )
    ));

    $response = curl_exec($curl);
    return $response;
    var_dump($response);

    curl_close($curl);
  }

  public function callback($sender, $amount, $tp, $network)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_WRITEFUNCTION=> "http://gmpay.npontu.com/api/tigo",
      CURLOPT_RETURNTRANSFER=> 1,
      CURLOPT_POST=> 1,
      CURLOPT_POSTFIELDS=>array(
        "amt"=>$amount,
        "number"=>$sender,
        "pin"=>NULL,
        "vou"=>"",
        "uid"=>"nana.bentil",
        "pass"=>"nana.bentil",
        "tp"=>$tp,
        "trans_type"=>"credit",
        "msg"=>"You have received GHS ".$amount."of money from ".$sender,
        "vendor"=>$network,
        // "cbk"=>"http://gmpay.npontu.com/api/tigo"
      )
    ));

    $response = curl_exec($curl);
    return $response;
    var_dump($response);

    curl_close($curl);
  }

//   public function displayInfo()
//   {
//     $db = Database::getInstance();
//     try {
//       $sql = $db->prepare("SELECT * FROM sessionmanager");
//       $sql->execute();
//       $info = $sql->fetchAll();
//       foreach ($info as $rec) {
//         echo $rec['number'] . '<br />';
//         echo $rec['transaction_type'] . '<br />';
//         echo $rec['network'] . '<br />';
//         echo $rec['recipientcol'] . '<br />';
//         echo $rec['amountcol'] . '<br />';
//         echo $rec['confirmcol'] . '<br />';
//   }
// }
// catch (Exception $e) {
//   return NULL;
// }
// }

/**
* Method to search on button click
*/

public function search($number)
{
  $db = Database::getInstance();
  try{
    $sql = $db->prepare("SELECT * FROM transactions WHERE number LIKE '%:number%'");
    $sql->bindParam(":number",$number);
    $sql->execute();
   $res = $sql->fetch(PDO::FETCH_ASSOC);
   if($res !== FALSE)
   {
     echo $res['number'] . '<br />';
     echo $res['transaction_type'] . '<br />';
     echo $res['network'] . '<br />';
     echo $res['recipientcol'] . '<br />';
     echo $res['amountcol'] . '<br />';
     echo $res['confirmcol'] . '<br />';
   }
 }
 catch (PDOException $e)
  {
   #echo $e->getMessage();
   return NULL;
 }
}

public function getVendor($number)
{
  $vendor = "";
  $prefix = substr($number, 0, 3);
  if ($prefix == "020" || $prefix == "050")
  {
    $vendor = "Vodafone";
    return $vendor;
  }
  elseif($prefix == "024" || $prefix == "054" || $prefix=="055")
  {
    $vendor = "MTN";
    return $vendor;
  }
  elseif($prefix == "026" || $prefix == "056")
  {
    $vendor = "Airtel";
    return $vendor;
  }
  elseif($prefix == "027" || $prefix == "057")
  {
    $vendor = "Tigo";
    return $vendor;
  }
  else {
    return NULL;
  }
}

public function sendSMS($recipient)
{
  $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=$recipient&source=Test&message=welcome";
}
}
