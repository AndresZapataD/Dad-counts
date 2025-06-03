Dad-counts v-2 es una aplicación web desarrollada en PHP puro, con manejo de ventas, clientes y reportes. Utiliza SQLite como motor de base de datos local, jQuery para la interacción dinámica del formulario y DomPDF para la generación de reportes en PDF.

📁 Estructura del Proyecto
bash
Copy
Edit
Dad-counts-v2/
│
├── index.php                # Página principal con formulario y listado de ventas
├── setup.php                # Crea la base de datos y las tablas requeridas
├── composer.json            # Autoload y dependencias (como DomPDF)
├── assets/
│   └── styles.css           # Estilos de la aplicación
├── controllers/
│   ├── VentaController.php
│   ├── FacturaController.php
│   ├── ReporteController.php
│   └── CambiarEstadoController.php
├── models/
│   ├── Venta.php
│   ├── Cliente.php
│   └── Database.php
├── database/
│   └── sales.db             # Archivo de base de datos SQLite (se genera con setup.php)
├── vendor/                  # Librerías instaladas por Composer (como DomPDF)
└── .gitignore
⚙️ Requisitos
PHP 8.1 o superior

Extensiones de PHP habilitadas:

pdo_sqlite

sqlite3

Composer (opcional, pero recomendado para instalar dependencias como DomPDF)

Navegador web moderno

🚀 Instalación y Primer Uso
Clona el repositorio o copia los archivos a tu proyecto:

bash
Copy
Edit
git clone https://github.com/tuusuario/Dad-counts-v2.git
cd Dad-counts-v2
Instala dependencias con Composer:

bash
Copy
Edit
composer install
Si no tienes Composer, descárgalo desde: https://getcomposer.org/download/

Habilita las extensiones necesarias en php.ini:

Busca y asegúrate de quitar el ; al inicio de estas líneas:

ini
Copy
Edit
extension=pdo_sqlite
extension=sqlite3
Guarda el archivo y reinicia tu servidor PHP.

Ejecuta el servidor local de PHP:

bash
Copy
Edit
php -S localhost:8000
En tu navegador, visita:

bash
Copy
Edit
http://localhost:8000/setup.php
✔ Esto generará la base de datos sales.db con las tablas necesarias (clientes, ventas, productos).

🧪 Uso de la aplicación
Accede desde el navegador a: http://localhost:8000

Puedes:

Registrar ventas con múltiples productos

Ver listado de ventas filtrable por cliente o estado

Cambiar el estado de una venta desde el listado

Eliminar ventas

Generar facturas y reportes PDF

📦 Estructura de base de datos
Base de datos usada: SQLite (database/sales.db)

Tabla clientes
Campo	Tipo
id	INTEGER
nombre	TEXT
zona	TEXT

Tabla ventas
Campo	Tipo
id	INTEGER
cliente_id	INTEGER
fecha	TEXT
estado	TEXT
total	REAL

Tabla productos
Campo	Tipo
id	INTEGER
venta_id	INTEGER
nombre	TEXT
cantidad	INTEGER
precio_unitario	REAL
total	REAL

🛠 Tecnologías utilizadas
PHP 8 (modo embebido)

SQLite (base de datos local)

jQuery (frontend dinámico)

HTML5 + CSS3

DomPDF (generación de PDFs)

📌 Notas importantes
No necesitas XAMPP, Apache o MySQL.

Usa php -S para correr localmente.

La base de datos es un archivo .db dentro de database/

Ejecuta setup.php solo una vez (o si borras la base de datos).

👤 Autor
Andrés Felipe Zapata Duque
📍 Proyecto de control de ventas en PHP puro, funcional y educativo, con fines de aprendizaje y uso práctico.

📝 Licencia
Este proyecto puede ser usado, adaptado o redistribuido CON ATRIBUCION. ¡Disfrútalo!
