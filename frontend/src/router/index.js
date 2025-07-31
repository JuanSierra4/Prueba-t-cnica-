import { createRouter, createWebHistory } from 'vue-router'
import Facturas from '../views/Facturas.vue'
import Panel from '../views/Panel.vue'

const routes = [
  {
    path: '/',
    name: 'panel',
    component: Panel
  },
  // {
  //   path: '/facturas',
  //   name: 'facturas',
  //   component: Facturas
  // },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
