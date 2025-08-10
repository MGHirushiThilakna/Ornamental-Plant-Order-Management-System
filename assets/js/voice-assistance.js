document.addEventListener("DOMContentLoaded", function () {
  const voiceAssistanceBtn = document.getElementById("voice-assistance");
  const micStatus = document.getElementById("mic-status");

  let currentTrack = null;

  //stop currently playing track
  function stopTrack() {
    if (currentTrack) {
      currentTrack.pause();
      currentTrack.currentTime = 0;
      currentTrack = null;
    }
  }

  // play a track and handle redirect
  function playTrack(trackPath, shouldRedirect = false) {
    stopTrack();
    currentTrack = new Audio(trackPath); // Create and play new track

    if (shouldRedirect) { // do it after the track ends
      currentTrack.addEventListener("ended", function () {
        window.location.href = "./customerLogin.php";
      });
    }

    currentTrack.play().catch((error) => {
      console.error("Error playing track:", error);
      if (shouldRedirect) {
        window.location.href = "./customerLogin.php";
      }
    });
  }
  // Make playTrack available globally
  window.playTrack = playTrack;

  //  handle login check
  window.handleLoginCheck = function () {
    const isVoiceAssistOn = micStatus.textContent === "ON";
    const isLoggedIn =
      document.body.hasAttribute("data-login") &&
      document.body.getAttribute("data-login") === "logged";

    if (!isLoggedIn) {
      if (isVoiceAssistOn) {
        playTrack("./assets/voice/Login_warning.mp3", true);
      } else {
        // if voice assist is off
        Swal.fire({
          title: "Login Required",
          text: "Please login to continue",
          icon: "warning",
          confirmButtonText: "OK",
          customClass: {
            container: "login-warning-alert",
          },
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "./customerLogin.php";
          }
        });
      }
      return false;
    }
    return true;
  };

  voiceAssistanceBtn.addEventListener("click", function () {
    const currentStatus = micStatus.textContent;

    fetch(
      "toggle_voice_assist.php?action=" +
        (currentStatus === "OFF" ? "start" : "stop")
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          if (currentStatus === "OFF") {
            // Turn ON voice assistance
            micStatus.textContent = "ON";
            voiceAssistanceBtn.classList.remove("inactive");
            voiceAssistanceBtn.classList.add("active");

            playTrack("./assets/voice/intro.mp3");
          } else {
            // Turn OFF voice assistance
            micStatus.textContent = "OFF";
            voiceAssistanceBtn.classList.remove("active");
            voiceAssistanceBtn.classList.add("inactive");

            stopTrack();
          }
        } else {
          console.error("Error toggling voice assistance:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

   //initialize 
   if (typeof initialVoiceAssistState !== "undefined") {
    voiceAssistanceBtn.classList.toggle("active", initialVoiceAssistState);
    voiceAssistanceBtn.classList.toggle("inactive", !initialVoiceAssistState);
  }

  window.addEventListener("voiceAssistanceStop", () => {
    stopTrack();
  });
});

// ==================================== Display the pointing message to the voice assistance button

 /*document.addEventListener("DOMContentLoaded", () => {
  const messageBox = document.getElementById("button-message");

  // Show the message after login
  setTimeout(() => {
    messageBox.classList.add("show");
  }, 1000); // 1-second delay to simulate login

  // Hide the message after a few seconds
  setTimeout(() => {
    messageBox.classList.remove("show");
    localStorage.setItem("messageDisplayed", "true");
  }, 4000);
 });
 */

// Message box display code remains unchanged
/*document.addEventListener("DOMContentLoaded", () => {
  const messageBox = document.getElementById("button-message");

  if (!localStorage.getItem("messageDisplayed")) {
    setTimeout(() => {
      messageBox.classList.add("show");
    }, 1000);

    setTimeout(() => {
      messageBox.classList.remove("show");
      localStorage.setItem("messageDisplayed", "true");
    }, 12000);
  }
});*/
