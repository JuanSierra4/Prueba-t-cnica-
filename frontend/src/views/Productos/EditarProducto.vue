<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>Editar Producto</h4>
      </div>
      <div class="card-body">
        <form @submit.prevent="EditarProducto">
           <div class="row">
            <div class="col-md-4 mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" v-model="producto.nombre" required>
            </div>

            <div class="col-md-4 mb-3">
              <label for="precio" class="form-label">Precio</label>
              <input type="text" class="form-control" id="precio" v-model="producto.precio" required>
            </div>

            <div class="col-md-4 mb-3">
              <label for="stock" class="form-label">Stock</label>
              <input type="text" class="form-control" id="stock" v-model="producto.stock" required>
            </div>

          </div>
          
          <div class="d-flex justify-content-end">
            <button type="button" @click="Volver" class="btn btn-secondary me-2" title="Volver"><i
                class="fa-solid fa-angle-left"></i></button>
            <button type="submit" class="btn btn-success" title="Guardar"><i
                class="fa-solid fa-floppy-disk"></i></button>
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

const producto = ref({
  nombre: '',
  precio: '',
  stock: ''
})

onMounted(async () => {
  await TraerDatosProducto(id)
})
const TraerDatosProducto = async (id) => {
  try {
    const response = await api.get(`/productos/${id}`)
    producto.value = response.data
  } catch (error) {
    console.error('Error al obtener los datos del producto:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al obtener los datos del producto',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
    })
  }
}
const EditarProducto = async () => {
  try {
    const response = await api.put(`/productos/${id}`, producto.value)
    Swal.fire({
      icon: 'success',
      title: 'Producto actualizado',
      text: response.data.message
    })
    router.push({ path: '/', query: { tab: 'productos' } })
  } catch (error) {
    console.error('Error al actualizar el producto:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al actualizar el producto',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
    })
  }
}

// Función para volver a la página anterior
const Volver = () => {
  router.push({ path: '/', query: { tab: 'productos' } })
}
</script>