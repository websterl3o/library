<template>
  <AppLayout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Visão geral do sistema</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Livros</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ counts.books }}</p>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Autores</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ counts.authors }}</p>
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Assuntos</p>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ counts.subjects }}</p>
        </div>
      </div>
    </div>

    <!-- Reports -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 h-[400px] flex flex-col">
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Livros por Autor</h3>
        <div class="relative flex-1 w-full min-h-0">
          <Bar v-if="chartDataAuthors" :data="chartDataAuthors" :options="chartOptions" />
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 h-[400px] flex flex-col">
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Livros por Assunto</h3>
        <div class="relative flex-1 w-full min-h-0">
          <Doughnut v-if="chartDataSubjects" :data="chartDataSubjects" :options="pieOptions" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';
import { truncateName } from '../helper.js';
import { Bar, Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const counts = ref({ books: '-', authors: '-', subjects: '-' });
const chartDataAuthors = ref(null);
const chartDataSubjects = ref(null);

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: { precision: 0 }
    }
  }
};

const pieOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'bottom' }
  }
};

onMounted(() => {
  // Busca as contagens de forma independente
  axios.get('/api/dashboard/books-count')
    .then(res => counts.value.books = res.data.count)
    .catch(err => console.error("Erro livros", err));

  axios.get('/api/dashboard/authors-count')
    .then(res => counts.value.authors = res.data.count)
    .catch(err => console.error("Erro autores", err));

  axios.get('/api/dashboard/subjects-count')
    .then(res => counts.value.subjects = res.data.count)
    .catch(err => console.error("Erro assuntos", err));

  // Busca os dados do gráfico de Autores
  axios.get('/api/dashboard/books-by-author')
    .then(res => {
      chartDataAuthors.value = {
        labels: res.data.map(item => truncateName(item.author_name)),
        datasets: [{
          label: 'Qtd. de Livros',
          backgroundColor: '#4f46e5', // indigo-600
          borderRadius: 6,
          data: res.data.map(item => item.book_count)
        }]
      };
    })
    .catch(err => console.error("Erro gráfico autores", err));

  // Busca os dados do gráfico de Assuntos
  axios.get('/api/dashboard/books-by-subject')
    .then(res => {
      chartDataSubjects.value = {
        labels: res.data.map(item => item.subject),
        datasets: [{
          backgroundColor: ['#4f46e5', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6', '#06b6d4'],
          borderWidth: 0,
          data: res.data.map(item => item.book_count)
        }]
      };
    })
    .catch(err => console.error("Erro gráfico assuntos", err));
});
</script>
