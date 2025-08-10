<?php 
$currentSubPage="addFAQ";
include "adminHeader_1.php"; 
include "..\classes\DBConnect.php";
include "..\classes\CategoryController.php";
$db = new DatabaseConnection;
$mainCatObj = new CategoryController;
?>

// index.php
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>FAQ Management - Admin Panel</title>
<style>
        /* Existing styles */
        .faq-container {
            max-width: 1200px;
            margin-top: 3px;
            font-family: Arial, sans-serif;
        }
        .faq-item {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .btn {
            padding: 8px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background: #4CAF50;
            color: white;
        }
        .btn-delete {
            background: #f44336;
            color: white;
        }
        .add-faq-form {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    width: 70%; /* Set width to 70% */
    margin: 0 auto 30px auto; /* Center horizontally and add bottom margin */
    box-sizing: border-box; /* Include padding in width calculation */
}

.add-faq-form h2 {
    font-family:Nunito, Arial;
    text-align: center;
    color: #556b2f;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.5em;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.form-group input, 
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus, 
.form-group textarea:focus {
    border-color: #4CAF50;
    outline: none;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

/* Center the submit button */
.add-faq-form .btn {
    display: block;
    margin: 20px auto 0;
    padding: 10px 30px;
    font-size: 16px;
}

/* Responsive design */
@media (max-width: 768px) {
    .add-faq-form {
        width: 85%; /* Slightly wider on tablets */
    }
}

@media (max-width: 480px) {
    .add-faq-form {
        width: 95%; /* Almost full width on mobile */
        padding: 15px;
    }
    
    .form-group input, 
    .form-group textarea {
        padding: 8px;
    }
}
        .status-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
        }
        .error {
            background: #f2dede;
            color: #a94442;
        }

        /* New styles for admin view section */
        .admin-nav {
            background:#3f5c3f ;
            padding: 10px;
            margin: 0 auto 20px auto; 
            width:30%;
            margin-top:3px;
            margin-bottom: 30px;
            border-radius: 5px;
            display: flex; /* Use flexbox for button alignment */
            justify-content: center; /* Center buttons horizontally */
            gap: 10px; 
           
        }
        .nav-btn {
            background: none;
            border: none;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            margin-right:15px;
           
            border-radius: 10px;
            flex: 0 1 auto; /* Allow buttons to shrink if needed */
            white-space: nowrap; /* Prevent button text from wrapping */
            transition: background-color 0.3s ease;
        }
        .nav-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}
        .nav-btn.active {
            background: #3d8361;
        }
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
        .search-box {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .category-filter {
            margin-bottom: 20px;
        }
        .category-filter select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .view-faq-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .view-faq-question {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 10px;
            color: #333;
        }
        .view-faq-answer {
            color: #666;
            line-height: 1.6;
        }
        .view-faq-category {
            color: #4CAF50;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .statistics-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .stat-label {
            color: #666;
            font-size: 0.9em;
        }
        * Add responsive behavior */
@media (max-width: 768px) {
    .admin-nav {
        width: 80%; /* Wider on mobile */
    }
}

@media (max-width: 480px) {
    .admin-nav {
        width: 95%; /* Almost full width on very small screens */
    }
}

.category-container {
    display: flex;
    align-items: center;
}

.category-container select {
    margin-right: 10px; }

.add-new-btn {
    padding: 5px 10px; 
    border-radius:100px;
}
 </style>
    <link rel="stylesheet" href="..\assets\css\admin-category-style.css">
</head>
<body>


<div class="container my-container" style="margin-top:20px; width:100%; margin-right:0px;">
<div class="page-title">FAQ Handling</div>
    <div class="card mycard" >
    
        <div class="card-body mycard-body">
  
    <div class="faq-container">
        
    <div class="admin-nav">
            <button class="nav-btn active" data-section="view">View FAQs</button>
            <button class="nav-btn" data-section="manage">Manage FAQs</button>
        </div>

        <!-- View Section -->
        <div id="viewSection" class="section active">
            <div class="statistics-panel">
                <div class="stat-card">
                    <div class="stat-number" id="totalFaqs">0</div>
                    <div class="stat-label">Total FAQs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalCategories">0</div>
                    <div class="stat-label">Categories</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="recentFaqs">0</div>
                    <div class="stat-label">Added This Month</div>
                </div>
            </div>

            <input type="text" class="search-box" id="searchFAQs" placeholder="Search FAQs...">
            
            <div class="category-filter">
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                </select>
            </div>

            <div id="faqViewList">
                <!-- FAQs will be loaded here -->
            </div>
        </div>

        <!-- Manage Section -->
        <div id="manageSection" class="section">
            <!-- Add FAQ Form -->
            <div class="add-faq-form">
                <h2>Add New FAQ</h2>
                <form id="addFaqForm">
                    <div class="form-group">
                        <label for="question">Question:</label>
                        <input type="text" id="question" name="question" required>
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer:</label>
                        <textarea id="answer" name="answer" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <div class="category-container">
                            <select id="category" name="category" class="form-control" required>
                                <option value="Select">Select</option>
                                <option value="General">General Information</option>
                                <option value="Account">Account and Registration</option>      
                                <option value="Product">Product Information</option>
                                <option value="Ordering">Ordering Process</option>
                                <option value="Delivery">Delivery Process</option>
                                <option value="Promotions"> Promotions and Discounts</option>
                                <option value="Issues">Technical Issues</option>
                                <option value="Support">Customer Support</option>                                </option>
                        </select>
                            <button type="button" class="btn btn-edit add-new-btn" onclick="addNewCategory()">New</button>
                        </div>
                </div>
            <div>
                <button type="submit" class="btn btn-edit">Add FAQ</button>
            </div>
        </form>
            </div>

            <div id="faqList">
                <!-- FAQs for management will be loaded here -->
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Navigation
            $('.nav-btn').click(function() {
                $('.nav-btn').removeClass('active');
                $(this).addClass('active');
                
                const section = $(this).data('section');
                $('.section').removeClass('active');
                $(`#${section}Section`).addClass('active');
                
                if(section === 'view') {
                    loadViewFAQs();
                    updateStatistics();
                } else {
                    loadManageFAQs();
                }
            });

            // Initialize view
            loadViewFAQs();
            updateStatistics();
            loadCategories();

            // Search functionality
            $('#searchFAQs').on('input', function() {
                loadViewFAQs();
            });

            // Category filter
            $('#categoryFilter').change(function() {
                loadViewFAQs();
            });

            function loadCategories() {
                $.ajax({
                    url: 'faq_handler.php',
                    type: 'GET',
                    data: { action: 'categories' },
                    success: function(response) {
                        const categories = JSON.parse(response);
                        let options = '<option value="">All Categories</option>';
                        categories.forEach(category => {
                            options += `<option value="${category}">${category}</option>`;
                        });
                        $('#categoryFilter').html(options);
                    }
                });
            }

            function updateStatistics() {
                $.ajax({
                    url: 'faq_handler.php',
                    type: 'GET',
                    data: { action: 'statistics' },
                    success: function(response) {
                        const stats = JSON.parse(response);
                        $('#totalFaqs').text(stats.total);
                        $('#totalCategories').text(stats.categories);
                        $('#recentFaqs').text(stats.recent);
                    }
                });
            }

            function loadViewFAQs() {
                const searchTerm = $('#searchFAQs').val();
                const category = $('#categoryFilter').val();

                $.ajax({
                    url: 'faq_handler.php',
                    type: 'GET',
                    data: { 
                        action: 'list',
                        search: searchTerm,
                        category: category
                    },
                    success: function(response) {
                        const faqs = JSON.parse(response);
                        let html = '';
                        
                        faqs.forEach(faq => {
                            html += `
                                <div class="view-faq-item">
                                    <div class="view-faq-question">${faq.question}</div>
                                    <div class="view-faq-answer">${faq.answer}</div>
                                    <div class="view-faq-category">Category: ${faq.category}</div>
                                </div>
                            `;
                        });
                        
                        $('#faqViewList').html(html || '<p>No FAQs found</p>');
                    }
                });
            }

            // Existing management functionality
            function loadManageFAQs() {
                $.ajax({
                    url: 'faq_handler.php',
                    type: 'GET',
                    data: { action: 'list' },
                    success: function(response) {
                        const faqs = JSON.parse(response);
                        let html = '';
                        faqs.forEach(faq => {
                            html += `
                                <div class="faq-item">
                                    <h3 id="question_${faq.id}">${faq.question}</h3>
                                    <p id="answer_${faq.id}">${faq.answer}</p>
                                    <p><strong>Category:</strong> <span id="category_${faq.id}">${faq.category}</span></p>
                                    <div class="action-buttons">
                                        <button class="btn btn-edit" data-id="${faq.id}">Edit</button>
                                        <button class="btn btn-delete" data-id="${faq.id}">Delete</button>
                                    </div>
                                </div>
                            `;
                        });
                        $('#faqList').html(html);
                    }
                });
            }

            // Add FAQ form submission
            $('#addFaqForm').on('submit', function(e) {
                e.preventDefault();
                const formData = {
                    question: $('#question').val(),
                    answer: $('#answer').val(),
                    category: $('#category').val()
                };

                $.ajax({
                    url: 'faq_handler.php',
                    type: 'POST',
                    data: {
                        action: 'add',
                        ...formData
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if(result.success) {
                            showMessage('FAQ added successfully!', 'success');
                            $('#addFaqForm')[0].reset();
                            loadManageFAQs();
                            updateStatistics();
                        } else {
                            showMessage('Error adding FAQ.', 'error');
                        }
                    }
                });
            });

            // Delete FAQ
            $(document).on('click', '.btn-delete', function() {
                const faqId = $(this).data('id');
                if(confirm('Are you sure you want to delete this FAQ?')) {
                    $.ajax({
                        url: 'faq_handler.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id: faqId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if(result.success) {
                                showMessage('FAQ deleted successfully!', 'success');
                                loadManageFAQs();
                                updateStatistics();
                            } else {
                                showMessage('Error deleting FAQ.', 'error');
                            }
                        }
                    });
                }
            });

            // Edit FAQ
            $(document).on('click', '.btn-edit', function() {
                const faqId = $(this).data('id');
                const questionEl = $(`#question_${faqId}`);
                const answerEl = $(`#answer_${faqId}`);
                const categoryEl = $(`#category_${faqId}`);

                const newQuestion = prompt('Edit question:', questionEl.text());
                const newAnswer = prompt('Edit answer:', answerEl.text());
                const newCategory = prompt('Edit category:', categoryEl.text());

                if(newQuestion && newAnswer && newCategory) {
                    $.ajax({
                        url: 'faq_handler.php',
                        type: 'POST',
                        data: {
                            action: 'edit',
                            id: faqId,
                            question: newQuestion,
                            answer: newAnswer,
                            category: newCategory
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if(result.success) {
                                showMessage('FAQ updated successfully!', 'success');
                                loadManageFAQs();
                                updateStatistics();
                            } else {
                                showMessage('Error updating FAQ.', 'error');
                            }
                        }
                    });
                }
            });

            function showMessage(message, type) {
                const messageDiv = $('<div>')
                    .addClass(`status-message ${type}`)
                    .text(message);
                $('.faq-container').prepend(messageDiv);
                setTimeout(() => messageDiv.fadeOut('slow', function() {
                    $(this).remove();
                }), 3000);
            }
        });
    </script>
    <script>
        function addNewCategory() {
            // Prompt user for a new category name
            var newCategory = prompt("Enter new category name:");
            
            // Check if the user entered a value
            if (newCategory && newCategory.trim() !== "") {
                // Get the select element
                var categorySelect = document.getElementById("category");
                
                // Create a new option element
                var newOption = document.createElement("option");
                newOption.value = newCategory;
                newOption.textContent = newCategory;
                
                // Add the new option to the select element
                categorySelect.appendChild(newOption);
            } else {
                alert("Please enter a valid category name.");
            }
        }
</script>
</body>
</html>

