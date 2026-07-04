<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300 flex">
    <!-- Mobile Sidebar Backdrop -->
    <div
      v-if="isMobileMenuOpen"
      class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
      @click="isMobileMenuOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700/50 shadow-sm transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-700/50">
        <div class="flex items-center gap-3">
          <div class="h-8 w-8 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
            L
          </div>
          <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300">
            Library
          </span>
        </div>
        <button class="lg:hidden text-slate-500 dark:text-slate-400" @click="isMobileMenuOpen = false">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-4rem)]">
        <!-- Dashboard -->
        <Link
          href="/"
          class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-colors"
          :class="$page.url === '/' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          Dashboard
        </Link>

        <!-- Livros -->
        <div class="space-y-1">
          <button
            @click="toggleMenu('books')"
            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white transition-colors"
          >
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
              Livros
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMenus.books }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.books" class="pl-11 pr-4 py-1 space-y-1">
            <Link href="/books" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/books' }">Listagem</Link>
            <Link href="/books/create" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/books/create' }">Criar Novo</Link>
          </div>
        </div>

        <!-- Autores -->
        <div class="space-y-1">
          <button
            @click="toggleMenu('authors')"
            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white transition-colors"
          >
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
              Autores
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMenus.authors }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.authors" class="pl-11 pr-4 py-1 space-y-1">
            <Link href="/authors" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/authors' }">Listagem</Link>
            <Link href="/authors/create" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/authors/create' }">Criar Novo</Link>
          </div>
        </div>

        <!-- Assuntos -->
        <div class="space-y-1">
          <button
            @click="toggleMenu('subjects')"
            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white transition-colors"
          >
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
              Assuntos
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMenus.subjects }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div v-show="openMenus.subjects" class="pl-11 pr-4 py-1 space-y-1">
            <Link href="/subjects" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/subjects' }">Listagem</Link>
            <Link href="/subjects/create" class="block py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400" :class="{ 'text-indigo-600 dark:text-indigo-400 font-medium': $page.url === '/subjects/create' }">Criar Novo</Link>
          </div>
        </div>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Mobile Header -->
      <header class="lg:hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl sticky top-0 z-30 border-b border-slate-200 dark:border-slate-700/50">
        <div class="px-4 py-3 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-8 w-8 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
              L
            </div>
            <span class="text-xl font-bold text-slate-900 dark:text-white">Library</span>
          </div>
          <button class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" @click="isMobileMenuOpen = true">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <slot />
      </main>

      <footer class="border-t border-slate-200 dark:border-slate-700/50">
        <div class="p-4 sm:p-6 lg:p-8 text-center">
          <p class="text-sm text-slate-500 dark:text-slate-400">© 2026 Library. Todos os direitos reservados.</p>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const isMobileMenuOpen = ref(false)

const openMenus = ref({
  books: false,
  authors: false,
  subjects: false
})

const toggleMenu = (menu) => {
  openMenus.value[menu] = !openMenus.value[menu]
}

onMounted(() => {
  const url = page.url
  if (url.startsWith('/books')) openMenus.value.books = true
  if (url.startsWith('/authors')) openMenus.value.authors = true
  if (url.startsWith('/subjects')) openMenus.value.subjects = true
})
</script>
