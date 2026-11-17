# T-HÜMAN backend — despliegue local (copia limpia)

Copia del backend (Laravel 11, PHP 8.3) tal como se desplegó en local, **sin historial, sin credenciales y sin archivos subidos**.

## Qué se cambió respecto al original
- `database/migrations/2025_09_24_265413_create_addendums_table.php`: `contract_id` ahora es `nullable()` (con `onDelete('set null')` MySQL 8 rechazaba la migración).
- `database/seeders/DemoDataSeeder.php` (nuevo, no está en `DatabaseSeeder`): datos de demostración ficticios.
- Credenciales de proveedores de SMS/correo reemplazadas por marcadores `CAMBIAR_*` en `ItemsAndSubitemsSeeder`; se eliminó un header `Authorization` fijo de `NotificationJob`; la clave del superadmin sale de `SUPERADMIN_PASSWORD`; IP interna y correo corporativo reemplazados.
- `public/storage` no se versiona (contiene documentos personales).

## Instalación
```
composer install
cp .env.example .env     # DB_CONNECTION=mysql, DB_DATABASE=..., SESSION_DRIVER=file, QUEUE_CONNECTION=sync, CACHE_STORE=file
php artisan key:generate
# crear la base (utf8mb4) y luego:
php artisan migrate --force
SUPERADMIN_PASSWORD='<elige una clave>' php artisan db:seed --force      # tarda varios minutos (ciudades + CIE-10)
php artisan db:seed --class=DemoDataSeeder --force                        # opcional: datos de demostración
php artisan serve --host=127.0.0.1 --port=8000
```
No ejecutar `storage:link` (ver análisis). Configurar en `.env` los proveedores de SMS/correo (`MASIV_*`, `SMS_*`) y en la base los valores de `notification_channels`.

## Problemas conocidos
Rutas sin middleware de autenticación; `ILIKE` en 77 modelos (las búsquedas fallan en MySQL); ids de tipos de notificación fijos en el código.
