# prueba_tecnica_valdez_woodward_(contenedores)

# ¡PASOS PARA INICIAR EL PROYECTO!:

# (0) Habilitar las Short Tags (<?=?>) de PHP [archivo php.ini o relacionado], porque dentro del proyecto se utilizan.
# (1) Iniciar Apache y MySQL utilizando ya sea (XAMPP o WAMPP)
# (2) Dentro de la carpeta del proyecto abrir la terminal (linea de comandos) y ejecutar las siguientes lineas para inicirar el proyecto, como se muestra a continuación:
    PowerShell 7.4.1
    > PS C:\xampp\htdocs\PRUEBAS_TECNICAS\contenedores> php -S localhost:83
    > [Sun Apr 14 13:18:57 2024] PHP 8.2.0 Development Server (http://localhost:83) started
        (Si el puerto esta disponible se confirmará que el proyecto se a iniciado y es accesible)
# (3) Si al iniciar el proyecto se muestra este error:
    {"estado":"error","tipoError":"server","mensaje":"SQLSTATE[HY000] [1049] Unknown database 'db_contenedor_entrada_salida'"}
    [Es porque no se a importado la BD a MySQL, el archivo a importar es (db_contenedor_entrada_salida.sql) que esta en la carpeta (db_and_procedures_[contenedores])]
# (4) Del punto anterior los procedimientos almacendados van dentro de ese archivo pero de igual manera están dentro del archivo, (db_and_procedures.sql) y está dentro de la misma carpeta del punto anterior.
    [Si no se importan los procedimientos almacenados se mostrará el siguiente error: SQLSTATE[42000]: Syntax error or access violation: 1305 PROCEDURE db_contenedor_entrada_salida.registrarContenedor does not exist]