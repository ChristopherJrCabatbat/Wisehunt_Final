 // JavaScript code
 const reportModal = document.getElementById("reportModal");
 const generateReportBtn = document.getElementById("generateReportBtn");
const closeBtns = document.querySelectorAll(".close");


 generateReportBtn.addEventListener("click", function() {
     reportModal.style.display = "block";
 });

 for (let i = 0; i < closeBtns.length; i++) {
     closeBtns[i].addEventListener("click", function() {
         reportModal.style.display = "none";
     });
 }