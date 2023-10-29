<?php

include "../src/inManage/Database.php";

// Server name must be localhost 
$servername = "localhost";

// In my case, user name will be root 
$username = "root";

// Password is empty 
$password = "";

// Creating a connection 

$db = new Database(
    $servername,
    $username,
    $password,
    'InManage_EX'
);


// Define the SELECT query
$query = "SELECT users.id AS uid , posts.id AS pid, users.active AS u_active ,name, email, title , body , creation_date FROM users, posts WHERE users.id = posts.user_id AND users.active = 1 ";

// Execute the query
$result = $db->query($query);
$userPostMap = [];

if ($result) {
    // Check if there are rows returned
    if ($result->num_rows > 0) {
        // Fetch and display the data
        while ($row = $result->fetch_assoc()) {

            // Add the post data to the user's list of posts
            $userPostMap[$row['uid']][] = [
                'user_id' => $row['uid'],
                'post_id' => $row['pid'],
                'name' => $row['name'],
                'email' => $row['email'],
                'title' => $row['title'],
                'body' => $row['body'],
                'creation_date' => $row['creation_date']
            ];
        }

        foreach ($userPostMap as $user_array) {
?>
            <img src='images/image.jpg' width="100" height="100">

            <?php
            echo "User ID: " . $user_array[0]['user_id'] . "<br>";
            foreach ($user_array as $row) {
            ?>
                <div>
                    <?php

                    echo "Post ID: " . $row['post_id'] . "<br>";
                    echo "Name: " . $row['name'] . "<br>";
                    echo "Email: " . $row['email'] . "<br>";
                    echo "Title: " . $row['title'] . "<br>";
                    echo "Body: " . $row['body'] . "<br>";
                    ?>
                </div>


<?php
            }

            echo "----------------------------------<br>";
        }
    } else {
        echo "No records found.";
    }

    // Close the result set
    $result->close();
} else {
    echo "Error executing the query: " . $database->getError();
}

$db->close();
