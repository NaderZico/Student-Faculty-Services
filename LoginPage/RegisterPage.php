<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="RegisterPage.css">
    <title>Create an Account</title>
    <script>
        function showOptions(accountType) {
            var collegeOptions = document.getElementById('college-options');
            var facultyOptions = document.getElementById('faculty-options');
            var departmentInput = document.getElementById('department');

            if (accountType === 'student') {
                collegeOptions.style.display = 'block';
                facultyOptions.style.display = 'none';
                departmentInput.removeAttribute('required'); // Remove required for student
            } else if (accountType === 'faculty') {
                collegeOptions.style.display = 'none';
                facultyOptions.style.display = 'block';
                departmentInput.setAttribute('required', true); // Add required for faculty
            }
        }

        function populateMajors() {
            var college = document.getElementById('college').value;
            var major = document.getElementById('major');
            major.innerHTML = ''; // Clear current options

            // Define majors for each college
            var majors = {
                'Education, Humanities and Social Sciences': ['Special Education', 'Applied Psychology', 'Arabic Language and Literature', 'Islamic Studies', 'English Language and Translation'],
                'Pharmacy': ['Nutrition and Dietetics', 'Pharmacy'],
                'Engineering': ['Cybersecurity', 'Software Engineering', 'Computer Science', 'Networks and Communication Engineering', 'Civil Engineering', 'Computer Engineering'],
                'Law': ['Law'],
                'Business': ['Marketing', 'Accounting', 'Management', 'Finance and Banking', 'Human Resource Management', 'Management Information Systems']
            };

            // Populate major options based on selected college
            if (college in majors) {
                for (var i = 0; i < majors[college].length; i++) {
                    var option = document.createElement('option');
                    option.value = majors[college][i];
                    option.text = majors[college][i];
                    major.add(option);
                }
            }
        }
    </script>

</head>

<body>
    <div class="register-container">
        <h1 class="header-login">Register</h1>
        <p class="register-description">Create an Account</p>
        <form class="form" action="handle_registration.php" method="post">
            <div class="account-type">
                <p>Who are you?</p>
                <div class="radio-options">
                    <input class="radio-input" type="radio" name="account-type" value="student" onclick="showOptions('student')" id="student" required>
                    <label class="radio-label" for="student">Student</label>
                    <input class="radio-input" type="radio" name="account-type" value="faculty" onclick="showOptions('faculty')" id="faculty" required>
                    <label class="radio-label" for="faculty">Faculty</label>
                </div>
            </div>

            <div id="college-options" style="display: none;">
                <label for="college" class="label">College:</label>
                <select name="college" id="college" onchange="populateMajors()" required>
                    <option value="" disabled selected>Select College</option>
                    <option value="Education, Humanities and Social Sciences">Education, Humanities and Social Sciences</option>
                    <option value="Pharmacy">Pharmacy</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Law">Law</option>
                    <option value="Business">Business</option>
                </select><br><br>

                <label for="major" class="label">Major:</label>
                <select name="major" id="major" required>
                    <option value="" disabled selected>Select major</option>
                    <!-- Options for majors will be dynamically populated based on the selected college -->
                </select><br><br>
            </div>

            <div id="faculty-options" style="display: none;">
                <label for="department" class="label">Department:</label>
                <input type="text" id="department" name="department" placeholder="ex. Associate Professor, College of Engineering"><br><br>
            </div>


            <div id="name-inputs">
                <label for="first-name" class="label">First Name:</label>
                <input class="input" type="text" id="first-name" name="first_name" placeholder="Enter your first name" required><br><br>

                <label for="last-name" class="label">Last Name:</label>
                <input class="input" type="text" id="last-name" name="last_name" placeholder="Enter your last name" required><br><br>
            </div>

            <label class="label">Email</label><br>
            <input class="input" type="email" id="email" name="email" placeholder="example@gmail.com" required><br><br>

            <label class="label">Password</label><br>
            <input class="input" type="password" id="password" name="password" autocomplete="on" placeholder="enter password" required><br><br>


            <div class="message">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<span class='error-message'>" . $_SESSION['error'] . "</span>";
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            <button class="button" id="submit" type="submit">Register</button>
        </form>
    </div>
</body>

</html>