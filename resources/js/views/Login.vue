<template>
    <div class="login-container">
        <div class="login-card">
            <h1 class="title">Login</h1>
            <input type="email" v-model="email" placeholder="Email" class="input">
            <input type="password" v-model="password" placeholder="Password" class="input">
            <button @click="login" class="btn">Login</button>
        </div>

        <div v-if="message" class="error-container">
            <div :class="messageType">
                <span>{{ message }}</span>
            </div>
            <div class="progress-bar" :style="{ width: progressWidth + '%' }"></div>
        </div>
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
        const message = ref('');
        const messageType = ref('');
        const countdown = ref(5);
        const progressWidth = ref(100);
        let timer = null;
        
        const authStore = useAuthStore();
        const router = useRouter();

        const login = async () => {
            const success = await authStore.login(email.value, password.value);
            
            if (success) {
                message.value = "Login berhasil!";
                messageType.value = "success-message";
                countdown.value = 5;
                progressWidth.value = 100;
                redirectUser();
            } else {
                message.value = "Email atau password Anda salah.";
                messageType.value = "error-message";
                countdown.value = 5;
                progressWidth.value = 100;
                startTimer();
            }
        };

        const startTimer = () => {
            if (timer) clearInterval(timer);
            timer = setInterval(() => {
                if (countdown.value > 0) {
                    countdown.value -= 1;
                    progressWidth.value -= 20;
                } else {
                    message.value = "";
                    clearInterval(timer);
                }
            }, 1000);
        };

        const redirectUser = () => {
            if (timer) clearInterval(timer);
            timer = setInterval(() => {
                if (countdown.value > 0) {
                    countdown.value -= 1;
                    progressWidth.value -= 20;
                } else {
                    router.push('/dashboard');
                    clearInterval(timer);
                }
            }, 1000);
        };

        return { email, password, login, message, messageType, countdown, progressWidth };
    }
}
</script>

<style scoped>
.login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: #f4f4f4;
    position: relative;
}

.login-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
    z-index: 2;
}

.title {
    font-size: 24px;
    margin-bottom: 20px;
}

.input {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.btn {
    background: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    margin-top: 10px;
}

.btn:hover {
    background: #0056b3;
}

.error-container {
    position: absolute;
    bottom: 50px;
    width: 350px;
    text-align: center;
    z-index: 1;
}

.error-message {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffdddd;
    color: #d8000c;
    border: 1px solid #d8000c;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
}

.success-message {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ddffdd;
    color: #4CAF50;
    border: 1px solid #4CAF50;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
}

.progress-bar {
    height: 3px;
    transition: width 1s linear;
}

.error-message .progress-bar {
    background: #d8000c;
}

.success-message .progress-bar {
    background: #4CAF50; 
}

</style>
