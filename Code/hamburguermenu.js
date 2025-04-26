/** Javascript for webpage
code done by Trishelle Leal **/

function hamburger() {
  var link = document.getElementById("links");
  if (link.style.display == "block") {
    link.style.display = "none";
  } else {
    link.style.display = "block";
  }
}