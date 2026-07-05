<template>
  <AppLayout>
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">Autores</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gerencie os autores cadastrados no sistema</p>
        </div>
        <div class="flex items-center gap-2">
          <a href="/api/reports/authors" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-100 dark:bg-slate-700 px-6 py-2.5 text-sm font-semibold text-slate-700 dark:text-white shadow-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200" title="Baixar Relatório Completo de Autores">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Relatório Completo
          </a>
          <Link href="/authors/create" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Autor
          </Link>
        </div>
      </div>

      <div class="overflow-x-auto">
        <BaseTable
          :headers="headers"
          :items="authors"
          :server-items-length="totalAuthors"
          @update-options="fetchAuthors"
          empty-message="Nenhum autor encontrado."
        >

          <template #item-id="author">
            <span class="text-slate-500 dark:text-slate-400 font-medium">#{{ author.id }}</span>
          </template>

          <template #item-name="author">
            <div class="font-semibold text-slate-900 dark:text-white py-2">{{ author.name }}</div>
          </template>

          <template #item-actions="author">
            <div class="flex items-center space-x-2">
              <a :href="`/api/reports/authors/${author.id}`" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 transition-colors" title="Baixar Relatório">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </a>
              <button @click="editAuthor(author)" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 transition-colors" title="Editar">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
              </button>
              <button @click="deleteAuthor(author.id)" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20 transition-colors" title="Excluir">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>
          </template>
        </BaseTable>
      </div>
    </div>

    <!-- Edit Modal (Simplified for Authors) -->
    <div v-if="editingAuthor" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="editingAuthor = null"></div>
      <div class="relative bg-white/70 dark:bg-slate-900/80 backdrop-blur-xl border border-white/40 dark:border-slate-700/50 rounded-3xl shadow-2xl w-full max-w-lg p-6 sm:p-8">
        <h3 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Editar Autor</h3>
        <form @submit.prevent="updateAuthor" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Nome</label>
            <input v-model="editForm.name" type="text" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-slate-900 dark:text-white" required maxlength="40" />
          </div>
          <div class="mt-6 flex justify-end gap-4">
            <button type="button" @click="editingAuthor = null" class="px-6 py-2.5 rounded-2xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Cancelar</button>
            <button type="submit" class="px-6 py-2.5 rounded-2xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700">Salvar</button>
          </div>
        </form>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import AppLayout from '../../Layouts/AppLayout.vue';
import BaseTable from '../../Components/BaseTable.vue';

const authors = ref([]);
const totalAuthors = ref(0);
const editingAuthor = ref(null);
const editForm = ref({
  name: ''
});

const headers = [
  { text: "ID", value: "id", sortable: true, width: 100 },
  { text: "NOME", value: "name", sortable: true },
  { text: "AÇÕES", value: "actions", width: 150 }
];

const fetchAuthors = async (options = { page: 1, rowsPerPage: 25, sortBy: 'id', sortType: 'asc' }) => {
  try {
    const params = new URLSearchParams();
    if (options.page) params.append('page', options.page);
    if (options.rowsPerPage) params.append('per_page', options.rowsPerPage);
    if (options.sortBy) params.append('sortBy', options.sortBy);
    if (options.sortType) params.append('sortType', options.sortType);

    const response = await axios.get(`/api/authors?${params.toString()}`);
    authors.value = response.data.data;
    totalAuthors.value = response.data.total;
  } catch (error) {
    console.error(error);
  }
};

const editAuthor = (author) => {
  editingAuthor.value = author;
  editForm.value = { name: author.name };
};

const updateAuthor = async () => {
  try {
    await axios.put(`/api/authors/${editingAuthor.value.id}`, editForm.value);
    editingAuthor.value = null;
    fetchAuthors();
  } catch (error) {
    console.error(error);
    alert('Erro ao atualizar o autor.');
  }
};

const deleteAuthor = async (id) => {
  const result = await Swal.fire({
    title: 'Atenção!',
    text: "Excluir este autor também excluirá permanentemente todos os livros relacionados a ele! Deseja realmente prosseguir?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar',
    background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
    color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/authors/${id}`);
      fetchAuthors();
      Swal.fire({
        title: 'Excluído!',
        text: 'O autor e seus livros foram excluídos.',
        icon: 'success',
        background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
      });
    } catch (error) {
      console.error(error);
      Swal.fire({
        title: 'Erro!',
        text: 'Não foi possível excluir o autor.',
        icon: 'error',
        background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
      });
    }
  }
};

onMounted(fetchAuthors);
</script>
