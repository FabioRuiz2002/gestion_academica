<?php
/*
 * Archivo: utils/HorarioHelper.php
 * Propósito: Clase de utilidad para parsear horarios y detectar conflictos.
 */
class HorarioHelper {

    private $mapaDias = [
        'Lu' => 1, 'Ma' => 2, 'Mi' => 3, 'Ju' => 4, 'Vi' => 5, 'Sa' => 6, 'Do' => 7
    ];

    /**
     * Parsea un string de horario (ej: "Lu 8-10, Mi 9-11")
     * y lo convierte en un array estructurado.
     */
    private function parsearHorario($horarioString) {
        $bloques = [];
        if (empty($horarioString)) {
            return $bloques;
        }

        // RegEx para encontrar patrones como "Lu 8-10"
        if (preg_match_all('/(Lu|Ma|Mi|Ju|Vi|Sa|Do) (\d{1,2})-(\d{1,2})/', $horarioString, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                // $match[1] = Dia (Lu), $match[2] = Inicio (8), $match[3] = Fin (10)
                $diaNum = $this->mapaDias[$match[1]] ?? 0;
                $horaInicio = (int)$match[2];
                $horaFin = (int)$match[3];

                if ($diaNum > 0 && $horaInicio < $horaFin) {
                    $bloques[] = [
                        'dia' => $diaNum,
                        'inicio' => $horaInicio,
                        'fin' => $horaFin
                    ];
                }
            }
        }
        return $bloques;
    }

    /**
     * Compara dos horarios (ya parseados) para ver si hay solape.
     */
    private function hayConflictoEntreBloques($bloquesA, $bloquesB) {
        foreach ($bloquesA as $a) {
            foreach ($bloquesB as $b) {
                // 1. Comprobar si son el mismo día
                if ($a['dia'] == $b['dia']) {
                    // 2. Comprobar si hay cruce de horas
                    // (A_inicio < B_fin) Y (A_fin > B_inicio)
                    if ($a['inicio'] < $b['fin'] && $a['fin'] > $b['inicio']) {
                        return true; // ¡Conflicto!
                    }
                }
            }
        }
        return false; // No hay conflicto
    }

    /**
     * Función PÚBLICA.
     * Comprueba si un nuevo horario (string) tiene conflicto con una lista
     * de otros horarios (array de strings).
     */
    public function verificarConflictoConLista($horarioNuevoStr, $listaHorariosExistentes) {
        $bloquesNuevos = $this->parsearHorario($horarioNuevoStr);
        if (empty($bloquesNuevos)) {
            return false; // El nuevo horario está vacío o mal formateado, no se puede validar
        }

        foreach ($listaHorariosExistentes as $horarioExistenteStr) {
            $bloquesExistentes = $this->parsearHorario($horarioExistenteStr);
            if ($this->hayConflictoEntreBloques($bloquesNuevos, $bloquesExistentes)) {
                return true; // Conflicto encontrado
            }
        }
        
        return false; // No se encontraron conflictos
    }
}
?>