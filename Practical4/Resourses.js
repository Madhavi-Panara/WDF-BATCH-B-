/* ==========================================================
   Resources Page
   ========================================================== */

var links = document.getElementsByClassName("resource-link");

for (var i = 0; i < links.length; i++) {
  links[i].onclick = function () {
    alert("Opening resource in a new tab...");
  };
}