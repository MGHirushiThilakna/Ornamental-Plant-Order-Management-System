$(document).ready(function () {
  $("#signout").on("click", function (e) {
    e.preventDefault();
    const href = "../staffLogoutProcess.php";
    Swal.fire({
      title: "Do you wish to Logout?",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3d8361",
      cancelButtonColor: "#f46e50",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        document.location.href = href;
      }
    });
  });
});
