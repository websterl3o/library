<template>
  <AppLayout>
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden max-w-2xl mx-auto">
      <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-700/50">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Novo Autor</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Cadastre um novo autor no sistema</p>
      </div>

      <div class="p-6 sm:p-8">
        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Nome do Autor</label>
            <input v-model="form.name" type="text" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required maxlength="40" />
          </div>

          <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-slate-100 dark:border-slate-700/50">
            <Link href="/authors" class="rounded-2xl px-6 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
              Cancelar
            </Link>
            <button type="submit" :disabled="loading" class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:hover:scale-100">
              {{ loading ? 'Salvando...' : 'Salvar Autor' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '../../Layouts/AppLayout.vue';

const loading = ref(false);
const form = ref({
  name: ''
});

const submit = async () => {
  loading.value = true;
  try {
    await axios.post('/api/authors', form.value);
    router.visit('/authors');
  } catch (error) {
    console.error(error);
    alert('Erro de validação ao salvar autor.');
  } finally {
    loading.value = false;
  }
};
</script>
