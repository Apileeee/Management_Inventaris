<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();
const isDarkMode = ref(false);
const userName = ref("");
const userEmail = ref("");

onMounted(() => {
  const savedTheme = localStorage.getItem("theme") || "light";
  isDarkMode.value = savedTheme === "dark";
  applyTheme();

  // Load user data from localStorage
  const userData = localStorage.getItem("user");
  if (userData) {
    const user = JSON.parse(userData);
    userName.value = user.name || "";
    userEmail.value = user.email || "";
  }
});

const applyTheme = () => {
  const htmlElement = document.documentElement;
  if (isDarkMode.value) {
    htmlElement.setAttribute("data-bs-theme", "dark");
    document.body.style.backgroundColor = "#1a1a1a";
  } else {
    htmlElement.setAttribute("data-bs-theme", "light");
    document.body.style.backgroundColor = "#f8f9fa";
  }
  localStorage.setItem("theme", isDarkMode.value ? "dark" : "light");
};

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  const htmlElement = document.documentElement;
  if (isDarkMode.value) {
    htmlElement.setAttribute("data-bs-theme", "dark");
    document.body.style.backgroundColor = "#1a1a1a";
    localStorage.setItem("theme", "dark");
  } else {
    htmlElement.setAttribute("data-bs-theme", "light");
    document.body.style.backgroundColor = "#f8f9fa";
    localStorage.setItem("theme", "light");
  }
};

const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  router.push("/login");
};

const isActive = (path) => {
  return route.path === path;
};
</script>

<template>
  <div v-if="route.path !== '/login'" class="d-flex" style="height: 100vh">
    <!-- Sidebar -->
    <div class="sidebar border-end" :class="{ 'sidebar-dark': isDarkMode }">
      <div class="p-3 text-center border-bottom">
        <div class="d-flex align-items-center justify-content-center gap-2">
          <img src="/icon.png" alt="logo" style="width: 32px; height: 32px" />
          <h5 class="mb-0">managemen<br />inventaris</h5>
        </div>
      </div>

      <nav class="nav flex-column p-3">
        <router-link
          to="/data-inventaris"
          class="nav-link"
          :class="{ active: isActive('/data-inventaris') }"
        >
          <i class="bi bi-box-seam me-2"></i>
          Data Inventaris
        </router-link>

        <router-link
          to="/management-anggota"
          class="nav-link"
          :class="{ active: isActive('/management-anggota') }"
        >
          <i class="bi bi-people me-2"></i>
          Management Anggota
        </router-link>
      </nav>

      <div class="p-3 border-top mt-auto">
        <!-- Profile Card -->
        <div class="profile-card mb-3">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="profile-avatar">
              <i class="bi bi-person-circle"></i>
            </div>
            <div>
              <div class="profile-name">{{ userName }}</div>
              <div class="profile-email">{{ userEmail }}</div>
            </div>
          </div>

          <!-- Dark Mode Toggle -->
          <!-- Dark Mode Toggle -->
          <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <i :class="isDarkMode ? 'bi bi-moon' : 'bi bi-sun-fill'"></i>
                <span class="theme-label">Dark Theme</span>
              </div>
              <label class="theme-toggle-label">
                <input
                  type="checkbox"
                  :checked="isDarkMode"
                  @change="toggleTheme"
                  class="theme-toggle-input"
                />
                <span class="toggle-slider"></span>
              </label>
            </div>
          </div>

          <!-- Logout Button -->
          <button @click="logout" class="logout-btn-simple">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-box-arrow-right"></i>
              <span class="theme-label">Logout</span>
            </div>
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div
      class="flex-grow-1 overflow-auto main-content"
      :class="{ 'main-dark': isDarkMode }"
    >
      <router-view />
    </div>
  </div>

  <!-- Login Page (Full Width) -->
  <router-view v-else />
</template>

<style scoped>
* {
  font-family: "Manrope", sans-serif;
  font-weight: 600;
  font-size: 16px;
}

.sidebar {
  width: 250px;
  height: 100vh;
  overflow-y: auto;
  background: white;
  color: #333;
  display: flex;
  flex-direction: column;
}

.sidebar-dark {
  background: #1e1e1e;
  color: #f0f0f0;
}

.sidebar.sidebar-dark .nav-link {
  color: #e0e0e0;
}
.sidebar.sidebar-dark .nav-link:hover {
  background-color: #333;
}
.sidebar.sidebar-dark .nav-link.active {
  background-color: #444;
  color: #fff;
}
.sidebar.sidebar-dark .border-bottom,
.sidebar.sidebar-dark .border-top {
  border-color: #333 !important;
}
.sidebar.sidebar-dark h5 {
  color: #f0f0f0;
}

.nav-link {
  color: #333;
  text-decoration: none;
  padding: 0.7rem 1rem;
  border-radius: 0.5rem;
  transition: all 0.3s;
}

.nav-link:hover {
  background-color: #000;
  color: white;
}
.nav-link.active {
  background-color: #e9ecef;
  color: #000;
  font-weight: 500;
}

.profile-card {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.sidebar-dark .profile-card {
  background-color: #2a2a2a;
}

.profile-avatar {
  width: 50px;
  height: 50px;
  background-color: #f0f0f0;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #666;
}

.sidebar-dark .profile-avatar {
  background-color: #3a3a3a;
  color: #ccc;
}

.profile-name {
  font-weight: 600;
  font-size: 0.95rem;
  color: #1a1a1a;
}

.sidebar-dark .profile-name {
  color: #fff;
}

.profile-email {
  font-size: 0.8rem;
  color: #999;
}

.sidebar-dark .profile-email {
  color: #bbb;
}

.theme-btn {
  background-color: #f0f0f0;
  border: none;
  color: #1a1a1a;
  border-radius: 0.5rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s;
}

.theme-btn:hover {
  background-color: #e0e0e0;
  color: #1a1a1a;
}

.sidebar-dark .theme-btn {
  background-color: #3a3a3a;
  color: #fff;
}

.sidebar-dark .theme-btn:hover {
  background-color: #444;
  color: #fff;
}

.main-content {
  background-color: #f8f9fa;
}
.main-dark {
  background-color: #1a1a1a;
}
.main-dark {
  color: #f0f0f0;
}

h5 {
  font-size: 1.1rem;
  font-weight: bold;
}

/* Dark Mode Toggle Switch */
.theme-toggle-label {
  position: relative;
  display: inline-flex;
  align-items: center;
  cursor: pointer;
}

.theme-toggle-input {
  display: none;
}

.toggle-slider {
  width: 40px;
  height: 20px;
  background-color: #ccc;
  border-radius: 10px;
  position: relative;
  transition: background-color 0.3s;
  display: block;
}

.sidebar-dark .toggle-slider {
  background-color: #666;
}

.toggle-slider::after {
  content: "";
  position: absolute;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background-color: white;
  top: 2px;
  left: 2px;
  transition: left 0.3s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.theme-toggle-input:checked + .toggle-slider {
  background-color: #1a1a1a;
}

.theme-toggle-input:checked + .toggle-slider::after {
  left: 20px;
}

.sidebar-dark .theme-toggle-input:checked + .toggle-slider {
  background-color: #555;
}

.theme-label {
  font-size: 0.9rem;
  font-weight: 500;
  color: #1a1a1a;
}

.sidebar-dark .theme-label {
  color: #e0e0e0;
}

.sidebar-dark i {
  color: #e0e0e0;
}

.logout-btn-simple {
  background: none;
  border: none;
  padding: 0.5rem 0;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 500;
  width: 100%;
  text-align: left;
  color: #333;
  transition: all 0.3s;
}

.logout-btn-simple:hover {
  color: #000;
  opacity: 0.8;
}

.logout-btn-simple:hover .theme-label {
  color: #000;
}

.sidebar-dark .logout-btn-simple {
  color: #e0e0e0;
}

.sidebar-dark .logout-btn-simple:hover {
  color: #fff;
  opacity: 0.9;
}

.sidebar-dark .logout-btn-simple:hover .theme-label {
  color: #fff;
}
</style>
