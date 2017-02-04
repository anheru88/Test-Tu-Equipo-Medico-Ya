<html>
<head>
    <title>Prueba Tuequipomedicoya</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>

<form action="/override" id="form" method="post">
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Producto Existente</h4>
                </div>
                <div class="modal-body">
                    <p>El producto con nombre: <b> <?php echo $name; ?> </b>ya se encuentra en el repositorio. ¿Que
                        desea hacer?</p>
                    <input type="hidden" name="id_field" value="<?php echo $id; ?>">
                    <input type="hidden" name="value" id="value"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" value="0">Sobrescribir producto</button>
                    <button type="button" class="btn btn-primary" value="1">Omitir producto</button>
                    <button type="button" class="btn btn-primary" value="2">Sobrescribir todos</button>
                    <button type="button" class="btn btn-primary" value="3">Omitir todos</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Latest compiled and minified JavaScript -->

<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#myModal').modal('toggle');

        $('button').on('click', function () {
            $('#value').val($(this).val());
            $('form').submit();
        });
    });
</script>
</body>
</html>