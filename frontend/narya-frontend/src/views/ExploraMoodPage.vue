<template>
    <ion-page>
        <ion-content class="tab-bg-explora fade-in" fullscreen>
            <div class="explora-mood-container">
                <!-- Botón de volver -->
                <ion-button @click="goBack" class="back-button" fill="clear">
                    ← Volver
                </ion-button>

                <h2 class="explora-title">🎵 Mood: {{ moodName }}</h2>

                <div v-if="entries.length === 0" class="empty-text">
                    Aún no tienes canciones en este mood. ¡Empieza a compartir!
                </div>

                <div v-for="entry in entries" :key="entry.id" class="entry-card neon-border"
                    :style="{ '--mood-color': moodColor }">
                    <img :src="entry.album_image" alt="Album cover" class="album-image" />
                    <div class="entry-info">
                        <p class="song-name">{{ entry.song_name }}</p>
                        <p class="artist-name">{{ entry.artist_name }}</p>
                    </div>
                </div>
            </div>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonButton } from '@ionic/vue';
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const moodId = parseInt(route.params.id as string, 10);
const entries = ref<any[]>([]);
const moodName = ref('');
const moodColor = ref('#ffffff'); // Por defecto blanco, luego se actualizará

onMounted(async () => {
    try {
        // 1️⃣ Obtener info del mood (nombre y color)
        const moodResponse = await api.get('/moods');
        const mood = moodResponse.data.find((m: any) => m.id === moodId);

        if (mood) {
            moodName.value = mood.name;
            moodColor.value = mood.color;
        }

        // 2️⃣ Obtener las canciones en este mood del usuario autenticado
        const userResponse = await api.get('/user');
        const userId = userResponse.data.id;

        const entriesResponse = await api.get('/entries', {
            params: {
                mood_id: moodId
            }
        });

        console.log('Entries recibidas:', entriesResponse.data.entries);
        entries.value = entriesResponse.data.entries;
    } catch (err) {
        console.error('Error cargando entries del mood:', err);
    }
});

function goBack() {
  router.back();
}
</script>

<style scoped>
.explora-mood-container {
    display: flex;
    flex-direction: column;
    padding: 24px;
}

.explora-title {
    font-family: 'Quicksand', sans-serif;
    font-size: 2rem;
    color: #4ef7a1;
    text-align: center;
    margin-bottom: 24px;
    text-shadow: 0 0 8px #4ef7a1;
}

.empty-text {
    font-family: 'Quicksand', sans-serif;
    color: white;
    text-align: center;
    margin-bottom: 16px;
}

.entry-card {
    display: flex;
    align-items: center;
    border: 2px solid var(--mood-color);
    border-radius: 12px;
    padding: 12px;
    margin-bottom: 12px;
    background-color: rgba(0, 0, 0, 0.3);
    box-shadow: 0 0 8px var(--mood-color);
}

.album-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 12px;
}

.entry-info {
    display: flex;
    flex-direction: column;
}

.song-name {
    font-family: 'Quicksand', sans-serif;
    font-size: 1.1rem;
    color: white;
    text-shadow: 0 0 6px white;
}

.artist-name {
    font-family: 'Quicksand', sans-serif;
    font-size: 0.9rem;
    color: #ccc;
}
</style>
