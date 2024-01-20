document.addEventListener("DOMContentLoaded", function () {
    // Get references to the elements for the New modal
    const newButton = document.getElementById("newButton");
    const newModal = document.getElementById("newModal");
    const closeButtons = document.querySelectorAll(".closeModal");
    const overlay = document.querySelector(".overlay");

    // Function to toggle the New modal visibility and overlay
    function toggleNewModal(event) {
        if (newModal.style.display === "block") {
            closeModal();
        } else {
            newModal.style.display = "block";
            overlay.style.display = "block"; // Show the overlay
        }

        // Autofocus
        setTimeout(() => {
            const autofocus = document.getElementById("autofocus");
            if (autofocus) {
                autofocus.focus();
            }
        }, 0);

        // Include CSRF token in the form
        const form = document.querySelector(".modal-form");
        const csrfField = document.createElement("input");
        csrfField.type = "hidden";
        csrfField.name = "_token";
        csrfField.value = window.csrfToken;
        form.appendChild(csrfField);
    }

    // Attach click event handlers for the New button
    newButton.addEventListener("click", toggleNewModal);

    // Attach click event handlers to close buttons
    closeButtons.forEach(function (closeButton) {
        closeButton.addEventListener("click", closeModal);
    });

    // Close the newModal when clicking anywhere outside of it
    document.addEventListener("click", function (event) {
        if (event.target === overlay) {
            closeModal();
        }
    });

    // Close the New Customer modal
    function closeModal() {
        const form = document.querySelector(".modal-form");

        // Loop through each form element and reset its value
        form.querySelectorAll("input:not([type='submit']):not([type='button']), select, textarea").forEach((element) => {
            element.value = "";
        });

        // Clear validation messages
        const validationMessages = document.querySelectorAll(".text-danger");
        validationMessages.forEach((message) => {
            message.innerHTML = "";
        });

        newModal.style.display = "none";
        overlay.style.display = "none"; // Hide the overlay
    }
});
