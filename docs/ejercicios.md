# Apuntes de clase de PHP · Papelería Luna

En esta practica hay que crear una cuenta, entrar a la página de la cuenta y cerrar la sesión. Para probar se pueden usar datos inventados.

## 1. Formularios y método POST

Cuando abro `/registro.php`, el navegador pide el formulario con `GET`. Cuando le doy al botón de registro, envía los datos con `POST`. PHP lee los valores en `$_POST` con el `name` de cada campo. Por ejemplo, si un campo tiene `name="correo"`, en PHP queda en `$_POST['correo']`.

**Ejercicio.** Abre las herramientas del navegador y entra a la pestaña Red. Marca la opción de conservar el registro. Abre el formulario y envíalo con datos de prueba. Deberías ver `GET` al abrirlo y `POST` al enviarlo.

## 2. Datos y restricciones en PostgreSQL

Cada fila de `usuarios` es una cuenta. El `id` sirve para encontrar esa fila. El `documento` y el `correo` no se pueden repetir. PHP convierte el correo a minusculas y usa PDO para pasar los datos como parámetros de la consulta.

```sql
SELECT id, nombres, apellidos, correo, creado_en
FROM usuarios
ORDER BY id;
```

**Ejercicio.** Registra una cuenta ficticia con `luna@example.com` y ejecuta la consulta. Luego intenta registrar `LUNA@example.com` con un documento diferente. Prueba tambien con un correo nuevo y el documento original. Al volver a consultar debería aparecer la primera cuenta y las otras dos no deberían haberse creado.

## 3. Contraseñas e inicio de sesión

Al registrar una cuenta, `password_hash()` convierte la contraseña en un hash y eso se guarda en `contrasena_hash`. En `/ingresar.php`, PHP busca la cuenta por correo y usa `password_verify()` para comprobar la contraseña. Si coincide, guarda el `id` en la sesión y puedo entrar a `/cuenta.php`.

**Ejercicio.** Intenta entrar con un correo registrado y una contraseña incorrecta. Debería salir un mensaje de error. Vuelve a intentar con la contraseña correcta y revisa qué página se abre. ¿Para qué sirve guardar el `id` en la sesión?

## 4. Página privada y cierre de sesión

Cada vez que abro `/cuenta.php`, PHP revisa si hay una sesión con usuario. El botón para salir envía un `POST` a `/salir.php` y cierra la sesión. Los formularios llevan un token CSRF. PHP compara el token que llega con el de la sesión antes de aceptar el formulario.

**Ejercicio.** Con la sesión iniciada, abre `/cuenta.php` en otra pestaña del mismo navegador. Despues cierra sesión y recarga esa pestaña. Deberías volver al formulario de ingreso. Prueba tambien abrir la página de la cuenta en una ventana privada.
