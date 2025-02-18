import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import PostDetail from "../views/PostDetail.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import EditPost from "../views/EditPost.vue";
import ResetPassword from "../views/ResetPassword.vue";
import NewResetPassword from "../views/NewPassword.vue";
import { useAuthStore } from "../stores/auth";
import axios from "axios";

const routes = [
    { path: "/", component: Home },
    { path: "/post/:id", component: PostDetail },
    { path: "/login", component: Login },
    {
        path: "/dashboard",
        component: Dashboard,
        meta: { requiresAuth: true, requiresRole: "Admin" },
    },
    {
        path: "/posts/:id/edit",
        name: "EditPost",
        component: EditPost,
    },
    {
        path: "/password/reset",
        name: "ResetPassword",
        component: ResetPassword,
    },
    {
        path: "/reset-password",
        name: "NewResetPassword",
        component: NewResetPassword,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    if (to.meta.requiresAuth) {
        if (!authStore.token) {
            return next("/login");
        }

        try {
            const response = await axios.get("/me", {
                headers: { Authorization: `Bearer ${authStore.token}` },
            });

            console.log("User Data:", response.data);

            if (
                to.meta.requiresRole &&
                response.data.role !== to.meta.requiresRole
            ) {
                alert("Akses Ditolak: Anda tidak memiliki izin!");
                return next("/"); 
            }

            next();
        } catch (error) {
            alert("Akses Ditolak: Harap login kembali.");
            authStore.logout();
            return next("/login");
        }
    } else {
        next();
    }
});

export default router;
