<?php

/**
 * Enumeración que representa los posibles estados de una tarea.
 *
 * Los valores deben ser strings para que puedan ser utilizados en las consultas a la base de datos.
 *
 */
enum TaskState: string
{
    /**
     * Estado en el que no se ha asignado un miembro a la tarea.
     */
    case NOT_ASSIGNED = "Not assigned";

    /**
     * Estado en el que se ha asignado un miembro a la tarea, pero no ha comenzado a trabajar en ella.
     */
    case PENDING = "Pending";

    /**
     * Estado en el que el miembro asignado ha comenzado a trabajar en la tarea, pero no ha terminado de hacerlo.
     */
    case ON_REVIEW = "On review";

    /**
     * Estado en el que el miembro asignado ha terminado de hacer la tarea.
     */
    case FINISHED = "Finished";
}
