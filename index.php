<?php

require_once 'config.php';
function filterName($field){
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+/")))){
        return $field;
    }else{
        return FALSE;
    }
} 
function filterString($field){
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if(!empty($field)){
        return $field;
    }else{
        return FALSE;
    }
}
$name = $lastName = "";
$nameErr = $lastNameErr = $error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty($_POST["name"])){
        $nameErr = 'Podaj imie.';
    }else{
        $name = filterName($_POST["name"]);
        if($name == FALSE ){
            $nameErr = 'Podaj prawidłowe imie.';
        }
    }
    if(empty($_POST["lastName"])){
        $lastNameErr = 'Podaj nazwisko.';
    }else{
        $lastName = filterName($_POST["lastName"]);
        if($name == FALSE){
            $lastNameErr = 'Podaj prawidłowe nazwisko.';
        }
    }
    if((strlen($_POST["name"])<3) && (strlen($_POST["name"])>0) ) {
        $nameErr = 'Podaj prawidłowe imie.';
    } 
    if((strlen($_POST["lastName"])<3) && (strlen($_POST["lastName"])>0) ) {
        $lastNameErr = 'Podaj prawidłowe nazwisko.';
    } 
    
    
    if(empty($nameErr) && empty($lastNameErr)){
        try {
            $sql = "SELECT first_name, last_name FROM persons WHERE first_name =:name AND last_name =:lastName ";
            if($stmt = $pdo->prepare($sql)){
                $stmt->bindParam(':name', $param_name, PDO::PARAM_STR);
                $stmt->bindParam(':lastName', $param_lastName, PDO::PARAM_STR);
                $param_name = trim($_POST["name"]);
                $param_lastName = trim($_POST["lastName"]);
                if($stmt->execute()){
                    if($stmt->rowCount() > 0){
                      $error= 'Taki sprzedawca znajduje się juz w bazie';  
                    }
                }
            }
        } catch(PDOException $e){
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            } 

        if(empty($error)) {
            try {    
                $sql = "INSERT INTO persons (first_name, last_name) VALUES (:name, :lastName)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $_REQUEST['name']);
                $stmt->bindParam(':lastName', $_REQUEST['lastName']);
                $stmt->execute();
            } catch(PDOException $e){   
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            } 
        }
    }
}


try{
    $sql = "SELECT * FROM persons";   
    $result = $pdo->query($sql);
    if($result->rowCount() > 0){
        echo '<div id="conteiner">';
        echo "<table>";
            echo "<tr>";
                echo "<th>id</th>";
                echo "<th>first_name</th>";
                echo "<th>last_name</th>";
            echo "</tr>";
        while($row = $result->fetch()){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo '</div>';
        unset($result);
    } else{
        echo "No records matching your query were found.";
    }
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

unset($pdo);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Contact Form</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <style type="text/css">
            .form-group {
                width: 400px;
                margin: 40px auto;
            }

            #conteiner {
                width: 400px;
                margin: 0 auto;
            }

            .error {
                color: red;
            }

            .success {
                color: green;
            }

        </style>
    </head>

    <body>


        <form action="index.php" method="post">
            <div class="form-group">
                <p>Wrzuć sprzedawcę do bazy danych.</p>
                <p>
                    <label for="inputName">Imie:<sup>*</sup></label>
                    <input type="text" name="name" class="form-control" id="inputName" value="<?php echo $name; ?>">
                    <span class="error"><?php echo $nameErr; ?></span>
                </p>
                <p>
                    <label for="inputLastName">Nazwisko:<sup>*</sup></label>
                    <input type="text" name="lastName" class="form-control" id="inputLastName" value="<?php echo $lastName; ?>">
                    <span class="error"><?php echo $lastNameErr; ?></span>
                </p>
                <input type="submit" value="Send" class="btn btn-primary">
                <p class="error">
                    <?php echo $error; ?>
                </p>
            </div>
        </form>
    </body>

    </html>
