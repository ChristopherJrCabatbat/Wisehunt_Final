// Select all edit buttons with the class "editButton"
const editButtons = document.querySelectorAll(".editButton");
const editOverlays = document.querySelectorAll(".editOverlay");
const editModals = document.querySelectorAll(".editModal");
const closeEditButtons = document.querySelectorAll(".closeEditModal");

// Set the initial display style to "none" for all edit modals and overlays
editModals.forEach(modal => modal.style.display = "none");
editOverlays.forEach(overlay => overlay.style.display = "none");

// Function to open the Edit Brand modal with the specified brand ID
function openEditModal(dataId) {
    // Find the corresponding modal based on brand ID and show it
    const modal = document.getElementById("editModal" + dataId);
    modal.style.display = "block";
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
