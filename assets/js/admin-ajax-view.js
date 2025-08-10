/*images handling*/
function preview(){
    let btn = document.getElementById("btn-updateImage");
    let fileInput = document.getElementById("file-input");
    let imageContainer = document.getElementById("images");
    let strError = document.getElementById("strErr");
    strError.style.color = "red";
    if(fileInput.files.length < 3){
        btn.disabled = true;
        fileInput.setAttribute("class","form-control myChooseFile is-invalid ");
        strError.innerHTML = "you must select 3 images";
        
    }else if(fileInput.files.length > 3){
        btn.disabled = true;
        fileInput.setAttribute("class","form-control myChooseFile is-invalid ");
        strError.innerHTML = "Maximum images you could select is 3";
    }else{
        btn.disabled = false;
        fileInput.setAttribute("class","form-control myChooseFile");
        strError.style.color = "green";
        strError.innerHTML = "looks good !";
        imageContainer.innerHTML = "";
        var x = 1;
        for(i of fileInput.files){
            
            let reader = new FileReader();
            let figure = document.createElement("div");
            let figCap = document.createElement("figcaption");
            figCap.innerText = "image "+x;
            x++;
            figure.appendChild(figCap);
            figure.setAttribute("class","col");
            reader.onload=()=>{
                let img = document.createElement("img");
                img.setAttribute("src",reader.result);
                img.setAttribute("class","img img-file");
                figure.insertBefore(img,figCap);
            }
            imageContainer.appendChild(figure);
            reader.readAsDataURL(i);
        }
    }

}

$(document).ready(function(){
    $('#updateImage').submit(function(event) {
        event.preventDefault();

        const fileInput = $("#file-input")[0];
                
                // Check if files are selected
        if (fileInput.files.length > 0) {
                    // Create a new FormData object to store the files
            const formData = new FormData();

                        // Add each selected file to the FormData object
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append("files[]", fileInput.files[i]);
            }
            var productID = $("#pid").val();
            formData.append("task", "updateImage");
            formData.append("pid", productID);
            updateProductImage(formData);
        }

    });

    $('#UpdateProduct').submit(function(event){
        event.preventDefault();
        var pid= $("#PID").val();
        var select_main = $('select[name="pMainCat"]');
        var select_sub = $('select[name="psubCat"]');
        var select_brand = $('select[name="pBrand"]');
        var select_sup = $('select[name="pSupplier"]');
        var product_name = $.trim($('input[name="pName"]').val());
        var product_desc = $.trim($('textarea[name="pDesc"]').val());
        var product_UnitPrice = $.trim($('input[name="pUnitPrice"]').val());
        var product_SellingPrice = $.trim($('input[name="pSalePrice"]').val());
        var fileInput = $('#file-input')[0];
    
        $('.myinputText').removeClass('is-invalid');
        $('#file-input').removeClass('is-invalid');
        $('.myselect').removeClass('is-invalid');
        $('.myinputTextArea').removeClass('is-invalid');
        resetError();
        
        var isValid_select_main = true;
        var isValid_select_sub = true;
        var isValid_select_brand = true;
        var isValid_select_sup = true;
        var isValid_product_name = true;
        var isValid_product_desc = true;
        var isValid_product_UnitPrice = true;
        var isValid_product_SellingPrice = true;
        var isValid_file_input = true;
    
        if (parseInt(select_main.val()) === 0) {
            select_main.addClass('is-invalid');
            $('#strMainError').html('Please select a Main Category');
            isValid_select_main = false;
        }
    
        if (parseInt(select_sub.val()) === 0) {
            select_sub.addClass('is-invalid');
            $('#strSubError').html('Please select a Sub Category');
            isValid_select_sub = false;
        }
    
        if (parseInt(select_brand.val()) === 0) {
            select_brand.addClass('is-invalid');
            $('#strBrandError').html('Please select a Brand');
            isValid_select_brand = false;
        }
    
        if (parseInt(select_sup.val()) === 0) {
            select_sup.addClass('is-invalid');
            $('#strSupError').html('Please select a Supplier');
            isValid_select_sup = false;
        }
    
        if (product_name === '') {
            $('input[name="pName"]').addClass('is-invalid');
            $('#strPNameError').html('This field is required');
            isValid_product_name = false;
        }
    
        if (product_desc === '') {
            $('textarea[name="pDesc"]').addClass('is-invalid');
            $('#strPDescError').html('This field is required');
            isValid_product_desc = false;
        }
    
        if (product_UnitPrice === '') {
            $('input[name="pUnitPrice"]').addClass('is-invalid');
            $('#strPUnitError').html('This field is required');
            isValid_product_UnitPrice = false;
        } else if (isNaN(product_UnitPrice)) {
            $('input[name="pUnitPrice"]').addClass('is-invalid');
            $('#strPUnitError').html('Invalid value entered');
            isValid_product_UnitPrice = false;
        }
    
        if (product_SellingPrice === '') {
            $('input[name="pSalePrice"]').addClass('is-invalid');
            $('#strPSellError').html('This field is required');
            isValid_product_SellingPrice = false;
        } else if (isNaN(product_SellingPrice)) {
            $('input[name="pSalePrice"]').addClass('is-invalid');
            $('#strPSellError').html('Invalid value entered');
            isValid_product_SellingPrice = false;
        }

        if (
            isValid_select_main &&
            isValid_select_sub &&
            isValid_select_brand &&
            isValid_select_sup &&
            isValid_product_name &&
            isValid_product_desc &&
            isValid_product_UnitPrice &&
            isValid_product_SellingPrice
        ) {
            // Form is valid, you can proceed with further actions
            $('.myinputText').removeClass('is-invalid');
            $('#file-input').removeClass('is-invalid');
            $('.myselect').removeClass('is-invalid');
            $('.myinputTextArea').removeClass('is-invalid');
            resetError();

            // Ajax update

            $.ajax({
                url : "adminProductAjax.php",
                type : "post",
                data : {
                    task:"updateProductInfo",
                    pid:pid,
                    pName:product_name,
                    pDesc:product_desc,
                    pUnit:product_UnitPrice,
                    pSell:product_SellingPrice,
                    mId:select_main.val(),
                    sId:select_sub.val(),
                    bId:select_brand.val(),
                    supId:select_sup.val()
                },
                success: function(response){
                    if(parseInt(response) === 1){
                        console.log("success");
                        Swal.fire({icon:'success',title:'Done !',text:'Product Info Updated'});
                    }else{
                        console.log(response);
                        Swal.fire({icon:'warning',title:'Something is not right',text:''});
                    }
                }
            });
        }
        
    });

});
function resetError() {
    $('#strMainError').html('');
    $('#strSubError').html('');
    $('#strBrandError').html('');
    $('#strSupError').html('');
    $('#strPNameError').html('');
    $('#strPDescError').html('');
    $('#strPUnitError').html('');
    $('#strPSellError').html('');
  }

function updateProductImage(formData){
    $.ajax({
        url: "adminProductAjax.php",
        type:"post",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(parseInt(response) === 1){
                console.log("success");
                Swal.fire({icon:'success',title:'Done !',text:'Product Image Updated'});
            }else{
                console.log(response);
                Swal.fire({icon:'warning',title:'Something is not right',text:''});
            }
        },
        error: function(xhr, status, error) {
            // Handle errors if the AJAX request fails
            console.error("Error:", error);
        }
    });
}

