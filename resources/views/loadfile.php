<html>
<head>
    <title>Prueba Tuequipomedicoya</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>

<div class="container">
    <div class="row">
        <form action="/" id="form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="excelfile">Subir Archivo Excel - (XLSX) o CSV</label>
                <input type="file" id="excelfile" name="excelfile">
            </div>
            <div class="form-group" id="list_campos">
                <button class="btn btn-default btn-info" type="button" data-toggle="modal"
                        data-target="#myModal" id="call_modal">Agregar Campos
                </button>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default" disabled id="submit">Enviar</button>
            </div>
        </form>
    </div>
</div>

<!-- Latest compiled and minified JavaScript -->

<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Campos</h4>
            </div>
            <div class="modal-body">
                <p>Digite la cantidad de campos que tiene el archivo</p>
                <input type="number" class="form-control" id="campos" placeholder="Número de Campos">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="add_fields" class="btn btn-primary" type="button" disabled>Agregar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('#add_fields').on('click', function () {
        $('#myModal').modal('toggle');
        $('#call_modal').hide();
        for (i = 0; i < $('#campos').val(); i++) {
            $('#list_campos').append('' +
                '<label>Columna ' + (i + 1) + ' </label>' +
                '<input type="text" class="form-control" name="fields[]" placeholder="Campo ' + (i + 1 ) + '"/>' +
                '<label> Campo Asociado </label> ' +
                '<select class="form-control" name="selects[]    " >' +
                '<option value="name"> Nombre del Producto </option>' +
                '<option value="category"> Categoria del Producto </option>' +
                '<option value="image"> Foto del Producto </option>' +
                '<option value="file"> Url Archivo del Producto </option>' +
                '<option value="description"> Descripción del Producto </option>' +
                '<option value="price"> Precio del Producto </option>' +
                '</select>'
            );
        }

        $('#submit').removeAttr('disabled');
    })

    $('#campos').on('input', function () {
        if ($(this).val().length > 0) {
            $('#add_fields').removeAttr('disabled');
        } else {
            $('#add_fields').attr('disabled', 'disabled');
        }
    })
</script>
</body>
</html>