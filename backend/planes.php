<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Backend Smooth Operators</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
 <?php require_once("includes/navbar.php");   ?>

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Bienvenido</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
              <button type="button" class="btn btn-sm btn-outline-danger cancelar">Cancelar</button>
              <button type="button" class="btn btn-sm btn-outline-success" id="nuevo_registro">Nuevo</button>
            </div>
          </div>
        </div>
        <h2>planes</h2>
        <div class="table-responsive view" id="show_data">
          <table class="table table-striped table-sm" id="list-planes">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>descripcion</th>
                <th>costo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="insert_data" class="view">
          <form action="#" id="form_data">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="nombre">Nombre</label>
                  <input type="text" id="nombre" name="nombre" class="form-control">
                </div>

                <div class="col">
                <div class="form-group">
                  <label for="descripcion">descripcion</label>
                  <input type="text" id="descripcion" name="descripcion" class="form-control">
                </div>
                <div class="col">
                <div class="form-group">
                  <label for="costo">costo</label>
                  <input type="text" id="costo" name="costo" class="form-control">
                </div>


            </div>
            <div class="row">
              <div class="col">
                <button type="button" class="btn btn-success" id="guardar_datos">Guardar</button>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <script>
    //muestra que vista (guardar o regirtrar) es visible
    function change_view(vista = 'show_data'){
      $("#main").find(".view").each(function(){
        // $(this).addClass("d-none");
        $(this).slideUp('fast');
        let id = $(this).attr("id");
        if(vista == id){
          $(this).slideDown(300);
          // $(this).removeClass("d-none");
        }
      });

    }
    function consultar(){
         let obj = {
        "accion" : "consultar_planes"  
      };
      
      $.post("includes/_funciones.php", obj, function(respuesta){
        let template = ``;
        $.each(respuesta,function(i,e){
          template += `
          <tr>
          <td>${e.nombre_plan}</td>
          <td>${e.desc_plan}</td>
          <td>${e.costo_plan}</td>
          <td>
          <a href="#" data-id="${e.id_plan}" class="editar_plan">Editar</a>
          <a href="#" data-id="${e.id_plan}" class="eliminar_planes">Eliminar</a>
          </td>
          </tr>
          `;
        });
        $("#list-planes tbody").html(template);
      },"JSON");
    }
    $(document).ready(function(){
      consultar();
      change_view();
    });
    $("#nuevo_registro").click(function(){
      change_view('insert_data');
    });

    $("#guardar_datos").click(function(){
      let nombre = $('#nombre').val();
      let descripcion = $('#descripcion').val();
      let costo = $('#costo').val();

      let obj ={
        "accion" : "insertar_planes",
        "nombre" : nombre,
        "descripcion" : descripcion,
        "costo" : costo

      };
      $("#form_data").find("input").each(function(){
        $(this).removeClass("has-error");
        if($(this).val() != ""){
          obj[$(this).prop("name")] =  $(this).val();
        }else{
          $(this).addClass("has-error").focus();
          return false;
        }
      });
      if($(this).data("editar") == 1){
        obj["accion"] = "editar_planes";
        obj["id"] = $(this).data("id");
        $(this).text("Guardar").data("editar",0);
        $("#form_data")[0].reset();
      }
      $.post("includes/_funciones.php", obj, function(respuesta){
          alert(respuesta);
        if (respuesta == "Se inserto el usuario en la BD ") {
          change_view();
          consultar();
         }
        if (respuesta == "Se edito el usuario correctamente") {
            change_view();
            consultar();
          }
      });
      });
//eliminar usuarios
    $("#main").on("click",".eliminar_planes" , function(e){
      e.preventDefault();
      let confirmacion= confirm("Desea eliminar este registro");
      if (confirmacion) {
        let id=$(this).data('id'),
            obj ={
              "accion":"eliminar_planes",
              "id":id
            };
            $.post("includes/_funciones.php", obj, function(respuesta){
              alert(respuesta);
              consultar();
            });


      }
      else{
        alert("El registro no se ha eliminado");
      }

    });


//editar registro
$('#list-planes').on("click",".editar_plan", function(e){
        e.preventDefault();
        let id = $(this).data('id'),
            obj = {
              "accion" : "editar_plan",
              "id" : id
            };
        $("#form_data")[0].reset();
        change_view('insert_data');
        $("#guardar_datos").text("Editar").data("editar",1).data("id",id);
        $.post("includes/_funciones.php", obj, function(r){
          $("#nombre").val(r.nombre_plan);
          $("#descripcion").val(r.desc_plan);
          $("#costo").val(r.costo_plan);


        }, "JSON");
            
      });


        $("#main").find(".cancelar").click(function(){
      change_view();
      $("#form_data")[0].reset();
      if ($("#guardar_datos").data("editar") == 1) {
        $("#guardar_datos").text("Guardar").data("editar",0);
              
      }
    });
  </script>
</body>
</html>