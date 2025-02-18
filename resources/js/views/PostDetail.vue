<template>
    <div class="container">
        <button @click="$router.push('/')" class="back-btn">Kembali</button>

        <div v-if="post" class="post-detail">
            <h1 class="title">{{ post.judul }}</h1>
            <img
                v-if="post.gambar"
                :src="'/storage/' + post.gambar"
                class="post-img"
            />
            <p class="date">{{ formatDate(post.tanggal_rilis) }}</p>
            <p class="content">{{ post.deskripsi }}</p>

            <h2>Komentar</h2>
            <ul class="comment-list">
                <li
                    v-for="comment in post.comments"
                    :key="comment.id"
                    class="comment"
                >
                    <p class="comment-text">{{ comment.komentar }}</p>
                    <span class="comment-author"
                        >- {{ comment.user.nama }}</span
                    >
                </li>
            </ul>

            <textarea
                v-model="newComment"
                placeholder="Tambahkan komentar..."
                class="comment-box"
            ></textarea>
            <button @click="submitComment" class="comment-btn">
                Tambah Komentar
            </button>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useAuthStore } from "../stores/auth";
import { useRoute } from "vue-router";

export default {
    setup() {
        const post = ref(null);
        const newComment = ref("");
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

            await axios.post(
                `/posts/${post.value.id}/comments`,
                { komentar: newComment.value },
                { headers: { Authorization: `Bearer ${authStore.token}` } }
            );

            newComment.value = "";
            const response = await axios.get(`/posts/${post.value.id}`);
            post.value = response.data;
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
            });
        };

        return { post, newComment, submitComment, formatDate };
    },
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.back-btn {
    background: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 20px;
}

.back-btn:hover {
    background: #0056b3;
}

.post-detail {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.post-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

.title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.date {
    font-size: 14px;
    color: gray;
    margin-bottom: 15px;
}

.content {
    font-size: 16px;
    color: #333;
    margin-bottom: 20px;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}

.comment-list {
    list-style: none;
    padding: 0;
}

.comment {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.comment-text {
    font-size: 14px;
}

.comment-author {
    font-size: 12px;
    color: gray;
}

.comment-box {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
}

.comment-btn {
    background: green;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.comment-btn:hover {
    background: darkgreen;
}
</style>
