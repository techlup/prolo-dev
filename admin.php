<?php
require 'view.php';
require 'db.php';
$page=new Page();
$page->start("admin");
    if(DB::getConn()){
        $page->alert(DB::getConn(), "danger");
    }
    else
    {
        if(isset($_POST['table_name'])){

            $table = $_POST['table_name'];

            $tbl = new Table($table, true);

            $code = new Section();
            $code->class="container-fluid px-4 row";
            $code->addItem(($tbl->form()));

            $tbl->method ="post";            
            $data='';
            if(isset($_POST['save']))
            {
                $tbl->getData();
                $img=new File('image');
                if($img->upload('image'))
                {
                    $tbl->addData('image', $img->file_name);
                    if($tbl->data['id']>0)
                        $tbl->update() ? $page->alert("Data has been saved", "success"): $page->alert("Failed", "danger");
                    else
                        $tbl->add() ? $page->alert("Data has been saved", "success"): $page->alert("Failed", "danger");
                }
            }
            if(isset($_POST['delete'])){
                $tbl->getData();
                $tbl->delete() ? $page->alert("Data has been deleted", "success"): $page->alert("Failed", "danger");
            }
            if(isset($_POST['search']) || isset($_POST['id'])&& !isset($_POST['new'])){
                $tbl->getData();
                $data = $tbl->get('id');
                $code->addData($data);
            }
            $fields = ['id', 'town', 'area', 'type'];
            $data=$tbl->select();
            $data_table = new DataTable();
            $data_table->addItem($tbl->rowHeader($fields));
            $data_table->addClass("table-hover table-dark col-md-6 mx-auto my-4");
                for($i=0; $i<sizeof($data); $i++)
                {
                    $data_table->addItem($tbl->rowFields($fields));
                    $data_table->addData($data[$i]);
                }                
            $code->addItem($data_table->show(false));
            $code->show();
        }
        else
       { 
            $code = new Section();
            $code->class="container row my-4";
            $code->addItem($page->snip("table_list"));
            $code->show();
        }
    }
$page->end();
?>