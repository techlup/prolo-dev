<?php
    $header='
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <%%DATA(styles)%%>
            <link rel="icon" href="<%%DATA(icon)%%>"  type="image/png">
            <title><%%DATA(title)%%></title>
            <!-- Chrome, Firefox OS and Opera -->
            
        </head>
        <body>
    ';
    $nav='
    <nav <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>>
        <a class="navbar-brand" href="#"><%%DATA(app-brand)%%></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <%%DATA(links)%%>
            </ul>
            <%%DATA(prolo-items)%%>
        </div>
        <%%DATA(search)%%>
    </nav>';
    $footer='
        <%%DATA(scripts)%%>
        </body>
        </html>
        ';

    $carousel= '
    <div <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%> data-ride="carousel">
    <ol class="carousel-indicators">
      <%%DATA(indicators)%%>
    </ol>
    <div class="carousel-inner">
    <%%DATA(prolo-items)%%>
    </div>
    <%%DATA(controls)%%>
    </div>   
        ';
  $form='
  <form class="col-sm-4 my-3 border border-light bg-light rounded" style="color: #757575;" action="about.php" method="POST">
  <h5 class="card-header info-color white-text text-center py-2">
    <strong>New student</strong>
  </h5>

<div class="md-form">
  <label for="id">id:</label>
  <input type="number" name="id" class="form-control" value="<%%DATA(id)%%>">
</div>

<div class="md-form">
  <label for="name">name:</label>
  <input type="text" name="name" class="form-control" value="<%%DATA(name)%%>">
</div>

<div class="md-form">
  <label for="class">Class:</label>
  <input type="text" name="class" class="form-control" value="<%%DATA(class)%%>">
</div>

<div class="md-form">
  <label for="marks">marks:</label>
  <input type="number" name="marks" class="form-control" value="<%%DATA(marks)%%>">
</div>
  
<input type="submit" class="btn btn-outline-info btn-rounded btn-md my-4 waves-effect z-depth-0" id="proloGet" name="proloGet" value="get" />
<input type="submit" class="btn btn-outline-success btn-rounded btn-md my-4 waves-effect z-depth-0" id="proloUpdate" name="proloUpdate" value="update" /> 
<input type="submit" class="btn btn-outline-primary btn-rounded btn-md my-4 waves-effect z-depth-0" id="proloAdd" name="proloAdd" value="save" />     
</form>
';

$data_table = '
      <table <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>">
        <h2> <%%DATA(table-title)%%> List<h2>
        <tbody>
          <%%DATA(prolo-items)%%>
        </tbody>
      </table>
    ';

$side_nav ='
      <div class="row">

        <!-- Sidebar -->
        <div class="col-2 d-none d-md-block d-lg-block bg-light position-fixed" style="height: 90vh">
          <div class="list-group list-group-flush">
            <div class="my-4"></div>
            <%%DATA(table-links)%%>
          </div>
        </div>

        <div class="col-sm-12 col-md-10 col-lg-10">
          <%%DATA(prolo-items)%%>
        </div>
      </div>
      <style>.show-in-nav{margin-left: 30vh;max-width: 83%;}</style>
      ';

$side_navx ='
      <style>#prolo-wrapper {overflow-x: hidden;}#prolo-sidebar-wrapper {position: fixed;min-height: 100vh;margin-left: -15rem;-webkit-transition: margin .25s ease-out;-moz-transition: margin .25s ease-out;-o-transition: margin .25s ease-out;transition: margin .25s ease-out;}#prolo-sidebar-wrapper .list-group {width: 15rem;}#prolo-page-content-wrapper {min-width: 100vw;}#prolo-wrapper.toggled #prolo-sidebar-wrapper {margin-left: 0;}@media (min-width: 768px) {#prolo-sidebar-wrapper {margin-left: 0;}#prolo-page-content-wrapper {min-width: 0;width: 100%;}#prolo-wrapper.toggled #sidebar-wrapper {margin-left: -15rem;}}</style>
      <div class="d-flex" id="prolo-wrapper">
      <!-- Sidebar -->
      <div <%%DATA(prolo-class)%%> id="prolo-sidebar-wrapper">
        <div class="list-group list-group-flush">
        <div class="my-4"></div>
          <%%DATA(table-links)%%>
        </div>
      </div>
      <div id="prolo-page-content-wrapper" class="my-4 py-4">';
?>