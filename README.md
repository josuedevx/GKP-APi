# Google Ads Keyword Generator

Este proyecto utiliza la **API de Google Ads** para generar ideas de palabras clave relacionadas con un conjunto de términos proporcionados. El script PHP se conecta con la API de Google Ads y muestra métricas como volumen de búsqueda mensual y nivel de competencia.

## 📋 Requisitos

- PHP 8.2 o superior
- Composer
- Cuenta de Google Ads y acceso a la API
- Token de desarrollador aprobado para usar con cuentas de prueba o acceso básico a cuentas de producción

## 🚀 Instalación

### 1. Clonar el repositorio

   ```bash
   git clone git@github.com:josuedevx/GKP-APi.git
   cd GKP-APi
   ```

2. **Instalar dependencias:**

   Si no tienes Composer instalado, puedes obtenerlo desde:

   - [Composer.org](composer.org)

     Luego, ejecuta:

   ```bash
    composer install
   ```

3. **Configurar las credenciales de Google Ads:**

Crea el archivo google_ads_php.ini en la raíz del proyecto con el siguiente contenido:

- [GOOGLE_ADS]
- developerToken = "TU_TOKEN_DE_DESARROLLADOR"
- [OAUTH2]
- clientId = "TU_CLIENT_ID"
- clientSecret = "TU_CLIENT_SECRET"
- refreshToken = "TU_REFRESH_TOKEN"
- [CONNECTION]
- customerId = "CUSTOMER_ID"
- languageId = "LANGUAGE_ID"
- locationId = "LOCATION_ID"

- Asegúrate de reemplazar los valores con los datos correctos.
- Si aún no tienes un token de desarrollador, consulta **Google Ads API** para obtenerlo.

4. **Ejecutar el script:**

Para ejecutar el script y obtener ideas de keywords, ejecuta el siguiente comando en tu terminal:

```bash
   php app.php
```

Esto generará una lista de keywords, su volumen de búsqueda mensual y la competencia.

## Notas

Acceso a la API: Si tu token solo tiene acceso a cuentas de prueba, necesitarás usar el customerId de una cuenta de prueba de Google Ads. Si necesitas acceso a cuentas de producción, solicita acceso básico en la consola de Google Ads.
