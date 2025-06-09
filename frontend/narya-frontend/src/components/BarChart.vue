<template>
  <canvas ref="canvas"></canvas>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import {
  Chart,
  registerables
} from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  chartData: Object
});

const canvas = ref(null);
let chartInstance = null;

onMounted(() => {
  renderChart();
});

watch(() => props.chartData, () => {
  if (chartInstance) {
    chartInstance.destroy();
  }
  renderChart();
}, { deep: true });

function renderChart() {
  if (!canvas.value) return;

  chartInstance = new Chart(canvas.value.getContext('2d'), {
    type: 'bar',
    data: props.chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          ticks: {
            color: '#ffffff',
            font: {
              family: 'Quicksand',
              size: 12
            },
            callback: function(value) {
              const str = this.getLabelForValue(value);
              return str.length > 20 ? str.match(/.{1,20}/g) : str;
            }
          }
        },
        x: {
          ticks: {
            color: '#ffffff',
            font: {
              family: 'Quicksand',
              size: 12
            }
          }
        }
      }
    }
  });
}
</script>

<style scoped>
canvas {
  max-height: 350px; /* un pelín más alto para que si hay varias canciones no se apreten */
  margin-bottom: 16px;
}
</style>
