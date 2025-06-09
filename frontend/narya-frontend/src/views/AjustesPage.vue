<template>
  <ion-page>
    <ion-content class="tab-bg-ajustes" fullscreen>
      <div class="ajustes-container fade-in">
        <h2 class="ajustes-title">Ajustes</h2>

        <!-- Botón dinámico según si está vinculada la cuenta de Spotify -->
        <ion-button v-if="!spotifyLinked" @click="vincularSpotify" expand="block" shape="round" class="spotify-btn">
          Vincular cuenta Spotify
        </ion-button>

        <ion-button v-else @click="desvincularSpotify" expand="block" shape="round" class="spotify-btn"
          style="--background: #d24848; --box-shadow: 0 0 12px #ff5c5c;">
          Desvincular cuenta Spotify
        </ion-button>

        <!-- Botón de cerrar sesión -->
        <ion-button expand="block" shape="round" @click="handleLogout"
          style="--background: #d24848; --color: white; --box-shadow: 0 0 12px #ff5c5c;">
          Cerrar sesión
        </ion-button>

        <!-- Cambiar contraseña -->
        <h3 class="ajustes-subtitle">Cambiar contraseña</h3>

        <!-- Input de contraseña actual -->
        <ion-input v-model="passwordForm.current_password" placeholder="Contraseña actual" type="password"
          class="custom-input" />

        <!-- Input de nueva contraseña -->
        <ion-input v-model="passwordForm.new_password" placeholder="Nueva contraseña" type="password"
          class="custom-input" />

        <!-- Input de repetir nueva contraseña -->
        <ion-input v-model="passwordForm.new_password_confirmation" placeholder="Repetir nueva contraseña"
          type="password" class="custom-input" />

        <!-- Botón para enviar el cambio de contraseña -->
        <ion-button expand="block" shape="round" @click="cambiarPassword"
          style="--background: #4e9af1; --box-shadow: 0 0 12px #4e9af1;">
          Guardar contraseña
        </ion-button>

        <!-- Cambiar foto de perfil -->
        <h3 class="ajustes-subtitle">Cambiar foto de perfil</h3>

        <!-- Vista previa de la foto actual o nueva -->
        <div class="foto-preview">
          <img :src="fotoPreview || userProfilePicture || 'assets/default-avatar.png'" alt="Foto de perfil" />
        </div>

        <!-- Input para seleccionar nueva imagen -->
        <input type="file" @change="onFileChange" accept="image/*" class="file-input" />

        <!-- Botón para enviar el cambio de foto -->
        <ion-button expand="block" shape="round" @click="cambiarFotoPerfil"
          style="--background: #ff7b00; --box-shadow: 0 0 12px #ff7b00;">
          Guardar foto de perfil
        </ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonButton, IonInput, toastController } from '@ionic/vue';
import { useRouter, useRoute } from 'vue-router';
import { logout } from '@/utils/auth';
import { ref, onMounted } from 'vue';
import api from '@/utils/api';

// Refs y router
const route = useRoute();
const router = useRouter();
const token = localStorage.getItem('token');
const spotifyLinked = ref(false);

// Para formulario de cambiar contraseña
const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});

// Para cambiar foto de perfil
const fotoFile = ref<File | null>(null);
const fotoPreview = ref<string | null>(null);
const userProfilePicture = ref<string | null>(null);

// Función para abrir ventana de vinculación de Spotify
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

// Función para desvincular cuenta de Spotify
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

// Función para cerrar sesión
function handleLogout() {
  logout();
  router.push('/login');
}

// Función para cambiar contraseña
async function cambiarPassword() {
  try {
    await api.post('/users/change-password', passwordForm.value);
    const toast = await toastController.create({
      message: 'Contraseña actualizada correctamente',
      duration: 2000,
      color: 'success',
    });
    toast.present();

    // Limpiar formulario
    passwordForm.value.current_password = '';
    passwordForm.value.new_password = '';
    passwordForm.value.new_password_confirmation = '';
  } catch (err) {
    console.error("Error al cambiar contraseña:", err);
    const error = err as any; // ⚠️ hacemos el casting aquí
    const errorMsg = error.response?.data?.message || 'Error al cambiar la contraseña';
    const toast = await toastController.create({
      message: errorMsg,
      duration: 2500,
      color: 'danger',
    });
    toast.present();
  }
}

// Evento al seleccionar un archivo (foto)
function onFileChange(event: Event) {
  const target = event.target as HTMLInputElement;
  const files = target.files;
  if (files && files[0]) {
    fotoFile.value = files[0];
    fotoPreview.value = URL.createObjectURL(files[0]);
  }
}

// Función para cambiar foto de perfil
async function cambiarFotoPerfil() {
  if (!fotoFile.value) {
    const toast = await toastController.create({
      message: 'Por favor selecciona una imagen',
      duration: 2000,
      color: 'warning',
    });
    toast.present();
    return;
  }

  const formData = new FormData();
  formData.append('profile_picture', fotoFile.value);

  try {
    const response = await api.post('/users/change-profile-picture', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    const toast = await toastController.create({
      message: 'Foto de perfil actualizada',
      duration: 2000,
      color: 'success',
    });
    toast.present();

    // Actualizar preview con la nueva imagen subida
    userProfilePicture.value = response.data.profile_picture;
    fotoPreview.value = null;
    fotoFile.value = null;
  } catch (err) {
    console.error("Error al cambiar foto de perfil:", err);
    const error = err as any; // ⚠️ hacemos el casting aquí
    const errorMsg = error.response?.data?.message || 'Error al cambiar la foto';
    const toast = await toastController.create({
      message: errorMsg,
      duration: 2500,
      color: 'danger',
    });
    toast.present();
  }

}

// Al cargar la página → obtener datos del usuario para saber si tiene Spotify y su foto actual
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
      userProfilePicture.value = response.data.profile_picture;
    } catch (err) {
      console.error("Error al verificar estado Spotify tras vincular:", err);
    }
  } else {
    try {
      const response = await api.get('/user');
      spotifyLinked.value = !!response.data.spotify_id;
      userProfilePicture.value = response.data.profile_picture;
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

.ajustes-subtitle {
  font-family: 'Quicksand', sans-serif;
  font-size: 1.4rem;
  color: var(--narya-neon-pink, #cb477e);
  margin: 24px 0 12px;
  text-shadow: 0 0 6px var(--narya-neon-pink, #e7d8de);
  text-align: center;
}

.spotify-btn {
  --background: #1db954;
  --color: white;
  --box-shadow: 0 0 12px #21e065;
  margin-bottom: 16px;
}

.custom-input {
  --background: rgba(255, 255, 255, 0.05);
  --color: white;
  --placeholder-color: #ccc;
  --padding-start: 12px;
  margin-bottom: 12px;
  border-radius: 8px;
  box-shadow: 0 0 8px #00ffff33;
}

.foto-preview {
  display: flex;
  justify-content: center;
  margin-bottom: 12px;
}

.foto-preview img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #21e065;
  box-shadow: 0 0 12px #21e065;
}

.file-input {
  display: block;
  margin: 0 auto 12px;
  color: white;
}
</style>
