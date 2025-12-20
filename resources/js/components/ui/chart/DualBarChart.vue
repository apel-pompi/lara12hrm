<script setup lang="ts">
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
} from "chart.js";

import { Bar } from "vue-chartjs";

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const props = defineProps({
  labels: {
    type: Array,
    required: true
  },
  alllead: {
    type: Array,
    required: true
  },
  pending: {
    type: Array,
    required: true
  },
  lead: {
    type: Array,
    required: true
  },
  prospect: {
    type: Array,
    required: true
  },
  onboard: {
    type: Array,
    required: true
  },
  archive: {
    type: Array,
    required: true
  }
});
</script>

<template>
  <div class="w-full h-full">
    <Bar
      :data="{
        labels: labels,
        datasets: [
          {
            label: 'All Lead',
            data: alllead,
            backgroundColor: 'oklch(70.7% 0.165 254.624)',
            borderRadius: 6,
            barThickness: 50
          },
          {
            label: 'Lead',
            data: lead,
            backgroundColor: 'oklch(48.8% 0.243 264.376)',
            borderRadius: 6,
            barThickness: 40
          },
          {
            label: 'Pending',
            data: pending,
            backgroundColor: 'oklch(76.9% 0.188 70.08)', 
            borderRadius: 6,
            barThickness: 45
          },
          {
            label: 'Prospect',
            data: prospect,
            backgroundColor: 'oklch(69.6% 0.17 162.48)', 
            borderRadius: 6,
            barThickness: 35
          },
          {
            label: 'onBoard',
            data: onboard,
            backgroundColor: 'oklch(78.9% 0.154 211.53)', 
            borderRadius: 6,
            barThickness: 30
          },
          {
            label: 'Archive',
            data: archive,
            backgroundColor: 'oklch(20.8% 0.042 265.755)', 
            borderRadius: 6,
            barThickness: 25
          }
        ]
      }"
      :options="{
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          y: {
            grid: {
              color: 'rgba(0,0,0,0.05)'
            },
            ticks: { color: '#6B7280' }
          },
          x: {
            ticks: { color: '#6B7280' },
          }
        },
        plugins: {
          legend: { display: true },
          tooltip: { enabled: true }
        }
      }"
    />
  </div>
</template>
