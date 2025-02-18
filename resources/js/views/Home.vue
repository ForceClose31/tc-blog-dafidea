<template>
    <div class="container">
        <header class="header">
            <h1>Daftar Artikel</h1>
            <router-link
                :to="authStore.token ? '/dashboard' : '/login'"
                class="btn"
            >
                {{ authStore.token ? "Dashboard" : "Login" }}
            </router-link>
        </header>

        <div class="grid">
            <div v-for="post in posts" :key="post.id" class="card">
                <router-link :to="'/post/' + post.id" class="card-link">
                    <img
                        v-if="post.gambar"
                        :src="'/storage/' + post.gambar"
                        class="post-img"
                    />
                    <h2 class="title">{{ post.judul }}</h2>
                    <p class="date">{{ formatDate(post.tanggal_rilis) }}</p>
                    <p class="excerpt">{{ post.deskripsi.slice(0, 100) }}...</p>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useAuthStore } from "../stores/auth";

export default {
    setup() {
        const posts = ref([]);
        const authStore = useAuthStore();

        onMounted(async () => {
            try {
                const response = await axios.get("/posts");
                posts.value = response.data;
            } catch (error) {
                console.error("Gagal mengambil data:", error);
            }
        });

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
            });
        };

        return { posts, authStore, formatDate };
    },
};
</script>

<style scoped>
.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover {
    background-color: #0056b3;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
}

.card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.post-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 5px;
}

.title {
    font-size: 20px;
    font-weight: bold;
    margin: 10px 0;
}

.date {
    font-size: 14px;
    color: gray;
    margin-bottom: 10px;
}

.excerpt {
    font-size: 14px;
    color: #333;
}
</style>
