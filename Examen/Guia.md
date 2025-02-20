# Guía de Despliegue React + Symfony + BD con SSL

## Estructura Base del Proyecto

```
proyecto/
├── frontend/
├── backend/
├── web/
│   ├── certs/
│   │   └── .gitignore
│   ├── default.conf
│   ├── DockerfileWebXXX
│   └── entrypoint.sh
├── database/
│   └── init.sql
├── .env
├── .env.template
├── .gitignore
└── docker-compose.yml
```

## 1. Configuración Inicial

### 1.1 Crear estructura de carpetas
```bash
mkdir -p proyecto/{frontend,backend,web/certs,database}
cd proyecto
```

### 1.2 Configurar .gitignore en la raíz
```
# Environment files
.env
.env.*
!.env.template

# SSL certificates
web/certs/*
!web/certs/.gitignore

# Node modules
node_modules/
dist/

# Symfony
/backend/var/
/backend/vendor/
```

### 1.3 Configurar .gitignore en web/certs
```
*
!.gitignore
```

### 1.4 Crear .env.template en la raíz
```
# Database Configuration
MYSQL_ROOT_PASSWORD=root_password
MYSQL_DATABASE=XXX_BD
MYSQL_USER=alumnoDAW
MYSQL_PASSWORD=passXXX
```

## 2. Configuración del Frontend (React)

### 2.1 Crear proyecto React
```bash
cd frontend
npm create vite@latest . -- --template react
```

### 2.2 Modificar App.jsx
```jsx
import { useEffect, useState } from 'react';

function App() {
  const [message, setMessage] = useState('');
  
  useEffect(() => {
    fetch('/api/xxx')
      .then((res) => res.json())
      .then((data) => setMessage(data.message));
  }, []);

  return (
    <div>
      <h1>Frontend en React de [TU NOMBRE]</h1>
      <p>Esta aplicación se conecta al backend de Symfony pidiendo una respuesta</p>
      <p>Respuesta del Backend: {message || 'Esperando respuesta...'}</p>
    </div>
  );
}

export default App;
```

### 2.3 Crear DockerfileFrontendXXX
```dockerfile
FROM node:alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
```

## 3. Configuración del Backend (Symfony)

### 3.1 Crear proyecto Symfony
```bash
cd backend
symfony new . --webapp
```

### 3.2 Crear XXX_Controller.php
```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class XXX_Controller extends AbstractController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[Route('/api/xxx', name: 'get_xxx')]
    public function getMessage(): JsonResponse
    {
        $sql = "SELECT fraseXXX FROM secretosXXX LIMIT 1;";
        $result = $this->connection->fetchOne($sql);
        
        return $this->json(['message' => $result ? $result : 'No hay mensajes en la BD']);
    }
}
```

### 3.3 Crear entidad TablaXXX.php
```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "secretosXXX")]
class TablaXXX
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(name: "fraseXXX", type: "string", length: 255)]
    private string $fraseXXX;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFraseXXX(): string
    {
        return $this->fraseXXX;
    }

    public function setFraseXXX(string $fraseXXX): self
    {
        $this->fraseXXX = $fraseXXX;
        return $this;
    }
}
```

### 3.4 Crear DockerfileBackendXXX
```dockerfile
FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install
RUN php bin/console doctrine:migrations:migrate --no-interaction

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
```

## 4. Configuración del Proxy Inverso (Nginx)

### 4.1 Crear entrypoint.sh
```bash
#!/bin/sh
set -e

if [ ! -f /etc/nginx/certs/selfsigned.crt ]; then
    echo "Generando certificado autofirmado..."
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -subj "/C=ES/ST=Granada/L=Granada/O=IESHLANZ/OU=DAWT/CN=[TU NOMBRE]/emailAddress=[TU EMAIL]" \
    -keyout /etc/nginx/certs/selfsigned.key \
    -out /etc/nginx/certs/selfsigned.crt
fi

exec nginx -g "daemon off;"
```

### 4.2 Crear default.conf
```nginx
# Redirección HTTP a HTTPS
server {
    listen 80;
    server_name localhost;
    return 301 https://$host$request_uri;
}

# Configuración HTTPS
server {
    listen 443 ssl;
    server_name localhost;

    # Certificados SSL
    ssl_certificate /etc/nginx/certs/selfsigned.crt;
    ssl_certificate_key /etc/nginx/certs/selfsigned.key;
    
    # Configuración SSL
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Frontend
    location / {
        proxy_pass http://frontend:5173;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }

    # Backend
    location /api {
        proxy_pass http://backend:8000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }

    # PHPMyAdmin
    location /phpmyadmin/ {
        proxy_pass http://phpmyadmin:80/;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
    }
}
```

### 4.3 Crear DockerfileWebXXX
```dockerfile
FROM nginx:latest
RUN apt-get update && apt-get install -y openssl && rm -rf /var/lib/apt/lists/*
COPY default.conf /etc/nginx/conf.d/default.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
CMD ["/entrypoint.sh"]
```

## 5. Configuración de la Base de Datos

### 5.1 Crear init.sql
```sql
CREATE TABLE secretosXXX (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fraseXXX VARCHAR(255) NOT NULL
);

INSERT INTO secretosXXX (fraseXXX) VALUES ('La base de datos responde, buenos días [TU NOMBRE]');
```

## 6. Docker Compose

### 6.1 Crear docker-compose.yml
```yaml
version: '3.8'

services:
  frontend:
    build:
      context: ./frontend
      dockerfile: DockerfileFrontendXXX
    volumes:
      - ./frontend:/app
      - /app/node_modules
    environment:
      - CHOKIDAR_USEPOLLING=true
    networks:
      - redXXX

  backend:
    build:
      context: ./backend
      dockerfile: DockerfileBackendXXX
    volumes:
      - ./backend:/app
    environment:
      - APP_ENV=dev
    networks:
      - redXXX

  web:
    build:
      context: ./web
      dockerfile: DockerfileWebXXX
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./web/certs:/etc/nginx/certs
    depends_on:
      - frontend
      - backend
    networks:
      - redXXX

  db:
    image: mysql:5.7
    volumes:
      - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql
      - db_data:/var/lib/mysql
    env_file:
      - .env
    networks:
      - redXXX

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    environment:
      PMA_HOST: db
      PMA_USER: ${MYSQL_USER}
      PMA_PASSWORD: ${MYSQL_PASSWORD}
    depends_on:
      - db
    networks:
      - redXXX

volumes:
  db_data:

networks:
  redXXX:
    driver: bridge
```

## 7. Despliegue

### 7.1 Local
1. Crear archivo .env basado en .env.template
2. Ejecutar:
```bash
docker-compose up -d --build
```

### 7.2 AWS
1. Clonar repositorio
2. Crear .env
3. Modificar default.conf (cambiar localhost por IP pública)
4. Ejecutar:
```bash
docker-compose up -d --build
```

## 8. Verificación
Acceder a:
- Frontend: https://[ip-aws]
- Backend: https://[ip-aws]/api/xxx
- PHPMyAdmin: https://[ip-aws]/phpmyadmin/

## Notas para el examen
1. Cambiar todas las ocurrencias de XXX por las iniciales o identificador requerido
2. Personalizar los nombres según las especificaciones
3. Modificar las credenciales de la base de datos según lo solicitado
4. Ajustar los mensajes y nombres en el frontend
5. Verificar que todos los servicios estén correctamente configurados antes del despliegue