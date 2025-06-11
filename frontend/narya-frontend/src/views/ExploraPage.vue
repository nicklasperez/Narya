<template>
  <ion-page>
    <ion-content class="tab-bg-explora fade-in" fullscreen>
      <div class="explora-container">
        <h2 class="explora-title">🎧 Explora por mood</h2>

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

const router = useRouter();
const moods = ref<{ id: number; name: string; color: string }[]>([]);

onMounted(async () => {
  try {
    const response = await api.get('/moods');
    moods.value = response.data;
  } catch (err) {
    console.error('Error cargando moods:', err);
  }
});

function goToMood(moodId: number) {
  router.push(`/tabs/explora/mood/${moodId}`);
}

// Función para convertir el name del mood en slug compatible con clase CSS
function slugifyMood(name: string): string {
  return name
    .normalize('NFD') // quita acentos
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .replace(/\s+/g, ''); // quita espacios (por si acaso)
}
</script>

<style scoped>
.explora-container {
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
