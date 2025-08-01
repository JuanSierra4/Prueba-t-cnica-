<template>
  <div class="container mt-4">
    <div class="card shadow-sm">
      <div class="card-header text-dark">
        <h5 class="mb-0">Crear nuevo Producto</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="crearProducto">
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
              <label for="direccion" class="form-label">Stock</label>
              <input type="text" class="form-control" id="direccion" v-model="producto.stock" required>
            </div>

          </div>

          <div class="d-flex justify-content-end">
            <button type="button" @click="Volver" class="btn btn-outline-secondary me-2" title="Volver">
              <i class="fa-solid fa-angle-left"></i>
            </button>
            <button type="submit" class="btn btn-success" title="Guardar"><i class="fa-solid fa-floppy-disk"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'

// Importa SweetAlert2
import Swal from 'sweetalert2'

const router = useRouter()

const producto = ref({
  nombre: '',
  precio: '',
  stock: ''
})

const crearProducto = async () => {
  try {
    const response = await api.post('/productos', producto.value)
    console.log('Producto creado:', response.data)
    // Limpiar el formulario
    producto.value = {
      nombre: '',
      precio: '',
      stock: ''
    }
    // Alerta 
    await Swal.fire({
      icon: 'success',
      title: '¡Producto Creado!',
      text: 'El producto ha sido guardado correctamente.',
      showConfirmButton: false,
      timer: 1500
    })
    router.push({ path: '/', query: { tab: 'productos' } })
  } catch (error) {
    console.error('Error al crear el cliente:', error)
    // Alerta de error
    Swal.fire({
      icon: 'error',
      title: '¡Error!',
      text: 'Hubo un problema al crear el cliente. Por favor, inténtalo de nuevo.',
    })
  }
}

// Función para volver a la página anterior
const Volver = () => {
  router.push({ path: '/', query: { tab: 'productos' } })
}
</script>