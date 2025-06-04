<template>
  <ion-page>
    <ion-content class="tab-bg-home fade-in" fullscreen>
      <div class="perfil-container">
        <div class="perfil-header">
          <img :src="user.profile_picture || 'assets/default-avatar.png'" alt="avatar" class="avatar-grande" />
          <div>
            <h2>@{{ user.username }}</h2>
            <p>{{ user.name }} {{ user.surname }}</p>
            <p class="stats-container">
              <span @click="goToFollowers" class="link neon-button">Seguidores: {{ user.followers_count }}</span>
              <span @click="goToFollowing" class="link neon-button">Siguiendo: {{ user.following_count }}</span>
            </p>
            <!-- Botón de seguir solo si no es el perfil propio -->
            <div class="follow-button-container" v-if="!isOwnProfile">
              <ion-button @click="toggleFollow" class="follow-btn" :class="isFollowing ? 'unfollow' : 'follow'"
                expand="block">
                {{ isFollowing ? 'Dejar de seguir' : 'Seguir' }}
              </ion-button>
            </div>

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
import router from '@/router';

const route = useRoute();
const user = ref<any>({});
const entries = ref<any[]>([]);
const isFollowing = ref(false);
const isOwnProfile = ref(false);

// Username autenticado guardado al hacer login
const loggedUsername = localStorage.getItem('username') || '';

onMounted(async () => {
  try {
    const profileId = Number(route.params.id);
    const res = await api.get(`/users/${profileId}/profile`);
    user.value = res.data.user;
    entries.value = res.data.entries;
    isFollowing.value = res.data.is_following;

    // Comparación por username
    console.log('[DEBUG] Usuario autenticado (localStorage):', loggedUsername);
    console.log('[DEBUG] Usuario del perfil:', user.value.username);

    isOwnProfile.value = user.value.username === loggedUsername;
    console.log('[DEBUG] ¿Es el perfil propio?', isOwnProfile.value);

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
      await api.post('/unfollow', { followed_id: user.value.id });
      isFollowing.value = false;
    } else {
      await api.post('/follow', { followed_id: user.value.id });
      isFollowing.value = true;
    }
  } catch (err) {
    console.error('Error al seguir/dejar de seguir:', err);
  }
}

function goToFollowers() {
  router.push(`/followers/${user.value.id}`);
}

function goToFollowing() {
  router.push(`/following/${user.value.id}`);
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
  flex-direction: column;
  align-items: center;
  gap: 8px;
  text-align: center;
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
  box-shadow: 0 0 10px #bf427633;
  display: grid;
  grid-template-columns: 1fr auto;
  align-items: center;
  gap: 16px;
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

.stats-container {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin-top: 8px;
}

.neon-button {
  padding: 6px 14px;
  border: 2px solid var(--narya-neon-pink, #ff5ca2);
  border-radius: 20px;
  background-color: rgba(255, 255, 255, 0.05);
  color: var(--narya-neon-pink, #ff5ca2);
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  text-shadow: 0 0 6px var(--narya-neon-pink, #ff5ca2);
  box-shadow: 0 0 10px var(--narya-neon-pink, #ff5ca2);
}

.neon-button:hover {
  background-color: var(--narya-neon-pink, #ff5ca2);
  color: #fff;
  text-shadow: 0 0 8px #fff;
  box-shadow: 0 0 16px var(--narya-neon-pink, #ff5ca2);
}

.follow-button-container {
  display: flex;
  justify-content: center;
  width: 100%;
  margin-top: 12px;
}

.follow-btn {
  --background: transparent;
  --border-radius: 25px;
  --padding-start: 12px;
  --padding-end: 12px;
  --color: white;
  font-weight: bold;
  text-shadow: 0 0 6px rgba(255, 255, 255, 0.2);
  width: 100%;
  max-width: 240px;
  margin-top: 12px;
  transition: all 0.3s ease;
  align-self: flex-start;
}

/* Cuando NO está siguiendo: color neón rosa */
.follow-btn.follow {
  --border-color: var(--narya-neon-blue);
  border: 2px solid var(--narya-neon-blue);
  box-shadow: 0 0 8px var(--narya-neon-blue);
  --color: var(--narya-neon-blue);
}

/* Cuando SÍ está siguiendo: color neón azul */
.follow-btn.unfollow {
  --border-color: var(--narya-neon-pink);
  border: 2px solid var(--narya-neon-pink);
  box-shadow: 0 0 8px var(--narya-neon-pink);
  --color: var(--narya-neon-pink);
}

.follow-btn:hover {
  animation: neon-glow 1s ease-in-out infinite alternate;
}

@keyframes neon-glow {
  from {
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
  }

  to {
    box-shadow: 0 0 14px rgba(255, 255, 255, 0.7);
  }
}
</style>
