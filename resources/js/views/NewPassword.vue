<template>
  <div class="reset-password-container">
    <div class="reset-password-card">
      <h1 class="title">Reset Password</h1>
      <input 
        type="password" 
        v-model="password" 
        placeholder="New Password"
        class="input"
      />
      <input 
        type="password" 
        v-model="passwordConfirmation" 
        placeholder="Confirm Password"
        class="input"
      />
      <button @click="resetPassword" class="btn">Set Password</button>

      <div v-if="message" :class="messageType" class="message">
        <span>{{ message }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

export default {
  setup() {
    const route = useRoute();
    const router = useRouter();
    const token = route.query.token;
    const email = route.query.email;
    const password = ref('');
    const passwordConfirmation = ref('');
    const message = ref('');
    const messageType = ref('');

    const resetPassword = async () => {
      try {
        const response = await axios.post('/reset-password', {
          email: email,
          token: token,
          password: password.value,
          password_confirmation: passwordConfirmation.value,
        });

        message.value = "Password berhasil direset!";
        messageType.value = "success-message";
        setTimeout(() => {
          router.push('/login');
        }, 5000);
      } catch (error) {
        console.error(error.response.data);
        message.value = "Gagal mereset password, coba lagi.";
        messageType.value = "error-message";
      }
    };

    return {
      password,
      passwordConfirmation,
      resetPassword,
      message,
      messageType,
    };
  },
};
</script>

<style scoped>
.reset-password-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: #f4f4f4;
}

.reset-password-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
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
}

.btn:hover {
    background: #0056b3;
}

.message {
    margin-top: 20px;
    font-size: 14px;
}

.error-message {
    background: #ffdddd;
    color: #d8000c;
    padding: 10px;
    border: 1px solid #d8000c;
    border-radius: 5px;
}

.success-message {
    background: #ddffdd;
    color: #4CAF50;
    padding: 10px;
    border: 1px solid #4CAF50;
    border-radius: 5px;
}
</style>
