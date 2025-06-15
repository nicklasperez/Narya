# 🎶 NARYA – Red Social Musical Emocional

**NARYA** es una aplicación multiplataforma donde los usuarios comparten su estado emocional junto a la música que escuchan, y conectan con otros a través de estados de ánimo similares.  
Desarrollada con **Laravel (backend)** e **Ionic + Vue (frontend)**, integra la API de **Spotify** para vincular cuentas y buscar canciones.

---

## 🧠 Requisitos Previos

- PHP ≥ 8.1  
- Composer  
- Node.js ≥ 18  
- MySQL  
- Git  
- Una cuenta de Spotify Developer  

---

## 🛠 Instalación

### 🔹 Backend (Laravel)

1. Clona el repositorio:

   git clone https://github.com/tu_usuario/narya.git
   cd narya
   
Instala las dependencias de Laravel:
composer install

Crea el archivo .env:
cp .env.example .env

Genera la clave de la app:
php artisan key:generate

Configura la base de datos:

Asegúrate de tener una base de datos llamada narya y luego edita tu .env:
DB_DATABASE=narya
DB_USERNAME=root
DB_PASSWORD=****

Agrega las claves de Spotify:
Solicítalas al autor del proyecto o crea una app en Spotify Developer:
SPOTIFY_CLIENT_ID=🔒
SPOTIFY_CLIENT_SECRET=🔒
SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/api/spotify/callback

Ejecuta migraciones y seeders:
php artisan migrate --seed

Inicia el servidor de Laravel:
php artisan serve
🔹 Frontend (Ionic + Vue)

Entra a la carpeta del frontend:
cd narya-frontend

Instala dependencias:
npm install

Inicia la app:
npm run dev

Abre en el navegador:
http://localhost:8100

🔑 Usuarios de prueba (base de datos semilla)
Usuario	Email	Contraseña
Nicklas	nicklas@example.com	Password123!
María	maria@example.com	Password123!
Javier	javi@example.com	Password123!
Laura	laura@example.com	Password123!
José	pepe@example.com	Password123!
Jose Luis	jose@example.com	Password123!
Santiago	santiago@example.com	Password123!
Rocío	rocio@example.com	Password123!
Jose Alberto	jalberto@example.com	Password123!
Bernardo	bernardo@example.com	Password123!

✨ Funcionalidades principales
✅ Registro seguro con validación avanzada

🎧 Vinculación con cuenta Spotify

🧠 Feed emocional según estado de ánimo

🔎 Buscador de usuarios

📊 Gráficos estadísticos por usuario

🧍‍♂️ Perfiles públicos

🔁 Gestión de seguidores / seguidos

🌈 Colores y estilos por mood musical

🎨 Estética y estilo

Estética Lo-fi + neón

Interfaz en dark-mode por defecto

Visual minimalista y emocional

Transiciones suaves y colores según estado de ánimo

👨‍💻 Autor
Nicklas Stæhr Pérez
Proyecto Integrado – Digitech FP
Junio 2025
GitHub: @tu_usuario

📄 Licencia
Este proyecto es de uso educativo.
Para cualquier otro uso, contactar previamente con el autor.
