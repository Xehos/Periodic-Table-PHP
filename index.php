<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "periodic_table";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, symbol, phase, category, xpos, ypos FROM elements";
$result = $conn->query($sql);
$elements = array();

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //echo "id: " . $row["name"]. " - Name: " . $row["name"]. " " . $row["xpos"]. "<br>";
    $elem = array("id"=>$row["id"],"name"=>$row["name"],"symbol"=>$row["symbol"],"phase"=>$row["phase"], "category"=>$row["category"],"xpos"=>$row["xpos"],"ypos"=>$row["ypos"]);
    //array_push($elements, $row["name"]=>$elem);
    array_push($elements, $elem);



  }
} else {
  echo "0 results";
}
$conn->close();




?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Periodická tabulka prvků</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">

</head>
<body>
<h1 class="text-center mt-5" id="heading1">Periodic table of elements</h1>
<table class="table-responsive table table-bordered" style="max-width: 95%; margin: 0 auto">
    <thead>
    <tr>

        <?php
        for ($x=0;$x<19;$x++){
            if($x>0){
            echo "<th scope='col'>$x </th>";
            }else{
                echo "<th scope='col'></th>";
            }
        }


        ?>

    </tr>
    </thead>
    <tbody>

    <?php 

    for($y=1;$y<8;$y++){
        echo "<tr>\n<th scope=\"row\">$y</th>\n";
        for($x=1;$x<19;$x++){
            $chose = false;

            foreach ($elements as $element){
                if(($element["xpos"]==$x) and ($element["ypos"]==$y)){
                    $elem_sym = $element["symbol"];
                    $elem_id = $element["id"];
                    $text_color = "text-black";

                    if($element["category"]=="diatomic nonmetal"){
                        $phase = "rgba(255,0,80,0.4)";
                    }else if($element["category"]=="noble gas"){
                        $phase = "rgba(113,0,255,0.4)";
                    }else if($element["category"]=="alkali metal"){
                        $phase = "rgba(220,199,30,0.4)";
                    }else if($element["category"]=="alkaline earth metal"){
                        $phase = "rgba(220,118,30,0.4)";
                    }else if($element["category"]=="metalloid"){
                        $phase = "rgba(21,118,30,0.4)";
                    }else if($element["category"]=="polyatomic nonmetal"){
                        $phase = "rgba(21,118,30,0.4)";
                    }else if($element["category"]=="post-transition metal"){
                        $phase = "rgba(251,9,7,0.64)";
                    }else if($element["category"]=="transition metal"){
                        $phase = "rgba(251,0,198,0.64)";
                    }else if($element["category"]=="lanthanide"){
                        $phase = "rgba(104,154,40,0.64)";
                    }else if($element["category"]=="actinide"){
                        $phase = "rgba(69,224,198,0.64)";
                    }else if(strpos($element["category"], 'unknown') !== false){
                        $phase = "rgba(22,31,0,1)";
                        $text_color = "text-white";
                    }


                    echo "<td style=\"background-color:$phase;\"><a href=\"?elem_id=$elem_id\" class=\"$text_color\">$elem_sym</a></td>\n";
                    $chose = true;
                    break;
                }else{
                    //echo "<td></td>\n";
                }
            }

            if (!$chose) {
                echo "<td></td>\n";
            }


        }

        echo "</tr>\n";
    }



    ?>

    </tbody>
</table>



<?php
if(isset($_GET['elem_id'])){
    $elem_id = $_GET['elem_id'];
    settype($elem_id, "int");
    render_element($elem_id);
}

function render_element($elem_id){
    global $username, $servername, $password, $dbname;
    //echo($elem_id);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM elements WHERE id = $elem_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $elem_row = $row;
        break; 
        }
    }
    $conn->close();

    include_once("components/table_head.html");

    foreach ($elem_row as $index => $value) {
        if($value!="0"){


        if($index=="cpkhex"){

            echo "<tr>"."<td>CPK Color</td>" . "<td style=\"background-color:#$value\"></td>"."</tr>";
        }else if($index=="source"){
            echo "<tr>"."<td>$form_key</td>" . "<td><a href=\"$value\">$value</a></td>"."</tr>";

        }

        else{
        $form_key = ucfirst($index);
        $form_key = str_replace("_"," ",$form_key);
        echo "<tr>"."<td>$form_key</td>" . "<td>$value</td>"."</tr>";
    }
    }
    }





}

?>
</table>
</body>
</html>
<footer class="bg-light text-center text-lg-start mt-2">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        &copy; <?php echo date("Y"); ?> Adam Huml:
        <a class="text-dark" href="https://xehos.cz/">https://xehos.cz/</a>
    </div>
</footer>