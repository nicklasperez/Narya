import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import TabsPage from '../views/TabsPage.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/tabs/home'
  },
  {
    path: '/tabs/',
    component: TabsPage,
    children: [
      {
        path: '',
        redirect: '/tabs/home'
      },
      {
        path: 'home',
        component: () => import('../views/HomePage.vue')
      },
      {
        path: 'explora',
        component: () => import('../views/ExploraPage.vue')
      },
      {
        path: 'nueva',
        component: () => import('../views/NuevaEntradaPage.vue')
      },
      {
        path: 'graficos',
        component: () => import('../views/GraficosPage.vue')
      },
      {
        path: 'ajustes',
        component: () => import('../views/AjustesPage.vue')
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

export default router;
