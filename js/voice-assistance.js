document.addEventListener("DOMContentLoaded", function () {
  const voiceAssistanceBtn = document.getElementById("voice-assistance");
  const micStatus = document.getElementById("mic-status");

  // Audio track management
  let currentTrack = null;

  // Function to stop currently playing track
  function stopTrack() {
    if (currentTrack) {
      currentTrack.pause();
      currentTrack.currentTime = 0;
      currentTrack = null;
    }
  }

  // Function to play a track
  function playTrack(trackPath) {
    // Stop any existing track first
    stopTrack();

    // Create and play new track
    currentTrack = new Audio(trackPath);
    currentTrack.play().catch((error) => {
      console.error("Error playing track:", error);
    });
  }

  // Reflect initial state from PHP session
  if (typeof initialVoiceAssistState !== "undefined") {
    voiceAssistanceBtn.classList.toggle("active", initialVoiceAssistState);
    voiceAssistanceBtn.classList.toggle("inactive", !initialVoiceAssistState);
  }

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

            // Optional: Play a startup sound
            playTrack("./assets/voice/intro.mp3");
          } else {
            // Turn OFF voice assistance
            micStatus.textContent = "OFF";
            voiceAssistanceBtn.classList.remove("active");
            voiceAssistanceBtn.classList.add("inactive");

            // Stop any playing track
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

  // Optional: Global track stop mechanism
  window.addEventListener("voiceAssistanceStop", () => {
    stopTrack();
  });
});
