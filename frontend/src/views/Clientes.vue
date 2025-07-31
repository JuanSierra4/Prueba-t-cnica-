<template>
  <div class="container mt-4 fondo-gris">
    <h3 class="mb-4">Listado de Clientes</h3>

    <div v-if="loading">Cargando clientes...</div>
    <div v-else-if="clientes.length === 0">No hay clientes.</div>
    <table v-else class="table table-striped table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Apellido</th>
          <th>Dirección</th>
          <th>Fecha de Nacimiento</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Categoría</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cliente in clientes" :key="cliente.id">
          <td>{{ cliente.id_cliente }}</td>
          <td>{{ cliente.nombre }}</td>
          <td>{{ cliente.apellido }}</td>
          <td>{{ cliente.direccion }}</td>
          <td>{{ cliente.fecha_nacimiento }}</td>
          <td>{{ cliente.telefono }}</td>
          <td>{{ cliente.email }}</td>
          <td>{{ cliente.categoria }}</td>
          <td>
            <button class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></button>
            <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios' // ← Asegúrate de tener este archivo configurado

const clientes = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await api.get('/clientes')
    clientes.value = response.data
  } catch (error) {
    console.error('Error al obtener clientes:', error)
  } finally {
    loading.value = false
  }
})
</script>