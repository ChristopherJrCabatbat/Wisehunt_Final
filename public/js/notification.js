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

// Sample implementation of isSalesForecastNotification
function isSalesForecastNotification(product) {
    // You can customize this function based on your criteria for sales forecast notifications
    // For example, if a product's quantity is low and it's a high-value product, consider it a sales forecast
    return product.quantity <= 20 && product.unit_price >= 100;
}


// Function to store dismissed notifications in local storage
function storeDismissedNotification(message) {
    const dismissedNotifications = JSON.parse(localStorage.getItem('dismissedNotifications')) || [];
    dismissedNotifications.push(message);
    localStorage.setItem('dismissedNotifications', JSON.stringify(dismissedNotifications));
}

// Function to toggle the visibility of the red dot
function toggleRedDotVisibility() {
    const redDot = document.getElementById('notificationDot');
    const notificationList = document.getElementById('notificationList');

    // Check if there are any notifications
    const hasNotifications = notificationList.querySelector('.notification-item') !== null;

    if (hasNotifications) {
        redDot.style.display = 'block'; // Show the red dot
    } else {
        redDot.style.display = 'none'; // Hide the red dot
    }
}

// Function to add low quantity notifications
function addLowQuantityNotifications() {
    const notificationList = document.getElementById('notificationList');

    // Clear the existing notifications
    notificationList.innerHTML = '';

    let hasLowQuantityNotification = false;
    let addedForecastMessages = []; // Keep track of added forecast messages

    lowQuantityNotifications.forEach(function (product, index) {
        // If it's not a sales forecast notification, add it to the regular list
        const lowQuantityItem = document.createElement('li');
        lowQuantityItem.classList.add('notification-item', 'low-quantity'); // Added 'low-quantity' class
        lowQuantityItem.innerHTML = `
            ${product.message}
            <span class="dot"></span>
        `;
        lowQuantityItem.addEventListener('click', function () {
            navigateToProductView(product.productId);
        });

        // Append the low quantity item to the notification list
        notificationList.appendChild(lowQuantityItem);

        // Check if forecastMessage is present and not empty
        if (product.forecastMessage && product.forecastMessage.trim() !== '') {
            const forecastMessages = product.forecastMessage.split('<br>');

            // Create a separate li for each forecast message
            forecastMessages.forEach(function (forecastMessage, forecastIndex) {
                // Check if the forecast message has not been added before
                if (!addedForecastMessages.includes(forecastMessage)) {
                    const forecastMessageItem = document.createElement('li');
                    forecastMessageItem.classList.add('forecast-message');
                    forecastMessageItem.innerHTML = forecastMessage;

                    // Append each forecast message to the notification list
                    notificationList.appendChild(forecastMessageItem);
                    addedForecastMessages.push(forecastMessage);

                    // Add margin-top to the first low quantity notification
                    if (index === 0 && forecastIndex === 0) {
                        lowQuantityItem.style.marginTop = '25px'; // Adjust the margin-top value as needed
                    }

                    console.log('Added forecast message:', forecastMessage);
                }
            });
        }

        hasLowQuantityNotification = true;
    });

    if (!hasLowQuantityNotification) {
        // No low quantity notifications, so display a message
        const noNotificationsItem = document.createElement('li');
        noNotificationsItem.innerText = 'No low quantity notifications.';
        notificationList.appendChild(noNotificationsItem);
    }

    // Toggle the red dot visibility based on whether there are low quantity notifications
    toggleRedDotVisibility();
}







// // Function to add low quantity notifications
// function addLowQuantityNotifications() {
//     const notificationList = document.getElementById('notificationList');

//     // Clear the existing notifications
//     notificationList.innerHTML = '';

//     let hasLowQuantityNotification = false;
//     let addedForecastMessages = []; // Keep track of added forecast messages

//     lowQuantityNotifications.forEach(function (product) {
//         // If it's not a sales forecast notification, add it to the regular list
//         const lowQuantityItem = document.createElement('li');
//         lowQuantityItem.classList.add('notification-item', 'low-quantity'); // Added 'low-quantity' class
//         lowQuantityItem.innerHTML = `
//             ${product.message}
//             <span class="dot"></span>
//         `;
//         lowQuantityItem.addEventListener('click', function () {
//             navigateToProductView(product.productId);
//         });

//         // Append the low quantity item to the notification list
//         notificationList.appendChild(lowQuantityItem);

//         // Add margin-top to the first forecast message
//         if (hasLowQuantityNotification.length === 1) {
//             forecastMessageItem.style.marginTop = '40px'; // Adjust the margin-top value as needed
//         }

//         // Check if forecastMessage is present and not empty
//         if (product.forecastMessage && product.forecastMessage.trim() !== '') {
//             const forecastMessages = product.forecastMessage.split('<br>');

//             // Create a separate li for each forecast message
//             forecastMessages.forEach(function (forecastMessage) {
//                 // Check if the forecast message has not been added before
//                 if (!addedForecastMessages.includes(forecastMessage)) {
//                     const forecastMessageItem = document.createElement('li');
//                     forecastMessageItem.classList.add('forecast-message');
//                     forecastMessageItem.innerHTML = forecastMessage;

//                     // Append each forecast message to the notification list
//                     notificationList.appendChild(forecastMessageItem);
//                     addedForecastMessages.push(forecastMessage);

//                     console.log('Added forecast message:', forecastMessage);

                    
//                 }
//             });
//         }

//         hasLowQuantityNotification = true;
//     });

//     if (!hasLowQuantityNotification) {
//         // No low quantity notifications, so display a message
//         const noNotificationsItem = document.createElement('li');
//         noNotificationsItem.innerText = 'No low quantity notifications.';
//         notificationList.appendChild(noNotificationsItem);
//     }

//     // Toggle the red dot visibility based on whether there are low quantity notifications
//     toggleRedDotVisibility();
// }










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
