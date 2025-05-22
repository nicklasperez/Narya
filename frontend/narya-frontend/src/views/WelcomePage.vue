<template>
  <ion-page>
    <ion-content class="welcome-bg fade-in" fullscreen>
      <div class="welcome-container">
        <h1 class="welcome-title">Narya</h1>
        <p class="welcome-subtitle">Comparte lo que sientes, escucha lo que viven.</p>
        <p class="welcome-subtitle">Una App por Nicklas Stæhr Pérez.</p>


        <ion-button expand="block" shape="round" class="welcome-button" @click="goToLogin">
          Iniciar sesión
        </ion-button>

        <ion-button expand="block" shape="round" fill="outline" class="welcome-button-outline" @click="goToRegister">
          Registrarse
        </ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonButton } from '@ionic/vue';
import { useRouter } from 'vue-router';

const router = useRouter();

function goToLogin() {
  router.push('/login');
}

function goToRegister() {
  router.push('/register');
}
</script>

<style scoped>
.welcome-bg {
  --background: none;
  background:
    radial-gradient(circle at 30% 30%, rgba(156, 92, 255, 0.12), transparent 60%),
    radial-gradient(circle at 70% 60%, rgba(92, 236, 255, 0.12), transparent 60%);
  background-color: #1e1e2f;
  background-blend-mode: screen;
  animation: neon-fade 14s ease-in-out infinite alternate;
}

.welcome-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 32px;
  text-align: center;
}

.welcome-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 3rem;
  color: var(--narya-neon-purple, #9c5cff);
  text-shadow: 0 0 8px var(--narya-neon-purple, #9c5cff);
  margin-bottom: 12px;
}

.welcome-subtitle {
  color: #ffffffcc;
  font-size: 1.1rem;
  margin-bottom: 40px;
}

.welcome-button,
.welcome-button-outline {
  margin-bottom: 16px;
  --background: var(--narya-neon-pink, #ff5ca2);
  --color: white;
  --box-shadow: 0 0 12px var(--narya-neon-pink, #ff5ca2);
}

.welcome-button-outline {
  --background: transparent;
  --color: var(--narya-neon-pink, #ff5ca2);
  --border-color: var(--narya-neon-pink, #ff5ca2);
}
</style>
