<form action="bets_by_group.php">
    <h2>Select a group to search for: </h2><br>
    <select name="group_name" id="groups">
        <?php
            $sql = "SELECT group_name FROM groups";
            $results = $mysqli->query($sql);
            if($results->num_rows > 0){
                echo "<option value='All'>All</option>";
                while($row = $results->fetch_assoc()){
                    echo "<option value='".$row['group_name']."'>".$row['group_name']."</option>";
                }
            }
        ?>
    </select>
    <input type="submit" value="Search">
</form>
