<?php
/*
 * Archivo: views/admin/gestionar_prerequisitos.php
 * (Botón "Volver" estandarizado con el componente)
 */
?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Gestión Académica</a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $infoPlan['id_facultad']; ?>"><?php echo htmlspecialchars($infoPlan['nombre_facultad']); ?></a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verEscuela&id_escuela=<?php echo $infoPlan['id_escuela']; ?>"><?php echo htmlspecialchars($infoPlan['nombre_escuela']); ?></a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=gestionarMalla&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>"><?php echo htmlspecialchars($infoPlan['nombre_plan']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Prerrequisitos</li>
        </ol>
    </nav>

    <h2 class="mb-4">Gestionar Prerrequisitos</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <p class="lead">Define qué cursos debe haber aprobado un estudiante para poder matricularse en este.</p>
    <hr>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    ?>

    <div class="row">
        <div class="col-md-5">
            <div class.card shadow-sm">
                <div class="card-header">
                    <h5 class.mb-0">Añadir Requisito</h5>
                </div>
                <div class.card-body">
                    <form action="index.php?controller=Academico&action=addPrerequisito" method="POST">
                        <input type="hidden" name="id_curso_principal" value="<?php echo $infoCurso['id_curso']; ?>">
                        <input type="hidden" name="id_plan" value="<?php echo $infoPlan['id_plan_estudio']; ?>">
                        
                        <div class="mb-3">
                            <label for="id_curso_requisito" class="form-label">Curso Requerido</label>
                            <select name="id_curso_requisito" class="form-select" required>
                                <option value="">Selecciona un curso...</option>
                                <?php if (empty($listaCursosDisponibles)): ?>
                                    <option value="" disabled>No hay cursos en ciclos anteriores.</option>
                                <?php endif; ?>
                                <?php foreach ($listaCursosDisponibles as $curso): ?>
                                    <option value="<?php echo $curso['id_curso']; ?>">
                                        (Ciclo <?php echo $curso['ciclo']; ?>) - <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Solo se muestran cursos de este plan en ciclos anteriores.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Añadir Prerrequisito
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h4>Requisitos Actuales</h4>
            
            <?php if (empty($listaRequisitos)): ?>
                <div class="alert alert-secondary">Este curso no tiene prerrequisitos.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaRequisitos as $req): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($req['nombre_curso_requisito']); ?>
                            <a href="index.php?controller=Academico&action=deletePrerequisito&id_prerequisito=<?php echo $req['id_prerequisito']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este requisito?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
    
</div>