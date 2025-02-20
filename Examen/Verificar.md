# Lista de Verificación para Examen de Despliegue

## 1. Lectura Inicial del Enunciado ✍️

### 1.1 Identificar y Anotar Elementos Personalizados
- [ ] Nombre del proyecto
- [ ] Prefijo/sufijo para Dockerfiles (ejemplo: DockerfileServicioXXX)
- [ ] Nombre del controlador de backend
- [ ] Nombre de la tabla de base de datos
- [ ] Nombre de las columnas de la tabla
- [ ] Credenciales de base de datos solicitadas
- [ ] Mensaje personalizado requerido
- [ ] Datos para el certificado SSL

### 1.2 Identificar Requisitos Especiales
- [ ] Rutas específicas del proxy inverso
- [ ] Puertos a exponer
- [ ] Servicios adicionales requeridos
- [ ] Configuraciones especiales de Docker

## 2. Preparación de la Estructura ️📁

### 2.1 Crear Estructura de Carpetas
- [ ] Crear carpeta raíz del proyecto
- [ ] Crear carpeta frontend/
- [ ] Crear carpeta backend/
- [ ] Crear carpeta web/
- [ ] Crear carpeta web/certs/
- [ ] Crear carpeta database/

### 2.2 Crear Archivos de Configuración Base
- [ ] .gitignore en la raíz
- [ ] .gitignore en web/certs
- [ ] .env.template
- [ ] README.md con tu nombre y descripción

## 3. Configuración Frontend ⚛️

### 3.1 Instalación y Configuración Básica
- [ ] Crear proyecto React con Vite
- [ ] Modificar App.jsx con nombre personalizado
- [ ] Actualizar mensaje de loading personalizado
- [ ] Configurar llamada a API backend

### 3.2 Configuración Docker
- [ ] Crear DockerfileFrontendXXX con nombre correcto
- [ ] Verificar puerto expuesto (5173)
- [ ] Configurar variables de entorno si necesario

## 4. Configuración Backend 🔧

### 4.1 Instalación y Configuración Básica
- [ ] Crear proyecto Symfony
- [ ] Crear controlador con nombre especificado
- [ ] Crear entidad con nombre especificado
- [ ] Configurar conexión a base de datos

### 4.2 Configuración Docker
- [ ] Crear DockerfileBackendXXX con nombre correcto
- [ ] Verificar puerto expuesto (8000)
- [ ] Configurar variables de entorno

## 5. Configuración Base de Datos 💾

### 5.1 Preparación
- [ ] Crear archivo init.sql
- [ ] Definir tabla con nombre correcto
- [ ] Definir columnas con nombres correctos
- [ ] Añadir INSERT con mensaje personalizado

### 5.2 Configuración
- [ ] Crear .env con credenciales correctas
- [ ] Verificar nombre de base de datos
- [ ] Verificar usuario y contraseña
- [ ] Comprobar variables en docker-compose

## 6. Configuración SSL y Proxy Inverso 🔒

### 6.1 Certificados
- [ ] Crear entrypoint.sh
- [ ] Configurar datos personales en el certificado
- [ ] Verificar permisos de ejecución

### 6.2 Nginx
- [ ] Crear default.conf
- [ ] Configurar redirección HTTP a HTTPS
- [ ] Configurar rutas de proxy inverso
- [ ] Añadir configuración para PHPMyAdmin

### 6.3 Docker
- [ ] Crear DockerfileWebXXX
- [ ] Exponer puertos 80 y 443
- [ ] Configurar volumen para certificados

## 7. Docker Compose 🐳

### 7.1 Servicios
- [ ] Configurar servicio frontend
- [ ] Configurar servicio backend
- [ ] Configurar servicio web (nginx)
- [ ] Configurar servicio base de datos
- [ ] Configurar servicio PHPMyAdmin

### 7.2 Redes y Volúmenes
- [ ] Definir red con nombre correcto
- [ ] Configurar volumen para BD
- [ ] Configurar volumen para certificados

## 8. Despliegue y Verificación 🚀

### 8.1 Despliegue Local
- [ ] Copiar .env.template a .env
- [ ] Ejecutar docker-compose build
- [ ] Ejecutar docker-compose up
- [ ] Verificar todos los servicios

### 8.2 Pruebas
- [ ] Acceder al frontend (https://localhost)
- [ ] Verificar mensaje personalizado
- [ ] Probar API backend (/api/xxx)
- [ ] Acceder a PHPMyAdmin
- [ ] Verificar conexión a base de datos

### 8.3 Git
- [ ] Inicializar repositorio
- [ ] Verificar .gitignore
- [ ] Hacer commit inicial
- [ ] Subir a GitHub

### 8.4 Despliegue AWS (si requerido)
- [ ] Clonar repositorio
- [ ] Crear .env
- [ ] Actualizar IP en configuración
- [ ] Ejecutar docker-compose
- [ ] Verificar acceso externo

## 9. Revisión Final 🔍

### 9.1 Validar Requisitos
- [ ] Nombres de archivos correctos
- [ ] Mensajes personalizados
- [ ] Credenciales correctas
- [ ] Puertos correctos

### 9.2 Validar Funcionamiento
- [ ] Frontend muestra mensaje correcto
- [ ] Backend responde correctamente
- [ ] Base de datos contiene datos correctos
- [ ] SSL funciona correctamente
- [ ] PHPMyAdmin accesible

### 9.3 Validar Entrega
- [ ] README actualizado
- [ ] Código en GitHub
- [ ] URLs de acceso funcionando
- [ ] Documentación completa