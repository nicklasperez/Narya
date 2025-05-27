<template>
  <ion-page>
    <ion-content class="tab-bg-nueva fade-in" fullscreen>
      <div class="nueva-container">
        <h2 class="nueva-title">Nueva entrada emocional</h2>

        <ion-select v-model="moodId" label="Mood" placeholder="Selecciona un estado de ánimo">
          <ion-select-option v-for="mood in moods" :key="mood.id" :value="mood.id">
            {{ mood.name }}
          </ion-select-option>
        </ion-select>

        <!-- Componente de búsqueda de canciones -->
        <spotify-search @onSongSelected="handleSongSelected" />

        <!-- Preview -->
        <div v-if="selectedSong.song_name" class="song-preview">
          <img v-if="selectedSong.album_image" :src="selectedSong.album_image" alt="Álbum" />
          <p><strong>{{ selectedSong.song_name }}</strong> – {{ selectedSong.artist_name }}</p>
        </div>

        <ion-button expand="block" @click="crearEntrada">Guardar entrada</ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import {
  IonPage,
  IonContent,
  IonSelect,
  IonSelectOption,
  IonButton
} from '@ionic/vue';
import SpotifySearch from '@/components/SpotifySearch.vue';
import api from '@/utils/api';

const moods = ref<{ id: number; name: string }[]>([]);
const moodId = ref<number | null>(null);

const selectedSong = ref({
  song_name: '',
  artist_name: '',
  album_image: '',
  spotify_track_id: ''
});

onMounted(async () => {
  try {
    const response = await api.get('/moods');
    moods.value = response.data;
  } catch (error) {
    console.error('Error al cargar moods:', error);
  }
});

function handleSongSelected(song: any) {
  selectedSong.value = song;
}

async function crearEntrada() {
  const token = localStorage.getItem('token');

  if (!token) {
    alert('No hay sesión activa. Vuelve a iniciar sesión.');
    return;
  }

  try {
    await api.post(
      '/entries',
      {
        mood_id: moodId.value,
        song_name: selectedSong.value.song_name,
        artist_name: selectedSong.value.artist_name,
        album_image: selectedSong.value.album_image,
        spotify_track_id: selectedSong.value.spotify_track_id
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    );

    alert('Entrada creada con éxito');
    moodId.value = null;
    selectedSong.value = {
      song_name: '',
      artist_name: '',
      album_image: '',
      spotify_track_id: ''
    };
  } catch (error) {
    console.error('Error al crear entrada:', error);
    alert('Hubo un problema al crear la entrada');
  }
}
</script>

<style scoped>
.nueva-container {
  padding: 40px 24px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.nueva-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: #ff9ebf;
  text-shadow: 0 0 6px #ff9ebf;
  text-align: center;
}

.song-preview {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 12px;
  box-shadow: 0 0 8px #ff8fbc;
  text-align: center;
  color: #fff;
}

.song-preview img {
  max-width: 100%;
  max-height: 100px;
  border-radius: 8px;
  margin-bottom: 10px;
}
</style>
