<?php
require_once 'config.php';
/*session_start();

try{
    $name= $_SESSION['name'];
    $lastName= $_SESSION['lastName'];
    
    $sql = "INSERT INTO persons (first_name, last_name) VALUES ($name, $lastName)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam( $name, $_REQUEST['first_name']);
    $stmt->bindParam( $lastName, $_REQUEST['last_name']);
    
    $stmt->execute();
    echo "Records inserted successfully.";
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
 */

try{
    $sql = "SELECT * FROM persons";   
    $result = $pdo->query($sql);
    if($result->rowCount() > 0){
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
        unset($result);
    } else{
        echo "No records matching your query were found.";
    }
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

unset($pdo);

?>
    <a href="index.php">Wróć do formularza</a>
