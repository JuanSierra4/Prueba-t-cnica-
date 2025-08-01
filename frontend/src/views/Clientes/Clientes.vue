  <template>
    <div class="container mt-4 fondo-gris">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">Listado de Clientes</h4>
        <router-link to="/clientes/nuevo" class="btn btn-light">Nuevo <i class="fa-solid fa-plus"></i></router-link>
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
              placeholder="Buscar por nombre, apellido o email..." />
          </div>
        </div>
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
          <tr v-for="cliente in clientesPaginados" :key="cliente.id">
            <td>{{ cliente.id_cliente }}</td>
            <td>{{ cliente.nombre }}</td>
            <td>{{ cliente.apellido }}</td>
            <td>{{ cliente.direccion }}</td>
            <td>{{ cliente.fecha_nacimiento }}</td>
            <td>{{ cliente.telefono }}</td>
            <td>{{ cliente.email }}</td>
            <td>{{ cliente.categoria }}</td>
            <td>
              <button @click="EditarCliente(cliente.id_cliente)" class="btn btn-primary btn-sm me-1"><i
                  class="fa-solid fa-pen-to-square"></i></button>
              <button @click="EliminarCliente(cliente.id_cliente)" class="btn btn-danger btn-sm"><i
                  class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
          Registro {{ inicio + 1 }} - {{ fin }} de {{ clientesFiltrados.length }}
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
    const clientes = ref([])
    const clientesOriginal = ref([])
    const loading = ref(true)
    const busqueda = ref('')
    const paginaActual = ref(1)
    const porPagina = ref(5)

    onMounted(async () => {
      await TraerClientes()
      loading.value = false
    })

    watch(porPagina, () => {
      paginaActual.value = 1
    })

    const inicio = computed(() => {
      return (paginaActual.value - 1) * porPagina.value
    })

    const fin = computed(() => {
      return Math.min(inicio.value + porPagina.value, clientesFiltrados.value.length)
    })

    const totalPaginas = computed(() => {
      return Math.ceil(clientesFiltrados.value.length / porPagina.value)
    })

    const clientesFiltrados = computed(() => {
      const texto = busqueda.value.toLowerCase()
      return clientes.value.filter(c =>
      c.id_cliente.toString().includes(texto) ||
        c.nombre.toLowerCase().includes(texto) ||
        c.apellido.toLowerCase().includes(texto) ||
        c.direccion.toLowerCase().includes(texto) ||
        c.fecha_nacimiento.toLowerCase().includes(texto) ||
        c.telefono.toLowerCase().includes(texto) || 
        c.email.toLowerCase().includes(texto) ||
        c.categoria.toLowerCase().includes(texto)
      )
    })

    const clientesPaginados = computed(() => {
      return clientesFiltrados.value.slice(inicio.value, fin.value)
    })

    const EditarCliente = (id) => {
      router.push(`/clientes/editar/${id}`)
    }

    const TraerClientes = async () => {
      try {
        const response = await api.get('/clientes')
        clientesOriginal.value = response.data
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
<style scoped>
/* Evitar que los contenedores sean editables */
* {
  user-select: none;      
}
input, textarea, select, button {
  user-select: text;
}
</style>