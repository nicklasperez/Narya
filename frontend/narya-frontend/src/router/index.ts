import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import TabsPage from '../views/TabsPage.vue';
import { isAuthenticated } from '@/utils/auth';

// Definimos todas las rutas de la aplicación
const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/welcome' // Redirección desde la raíz al inicio tipo bienvenida
  },
  {
    path: '/welcome',
    component: () => import('../views/WelcomePage.vue') // Página de bienvenida libre
  },

  // 🔐 Rutas públicas (no requieren autenticación)
  {
    path: '/login',
    component: () => import('../views/LoginPage.vue') // Página de login
  },
  {
    path: '/register',
    component: () => import('../views/RegisterPage.vue') // Página de registro
  },
  {
  path: '/perfil/:id',
  component: () => import('../views/PerfilPublico.vue') // Vista pública de perfil de usuario
  },


  // 🌟 Ruta principal de la app con estructura en pestañas (tabs)
  {
    path: '/tabs/',
    component: TabsPage, // Componente que contiene los tabs
    children: [
      { path: '', redirect: '/tabs/home' }, // Si se accede a /tabs/, redirige al tab home

      // Rutas internas de cada tab (ya protegidas con beforeEach más abajo)
      { path: 'home', component: () => import('../views/HomePage.vue') },
      { path: 'explora', component: () => import('../views/ExploraPage.vue') },
      { path: 'nueva', component: () => import('../views/NuevaEntradaPage.vue') },
      { path: 'graficos', component: () => import('../views/GraficosPage.vue') },
      { path: 'ajustes', component: () => import('../views/AjustesPage.vue') },
      { path: 'perfil/:id', component: () => import('../views/PerfilPublico.vue') },
      {path: '/followers/:id', component: () => import('@/views/FollowersPage.vue')},
      {path: '/following/:id', component: () => import('@/views/FollowingPage.vue')}
    ]
  }
];

// Creamos la instancia del router usando historial basado en URLs normales
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});


// 🚧 PROTECCIÓN GLOBAL DE RUTAS
// Este guard se ejecuta antes de cada navegación de ruta
router.beforeEach((to, from, next) => {
  // Rutas que no requieren autenticación (públicas)
  const publicPages = ['/login', '/register', '/welcome'];

  // Si la ruta a la que vamos NO está en la lista de rutas públicas, entonces requiere login
  const authRequired = !publicPages.includes(to.path);

  // Si se requiere autenticación y el usuario NO está logueado (sin token), lo mandamos al login
  if (authRequired && !isAuthenticated()) {
    return next('/login');
  }

  // Si el usuario ya está logueado e intenta entrar a /login o /register, lo mandamos a /tabs/home
  if ((to.path === '/login' || to.path === '/register') && isAuthenticated()) {
    return next('/tabs/home');
  }

  // Si todo está bien, seguimos con la navegación normal
  next();
});

// Exportamos la instancia del router para usarla en la app principal
export default router;
