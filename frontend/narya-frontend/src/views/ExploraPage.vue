<template>
  <ion-page>
    <ion-content class="tab-bg-explora fade-in" fullscreen>
      <div class="explora-container">
        
        <!-- Título -->
        <h2 class="explora-title">🎧 Explora por mood</h2>

        <!-- 🔍 Buscador de usuarios -->
        <input
          v-model="searchQuery"
          @input="buscarUsuarios"
          placeholder="Buscar usuarios..."
          class="search-bar"
        />

        <!-- Resultados de usuarios -->
        <div v-if="usuariosEncontrados.length > 0" class="user-results">
          <div
            v-for="user in usuariosEncontrados"
            :key="user.id"
            class="user-card neon-border"
            @click="goToProfile(user.id)"
          >
            <img :src="user.profile_picture" class="avatar" />
            <div class="user-info">
              <p class="username">@{{ user.username }}</p>
              <p class="name">{{ user.name }} {{ user.surname }}</p>
            </div>
          </div>
        </div>

        

        <!-- Moods -->
        <div class="moods-grid">
          <div
            v-for="mood in moods"
            :key="mood.id"
            class="mood-card neon-border"
            :style="{ '--mood-color': mood.color }"
            @click="goToMood(mood.id)"
          >
            <span :class="'neon-text-' + slugifyMood(mood.name)">
              {{ mood.name }}
            </span>
          </div>
        </div>

      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent } from '@ionic/vue';
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import { useRouter } from 'vue-router';

interface Usuario {
  id: number;
  username: string;
  name: string;
  surname: string;
  profile_picture: string;
}

const usuariosEncontrados = ref<Usuario[]>([]);
const router = useRouter();

const moods = ref<{ id: number; name: string; color: string }[]>([]);
const searchQuery = ref('');


onMounted(async () => {
  try {
    const response = await api.get('/moods');
    moods.value = response.data;
  } catch (err) {
    console.error('Error cargando moods:', err);
  }
});

async function buscarUsuarios() {
  if (searchQuery.value.trim().length < 2) {
    usuariosEncontrados.value = [];
    return;
  }

  try {
    const res = await api.get(`/search-users?query=${searchQuery.value}`);
    usuariosEncontrados.value = res.data;
  } catch (error) {
    console.error('Error buscando usuarios', error);
  }
}

function goToMood(moodId: number) {
  router.push(`/tabs/explora/mood/${moodId}`);
}

function goToProfile(userId: number) {
  router.push(`/tabs/perfil-publico/${userId}`);
}

function slugifyMood(name: string): string {
  return name.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/\s+/g, '');
}
</script>

<style scoped>
.explora-container {
  display: flex;
  flex-direction: column;
  padding: 24px;
}

.search-bar {
  width: 90%;
  margin: 16px auto;
  padding: 12px;
  border-radius: 10px;
  border: none;
  font-size: 16px;
  box-shadow: 0 0 5px #00f0ff;
  background-color: #1a1a1a;
  color: white;
}

.user-results {
  padding: 0 16px;
  margin-bottom: 24px;
}

.user-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background-color: rgba(255, 255, 255, 0.05);
  padding: 12px;
  border-radius: 10px;
  margin-bottom: 12px;
  cursor: pointer;
}

.user-card:hover {
  background-color: rgba(255, 255, 255, 0.08);
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
}

.user-info .username {
  font-weight: bold;
  color: #00f0ff;
  font-size: 1rem;
}

.user-info .name {
  font-size: 0.9rem;
  color: #ccc;
}

.explora-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: #4ef7a1;
  text-align: center;
  margin-bottom: 24px;
  text-shadow: 0 0 8px #4ef7a1;
}

.moods-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 16px;
}

.mood-card {
  background-color: rgba(0, 0, 0, 0.3);
  border: 2px solid var(--mood-color);
  border-radius: 12px;
  padding: 16px;
  text-align: center;
  font-family: 'Quicksand', sans-serif;
  font-size: 1.1rem;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.mood-card:hover {
  transform: scale(1.05);
  box-shadow: 0 0 12px var(--mood-color);
}
</style>
