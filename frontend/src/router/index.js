import { createRouter, createWebHistory } from 'vue-router'
//Componente panel
import Panel from '../views/Panel.vue'
// Crear cliente
import CrearCliente from '../views/Clientes/CrearCliente.vue'
// Editar cliente
import EditarCliente from '../views/Clientes/EditarCliente.vue'
const routes = [
  {
    path: '/',
    name: 'panel',
    component: Panel
  },
  {
    path: '/clientes/nuevo',
    name: 'crear-cliente',
    component: CrearCliente
  },
  {
    path: '/clientes/editar/:id',
    name: 'editar-cliente',
    component: EditarCliente
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
