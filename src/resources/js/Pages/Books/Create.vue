<template>
  <AppLayout>
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden max-w-3xl mx-auto">
      <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-700/50">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Novo Livro</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Preencha os detalhes para cadastrar um novo livro no acervo</p>
      </div>

      <div class="p-6 sm:p-8">
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
              <CurrencyInput v-model="form.price" class="mt-1 block w-full rounded-2xl border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all px-4 py-3 text-slate-900 dark:text-white" required />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="min-w-0">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 ml-1 mb-1">Autores</label>
              <Multiselect
                v-model="form.authors"
                mode="tags"
                :options="authorsList"
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
                :options="subjectsList"
                valueProp="id"
                label="description"
                trackBy="description"
                placeholder="Selecione..."
              />
            </div>
          </div>

          <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-slate-100 dark:border-slate-700/50">
            <Link href="/books" class="rounded-2xl px-6 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
              Cancelar
            </Link>
            <button type="submit" :disabled="loading" class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:hover:scale-100">
              {{ loading ? 'Salvando...' : 'Salvar Livro' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import AppLayout from '../../Layouts/AppLayout.vue';
import CurrencyInput from '../../Components/CurrencyInput.vue';

const loading = ref(false);
const authorsList = ref([]);
const subjectsList = ref([]);

const form = ref({
  title: '',
  publisher: '',
  edition: '',
  publication_year: '',
  price: '',
  authors: [],
  subjects: []
});

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

const submit = async () => {
  loading.value = true;
  try {
    await axios.post('/api/books', form.value);
    router.visit('/books'); // Redirect back to list
  } catch (error) {
    console.error(error);
    alert('Erro de validação. Verifique o console.');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDependencies();
});
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
