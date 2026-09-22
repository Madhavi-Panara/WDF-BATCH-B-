/* ==========================================================
   Login Page
   ========================================================== */

var loginBtn = document.getElementById("loginBtn");

loginBtn.onclick = function () {
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;

  if (username == "") {
    alert("Please enter your username!");
  } else if (password == "") {
    alert("Please enter your password!");
  } else {
    alert("Login successful!");
    window.location.href = "profile.html";
  }
};