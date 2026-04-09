# Laravel ERP - Docker

## Requisitos Previos

- Docker Desktop instalado y ejecutándose
- Puerto 8000 disponible

## Instalación Inicial (Primera vez)

### 1. Clonar el proyecto

```bash
git clone <repo-url>
cd ERP_node
```

### 2. Eliminar volumen anterior (si existe) y levantar contenedores

```bash
docker-compose down -v
docker-compose up -d --build
```

### 3. Instalar dependencias Composer

```bash
docker exec laravel_app composer install
```

### 4. Generar APP_KEY
Antes de crear la Key: Primero configurar el .env
```bash
docker exec laravel_app php artisan key:generate
```

## Importante:

Ejecutar:      --->Ambas estando dentro de la carpeta src que es de laravel
npm install
npm run build 

### 5. Ejecutar migraciones

Las migraciones crean automáticamente las 22 tablas del ERP en la base de datos `erp_node`.

```bash
docker exec laravel_app php artisan migrate
```

### 6. Corregir permisos de storage

```bash
docker exec laravel_app chmod -R 777 storage
```

### 7. Limpiar cache

```bash
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan cache:clear
```

## Iniciar el Proyecto (Ya configurado)

```bash
docker-compose up -d
```

## Acceder a la Aplicación

| Servicio | URL |
|----------|-----|
| **Web Laravel** | http://localhost:8000 |
| **phpMyAdmin** | http://localhost:8080 |

### Credenciales phpMyAdmin

- **Servidor:** db
- **Usuario:** root
- **Password:** root

## Base de Datos

- **Tablas:** 22 tablas mediante migraciones Laravel
- **Base de datos:** `erp_node`
- **Usuario autenticación:** Tabla `empleados` (con campos password, rol, remember_token)

## Roles de Usuario

| Rol | Descripción |
|-----|-------------|
| `admin` | Acceso completo a todos los módulos |
| `vendedor` | Acceso a Ventas, Inventarios y Producción |
| `contador` | Acceso a Finanzas |
| `rrhh` | Acceso a Gestión de Personal |

## Estructura de Tablas

| Módulo | Tablas |
|--------|--------|
| **RRHH** | empleados, puestos, contratos, nominas |
| **CRM/Ventas** | clientes, proyectos, asignaciones, ordenes, detalle_orden |
| **Inventario** | almacenes, productos, categorias, proveedores, inventario, ordenes_compra, detalle_orden_compra |
| **Finanzas** | facturas, pagos |
| **Contabilidad** | periodos, asientos, cuenta_contable, asiento_detalle |

## Comandos Útiles

| Comando | Descripción |
|---------|-------------|
| `docker-compose up -d --build` | Iniciar y construir contenedores |
| `docker-compose down` | Detener contenedores |
| `docker-compose down -v` | Detener y eliminar volúmenes (reiniciar DB) |
| `docker-compose restart` | Reiniciar todos los contenedores |
| `docker exec laravel_app bash` | Acceder al contenedor PHP |
| `docker exec laravel_db mysql -uroot -proot` | Acceder a MySQL |
| `docker-compose logs -f` | Ver logs de todos los servicios |
| `docker exec laravel_app php artisan migrate` | Ejecutar migraciones |
| `docker exec laravel_app php artisan migrate:fresh` | Recrear todas las tablas |

## Contenedores

| Servicio | Contenedor | Puerto |
|----------|------------|--------|
| PHP-FPM | laravel_app | 9000 |
| Nginx | laravel_nginx | 8000 |
| MySQL | laravel_db | 3306 |
| phpMyAdmin | laravel_phpmyadmin | 8080 |

## Notas

- Los datos de MySQL persisten en un volumen Docker (`dbdata`)
- Si ves error 504, intenta: `docker-compose restart`
- Si ves error 500, verifica los logs: `docker-compose logs -f app`
