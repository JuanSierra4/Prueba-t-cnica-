<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>Editar Cliente</h4>
      </div>
      <div class="card-body">
        <form @submit.prevent="EditarCliente">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" v-model="cliente.nombre" required>
          </div>

          <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" v-model="cliente.apellido" required>
          </div>

          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" v-model="cliente.direccion" required>
          </div>

          <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" v-model="cliente.fecha_nacimiento" required>
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" v-model="cliente.telefono" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" v-model="cliente.email" required>
          </div>

          <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <input type="text" class="form-control" id="categoria" v-model="cliente.categoria" required>
          </div>
          
          <div class="d-flex justify-content-end">
            <button type="button" @click="Volver" class="btn btn-secondary me-2" title="Volver"><i class="fa-solid fa-angle-left"></i></button>
            <button type="submit" class="btn btn-success" title="Guardar"><i class="fa-solid fa-floppy-disk"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '@/api/axios'

// Importa SweetAlert2
import Swal from 'sweetalert2'

const router = useRouter()
const route = useRoute()
const id = route.params.id

const cliente = ref({
  nombre: '',
  apellido: '',
  direccion: '',
  fecha_nacimiento: '',
  telefono: '',
  email: '',
  categoria: ''
})
onMounted(async () => {
  await TraerDatosCliente(id)
})
const TraerDatosCliente = async (id) => {
  try {
    const response = await api.get(`/clientes/${id}`)
    cliente.value = response.data
  } catch (error) {
    console.error('Error al obtener los datos del cliente:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al obtener los datos del cliente',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
    })
  }
}
const EditarCliente = async () => {
  try {
    const response = await api.put(`/clientes/${id}`, cliente.value)
    Swal.fire({
      icon: 'success',
      title: 'Cliente actualizado',
      text: response.data.message
    })
    router.push('/')
  } catch (error) {
    console.error('Error al actualizar el cliente:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al actualizar el cliente',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
    })
  }
}

// Función para volver a la página anterior
const Volver = () => {
  router.back()
}
</script>