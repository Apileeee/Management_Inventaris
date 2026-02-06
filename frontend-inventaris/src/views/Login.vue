<script setup>
import { ref } from "vue";
import api from "../services/api";
import { useRouter } from "vue-router";

const email = ref("");
const password = ref("");
const error = ref("");
const loading = ref(false);
const router = useRouter();

const login = async () => {
  if (!email.value || !password.value) {
    error.value = "Email dan password tidak boleh kosong";
    return;
  }

  loading.value = true;
  try {
    const res = await api.post("/login", {
      email: email.value,
      password: password.value,
    });

    localStorage.setItem("token", res.data.token);
    localStorage.setItem("user", JSON.stringify(res.data.user));
    router.push("/management-anggota");
  } catch (err) {
    error.value = "Email atau password salah";
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div
    class="login-bg min-vh-100 d-flex align-items-center justify-content-center"
  >
    <div class="login-card p-4 rounded-4 shadow-lg">
      <!-- Header -->
      <div class="text-center mb-4">
        <i
          class="bi bi-box-seam d-block mb-3"
          style="font-size: 2.5rem; color: #000"
        ></i>
        <h2 class="fw-bold mb-0">Management Inventaris</h2>
      </div>

      <!-- Error -->
      <div v-if="error" class="alert alert-danger alert-sm mb-3">
        {{ error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="login">
        <div class="mb-3">
          <input
            v-model="email"
            type="email"
            class="form-control"
            placeholder="Email"
            :disabled="loading"
          />
        </div>

        <div class="mb-3">
          <input
            v-model="password"
            type="password"
            class="form-control"
            placeholder="Password"
            :disabled="loading"
          />
        </div>

        <button
          type="submit"
          class="btn btn-login w-100 fw-semibold"
          :disabled="loading"
        >
          {{ loading ? "Loading..." : "Masuk" }}
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
* {
  font-family: "Manrope", sans-serif;
  font-weight: 600;
  font-size: 16px;
}

.login-bg {
  background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 50%, #f5f5f5 100%);
}
.login-card {
  background: white;
  width: 100%;
  max-width: 360px;
}
.form-control {
  border: 1px solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 0.7rem 1rem;
  font-size: 0.95rem;
}
.form-control:focus {
  border-color: #000;
  box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
}
.btn-login {
  background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
  color: white;
  border: none;
  padding: 0.7rem;
  border-radius: 0.5rem;
}
.btn-login:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}
h2 {
  color: #333;
  font-size: 1.4rem;
}
</style>
