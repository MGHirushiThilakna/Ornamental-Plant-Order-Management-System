document.addEventListener("DOMContentLoaded", function () {
   //view action link
    document.querySelectorAll(".item-action.review .action-link")
      .forEach(function (link) {
        link.addEventListener("click", function (event) {
          event.preventDefault();
          var itemId = this.getAttribute("data-item-id");
          fetchItemDetails(itemId);
        });
      });
      //close view
    document.querySelector(".close-quick-view")
      .addEventListener("click", function () {
        document.querySelector(".product-quick-view-box").style.display = "none";
      });
  });
  
  function fetchItemDetails(itemId) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getProductDetails.php?item_id=" + itemId, true);
    xhr.onload = function () {
      if (this.status === 200) {
        try {
          var itemDetails = JSON.parse(this.responseText);
          displayItemDetails(itemDetails);
          document.querySelector(".product-quick-view-box").style.display =
            "block";
        } catch (e) {
          console.error("Error parsing response:", e, this.responseText);
        }
      } else {
        console.error("Failed to fetch item details. Status:", this.status);
      }
    };
    xhr.send();
  }
  
  function displayItemDetails(item) {
    // Update basic item details
    document.getElementById("quickview-product-name").textContent =
      item.name || "Product Name";
    document.getElementById("quickview-description").textContent =
      item.description || "No description available.";
    document.getElementById("quickview-sku").innerHTML = `<span>ID:</span> ${
      item.item_id || "N/A"
    }`;
    document.getElementById(
      "quickview-category"
    ).innerHTML = `<span>Category:</span> ${item.category || "Uncategorized"}`;
  
    const carouselInner = document.querySelector(
      "#quickview-carousel .carousel-inner"
    );
    const prevButton = document.getElementById("prev-image");
    const nextButton = document.getElementById("next-image");
    const priceElement = document.getElementById("quickview-original-price");
    const quantityElement = document.getElementById("quickview-quantity");
    const colorContainer = document.getElementById("color-variation-container");
  
    // Clear previous content
    carouselInner.innerHTML = "";
    colorContainer.innerHTML = "";
  
    // Handle Images
    setupImageCarousel(
      item.images,
      item.name,
      carouselInner,
      prevButton,
      nextButton
    );
    // Handle Colors
    setupColorVariations(item, colorContainer, priceElement, quantityElement);
  }
  
  function setupImageCarousel(
    images,
    productName,
    carouselInner,
    prevButton,
    nextButton
    //pass the parameters
  ) {
    if (images && images.length > 0) {
      images.forEach((imageSrc, index) => {
        const imgWrapper = document.createElement("div");
        imgWrapper.classList.add("carousel-item");
        if (index === 0) imgWrapper.classList.add("active");
  
        const imgElement = document.createElement("img");
        imgElement.src = imageSrc;
        imgElement.alt = `${productName || "Product"} Image ${index + 1}`;
        imgElement.classList.add("img-fluid", "w-100");
  
        imgWrapper.appendChild(imgElement);
        carouselInner.appendChild(imgWrapper);
      });
  //image navigation
      if (images.length > 1) {
        prevButton.style.display = "block";
        nextButton.style.display = "block";
        setupImageNavigation(images, carouselInner);
      } else {
        prevButton.style.display = "none";
        nextButton.style.display = "none";
      }
    } else {
      carouselInner.innerHTML =
        '<div class="carousel-item active"><p>No images available</p></div>';
      prevButton.style.display = "none";
      nextButton.style.display = "none";
    }
  }
  
  function setupImageNavigation(images, carouselInner) {
    //initialize image index as 0
    let currentImageIndex = 0;
  
    document.getElementById("prev-image").addEventListener("click", () => {
      currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
      updateCarousel(currentImageIndex, carouselInner);
    });
  
    document.getElementById("next-image").addEventListener("click", () => {
      currentImageIndex = (currentImageIndex + 1) % images.length;
      updateCarousel(currentImageIndex, carouselInner);
    });
  // update carousel 
    function updateCarousel(index, carouselInner) {
      const carouselItems = carouselInner.querySelectorAll(".carousel-item");
      carouselItems.forEach((item, i) => {
        item.classList.toggle("active", i === index);
      });
    }
  }
  
  function setupColorVariations(
    item,
    colorContainer,
    priceElement,
    quantityElement
  ) {
    if (
      item.colors === "Yes" &&
      item.color_variations &&
      item.color_variations.length > 0
    ) {
      //create color burrons
      item.color_variations.forEach((colorVar, index) => {
        const colorButton = document.createElement("button");
        colorButton.style.backgroundColor = colorVar.color || "#ccc";
        colorButton.classList.add("color-variation-btn");
        colorButton.setAttribute("data-color-id", colorVar.color_id);
        colorButton.setAttribute("data-color", colorVar.color);
  
        if (index === 0) {
          colorButton.classList.add("selected");
          updatePriceAndQuantity(colorVar, item, priceElement, quantityElement);
        }
  //when select other color
        colorButton.addEventListener("click", function () {
          document
            .querySelectorAll(".color-variation-btn")
            .forEach((btn) => btn.classList.remove("selected"));
          this.classList.add("selected");
          updatePriceAndQuantity(colorVar, item, priceElement, quantityElement);
        });
  
        colorContainer.appendChild(colorButton);
      });
    } else {
      updatePriceAndQuantity(item, item, priceElement, quantityElement);
    }
  }
  
  function updatePriceAndQuantity(colorVar, item, priceElement, quantityElement) {
    const maxQuantity =
      colorVar.quantity !== undefined ? parseInt(colorVar.quantity) : 1;
    quantityElement.innerHTML = "";
  //when quantity is empty
    if (maxQuantity === 0) {
      const outOfStockMessage = document.createElement("span");
      outOfStockMessage.textContent = "Out of Stock";
      outOfStockMessage.style.color = "red";
      outOfStockMessage.classList.add("out-of-stock-message");
      quantityElement.appendChild(outOfStockMessage);
      priceElement.textContent = "Rs 0.00";
      return;
    }
  
    priceElement.textContent =
      "Rs " + parseFloat(colorVar.u_price || item.price).toFixed(2);
  //create  quantity control, lable, action buttons
    const quantityContainer = createQuantityControl(maxQuantity);
    const quantityLabel = createQuantityLabel(maxQuantity);
    const buttonContainer = createActionButtons(item, quantityContainer);
  
    quantityElement.appendChild(quantityLabel);
    quantityElement.appendChild(quantityContainer);
    quantityElement.appendChild(buttonContainer);
  }
  
  function createQuantityControl(maxQuantity) {
    const quantityContainer = document.createElement("div");
    quantityContainer.classList.add("quantity-control");
  
    const decreaseBtn = document.createElement("button");
    decreaseBtn.textContent = "-";
    decreaseBtn.classList.add("quantity-btn", "decrease-btn");
  //create decrease  increase btn
    const quantityInput = document.createElement("input");
    quantityInput.type = "number";
    quantityInput.min = "1";
    quantityInput.max = maxQuantity;
    quantityInput.value = "1";
    quantityInput.readOnly = true;
    quantityInput.classList.add("quantity-input");
    const increaseBtn = document.createElement("button");
    increaseBtn.textContent = "+";
    increaseBtn.classList.add("quantity-btn", "increase-btn");

  //decrease  increase btn function
    decreaseBtn.addEventListener("click", () => {
      let currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
      }
    });
  
    increaseBtn.addEventListener("click", () => {
      let currentValue = parseInt(quantityInput.value);
      if (currentValue < maxQuantity) {
        quantityInput.value = currentValue + 1;
      }
    });
  
    quantityContainer.appendChild(decreaseBtn);
    quantityContainer.appendChild(quantityInput);
    quantityContainer.appendChild(increaseBtn);
  
    return quantityContainer;
  }
  //show available quantity
  function createQuantityLabel(maxQuantity) {
    const quantityLabel = document.createElement("span");
    quantityLabel.textContent = `${maxQuantity} Available`;
    quantityLabel.classList.add("quantity-label");
    return quantityLabel;
  }
  
  //create add to cart and but now buttons
  function createActionButtons(item, quantityContainer) {
    const buttonContainer = document.createElement("div");
    buttonContainer.classList.add("button-container");
  
    const addToCartButton = document.createElement("button");
    addToCartButton.textContent = "Add to Cart";
    addToCartButton.classList.add("btn", "btn-primary", "add-to-cart-btn");
  
    const buyNowButton = document.createElement("button");
    buyNowButton.textContent = "Buy Now";
    buyNowButton.classList.add("btn", "btn-success", "buy-now-btn");
  
    //when click add to cart button
    addToCartButton.addEventListener("click", () => {
      if (!window.handleLoginCheck()) {
        return;
      }
  
      const quantity = parseInt(
        quantityContainer.querySelector(".quantity-input").value
      );
      let colorId = null;
      let color = null;
  
      if (item.colors === "Yes" && item.color_variations.length > 0) {
        const selectedColorButton = document.querySelector(
          ".color-variation-btn.selected"
        );
        if (!selectedColorButton) {
          alert("Please select a color variation.");
          return;
        }
        colorId = selectedColorButton.getAttribute("data-color-id");
        color = selectedColorButton.getAttribute("data-color");
      }
  
      if (quantity > 0) {
        //call add to cart function
        addToCart(item.item_id, colorId, color, quantity);
      } else {
        alert("Please select a valid quantity.");
      }
    });

  //when click buy now button
    buyNowButton.addEventListener("click", () => {
      // Check login status first
      if (!window.handleLoginCheck()) {
        return;
      }
  
      const quantity = parseInt(
        quantityContainer.querySelector(".quantity-input").value
      );
      handleBuyNow(item, quantity);
    });
  
    buttonContainer.appendChild(addToCartButton);
    buttonContainer.appendChild(buyNowButton);
  
    return buttonContainer;
  }
  
  function addToCart(itemId, colorId, color, quantity) {
    const handleQuickView = (action) => {
      const quickView = document.querySelector(".product-quick-view");
      if (quickView) {
        if (action === "hide") {
          quickView.style.display = "none";
        } else {
          quickView.style.display = "block";
        }
      }
    };
  
    const swalConfig = {
      customClass: {
        popup: "swal2-above-modal",
        backdrop: "swal2-above-modal-backdrop",
      },
      didOpen: () => {
        handleQuickView("hide");
      },
      didClose: () => {
        handleQuickView("show");
      },
    };
  
    Swal.fire({
      title: "Adding to Cart...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
        handleQuickView("hide");
      },
      didClose: () => {
        handleQuickView("show");
      },
    });
  
    fetch("checkLoginStatus.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((sessionData) => {
        if (!sessionData.loggedIn) {
          Swal.fire({
            icon: "warning",
            title: "Not Logged In",
            text: "Please login to add items to your cart",
            showCancelButton: true,
            confirmButtonText: "Log In",
            cancelButtonText: "Cancel",
            ...swalConfig,
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "customerLogin.php";
            }
          });
          return Promise.reject("Not logged in");
        }
  
        const params = new URLSearchParams({
          item_id: itemId,
          quantity: quantity,
        });
  
        if (colorId && colorId !== "No Color") {
          params.append("color_id", colorId);
          params.append("color", color);
        }
  
        return fetch("addToCart.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: params,
        });
      })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: data.message || "Item added to cart successfully",
            timer: 2000,
            showConfirmButton: false,
            ...swalConfig,
          });
        } else {
          throw new Error(data.message || "Failed to add item to cart");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
  
        if (error === "Not logged in") return;
  
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text:
            error.message ||
            "An error occurred while adding the item to the cart",
          confirmButtonText: "OK",
          ...swalConfig,
        });
      });
  }
  // buy now function 
  function handleBuyNow(item, quantity) {
    const handleQuickView = (action) => {
      const quickView = document.querySelector(".product-quick-view");
      if (quickView) {
        if (action === "hide") {
          quickView.style.display = "none";
        } else {
          quickView.style.display = "block";
        }
      }
    };
  
    const swalConfig = {
      customClass: {
        popup: "swal2-above-modal",
        backdrop: "swal2-above-modal-backdrop",
      },
      didOpen: () => {
        handleQuickView("hide");
      },
      didClose: () => {
        handleQuickView("show");
      },
    };
  
    if (quantity <= 0) {
      Swal.fire({
        icon: "warning",
        title: "Invalid Quantity",
        text: "Please select a valid quantity to proceed.",
        confirmButtonText: "OK",
        ...swalConfig,
      });
      return;
    }
  
    Swal.fire({
      title: "Processing...",
      text: "Please wait while we prepare your order",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
        handleQuickView("hide");
      },
      ...swalConfig,
    });
  
    fetch("checkLoginStatus.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((sessionData) => {
        if (!sessionData.loggedIn) {
          Swal.fire({
            icon: "warning",
            title: "Not Logged In",
            text: "Please login to continue with your purchase",
            showCancelButton: true,
            confirmButtonText: "Log In",
            cancelButtonText: "Cancel",
            ...swalConfig,
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "customerLogin.php";
            }
          });
          return Promise.reject("Not logged in");
        }
  
        const priceElement = document.getElementById("quickview-original-price");
        if (!priceElement) {
          throw new Error("Price information not found");
        }
  
        const price = parseFloat(priceElement.textContent.replace("Rs ", ""));
        if (isNaN(price)) {
          throw new Error("Invalid price format");
        }
        // then order process to checkout.php by post method
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "checkout.php";
        form.style.display = "none";
  
        const fields = [
          { name: "item_id", value: item.item_id },
          { name: "item_name", value: item.name || "Unknown Item" },
          { name: "quantity", value: quantity },
          { name: "price", value: price },
        ];
  
        // Handle color selection if applicable
        if (item.colors === "Yes") {
          const selectedColorButton = document.querySelector(
            ".color-variation-btn.selected"
          );
          if (!selectedColorButton) {
            throw new Error("Please select a color before proceeding");
          }
          fields.push(
            {
              name: "color_id",
              value: selectedColorButton.getAttribute("data-color-id"),
            },
            {
              name: "color",
              value: selectedColorButton.getAttribute("data-color"),
            }
          );
        }
  
        fields.forEach((field) => {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = field.name;
          input.value = field.value;
          form.appendChild(input);
        });
  
        document.body.appendChild(form);
  
        Swal.close();
  
        form.submit();
      })
      .catch((error) => {
        console.error("Error:", error);
  
        if (error === "Not logged in") return;
  
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text:
            error.message ||
            "An error occurred while processing your order. Please try again.",
          confirmButtonText: "OK",
          ...swalConfig,
        });
      });
  }
  