let notificationPanelVisible = false; // Initially, the notification panel is hidden

// Function to toggle the visibility of the notification panel
function toggleNotificationPanel() {
    const notificationPanel = document.getElementById('notificationPanel');
    notificationPanelVisible = !notificationPanelVisible; // Toggle the visibility state

    if (notificationPanelVisible) {
        notificationPanel.style.display = 'block'; // Show the notification panel

        // // Set a timeout to automatically close the notification panel after 5 seconds
        // setTimeout(() => {
        //     closeNotification();
        // }, 5000);
    } else {
        notificationPanel.style.display = 'none'; // Hide the notification panel
    }
    // Do not change the red dot visibility here
}

// Function to close the notification panel
function closeNotification() {
    const notificationPanel = document.getElementById('notificationPanel');
    notificationPanelVisible = false; // Set the visibility state explicitly to false
    notificationPanel.style.display = 'none';
}

// Function to add a notification dynamically
function addNotification(product) {
    const notificationList = document.getElementById('notificationList');
    const listItem = document.createElement('li');
    listItem.classList.add('notification-item');
    listItem.innerHTML = `
${product.message}
<span class="dot"></span>
`;

    listItem.addEventListener('click', function () {
        navigateToProductView(product.productId);
    });

    notificationList.appendChild(listItem);

    const redDot = document.getElementById('notificationDot');
    redDot.style.display = 'block'; // Show the red dot when a new notification is added
}

// Function to store dismissed notifications in local storage
function storeDismissedNotification(message) {
    const dismissedNotifications = JSON.parse(localStorage.getItem('dismissedNotifications')) || [];
    dismissedNotifications.push(message);
    localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
}

// Function to add low quantity notifications
function addLowQuantityNotifications() {
    const notificationList = document.getElementById('notificationList');

    // Clear the existing notifications
    notificationList.innerHTML = '';

    let hasSalesForecastNotification = false;

    if (lowQuantityNotifications.length > 0) {
        lowQuantityNotifications.forEach(function (product) {
                addNotification(product);
                // Check if the notification is a sales forecasting notification
            if (isSalesForecastNotification(product)) {
                // If it is, add it to the top of the list
                addNotification(product);
                hasSalesForecastNotification = true;
            } else {
                // If not, add it to the regular list
                addNotification(product);
            }
        });
    }

    if (!hasSalesForecastNotification) {
        // No sales forecasting notification, so display a message
        const noSalesForecastItem = document.createElement('li');
        noSalesForecastItem.innerText = 'No notifications.';
        notificationList.appendChild(noSalesForecastItem);
    }

    // Toggle the red dot visibility based on whether there are notifications
    toggleRedDotVisibility();
}


// Navigate to product edit view
function navigateToProductView(productId) {
    // Redirect to the product edit view with the corresponding ID
    window.location.href = `{{ route('admin.product') }}/${productId}/edit`;
    // Note: The line below seems redundant; you may choose one or the other based on your requirements
    // window.location.href = `Products/${productId}/edit`;
}

// Call the function to load dismissed notifications when the page loads
document.addEventListener("DOMContentLoaded", function () {
    addLowQuantityNotifications();
    // Check if there are notifications and show the red dot accordingly
    const redDot = document.getElementById('notificationDot');
    if (document.querySelector('.notification-list li')) {
        redDot.style.display = 'block'; // Show the red dot
    } else {
        redDot.style.display = 'none'; // Hide the red dot
    }
});

// Close the notificationPanel when clicking anywhere outside of it
document.addEventListener("click", function (event) {
    const notificationPanel = document.getElementById('notificationPanel');
    const notifButton = document.querySelector('.notif');

    if (
        event.target !== notifButton &&
        event.target !== notificationPanel &&
        !notificationPanel.contains(event.target)
    ) {
        notificationPanel.style.display = 'none';
        notificationPanelVisible = false;
    }
});
