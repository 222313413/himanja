// H!MANJA - Main JavaScript File

// DOM Content Loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeApp()
})

// Initialize Application
function initializeApp() {
  initMobileMenu()
  initScrollAnimations()
  initCounterAnimation()
  initSmoothScrolling()
  initTooltips()
  initParallaxEffect()
  initHeaderScroll()
  initMapInteraction()
  initFormValidation()
  initLoadingStates()
}

// Mobile Menu Toggle
function initMobileMenu() {
  const hamburger = document.querySelector(".hamburger")
  const navMenu = document.querySelector(".nav-menu")
  const navLinks = document.querySelectorAll(".nav-link")

  if (hamburger && navMenu) {
    hamburger.addEventListener("click", () => {
      hamburger.classList.toggle("active")
      navMenu.classList.toggle("active")
      document.body.classList.toggle("menu-open")
    })

    // Close menu when clicking on nav links
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        hamburger.classList.remove("active")
        navMenu.classList.remove("active")
        document.body.classList.remove("menu-open")
      })
    })

    // Close menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
        hamburger.classList.remove("active")
        navMenu.classList.remove("active")
        document.body.classList.remove("menu-open")
      }
    })
  }
}

// Scroll Animations with Intersection Observer
function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible")

        // Trigger stagger animation for children
        if (entry.target.classList.contains("stagger-animation")) {
          const children = entry.target.children
          Array.from(children).forEach((child, index) => {
            setTimeout(() => {
              child.style.animationDelay = `${index * 0.1}s`
              child.classList.add("animate-slide-up")
            }, index * 100)
          })
        }
      }
    })
  }, observerOptions)

  // Observe elements with animation classes
  const animatedElements = document.querySelectorAll(
    ".fade-in-on-scroll, .slide-in-left-on-scroll, .slide-in-right-on-scroll, .stagger-animation",
  )

  animatedElements.forEach((el) => observer.observe(el))
}

// Counter Animation
function initCounterAnimation() {
  const counters = document.querySelectorAll(".stat-number")

  const counterObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const counter = entry.target
          const target = Number.parseInt(counter.getAttribute("data-target"))
          const duration = 2000 // 2 seconds
          const increment = target / (duration / 16) // 60fps
          let current = 0

          const updateCounter = () => {
            current += increment
            if (current < target) {
              counter.textContent = Math.floor(current)
              requestAnimationFrame(updateCounter)
            } else {
              counter.textContent = target
            }
          }

          updateCounter()
          counterObserver.unobserve(counter)
        }
      })
    },
    { threshold: 0.5 },
  )

  counters.forEach((counter) => counterObserver.observe(counter))
}

// Smooth Scrolling
function initSmoothScrolling() {
  const links = document.querySelectorAll('a[href^="#"]')

  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()

      const targetId = this.getAttribute("href")
      const targetElement = document.querySelector(targetId)

      if (targetElement) {
        const headerHeight = document.querySelector(".header").offsetHeight
        const targetPosition = targetElement.offsetTop - headerHeight

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        })
      }
    })
  })
}

// Tooltips for Map Dots
function initTooltips() {
  const mapDots = document.querySelectorAll(".map-dot")

  mapDots.forEach((dot) => {
    const himadaName = dot.getAttribute("data-himada")

    // Create tooltip
    const tooltip = document.createElement("div")
    tooltip.className = "tooltip"
    tooltip.textContent = himadaName
    tooltip.style.cssText = `
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1000;
            white-space: nowrap;
        `

    document.body.appendChild(tooltip)

    dot.addEventListener("mouseenter", (e) => {
      tooltip.style.opacity = "1"
    })

    dot.addEventListener("mouseleave", () => {
      tooltip.style.opacity = "0"
    })

    dot.addEventListener("mousemove", (e) => {
      tooltip.style.left = e.pageX + 10 + "px"
      tooltip.style.top = e.pageY - 30 + "px"
    })
  })
}

// Parallax Effect
function initParallaxEffect() {
  const parallaxElements = document.querySelectorAll(".parallax")

  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset

    parallaxElements.forEach((element) => {
      const rate = scrolled * -0.5
      element.style.transform = `translateY(${rate}px)`
    })
  })
}

// Header Scroll Effect
function initHeaderScroll() {
  const header = document.querySelector(".header")
  let lastScrollTop = 0

  window.addEventListener("scroll", () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop

    if (scrollTop > 100) {
      header.classList.add("scrolled")
    } else {
      header.classList.remove("scrolled")
    }

    // Hide/show header on scroll
    if (scrollTop > lastScrollTop && scrollTop > 200) {
      header.style.transform = "translateY(-100%)"
    } else {
      header.style.transform = "translateY(0)"
    }

    lastScrollTop = scrollTop
  })
}

// Map Interaction
function initMapInteraction() {
  const mapDots = document.querySelectorAll(".map-dot")

  mapDots.forEach((dot) => {
    dot.addEventListener("click", function () {
      const himadaName = this.getAttribute("data-himada")

      // Add ripple effect
      const ripple = document.createElement("span")
      ripple.className = "ripple"
      ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `

      this.appendChild(ripple)

      setTimeout(() => {
        ripple.remove()
      }, 600)

      // Show info (you can customize this)
      showNotification(`Clicked on ${himadaName}!`)
    })
  })
}

// Form Validation
function initFormValidation() {
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const inputs = form.querySelectorAll("input[required], select[required], textarea[required]")
      let isValid = true

      inputs.forEach((input) => {
        if (!input.value.trim()) {
          isValid = false
          input.classList.add("error")
          showFieldError(input, "Field ini wajib diisi")
        } else {
          input.classList.remove("error")
          hideFieldError(input)
        }

        // Email validation
        if (input.type === "email" && input.value) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
          if (!emailRegex.test(input.value)) {
            isValid = false
            input.classList.add("error")
            showFieldError(input, "Format email tidak valid")
          }
        }
      })

      if (!isValid) {
        e.preventDefault()
        showNotification("Mohon lengkapi semua field yang wajib diisi", "error")
      }
    })

    // Real-time validation
    const inputs = form.querySelectorAll("input, select, textarea")
    inputs.forEach((input) => {
      input.addEventListener("blur", function () {
        validateField(this)
      })

      input.addEventListener("input", function () {
        if (this.classList.contains("error")) {
          validateField(this)
        }
      })
    })
  })
}

// Field Validation
function validateField(field) {
  const value = field.value.trim()
  let isValid = true
  let errorMessage = ""

  if (field.hasAttribute("required") && !value) {
    isValid = false
    errorMessage = "Field ini wajib diisi"
  } else if (field.type === "email" && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(value)) {
      isValid = false
      errorMessage = "Format email tidak valid"
    }
  } else if (field.type === "password" && value && value.length < 6) {
    isValid = false
    errorMessage = "Password minimal 6 karakter"
  }

  if (isValid) {
    field.classList.remove("error")
    hideFieldError(field)
  } else {
    field.classList.add("error")
    showFieldError(field, errorMessage)
  }

  return isValid
}

// Show Field Error
function showFieldError(field, message) {
  hideFieldError(field) // Remove existing error

  const errorDiv = document.createElement("div")
  errorDiv.className = "field-error"
  errorDiv.textContent = message
  errorDiv.style.cssText = `
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
        animation: slideInUp 0.3s ease-out;
    `

  field.parentNode.appendChild(errorDiv)
}

// Hide Field Error
function hideFieldError(field) {
  const existingError = field.parentNode.querySelector(".field-error")
  if (existingError) {
    existingError.remove()
  }
}

// Loading States
function initLoadingStates() {
  const buttons = document.querySelectorAll('button[type="submit"], .btn-submit')

  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      if (this.form && this.form.checkValidity()) {
        showButtonLoading(this)
      }
    })
  })
}

// Show Button Loading
function showButtonLoading(button) {
  const originalText = button.textContent
  button.disabled = true
  button.innerHTML = '<span class="loading-spinner"></span> Memproses...'

  // Reset after 3 seconds (adjust based on your needs)
  setTimeout(() => {
    button.disabled = false
    button.textContent = originalText
  }, 3000)
}

// Notification System
function showNotification(message, type = "info") {
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.textContent = message
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
        max-width: 300px;
        word-wrap: break-word;
    `

  // Set background color based on type
  switch (type) {
    case "success":
      notification.style.background = "#27ae60"
      break
    case "error":
      notification.style.background = "#e74c3c"
      break
    case "warning":
      notification.style.background = "#f39c12"
      break
    default:
      notification.style.background = "#3498db"
  }

  document.body.appendChild(notification)

  // Auto remove after 5 seconds
  setTimeout(() => {
    notification.style.animation = "slideOutRight 0.3s ease-out"
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification)
      }
    }, 300)
  }, 5000)

  // Click to dismiss
  notification.addEventListener("click", function () {
    this.style.animation = "slideOutRight 0.3s ease-out"
    setTimeout(() => {
      if (this.parentNode) {
        this.parentNode.removeChild(this)
      }
    }, 300)
  })
}

// Utility Functions
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

function throttle(func, limit) {
  let inThrottle
  return function () {
    const args = arguments
    
    if (!inThrottle) {
      func.apply(this, args)
      inThrottle = true
      setTimeout(() => (inThrottle = false), limit)
    }
  }
}

// Add CSS for animations
const style = document.createElement("style")
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
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
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .header.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }
    
    .header {
        transition: all 0.3s ease;
    }
    
    input.error, select.error, textarea.error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2) !important;
    }
    
    .notification {
        cursor: pointer;
    }
    
    .notification:hover {
        opacity: 0.9;
    }
`
document.head.appendChild(style)

// Export functions for use in other files
window.HimanjaApp = {
  showNotification,
  showButtonLoading,
  validateField,
  debounce,
  throttle,
}
