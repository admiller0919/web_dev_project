<!--
Alex Miller
Project Deliverable #6
comments.php 

Inserts comments into SQL database
Shows message to go back to users profile
-->

<?php
    session_start();
    
    if(!isset($_SESSION["user"]))
    {
        header('Location: login.php');
    }
    # Add this code to top of every page
?>

<html>
<!-- Code written by: Alex Miller -->
	<head>
        <link rel="stylesheet" type="text/css" href="main.css"> 
        <style>
        /*align content so it isn't all the way against the left edge*/
            .content {
                padding-top: 55px;
                padding-left: 15px;
            }
        </style>  
    </head>
	<body>
    <ul style="padding: 0;"><div class="nav">
        <li><div class="nav-a"><a href="home.php" class="test">Home</a></div></li>
        <li><div class="nav-a"><a href="addTicket.php" class="test">Add Tickets</a></div></li>
        <li><div class="nav-a"><a href="bidding.php" class="test">Place Bids</a></div></li>
        <li><div class="nav-a"><a href="editTicket.php" class="test">Edit Tickets</a></div></li>
        <li style="float: right;"><div class="nav-a"><a href="profile.php" class="test">My Profile</a></div></li>
        <li style="float: right;"><div class="nav-a"><a href="logout.php" class="test">Log Out</a></div></li>   
        <li style="float: right;"><div class="nav-a"><a href="login.php" class="test">Login</a></div></li>
    </ul>

    <div class="content">

		<?php
            //GET user_id from address bar in order to determine whose profile is being viewed
            $id = $_GET["user_id"];
        ?>


		<?php
			// Connect to db
            $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "csulli1", "csulli1", "csulli1");
            if (mysqli_connect_errno()) {
                printf("Connect failed <br />", mysqli_connect_error());
                exit();
            }

            if (isset($_POST['feedback_value']) && (!empty($_POST['feedback_value'])) && isset($_POST['feedback_input']) && (!empty($_POST['feedback_input']))){
                //get contents from profile.php form
                $comment = $_POST['feedback_input'];
                $rating = $_POST['feedback_value'];

                $comment = htmlspecialchars($_POST['feedback_input']);
                $rating = htmlspecialchars($_POST['feedback_value']);

                $comment = mysqli_real_escape_string($db,$comment);
                $rating = mysqli_real_escape_string($db,$rating);

                //$logged_in_user = usernameToID($_SESSION["user"]);

                //use session variable to grab user id
                $logged_in_user_id = $_SESSION["id"];

                $session_user = $_SESSION["user"];

                //insert the current profile id, logged in user id, comment, and rating to database
                // $insert = "INSERT INTO reviews(reviewer_user_id, reviewer_username, reviewed_user_id, reviewer_rating, reviewer_comments) VALUES ('$logged_in_user_id', '$_SESSION["user"]', '$id', '$rating', '$comment')";

                $insert = "INSERT INTO reviews(reviewer_user_id, reviewer_username, reviewed_user_id, reviewer_rating, reviewer_comments) VALUES ('$logged_in_user_id', '$session_user', '$id', '$rating', '$comment')";

                $insert_results = mysqli_query($db, $insert);
           }

		?>

		<?php

            function userIdToUsername($id) {
                $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "csulli1", "csulli1", "csulli1");
                if (mysqli_connect_errno()) {
                    printf("Connect failed <br />", mysqli_connect_error());
                    exit();
                }

                $select_user = "SELECT * from users where user_id=" . $id . "";
                $user_result = mysqli_query($db, $select_user);
                $num_user_rows = mysqli_num_rows($user_result);

                $user_row = mysqli_fetch_assoc($user_result);

                return $user_row['username'];
            }


            // print "Thank you for leaving a comment " . $_SESSION["user"] . "!<br />";
			print '<a href="profile.php?user_id=' . $id . '">Go back to ' . userIdToUsername($id) . '\'s page</a>';
		?>
        </div>
	</body>
</html>