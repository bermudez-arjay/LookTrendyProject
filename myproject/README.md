"Laravel Project - [LookTrendyProject]
Laravel PHP MySQL

📌 Descripción
Aplicación web construida con Laravel para [breve descripción del propósito].

🚀 Requisitos del Sistema
PHP ≥ 8.1

Composer ≥ 2.0

MySQL ≥ 8.0 

Node.js ≥ 16 

Extensión PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

🛠️ Instalación Rápida
Clonar repositorio:

bash
git clone https://github.com/bermudez-arjay/LookTrendyProject.git
cd LookTrendyProject
cd myproject

Instalar dependencias:

bash
composer install && npm install
Configurar entorno:

bash
cp .env.example .env pegar el .env de ejemplo del proyecto y modificar lo siguiente a continuacion
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

bash ejecutar las migraciones
con un:
php artisan migrate

bash
ejecutar los seeder el cual genera un usuario con el Rol de administrador con las siguientes credenciales:

Correo: admin@admin.com
Contraseña: admin

php artisan migrate --seed

Iniciar servidor:

bash
php artisan serve
npm run dev

🔧 Comandos Esenciales
php artisan test	Ejecuta pruebas PHPUnit
📂 Estructura del Proyecto
app/
├── Console/          # Comandos Artisan
├── Exceptions/       # Manejo de excepciones
├── Http/             # Lógica HTTP
│   ├── Controllers/  # Controladores
│   ├── Middleware/   # Middlewares  
│   └── Requests/     # Form Requests
│── Livewire/         # Livewire
├── Models/           # Modelos Eloquent
├── Providers/        # Service Providers
config/               # Configuraciones
database/
├── factories/        # Factories
├── migrations/       # Migraciones
├── seeders/          # Seeders
public/               # Assets públicos
resources/
├── js/               # JavaScript
├── lang/             # Localización
├── views/            # Vistas Blade
│    ├── Livewire/    # Livewire
routes/               # Definición de rutas
storage/              
tests/                # Pruebas automatizadas
vendor/               # Dependencias
⚙️ Configuración Avanzada
Para desarrollo:

bash
php artisan optimize
php artisan view:cache
php artisan route:cache

📜 Licencia
MIT License - Ver archivo LICENSE

📧 Contacto
Desarrolladores: GRUPO-1

Email: bermudezarjaydev@gmail.com


🔄 Actualizado: {5/21/2025}
📌 Nota: Ejecutar composer update periódicamente para mantener dependencias actualizadas" 
