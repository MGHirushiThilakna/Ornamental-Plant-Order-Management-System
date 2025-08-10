let dropdownVisible = false;

document.getElementById("profile-pic").addEventListener("click", function () {
  document.getElementById("profile-dropdown").classList.toggle("show");
  dropdownVisible = !dropdownVisible;
});

document.addEventListener("click", function (event) {
  if (
    event.target !== document.getElementById("profile-pic") &&
    event.target !== document.getElementById("profile-dropdown") &&
    dropdownVisible
  ) {
    document.getElementById("profile-dropdown").classList.remove("show");
    dropdownVisible = false;
  }
});
