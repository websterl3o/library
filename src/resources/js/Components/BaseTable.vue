<template>
  <div class="custom-datatable-wrapper flex flex-col">
    <EasyDataTable
      ref="dataTableRef"
      :headers="headers"
      :items="items"
      theme-color="#4f46e5"
      table-class-name="vue3-easy-data-table__main"
      header-text-direction="left"
      body-text-direction="left"
      :empty-message="emptyMessage"
      hide-footer
      v-bind="{ ...$attrs, ...tableBindings }"
    >
      <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
        <slot :name="slotName" v-bind="slotProps || {}"></slot>
      </template>
    </EasyDataTable>

    <!-- Custom Footer (3 Colunas) -->
    <div class="custom-table-footer flex items-center justify-between px-6 py-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700/50 rounded-b-3xl">
      
      <!-- Esquerda: Informações de Exibição -->
      <div class="text-sm font-medium text-slate-500 dark:text-slate-400 w-1/3 text-left">
        Exibindo {{ currentFirstItem }} a {{ currentLastItem }} de {{ totalItems }} itens
      </div>

      <!-- Centro: Botões de Paginação -->
      <div class="flex items-center justify-center space-x-1 w-1/3">
        <button 
          @click="prevPage" 
          :disabled="currentPage === 1"
          class="px-2 py-1 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed text-slate-600 dark:text-slate-300 transition-colors"
        >
          &lt;
        </button>
        
        <!-- Mostraremos um máximo de 5 páginas para não quebrar o layout (ex: 1, 2, 3, 4, 5) -->
        <button 
          v-for="page in visiblePages" 
          :key="page"
          @click="goToPage(page)"
          :class="[
            'px-3 py-1 text-sm rounded-lg transition-colors',
            currentPage === page 
              ? 'bg-indigo-600 text-white font-medium' 
              : 'hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300'
          ]"
        >
          {{ page }}
        </button>

        <button 
          @click="nextPage" 
          :disabled="currentPage === totalPages || totalPages === 0"
          class="px-2 py-1 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed text-slate-600 dark:text-slate-300 transition-colors"
        >
          &gt;
        </button>
      </div>

      <!-- Direita: Linhas por página -->
      <div class="flex items-center justify-end space-x-2 text-sm text-slate-500 dark:text-slate-400 w-1/3">
        <span>Linhas por página:</span>
        <select 
          v-model="rowsPerPage"
          @change="goToPage(1)"
          class="bg-transparent border border-slate-300 dark:border-slate-600 rounded-lg px-2 py-1 text-sm text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option :value="10" class="text-slate-900 bg-white dark:text-white dark:bg-slate-800">10</option>
          <option :value="25" class="text-slate-900 bg-white dark:text-white dark:bg-slate-800">25</option>
          <option :value="50" class="text-slate-900 bg-white dark:text-white dark:bg-slate-800">50</option>
          <option :value="100" class="text-slate-900 bg-white dark:text-white dark:bg-slate-800">100</option>
        </select>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';

const props = defineProps({
  headers: {
    type: Array,
    required: true
  },
  items: {
    type: Array,
    required: true
  },
  emptyMessage: {
    type: String,
    default: 'Nenhum registro encontrado.'
  },
  serverItemsLength: {
    type: Number,
    default: undefined
  }
});

const emit = defineEmits(['update-options']);

const dataTableRef = ref(null);
const rowsPerPage = ref(25);
const currentPage = ref(1);

// Estado de ordenação vindo da tabela (quando em Server Mode)
const internalServerOptions = ref({
  page: 1,
  rowsPerPage: 25,
  sortBy: '',
  sortType: ''
});

// Verifica se estamos em Server Mode
const isServerMode = computed(() => props.serverItemsLength !== undefined);

// Computed values
const totalItems = computed(() => isServerMode.value ? props.serverItemsLength : props.items.length);
const totalPages = computed(() => Math.ceil(totalItems.value / rowsPerPage.value) || 1);

const currentFirstItem = computed(() => {
  if (totalItems.value === 0) return 0;
  return ((currentPage.value - 1) * rowsPerPage.value) + 1;
});

const currentLastItem = computed(() => {
  return Math.min(currentPage.value * rowsPerPage.value, totalItems.value);
});

// Dinamicamente liga as props de Server-Side ou Client-Side nativas da tabela
const tableBindings = computed(() => {
  if (isServerMode.value) {
    return {
      'server-items-length': props.serverItemsLength,
      'server-options': internalServerOptions.value,
      'onUpdate:serverOptions': (val) => {
        internalServerOptions.value = val;
        triggerUpdate();
      }
    };
  }
  return {
    'rows-per-page': rowsPerPage.value
  };
});

const triggerUpdate = () => {
  if (isServerMode.value) {
    emit('update-options', {
      page: currentPage.value,
      rowsPerPage: rowsPerPage.value,
      sortBy: internalServerOptions.value.sortBy,
      sortType: internalServerOptions.value.sortType
    });
  }
};

// Lógica de páginas visíveis
const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 5;
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let end = start + maxVisible - 1;

  if (end > totalPages.value) {
    end = totalPages.value;
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

// Ações
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    internalServerOptions.value.page = page;
    internalServerOptions.value.rowsPerPage = rowsPerPage.value;
    
    if (!isServerMode.value && dataTableRef.value && dataTableRef.value.updatePage) {
      dataTableRef.value.updatePage(page);
    }
    
    triggerUpdate();
  }
};

const prevPage = () => goToPage(currentPage.value - 1);
const nextPage = () => goToPage(currentPage.value + 1);

// Client-Side watch: reseta pagina se os itens mudarem e ficarem fora do range
watch(() => props.items, () => {
  if (!isServerMode.value) {
    nextTick(() => {
      if (currentPage.value > totalPages.value && totalPages.value > 0) {
        goToPage(1);
      }
    });
  }
}, { deep: true });

</script>

<style scoped>
/* Remove bordas arredondadas e margin inferiores da tabela principal para grudar com o novo footer */
:deep(.vue3-easy-data-table__main) {
  border-bottom-left-radius: 0 !important;
  border-bottom-right-radius: 0 !important;
  border-bottom: none !important;
}

/* Variáveis Light Mode para a tabela */
:deep(.vue3-easy-data-table__main) {
  border-radius: 1.5rem; /* Tailwind rounded-3xl */
  border-color: #f1f5f9 !important; /* slate-100 */
  
  --easy-table-border: 1px solid #f1f5f9;
  --easy-table-row-border: 1px solid #f1f5f9;
  
  --easy-table-header-font-size: 14px;
  --easy-table-header-height: 50px;
  --easy-table-header-font-color: #475569; /* slate-600 */
  --easy-table-header-background-color: #f8fafc; /* slate-50/50 */
  
  --easy-table-header-item-padding: 10px 15px;
  
  --easy-table-body-even-row-font-color: #0f172a;
  --easy-table-body-even-row-background-color: #fff;
  
  --easy-table-body-row-font-color: #0f172a;
  --easy-table-body-row-background-color: #fff;
  --easy-table-body-row-height: 50px;
  --easy-table-body-row-font-size: 14px;
  
  --easy-table-body-row-hover-font-color: #0f172a;
  --easy-table-body-row-hover-background-color: #f8fafc;
}

/* Variáveis Dark Mode para a tabela */
@media (prefers-color-scheme: dark) {
  :deep(.vue3-easy-data-table__main) {
    border-color: rgba(51, 65, 85, 0.5) !important; /* slate-700/50 */
    background-color: #1e293b !important; /* slate-800 */
    
    --easy-table-border: 1px solid rgba(51, 65, 85, 0.5);
    --easy-table-row-border: 1px solid rgba(51, 65, 85, 0.5);
    
    --easy-table-header-font-color: #cbd5e1; /* slate-300 */
    --easy-table-header-background-color: #1e293b; /* slate-800 */
    
    --easy-table-body-even-row-font-color: #f8fafc;
    --easy-table-body-even-row-background-color: #1e293b;
    
    --easy-table-body-row-font-color: #f8fafc;
    --easy-table-body-row-background-color: #1e293b;
    
    --easy-table-body-row-hover-font-color: #f8fafc;
    --easy-table-body-row-hover-background-color: rgba(51, 65, 85, 0.4); /* slate-700/40 */
  }
}
</style>
