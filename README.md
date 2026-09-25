# Sistema Contable — (BD_sis_cont)

Proyecto en **PHP + MySQL** con CRUD de **Usuarios** y **Movimientos**, un **Dashboard** y **API JSON** para pruebas en Postman.

---

## 🚀 Instalación rápida
1. Copiar carpeta a `C:\xampp\htdocs\BD_sis_cont`
2. Importar `database.sql` en phpMyAdmin
3. Revisar `conexion.php` (usar BD `BD_sis_cont`)
4. Prender Apache y MySQL
5. Abrir en navegador:
   - `http://localhost/BD_sis_cont/dashboard.php`

---

## 📂 Páginas
- Dashboard → `dashboard.php`
- Usuarios → `listar_usuarios.php`, `crear_usuarios.php`, `editar_usuarios.php`, `eliminar_usuarios.php`
- Movimientos → `movimientos_index.php`, `movimientos_create.php`, `movimientos_edit.php`, `movimientos_delete.php`
- Reportes → `reportes.php`
- Saldo → `saldo.php`
- Libro → `libro.php`
- Logout → `logout.php`

---

## 🔌 API JSON
Carpeta: `api/`

- **Usuarios** → `/api/usuarios.php`
  - GET (listar), POST (crear), PUT (?id=), DELETE (?id=)
- **Movimientos** → `/api/movimientos.php`
  - GET, POST, PUT, DELETE

Ejemplo POST usuario:
```json
{
  "nombre": "Nuevo",
  "usuario": "nuevo",
  "correo": "nuevo@local.test",
  "password": "123456",
  "rol": "usuario"
}
