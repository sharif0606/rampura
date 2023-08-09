<?php

namespace App\Http\Traits;

trait ResponseTrait{
    public function resMessage($status=true, $error=null, $message=null){
        return [
            'response' =>[
                    'status' => $status,
                    'error' => $error,
                    'message' => $message,
                    'class' => $status?'success':'danger'
                ]
            ];
    }   
    public function resMessageHtml($status=true, $error=null, $message=null){
        $st=$status?'success':'danger';
        $type=$status?'Success':'logout Completed';
        return [
            'response' =>[
                    'message' => "<div class='py-1 alert alert-".$st." alert-dismissible fade show' role='alert'>
                    <strong>".$type."!</strong> ".$message."
                    <button type='button' class='btn-close py-2' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>"
                ]
            ];
    }   
}