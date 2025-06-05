/** Javascript for webpage
code done by Trishelle Leal **/

function hamburger() {
  var sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("show");
  sidebar.addEventListener("click", function() {
    sidebar.classList.remove("show");
});
}
