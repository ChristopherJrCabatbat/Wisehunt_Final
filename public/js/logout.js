const logoutBtn = document.getElementById("logoutBtn");
const checkbox = document.getElementById("logout");
const dropdownMenu = document.getElementById("dropdownMenu");

logoutBtn.addEventListener("click", function (event) {
    // Prevent the label's default behavior, which triggers the checkbox
    event.preventDefault();

    // Toggle the checkbox state
    checkbox.checked = !checkbox.checked;

    // Toggle the dropdown-menu visibility
    dropdownMenu.style.display = checkbox.checked ? 'block' : 'none';
});

// Close the dropdown-menu when clicking anywhere outside of it
document.addEventListener("click", function (event) {
    if (!logoutBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
        checkbox.checked = false;
    }
});

// Prevent the dropdown-menu from closing when clicking on it
dropdownMenu.addEventListener("click", function (event) {
    event.stopPropagation();
});
