<!DOCTYPE html>

<?php
include "../Chatbot/Chatbot.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="LoginPage.css">
  <title>Login Page</title>
</head>

<body>
  <div class="container">
    <div class="sidebar">
      <div class="header">
        <img src="AAU logo.png" alt="logo" class="logo">
        <div class="title-container">
          <h1 class="title" style="font-size: 33px;">STUDENT-FACULTY</h1>
          <h1 class="title" style="font-size: 26px; margin-top: -10px;">SERVICES</h1>
        </div>
      </div>
      <span class="main-title">Get Quick</span><br>
      <span class="main-title" style="font-weight: bold;">Support Services</span><br><br>
      <span class="main-question" id="mainQuestion">What can you do<span class="dot" id="dot1">.</span><span class="dot" id="dot2">.</span><span class="dot" id="dot3">.</span></span>

      <div class="question-answer-container">
        <div class="question-answer">
          <span class="question">Asking a simple question?</span><br>
          <span class="answer">Try our Chatbot!</span>
        </div>
        <div class="question-answer">
          <span class="question">Need more help?</span><br>
          <span class="answer">Send an email.</span><br>
          <span class="answer"> Set up an appointment.</span>
        </div>
        <div class="question-answer">
          <span class="question">Say Thanks!</span><br>
          <span class="answer">Share your positive comment!</span><br>
          <span class="answer">Rate your instructor!</span>
        </div>
      </div>

    </div>
    <div class="contact-us">
            <a class="contact" href="mailto:Admin@SFS.com">Contact us</a>
          </div>
           <!-- Include the help modal HTML content -->
<button class="help-button" onclick="toggleHelp()">
    <img src="../Header/question mark.jpg" class="help-icon">
</button>

<!-- Add the help modal container with the modal content -->
<div class="modal-container" id="helpModalContainer">
    <div class="modal-content">
    <?php include "../LoginPage/help.html"; ?>
    <link rel="stylesheet" href="../LoginPage/help.css">
</div>
</div>

    <div class="login-container">
      <h1 class="header-login">LOGIN</h1>
      <p class="login-description">Login using your AAU account</p>
      <form class="form" action="config.php" method="post">
        <label class="label">Email</label><br>
        <input class="input" type="email" id="email" name="email" placeholder="example@aau.ac.ae" required><br><br>
        <label class="label">Password</label><br>
        <input class="input" type="password" id="password" name="password" autocomplete="on" placeholder="enter password" required><br><br>
        <div class="error-message">
          <?php
          if (isset($_SESSION['error'])) {
            echo "<span>Invalid email or password. Please try again.</span>";
            unset($_SESSION['error']);
          }
          ?>
        </div>
        <button class="button" id="submit" type="submit">Login</button>
      </form>
      <h4 class="register-text">Don't have an account? <a href="./RegisterPage.php">Register here.</a></h4>
    </div>

  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var questionAnswers = document.querySelectorAll(".question-answer");
      var currentQAIndex = 0;

      function displayQuestionAnswers() {
        // Hide all question-answer pairs
        questionAnswers.forEach(function(qa) {
          qa.style.display = "none";
        });

        // Show current question-answer pair with animation
        questionAnswers[currentQAIndex].style.display = "block";

        // Increment index for next question-answer pair
        currentQAIndex++;

        // Reset index if reached the end
        if (currentQAIndex === questionAnswers.length) {
          currentQAIndex = 0;
        }

        // Repeat the process after a delay
        setTimeout(displayQuestionAnswers, 3000); // Change slide every 3 seconds
      }

      displayQuestionAnswers();

      // Call the animateDots function
      animateDots();
    });

    function animateDots() {
      var dots = document.querySelectorAll(".dot");

      dots.forEach(function(dot) {
        dot.style.display = "none"; // Hide the dot initially
      });

      // Animate dots
      setInterval(function() {
        dots.forEach(function(dot) {
          dot.style.display = dot.style.display === "none" ? "inline" : "none";
        });
      }, 500); // Adjust the interval between animations as needed
    }


  </script>
</body>

</html>