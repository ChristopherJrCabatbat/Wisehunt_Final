// JavaScript code
const reportModal = document.getElementById("reportModal");
const generateReportBtn = document.getElementById("generateReportBtn");
const closeBtns = document.querySelectorAll(".close");
const goBackBtn = document.querySelector(".back"); // Selecting the "Go back" button

generateReportBtn.addEventListener("click", function() {
    reportModal.style.display = "block";
});

for (let i = 0; i < closeBtns.length; i++) {
    closeBtns[i].addEventListener("click", function() {
        reportModal.style.display = "none";
    });
}

// Adding event listener for the "Go back" button
goBackBtn.addEventListener("click", function() {
    window.close(); // Attempt to close the current tab
});
