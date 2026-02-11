<script setup>
import { computed } from 'vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    appointmentStatusCount: {
        type: Array,
        required: true
    }
});

const chartData = computed(() => ({
    // Match the keys returned by your Laravel Service ('status' and 'count')
    labels: props.appointmentStatusCount.map(item => item.status),
    datasets: [{
        backgroundColor: ['#ef4444', '#f59e0b ', '#3b82f6', '#10b981 '], 
        data: props.appointmentStatusCount.map(item => item.count),
        hoverOffset: 4
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom' }
    }
};
</script>

<template>
    <div class="h-full w-full">
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>