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
// function addLowQuantityNotifications() {
//     const notificationList = document.getElementById('notificationList');

//     // Clear the existing notifications
//     notificationList.innerHTML = '';

//     let hasLowQuantityNotification = false;
//     let addedForecastMessages = []; // Keep track of added forecast messages

//     lowQuantityNotifications.forEach(function (product, index) {
//         // If it's not a sales forecast notification, add it to the regular list
//         const notificationItem = document.createElement('li');
//         notificationItem.classList.add('notification-item', 'low-quantity'); // Added 'low-quantity' class
//         notificationItem.innerHTML = `
//             ${product.message}
//             <span class="dot"></span>
//         `;
//         notificationItem.addEventListener('click', function () {
//             navigateToProductView(product.productId);
//         });

//         // Append the notification item to the notification list
//         notificationList.appendChild(notificationItem);

//         // Check if forecastMessage is present and not empty
//         if (product.forecastMessage && product.forecastMessage.trim() !== '') {
//             const forecastMessages = product.forecastMessage.split('<br>');

//             // Create a separate li for each forecast message
//             forecastMessages.forEach(function (forecastMessage, forecastIndex) {
//                 // Check if the forecast message has not been added before
//                 if (!addedForecastMessages.includes(forecastMessage)) {
//                     const forecastMessageItem = document.createElement('li');
//                     forecastMessageItem.classList.add('notification-item', 'forecast-message');
//                     forecastMessageItem.innerHTML = `
//                         ${forecastMessage}
//                         <span class="dot"></span>
//                     `;

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



// Function to add low quantity notifications

function addLowQuantityNotifications() {
    const notificationList = document.getElementById('notificationList');

    // Clear the existing notifications
    notificationList.innerHTML = '';

    let hasLowQuantityNotification = false;

    lowQuantityNotifications.forEach(function (product) {
        const notificationItem = document.createElement('li');
        notificationItem.classList.add('notification-item', 'low-quantity');
        notificationItem.innerHTML = `
            ${product.message}
            <span class="dot"></span>
        `;
        notificationItem.addEventListener('click', function () {
            navigateToProductView(product.productId);
        });

        notificationList.appendChild(notificationItem);

        if (product.forecastMessage && product.forecastMessage.trim() !== '') {
            const forecastMessageItem = document.createElement('li');
            forecastMessageItem.classList.add('notification-item', 'forecast-message');
            forecastMessageItem.innerHTML = `
                ${product.forecastMessage}
                <span class="dot"></span>
            `;
            notificationList.appendChild(forecastMessageItem);
        }

        hasLowQuantityNotification = true;
    });

    if (!hasLowQuantityNotification) {
        const noNotificationsItem = document.createElement('li');
        noNotificationsItem.innerText = 'No low quantity notifications.';
        notificationList.appendChild(noNotificationsItem);
    }

    toggleRedDotVisibility();
}






function addBestSellerNotifications() {
    console.log('Adding best-seller notifications:', bestSellerNotifications);

    const notificationList = document.getElementById('notificationList');

    bestSellerNotifications.forEach(function (product) {
        console.log('Adding notification for product:', product);

        const notificationItem = document.createElement('li');
        notificationItem.classList.add('notification-item', 'best-seller'); // Added 'best-seller' class
        notificationItem.innerHTML = `
            ${product.message}
            <span class="dot"></span>
        `;
        notificationItem.addEventListener('click', function () {
            navigateToProductView(product.productId);
        });

        // Append the notification item to the notification list
        notificationList.appendChild(notificationItem);
    });

    // Toggle the red dot visibility based on whether there are best-seller notifications
    toggleRedDotVisibility();
}

// Call the function to add best-seller notifications when the page loads
document.addEventListener("DOMContentLoaded", function () {
    addLowQuantityNotifications();
    addBestSellerNotifications();
    // Check if there are notifications and show the red dot accordingly
    const redDot = document.getElementById('notificationDot');
    if (document.querySelector('.notification-list li')) {
        redDot.style.display = 'block'; // Show the red dot
    } else {
        redDot.style.display = 'none'; // Hide the red dot
    }
});

// Navigate to product edit view
function navigateToProductView(productId) {
    // Redirect to the product edit view with the corresponding ID
    window.location.href = `#`;
    // window.location.href = `{{ route('admin.product') }}/${productId}/edit`;
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

// // Close the notificationPanel when clicking anywhere outside of it
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
        
        // Toggle the red dot visibility after closing the notification panel
        toggleRedDotVisibility();
    }
});


// Function to add notifications
function addNotifications(notifications) {
    const notificationList = document.getElementById('notificationList');

    // Clear the existing notifications
    notificationList.innerHTML = '';

    let addedForecastMessages = []; // Keep track of added forecast messages

    notifications.forEach(function (product, index) {
        // If it's not a sales forecast notification, add it to the regular list
        const notificationItem = document.createElement('li');
        notificationItem.classList.add('notification-item'); // Common class for all notifications
        notificationItem.innerHTML = `
            ${product.message}
            <span class="dot"></span>
        `;
        notificationItem.addEventListener('click', function () {
            navigateToProductView(product.productId);
        });

        // Append the notification item to the notification list
        notificationList.appendChild(notificationItem);

        // Check if forecastMessage is present and not empty
        if (product.forecastMessage && product.forecastMessage.trim() !== '') {
            const forecastMessages = product.forecastMessage.split('<br>');

            // Create a separate li for each forecast message
            forecastMessages.forEach(function (forecastMessage) {
                // Check if the forecast message has not been added before
                if (!addedForecastMessages.includes(forecastMessage)) {
                    const forecastMessageItem = document.createElement('li');
                    forecastMessageItem.classList.add('notification-item', 'forecast-message');
                    forecastMessageItem.innerHTML = `
                        ${forecastMessage}
                        <span class="dot"></span>
                    `;

                    // Append each forecast message to the notification list
                    notificationList.appendChild(forecastMessageItem);
                    addedForecastMessages.push(forecastMessage);

                    // Add margin-top to the first low quantity notification
                    if (index === 0) {
                        notificationItem.style.marginTop = '35px'; // Adjust the margin-top value as needed
                    }

                    console.log('Added forecast message:', forecastMessage);
                }
            });
        }
    });

    if (notifications.length === 0) {
        // No notifications, so display a message
        const noNotificationsItem = document.createElement('li');
        noNotificationsItem.innerText = 'No notifications.';
        notificationList.appendChild(noNotificationsItem);
    }

    // Toggle the red dot visibility based on whether there are notifications
    toggleRedDotVisibility();
}


// // Function to add notifications
// function addNotifications(notifications) {
//     const notificationList = document.getElementById('notificationList');

//     // Clear the existing notifications
//     notificationList.innerHTML = '';

//     let hasLowQuantityNotification = false;
//     let addedForecastMessages = []; // Keep track of added forecast messages

//     notifications.forEach(function (product, index) {
//         // If it's not a sales forecast notification, add it to the regular list
//         const notificationItem = document.createElement('li');
//         notificationItem.classList.add('notification-item'); // Common class for all notifications
//         notificationItem.innerHTML = `
//             ${product.message}
//             <span class="dot"></span>
//         `;
//         notificationItem.addEventListener('click', function () {
//             navigateToProductView(product.productId);
//         });

//         // Append the notification item to the notification list
//         notificationList.appendChild(notificationItem);

//         // Check if forecastMessage is present and not empty
//         if (product.forecastMessage && product.forecastMessage.trim() !== '') {
//             const forecastMessages = product.forecastMessage.split('<br>');

//             // Create a separate li for each forecast message
//             forecastMessages.forEach(function (forecastMessage, forecastIndex) {
//                 // Check if the forecast message has not been added before
//                 if (!addedForecastMessages.includes(forecastMessage)) {
//                     const forecastMessageItem = document.createElement('li');
//                     forecastMessageItem.classList.add('notification-item', 'forecast-message');
//                     forecastMessageItem.innerHTML = `
//                         ${forecastMessage}
//                         <span class="dot"></span>
//                     `;

//                     // Append each forecast message to the notification list
//                     notificationList.appendChild(forecastMessageItem);
//                     addedForecastMessages.push(forecastMessage);

//                     // Add margin-top to the first low quantity notification
//                     if (index === 0 && forecastIndex === 0) {
//                         notificationItem.style.marginTop = '35px'; // Adjust the margin-top value as needed
//                     }

//                     console.log('Added forecast message:', forecastMessage);
//                 }
//             });
//         }

//         hasLowQuantityNotification = true;
//     });

//     if (!hasLowQuantityNotification) {
//         // No low quantity notifications, so display a message
//         const noNotificationsItem = document.createElement('li');
//         noNotificationsItem.innerText = 'No notifications.';
//         notificationList.appendChild(noNotificationsItem);
//     }

//     // Toggle the red dot visibility based on whether there are notifications
//     toggleRedDotVisibility();
// }


// Call the function to add notifications when the page loads
document.addEventListener("DOMContentLoaded", function () {
    const combinedNotifications = [...lowQuantityNotifications, ...bestSellerNotifications];
    addNotifications(combinedNotifications);

    // Toggle the red dot visibility based on whether there are notifications
    toggleRedDotVisibility();
});