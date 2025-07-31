<template>
  <div class="container mt-4 fondo-gris">
    <h2 class="mb-4">Listado de Facturas</h2>

    <div v-if="loading">Cargando facturas...</div>
    <div v-else-if="facturas.length === 0">No hay facturas.</div>
    <table v-else class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Total</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="factura in facturas" :key="factura.id">
          <td>{{ factura.id }}</td>
          <td>{{ factura.cliente }}</td>
          <td>{{ factura.total }}</td>
          <td>{{ factura.fecha }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios' // ← Asegúrate de tener este archivo configurado

const facturas = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await api.get('/facturas')
    facturas.value = response.data
  } catch (error) {
    console.error('Error al obtener facturas:', error)
  } finally {
    loading.value = false
  }
})
</script>
<style scoped>
.table {
  margin-top: 20px;
}
.fondo-gris {
  background-color: #b2b3b463; 
  padding: 1.5rem;
  border-radius: 0.5rem;
}

</style>