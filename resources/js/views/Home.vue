<template>
    <div>
        <h1>Daftar Artikel</h1>
        <div v-for="post in posts" :key="post.id">
            <router-link :to="'/post/' + post.id">
                <h2>{{ post.judul }}</h2>
                <p>{{ post.tanggal_rilis }}</p>
            </router-link>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const posts = ref([]);

        onMounted(async () => {
            const response = await axios.get('/posts');
            posts.value = response.data;
        });

        return { posts };
    }
}
</script>
