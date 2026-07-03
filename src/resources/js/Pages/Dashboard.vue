<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
    <!-- Header -->
    <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl sticky top-0 z-40 border-b border-slate-200 dark:border-slate-700 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
            L
          </div>
          <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300 tracking-tight">
            Library
          </h1>
        </div>

        <button @click="openNewModal" class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-200">
          + Adicionar Livro
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700/50 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl font-bold">
            {{ books.length }}
          </div>
          <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total de Registros</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">No Sistema</p>
          </div>
        </div>
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
              <tr v-for="book in books" :key="book.book_id + '-' + book.author_id" class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-8 bg-slate-200 dark:bg-slate-700 rounded flex-shrink-0 relative overflow-hidden">
                       <div class="absolute inset-0 bg-gradient-to-tr from-transparent to-black/10"></div>
                    </div>
                    <div>
                      <p class="font-semibold text-slate-900 dark:text-white">{{ book.book_title }}</p>
                      <p class="text-xs text-slate-500 dark:text-slate-400">{{ book.book_publisher }} (Edição: {{ book.book_edition }}, {{ book.book_year }})</p>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                    {{ book.author_name }}
                  </span>
                </td>
                <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-300 truncate max-w-xs" :title="book.subjects_list">
                  {{ book.subjects_list }}
                </td>
                <td class="py-4 px-6">
                  <p class="text-sm font-semibold text-slate-900 dark:text-white">R$ {{ book.book_price }}</p>
                </td>
                <td class="py-4 px-6 text-right space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button @click="editBook(book)" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">Editar</button>
                  <button @click="deleteBook(book.book_id)" class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 text-sm font-medium">Excluir</button>
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

const fetchData = async () => {
  try {
    const [booksRes, authorsRes, subjectsRes] = await Promise.all([
      axios.get('/api/books'),
      axios.get('/api/authors'),
      axios.get('/api/subjects')
    ]);
    books.value = booksRes.data;
    authors.value = authorsRes.data;
    subjects.value = subjectsRes.data;
  } catch (e) {
    console.error("Failed to fetch data:", e);
  }
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
