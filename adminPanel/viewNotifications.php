<?php 
$currentSubPage="ViewNoti";
include "notificationHandling.php";
include "..\classes\DBConnect.php";
$db = new DatabaseConnection; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\assets\css\employee-style.css">
    <link rel="stylesheet" href="..\assets\css\admin-notification.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="container mt-6 my-container " style=" width:max-content;" >
    <div class="card form-card mb-4 my serchcard" style=" width: auto;">
        <div class="card-body"  >
            <div class="row">

                <div class="col-lg-4 off-my-col">
                        <form class="d-flex search-box" method="post">
                            <select class="form-control search-input select-input" name="noti_col">
                                <option value ="noti_id" selected>Notifi ID </option>
                                <option value ="noti_title">Notification Title </option>
                                <option value ="status">Status </option>
                            </select>
                            <input  type="search"  class="form-control search-input " name="searchData" placeholder="Search" aria-label="Search">
                            
                            <button class="btn search-btn" type="submit" name="searchSubmit"><i class="fas fa-search"></i></button>
                            
                        </form>
                </div>
                
            </div>

            <div class="recent-requests">
                    <div class="insights-02">

                    <?php

                        $sn = 1;

                        if(isset($_POST['searchSubmit'])){

                            $searches = $_POST['searchData'];

                            $sql = "SELECT * FROM notification WHERE noti_id LIKE '%$searches%' OR noti_title LIKE '%$searches%'";
                            $result = $db->conn->query($sql);

                            //count rows to check whether the category is available or not
                            $count = mysqli_num_rows($result);

                            if($count>0){
                                //categories available
                                while($row=mysqli_fetch_assoc($result))
                                {
                                    $not_id = $row['noti_id'];
                                    $not_title = $row['noti_title'];
                                    $not_img = $row['noti_img'];
                                    $active = $row['status']; ?>

                                    <div class="box-3 float-container">
                                        <h3 style="color:red; margin-bottom:6px"><?php echo $not_id; ?></h3>
                                        <h3><?php echo $not_title; ?></h3>
                                        <?php
                                            //check whether image is available or not
                                            if($not_img=="")
                                            {
                                                //display the message
                                                echo "<div class='error'>Image Not Available.</div>";
                                            }
                                            else
                                            {
                                                //image available
                                                ?>
                                                <div class="notifi-img">
                                                    <img src="../assets/imgs/notification/<?php echo $not_img; ?>">
                                                </div>
                                                <?php
                                            }
                                        ?>
                                            <div class="edit-btn-03">
                                                <h3>Status: 
                                                    <?php
                                                    if($active == 'Active'){ ?>
                                                        <span class="success"><?php echo $active; ?></span>
                                                    <?php } else{ ?>
                                                        <span class="danger"><?php echo $active; ?></span>
                                                    <?php } ?>
                                                </h3>
                                            </div>
                                            
                                            <div class="edit-btn-03">
                                            <button class="pop_btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $not_id; ?>">Edit</button>
                                                <button class="delete_btn">Delete</button>
                                            </div>
                                    </div>

                            <?php    }
                            }   
                        }else{ ?>

                        <?php
                            $sql2 = "SELECT * FROM notification";

                            $res = $db->conn->query($sql2);

                            //count rows to check whether the category is available or not
                            $count2 = mysqli_num_rows($res);

                            if($count2>0){
                                //categories available
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $not_id = $row['noti_id'];
                                    $not_title = $row['noti_title'];
                                    $not_img = $row['noti_img'];
                                    $active = $row['status']; ?>

                                    <div class="box-3 float-container">
                                        <h3 style="color:red; margin-bottom:6px"><?php echo $not_id; ?></h3>
                                        <h3><?php echo $not_title; ?></h3>
                                        <?php
                                            //check whether image is available or not
                                            if($not_img=="")
                                            {
                                                //display the message
                                                echo "<div class='error'>Image Not Available.</div>";
                                            }
                                            else
                                            {
                                                //image available
                                                ?>
                                                <div class="notifi-img">
                                                    <img src="../assets/imgs/notification/<?php echo $not_img; ?>">
                                                </div>
                                                <?php
                                            }
                                        ?>
                                            <div class="edit-btn-03">
                                                <h3>Status: 
                                                    <?php
                                                    if($active == 'Active'){ ?>
                                                        <span class="success"><?php echo $active; ?></span>
                                                    <?php } else{ ?>
                                                        <span class="danger"><?php echo $active; ?></span>
                                                    <?php } ?>
                                                </h3>
                                            </div>
                                            
                                            <div class="edit-btn-03">
                                            <button class="pop_btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $not_id; ?>">Edit</button>
                                                <button class="delete_btn">Delete</button>
                                            </div>
                                    </div>

                            <?php    }
                            }}
                        ?>
                    </div>
                </div>
        </div>
    </div>

            </div>
        </div>
    </div>
                                                     <!-- Edit Modal HTML -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Editing -->
                <form id="editNotiForm">
                    <input type="hidden" name="noti_id" id="editNotiId">

                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="editTitle">
                    </div>

                    <div class="mb-3">
                        <label for="editDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="editDesc" name="editDesc"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" name="editStatus">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <!-- Image Preview -->
                    <div class="mb-3">
                        <label for="editImagePreview" class="form-label">Current Image</label>
                        <div id="editImagePreview">
                            <img id="currentImage" src="" alt="Notification Image" style="width: 100%; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editImage" class="form-label">Change Image (optional)</label>
                        <input type="file" class="form-control" id="editImage" name="editImage">
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="..\assets\js\form-validation\admin-notification-validation.js"></script> <!-- Include your JS file -->
<!-- If using SweetAlert, include it -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>