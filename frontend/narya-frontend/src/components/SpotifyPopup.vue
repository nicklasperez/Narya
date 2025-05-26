<template>
    <ion-modal :is-open="visible" @didDismiss="visible = false">
        <ion-content class="spotify-popup-content">
            <div class="popup-wrapper">
                <div class="popup-container">
                    <h2>¡Vincula tu cuenta de Spotify!</h2>
                    <p>Conecta tu cuenta de Spotify y empieza a registrar tus emociones con música.</p>
                    <ion-button expand="block" class="spotify-button" @click="openSpotifyAuth">
                        Vincular Spotify
                    </ion-button>
                    <ion-button fill="clear" color="medium" expand="block" @click="visible = false">Ahora
                        no</ion-button>
                </div>
            </div>
        </ion-content>
    </ion-modal>
</template>

<script setup lang="ts">
import { ref, defineExpose } from 'vue';
import { IonModal, IonContent, IonButton } from '@ionic/vue';

const visible = ref(false);

function open() {
    visible.value = true;
}

function openSpotifyAuth() {
  const token = localStorage.getItem('token');
  const url = `http://127.0.0.1:8000/api/spotify/redirect?token=${token}`;
  window.open(url, '_blank');
}

defineExpose({ open });
</script>

<style>
.spotify-popup-content {
    --background: rgba(18, 18, 18, 0.95);
    backdrop-filter: blur(10px);
    animation: fadeInSmooth 0.5s ease;
    padding: 0;
    height: 100%;
}

/* Este es el truco: envoltorio que centra el popup */
.popup-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}

.popup-container {
    width: 90%;
    max-width: 400px;
    padding: 32px 24px;
    border-radius: 20px;
    text-align: center;
    font-family: 'Quicksand', sans-serif;

    background:
        radial-gradient(circle at 30% 30%, rgba(30, 255, 126, 0.08), transparent 60%),
        radial-gradient(circle at 70% 70%, rgba(0, 255, 138, 0.06), transparent 60%);

    border: 1px solid rgba(0, 255, 138, 0.4);
    box-shadow: 0 0 20px rgba(0, 255, 138, 0.2);
}

.popup-container h2 {
    color: #1ed760;
    text-shadow: 0 0 6px #1ed760;
    margin-bottom: 16px;
    font-size: 1.5rem;
}

.popup-container p {
    color: #ccc;
    margin-bottom: 24px;
    font-size: 1rem;
}

.spotify-button {
    --background: #0cb246;
    --color: white;
    --box-shadow: 0 0 8px #1ed760;
    margin-bottom: 12px;
}

@keyframes fadeInSmooth {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
