  <template>
    <div class="container mt-4 fondo-gris">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">Listado de Facturas</h4>
        <!-- <router-link to="/facturas/nuevo" class="btn btn-light">Nuevo <i class="fa-solid fa-plus"></i></router-link> -->
      </div>

      <div class="row mb-3 align-items-center">
        
        <div class="col-md-3">
          <div class="input-group">
            <span class="input-group-text">Mostrar</span>
            <select class="form-select" v-model.number="porPagina">
              <option :value="5">5</option>
              <option :value="10">10</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
            <span class="input-group-text">registros</span>
          </div>
        </div>

        <!-- Buscador -->
        <div class="col-md-5 ms-auto">
          <div class="input-group">
            <span class="input-group-text">
              <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input v-model="busqueda" type="text" class="form-control"
              placeholder="Buscar por nombre, apellido o email..." />
          </div>
        </div>
      </div>

      <div v-if="loading">Cargando facturas...</div>
      <div v-else-if="facturas.length === 0">No hay facturas.</div>
      <table v-else class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Numero de Factura</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="factura in facturasPaginadas" :key="factura.num_factura">
            <td>{{ factura.num_factura }}</td>
            <td>{{ factura.fecha }}</td>
            <td>{{ factura.nombres_cliente }} {{  factura.apellidos_cliente }}</td> 
            <td>
              <button @click="EditarFactura(factura.num_factura)" class="btn btn-primary btn-sm me-1"><i
                  class="fa-solid fa-pen-to-square"></i></button>
              <button @click="EliminarFactura(factura.num_factura)" class="btn btn-danger btn-sm"><i
                  class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
          Registro {{ inicio + 1 }} - {{ fin }} de {{ facturasFiltradas.length }}
        </div>
        <nav>
          <ul class="pagination mb-0">
            <li class="page-item" :class="{ disabled: paginaActual === 1 }">
              <button class="page-link" @click="paginaActual--" :disabled="paginaActual === 1">&laquo;</button>
            </li>
            <li class="page-item active">
              <span class="page-link">{{ paginaActual }}</span>
            </li>
            <li class="page-item" :class="{ disabled: paginaActual === totalPaginas }">
              <button class="page-link" @click="paginaActual++"
                :disabled="paginaActual === totalPaginas">&raquo;</button>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </template>

  <script setup>
    import {
      ref, onMounted, watch, computed } from 'vue'
    import api from '@/api/axios'
    import Swal from 'sweetalert2'
    import { useRouter } from 'vue-router'

    const router = useRouter()
    const facturas = ref([])
    const facturasOriginal = ref([])
    const loading = ref(true)
    const busqueda = ref('')
    const paginaActual = ref(1)
    const porPagina = ref(5)

    onMounted(async () => {
      await TraerFacturas()
      loading.value = false
    })

    watch(porPagina, () => {
      paginaActual.value = 1
    })
    // Paginación
    const inicio = computed(() => {
      return (paginaActual.value - 1) * porPagina.value
    })

    const fin = computed(() => {
      return Math.min(inicio.value + porPagina.value, facturasFiltradas.value.length)
    })

    const totalPaginas = computed(() => {
      return Math.ceil(facturasFiltradas.value.length / porPagina.value)
    })

    const facturasPaginadas = computed(() => {
      return facturasFiltradas.value.slice(inicio.value, fin.value)
    })

    //Buscador
    const facturasFiltradas = computed(() => {
      const texto = busqueda.value.toLowerCase()
      return facturas.value.filter(f =>
        f.fecha.toLowerCase().includes(texto) ||
        f.cliente.toLowerCase().includes(texto)
      )
    })

    // Ir al formulario de editar
    const EditarFactura = (num_factura) => {
      router.push(`/facturas/editar/${num_factura}`)
    }

    // Traer facturas desde la API
    const TraerFacturas = async () => {
      try {
        const response = await api.get('/facturas')
        facturasOriginal.value = response.data
        facturas.value = response.data
      } catch (error) {
        console.error('Mensaje del servidor:', error.response.data.message)
        console.error('Error al obtener facturas:', error)
      }
    }

    // Función para eliminar una factura
    const EliminarFactura = async (num_factura) => {
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
          await api.delete(`/facturas/${num_factura}`)
          await Swal.fire({
            icon: 'success',
            title: '¡Eliminado!',
            text: 'La factura ha sido eliminada correctamente.',
            showConfirmButton: false,
            timer: 1500
          });
          // Refresca la lista de facturas
          await TraerFacturas();

        } catch (error) {
          console.error('Error al eliminar factura:', error);
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar la factura.',
          });
        }
      }
    };
  </script>
<style scoped>
/* Evitar que los contenedores sean editables */
* {
  user-select: none;      
}
input, textarea, select, button {
  user-select: text;
}
</style>  