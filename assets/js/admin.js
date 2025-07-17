// H!MANJA - Admin Dashboard JavaScript

document.addEventListener("DOMContentLoaded", () => {
  initAdminDashboard()
})

function initAdminDashboard() {
  initSidebarToggle()
  initResponsiveLayout()
  initNotifications()
  initQuickActions()
  initDataRefresh()
  initKeyboardShortcuts()
}

// Sidebar Toggle for Mobile
function initSidebarToggle() {
  const sidebarToggle = document.querySelector(".sidebar-toggle")
  const sidebar = document.querySelector(".admin-sidebar")
  const main = document.querySelector(".admin-main")

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("active")

      // Close sidebar when clicking outside on mobile
      if (sidebar.classList.contains("active")) {
        document.addEventListener("click", closeSidebarOnOutsideClick)
      }
    })
  }

  function closeSidebarOnOutsideClick(e) {
    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
      sidebar.classList.remove("active")
      document.removeEventListener("click", closeSidebarOnOutsideClick)
    }
  }
}

// Responsive Layout Adjustments
function initResponsiveLayout() {
  const sidebar = document.querySelector(".admin-sidebar")
  const main = document.querySelector(".admin-main")

  function handleResize() {
    if (window.innerWidth > 768) {
      sidebar.classList.remove("active")
    }
  }

  window.addEventListener("resize", handleResize)
  handleResize() // Initial check
}

// Notification System
function initNotifications() {
  // Check for new notifications every 30 seconds
  setInterval(checkNotifications, 30000)

  // Mark notifications as read when clicked
  const notificationLinks = document.querySelectorAll('a[href*="notifications"]')
  notificationLinks.forEach((link) => {
    link.addEventListener("click", () => {
      markNotificationsAsRead()
    })
  })
}

async function checkNotifications() {
  try {
    const response = await fetch("api/notifications.php?action=count")
    const data = await response.json()

    if (data.success) {
      updateNotificationBadges(data.count)
    }
  } catch (error) {
    console.error("Failed to check notifications:", error)
  }
}

function updateNotificationBadges(count) {
  const badges = document.querySelectorAll('.nav-link[href*="notifications"] .nav-badge')

  badges.forEach((badge) => {
    if (count > 0) {
      badge.textContent = count
      badge.style.display = "inline-block"
    } else {
      badge.style.display = "none"
    }
  })
}

async function markNotificationsAsRead() {
  try {
    await fetch("api/notifications.php?action=mark_read", {
      method: "POST",
    })
  } catch (error) {
    console.error("Failed to mark notifications as read:", error)
  }
}

// Quick Actions
function initQuickActions() {
  const quickActionBtns = document.querySelectorAll(".quick-action-btn")

  quickActionBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      // Add loading state
      this.style.opacity = "0.7"
      this.style.pointerEvents = "none"

      // Reset after navigation
      setTimeout(() => {
        this.style.opacity = "1"
        this.style.pointerEvents = "auto"
      }, 1000)
    })
  })
}

// Data Refresh
function initDataRefresh() {
  // Auto-refresh dashboard data every 5 minutes
  setInterval(() => {
    refreshDashboardData()
  }, 300000)

  // Manual refresh button
  const refreshBtn = document.querySelector(".refresh-btn")
  if (refreshBtn) {
    refreshBtn.addEventListener("click", () => {
      refreshDashboardData()
    })
  }
}

async function refreshDashboardData() {
  try {
    const response = await fetch("api/dashboard-data.php")
    const data = await response.json()

    if (data.success) {
      updateDashboardStats(data.stats)
      updateRecentOrders(data.orders)
      updateLowStockAlerts(data.lowStock)
    }
  } catch (error) {
    console.error("Failed to refresh dashboard data:", error)
  }
}

function updateDashboardStats(stats) {
  // Update stat numbers
  Object.keys(stats).forEach((key) => {
    const statElement = document.querySelector(`[data-stat="${key}"] .stat-number`)
    if (statElement) {
      animateNumber(statElement, Number.parseInt(statElement.textContent), stats[key])
    }
  })
}

function animateNumber(element, from, to) {
  const duration = 1000
  const steps = 60
  const stepValue = (to - from) / steps
  let current = from
  let step = 0

  const timer = setInterval(() => {
    current += stepValue
    step++

    element.textContent = Math.round(current)

    if (step >= steps) {
      clearInterval(timer)
      element.textContent = to
    }
  }, duration / steps)
}

// Keyboard Shortcuts
function initKeyboardShortcuts() {
  document.addEventListener("keydown", (e) => {
    // Ctrl/Cmd + D: Dashboard
    if ((e.ctrlKey || e.metaKey) && e.key === "d") {
      e.preventDefault()
      window.location.href = "himanja-dashboard.php"
    }

    // Ctrl/Cmd + P: Products
    if ((e.ctrlKey || e.metaKey) && e.key === "p") {
      e.preventDefault()
      window.location.href = "products.php"
    }

    // Ctrl/Cmd + O: Orders
    if ((e.ctrlKey || e.metaKey) && e.key === "o") {
      e.preventDefault()
      window.location.href = "orders.php"
    }

    // Ctrl/Cmd + S: Stock
    if ((e.ctrlKey || e.metaKey) && e.key === "s") {
      e.preventDefault()
      window.location.href = "stock.php"
    }

    // Escape: Close modals/sidebars
    if (e.key === "Escape") {
      const sidebar = document.querySelector(".admin-sidebar")
      if (sidebar && sidebar.classList.contains("active")) {
        sidebar.classList.remove("active")
      }
    }
  })
}

// Utility Functions
function showToast(message, type = "info") {
  const toast = document.createElement("div")
  toast.className = `toast toast-${type}`
  toast.textContent = message

  toast.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
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
      toast.style.background = "#27ae60"
      break
    case "error":
      toast.style.background = "#e74c3c"
      break
    case "warning":
      toast.style.background = "#f39c12"
      break
    default:
      toast.style.background = "#3498db"
  }

  document.body.appendChild(toast)

  // Auto remove after 5 seconds
  setTimeout(() => {
    toast.style.animation = "slideOutRight 0.3s ease-out"
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast)
      }
    }, 300)
  }, 5000)

  // Click to dismiss
  toast.addEventListener("click", function () {
    this.style.animation = "slideOutRight 0.3s ease-out"
    setTimeout(() => {
      if (this.parentNode) {
        this.parentNode.removeChild(this)
      }
    }, 300)
  })
}

function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount)
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  })
}

function updateRecentOrders(orders) {
  // Implementation for updating recent orders
  console.log("Updating recent orders:", orders)
}

function updateLowStockAlerts(lowStock) {
  // Implementation for updating low stock alerts
  console.log("Updating low stock alerts:", lowStock)
}

// Export functions for global use
window.AdminDashboard = {
  showToast,
  formatCurrency,
  formatDate,
  refreshDashboardData,
  checkNotifications,
}

// Add CSS animations
const style = document.createElement("style")
style.textContent = `
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
  
  .toast {
    cursor: pointer;
    transition: opacity 0.3s ease;
  }
  
  .toast:hover {
    opacity: 0.9;
  }
`
document.head.appendChild(style)
