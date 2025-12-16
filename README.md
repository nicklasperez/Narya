# 🎶 NARYA – Musical Emotional Social Media

**NARYA** is a cross-platform application where users share their emotional state along with the music they listen to, and connect with others through similar moods.  
Developed with **Laravel (backend)** and **Ionic + Vue (frontend)**, it integrates the **Spotify** API to link accounts and search for songs.

---

## 🧠 Prerequisites

- PHP ≥ 8.1  
- Composer  
- Node.js ≥ 18  
- MySQL  
- Git  
- Spotify Developer Account  

---

## 🛠 Instalation

### 🔹 Backend (Laravel)

1. Clone the Repository

   git clone https://github.com/tu_usuario/narya.git
   cd narya
   
2. Install Laravel Dependencies:

   composer install

3. Create .env:

   cp .env.example .env

4. Generate the app key:

   php artisan key:generate

5. Configure the DB:

   Make sure you have a database named narya, then edit your .env:
   DB_DATABASE=narya
   DB_USERNAME=root
   DB_PASSWORD=****

6. Add the Spotify credentials:

   Request them from the project author or create an app in Spotify Developer:
   SPOTIFY_CLIENT_ID=🔒
   SPOTIFY_CLIENT_SECRET=🔒
   SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/api/spotify/callback

7. Run migrations and seeders:

   php artisan migrate --seed

8. Initialize the Laravel Server:

   php artisan serve

🔹 Frontend (Ionic + Vue)

1. Enter the frontend folder:

   cd narya-frontend

2. Install Dependencies:

   npm install

3. Start the APP:

   npm run dev

4. Open your browser:

   http://localhost:8100


🔑 Test Users (Seeded DB)

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


✨ Main Functionalities:

✅ Secure registration with advanced validation

🎧 Spotify account linking

🧠 Mood-based emotional feed

🔎 User search

📊 User statistical charts

🧍‍♂️ Public profiles

🔁 Followers / following management

🌈 Mood-based colors and styles

🎨 Aesthetic and style

Lo-fi + neon aesthetic

Dark-mode interface by default

Minimalistic and emotional visual design

Smooth transitions and mood-based colors

👨‍💻 Author
Nicklas Stæhr Pérez

Final Integrated Project – Digitech FP

June 2025

GitHub: @nicklasperez

📄 License

This project is for educational use.

For any other use, please contact the author in advance.

