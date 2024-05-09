<?php

$conn = new mysqli("localhost", "root", "", "capstone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$select_sql = "SELECT uploads FROM account WHERE account_id = ?";
$stmt = $conn->prepare($select_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_photo);
$stmt->fetch();
$stmt->close();

// Determine the profile photo path
if ($profile_photo) {
    $profile_photo_path = $profile_photo;
} else {
    $profile_photo_path = "../ProfilePage/User icon.png"; // Default photo path
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" type="text/css" href="../Header/Header.css">
    <script src='../Notifications/notifications.js'></script>

</head>

<body>
    <div class="web-container">
        <div class="web-header">
            <div class="web-header-left">
                <span class="web-icon" id="menu-icon">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </span>

                <div id="optionSlider" class="option-slider">

                    <img src="<?php echo $profile_photo_path; ?>" class="photo">

                    <?php echo $_SESSION['user_name'] ?>


                    <hr size="1">
                    <nav id="menu">
                        <ul>
                            <li class="active"> <!-- Add the active class to keep it visible -->
                                <a href="#">Account</a>
                                <ul class="sub-menu">
                                    <li><a href="../ProfilePage/StudentProfile.php">My Profile</a></li>
                                    <li><a href="../LoginPage/Logout.php">Logout</a></li>
                                </ul>

                            </li>
                            <hr size="1">
                            <li class="active"> <!-- Add the active class to keep it visible -->
                                <a href="#">Appointment</a>
                                <ul class="sub-menu">
                                    <li><a href="../BookAppointmentPage/BookAppointmentPage.php">Book Appointments</a></li>
                                    <li><a href="../ViewAppointmentPage/StudentAppointmentView.php">View Appointments</a></li>
                                    <li><a href="../RatingPage/RatePage.php">Rate Appointments</a></li>
                                </ul>
                            </li>
                        </ul>

                    </nav>
                </div>
                <div class="search-wrapper">
                    <div class="search-input-container">
                        <input oninput="searchProfile()" type="search" name="search-profile" class="search-profile search-input" id="search-profile" placeholder="Search for Profiles" list="profile-list">
                        <button onclick="redirectToProfile()" type="button" class="search-button">
                            <img src="../Header/Search-icon.png" alt="Search Icon" class="search-icon">
                        </button>
                    </div>
                </div>
                <select id="profile-list" class="profile-list">

                </select>



                <div class="web-name-container">
                    <p class="web-name">STUDENT-FACULTY</p>
                    <p class="web-services">SERVICES</p>
                </div>
            </div>


            <div class="web-header-right">
                <div class="notification-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="bell-icon" id="notificationBell" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onClick="toggleNotifications()">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <div id="unreadCount" class="unread-count" style="display: none;"></div> <!-- Set display: none; -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <button class="mark-all-read-btn" onclick="markAllAsRead()">Mark All as Read</button>

                        <div id="notificationsContainer">
                            <!-- Notifications will be loaded here -->
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        const optionSlider = document.getElementById('optionSlider');
        const menuIcon = document.getElementById('menu-icon');

        menuIcon.addEventListener('click', function() {
            optionSlider.classList.toggle('show');
            if (optionSlider.classList.contains('show')) {
                optionSlider.style.height = `${window.innerHeight}px`; // Set slider height to window height
            } else {
                optionSlider.style.height = ''; // Reset height when slider is hidden
            }
        });
        const menuItems = document.querySelectorAll('#menu li');

        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const submenu = this.querySelector('ul');
                if (submenu) {
                    submenu.classList.toggle('active');
                    this.classList.toggle('active'); // Toggle active class on the parent li element
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-profile');
            const profileList = document.getElementById('profile-list');
            const searchButton = document.querySelector('.search-button');


            searchProfile();

            profileList.addEventListener('change', function() {
                redirectToProfile();
            });

            searchButton.addEventListener('click', function() {
                redirectToProfilePage();
            });
        });


        function searchProfile() {
            var query = document.getElementById('search-profile').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'SearchProfile.php?query=' + query, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var selectElement = document.getElementById('profile-list');
                    selectElement.innerHTML = xhr.responseText;
                    // Always show the profile list when the user starts typing
                    selectElement.style.display = query.trim() !== '' ? 'block' : 'none'; // Show/hide profile list based on input


                }
            };

            xhr.send();
        }

        function redirectToProfile() {
            var selectedProfile = document.getElementById('profile-list').value;
            if (selectedProfile !== '') {
                var selectedOption = document.getElementById('profile-list').options[document.getElementById('profile-list').selectedIndex];
                var profileName = selectedOption.text; // Get the text of the selected option
                document.getElementById('search-profile').value = profileName; // Set the search input value to the selected profile name
                // Hide the profile list after selecting
                document.getElementById('profile-list').style.display = 'none';
            } else {
                alert('Please select a profile.');
            }
        }


        function redirectToProfilePage() {
            var selectedProfile = document.getElementById('profile-list').value;
            if (selectedProfile !== '') {
                window.location.href = "../ProfilePage/ViewProfile.php?profile_id=" + selectedProfile;
                // Clear the search input after redirection
                document.getElementById('search-profile').value = '';
            } else {
                alert('Please select a profile.');
            }
        }
    </script>
</body>

</html>