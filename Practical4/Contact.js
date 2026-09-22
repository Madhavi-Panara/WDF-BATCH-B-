/* ==========================================================
   Contact Page
   ========================================================== */

var contactForm = document.getElementById("contactForm");

contactForm.onsubmit = function () {
  var name = document.getElementById("contactName").value;
  var email = document.getElementById("contactEmail").value;
  var message = document.getElementById("contactMessage").value;

  if (name == "" || email == "" || message == "") {
    alert("Please fill in all fields!");
    return false;
  }

  alert("Thank you " + name + ", your message has been sent!");
  return false;
};