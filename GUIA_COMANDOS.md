# Guia rapida de comandos

Este archivo te sirve como referencia rapida para trabajar con Git, Laravel y Vite en este proyecto.

## 1. Entrar a la carpeta del proyecto

### En Git Bash
```bash
cd /c/Users/david/Desktop/Hospital_privado_malacatan
```

### En PowerShell
```powershell
cd C:\Users\david\Desktop\Hospital_privado_malacatan
```

## 2. Revisar en que rama estas

```bash
git branch --show-current
git status
```

## 3. Crear una rama nueva

Ejemplo para farmacia:

```bash
git checkout main
git pull
git checkout -b backend-farmacia
```

## 4. Subir una rama nueva a GitHub por primera vez

```bash
git push -u origin backend-farmacia
```

Despues de eso, normalmente ya solo usas:

```bash
git push
```

## 5. Guardar cambios normales

```bash
git status
git add .
git commit -m "mensaje del cambio"
git push
```

Ejemplo:

```bash
git add .
git commit -m "feat: agrega backend inicial de farmacia"
git push
```

## 6. Traer cambios del remoto

```bash
git pull
```

## 7. Cambiar entre ramas

Ir a la rama de usuarios:

```bash
git checkout backend-usuarios
```

Ir a la rama de farmacia:

```bash
git checkout backend-farmacia
```

Volver a main:

```bash
git checkout main
```

## 8. Ver todas las ramas

```bash
git branch
git branch -a
```

## 9. Ver historial de commits

```bash
git log --oneline -n 10
```

## 10. Ver que archivos cambiaste

```bash
git status
git diff --stat
```

## 11. Levantar el proyecto

### Laravel
```bash
php artisan serve
```

### Vite
```bash
npm run dev
```

## 12. Ejecutar pruebas

Todas las pruebas:

```bash
php artisan test
```

Solo un modulo:

```bash
php artisan test --filter=UserManagementTest
```

## 13. Comandos utiles para este proyecto

Limpiar caches:

```bash
php artisan optimize:clear
```

Verificar configuracion:

```bash
php artisan config:clear
```

## 14. Flujo recomendado de trabajo

1. Entrar al proyecto
2. Verificar rama actual
3. Hacer cambios
4. Revisar con `git status`
5. Guardar con `git add .`
6. Crear commit con mensaje claro
7. Subir con `git push`

## 15. Regla practica

- Git Bash: mejor para Git
- PowerShell o terminal del proyecto: mejor para Laravel y Vite

## 16. Ejemplo completo

```bash
cd /c/Users/david/Desktop/Hospital_privado_malacatan
git checkout backend-farmacia
git status
git add .
git commit -m "feat: inicia modulo de farmacia"
git push
```

