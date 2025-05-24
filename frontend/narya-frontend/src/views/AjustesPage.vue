<template>
  <ion-page>
    <ion-content class="tab-bg-ajustes" fullscreen>
      <div class="ajustes-container fade-in">
        <h2 class="ajustes-title">Ajustes</h2>
        <ion-button 
          expand="block" 
          shape="round" 
          @click="handleLogout"
          style="--background: #d24848; --color: white; --box-shadow: 0 0 12px #ff5c5c;"
          >
          Cerrar sesión
        </ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonButton } from '@ionic/vue';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { logout } from '@/utils/auth';
import { ref, watch } from 'vue';

const route = useRoute();
const router = useRouter();
const hovering = ref(false);

function handleLogout() {
  logout();
  router.push('/login');
}

onMounted(() => {
  if (route.query.spotify === 'success') {
    alert('Cuenta de Spotify vinculada con éxito');
    // Aquí podrías meter un toast o animación si quieres
  }
});
</script>

<style>
.ajustes-container {
  display: flex;
  flex-direction: column;
  padding: 40px 24px;
  height: 100%;
}

.ajustes-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: var(--narya-neon-pink, #cb477e);
  text-align: center;
  margin-bottom: 24px;
  text-shadow: 0 0 8px var(--narya-neon-pink, #e7d8de);
}

</style>