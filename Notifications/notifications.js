function toggleNotifications() {
    var dropdown = document.getElementById("notificationDropdown");
    dropdown.classList.toggle("show");

    // Load notifications if the dropdown is shown
    if (dropdown.classList.contains("show")) {
        loadNotifications();
    }
}

function loadNotifications() {
    fetch('../Notifications/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            var notificationsContainer = document.getElementById('notificationsContainer');
            // Clear the notificationsContainer before appending new notifications
            notificationsContainer.innerHTML = '';
            var unreadCount = 0; // Counter for unread notifications

            // Check if data is an array
            var notifications = Array.isArray(data) ? data : [];

            if (notifications.length === 0) {
                // If there are no notifications, display a message
                var noNotificationsMessage = document.createElement('p');
                noNotificationsMessage.className = 'no-notifications';
                noNotificationsMessage.textContent = 'No notifications. Check back later.';
                notificationsContainer.appendChild(noNotificationsMessage);

            } else {
                // Inside your loadNotifications function...
                notifications.forEach(notification => {
                    var div = document.createElement('div');
                    div.className = 'notification ' + notification.status;
                    div.dataset.notificationId = notification.notification_id;

                    // Create a paragraph for the message
                    var messagePara = document.createElement('p');
                    messagePara.textContent = notification.message;
                    div.appendChild(messagePara);

                    // Create a span for the timestamp
                    var timestampSpan = document.createElement('span');
                    timestampSpan.className = 'timestamp';
                    timestampSpan.textContent = notification.timestamp;
                    div.appendChild(timestampSpan);

                    // Create a span for the "Mark as read" text
                    var markAsReadSpan = document.createElement('span');
                    markAsReadSpan.className = 'mark-as-read';
                    markAsReadSpan.textContent = 'Mark as read';
                    div.appendChild(markAsReadSpan);

                    notificationsContainer.appendChild(div);

                    // Increment the counter if the notification is unread
                    if (notification.status === 'unread') {
                        unreadCount++;
                    }
                });

            }
            // Display the unread count
            document.getElementById('unreadCount').textContent = unreadCount;

            // Hide the unreadCountElement if there are no unread notifications
            if (unreadCount === 0) {
                document.getElementById('unreadCount').style.display = 'none';
            } else {
                document.getElementById('unreadCount').style.display = 'block';
            }

            // Call the function to handle notification status and font-weight
            handleNotificationStatus();
        })
        .catch(error => console.error('Error:', error));
}

loadNotifications();

// Function to handle notification status and font-weight
function handleNotificationStatus() {
    var notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        // Check if the notification is unread
        if (notification.classList.contains('unread')) {
            // Apply styles for unread notifications
            notification.style.fontWeight = 'bold';
        } else {
            // Apply styles for read notifications
            notification.style.fontWeight = 'normal';
        }

        // Add click event listener to mark notification as read
        notification.addEventListener('click', function () {
            if (notification.classList.contains('unread')) {
                // Update UI for read notification
                notification.style.fontWeight = 'normal';

                // Remove the 'unread' class to mark notification as read
                notification.classList.remove('unread');

                // You can add an AJAX call here to update the status in the database
                markNotificationAsRead(notification.dataset.notificationId);
            }
        });
    });
}

handleNotificationStatus();

function markAllAsRead() {
    var notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        if (notification.classList.contains('unread')) {
            // Update UI for read notification
            notification.style.fontWeight = 'normal';
            // Remove the 'unread' class to mark notification as read
            notification.classList.remove('unread');
            // Mark notification as read in the database
            markNotificationAsRead(notification.dataset.notificationId);
        }
    });
}


function markNotificationAsRead(notificationId) {
    // Create a FormData object with the notification ID
    var formData = new FormData();
    formData.append('notification_id', notificationId);

    // Fetch API to send a POST request to mark the notification as read
    fetch('../Notifications/update_notification_status.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                // Log the response text when the response is not ok
                return response.text().then(text => {
                    throw new Error('Failed to mark notification as read: ' + text);
                });
            }
            // Decrement the unread count
            var unreadCountElement = document.getElementById('unreadCount');
            var unreadCount = Number(unreadCountElement.textContent);
            unreadCountElement.textContent = Math.max(unreadCount - 1, 0);
            // You can update the UI here if needed
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            // Handle the error, if necessary
        });
}


// Close the notification dropdown when clicking outside of it
window.onclick = function (event) {
    if (!event.target.matches('.bell-icon') && !event.target.closest('.notification-dropdown')) {
        var dropdowns = document.getElementsByClassName("notification-dropdown");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
