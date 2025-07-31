<template>
  <div class="container mt-4 fondo-gris">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Listado de Clientes</h4>
      <router-link to="/clientes/nuevo" class="btn btn-light">Nuevo <i class="fa-solid fa-plus"></i></router-link>
    </div>

    <div v-if="loading">Cargando clientes...</div>
    <div v-else-if="clientes.length === 0">No hay clientes.</div>
    <table v-else class="table table-striped table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
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
            <button @click="EditarCliente(cliente.id_cliente)" class="btn btn-primary btn-sm me-1"><i class="fa-solid fa-pen-to-square"></i></button>
            <button @click="EliminarCliente(cliente.id_cliente)" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios' 
// Importa SweetAlert2
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router' // 👈 importar el router
const router = useRouter()
const clientes = ref([])
const loading = ref(true)

onMounted(async () => {
  await TraerClientes()
  loading.value = false
})

const EditarCliente = (id) => {
  router.push(`/clientes/editar/${id}`)
}
const TraerClientes = async () => {
  try {
    const response = await api.get('/clientes')
    clientes.value = response.data
  } catch (error) {
    console.error('Error al obtener clientes:', error)
  }
}

// Función para eliminar un cliente
const EliminarCliente = async (id) => {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/clientes/${id}`)
      await Swal.fire({
        icon: 'success',
        title: '¡Eliminado!',
        text: 'El cliente ha sido eliminado correctamente.',
        showConfirmButton: false,
        timer: 1500
      });
      // Refresca la lista de clientes
      await TraerClientes();

    } catch (error) {
      console.error('Error al eliminar cliente:', error);
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al eliminar el cliente.',
      });
    }
  }
};

</script>