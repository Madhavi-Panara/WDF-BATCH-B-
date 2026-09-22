/* ==========================================================
   Library Page
   ========================================================== */

var searchBtn = document.getElementById("librarySearchBtn");

searchBtn.onclick = function () {
  var keyword = document.getElementById("librarySearchInput").value;

  if (keyword == "") {
    alert("Please type something to search!");
  } else {
    alert("Searching for: " + keyword);
  }
};