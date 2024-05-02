<title>Login Page</title>

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
            <a class="contact" href="mailto:farahedelbi@hotmail.com">Contact us</a>
          </div>
    <button class="help-button" onclick="openHelp()">
      <img src="../Header/question mark.jpg" class="help-icon">
      
    </button>

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
    </div>

  </div>

  <div id="helpModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeHelp()">&times;</span>
      <div class="slider-container">
        <div class="slide">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
          
          <!-- Your first page content -->
          <h2>To use the services</h2>
          <!-- Add your form elements here -->
          <p>First login with your AAU account</p>
          <img src="../LoginPage/Help/16.png" alt="Description of the image" class="help-image">



        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
        
          <h2>Important links</h2>
          <!-- Add your form elements here -->
          <p>Once you login, many features are there to help you such as</p>
          <img src="../LoginPage/Help/1.png" alt="Description of the image" class="help-image">


        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>Login as STUDENT</h2>
          <!-- Add your form elements here -->
          <p>To navigate the website, click the menu icon. Choose your destination from the slide-out menu that appears.<br> Then, simply click on the desired option to explore. </p>
          <img src="../LoginPage/Help/2.png" alt="Description of the image" class="help-image">
        </div>     
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>Book an appointment.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/3.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>View your appointment.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/4.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>Rate your appointment.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/5.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>Login as INSTRUCTOR</h2>
          <!-- Add your form elements here -->
          <p>To navigate the website, click the menu icon. Choose your destination from the slide-out menu that appears.<br> Then, simply click on the desired option to explore. </p>
          <img src="../LoginPage/Help/7.png" alt="Description of the image" class="help-image">
        </div> 
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
      
          <h2>Instructor profile </h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/8.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
      
          <h2>Set slots</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/9.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
     
          <h2>View slots</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/10.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>View appointment.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/11.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
      
          <h2>Leave <strong>positive</strong> comment.</h2>
          <!-- Add your form elements here -->
          <p>Student and Faculty members can leave comment on each other's profiles.</p>
          <img src="../LoginPage/Help/6.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
      
          <h2>Use our Chatbot.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/12.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
     
          <h2>Get courses info.</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/13.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
       
          <h2>Get faculty info</h2>
          <!-- Add your form elements here -->
          <p>  </p>
          <img src="../LoginPage/Help/14.png" alt="Description of the image" class="help-image">
        </div>
        <div class="slide">
          <img src="../Header/PreArrow.jpg" class="prev-arrow" onclick="prevSlide()">
          <img src="../Header/NextArrow.jpg" class="next-arrow" onclick="nextSlide()">
        
          <h2>Get general help</h2>
          <!-- Add your form elements here -->
          <p>How to register courses, conduct payment and Get the self-service guide</p>
          <img src="../LoginPage/Help/15.png" alt="Description of the image" class="help-image">
        </div>
      </div>
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

    // Open help modal
    function openHelp() {
      var modal = document.getElementById("helpModal");
      modal.style.display = "block";
    }

    // Close help modal
    function closeHelp() {
      var modal = document.getElementById("helpModal");
      modal.style.display = "none";
    }

    // Slide navigation
    var slideIndex = 1;
    showSlide(slideIndex);

    function nextSlide() {
      showSlide(slideIndex += 1);
    }

    function prevSlide() {
      showSlide(slideIndex -= 1);
    }

    function showSlide(n) {
      var slides = document.getElementsByClassName("slide");
      if (n > slides.length) {
        slideIndex = 1;
      }
      if (n < 1) {
        slideIndex = slides.length;
      }
      for (var i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      slides[slideIndex - 1].style.display = "block";
    }
  </script>
</body>

</html>