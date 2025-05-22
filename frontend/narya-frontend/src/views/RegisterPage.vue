<template>
  <ion-page>
    <ion-content class="register-bg fade-in" fullscreen>
      <div class="register-container">
        <h2 class="register-title">Crea tu cuenta</h2>

        <ion-input
          v-model="form.username"
          placeholder="Nombre de usuario"
          type="text"
          class="custom-input"
        ></ion-input>

        <ion-input
          v-model="form.name"
          placeholder="Nombre"
          type="text"
          class="custom-input"
        ></ion-input>

        <ion-input
          v-model="form.surname"
          placeholder="Apellido (opcional)"
          type="text"
          class="custom-input"
        ></ion-input>

        <ion-input
          v-model="form.birthdate"
          placeholder="Fecha de nacimiento"
          type="date"
          class="custom-input"
        ></ion-input>

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

        <ion-input
          v-model="form.password_confirmation"
          placeholder="Confirmar contraseña"
          type="password"
          class="custom-input"
        ></ion-input>

        <ion-button expand="block" shape="round" class="register-button" @click="handleRegister">
          Registrarse
        </ion-button>

        <ion-text color="white" class="redirect-text">
          ¿Ya tienes cuenta? --- 
          <a @click="goToLogin">Iniciar sesión</a>
        </ion-text>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton, IonText } from '@ionic/vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = ref({
  username: '',
  name: '',
  surname: '',
  birthdate: '',
  email: '',
  password: '',
  password_confirmation: ''
});

async function handleRegister() {
  try {
    const response = await axios.post('http://localhost:8000/api/register', form.value);
    const token = response.data.token;

    localStorage.setItem('token', token);
    router.push('/tabs/home');
  } catch (error) {
    console.error(error);
    alert('Error al registrar. Verifica tus datos.');
  }
}

function goToLogin() {
  router.push('/login');
}
</script>

<style>
.register-bg {
  --background: none;
  background:
    radial-gradient(circle at 30% 30%, rgba(255, 92, 162, 0.12), transparent 60%),
    radial-gradient(circle at 70% 60%, rgba(255, 206, 92, 0.12), transparent 60%);
  background-color: #241b1f;
  background-blend-mode: screen;
  animation: neon-fade 14s ease-in-out infinite alternate;
}

.register-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 40px 24px;
  height: 100%;
}

.register-title {
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

/* Centrar el texto dentro del input */
ion-input.custom-input::part(native) {
  text-align: center;
  font-family: 'Quicksand', sans-serif;
  font-size: 1rem;
}

/* Centrar el placeholder también */
ion-input.custom-input::part(native)::placeholder {
  text-align: center;
  color: rgba(255, 255, 255, 0.6);
}

.register-button {
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
  
  cursor: pointer;
}
</style>
