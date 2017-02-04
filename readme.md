#Actividad de desarrollo backend

##Descripción de la actividad:

Construya un servicio que permita subir un archivo de Excel - (XLSX) o CSV para la importación de su contenido y almacenamiento de dicha información en un repositorio. Tenga en cuenta los siguientes lineamientos:

· Al cargar el archivo se debe preguntar qué columna del archivo corresponde a qué campo en el repositorio de datos (Ejemplo: columna 1 a nombre, columna 2 a precio, etc.).

· Un producto debe almacenar los siguientes datos: nombre, categoría (al menos una), foto, archivo, descripción y precio.

· Un producto puede tener una o varias categorías (Para la prueba basta con dos).

· En el archivo pueden existir varias columnas de categorías. Si una categoría no existe, se creará y se asignará al producto correspondiente.

· En el caso de que un nombre de producto se repita se deben ofrecer las siguientes opciones

    o   Sobrescribir producto

    o   Omitir producto
    
    o   Sobrescribir todos (ya no se preguntara más al usuario)

    o   Omitir todos (ya no se preguntara más al usuario)
    
## Bonus

Un producto puede tener una o varias imágenes (JPG, PNG, máximo 3MB), sí se sube la URL de la imagen, el sistema debe descargarla, subirla al servidor y asociarla con el producto.

##Reglamento

·         Para el desarrollo debe usar el micro framework Lumen 5.3.

·         Es necesario el uso de migraciones y seeders para trabajar con bases de datos.

·         Para el desarrollo de pruebas unitarias debe usar la librería PHPUnit.

·         El motor relacional de base datos es de libre elección, debe justificar brevemente.

·         El uso de estilos no es necesario.

·         Se permite el uso de librerías de composer.

·         Es necesario el uso de Git.

·         Estándar de codificación PSR-2.

##Otros

·         Se recomienda el uso de TDD (test driven development).

·         Realice las validaciones en el servidor que considere necesarias.

·         Se revisará el modelo relacional correspondiente a la solución

## Aspectos a evaluar:

·         Estructura: 30%

·         Pruebas unitarias y/o de integración: 25%

·         Funcionamiento: 15%

·         Documentación: 15%

·         Tiempo de entrega 10%