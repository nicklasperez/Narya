<template>
  <div class="spotify-search">
    <ion-input
      v-model="query"
      placeholder="Buscar canción o artista"
      @ionInput="debouncedInput"
      class="custom-input"
    ></ion-input>

    <div v-if="results.length > 0" class="search-results">
      <div
        class="search-item"
        v-for="track in results"
        :key="track.id"
        @click="selectSong(track)"
      >
        <img :src="track.album.images[0]?.url" alt="cover" />
        <div class="info">
          <strong>{{ track.name }}</strong>
          <small>{{ track.artists[0].name }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import api from '@/utils/api';
import { IonInput, IonContent } from '@ionic/vue';


const emit = defineEmits(['onSongSelected']);
const query = ref('');
const results = ref<any[]>([]);
let typingTimeout: any = null;

function debouncedInput() {
  clearTimeout(typingTimeout);
  typingTimeout = setTimeout(() => {
    handleInput();
  }, 400);
}

async function handleInput() {
  if (query.value.length < 3) {
    results.value = [];
    return;
  }

  try {
    const res = await api.get(`/spotify/search?q=${encodeURIComponent(query.value)}`);
    results.value = res.data.tracks.items;
  } catch (err) {
    console.error('Error al buscar en Spotify:', err);
  }
}

function selectSong(track: any) {
  const song = {
    song_name: track.name,
    artist_name: track.artists[0]?.name,
    album_image: track.album.images[0]?.url,
    spotify_track_id: track.id
  };
  emit('onSongSelected', song);
  results.value = [];
  query.value = '';
}
</script>

<style scoped>
.spotify-search {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.search-results {
  margin-top: 8px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  background-color: #4D2A37;
  padding: 12px;
  border-radius: 12px;
  box-shadow: 0 0 10px #9a4d6e80 inset;
}

.search-item {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #5c3242;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 0 6px #cb477e55;
}

.search-item:hover {
  transform: scale(1.02);
  box-shadow: 0 0 12px #cb477e;
}

.search-item img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
}

.search-item .info {
  color: #f1d9de;
}
</style>
