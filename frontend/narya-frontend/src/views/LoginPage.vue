<template>
  <ion-page>
    <ion-content class="login-bg fade-in" fullscreen>
      <div class="login-container">
        <h2 class="login-title">Inicia sesión</h2>

        <ion-input
          v-model="form.email"
          placeholder="Correo electrónico"
          type="email"
          class="custom-input"
        ></ion-input>

        <ion-input
          v-model="form.password"
          placeholder="Contraseña"
          type="password"
          class="custom-input"
        ></ion-input>

        <ion-button expand="block" shape="round" class="login-button" @click="handleLogin">
          Entrar
        </ion-button>

        <ion-text color="white" class="redirect-text">
          ¿No tienes cuenta? ---
          <a @click="goToRegister">Regístrate</a>
        </ion-text>
      </div>

      <!-- Popup de Spotify -->
      <SpotifyPopup ref="spotifyPopupRef" />
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton, IonText } from '@ionic/vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import SpotifyPopup from '@/components/SpotifyPopup.vue';

const router = useRouter();

const spotifyPopupRef = ref();
const form = ref({
  email: '',
  password: ''
});

async function handleLogin() {
  try {
    const response = await axios.post('http://localhost:8000/api/login', form.value);
    const token = response.data.token;
    localStorage.setItem('token', token);

    // Verificar estado del usuario
    const userRes = await axios.get('http://localhost:8000/api/user', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    const user = userRes.data;

    if (!user.spotify_id) {
      spotifyPopupRef.value.open(); // mostrar popup si no está vinculado
    }

    router.push('/tabs/home');
  } catch (error: any) {
    console.error('Error al iniciar sesión:', error);
    alert('Credenciales inválidas. Intenta de nuevo.');
  }
}

function goToRegister() {
  router.push('/register');
}
</script>

<style>
.login-bg {
  --background: none;
  background:
    radial-gradient(circle at 30% 30%, rgba(92, 255, 200, 0.12), transparent 60%),
    radial-gradient(circle at 70% 60%, rgba(92, 162, 255, 0.12), transparent 60%);
  background-color: #1c1c24;
  background-blend-mode: screen;
  animation: neon-fade 14s ease-in-out infinite alternate;
}

.login-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 40px 24px;
  height: 100%;
}

.login-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: var(--narya-neon-pink, #ff5ca2);
  text-align: center;
  margin-bottom: 24px;
  text-shadow: 0 0 8px var(--narya-neon-pink, #ff5ca2);
}

.custom-input {
  margin-bottom: 16px;
  --background: rgba(255, 255, 255, 0.08);
  --color: #fff;
  --placeholder-color: rgba(255, 255, 255, 0.5);
  --highlight-color-focused: var(--narya-neon-pink, #ff5ca2);
  --padding-start: 16px;
  --padding-end: 16px;
  --border-radius: 25px;
}

ion-input.custom-input::part(native) {
  text-align: center;
  font-family: 'Quicksand', sans-serif;
  font-size: 1rem;
  box-shadow: none;
  border: none;
}

ion-input.custom-input::part(native)::placeholder {
  text-align: center;
  color: rgba(255, 255, 255, 0.6);
}

.login-button {
  --background: var(--narya-neon-pink, #ff5ca2);
  --color: white;
  --box-shadow: 0 0 12px var(--narya-neon-pink, #ff5ca2);
  margin-top: 16px;
}

.redirect-text {
  text-align: center;
  margin-top: 16px;
  font-size: 0.95rem;
}

.redirect-text a {
  color: var(--narya-neon-pink, #ff5ca2);
  text-decoration: underline;
  cursor: pointer;
}
</style>
