<template>
    <div class="container">
        <h1 class="title">Dashboard Admin</h1>

        <div class="card">
            <h2>Tambah Post</h2>
            <form @submit.prevent="createPost">
                <input
                    type="text"
                    v-model="newPost.judul"
                    placeholder="Judul"
                    required
                    class="input"
                />
                <textarea
                    v-model="newPost.deskripsi"
                    placeholder="Deskripsi"
                    required
                    class="textarea"
                ></textarea>
                <input
                    type="file"
                    @change="handleFileChange"
                    class="file-input"
                />
                <button type="submit" class="btn">Tambah Post</button>
            </form>
        </div>

        <h2>Daftar Post</h2>
        <div class="grid">
            <div v-for="post in posts" :key="post.id" class="post-card">
                <img
                    v-if="post.gambar"
                    :src="'/storage/' + post.gambar"
                    class="post-img"
                />
                <h3>{{ post.judul }}</h3>
                <p>{{ post.deskripsi.slice(0, 50) }}...</p>
                <div class="actions">
                    <button class="edit-btn">
                        <router-link :to="'/posts/' + post.id + '/edit'"
                            >Edit</router-link
                        >
                    </button>

                    <button class="delete-btn" @click="deletePost(post.id)">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <div class="actions">
            <button @click="logout" class="logout-btn">Logout</button>
            <router-link to="/" class="home-btn">Kembali ke Home</router-link>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
    setup() {
        const newPost = ref({
            judul: "",
            deskripsi: "",
            gambar: null,
        });

        const posts = ref([]);
        const authStore = useAuthStore();
        const router = useRouter();
        const hasAccess = ref(false);

        onMounted(async () => {
            try {
                const response = await axios.get("/me", {
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });

                if (response.data.role === "Admin") {
                    hasAccess.value = true;
                    loadPosts();
                } else {
                    hasAccess.value = false;
                }
            } catch (error) {
                hasAccess.value = false;
            }
        });

        const loadPosts = async () => {
            try {
                const response = await axios.get("/posts");
                posts.value = response.data;
            } catch (error) {
                console.error("Gagal mengambil data:", error);
            }
        };

        const logout = () => {
            authStore.logout();
            router.push("/");
        };

        const handleFileChange = (event) => {
            newPost.value.gambar = event.target.files[0];
        };

        const createPost = async () => {
            try {
                const response = await axios.get("/me", {
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });

                if (response.data.role !== "Admin") {
                    alert("403 - Anda tidak memiliki izin!");
                    return;
                }

                const formData = new FormData();
                formData.append("judul", newPost.value.judul);
                formData.append("deskripsi", newPost.value.deskripsi);
                if (newPost.value.gambar) {
                    formData.append("gambar", newPost.value.gambar);
                }

                await axios.post("/posts", formData, {
                    headers: {
                        Authorization: `Bearer ${authStore.token}`,
                        "Content-Type": "multipart/form-data",
                    },
                });

                alert("Post berhasil ditambahkan");
                newPost.value = { judul: "", deskripsi: "", gambar: null };
                loadPosts();
            } catch (error) {
                alert("403 - Akses Ditolak!");
            }
        };

        const deletePost = async (id) => {
            if (!confirm("Yakin ingin menghapus post ini?")) return;
            try {
                await axios.delete(`/posts/${id}`, {
                    headers: { Authorization: `Bearer ${authStore.token}` },
                });
                alert("Post berhasil dihapus");
                loadPosts();
            } catch (error) {
                alert("Gagal menghapus post");
            }
        };

        const editPost = async (post) => {
            const newTitle = prompt("Edit Judul:", post.judul);
            const newDescription = prompt("Edit Deskripsi:", post.deskripsi);
            if (newTitle === null || newDescription === null) return;

            try {
                await axios.put(
                    `/posts/${post.id}`,
                    { judul: newTitle, deskripsi: newDescription },
                    { headers: { Authorization: `Bearer ${authStore.token}` } }
                );
                alert("Post berhasil diperbarui");
                loadPosts();
            } catch (error) {
                alert("Gagal mengedit post");
            }
        };

        return {
            newPost,
            createPost,
            logout,
            handleFileChange,
            hasAccess,
            posts,
            deletePost,
            editPost,
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

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.post-card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.post-card:hover {
    transform: translateY(-5px);
}

.post-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.edit-btn,
.delete-btn,
.logout-btn,
.home-btn {
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.logout-btn {
    background: rgb(252, 0, 0);
    color: white;
}

.home-btn {
    background: #00a015;
    color: white;
}

.edit-btn {
    background: orange;
}

.delete-btn {
    background: red;
    color: white;
}
</style>
