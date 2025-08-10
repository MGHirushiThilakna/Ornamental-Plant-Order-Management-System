<?php 
$currentSubPage="addNoti";
include "notificationHandling.php"; ?>
<link rel="stylesheet" href="..\assets\css\employee-style.css">
<link rel="stylesheet" href="..\assets\css\admin-product-new-product-style.css">

<div class="container myMainContainer" >
    <div class="card mt-2 " style="margin-left:100px;margin-top:0px;margin-righ:100px">
        <div class="card-header mycardheader">Add New Notification</div>
            <div class="card-body mycardbody" >
                <div>
                <form id="AddNoti" style="width: 700px; margin-left:250px;">
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="form-floating myFormFloating" style="display: inline-block; margin-right: 10px; width:340px;">
                <input type="text" class="form-control myinputText" name="NotiTitle" id="floatingInput" placeholder=" ">
                <label for="floatingInput">Notification Title</label>
                <div id="strNTitleError"></div>
            </div>
            <div style="margin-top:25px;">
                <textarea class="form-control" name="notifiDes" placeholder="Enter notification description here" id="notifi"></textarea>
                <div id="strnotifiDesError"></div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="form-group has-validation myFormGroup" style="margin-top:25px;">
                <label for="image01" class="form-label">Please select images</label>
                <input type="file" id="image01" name="image01" accept="image/png, image/jpeg" onchange="preview(this);" class="form-control myChooseFile">
                <div id="strImgErr"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="imagePreview" style="margin-left:20px;width: 300px; height: 200px; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                <img id="previewImage" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating myFormFloating">
            <select class="form-select myselect" id="floatingSelect" name="status">
            <option value="0">Select</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            </select>
            <label for="floatingSelect">Status</label>
            <div id="strStatusError"></div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12" style="margin-left:60px;">
            <div class="btn-col">
                <button class="btn myBtn" type="submit" name="btnNoti">Add Notification</button>
            </div>
        </div>
    </div>
</form>
                    </div>
                   
            </div>
        </div>
    </div>
</div>
<script src="..\assets\js\form-validation\admin-notification-validation.js"></script>