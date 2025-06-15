<template>
  <ion-page>
    <ion-content class="register-bg fade-in" fullscreen>
      <div class="register-container">
        <h2 class="register-title">Crea tu cuenta</h2>

        <!-- Campo: Nombre de usuario -->
        <ion-input v-model="form.username" placeholder="Nombre de usuario" type="text" class="custom-input" />
        <ion-text color="danger" v-if="errors.username">
          <ul>
            <li v-for="e in errors.username" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Nombre -->
        <ion-input v-model="form.name" placeholder="Nombre" type="text" class="custom-input" />
        <ion-text color="danger" v-if="errors.name">
          <ul>
            <li v-for="e in errors.name" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Apellido -->
        <ion-input v-model="form.surname" placeholder="Apellido (opcional)" type="text" class="custom-input" />
        <ion-text color="danger" v-if="errors.surname">
          <ul>
            <li v-for="e in errors.surname" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Fecha de nacimiento -->
        <ion-input v-model="form.birthdate" placeholder="Fecha de nacimiento" type="date" class="custom-input" />
        <ion-text color="danger" v-if="errors.birthdate">
          <ul>
            <li v-for="e in errors.birthdate" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Email -->
        <ion-input v-model="form.email" placeholder="Correo electrónico" type="email" class="custom-input" />
        <ion-text color="danger" v-if="errors.email">
          <ul>
            <li v-for="e in errors.email" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Contraseña -->
        <ion-input v-model="form.password" placeholder="Contraseña" type="password" class="custom-input" />
        <ion-text color="danger" v-if="errors.password">
          <ul>
            <li v-for="e in errors.password" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <!-- Campo: Confirmar contraseña -->
        <ion-input v-model="form.password_confirmation" placeholder="Confirmar contraseña" type="password"
          class="custom-input" />
        <ion-text color="danger" v-if="errors.password_confirmation">
          <ul>
            <li v-for="e in errors.password_confirmation" :key="e">{{ e }}</li>
          </ul>
        </ion-text>

        <ion-text>
          La contraseña debe contener:<br>
          - Al menos 8 caracteres<br>
          - Al menos una letra mayúscula, una minúscula y un número
        </ion-text>

        <!-- Botón de registro -->
        <ion-button expand="block" shape="round" class="register-button" @click="handleRegister">
          Registrarse
        </ion-button>

        <!-- Link de redirección al login -->
        <ion-text color="white" class="redirect-text">
          ¿Ya tienes cuenta? ---
          <a @click="goToLogin">Iniciar sesión</a>
        </ion-text>
      </div>

      <!-- Componente para vincular Spotify si no está vinculado -->
      <SpotifyPopup ref="spotifyPopupRef" />
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton, IonText, IonList, IonItem } from '@ionic/vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import SpotifyPopup from '@/components/SpotifyPopup.vue';

const router = useRouter();
const spotifyPopupRef = ref();

// Formulario de datos del usuario
const form = ref({
  username: '',
  name: '',
  surname: '',
  birthdate: '',
  email: '',
  password: '',
  password_confirmation: ''
});

// Objeto reactivo para guardar errores de validación (por campo)
const errors = ref<{ [key: string]: string[] }>({});

// Función para manejar el registro
async function handleRegister() {
  try {
    // Enviar datos al backend Laravel
    const response = await axios.post('http://localhost:8000/api/register', form.value);
    const token = response.data.token;

    localStorage.setItem('token', token);

    // Obtener datos del usuario una vez registrado
    const userRes = await axios.get('http://localhost:8000/api/user', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    const user = userRes.data;

    // Si no está vinculado con Spotify, mostrar popup
    if (!user.spotify_id) {
      spotifyPopupRef.value.open();
    }

    // Redirigir a Home
    router.push('/tabs/home');
  } catch (error: any) {
    // Si hay errores de validación (código 422), mostrarlos en pantalla
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      console.error(error);
      alert('Error al registrar. Verifica tus datos.');
    }
  }
}

// Navegar a la vista de login
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

ion-input.custom-input::part(native) {
  text-align: center;
  font-family: 'Quicksand', sans-serif;
  font-size: 1rem;
}

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
  text-decoration: underline;
  cursor: pointer;
}

/* Estilo para errores debajo de inputs */
ion-text[color="danger"] {
  margin: -12px 0 12px 0;
  font-size: 0.85rem;
  color: #ff6b6b;
  text-align: center;
}
</style>
