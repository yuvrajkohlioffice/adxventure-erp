<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $table = "logs";

    protected $fillable = [
        'user_id','type','content','status','ip','time'
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
   public static function LoginLogsCreate($user_id, $type, $content){
       
        if (!$user_id) {
            return NULL;
        }
    
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['content'] = $content;
        $data['ip'] = request()->ip();
        $data['time'] = now();
    
        return Logs::create($data);
       
    }

    
    
    // public static function LoginLogsCreate($user_id,$type,$content){
        
    //         if(!$user_id){
    //             return NULL;
    //         }
            
    //         $data['user_id'] = $user_id;
    //         $data['type'] = $type;
    //         $data['content'] = $content;
    //         $data['ip'] = request()->ip();

    //         return Logs::create($data);
            
    // }
    
    

}
