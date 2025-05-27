<template>
  <ion-page>
    <ion-content class="tab-bg-ajustes" fullscreen>
      <div class="ajustes-container fade-in">
        <h2 class="ajustes-title">Ajustes</h2>

        <!-- Botón dinámico según si está vinculada la cuenta de Spotify -->
        <ion-button
          v-if="!spotifyLinked"
          @click="vincularSpotify"
          expand="block"
          shape="round"
          class="spotify-btn"
        >
          Vincular cuenta Spotify
        </ion-button>

        <ion-button
          v-else
          @click="desvincularSpotify"
          expand="block"
          shape="round"
          class="spotify-btn"
          style="--background: #d24848; --box-shadow: 0 0 12px #ff5c5c;"
        >
          Desvincular cuenta Spotify
        </ion-button>

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
import { IonPage, IonContent, IonButton, toastController } from '@ionic/vue';
import { useRouter, useRoute } from 'vue-router';
import { logout } from '@/utils/auth';
import { ref, onMounted } from 'vue';
import api from '@/utils/api';

const route = useRoute();
const router = useRouter();
const token = localStorage.getItem('token');
const spotifyLinked = ref(false);

function vincularSpotify() {
  if (!token) {
    console.error("Token no disponible");
    return;
  }

  window.open(
    `http://localhost:8000/api/spotify/redirect?token=${token}`,
    '_blank',
    'width=500,height=700'
  );
}

async function desvincularSpotify() {
  try {
    await api.post('/spotify/unlink');
    spotifyLinked.value = false;

    const toast = await toastController.create({
      message: 'Cuenta de Spotify desvinculada',
      duration: 2000,
      color: 'medium',
    });
    toast.present();
  } catch (err) {
    console.error("Error al desvincular Spotify:", err);
  }
}

function handleLogout() {
  logout();
  router.push('/login');
}

onMounted(async () => {
  if (route.query.spotify === 'success') {
    const toast = await toastController.create({
      message: 'Cuenta de Spotify vinculada con éxito',
      duration: 2500,
      color: 'success',
    });
    toast.present();

    try {
      const response = await api.get('/user');
      spotifyLinked.value = !!response.data.spotify_id;
    } catch (err) {
      console.error("Error al verificar estado Spotify tras vincular:", err);
    }
  } else {
    try {
      const response = await api.get('/user');
      spotifyLinked.value = !!response.data.spotify_id;
    } catch (err) {
      console.error("No se pudo verificar estado Spotify:", err);
    }
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

.spotify-btn {
  --background: #1db954;
  --color: white;
  --box-shadow: 0 0 12px #21e065;
  margin-bottom: 16px;
}
</style>
