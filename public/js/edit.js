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
