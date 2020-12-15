<?php
require 'code.php';
require 'config.php';
/**
 * class view
 * contains requirements shared by other clases
 * -----------------------------------------------------------------------------------------------------------------------------------------------------
 */
class View{
    public $code="";
    public $data="";
    public $name="";
    public $class="";
    public $id="";
    protected $items="";
    protected $attr= array();
    public function attr($key="",$val=""){
        if($key>"" && $val>""){
            switch(strtolower($key)){
                case "name":
                    $this->name=$val;
                break;
                case "class":
                    $this->class=$val;
                break;
                case "id":
                    $this->id=$val;
                break;
                default:
                    $this->attr[$key]=$val; 
            };
        };
    }
    public function alert($prompt="", $type="info"){
        
        echo '<div class="alert alert-'.$type.' position-fixed w-100 my-3" role="alert" style="z-index: 99;">'.$prompt.'
        <button type="button" class="close" data-dismiss="alert" arial-lable="close">
            <span arial-hidden="true">&times;</span>
        </button>
        </div>';
       
    }
    public function getIcons(){

        while(substr_count($this->items, '<%%ICON(')>0){
            $len=strpos($this->items, ')%%>', 0)-strpos($this->items, '<%%ICON(', 0)+4;
            $str = substr($this->items,strpos($this->items, '<%%ICON(', 0),$len);
            $icon = substr($this->items,strpos($this->items, '<%%ICON(', 0),$len-4);
            $icon = str_replace("<%%ICON(","", $icon);
            if (is_file("res/icons/$icon")){
                $this->items=str_replace($str,  "res/icons/$icon", $this->items);
            }else{
                $this->alert("ERROR: icon $icon was not found", "danger");
                $this->items=str_replace($str,  "", $this->items);
            }
        };
    }
    public function getImages(){

        while(substr_count($this->items, '<%%IMG(')>0){
            $len=strpos($this->items, ')%%>', 0)-strpos($this->items, '<%%IMG(', 0)+4;
            $str = substr($this->items,strpos($this->items, '<%%IMG(', 0),$len);
            $icon = substr($this->items,strpos($this->items, '<%%IMG(', 0),$len-4);
            $icon = str_replace("<%%IMG(","", $icon);
            if (is_file("res/img/$icon")){
                $this->items=str_replace($str,  "res/img/$icon", $this->items);
            }else{
                $this->alert("ERROR: image $icon was not found", "danger");
                $this->items=str_replace($str,  "", $this->items);
            }
        };
    }
    public function image($file=""){
        if (is_file("res/img/$file")){
            return "res/img/$file";
        }else{
            $this->alert("ERROR: image $file was not found", "danger");
        }        
    }
    public function icon($file=""){
        if (is_file("res/icons/$file")){
            return "res/icons/$file";
        }else{
            $this->alert("ERROR: icon $file was not found", "danger");

        }        
    }
    public function show($render=true){
        if($this->items>""){
            $this->code=str_replace('<%%DATA(prolo-items)%%>', $this->items, $this->code);
        };
        global $app;
        $this->code=str_replace('<%%DATA(prolo-id)%%>', $this->id>""?'id="'.$this->id.'"':"", $this->code);
        $this->code=str_replace('<%%DATA(prolo-name)%%>', $this->name>""?'name="'.$this->name.'"':"", $this->code);
        $attributes=array_keys($this->attr);
        $this->data="";
        foreach($attributes as $attr){
            $this->data=$this->data.' '.$attr.'="'.$this->attr[$attr].'"';
        };
        $this->code=str_replace('<%%DATA(prolo-attr)%%>', $this->data, $this->code);
        if($this->class>""){
            $this->code=str_replace('<%%DATA(prolo-class)%%>', 'class="'.get_class($this).' '.$this->class.'"', $this->code);
        };
        $this->code=str_replace('<%%DATA(app-brand)%%>', $app["brand"], $this->code);
        $this->code=str_replace('<%%DATA(app-name)%%>', $app["name"], $this->code);
        while(substr_count($this->code, '<%%DATA(')>0){
            $len=strpos($this->code, ')%%>', 0)-strpos($this->code, '<%%DATA(', 0)+4;
            $str = substr($this->code,strpos($this->code, '<%%DATA(', 0),$len);
            $this->code=str_replace($str, "", $this->code);
        };
        
        if($render){
            echo $this->code;
        }
        else{
            return $this->code;
        };
    }
    public function addItem($code=""){
        $this->items=$this->items.$code;
    }
    public function addData($item=[]){
        $data=array_keys($item);
        foreach($data as $dat){
            $this->code=str_replace("<%%DATA($dat)%%>", $item["$dat"], $this->code);
            $this->items=str_replace("<%%DATA($dat)%%>", $item["$dat"], $this->items);
        }
    }
    public function data($key="", $val=""){
        $this->code=str_replace("<%%DATA($key)%%>", $val, $this->code);
        $this->items=str_replace("<%%DATA($key)%%>", $val, $this->items);
    }
    public function addClass($class)
    {
        $this->class = $this->class." ".$class;
    }
};

/**
 * class page
 * -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
class Page extends View{
    public function class($name=null){
        if(! empty($name)){
            return "classes/$name.php";
        }else{
            $this->aletr("ERROR: class $name was not found","danger");
            return false;
        }
    }
    public function start($title=""){
        global $header;
        global $app;
        $this->code=$header;
        for($i=0; $i<sizeof($app['css']);$i++){
            $this->data= $this->data . '<link rel="stylesheet" href="css/'. $app["css"][$i].'">';
        };
        $this->code=str_replace('<%%DATA(styles)%%>', $this->data, $this->code);
        $this->data=strtoupper($app["name"]).' | '.ucfirst($title);
        $this->code=str_replace('<%%DATA(title)%%>', $this->data, $this->code);
        $this->code=str_replace('<%%DATA(icon)%%>', $app["icon"], $this->code);
        echo $this->code;
        echo str_replace('#4285f4',$app["color"]["primaryDark"],'
        <meta name="theme-color" content="#4285f4">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#4285f4">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#4285f4">');
        $this->data="";
        $this->code="";
    }
    public function end(){
        global $footer;
        global $app;
        $this->code=$footer;
        for($i=0; $i<sizeof($app['js']);$i++){
            $this->data= $this->data . '<script src="js/'. $app["js"][$i].'"></script>';
        };
        $this->code=str_replace('<%%DATA(scripts)%%>', $this->data, $this->code);
        echo $this->code;
        $this->data="";
        $this->code="";
    }
    public function getCode($file=""){
        if($file>""){
            is_file($file)?$content=@file_get_contents($file):$content=false;
            return $content;
        };
    }
    public function snip($file=""){
        if($file>""){
            $file="res/snips/$file.html";
            is_file($file)?$content=@file_get_contents($file):$content=false;
            return $content;
        };
    }
    
    
};
/**
 * class navbar
 * used to render navigationbar
 * -----------------------------------------------------------------------------------------------------------------------------------------
 */
class Navbar extends View{
    public function __construct() {
        global $nav;
        $this->code = $nav;
        $this->class="navbar navbar-expand-lg navbar-light bg-light";
    }
    public function render(){
        return $this->code;
    }
    public function getLinks($page="Default"){
        if($page=="")return null;
        $key = array_search(__FUNCTION__, array_column(debug_backtrace(), 'function'));
        $file_path=debug_backtrace()[$key]['file'];
        $file_path=str_replace('\\','/',$file_path);
        $file_name=basename($file_path).PHP_EOL;
        global $app;
        global $nav;
        $this->data="";
        $this->code = $nav;
        for($i=0; $i<sizeof($app['links'][$page]);$i++){
            if(is_array($app["links"][$page][$i][1])){
                $drop_links=$app["links"][$page][$i][1];
                $links="";
                for($y=1; $y<sizeof($drop_links); $y++){
                    $links=$links.'<a class="dropdown-item prolo-link" href="'.$drop_links[$y][1].'">'.$drop_links[$y][0].'</a>';
                };
                $this->data= $this->data .'
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span height="0px" class="w-100">'.$app["links"][$page][$i][0].'</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$links.'
                    </div>
                </li>  
                    ';
            }else{
                $link_path=$app["links"][$page][$i][1];
                $link_path=str_replace('\\','/',$link_path);
                $link_name=basename($link_path).PHP_EOL;
                $page_home=(substr($file_name,0,strpos($file_name,'.')));
                trim($link_name) == trim($file_name) || strcmp(trim("$link_name"),trim("#$page_home")) == 0?$this->data= $this->data . '<a href="'.$app["links"][$page][$i][1].'" class="nav-item nav-link active prolo-link"><span height="0px" class="w-100">'.$app["links"][$page][$i][0].'</span></a>': $this->data= $this->data . '<a href="'.$app["links"][$page][$i][1].'" class="nav-item nav-link prolo-link"><span height="0px" class="w-100">'.$app["links"][$page][$i][0].'</span></a>';
            };
        };
        $this->code=str_replace('<%%DATA(links)%%>', $this->data, $this->code);
        $this->code=str_replace('<%%DATA(app-brand)%%>', $app["brand"], $this->code);
    }
    public function enableSearch($id="navbar_search"){
        $this->data='
        <form id="'.$id.'" class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <input class="btn btn-info my-2 my-sm-0 d-none d-lg-block" type="submit" name="'.$id.'" value="Search">
      </form>';
        $this->code=str_replace('<%%DATA(search)%%>', $this->data, $this->code);
    }
};
/**
 * class section
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class Section extends View{
    public function __construct(){
        $this->code='<section <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>><%%DATA(prolo-items)%%></section>';
    }
};
/**
 * class row
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class Row extends View{
    public function __construct(){
        $this->class="row";
        $this->code='<div <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>><%%DATA(prolo-items)%%></div>';
    }
};
/**
 * class col
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class Col extends View{
    public function __construct($class="col-sm-6"){
        $this->class=$class;
        $this->code='<div <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>><%%DATA(prolo-items)%%></div>';
    }
};
/**
 * class carousel
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class Carousel extends View{
    public $indicators="";
    public function __construct(){
        global $carousel;
        $this->code=$carousel;
        $this->class="carousel slide my-3";
        $this->id="carouselExampleIndicators";
    }
    public function items(){
        return substr_count($this->items, 'carousel-item');
    }
    public function showIndicators(){
        $this->indicators="";
        for($i=0; $i<$this->items();$i++){
            $this->indicators=$this->indicators.'<li data-target="#'.$this->id.'" data-slide-to="'.$i.'"></li>';
        };
        $this->code=str_replace('<%%DATA(indicators)%%>', $this->indicators, $this->code);
    }
    public function showControls(){
        $controls='
        <a class="carousel-control-prev " href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon rounded-circle" aria-hidden="true"></span>
          <span class="sr-only rounded-circle">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon rounded-circle" aria-hidden="true"></span>
          <span class="sr-only rounded-circle">Next</span>
        </a>
        ';
        $this->code=str_replace('<%%DATA(controls)%%>', $controls, $this->code);
    }
}
/**
 * class carousel item
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class CarouselItem extends View{
    public $code="";
    public $image="";
    public $caption="";
    public $title="";

    public function __construct($status=""){
        $this->code='
        <div class="carousel-item '.$status.'">
            <%%DATA(image)%%>
            <div class="carousel-caption row justify-content-center">
                <div class="">
                <h3 class="text-secondary"><%%DATA(title)%%></h3>
                <p class="text-primary h4"><%%DATA(caption)%%></p>
                </div>
            </div>
        </div>
        ';
    }
    public function render(){
        // global $app;
        $this->code=str_replace('<%%DATA(caption)%%>', $this->caption, $this->code);
        $this->code=str_replace('<%%DATA(title)%%>', $this->title, $this->code);
        $this->image>""? $this->code=str_replace('<%%DATA(image)%%>', '<img class="d-block w-100 carousel-img" src="'.$this->image($this->image).'" alt="slide">', $this->code):false;
        return $this->code;
    }
}
/**
 * class carousel item
 * --------------------------------------------------------------------------------------------------------------------------------
 */
class CartItem extends View{
    public $code="";
    public $image="";
    public $caption="";
    public $title="";
    public $action="shop.php";
    public $link="";
    public $price=0;

    public function __construct($status=""){
        $this->class="col-md-3 px-5 my-3";
        $this->code='
        <div <%%DATA(prolo-name)%%> <%%DATA(prolo-id)%%> <%%DATA(prolo-class)%%> <%%DATA(prolo-attr)%%>>
        <form class="product-block card border-light bg-white" action="<%%DATA(form-action)%%>?<%%DATA(prolo-link)%%>" method="post">
            <img class="d-block w-100"  src="<%%DATA(image)%%>" alt="Product" height="150px">
            <div class="card-hearder mx-auto text-center">
                <h6 class="card-hearder text-center"><%%DATA(title)%%></h6>
                <small><%%DATA(caption)%%></small>
                <span class="float-left text-info font-italic small"><%%DATA(price)%%></span>
            </div>            
                       
            <div class="card-footer  border-0 bg-white">
            <input type="text" name="quantity" value="1" class="form-control w-25 d-inline" />
                <button type="submit" class="btn btn-sm btn-outline-secondary my-1 float-right"><i class="fas fa-shopping-cart px-2 text-dark"></i>Add</button>
            </div>
        </form>
    </div>
        ';
    }
    public function render(){
        // global $app;
        $this->code=str_replace('<%%DATA(form-action)%%>', $this->action, $this->code);
        $this->code=str_replace('<%%DATA(prolo-link)%%>', $this->link, $this->code);
        $this->code=str_replace('<%%DATA(caption)%%>', $this->caption, $this->code);
        $this->code=str_replace('<%%DATA(price)%%>', $this->price, $this->code);
        $this->code=str_replace('<%%DATA(title)%%>', $this->title, $this->code);
        $this->image>""? $this->code=str_replace('<%%DATA(image)%%>', $this->image($this->image), $this->code):false;
        return $this->show(false);
    }
}
/**
 * Floating action button
 */
Class ActionButton extends View{
    public function __construct(){
        $this->code='
        <div class="prolo-fab bg-light"> <%%DATA(prolo-items)%%> </div>
        <style>
            .prolo-fab {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                box-shadow: 0 6px 10px 0 #666;
                transition: all 0.1s ease-in-out;
              
                color: white;
                text-align: center;
                line-height: 70px;
              
                position: fixed;
                right: 50px;
                bottom: 50px;
             }
              
            .prolo-fab:hover {
                box-shadow: 0 6px 14px 0 #666;
                transform: scale(1.05);
             }
        </style>
        ';
    }
}
class SideNav extends View
{
    private $links ='';
    public function __construct(){
        global $side_nav;
        $this->class="bg-light";
        $this->code=$side_nav;
    }
    
    public function addLink($name, $link, $active="bg-light"){
        $this->links = $this->links.'<a href="'.$link.'" class="list-group-item list-group-item-action '.$active.' ">'.$name.'</a>';
    }

    public function show($cond=true)
    {
        $this->code=str_replace('<%%DATA(table-links)%%>', $this->links, $this->code);
        return parent::show($cond);
    }

}
class Code extends View{
    public function __construct($code=""){
        $this->code=$code;
    }
}
/**
 * class form
 */
class DataTable extends View{
    public function __construct($title='table'){
        global $data_table;
        $this->code=$data_table;
        $this->class="table";
        $this->data("table-title", ucwords($title));
    }
}

/**
 *  file upload
 */
require  'src/FileUpload.php';
class File
{
    private $file=null;
    public $file_name='';
    private $type='';
    public function __construct($type='image'){
        $this->type=$type;
        $this->file = new FileUpload\FileUpload();
        $this->file->setAllowMimeType($type);
    }

    public function upload($name='')
    {
        global $app;

        $this->file->setInput($name);
        $pos=strpos($this->type,'image');
        if($pos !== false)
            $this->file->setDestinationDirectory("../".$app['image_dir']);

        $name = $_FILES["$name"]["name"];
        $ext = end((explode(".", $name)));
        $id=uniqid();
        $this->file_name = $app['image_dir']."img_".$id.".".$ext;
        $this->file->setFilename("img_".$id.".%s");
        $this->file->save();

        if ($this->file->getStatus())
            return true;
        return false;
    }
}

?>
