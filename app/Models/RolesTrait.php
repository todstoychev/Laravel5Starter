<?php
 namespace App\Models;

 /**
  * Holds roles related methods
  *
  * @author Todor Todorov <todstoychev@gmail.com>
  * @package App\Models
  */
 trait RolesTrait {
     /**
      * Get all roles list as string
      *
      * @return String
      */
     public function getRoles() {
         $string = null;

         $last = end($this->roles);

         foreach ($this->roles as $role) {
             if ($role->role != end($last)->role) {
                 $string .= $role->role . ', ';
             } else {
                 $string .= $role->role;
             }
         }

         return $string;
     }

     /**
      * Check if user has role
      *
      * @param string $check Role name
      * @return Boolean
      */
     public function hasRole($check) {
         return in_array($check, array_fetch($this->roles->toArray(), 'role'));
     }

     /**
      * Get all roles as array
      *
      * @return array
      */
     public function getRolesAsArray()
     {
         $array = [];

         foreach ($this->roles as $role) {
             $array[] = $role->role;
         }

         return $array;
     }

     /**
      * Get roles ids as array
      *
      * @return array
      */
     public function getRoleIds()
     {
         $array = [];

         foreach ($this->roles as $role) {
             $array[] = $role->id;
         }

         return $array;
     }
 }