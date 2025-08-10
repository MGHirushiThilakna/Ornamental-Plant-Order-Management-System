$(document).ready(function() {
    $('#AddProduct').submit(function(event) {
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

      if (fileInput.files.length === 0) {
        $('#file-input').addClass('is-invalid');
        $('#strErr').html('Please select image file');
        isValid_file_input = false;
      }

      if (
        isValid_select_main &&
        isValid_select_sub &&
        isValid_select_brand &&
        isValid_select_sup &&
        isValid_product_name &&
        isValid_product_desc &&
        isValid_product_UnitPrice &&
        isValid_product_SellingPrice &&
        isValid_file_input
      ) {
        // Form is valid, you can proceed with further actions
        $('.myinputText').removeClass('is-invalid');
        $('#file-input').removeClass('is-invalid');
        $('.myselect').removeClass('is-invalid');
        $('.myinputTextArea').removeClass('is-invalid');
      resetError();
      } else {
        event.preventDefault();
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
  
  