// H!MANJA - Order Page JavaScript

document.addEventListener("DOMContentLoaded", () => {
  const himadaSelect = document.getElementById("himada_select")
  const productsContainer = document.getElementById("products_container")
  const productsList = document.getElementById("products_list")
  const modal = document.getElementById("productModal")
  const modalClose = document.querySelector(".modal-close")
  const addToCartForm = document.getElementById("add_to_cart_form")

  let currentProducts = []
  let selectedHimadaId = null

  // HIMADA selection change
  himadaSelect.addEventListener("change", function () {
    const himadaId = this.value
    selectedHimadaId = himadaId

    if (himadaId) {
      loadProducts(himadaId)
    } else {
      productsContainer.style.display = "none"
      productsList.innerHTML = ""
    }
  })

  // Load products for selected HIMADA
  function loadProducts(himadaId) {
    showLoading(productsList)

    const formData = new FormData()
    formData.append("action", "get_products")
    formData.append("himada_id", himadaId)

    fetch("order.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((products) => {
        currentProducts = products
        displayProducts(products)
        productsContainer.style.display = "block"
      })
      .catch((error) => {
        console.error("Error loading products:", error)
        showError("Gagal memuat produk. Silakan coba lagi.")
      })
  }

  // Display products
  function displayProducts(products) {
    if (products.length === 0) {
      productsList.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <p>Tidak ada produk tersedia</p>
                </div>
            `
      return
    }

    productsList.innerHTML = products
      .map(
        (product) => `
            <div class="product-item" data-product-id="${product.id}">
                <img src="${product.gambar_url || "/placeholder.svg?height=120&width=200"}" 
                     alt="${product.nama_produk}" class="product-image"
                     onerror="this.src='/placeholder.svg?height=120&width=200'">
                <h4 class="product-name">${product.nama_produk}</h4>
                <p class="product-price">${formatCurrency(product.harga)}</p>
                <p class="product-stock">Stok: ${product.stok}</p>
                <div class="product-actions">
                    <button class="btn-select" onclick="openProductModal(${product.id})">
                        Pilih Produk
                    </button>
                </div>
            </div>
        `,
      )
      .join("")
  }

  // Open product modal
  window.openProductModal = (productId) => {
    const product = currentProducts.find((p) => p.id == productId)
    if (!product) return

    // Fill modal with product data
    document.getElementById("modal_product_name").textContent = product.nama_produk
    document.getElementById("modal_product_image").src = product.gambar_url || "/placeholder.svg?height=200&width=200"
    document.getElementById("modal_product_description").textContent = product.deskripsi
    document.getElementById("modal_product_price").textContent = formatCurrency(product.harga)
    document.getElementById("modal_product_stock").textContent = `Stok tersedia: ${product.stok}`

    // Set form data
    document.getElementById("modal_himada_id").value = selectedHimadaId
    document.getElementById("modal_product_id").value = productId
    document.getElementById("quantity").max = product.stok
    document.getElementById("quantity").value = 1
    document.getElementById("notes").value = ""

    // Show modal
    modal.style.display = "block"
    document.body.style.overflow = "hidden"
  }

  // Close modal
  function closeModal() {
    modal.style.display = "none"
    document.body.style.overflow = "auto"
  }

  modalClose.addEventListener("click", closeModal)

  // Close modal when clicking outside
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      closeModal()
    }
  })

  // Quantity change buttons
  window.changeQuantity = (change) => {
    const quantityInput = document.getElementById("quantity")
    const currentValue = Number.parseInt(quantityInput.value)
    const newValue = currentValue + change
    const maxValue = Number.parseInt(quantityInput.max)

    if (newValue >= 1 && newValue <= maxValue) {
      quantityInput.value = newValue
    }
  }

  // Add to cart form submission
  addToCartForm.addEventListener("submit", function (e) {
    e.preventDefault()

    const formData = new FormData(this)
    formData.append("action", "add_to_cart")

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]')
    const originalText = submitBtn.innerHTML
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Menambahkan...'
    submitBtn.disabled = true

    fetch("order.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          showSuccess(result.message)
          closeModal()
          // Reload page to update cart
          setTimeout(() => {
            window.location.reload()
          }, 1000)
        } else {
          showError(result.message)
        }
      })
      .catch((error) => {
        console.error("Error adding to cart:", error)
        showError("Gagal menambahkan ke keranjang. Silakan coba lagi.")
      })
      .finally(() => {
        submitBtn.innerHTML = originalText
        submitBtn.disabled = false
      })
  })

  // Utility functions
  function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    }).format(amount)
  }

  function showLoading(element) {
    element.innerHTML = `
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p>Memuat produk...</p>
            </div>
        `
  }

  function showError(message) {
    showNotification(message, "error")
  }

  function showSuccess(message) {
    showNotification(message, "success")
  }

  function showNotification(message, type) {
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.innerHTML = `
            <span class="notification-icon">${type === "success" ? "✅" : "⚠️"}</span>
            <span class="notification-text">${message}</span>
            <button class="notification-close">&times;</button>
        `

    document.body.appendChild(notification)

    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove()
      }
    }, 5000)

    // Manual close
    notification.querySelector(".notification-close").addEventListener("click", () => {
      notification.remove()
    })
  }

  // Form validation
  function validateForm() {
    const requiredFields = document.querySelectorAll("[required]")
    let isValid = true

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        field.classList.add("error")
        isValid = false
      } else {
        field.classList.remove("error")
      }
    })

    return isValid
  }

  // Auto-save cart to localStorage
  function saveCartToStorage() {
    const cartData = {
      himada_id: selectedHimadaId,
      timestamp: Date.now(),
    }
    localStorage.setItem("himanja_cart", JSON.stringify(cartData))
  }

  // Load cart from localStorage
  function loadCartFromStorage() {
    const cartData = localStorage.getItem("himanja_cart")
    if (cartData) {
      try {
        const parsed = JSON.parse(cartData)
        // Check if cart is not too old (24 hours)
        if (Date.now() - parsed.timestamp < 24 * 60 * 60 * 1000) {
          if (parsed.himada_id) {
            himadaSelect.value = parsed.himada_id
            loadProducts(parsed.himada_id)
          }
        }
      } catch (error) {
        console.error("Error loading cart from storage:", error)
      }
    }
  }

  // Initialize
  loadCartFromStorage()
})

// Remove from cart
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-remove-cart")) {
    const cartKey = e.target.getAttribute("data-cart-key")
    if (!cartKey) return

    if (!confirm("Yakin ingin menghapus item ini dari keranjang?")) return

    const formData = new FormData()
    formData.append("action", "remove_from_cart")
    formData.append("cart_key", cartKey)

    e.target.disabled = true
    e.target.textContent = "Menghapus..."

    fetch("order.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((result) => {
        if (result.success) {
          showSuccess(result.message)
          // reload halaman biar update cart
          setTimeout(() => window.location.reload(), 1000)
        } else {
          showError(result.message)
          e.target.disabled = false
          e.target.textContent = "❌ Hapus"
        }
      })
      .catch((err) => {
        console.error(err)
        showError("Gagal menghapus item. Coba lagi.")
        e.target.disabled = false
        e.target.textContent = "❌ Hapus"
      })
  }
})


// CSS for notifications
const notificationStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 1001;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    }
    
    .notification-success {
        border-left: 4px solid #10b981;
    }
    
    .notification-error {
        border-left: 4px solid #ef4444;
    }
    
    .notification-icon {
        font-size: 1.25rem;
    }
    
    .notification-text {
        flex: 1;
        font-weight: 500;
    }
    
    .notification-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-close:hover {
        color: #374151;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .loading-state {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`

// Inject notification styles
const styleSheet = document.createElement("style")
styleSheet.textContent = notificationStyles
document.head.appendChild(styleSheet)
