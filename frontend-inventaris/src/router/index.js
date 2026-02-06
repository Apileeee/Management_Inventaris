import { createRouter, createWebHistory } from "vue-router";

import Login from "../views/Login.vue";
import ManagementAnggota from "../views/ManagementAnggota.vue";
import DataInventaris from "../views/DataInventaris.vue";

const routes = [
  { path: "/login", component: Login },

  {
    path: "/management-anggota",
    component: ManagementAnggota,
    meta: { auth: true },
  },
  {
    path: "/data-inventaris",
    component: DataInventaris,
    meta: { auth: true },
  },

  { path: "/", redirect: "/management-anggota" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !localStorage.getItem("token")) {
    next("/login");
  } else {
    next();
  }
});

export default router;
