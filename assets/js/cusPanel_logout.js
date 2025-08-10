console.log("Logout script loaded");

document.addEventListener("DOMContentLoaded", function () {
  console.log("Document ready");

  var logoutLink = document.getElementById("logout");
  if (logoutLink) {
    logoutLink.addEventListener("click", function (e) {
      console.log("Logout clicked");
      e.preventDefault();
      const href = "../cus-logoutProcess.php";

      if (typeof Swal !== "undefined") {
        Swal.fire({
          title: "Do you wish to Logout?",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3d8361",
          cancelButtonColor: "#f46e50",
          confirmButtonText: "Yes",
        }).then((result) => {
          if (result.isConfirmed) {
            console.log("Logout confirmed");
            window.location.href = href;
          }
        });
      } else {
        console.log("SweetAlert not found, using default confirm");
        if (confirm("Do you wish to0000 Logout?")) {
          window.location.href = href;
        }
      }
    });
  } else {
    console.log("Logout link not found");
  }
});
