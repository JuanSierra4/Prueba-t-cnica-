# Prueba Técnica - Sistema de Gestión de Clientes, Productos y Facturas

Este proyecto es una prueba técnica desarrollada para una empresa, que incluye un backend en **Laravel** y un frontend en **Vue.js 3**. El sistema permite la gestión de clientes, productos y facturas, incluyendo funcionalidades como creación, edición, eliminación, búsqueda, ordenamiento, paginación y exportación.

---

## 🛠️ Tecnologías utilizadas

### Backend:
- PHP 8+
- Laravel 10+
- MySQL

### Frontend:
- Vue 3 (Options API)
- Bootstrap 5
- Axios
- SweetAlert2
- jsPDF / jsPDF-autotable
- xlsx + file-saver (para exportar Excel)

---

## 📁 Estructura del proyecto
Prueba tecnica/
├── backend/ → Proyecto Laravel
├── frontend/ → Proyecto Vue 3
└── README.md → Este archivo


## 🚀 Instrucciones para ejecutar el proyecto

### 1. Clonar el repositorio


```bash
git clone https://github.com/JuanSierra4/Prueba-t-cnica-.git
cd nombre-repo
8

### 2. Configurar el Backend (Laravel)
cd backend
cp .env.example .env
composer install
php artisan key:generate

Abre el archivo .env y asegúrate de tener configurado tu entorno de base de datos:
DB_DATABASE=prueba_tecnica
DB_USERNAME=root
DB_PASSWORD=tu_contraseña

No olvides crear el archivo .env en el backend, puedes crearlo con base en el archivo .env.example.

Luego ejecuta las migraciones:
php artisan migrate 
php artisan serve

### 3. Configurar el Frontend (Vue 3)
cd ../frontend
npm install
npm run dev
La aplicación frontend va a correr en http://localhost:5173, no en el puerto de laravel.

