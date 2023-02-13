<?php 
    require_once("../Controller/set_formulario.php");
?>
<div class="mb-5">
    <span class="text-uppercase page-subtitle">Hojas de ruta</span>
    <h3 class="page-title">Nuevo Funcionario</h3>
</div>
<div class="px-5 py-3 mb-4 rounded-3" style="background-color: #e0e0e0;">
    <form class="row g-3 m-sm-4" method="post" id="formIngresoFuncionario">
      <div class="col-md-4">
        <label for="inpNombre" class="form-label">Nombres</label>
        <input type="text" class="form-control text-uppercase" id="inpNombre" name="inpNombre" placeholder="juan Carlos">
      </div>
      <div class="col-md-4">
        <label for="inpPaterno" class="form-label">Apellido Paterno</label>
        <input type="text" class="form-control text-uppercase" id="inpPaterno" name="inpPaterno" placeholder="Peréz">
      </div>
      <div class="col-md-4">
        <label for="inpMaterno" class="form-label">Apellido Materno</label>
        <input type="text" class="form-control text-uppercase" id="inpMaterno" name="inpMaterno" placeholder="Peréz">
      </div>
      <div class="col-md-3">
        <label for="inpRut" class="form-label">Rol único nacional (RUN)</label>
        <input type="email" class="form-control" id="inpRut" name="inpRut" required placeholder="111111111-1">
      </div>
      <div class="col-md-3">
        <label for="inpF_nac" class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="inpF_nac" name="inpF_nac">
      </div>
      <div class="col-md-3">
        <label for="inpGenero" class="form-label">Genero</label>
        <select id="inpGenero" class="form-select" name="inpGenero">
          <option>Seleccionar...</option>
          <option>FEMENINO</option>
          <option>MASCULINO</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="inpFono" class="form-label">Teléfono</label>
        <input type="text" class="form-control text-uppercase" id="inpFono" name="inpFono" placeholder="988027574">
      </div>
      <div class="col-md-6">
        <label for="inpEmail" class="form-label">Correo Electrónico</label>
        <input type="text" class="form-control text-uppercase" id="inpEmail" name="inpEmail" placeholder="jc@mail.cl">
      </div>
      <div class="col-md-6">
        <label for="inpProfesion" class="form-label">Profesión</label>
        <input type="text" class="form-control text-uppercase" id="inpProfesion" name="inpProfesion" placeholder="ADMINISTRATIVO">
      </div>
      <div class="col-md-3">
        <label for="inpC_juridica" class="form-label">Calidad Juridica</label>
        <select id="inpC_juridica" name="inpC_juridica" class="form-select">
          <option selected>Seleccionar...</option>
          <?php for ($i=0; $i < count($data['cj']); $i++) { ?>
            <option value="<?=$data['cj'][$i]['id'];?>"><?=$data['cj'][$i]['nombre'];?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="inpCargo" class="form-label">Cargo</label>
        <input type="text" class="form-control text-uppercase" id="inpCargo" name="inpCargo" placeholder="ADMINISTRATIVO">
      </div>
      <div class="col-md-3">
        <label for="inpEstamento" class="form-label">Estamento</label>
        <select id="inpEstamento" name="inpEstamento" class="form-select">
          <option selected>Seleccionar...</option>
          <?php for ($i=0; $i < count($data['est']); $i++) { ?>
            <option value="<?=$data['est'][$i]['id'];?>"><?=$data['est'][$i]['nombre'];?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-12">
        <label for="inpHR" class="form-label">Hoja de ruta</label>
        <select id="inpHR" name="inpHR" class="form-select">
          <option selected>Seleccionar...</option>
          <?php for ($i=0; $i < count($data['hr']); $i++) { ?>
            <option value="<?=$data['hr'][$i]['id'];?>"><?=$data['hr'][$i]['id']." - ".$data['hr'][$i]['nombre'];?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-3">
        <label for="inp_fIng" class="form-label">Fecha de Ingreso</label>
        <input type="date" class="form-control" id="inpf_ing" name="inp_fIng">
      </div>
      <div class="col-md-5">
        <label for="inpUnidad" class="form-label">Unidad</label>
        <select id="inpUnidad" name="inpUnidad" class="form-select">
          <option selected>Seleccionar...</option>
          <?php for ($i=0; $i < count($data['unidad']); $i++) { ?>
            <option class="text-uppercase" value="<?=$data['unidad'][$i]['id'].';'.$data['unidad'][$i]['nombre'];?>"><?=$data['unidad'][$i]['nombre'];?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="inpJefatura" class="form-label">Jefatura</label>
        <select id="inpJefatura" class="form-select" name="inpJefatura">
          <option>Seleccionar...</option>
          <?php for ($i=0; $i < count($data['funcionario']); $i++) { ?>
            <option value="<?=$data['funcionario'][$i]['per_rut'].';'.$data['funcionario'][$i]['per_nombre'];?>"><?=$data['funcionario'][$i]['per_nombre'];?></option>
          <?php } ?>
          <option value="nuevo">Otro..</option>
        </select>
      </div>
      <div class="col-md-4" id="otraJefatura">
        <label for="nombreFuncionario" class="form-label">Nueva Jefatura</label>
        <input id="rutFuncionario" name="nuevo_jefe" type="hidden">
        <input class="form-control  text-uppercase" name="nuevo_jefe_nom" id="nombreFuncionario" type="text" placeholder="Nombre">
      </div>
      <div class="col-12 col-md-3 offset-md-9 d-grid gap-2 mt-5">
        <button type="button" id="btnSendForm" class="btn btn-primary"><i class="fs-6 bi-save me-3"></i>Guardar</button>
      </div>
    </form>
</div>
