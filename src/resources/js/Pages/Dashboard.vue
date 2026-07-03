<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
    <!-- Header -->
    <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl sticky top-0 z-40 border-b border-slate-200 dark:border-slate-700 shadow-sm">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
            L
          </div>
          <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300 tracking-tight">
            Library
          </h1>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl font-bold">
            {{ pagination.total || 0 }}
          </div>
          <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total de Registros</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">No Sistema</p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end items-center mb-6">
        <button @click="openNewModal" class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-200">
          + Adicionar Livro
        </button>
      </div>

      <!-- Table Container -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Livro</th>
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Autor</th>
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Assuntos</th>
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Preço</th>
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
              <tr v-for="book in books" :key="book.book_id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-3">
                    <div>
                      <p class="font-semibold text-slate-900 dark:text-white">{{ book.book_title }}</p>
                      <p class="text-xs text-slate-500 dark:text-slate-400">{{ book.book_publisher }} (Edição: {{ book.book_edition }}, {{ book.book_year }})</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <div class="flex flex-col items-start gap-2">
                    <span v-for="author in (book.authors_list ? book.authors_list.split(', ') : ['Sem autor'])" :key="author" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                      {{ author }}
                    </span>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <div class="flex flex-col items-start gap-2">
                    <span v-for="subject in (book.subjects_list ? book.subjects_list.split(', ') : ['Sem autor'])" :key="subject" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                      {{ subject }}
                    </span>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <p class="text-sm font-semibold text-slate-900 dark:text-white">R$ {{ book.book_price }}</p>
                </td>
                <td class="py-4 px-6 text-right space-x-2">
                  <button @click="editBook(book)" title="Editar" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                  </button>
                  <button @click="deleteBook(book.book_id)" title="Excluir" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                  </button>
                </td>
              </tr>
              <tr v-if="books.length === 0">
                <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">
                  <p class="text-lg mb-2">Nenhum livro encontrado.</p>
                  <p class="text-sm">Clique em "Adicionar Livro" para popular sua biblioteca.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.total > 0" class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 gap-4">
          <div class="text-sm text-slate-500 dark:text-slate-400">
            Mostrando <span class="font-medium text-slate-900 dark:text-white">{{ pagination.from }}</span> a <span class="font-medium text-slate-900 dark:text-white">{{ pagination.to }}</span> de <span class="font-medium text-slate-900 dark:text-white">{{ pagination.total }}</span> resultados
          </div>
          <div class="flex items-center space-x-1 flex-wrap justify-center gap-y-2">
            <template v-for="(link, index) in pagination.links" :key="index">
              <button
                @click="link.url && fetchData(link.url)"
                :disabled="!link.url"
                v-html="formatPaginationLabel(link.label)"
                :class="[
                  'px-3 py-1.5 text-sm rounded-lg transition-all duration-200',
                  link.active ? 'bg-indigo-600 text-white font-medium shadow-md shadow-indigo-500/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700',
                  !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:-translate-y-0.5'
                ]"
              ></button>
            </template>
          </div>
        </div>
      </div>
    </main>

    <BookModal
      :show="isModalOpen"
      :book="selectedBook"
      :authors="authors"
      :subjects="subjects"
      @close="isModalOpen = false"
      @saved="fetchData"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import BookModal from '../Components/BookModal.vue';

const books = ref([]);
const authors = ref([]);
const subjects = ref([]);
const isModalOpen = ref(false);
const selectedBook = ref(null);
const pagination = ref({});

const fetchData = async (pageUrl = '/api/books') => {
  try {
    const res = await axios.get(pageUrl);
    books.value = res.data.data;
    pagination.value = {
      links: res.data.links,
      from: res.data.from,
      to: res.data.to,
      total: res.data.total
    };

    if (authors.value.length === 0) {
      const [authorsRes, subjectsRes] = await Promise.all([
        axios.get('/api/authors'),
        axios.get('/api/subjects')
      ]);
      authors.value = authorsRes.data;
      subjects.value = subjectsRes.data;
    }
  } catch (e) {
    console.error("Failed to fetch data:", e);
  }
};

const formatPaginationLabel = (label) => {
  if (label.includes('previous') || label.includes('Previous')) return '&laquo; Anterior';
  if (label.includes('next') || label.includes('Next')) return 'Próxima &raquo;';
  return label;
};

const openNewModal = () => {
  selectedBook.value = null;
  isModalOpen.value = true;
};

const editBook = (book) => {
  selectedBook.value = book;
  isModalOpen.value = true;
};

const deleteBook = async (id) => {
  if (confirm("Tem certeza que deseja excluir este livro?")) {
    try {
      await axios.delete(`/api/books/${id}`);
      fetchData();
    } catch (e) {
      console.error(e);
      alert('Falha ao excluir o livro.');
    }
  }
};

onMounted(() => {
  fetchData();
});
</script>
