<?php

namespace App\Security;

use Illuminate\Support\Facades\Validator;

class SecurityTools
{
   public static function sanitizeString($value)
   {
      if (is_null($value)) {
         return null;
      }
      $value = trim($value);
      $value = strip_tags($value);
      $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
      return $value;
   }

   public static function sanitizeArray(array $data)
   {
      $clean = [];
      foreach ($data as $key => $value) {
         if (is_array($value)) {
            $clean[$key] = self::sanitizeArray($value);
         } else {
            $clean[$key] = self::sanitizeString($value);
         }
      }
      return $clean;
   }

   public static function validatedInput(array $data, array $rules)
   {
      $data = self::sanitizeArray($data);
      $validator = Validator::make($data, $rules);
      if ($validator->fails()) {
         return [false, $validator->errors()];
      }
      return [true, $validator->validated()];
   }

   public static function safeEquals($known, $user)
   {
      if (!is_string($known) || !is_string($user)) {
         return false;
      }
      if (function_exists('hash_equals')) {
         return hash_equals($known, $user);
      }
      if (strlen($known) !== strlen($user)) {
         return false;
      }
      $res = 0;
      $len = strlen($known);
      for ($i = 0; $i < $len; $i++) {
         $res |= ord($known[$i]) ^ ord($user[$i]);
      }
      return $res === 0;
   }
}
