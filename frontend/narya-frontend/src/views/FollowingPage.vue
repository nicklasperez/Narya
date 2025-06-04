<template>
  <ion-page>
    <ion-content class="tab-bg-home fade-in" fullscreen>
      <div class="followers-container fade-in">
        <h2 class="home-title">Siguiendo</h2>

        <div v-if="following.length === 0" class="empty-text">Aún no sigues a nadie 😢</div>
        <div
          v-for="follower in following"
          :key="follower.id"
          class="user-card neon-border"
          @click="goToProfile(follower.id)"
        >
          <img :src="follower.profile_picture || 'assets/default-avatar.png'" class="avatar" />
          <div class="user-info">
            <p class="username">@{{ follower.username }}</p>
          </div>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent } from '@ionic/vue';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';

const route = useRoute();
const router = useRouter();
const following = ref<any[]>([]);

const userId = Number(route.params.id);

onMounted(async () => {
  try {
    const res = await api.get(`/users/${userId}/following`);
    following.value = res.data;
  } catch (error) {
    console.error('Error al obtener seguidos:', error);
  }
});

function goToProfile(id: number) {
  router.push(`/tabs/perfil/${id}`);
}
</script>

<style scoped>
.followers-container {
  padding: 40px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.home-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: #b88cff;
  text-shadow: 0 0 6px #b88cff;
  text-align: center;
  margin-bottom: 16px;
}

.user-card {
  background: rgba(255, 255, 255, 0.05);
  display: flex;
  align-items: center;
  padding: 12px;
  border-radius: 12px;
  cursor: pointer;
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  border: 2px solid #cb477e;
  object-fit: cover;
  margin-right: 12px;
}

.username {
  color: white;
  font-weight: bold;
  font-size: 1rem;
}

.empty-text {
  text-align: center;
  color: #aaa;
  margin-top: 32px;
}

.neon-border {
  box-shadow: 0 0 8px var(--narya-neon-pink);
}

.fade-in {
  animation: fadeInSmooth 0.5s ease;
}

@keyframes fadeInSmooth {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
