<template>
    <div>
        <h1>Login</h1>
        <input type="email" v-model="email" placeholder="Email">
        <input type="password" v-model="password" placeholder="Password">
        <button @click="login">Login</button>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const email = ref('');
        const password = ref('');
        const authStore = useAuthStore();
        const router = useRouter();

        const login = async () => {
            const success = await authStore.login(email.value, password.value);
            if (success) {
                router.push('/dashboard');
            } else {
                alert("Login gagal");
            }
        };

        return { email, password, login };
    }
}
</script>
