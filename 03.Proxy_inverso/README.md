# Proyecto React-Symfony con SSL

## Configuración Inicial

1. Crear archivo .env en la raíz del proyecto con las siguientes variables:
   
```plaintext
# Configuración de la base de datos
MYSQL_ROOT_PASSWORD=your_root_password
MYSQL_DATABASE=AMG_BD
MYSQL_USER=alumnoDAW
MYSQL_PASSWORD=passAMG

# Backend Database URL
DATABASE_URL=mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@db:3306/${MYSQL_DATABASE}