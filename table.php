<?php
require_once "database.php";

header("Content-Type: application/json; charset=UTF-8");

displayTable();
// search('020');

function displayTable()
{
  $db = Database::getInstance();

  try
  {
    $getinfo = $db->prepare("SELECT * FROM transactions");
    $getinfo->execute();

    $info = $getinfo->fetchAll();

    echo "<table>";
    echo "<tr><th>Transaction ID</th><th>Sender Number</th><th>Transaction Type</th><th>Mobile Network</th><th>Recipient Number</th><th>Amount Sent</th><th>Status</th></tr>";

    foreach( $info as $row) {
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['number']."</td>";
        echo "<td>".$row['transaction_type']."</td>";
        echo "<td>".$row['network']."</td>";
        echo "<td>".$row['recipientcol']."</td>";
        echo "<td>".$row['amountcol']."</td>";
        echo "<td>".$row['confirmcol']."</td>";
        echo "</tr>";
    }
    echo "</table>";
  }
  catch(PDOException $e)
   {
    #echo $e->getMessage();
    return NULL;
  }
}

 if(isset($_GET['searchbutton']) && !empty($_GET['search']))
 {
   echo "2";
   search($_GET['search']);
}
  function search($number)
  {
    $db = Database::getInstance();
    try {
  $info = $db->prepare("SELECT * FROM transactions WHERE number LIKE '%:number%'");
  $info->bindParam(":number", $number);
  $info->execute();

  $result = $info->fetchAll();

  echo "<table>";
  echo "<tr><th>Sender Number</th><th>Transaction Type</th><th>Mobile Network</th><th>Recipient Number</th><th>Amount Sent</th><th>Status</th></tr>";

  foreach( $result as $row) {
      echo "<tr>";
      echo "<td>".$row['number']."</td>";
      echo "<td>".$row['transaction_type']."</td>";
      echo "<td>".$row['network']."</td>";
      echo "<td>".$row['recipientcol']."</td>";
      echo "<td>".$row['amountcol']."</td>";
      echo "<td>".$row['confirmcol']."</td>";
      echo "</tr>";
  }
  echo "</table>";
}
catch(PDOException $e)
 {
  #echo $e->getMessage();
  return NULL;
}
}
