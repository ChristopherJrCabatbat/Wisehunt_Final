document.addEventListener("DOMContentLoaded", function() {
    
// Select all edit buttons with the class "editButton"
const editButtons = document.querySelectorAll(".editButton");
const editOverlays = document.querySelectorAll(".editOverlay");
const editModals = document.querySelectorAll(".editModal");
const closeEditButtons = document.querySelectorAll(".closeEditModal");

// Set the initial display style to "none" for all edit modals and overlays
editModals.forEach(modal => modal.style.display = "none");
editOverlays.forEach(overlay => overlay.style.display = "none");

let openedModal;

function openEditModal(dataId) {
    // Find the corresponding modal based on brand ID and show it
    openedModal = document.getElementById("editModal" + dataId);
    openedModal.style.display = "block";
    
    // Autofocus
    setTimeout(() => {
        const autofocus = openedModal.querySelector('.autofocus');
        if (autofocus) {
            autofocus.focus();
        }
    }, 0);

    //  // Include CSRF token in the form
    //  const form = openedModal.querySelector(".edit-modal-form");
    //  console.log("Form method:", form.method); // Add this line to log the form method

    //  const csrfField = document.createElement("input");
    //  csrfField.type = "hidden";
    //  csrfField.name = "_token";
    //  csrfField.value = window.csrfToken;
    //  form.appendChild(csrfField);
    
    // Include CSRF token in the form
    const form = openedModal.querySelector(".edit-modal-form");
    console.log("Form method:", form.getAttribute("method")); // Use getAttribute to get the method

    const csrfField = document.createElement("input");
    csrfField.type = "hidden";
    csrfField.name = "_token";
    csrfField.value = window.csrfToken;
    form.appendChild(csrfField);

}

// Attach click event handler for all edit buttons and handle event delegation
document.addEventListener("click", function (event) {
    // Find the closest ancestor with the class "editButton"
    const editButton = event.target.closest(".editButton");

    // Check if the clicked element or its ancestor has the class "editButton"
    if (editButton) {
        const dataId = editButton.getAttribute("data-id");
        openEditModal(dataId);
    }
});

// Attach click event handlers to close buttons
closeEditButtons.forEach(function (closeEditButton) {
    closeEditButton.addEventListener("click", closeEditModal);
});

// Close the Edit Order modal
function closeEditModal() {
    const form = openedModal.querySelector(".edit-modal-form");

    // Loop through each form element and reset its value
    form.querySelectorAll("input:not([type='submit']), select, textarea").forEach((element) => {
        element.value = "";
    });

    // Clear validation messages
    const validationMessages = openedModal.querySelectorAll(".text-danger");
    validationMessages.forEach((message) => {
        message.innerHTML = "";
    });

    // Reset the form
    form.reset();

    editModals.forEach(function (modal) {
        modal.style.display = "none";
    });
    editOverlays.forEach(function (overlay) {
        overlay.style.display = "none";
    });
}

// Close the editModal when clicking anywhere outside of it
document.addEventListener("click", function (event) {
    editOverlays.forEach(function (overlay) {
        if (event.target === overlay) {
            closeEditModal();
        }
    });
});

});
