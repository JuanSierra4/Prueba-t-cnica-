import { createRouter, createWebHistory } from 'vue-router'
//Componente panel
import Panel from '../views/Panel.vue'
// Crear cliente
import CrearCliente from '../views/Clientes/CrearCliente.vue'
// Editar cliente
import EditarCliente from '../views/Clientes/EditarCliente.vue'
// Crear factura
import CrearFactura from '../views/Facturas/CrearFactura.vue'
// Editar factura
import EditarFactura from '../views/Facturas/EditarFactura.vue'
// Crear producto
import CrearProducto from '../views/Productos/CrearProducto.vue'
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
  },
  {
    path: '/facturas/nueva',
    name: 'crear-factura',
    component: CrearFactura
  },
  {
    path: '/facturas/editar/:id',
    name: 'editar-factura',
    component: EditarFactura  
  },
  {
    path: '/productos/nuevo',
    name: 'crear-producto',
    component: CrearProducto
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
