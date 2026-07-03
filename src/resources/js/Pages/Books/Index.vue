<template>
  <AppLayout>
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">Livros</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gerencie o acervo da biblioteca</p>
        </div>
        <Link href="/books/create" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-200">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Novo Livro
        </Link>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Livros</th>
                <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Autores</th>
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
                  <button @click="editBook(book)" title="Editar" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                  </button>
                  <button @click="deleteBook(book.book_id)" title="Excluir" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20 transition-colors">
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
    </div>

    <BookModal
      :show="showModal"
      :book="editingBook"
      :authors="authorsList"
      :subjects="subjectsList"
      @close="closeModal"
      @saved="fetchBooks"
    />
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import AppLayout from '../../Layouts/AppLayout.vue';
import BookModal from '../../Components/BookModal.vue';

const books = ref([]);
const authorsList = ref([]);
const subjectsList = ref([]);
const showModal = ref(false);
const editingBook = ref(null);
const pagination = ref({});

const fetchBooks = async () => {
  try {
    const response = await axios.get('/api/books');
    books.value = response.data.data || response.data;
    pagination.value = {
      links: response.data.links,
      from: response.data.from,
      to: response.data.to,
      total: response.data.total
    };
  } catch (error) {
    console.error(error);
  }
};

const formatPaginationLabel = (label) => {
  if (label.includes('previous') || label.includes('Previous')) return '&laquo; Anterior';
  if (label.includes('next') || label.includes('Next')) return 'Próxima &raquo;';
  return label;
};

const fetchDependencies = async () => {
  try {
    const [authorsRes, subjectsRes] = await Promise.all([
      axios.get('/api/authors'),
      axios.get('/api/subjects')
    ]);
    authorsList.value = authorsRes.data;
    subjectsList.value = subjectsRes.data;
  } catch (error) {
    console.error(error);
  }
}

const editBook = (book) => {
  editingBook.value = book;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingBook.value = null;
};

const deleteBook = async (id) => {
  const result = await Swal.fire({
    title: 'Tem certeza?',
    text: "Você deseja realmente excluir este livro?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#4f46e5',
    cancelButtonColor: '#ef4444',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar',
    background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
    color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/books/${id}`);
      fetchBooks();
      Swal.fire({
        title: 'Excluído!',
        text: 'O livro foi excluído.',
        icon: 'success',
        background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
      });
    } catch (error) {
      console.error(error);
      Swal.fire({
        title: 'Erro!',
        text: 'Não foi possível excluir o livro.',
        icon: 'error',
        background: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#fff',
        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#f8fafc' : '#0f172a'
      });
    }
  }
};

onMounted(() => {
  fetchBooks();
  fetchDependencies();
});
</script>
