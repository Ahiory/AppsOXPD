# SmartPrint - Aplicaciones HP OXPD

## 📖 Descripción del Proyecto
Este repositorio contiene el código fuente y los recursos para **SmartPrint**, un conjunto de aplicaciones desarrolladas sobre la plataforma **HP OXPD** (Open Extensibility Platform Device). La solución está construida principalmente con bibliotecas de **JavaScript** y está diseñada para ejecutarse en el navegador embebido del panel de control de los dispositivos HP (FutureSmart).

## 📂 Estructura del Repositorio

El proyecto está modularizado en los siguientes directorios principales:

* **`1print_doc/`**: Lógica central y flujos de trabajo relacionados con la retención, liberación y monitoreo de documentos de impresión.
* **`3- Encuesta/`**: Aplicación interactiva diseñada para recopilar datos o feedback de los usuarios directamente desde la pantalla de la impresora.
* **`AppPdf/`**: Módulos y scripts para el procesamiento, previsualización o manipulación de archivos PDF antes de su impresión o envío.
* **`Brochure aplicaciones/`**: Material informativo, presentaciones y vistas previas de las capacidades de las aplicaciones.
* **`Capacitacion/`**: Manuales, documentación técnica para desarrolladores y material de entrenamiento para el despliegue de la solución.
* **`Datos/`**: Almacenamiento local temporal, archivos de configuración (JSON) y parámetros estáticos necesarios para la ejecución.
* **`apps iconos/`**: Activos visuales y recursos gráficos (formato PNG/SVG) para la interfaz de usuario.
* **`ayuda_en_sitio/`**: Módulos de soporte técnico y guías interactivas disponibles para el usuario final en el panel del dispositivo.
* **`certificado/` & `certficados2/`**: Gestión de certificados de seguridad para garantizar conexiones cifradas (ej. flujos de autenticación SSO/OAuth 2.0 con servidores backend).
* **`recoleccion2/`**: Scripts orientados a la recolección de eventos, métricas de uso y logs de auditoría para su posterior envío a plataformas de análisis.

## 🛠️ Requisitos Técnicos y Dependencias

* **Entorno del Dispositivo:** Dispositivos HP Enterprise o Pro compatibles con OXPD y firmware FutureSmart (versión 4 o superior recomendada).
* **Tecnologías:** HTML5, CSS3 y JavaScript nativo/librerías soportadas por el motor web del dispositivo.
* **Conectividad:** Acceso a red configurado con soporte para protocolos SSO/OAuth 2.0 si se requiere autenticación contra servicios de directorio remotos.

## 🚀 Despliegue y Pruebas

1. **Configuración de OXPD:** Asegúrate de que la API de OXPD esté habilitada en el EWS (Embedded Web Server) de la impresora bajo la pestaña de `Seguridad` o `Servicios Web`.
2. **Carga de Archivos:** Las aplicaciones pueden empaquetarse y registrarse mediante un simulador OXPD para pruebas en local, o enviarse al dispositivo mediante herramientas de administración como HP Web Jetadmin o scripts de despliegue automatizado.
3. **Validación:** Verifica que los certificados en la carpeta `certificado/` coincidan con las políticas de seguridad del dispositivo para evitar bloqueos del navegador embebido.

## ⚙️ Notas de Desarrollo
* Mantener la interfaz de usuario optimizada y con alto contraste para asegurar la legibilidad en pantallas táctiles de diferentes resoluciones.
* Limitar el uso de librerías externas pesadas que puedan ralentizar el tiempo de carga en el navegador de la impresora.

---
**Desarrollado para la optimización y control de entornos de impresión.**
