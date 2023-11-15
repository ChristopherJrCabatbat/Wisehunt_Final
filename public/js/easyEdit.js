const editBtn = document.getElementById("editBtn");
const myEditModal = document.getElementById("myEditModal");
const closeBtn = document.getElementsByClassName("close");
// const closeBtn = document.getElementsByClassName("close")[0];

function openEditModal(dataId) {
    // Find the corresponding modal based on brand ID and show it
    const modal = document.getElementById("myEditModal" + dataId);
    modal.style.display = "block";
}

editBtn.addEventListener("click", function () {
    myEditModal.style.display = "block";
});

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

// closeBtn.addEventListener("click", function () {
//     myEditModal.style.display = "none";
// });

editBtn.addEventListener("click", function () {
    myEditModal.style.display = "block";

     // Autofocus
     setTimeout(() => {
        const autofocus = document.getElementById('autofocus');
        if (autofocus) {
            autofocus.focus();
        }
    }, 0);
});
