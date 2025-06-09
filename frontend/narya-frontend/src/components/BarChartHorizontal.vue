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
            indexAxis: 'y',
            scales: {
                y: {
                    ticks: {
                        color: '#ffffff', // Nombre + artista en blanco
                        font: {
                            family: 'Quicksand',
                            size: 12
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
    max-height: 400px; /* Un poco más alto para que el texto debajo de las barras se vea bien */
    margin-bottom: 16px;
}
</style>
