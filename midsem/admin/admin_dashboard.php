<?php
require_once '../ApplicationFunctions.php';
 // transactions();
 // displayInfo();

//  if (isset($_POST['search']))
//  {
//    search();
//  }
//
//  public function search($number)
//  {
//    $db = Database::getInstance();
//    try{
//      $sql = $db->prepare("SELECT * FROM transactions WHERE number = :number");
//      $sql->bindParam(":number",$number);
//      $sql->execute();
//     $res = $sql->fetch(PDO::FETCH_ASSOC);
//     if($res !== FALSE)
//     {
//       echo $res['number'] . '<br />';
//       echo $res['transaction_type'] . '<br />';
//       echo $res['network'] . '<br />';
//       echo $res['recipientcol'] . '<br />';
//       echo $res['amountcol'] . '<br />';
//       echo $res['confirmcol'] . '<br />';
//     }
//   }
//   catch (PDOException $e)
//    {
//     #echo $e->getMessage();
//     return NULL;
//   }
//  }
// ?>

<!DOCTYPE html>
<html>
  <head>
       <!--Import Google Icon Font-->
       <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
       <!--Import materialize.css-->
       <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
       <link type="text/css" rel="stylesheet" href="admin_dashboard.css"  media="screen,projection"/>

       <!--Let browser know website is optimized for mobile-->
       <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>

<body  onload="getTable()">

  <!--Import jQuery before materialize.js-->
     <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
     <script type="text/javascript" src="js/materialize.min.js"></script>

  <h3> Admin Dashboard </h3>

  <div class = "row">
    <div class = "input-field col s10">
     <input type="text" id = "search" name = "search">
     <label for"search">Search number</label>
   </div>
   <button class="btn waves-effect waves-light" type="submit" name="searchbutton">Search
     <i class="material-icons right">search</i>
   </button>
 </div>
<div>
  <p id="table"></p>
</div>

<script>
  function getTable()
  {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
      if(this.readyState ==4 && this.status == 200)
      {
        document.getElementById('table').innerHTML=this.responseText;
      }
    };
    xmlhttp.open('GET','table.php?', true);
    xmlhttp.send();
  }
</script>
</body>
</html>
