<template>
  <ion-page>
    <ion-content class="tab-bg-graficos fade-in" fullscreen>
      <div class="graficos-container">
        <h2 class="graficos-title">📊 Tus Gráficos</h2>

        <!-- Reparto total de moods -->
        <h3 class="graficos-subtitle">Reparto total de moods</h3>
        <PieChart v-if="moodsData.length" :chart-data="moodsData" />
        <p v-else class="empty-text">No hay datos suficientes.</p>

        <!-- Mood dominante por semana -->
        <h3 class="graficos-subtitle">Mood dominante por semana</h3>
        <BarChart v-if="moodData.labels.length" :chart-data="moodData" />
        <p v-else class="empty-text">No hay datos suficientes.</p>

        <!-- Canciones más escuchadas -->
        <h3 class="graficos-subtitle">Canciones más escuchadas</h3>
        <BarChartHorizontal v-if="songsData.labels.length" :chart-data="songsData" />
        <p v-else class="empty-text">No hay datos suficientes.</p>

        <!-- Artistas más frecuentes -->
        <h3 class="graficos-subtitle">Artistas más frecuentes</h3>
        <BarChartHorizontal v-if="artistsData.labels.length" :chart-data="artistsData" />
        <p v-else class="empty-text">No hay datos suficientes.</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent } from '@ionic/vue';
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import { onIonViewDidEnter } from '@ionic/vue';
import { CanvasRenderer } from 'echarts/renderers';


// Importamos nuestros componentes de gráfico
import BarChart from '@/components/BarChart.vue';
import BarChartHorizontal from '@/components/BarChartHorizontal.vue';
import PieChart from '@/components/PieChart.vue';

// Datos para los gráficos

const moodsData = ref([]);

const moodData = ref({
  labels: [],
  datasets: [{
    label: 'Entradas',
    data: [],
    backgroundColor: '#4e9af1',
    borderColor: '#4e9af1',
    borderWidth: 1,
  }],
});

const songsData = ref({
  labels: [],
  datasets: [{
    label: 'Veces escuchada',
    data: [],
    backgroundColor: '#cb477e',
    borderColor: '#cb477e',
    borderWidth: 1,
  }],
});

const artistsData = ref({
  labels: [],
  datasets: [{
    label: 'Entradas',
    data: [],
    backgroundColor: '#1db954',
    borderColor: '#1db954',
    borderWidth: 1,
  }],
});


onIonViewDidEnter(async () => {
  await loadMoodDominante();
  await loadCancionesMasEscuchadas();
  await loadArtistasMasFrecuentes();
  await loadMoodsRepartoTotal();
});



async function loadMoodDominante() {
  try {
    const response = await api.get('/stats/mood-dominante-semana');
    const data = response.data;

    // Mostramos: Semana XX - Nombre del mood
    moodData.value.labels = data.map((item: { week: number; mood: string; count: number }) =>
      `Semana ${item.week.toString().slice(-2)} - ${item.mood}`
    );

    // Valores de conteo para la altura de la barra
    moodData.value.datasets[0].data = data.map((item: { week: number; mood: string; count: number }) => item.count);
    
  } catch (err) {
    console.error('Error cargando mood dominante:', err);
  }
}



async function loadCancionesMasEscuchadas() {
  try {
    const response = await api.get('/stats/canciones-mas-escuchadas');
    const data = response.data;

    songsData.value.labels = data.map((item: any) => `${item.song_name} - ${item.artist_name}`);
    songsData.value.datasets[0].data = data.map((item: any) => item.count);
  } catch (err) {
    console.error('Error cargando canciones más escuchadas:', err);
  }
}

async function loadArtistasMasFrecuentes() {
  try {
    const response = await api.get('/stats/artistas-mas-frecuentes');
    const data = response.data;

    artistsData.value.labels = data.map((item: any) => item.artist_name);
    artistsData.value.datasets[0].data = data.map((item: any) => item.count);
  } catch (err) {
    console.error('Error cargando artistas más frecuentes:', err);
  }
}

async function loadMoodsRepartoTotal() {
  try {
    const response = await api.get('/stats/moods-reparto-total');
    console.log('Moods reparto total:', response.data); // 👈 añade este log
    moodsData.value = response.data;
  } catch (err) {
    console.error('Error cargando reparto de moods:', err);
  }
}


</script>

<style>
.graficos-container {
  display: flex;
  flex-direction: column;
  padding: 24px;
}

.graficos-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 2rem;
  color: var(--narya-neon-pink, #cb477e);
  text-align: center;
  margin-bottom: 24px;
  text-shadow: 0 0 8px var(--narya-neon-pink, #e7d8de);
}

.graficos-subtitle {
  font-family: 'Quicksand', sans-serif;
  font-size: 1.4rem;
  color: #4e9af1;
  margin: 16px 0 8px;
  text-shadow: 0 0 6px #4e9af1;
}

.empty-text {
  font-family: 'Quicksand', sans-serif;
  color: white;
  text-align: center;
  margin-bottom: 16px;
}
</style>
