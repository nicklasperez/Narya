<template>
  <ion-page>
    <ion-content class="tab-bg-home" fullscreen>
      <div class="home-container fade-in">
        <h2 class="home-title">Tu feed emocional</h2>

        <div class="feed-list">
          <div v-for="entry in feed" :key="entry.id" class="feed-item">
            <div class="entry-left">
              <div class="entry-header">
                <img :src="entry.user.profile_picture || 'assets/default-avatar.png'" alt="Avatar" class="avatar" />
                <div>
                  <p class="feed-user">{{ entry.user.username }}</p>
                  <p class="feed-date">{{ formatDate(entry.created_at) }}</p>
                </div>
              </div>
              <div class="entry-body">
                <p>🎵 <strong>{{ entry.song_name }}</strong></p>
                <p>🧑‍🎤 {{ entry.artist_name }}</p>
                <p>Mood: <span class="mood-text">{{ entry.mood.name }}</span></p>
              </div>
            </div>

            <div class="entry-right" v-if="entry.album_image">
              <img :src="entry.album_image" alt="album cover" class="album-cover" />
            </div>
          </div>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { IonPage, IonContent } from '@ionic/vue';
import api from '@/utils/api';

interface Entry {
  id: number;
  song_name: string;
  artist_name?: string;
  mood: {
    name: string;
  };
  user: {
    username: string;
    profile_picture?: string;
  };
  album_image?: string;
  created_at: string;
}

const feed = ref<Entry[]>([]);

function formatDate(dateStr: string) {
  const date = new Date(dateStr);
  return date.toLocaleString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  });
}

onMounted(async () => {
  const token = localStorage.getItem('token');

  try {
    const response = await api.get('/feed', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    feed.value = response.data.feed;
  } catch (error) {
    console.error('Error al cargar el feed:', error);
  }
});
</script>

<style scoped>
.home-container {
  padding: 24px;
  padding-top: 0;
  display: flex;
  flex-direction: column;
  gap: 24px;
  min-height: 100%;
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
  gap: 20px;
}

.feed-item {
  display: flex;
  justify-content: space-between;
  background: linear-gradient(135deg, #4D2A37, #3a1f2b);
  padding: 16px;
  border-radius: 16px;
  box-shadow: 0 0 10px #cb477e55;
  font-family: 'Quicksand', sans-serif;
  color: #f5e7ec;
  align-items: center;
}

.entry-left {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
}

.entry-header {
  display: flex;
  align-items: center;
  gap: 12px;
}

.feed-user {
  font-weight: bold;
  font-size: 1.1rem;
  color: #ffffff;
}

.feed-date {
  font-size: 0.75rem;
  color: #aaa;
}

.entry-body p {
  margin: 2px 0;
  font-size: 0.95rem;
  color: #f1d9de;
}

.mood-text {
  font-weight: bold;
  color: #ff92e0;
}

.entry-right {
  margin-left: 16px;
}

.album-cover {
  width: 80px;
  height: 80px;
  border-radius: 10px;
  object-fit: cover;
  box-shadow: 0 0 10px #cb477e55;
  border: 2px solid #fff;
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #b88cff;
}
</style>
