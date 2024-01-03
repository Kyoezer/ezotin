<?php
class BaseModel extends Eloquent{
    protected $primaryKey = 'Id';
    protected $rules = array();
    protected $messages = array();
    protected $errors;
    public $timestamps = false;
    
    public static function boot(){
        parent::boot();
        static::creating(function($post){
            /*foreach($post as $k=>$v){
                if(gettype($v)=='string'){
                    $val=trim($v);
                    if(strlen($val)==0){
                        $post[$k]=null;
                    }
                }
            }*/
            if(Auth::check()){
                $post->CreatedBy = Auth::user()->Id;
            }
            if(empty($post->Id)){
                $uuid=DB::select("select uuid() as Id");
                $generatedId=$uuid[0]->Id;
                $post->Id=$generatedId;
            }
            $post->CreatedOn = date('Y-m-d G:i:s');
        });
        static::updating(function($post){
            if(Auth::check()){
                $post->EditedBy = Auth::user()->Id;
            }
            $post->EditedOn = date('Y-m-d G:i:s');
            foreach ($post->toArray() as $name => $value) {
                if (empty($value)) {
                    $post->{$name} = null;
                }
            }
        });
    }
    public function validate($data){
        $v = Validator::make($data, $this->rules, $this->messages);
        if ($v->fails()){
            $this->errors = $v->errors();
            return false;
        }
        return true;
    }
    public function errors(){
        return $this->errors;
    }
        /*
     * Method to strip tags globally.
     */
    public static function globalXssClean(){
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }
    public static function arrayStripTags($array){
        $result = array();
        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = htmlentities($key);
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            }else{
                if($key == 'Content'){
                    $result[$key] = trim($value);
                }else{
                    $value = strip_tags($value);
                    $result[$key] = str_replace('=','',trim(htmlentities($value)));
                }

            }
        }
        return $result;
    }
}