<!--
Alex Miller
Project Deliverable #6
profile.php

Script which displays the profile page of users.
Shows the table of their uploaded tickets
Shows a form for other users to add comments
    Displays those comments
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
        <title>Table View</title>
        <link rel="stylesheet" type="text/css" href="main.css">
        <script type="text/javascript" src="profile_validation.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
        <style>
        /*align content and set visibility of message div*/
            .content {
                padding-left: 15px;
            }

            .messageAfterSubmit {
                visibility: hidden;
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

<!--
Connect to MySQL database
Query all rows from tickets table
Get the number of columns and rows
-->
<div class="content">
        <?php
            // Connect to db
            $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "csulli1", "csulli1", "csulli1");
            if (mysqli_connect_errno()) {
                printf("Connect failed <br />", mysqli_connect_error());
                exit();
            }

            //select table
            $select_all_tickets_query = "select * from tickets";
            $results = mysqli_query($db, $select_all_tickets_query);

            //get num of rows and columns
            $num_cols = mysqli_num_fields($results);
            $num_rows = mysqli_num_rows($results);
        ?>


        <?php
            //GET user_id from address bar and then get username from user table using that user_id
            //$id = $_GET["user_id"];
            //if user_id is empty assign $id to users logged in id
            $id;
            if (empty($_GET["user_id"])) {
                $id = $_SESSION["id"];
            } else {
                $id = $_GET["user_id"];
            }

            //$select_user = "select * from users where user_id=" . $id . "";
            $select_user = "select * from users where user_id=" . $id . "";
            $user_result = mysqli_query($db, $select_user);
            $user_row = mysqli_fetch_assoc($user_result);
            $username1 = $user_row['username'];


            // Used for debugging usernames and ID's

            // print "Profile ID: " . $id;
            // print "<br />";
            // print "Viewing profile: " . $username1; 
            // print "<br />";

            $logged_in_user = $_SESSION['user'];
            //print "Logged in as: " . $logged_in_user;
            //print "<br />";

            $logged_in_user_id = $_SESSION['id'];
            //print "Logged in user ID: " . $logged_in_user_id;
            //print "<br />";
        ?>

        <?php
            print "<h1>" . $username1 . "'s profile</h1>";
        ?>







<!--
Query to get the tickets for selected user.
Uses the ID from the address bar or logged in user
Gets and prints number of rows
-->
        <?php
            $select_tickets_from_user = "select * from tickets where seller_id=" . $id . ""; //seller
            $ticket_result = mysqli_query($db, $select_tickets_from_user);

            if (!$ticket_result) {
                print "No results";
            } else {
                $num_rows_tickets_user = mysqli_num_rows($ticket_result);
        ?>
<!--
Table to show tickets for selected user.
If the user has not added any tickets, do not show ticket table.
-->
        <div class="my_tickets">
            <table>
                <?php
                    print "<h2>" . $username1 . "'s Tickets</h2>"
                ?>

                <tr>
                    <th>User</th>
                    <th>Ticket ID</th>
                    <th>Original Price</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Current List Price</th>
                    <th>Current Bid User</th>
                    <th>Bid</th>
                </tr>

                <?php
                    while ($row = mysqli_fetch_assoc($ticket_result)) {
                        print "<tr>";
                        $username = userIdToUsername($row['seller_id']);
                        $is_accepted = isAccepted($row['seller_response']);

                        print "<td>" . $username . "</td>";
                        print "<td>" . $row['ticket_id'] . "</td>";
                        print "<td>" . "$" . $row['seller_price'] . "</td>";
                        print "<td>" . $row['event_name'] . "</td>";
                        print "<td>" . $row['event_date'] . "</td>";
                        print "<td>" . $row['bidder_price'] . "</td>";
                        print "<td>" . $row['bidder_username'] . "</td>";
                        print "<td>" . $is_accepted . "</td>";
                        print "</tr>";
                    }
                ?>
                            
            </table>
            <br />
        </div> <!-- End my_tickets -->        
        <?php
            } //end else
        ?>




<!-- PHP helper functions -->
        <?php
            // Reads the is_accepted field from database and returns a string
            function isAccepted($flag) {
                if ($flag == 1) {
                    return "Bidding open";
                } else {
                    return "Can not bid";
                }
            }

            // Connects to database to return the username by using the user_id
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

            //connects to database and selects the username and returns the user_id
            function usernameToID($user) {
                $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "csulli1", "csulli1", "csulli1");
                if (mysqli_connect_errno()) {
                    printf("Connect failed <br />", mysqli_connect_error());
                    exit();
                }

                $select_id = "select * from users where username=" . $user . "";
                $id_results = mysqli_query($db, $select_id);
                $id_row = mysqli_fetch_assoc($id_results);

                return $id_row['user_id'];                
            }
        ?>

<!-- Comment Form -->

<?php
   $fa = '<form action="comments.php?user_id=' . $id . '" method="POST">';
   print $fa;
?>
        <!-- <form> -->
            <textarea type="text" name="feedback_input" id="comment_text_area">Enter your feedback for this user.
            </textarea>
            <br />

            <label>How would you rate this user? (Out of 5)</label>

            <select name="feedback_value" id="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <br />

            <!-- <button type="button" value="Submit" id="submitBtn">Submit</button> -->
            <input type="submit" value="Submit" id="submitBtn">
        </form>

        <!-- JavaScript validation -->
        <script type="text/javascript" src="profile_validation.js"></script>

        <!-- Hidden DIV which will be visible after comment is submitted through profile_validation.js -->
        <div class="messageAfterSubmit" id="messageAfterSubmit">
            <p></p>
        </div>


                <!-- Comments Section -->

        <h2>Comments</h2>

                <?php
            // Connect to db
            $db = mysqli_connect("studentdb-maria.gl.umbc.edu", "csulli1", "csulli1", "csulli1");
            if (mysqli_connect_errno()) {
                printf("Connect failed <br />", mysqli_connect_error());
                exit();
            }


            //select
            $query = "SELECT * from reviews where reviewed_user_id=" . $id;
            $results = mysqli_query($db, $query);

            //num rows and cols
            $num_rows = mysqli_num_rows($results);
            $num_cols = mysqli_num_fields($results);

            if ($num_rows > 0) { //new
                //initialize rating array
                $rating_arr = [];

                //print comments
                while ($row = mysqli_fetch_assoc($results)) {
                    print "User: " . userIdToUsername($row['reviewer_user_id']) . "<br />";
                    print "Text: " . $row['reviewer_comments'] . "<br />";
                    print "Rating: " . $row['reviewer_rating'] . "<br />";
                    print "<br />";
                    //$rating_arr[$i] = $row['c_rating'];
                    array_push($rating_arr, $row['reviewer_rating']);
                }
                
                $overall_rating = 0;

                foreach($rating_arr as $val) {
                    $overall_rating += $val;
                }

                $rating_average = $overall_rating / $num_rows;

                //only 2 decimal points
                $rating_average = round($rating_average, 2);

                print "Overall rating: " . $rating_average . "/5";
            } else {
                print "No ratings yet.";
            }

            //print '<a href="profile.php?user_id=' . $id . '">Go back to ' . userIdToUsername($id) . '\'s page</a>';


        ?>
</div>
    </body>
</html>