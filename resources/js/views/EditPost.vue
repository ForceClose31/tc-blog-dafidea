<template>
    <div class="container">
        <button @click="$router.push('/dashboard')" class="back-btn">Kembali</button>
        <h1 class="title">Edit Post</h1>

        <div v-if="post" class="card">
            <form @submit.prevent="updatePost">
                <input
                    type="text"
                    v-model="post.judul"
                    placeholder="Judul"
                    required
                    class="input"
                />
                <textarea
                    v-model="post.deskripsi"
                    placeholder="Deskripsi"
                    required
                    class="textarea"
                ></textarea>
                <input
                    type="file"
                    @change="handleFileChange"
                    class="file-input"
                />
                <img
                    v-if="post.gambar"
                    :src="'/storage/' + post.gambar"
                    class="post-img"
                />
                <button type="submit" class="btn">Update Post</button>
            </form>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

export default {
    setup() {
        const post = ref({
            judul: "",
            deskripsi: "",
            gambar: null,
        });
        const newImage = ref(null);
        const route = useRoute();
        const router = useRouter();
        const authStore = useAuthStore();

        onMounted(async () => {
            const response = await axios.get(`/posts/${route.params.id}`, {
                headers: { Authorization: `Bearer ${authStore.token}` },
            });
            post.value = response.data;
        });

        const handleFileChange = (event) => {
            newImage.value = event.target.files[0];
        };

        const updatePost = async () => {
            const formData = new FormData();

            formData.append("judul", post.value.judul);
            formData.append("deskripsi", post.value.deskripsi);

            if (newImage.value) {
                formData.append("gambar", newImage.value);
            }

            try {
                await axios.post(`/posts/${post.value.id}`, formData, {
                    headers: {
                        Authorization: `Bearer ${authStore.token}`,
                        "Content-Type": "multipart/form-data",
                    },
                });

                alert("Post berhasil diperbarui");
                router.push("/dashboard");
            } catch (error) {
                alert("Gagal memperbarui post");
            }
        };

        return {
            post,
            updatePost,
            handleFileChange,
        };
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

.title {
    text-align: center;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.input,
.textarea,
.file-input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.btn {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

.post-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    margin-bottom: 10px;
}
</style>
