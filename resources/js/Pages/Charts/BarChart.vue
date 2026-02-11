<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { 
    Chart as ChartJS, Title, Tooltip, Legend, 
    BarElement, CategoryScale, LinearScale 
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    monthlyAppointmentCount: {
        type: Array,
        required: true
    }
});

const chartData = computed(() => ({
    labels: props.monthlyAppointmentCount.map(item => item.month),
    datasets: [{
        label: 'Appointments',
        backgroundColor: '#3b82f6',
        borderRadius: 6,
        data: props.monthlyAppointmentCount.map(item => item.count),
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
    }
};
</script>

<template>
    <div class="h-[300px] w-full">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>