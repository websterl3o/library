<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop blur -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" @click="$emit('close')"></div>

    <!-- Modal Content (Glassmorphism) -->
    <div class="relative bg-white/70 dark:bg-slate-900/80 backdrop-blur-xl border border-white/40 dark:border-slate-700/50 rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all ring-1 ring-black/5 p-6 sm:p-8">
      <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-indigo-600 dark:from-violet-400 dark:to-indigo-400 mb-6 tracking-tight">
        {{ book ? 'Editar Livro' : 'Novo Livro' }}
      </h3>

      <form @submit.prevent="submit" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Título</label>
          <input v-model="form.title" type="text" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required maxlength="40" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Editora</label>
            <input v-model="form.publisher" type="text" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required maxlength="40" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Edição</label>
            <input v-model="form.edition" type="number" min="1" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Ano</label>
            <input v-model="form.publication_year" type="text" pattern="\d{4}" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Preço</label>
            <input v-model="form.price" type="number" step="0.01" min="0" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="min-w-0">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1 mb-1">Autores</label>
            <Multiselect
              v-model="form.authors"
              mode="tags"
              :options="authors"
              valueProp="id"
              label="name"
              trackBy="name"
              placeholder="Selecione..."
            />
          </div>
          <div class="min-w-0">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1 mb-1">Assuntos</label>
            <Multiselect
              v-model="form.subjects"
              mode="tags"
              :options="subjects"
              valueProp="id"
              label="description"
              trackBy="description"
              placeholder="Selecione..."
            />
          </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
          <button type="button" @click="$emit('close')" class="rounded-2xl px-6 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
            Cancelar
          </button>
          <button type="submit" :disabled="loading" class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:hover:scale-100">
            {{ loading ? 'Salvando...' : 'Salvar Livro' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const props = defineProps({
  show: Boolean,
  book: Object,
  authors: Array,
  subjects: Array
});

const emit = defineEmits(['close', 'saved']);
const loading = ref(false);

const form = ref({
  title: '',
  publisher: '',
  edition: '',
  publication_year: '',
  price: '',
  authors: [],
  subjects: []
});

watch(() => props.book, async (newBook) => {
  if (newBook) {
    loading.value = true;
    try {
      // Need to fetch full book details for pivot relations, since the view only has string aggregates
      const response = await axios.get(`/api/books/${newBook.book_id}`);
      const fullBook = response.data;
      form.value = {
        title: fullBook.title,
        publisher: fullBook.publisher,
        edition: fullBook.edition,
        publication_year: fullBook.publication_year,
        price: fullBook.price,
        authors: fullBook.authors.map(a => a.id),
        subjects: fullBook.subjects.map(s => s.id)
      };
    } catch (e) {
      console.error(e);
    } finally {
      loading.value = false;
    }
  } else {
    form.value = { title: '', publisher: '', edition: '', publication_year: '', price: '', authors: [], subjects: [] };
  }
}, { immediate: true });

const submit = async () => {
  loading.value = true;
  try {
    if (props.book && props.book.book_id) {
      await axios.put(`/api/books/${props.book.book_id}`, form.value);
    } else {
      await axios.post('/api/books', form.value);
    }
    emit('saved');
    emit('close');
  } catch (error) {
    console.error(error);
    alert('Erro de validação. Verifique o console.');
  } finally {
    loading.value = false;
  }
};
</script>

<style>
.multiselect {
  --ms-bg: rgba(255, 255, 255, 0.5);
  --ms-border-color: #cbd5e1;
  --ms-border-width: 1px;
  --ms-radius: 1rem;
  --ms-font-size: 0.875rem;
  --ms-dropdown-bg: #ffffff;
  --ms-dropdown-border-color: #cbd5e1;
  --ms-option-bg-pointed: #f1f5f9;
  --ms-option-bg-selected: #6366f1;
  --ms-option-bg-selected-pointed: #6366f1;
  --ms-option-color-selected: #ffffff;
  --ms-tag-bg: #6366f1;
  --ms-tag-color: #ffffff;
  --ms-tag-radius: 0.25rem;
  --ms-tag-font-weight: 600;
  --ms-ring-width: 2px;
  --ms-ring-color: #6366f1;
}

@media (prefers-color-scheme: dark) {
  .multiselect {
    --ms-bg: rgba(30, 41, 59, 0.5);
    --ms-border-color: #475569;
    --ms-dropdown-bg: #1e293b;
    --ms-dropdown-border-color: #475569;
    --ms-option-bg-pointed: #334155;
    --ms-option-color-pointed: #ffffff;
  }
}

.multiselect-tags {
  padding-left: 0.5rem;
}
</style>
