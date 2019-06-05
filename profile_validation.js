// Alex Miller
// profile_validation.js
// Project Deliverable #6 - Ajax and JavaScript

// Script is run when page is loaded
// Validates form from profile.php and then
// runs insert script if validated

"use strict"

window.onload = pageLoad();

// Function to run once page is loaded
// Will wait for submit button to be clicked and then run check_comment function
function pageLoad() {
	var submit = document.getElementById("submitBtn");
	submit.onclick = check_comment;
}


// Function to check and validate the input from comment form
// Passes check if it is only word characters
// If passes
	// Send an Ajax request to insert the data into the SQL database
	// Make div visible to show thank you message
	// Reload the page to show updated comment thread
// If check fails
	// Make div visible to show message to enter a valid comment
function check_comment(){
	var messageDiv = document.getElementById("messageAfterSubmit");
	var comment = document.getElementById("comment_text_area").value;
	var rating = document.getElementById("rating").value;
	var pattern = /\w+/; //match a word character
	var flag = pattern.test(comment);

	if (flag) {
		//run ajax request for php script to insert data into database

		// new Ajax.Request("insert.php", {
  // 			method: "POST",
  // 			parameters: {feedback_input: comment, feedback_value: rating},
		// });

		messageDiv.innerHTML = "Thank you for leaving your comment.";
		messageDiv.style.visibility = "visible";

		// reloads the page so the new comment as appended to end of comment thread
		location.reload();
	} else {
		//change visibility of message div
		//dont insert data into database
		messageDiv.innerHTML = "Please enter a valid comment and try again.";
		messageDiv.style.visibility = "visible";
	}
}