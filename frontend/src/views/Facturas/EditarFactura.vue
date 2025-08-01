<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Editar Factura</h4>
            </div>
            <div class="card-body">
                <form @submit.prevent="EditarFactura">
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

const factura = ref({
  id_cliente: '',
  id_producto: '',
  fecha: ''
})
const clientes = ref([])
const productos = ref([])
onMounted(async () => {
  await TraerDatosFactura(id)
  await TraerClientes()
  await TraerProductos()
})
const TraerDatosFactura = async (id) => {
  try {
    const response = await api.get(`/facturas/${id}`)
    factura.value = response.data
  } catch (error) {
    console.error('Error al obtener los datos de la factura:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al obtener los datos de la factura',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
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

const EditarFactura = async () => {
  try {
    const response = await api.put(`/facturas/${id}`, factura.value)
    Swal.fire({
      icon: 'success',
      title: 'Factura actualizada',
      text: response.data.message
    })
    router.push({ path: '/', query: { tab: 'facturas' } })
  } catch (error) {
    console.error('Error al actualizar la factura:', error)
    Swal.fire({
      icon: 'error',
      title: 'Error al actualizar la factura',
      text: error.response.data.message || 'Ocurrió un error inesperado.'
    })
  }
}

// Función para volver a la página anterior
const Volver = () => {
  router.push({ path: '/', query: { tab: 'facturas' } })
}
</script>