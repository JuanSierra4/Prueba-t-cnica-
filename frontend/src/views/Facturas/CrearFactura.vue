<template>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header text-dark">
                <h5 class="mb-0">Crear Nueva Factura</h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="crearFactura">
                    <div class="row">
                        <!-- Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <select class="form-select" id="cliente" v-model="factura.id_cliente" required>
                                <option value="">Seleccionar Cliente</option>
                                <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id_cliente">
                                    {{ cliente.nombre }} {{ cliente.apellido }}
                                </option>
                            </select>
                        </div>

                        <!-- Producto -->
                        <div class="col-md-6 mb-3">
                            <label for="producto" class="form-label">Producto</label>
                            <select class="form-select" id="producto" v-model="factura.id_producto" required>
                                <option value="">Seleccionar Producto</option>
                                <option v-for="producto in productos" :key="producto.id" :value="producto.id_producto">
                                    {{ producto.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" v-model="factura.fecha" required />
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end">
                        <button type="button" @click="Volver" class="btn btn-outline-secondary me-2" title="Volver">
                            <i class="fa-solid fa-angle-left"></i> 
                        </button>
                        <button type="submit" class="btn btn-success" title="Guardar">
                            <i class="fa-solid fa-floppy-disk"></i> 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'

// Importa SweetAlert2
import Swal from 'sweetalert2'

onMounted(async () => {
  await TraerClientes()
  await TraerProductos()
})

const router = useRouter()

const factura = ref({
  id_cliente: '',
  id_producto: '',
  fecha: ''
})

const clientes = ref([])
const productos = ref([])

const crearFactura = async () => {
  try {
    const response = await api.post('/facturas', factura.value)
    console.log('Factura creada:', response.data)
    // Limpiar el formulario
    factura.value = {
      fecha: '',
      id_cliente: '',
      id_producto: ''
    }
    // Alerta 
    await Swal.fire({
      icon: 'success',
      title: '¡Factura Creada!',
      text: 'La factura ha sido guardada correctamente.',
      showConfirmButton: false,
      timer: 1500
    })
    router.push({
      path: '/',
      query: {
        tab: 'facturas'
      }
    })
  } catch (error) {
    console.error('Error al crear la factura:', error)
    // Alerta de error
    Swal.fire({
      icon: 'error',
      title: '¡Error!',
      text: error.response?.data?.message || 'Ocurrió un error inesperado al crear la factura.',
    })
  }
}

const TraerClientes = async () => {
  try {
    const response = await api.get('/clientes')
    clientes.value = response.data
  } catch (error) {
    console.error('Error al obtener clientes:', error)
  }
}

const TraerProductos = async () => {
  try {
    const response = await api.get('/productos')
    productos.value = response.data
  } catch (error) {
    console.error('Error al obtener productos:', error)
  }
}

// Función para volver a la página anterior
const Volver = () => {
  router.push({ path: '/', query: { tab: 'facturas' } })
}
</script>