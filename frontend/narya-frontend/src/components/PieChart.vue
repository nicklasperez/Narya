<template>
    <div v-if="showChart" style="width: 100%; height: 300px;">
        <v-chart :option="chartOptions" autoresize />
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { PieChart } from 'echarts/charts';
import {
    TitleComponent,
    TooltipComponent,
    LegendComponent
} from 'echarts/components';
import VChart from 'vue-echarts';

// Register components
use([
    CanvasRenderer,
    PieChart,
    TitleComponent,
    TooltipComponent,
    LegendComponent
]);

const props = defineProps({
    chartData: Array
});

const chartOptions = ref({
    backgroundColor: 'transparent',
    tooltip: {
        trigger: 'item',
        textStyle: {
            fontFamily: 'Quicksand',
            fontSize: 14
        }
    },
    legend: {
        orient: 'horizontal',  // de vertical → horizontal
        bottom: 0,             
        textStyle: {
            color: '#ffffff',
            fontFamily: 'Quicksand',
            fontSize: 12         // reduzco el tamaño del texto un poco el tamaño
        }
    },
    series: [
        {
            name: 'Moods',
            type: 'pie',
            radius: '60%',       // 👈 reducimos un poco para que no se pise
            center: ['50%', '45%'],
            data: [],
            label: {
                color: '#ffffff',
                fontFamily: 'Quicksand',
                fontSize: 12,      // 👈 más pequeño para que no se solape
                overflow: 'truncate'  // 👈 opcional, puedes usar 'truncate' o un formatter
            },
            labelLine: {
                lineStyle: {
                    color: '#ffffff'
                }
            }
        }
    ]

});

// Trick to force re-render on tab change
const showChart = ref(true);

function handleVisibilityChange() {
    showChart.value = false;
    setTimeout(() => {
        showChart.value = true;
    }, 50); // small delay so DOM is ready
}

onMounted(() => {
    document.addEventListener('ionViewDidEnter', handleVisibilityChange);
});

onBeforeUnmount(() => {
    document.removeEventListener('ionViewDidEnter', handleVisibilityChange);
});

// Update chart data when props change
watch(() => props.chartData, (newData) => {
    chartOptions.value.series[0].data = newData.map(item => ({
        value: item.count,
        name: item.mood,
        itemStyle: { color: item.color || '#ffffff' }
    }));
}, { immediate: true });
</script>
