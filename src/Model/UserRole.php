<?php

/**
 * Enumeración de roles de usuario.
 *
 * - Representa los roles posibles dentro de la aplicación con sus valores legibles.
 * - Para obtener el texto (por ejemplo para comparaciones o persistencia) usa `UserRole::X->value`.
 *
 * NOTA: Mantén consistencia entre estos valores y los almacenados en la base de datos u otras
 * partes del sistema que dependan del nombre del rol.
 */
enum UserRole: string{
    case SUPERADMIN = "Superadmin";
    case TEAM_LEADER = "Team Leader";
    case SOFTWARE_ENGINEER = "Software Engineer"; 

}