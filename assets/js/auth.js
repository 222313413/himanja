// H!MANJA - Authentication JavaScript

document.addEventListener("DOMContentLoaded", () => {
  initAuthForm()
  initPasswordToggle()
  initEmailValidation()
  initDemoAccountHelper()
})

function initAuthForm() {
  const authForm = document.querySelector(".auth-form")
  const submitBtn = document.querySelector(".btn-auth")

  if (authForm && submitBtn) {
    authForm.addEventListener("submit", function (e) {
      const btnText = submitBtn.querySelector(".btn-text")
      const btnLoading = submitBtn.querySelector(".btn-loading")

      if (validateForm(this)) {
        // Show loading state
        submitBtn.classList.add("loading")
        submitBtn.disabled = true

        if (btnText) btnText.style.display = "none"
        if (btnLoading) btnLoading.style.display = "flex"

        // Reset after 10 seconds if no response
        setTimeout(() => {
          submitBtn.classList.remove("loading")
          submitBtn.disabled = false
          if (btnText) btnText.style.display = "inline"
          if (btnLoading) btnLoading.style.display = "none"
        }, 10000)
      }
    })
  }
}

function initPasswordToggle() {
  window.togglePassword = (inputId) => {
    const input = document.getElementById(inputId)
    const toggle = input.parentElement.querySelector(".password-toggle")

    if (input.type === "password") {
      input.type = "text"
      toggle.textContent = "🙈"
      toggle.setAttribute("aria-label", "Hide password")
    } else {
      input.type = "password"
      toggle.textContent = "👁️"
      toggle.setAttribute("aria-label", "Show password")
    }
  }
}

function initEmailValidation() {
  const emailInput = document.getElementById("username")
  if (emailInput) {
    emailInput.addEventListener("blur", function () {
      validateSTISEmail(this)
    })

    emailInput.addEventListener("input", function () {
      // Clear previous validation state
      this.classList.remove("error", "success")
      clearFieldError(this)
    })
  }
}

function validateSTISEmail(input) {
  const value = input.value.trim()

  // Skip validation if it's not an email format
  if (!value.includes("@")) {
    return true
  }

  const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
  const isSTISEmail = value.toLowerCase().endsWith("@stis.ac.id")

  if (!isValidEmail) {
    showFieldError(input, "Format email tidak valid")
    return false
  }

  if (!isSTISEmail) {
    showFieldError(input, "Hanya email @stis.ac.id yang diizinkan")
    return false
  }

  input.classList.add("success")
  clearFieldError(input)
  return true
}

function validateForm(form) {
  const inputs = form.querySelectorAll("input[required]")
  let isValid = true

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      showFieldError(input, "Field ini wajib diisi")
      isValid = false
    } else if (input.type === "email" || input.id === "username") {
      if (!validateSTISEmail(input)) {
        isValid = false
      }
    }
  })

  return isValid
}

function showFieldError(field, message) {
  clearFieldError(field)

  field.classList.add("error")

  const errorDiv = document.createElement("div")
  errorDiv.className = "field-error"
  errorDiv.textContent = message

  field.parentElement.appendChild(errorDiv)
}

function clearFieldError(field) {
  field.classList.remove("error")
  const existingError = field.parentElement.querySelector(".field-error")
  if (existingError) {
    existingError.remove()
  }
}

function initDemoAccountHelper() {
  const demoItems = document.querySelectorAll(".demo-item")

  demoItems.forEach((item) => {
    item.addEventListener("click", function () {
      const text = this.textContent
      const emailMatch = text.match(/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/)

      if (emailMatch) {
        const usernameInput = document.getElementById("username")
        const passwordInput = document.getElementById("password")

        if (usernameInput) {
          usernameInput.value = emailMatch[1]
          usernameInput.focus()
        }

        if (passwordInput) {
          passwordInput.value = "password"
        }

        // Add visual feedback
        this.style.background = "rgba(178, 231, 232, 0.2)"
        setTimeout(() => {
          this.style.background = ""
        }, 1000)
      }
    })

    // Add hover effect
    item.style.cursor = "pointer"
    item.addEventListener("mouseenter", function () {
      this.style.background = "rgba(178, 231, 232, 0.1)"
    })

    item.addEventListener("mouseleave", function () {
      if (!this.style.background.includes("0.2")) {
        this.style.background = ""
      }
    })
  })
}

// Form animation
function animateFormElements() {
  const formElements = document.querySelectorAll(".form-group, .form-options, .btn-auth")

  formElements.forEach((element, index) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(20px)"
    element.style.transition = "all 0.6s ease"

    setTimeout(() => {
      element.style.opacity = "1"
      element.style.transform = "translateY(0)"
    }, index * 100)
  })
}

// Initialize animations when page loads
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(animateFormElements, 300)
})

// Real-time password strength indicator (for register page)
function initPasswordStrength() {
  const passwordInput = document.getElementById("password")
  if (passwordInput && passwordInput.name === "password") {
    passwordInput.addEventListener("input", function () {
      checkPasswordStrength(this.value)
    })
  }
}

function checkPasswordStrength(password) {
  const strengthIndicator = document.querySelector(".password-strength")
  if (!strengthIndicator) return

  let strength = 0
  const feedback = []

  // Length check
  if (password.length >= 8) {
    strength += 1
  } else {
    feedback.push("Minimal 8 karakter")
  }

  // Uppercase check
  if (/[A-Z]/.test(password)) {
    strength += 1
  } else {
    feedback.push("Satu huruf besar")
  }

  // Lowercase check
  if (/[a-z]/.test(password)) {
    strength += 1
  } else {
    feedback.push("Satu huruf kecil")
  }

  // Number check
  if (/\d/.test(password)) {
    strength += 1
  } else {
    feedback.push("Satu angka")
  }

  // Special character check
  if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
    strength += 1
  } else {
    feedback.push("Satu karakter khusus")
  }

  // Update strength indicator
  const strengthLevels = ["Sangat Lemah", "Lemah", "Sedang", "Kuat", "Sangat Kuat"]
  const strengthColors = ["#e74c3c", "#e67e22", "#f39c12", "#27ae60", "#2ecc71"]

  strengthIndicator.textContent = strengthLevels[strength] || "Sangat Lemah"
  strengthIndicator.style.color = strengthColors[strength] || "#e74c3c"

  // Show feedback
  const feedbackElement = document.querySelector(".password-feedback")
  if (feedbackElement) {
    if (feedback.length > 0) {
      feedbackElement.textContent = "Perlu: " + feedback.join(", ")
      feedbackElement.style.display = "block"
    } else {
      feedbackElement.style.display = "none"
    }
  }
}

// Auto-fill demo accounts with animation
function fillDemoAccount(email, password) {
  const usernameInput = document.getElementById("username")
  const passwordInput = document.getElementById("password")

  if (usernameInput && passwordInput) {
    // Clear existing values
    usernameInput.value = ""
    passwordInput.value = ""

    // Animate typing effect
    typeText(usernameInput, email, 50, () => {
      typeText(passwordInput, password, 30)
    })
  }
}

function typeText(element, text, speed, callback) {
  let i = 0
  element.focus()

  const typeInterval = setInterval(() => {
    if (i < text.length) {
      element.value += text.charAt(i)
      i++
    } else {
      clearInterval(typeInterval)
      if (callback) callback()
    }
  }, speed)
}

// Export functions for global use
window.AuthHelper = {
  validateSTISEmail,
  fillDemoAccount,
  togglePassword: window.togglePassword,
}
