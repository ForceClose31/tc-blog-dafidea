<template>
    <div>
        <h1>Dashboard Admin</h1>
        <button @click="logout">Logout</button>
        <router-link to="/">Kembali ke Home</router-link>
    </div>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();

        const logout = () => {
            authStore.logout();
            router.push('/');
        };

        return { logout };
    }
}
</script>
