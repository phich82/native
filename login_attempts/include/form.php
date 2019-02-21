<?php
class Form
{
   var $values = array();  //Holds submitted form field values
   var $errors = array();  //Holds submitted form error messages
   var $num_errors;        //The number of errors in submitted form

   function Form(){
      if(isset($_SESSION['value_array']) && isset($_SESSION['error_array'])){
         $this->values = $_SESSION['value_array'];
         $this->errors = $_SESSION['error_array'];
         $this->num_errors = count($this->errors);

         unset($_SESSION['value_array']);
         unset($_SESSION['error_array']);
      }
      else{
         $this->num_errors = 0;
      }
   }

   function setError($error_type, $errmsg){
      $this->errors[$error_type] = $errmsg;
      $this->num_errors = count($this->errors);
   }

   function value($field){
      if(array_key_exists($field,$this->values)){
         return htmlspecialchars(stripslashes($this->values[$field]));
      }else{
         return "";
      }
   }

   function error($error_type){
      if(array_key_exists($error_type,$this->errors)){
         return "<font size=\"2\" color=\"#ff0000\">".$this->errors[$error_type]."</font>";
      }else{
         return "";
      }
   }

   function getErrorArray(){
      return $this->errors;
   }
};
 
?>
