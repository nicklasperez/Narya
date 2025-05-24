<template>
  <ion-page>
    <ion-content class="tab-bg-home" fullscreen>
      <div class="home-container fade-in">
        <h2 class="home-title">Tu feed emocional</h2>

        <div class="feed-list">
          <div v-for="entry in mockFeed" :key="entry.id" class="feed-item neon-border-purple">
            <p class="feed-user">{{ entry.user }}</p>
            <p class="feed-song">🎵 {{ entry.song }}</p>
            <p class="feed-mood">Estado: <span class="mood-text">{{ entry.mood }}</span></p>
          </div>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const mockFeed = ref([
  { id: 1, user: 'Nicklas', song: 'Nothing Else Matters', mood: 'Deprimido' },
  { id: 2, user: 'Lucía', song: 'Bailando', mood: 'Alegre' },
  { id: 3, user: 'Carlos', song: 'Sad but True', mood: 'Melancólico' }
]);
</script>

<style scoped>


.home-container {
  padding: 24px;
  padding-top: 0;
  display: flex;
  flex-direction: column;
  gap: 24px;
  min-height: 100%; /* importante para fondo animado */
}

.fade-in {
  animation: fadeInSmooth 0.5s ease;
}



.home-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 1.8rem;
  color: #b88cff;
  text-shadow: 0 0 6px #b88cff;
  text-align: center;
  margin-top: 16px;
}

.feed-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.feed-item {
  background-color: rgba(255, 255, 255, 0.04);
  padding: 16px;
  border-radius: 16px;
  font-family: 'Quicksand', sans-serif;
  color: white;
}

.feed-user {
  font-weight: bold;
  font-size: 1.1rem;
}

.feed-song {
  font-style: italic;
  margin: 4px 0;
}

.feed-mood .mood-text {
  font-weight: bold;
  color: #ff92e0;
}
</style>
