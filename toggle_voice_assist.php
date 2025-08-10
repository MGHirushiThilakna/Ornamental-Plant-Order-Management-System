<?php
session_start();

// Check if an action is specified
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'start':
            // Create voice assistance session
            $_SESSION['voice_assist'] = true;
            echo json_encode([
                'status' => 'success', 
                'message' => 'Voice assistance session started'
            ]);
            break;

        case 'stop':
            // Destroy voice assistance session
            if (isset($_SESSION['voice_assist'])) {
                unset($_SESSION['voice_assist']);
            }
            echo json_encode([
                'status' => 'success', 
                'message' => 'Voice assistance session stopped'
            ]);
            break;

        default:
            echo json_encode([
                'status' => 'error', 
                'message' => 'Invalid action'
            ]);
            break;
    }
    exit();
}
?>