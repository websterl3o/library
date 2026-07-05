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
        <BaseTable
          :headers="headers"
          :items="books"
          :server-items-length="totalBooks"
          @update-options="fetchBooks"
          empty-message="Nenhum livro encontrado."
        >

          <template #item-book_title="book">
            <div class="flex items-center gap-3 py-2">
              <div>
                <p class="font-semibold text-slate-900 dark:text-white">{{ book.book_title }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ book.book_publisher }} (Edição: {{ book.book_edition }}, {{ book.book_year }})</p>
              </div>
            </div>
          </template>

          <template #item-authors_list="book">
            <div class="flex flex-col items-start gap-1 py-2">
              <span v-for="author in (book.authors_list ? book.authors_list.split(', ') : ['Sem autor'])" :key="author" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                {{ author }}
              </span>
            </div>
          </template>

          <template #item-subjects_list="book">
            <div class="flex flex-col items-start gap-1 py-2">
              <span v-for="subject in (book.subjects_list ? book.subjects_list.split(', ') : ['Sem assunto'])" :key="subject" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                {{ subject }}
              </span>
            </div>
          </template>

          <template #item-book_price="book">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(book.book_price) }}
            </p>
          </template>

          <template #item-actions="book">
            <div class="flex items-center space-x-2">
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
            </div>
          </template>
        </BaseTable>
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
import BaseTable from '../../Components/BaseTable.vue';
import BookModal from '../../Components/BookModal.vue';

const books = ref([]);
const totalBooks = ref(0);
const authorsList = ref([]);
const subjectsList = ref([]);
const showModal = ref(false);
const editingBook = ref(null);
const tableOptions = ref({ page: 1, rowsPerPage: 25, sortBy: '', sortType: '' });

const headers = [
  { text: "LIVROS", value: "book_title", sortable: true },
  { text: "AUTORES", value: "authors_list", sortable: false },
  { text: "ASSUNTOS", value: "subjects_list", sortable: false },
  { text: "PREÇO", value: "book_price", sortable: true, width: 120 },
  { text: "AÇÕES", value: "actions", width: 150 }
];

const fetchBooks = async (options) => {
  if (options && options.page !== undefined) {
    tableOptions.value = { ...tableOptions.value, ...options };
  }

  try {
    const params = new URLSearchParams();
    const current = tableOptions.value;

    if (current.page) params.append('page', current.page);
    if (current.rowsPerPage) params.append('per_page', current.rowsPerPage);
    if (current.sortBy) params.append('sortBy', current.sortBy);
    if (current.sortType) params.append('sortType', current.sortType);

    const response = await axios.get(`/api/books?${params.toString()}`);
    books.value = response.data.data;
    totalBooks.value = response.data.total;
  } catch (error) {
    console.error(error);
  }
};

const fetchDependencies = async () => {
  try {
    const [authorsRes, subjectsRes] = await Promise.all([
      axios.get('/api/authors?per_page=1000'),
      axios.get('/api/subjects?per_page=1000')
    ]);
    authorsList.value = authorsRes.data.data || authorsRes.data;
    subjectsList.value = subjectsRes.data.data || subjectsRes.data;
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
