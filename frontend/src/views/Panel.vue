<template>
  <div class="center-wrapper">
    <div class="panel-container">
      <ul class="nav nav-tabs fixed-top bg-white border-bottom shadow-sm">
        <li class="nav-item">
          <a
            class="nav-link"
            :class="{ active: activeTab === 'clientes' }"
            href="#"
            @click.prevent="activeTab = 'clientes'"
          >
            Clientes
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            :class="{ active: activeTab === 'productos' }"
            href="#"
            @click.prevent="activeTab = 'productos'"
          >
            Productos
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            :class="{ active: activeTab === 'facturas' }"
            href="#"
            @click.prevent="activeTab = 'facturas'"
          >
            Facturas
          </a>
        </li>
      </ul>

      <div class="tab-content mt-2">
        <div v-if="activeTab === 'clientes'">
          <Clientes />
        </div>
        <div v-if="activeTab === 'productos'">
          <Productos />
        </div>
        <div v-if="activeTab === 'facturas'">
          <Facturas />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted  } from 'vue'
import { useRoute } from 'vue-router'

import Clientes from '@/views/Clientes/Clientes.vue'
import Productos from '@/views/Productos/Productos.vue'
import Facturas from '@/views/Facturas/Facturas.vue'

const activeTab = ref('clientes') // valor por defecto
const route = useRoute()

onMounted(() => {
  if (route.query.tab) {
    activeTab.value = route.query.tab // corregido
  }
})
</script>

<style scoped>
.center-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* ocupa toda la altura de la ventana */
  background-color: #e0e0e0;
}

.panel-container {
  height: 90vh;
  width: 90vw;
  background-color: #f0efef;
  overflow-y: auto;
  padding: 1rem;
  box-sizing: border-box;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
}
</style>
<style scoped>
.table {
  margin-top: 20px;
}
.fondo-gris {
  background-color: #d5d6d6ce; 
  padding: 1.5rem;
  border-radius: 0.5rem;
}

</style>