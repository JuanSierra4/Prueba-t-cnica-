  <template>
    <div class="container mt-4 fondo-gris">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">Listado de Productos</h4>
        <router-link to="/productos/nuevo" class="btn btn-light">Nuevo <i class="fa-solid fa-plus"></i></router-link>
      </div>

      <div class="row mb-3 align-items-center">
        <!-- Select Mostrar Registros -->
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
              placeholder="Buscar por nombre, precio o stock..." />
          </div>
        </div>
      </div>

      <div v-if="loading">Cargando productos...</div>
      <div v-else-if="productos.length === 0">No hay productos.</div>
      <table v-else class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in productosPaginados" :key="producto.id_producto">
            <td>{{ producto.id_producto }}</td>
            <td>{{ producto.nombre }}</td>
            <td>{{ producto.precio_formateado }}</td>
            <td>{{ producto.stock }}</td>
            <td>
              <button @click="EditarProducto(producto.id_producto)" class="btn btn-primary btn-sm me-1"><i
                  class="fa-solid fa-pen-to-square"></i></button>
              <button @click="EliminarProducto(producto.id_producto)" class="btn btn-danger btn-sm"><i
                  class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
          Registro {{ inicio + 1 }} - {{ fin }} de {{ productosFiltrados.length }}
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
    const productos = ref([])
    const productosOriginal = ref([])
    const loading = ref(true)
    const busqueda = ref('')
    const paginaActual = ref(1)
    const porPagina = ref(5)

    onMounted(async () => {
      await TraerProductos()
      loading.value = false
    })

    watch(porPagina, () => {
      paginaActual.value = 1
    })

    const inicio = computed(() => {
      return (paginaActual.value - 1) * porPagina.value
    })

    const fin = computed(() => {
      return Math.min(inicio.value + porPagina.value, productosFiltrados.value.length)
    })

    const totalPaginas = computed(() => {
      return Math.ceil(productosFiltrados.value.length / porPagina.value)
    })

    const productosFiltrados = computed(() => {
      const texto = busqueda.value.toLowerCase()
      return productos.value.filter(p =>
        p.id_producto.toString().includes(texto) ||
        p.nombre.toLowerCase().includes(texto) ||
        p.precio.toLowerCase().includes(texto) ||
        p.stock.toString().includes(texto)
      )
    })

    const productosPaginados = computed(() => {
      return productosFiltrados.value.slice(inicio.value, fin.value)
    })

    const EditarProducto = (id) => {
      router.push(`/productos/editar/${id}`)
    }

    const TraerProductos = async () => {
      try {
        const response = await api.get('/productos')
        productosOriginal.value = response.data
        productos.value = response.data
      } catch (error) {
        console.error('Error al obtener productos:', error)
      }
    }

    // Función para eliminar un producto
    const EliminarProducto = async (id) => {
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
          await api.delete(`/productos/${id}`)
          await Swal.fire({
            icon: 'success',
            title: '¡Eliminado!',
            text: 'El producto ha sido eliminado correctamente.',
            showConfirmButton: false,
            timer: 1500
          });
          // Refresca la lista de productos
          await TraerProductos();

        } catch (error) {
          console.error('Error al eliminar producto:', error);
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar el producto.',
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