// Create the validation script (delivery_charge_validation.js)
document.addEventListener('DOMContentLoaded', function() {
  const updateChargeForm = document.getElementById('update-charge-form');
  
  function validateForm() {
      let isValid = true;
      const chargeName = document.getElementById('chargeName').value.trim();
      const chargeValue = document.getElementById('chargeValue').value;
      const chargeValueError = document.getElementById('chargeValueError');
      
      // Validate charge name
      if (!chargeName) {
          isValid = false;
          document.getElementById('chargeName').classList.add('is-invalid');
      } else {
          document.getElementById('chargeName').classList.remove('is-invalid');
      }
      
      // Validate charge value
      if (!chargeValue || chargeValue <= 0) {
          isValid = false;
          document.getElementById('chargeValue').classList.add('is-invalid');
          chargeValueError.textContent = 'Please enter a valid charge amount greater than 0';
          chargeValueError.style.color = 'red';
      } else {
          document.getElementById('chargeValue').classList.remove('is-invalid');
          chargeValueError.textContent = '';
      }
      
      return isValid;
  }
  
  if (updateChargeForm) {
      updateChargeForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          if (!validateForm()) {
              return false;
          }
          
          const formData = new FormData(this);
          formData.append('action', 'add');
          
          fetch('delivery_charges_ajax.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  Swal.fire({
                      title: 'Success',
                      text: 'Delivery charge added successfully!',
                      icon: 'success'
                  }).then(() => {
                      location.reload();
                  });
              } else {
                  Swal.fire({
                      title: 'Error',
                      text: data.message || 'Failed to add delivery charge',
                      icon: 'error'
                  });
              }
          })
          .catch(error => {
              console.error('Error:', error);
              Swal.fire({
                  title: 'Error',
                  text: 'An unexpected error occurred',
                  icon: 'error'
              });
          });
      });
  }
});