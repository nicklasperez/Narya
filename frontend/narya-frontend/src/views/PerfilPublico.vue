<template>
  <ion-page>
    <ion-content class="tab-bg-home fade-in" fullscreen>
      <div class="perfil-container">
        <div class="perfil-header">
          <img :src="user.profile_picture || 'assets/default-avatar.png'" alt="avatar" class="avatar-grande" />
          <div>
            <h2>@{{ user.username }}</h2>
            <p>{{ user.name }} {{ user.surname }}</p>
            <p>Seguidores: {{ user.followers_count }} | Siguiendo: {{ user.following_count }}</p>
            <ion-button
              v-if="!isOwnProfile"
              @click="toggleFollow"
              shape="round"
              :color="isFollowing ? 'medium' : 'primary'"
            >
              {{ isFollowing ? 'Dejar de seguir' : 'Seguir' }}
            </ion-button>
          </div>
        </div>

        <div class="feed-list">
          <div v-for="entry in entries" :key="entry.id" class="feed-item">
            <div class="entry-left">
              <p>🎵 <strong>{{ entry.song_name }}</strong></p>
              <p>🧑‍🎤 {{ entry.artist_name }}</p>
              <p>Mood: <span :style="{ color: entry.mood.color }">{{ entry.mood.name }}</span></p>
              <p class="entry-date">{{ formatDate(entry.created_at) }}</p>
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
import { IonPage, IonContent, IonButton } from '@ionic/vue';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/utils/api';

const route = useRoute();
const user = ref<any>({});
const entries = ref<any[]>([]);
const isFollowing = ref(false);
const isOwnProfile = ref(false);

onMounted(async () => {
  const authId = localStorage.getItem('user_id');
  const userId = route.params.id;

  isOwnProfile.value = authId == userId;

  try {
    const res = await api.get(`/users/${userId}/profile`);
    user.value = res.data.user;
    entries.value = res.data.entries;
    isFollowing.value = res.data.is_following;
  } catch (err) {
    console.error('Error cargando perfil:', err);
  }
});

function formatDate(dateStr: string) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
}

async function toggleFollow() {
  try {
    if (isFollowing.value) {
      await api.post('/unfollow', { user_id: user.value.id });
      isFollowing.value = false;
    } else {
      await api.post('/follow', { user_id: user.value.id });
      isFollowing.value = true;
    }
  } catch (err) {
    console.error('Error al seguir/dejar de seguir:', err);
  }
}
</script>

<style scoped>
.perfil-container {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.perfil-header {
  display: flex;
  gap: 16px;
  align-items: center;
  color: white;
}

.avatar-grande {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #cb477e;
}

.feed-item {
  background: rgba(255, 255, 255, 0.05);
  padding: 16px;
  border-radius: 12px;
  box-shadow: 0 0 10px #cb477e33;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.entry-left {
  display: flex;
  flex-direction: column;
  color: white;
  gap: 4px;
}

.entry-right img.album-cover {
  width: 64px;
  height: 64px;
  border-radius: 8px;
  object-fit: cover;
  border: 2px solid #fff;
}

.entry-date {
  font-size: 0.75rem;
  color: #aaa;
}
</style>
