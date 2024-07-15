# Proyecto de HuecoUIO
<img src="public/logo.png" width=250></img>
## ¿Qué se esta usando?
- La ultima version de Laravel
- TailwindCSS
- VueJS
- InertiaJS
- shadcn-ui/tailwindcss
- mysql

## Tutorial Facil de como ejecutar el proyecto
### 1. Clonar el repositorio
```bash
git clone https://github.com/ElHuecoUTE/huecoUIO_LaravelApp.git
```
### 2. Instalar las dependencias
```bash
composer install
```
### 3. Instalar las dependencias de node
```bash
npm install
```
### 4. Copiar el archivo .env.example a .env
Como estamos usando la base de datos online, estos son los datos de la base de datos:
```
DB_CONNECTION=mysql
DB_HOST=oasisdb-hoteloasisdb.i.aivencloud.com
DB_PORT=18058
DB_DATABASE=huecodb
DB_USERNAME=avnadmin
DB_PASSWORD=AVNS_DCCUU4k2Cyq_wnFtpGa
DB_CHARSET=utf8mb4
```
Nota: No hacer spam a la base de datos, ya que es una base de datos real, y el servicio es gratuito.
### 5. Generar la llave de la aplicacion
```bash
php artisan key:generate
```

### 6. Iniciar el servidor
```bash
php artisan serve
```
### 7. Compilar los assets
```bash
npm run dev
```
Nota: Este comando se debe ejecutar en otra terminal, ya que el servidor debe estar corriendo.

### 8. Acceder a la aplicacion
```
http://localhost:8000
```


