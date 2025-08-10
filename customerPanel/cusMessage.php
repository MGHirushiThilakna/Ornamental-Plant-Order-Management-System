
<?php

include "customerSidePanal.php";
include "..\classes\DBConnect.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if(isset($_SESSION['Customer_ID'])){
    $customerID = $_SESSION['Customer_ID'];
    $customerName = $_SESSION['Name']; // Assuming you store customer name in session
}
?>
<head>
  <style>
   .mydash{
    height:500px;
   }
   .mainDash{
    margin-top: 100px;
    margin-left: 250px;
   }
    main{
      width: 70%;
    }
    img{
      width: 50px;
      height: 50px;
    }
    h2{
      font-size: 24px;
    }
    p{
      font-size: 18px;
    }
    img:hover{
      transform: scale(1.1);
    }
    div{
      margin-bottom: 20px;
    }
    h3{
      font-size: 20px;
    }
    p{
      font-size: 16px;
    }
    h4{
      font-size: 18px;
    }
    p{
      font-size: 14px;
    }

    .message-container {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            box-shadow: 20px 20px 60px #d9d9d9,
                       -20px -20px 60px #ffffff;
        }
        .file-drop-zone {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        .file-drop-zone:hover {
            border-color: #4f46e5;
        }
        .attached-file {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
                
            }
        }
</style>
</head>
<body>
    


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Center</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       
    </style>
</head>
<body class="bg-gray-50">

              <h2 class="text-xl font-semibold">message</h2>
            </div>
          </div>
    <div class="container mx-auto px-4 py-8" style="margin-top:50px">
        <div class="message-container rounded-lg p-6 max-w-4xl mx-auto">
            <div class="mb-6" style="text-align:center; background-color:rgb(106, 176, 103); border-radius:7px; height:150px; padding-top:35px;">
                <h1 class="text-3xl font-bold text-gray-800 mb-2" style="text-align:center; font-size:35px; ">Message Center</h1>
                <p class="text-gray-600" style="color:rgb(233, 240, 241); font-size:25px;">Send us your feedback, questions, or concerns</p>
            </div>

            <form id="messageForm" class="" enctype="multipart/form-data">
                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="subject" name="subject" required
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="message" name="message" rows="6" required
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Attachments</label>
                    <div class="file-drop-zone p-4 rounded-lg text-center cursor-pointer relative">
                        <input type="file" id="fileInput" name="attachments[]" multiple 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <p class="text-gray-600">Drag and drop files here or click to select</p>
                            <p class="text-sm text-gray-500">Maximum file size: 10MB</p>
                        </div>
                    </div>
                    <div id="fileList" class="mt-3 space-y-2"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // File input handling
            $('#fileInput').on('change', function() {
                const fileList = $('#fileList');
                fileList.empty();
                
                Array.from(this.files).forEach(file => {
                    if (file.size > 10 * 1024 * 1024) { // 10MB limit
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: `${file.name} exceeds the 10MB size limit`
                        });
                        return;
                    }
                    
                    const fileItem = $(`
                        <div class="attached-file flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                <span class="text-sm text-gray-700">${file.name}</span>
                            </div>
                            <button type="button" class="remove-file text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                    
                    fileList.append(fileItem);
                });
            });

            // Remove file
            $(document).on('click', '.remove-file', function() {
                $(this).closest('.attached-file').remove();
            });

            // Form submission
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('customerID', '<?php echo $customerID; ?>');
                formData.append('customerName', '<?php echo $customerName; ?>');

                $('#loadingOverlay').removeClass('hidden');

                $.ajax({
                    url: 'send-message.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loadingOverlay').addClass('hidden');
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Message Sent!',
                                text: 'We will get back to you soon.'
                            }).then(() => {
                                $('#messageForm')[0].reset();
                                $('#fileList').empty();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to send message'
                            });
                        }
                    },
                    error: function() {
                        $('#loadingOverlay').addClass('hidden');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

          
  </body>
  </html>