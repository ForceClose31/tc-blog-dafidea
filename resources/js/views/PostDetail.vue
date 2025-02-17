<template>
    <div v-if="post">
        <h1>{{ post.judul }}</h1>
        <img :src="'/storage/' + post.pict" v-if="post.pict">
        <p>{{ post.deskripsi }}</p>
        <h2>Komentar</h2>
        <ul>
            <li v-for="comment in post.comments" :key="comment.id">
                <p>{{ comment.komentar }} - {{ comment.user.nama }}</p>
            </li>
        </ul>
        <textarea v-model="newComment"></textarea>
        <button @click="submitComment">Tambah Komentar</button>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

export default {
    setup() {
        const post = ref(null);
        const newComment = ref('');
        const authStore = useAuthStore();
        const route = useRoute();

        onMounted(async () => {
            const response = await axios.get(`/posts/${route.params.id}`);
            post.value = response.data;
        });

        const submitComment = async () => {
            if (!authStore.token) {
                alert("Silakan login terlebih dahulu");
                return;
            }

            await axios.post(`/posts/${post.value.id}/comments`, {
                komentar: newComment.value
            }, {
                headers: { Authorization: `Bearer ${authStore.token}` }
            });

            newComment.value = '';
            const response = await axios.get(`/posts/${post.value.id}`);
            post.value = response.data;
        };

        return { post, newComment, submitComment };
    }
}
</script>
