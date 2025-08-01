  <template>
    <div class="container mt-4 fondo-gris">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">Listado de Facturas</h4>
        <div>
          <button class="btn btn-outline-secondary me-2" @click="limpiarFiltros" title="Quitar filtros">
            <i class="fa-solid fa-filter-circle-xmark"></i> Limpiar
          </button>
          <button class="btn btn-outline-success me-2" @click="exportarExcel">
            <i class="fa-solid fa-file-excel"></i> Excel
          </button>
          <button class="btn btn-outline-danger me-2" @click="exportarPDF">
            <i class="fa-solid fa-file-pdf"></i> PDF
          </button>
          <router-link to="/facturas/nueva" class="btn btn-light">
            Nuevo <i class="fa-solid fa-plus"></i>
          </router-link>
        </div>
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
              placeholder="Buscar por número de factura, cliente o producto..." />
          </div>
        </div>
      </div>

      <div v-if="loading">Cargando facturas...</div>
      <div v-else-if="facturas.length === 0">No hay facturas.</div>
      <table v-else class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th @click="ordenarPor('num_factura')">
              #
              <span v-if="campoOrden === 'num_factura'">{{ ordenAsc ? '▲' : '▼' }}</span>
            </th>
            <th @click="ordenarPor('nombres_cliente')">
              Cliente
              <span v-if="campoOrden === 'nombres_cliente'">{{ ordenAsc ? '▲' : '▼' }}</span>
            </th>
            <th @click="ordenarPor('nombre_producto')">
              Producto
              <span v-if="campoOrden === 'nombre_producto'">{{ ordenAsc ? '▲' : '▼' }}</span>
            </th>
            <th @click="ordenarPor('precio_producto_formateado')">
              Precio
              <span v-if="campoOrden === 'precio_producto_formateado'">{{ ordenAsc ? '▲' : '▼' }}</span>
            </th>
            <th @click="ordenarPor('fecha_formateada')">
              Fecha
              <span v-if="campoOrden === 'fecha_formateada'">{{ ordenAsc ? '▲' : '▼' }}</span>
            </th>
            <th>Acciones</th>
          </tr>

        </thead>
        <tbody>
          <tr v-for="factura in facturasPaginadas" :key="factura.num_factura">
            <td>{{ factura.num_factura }}</td>
            <td>{{ factura.nombres_cliente }} {{ factura.apellidos_cliente }}</td>
            <td>{{ factura.nombre_producto }}</td>
            <td>{{ factura.precio_producto_formateado }}</td>
            <td>{{ factura.fecha_formateada }}</td>
            <td>
              <button @click="EditarFactura(factura.num_factura)" class="btn btn-primary btn-sm me-1">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button @click="EliminarFactura(factura.num_factura)" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-trash"></i>
              </button>
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
    import * as XLSX from 'xlsx'
    import { saveAs } from 'file-saver'
    import jsPDF from 'jspdf'
    import autoTable from 'jspdf-autotable'


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
// Ordenamiento

const campoOrden = ref('')
const ordenAsc = ref(true)

const filtros = ref({
  num_factura: '',
  nombres_cliente: '',
  nombre_producto: '',
  precio_producto_formateado: '',
  fecha_formateada: ''
})

const ordenarPor = (campo) => {
  if (campoOrden.value === campo) {
    ordenAsc.value = !ordenAsc.value
  } else {
    campoOrden.value = campo
    ordenAsc.value = true
  }
}
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
  let resultado = facturas.value

  // Filtro por búsqueda general
  if (busqueda.value.trim() !== '') {
    const texto = busqueda.value.toLowerCase()
    resultado = resultado.filter(f =>
      String(f.num_factura).toLowerCase().includes(texto) ||
      `${f.nombres_cliente} ${f.apellidos_cliente}`.toLowerCase().includes(texto) ||
      f.nombre_producto.toLowerCase().includes(texto) ||
      f.precio_producto_formateado.toLowerCase().includes(texto) ||
      f.fecha_formateada.toLowerCase().includes(texto)
    )
  }

  //  Filtro por columnas específicas
  Object.entries(filtros.value).forEach(([campo, valor]) => {
    if (valor.trim() !== '') {
      resultado = resultado.filter(f =>
        String(f[campo]).toLowerCase().includes(valor.toLowerCase())
      )
    }
  })

  //  Ordenamiento
  if (campoOrden.value) {
    resultado = resultado.slice().sort((a, b) => {
      const aVal = a[campoOrden.value]
      const bVal = b[campoOrden.value]
      if (aVal < bVal) return ordenAsc.value ? -1 : 1
      if (aVal > bVal) return ordenAsc.value ? 1 : -1
      return 0
    })
  }

  return resultado
})
// Ir al formulario de editar
const EditarFactura = (num_factura) => {
  router.push(`/facturas/editar/${num_factura}`)
}
// Limpiar filtros
const limpiarFiltros = () => {
  campoOrden.value = '' // Quita el campo de ordenamiento
  ordenAsc.value = true // Reinicia el sentido de orden
  paginaActual.value = 1 // Reinicia la paginación
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
// Exportar a Excel
const exportarExcel = () => {
  const datos = facturasFiltradas.value.map(factura => ({
    'Factura N°': factura.num_factura,
    'Cliente': `${factura.nombres_cliente} ${factura.apellidos_cliente}`,
    'Producto': factura.nombre_producto,
    'Precio': factura.precio_producto_formateado,
    'Fecha': factura.fecha_formateada,
  }))

  const ws = XLSX.utils.json_to_sheet(datos)
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Facturas')

  const excelBuffer = XLSX.write(wb, {
    bookType: 'xlsx',
    type: 'array'
  })
  const blob = new Blob([excelBuffer], {
    type: 'application/octet-stream'
  })
  saveAs(blob, 'facturas.xlsx')
}
// Exportar a PDF
const exportarPDF = () => {
  const doc = new jsPDF()

  const columnas = ['Factura N°', 'Cliente', 'Producto', 'Precio', 'Fecha']
  const filas = facturasFiltradas.value.map(f => [
    f.num_factura,
    `${f.nombres_cliente} ${f.apellidos_cliente}`,
    f.nombre_producto,
    f.precio_producto_formateado,
    f.fecha_formateada
  ])

  autoTable(doc, {
    head: [columnas],
    body: filas,
    startY: 20,
    styles: { fontSize: 10 },
    headStyles: { fillColor: [41, 128, 185] }
  })

  doc.save('facturas.pdf')
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