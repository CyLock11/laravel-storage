# Gestor de archivos

Aplicación web sencilla hecha con Laravel para guardar imágenes y documentos PDF.

## Funciones

- Subir imágenes con título en `/photos`.
- Ver, reemplazar y eliminar imágenes.
- Subir documentos PDF con título en `/docs`.
- Abrir los documentos PDF en una pestaña nueva, reemplazarlos o eliminarlos.
- Guardar los archivos en el disco público de Laravel.

## Almacenamiento

Las imágenes se guardan en `storage/app/public/photos` y los PDF en `storage/app/public/docs`. El enlace creado por `php artisan storage:link` permite verlos desde la aplicación.
