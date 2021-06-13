<?php

namespace App\Http\Controllers;

use App\Models\Datatable;
use App\Models\Job;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\Data;

class Datatables extends Controller
{

    public function getData(Request $request){

        if(isset($_GET['search']['value'])){
            $search = $_GET['search']['value'];
        }
        else{
            $search = '';
        }
        if(isset($_GET['length'])){
            $limit = $_GET['length'];
        }
        else{
            $limit = 10;
        }

        if(isset($_GET['start'])){
            $ofset = $_GET['start'];
        }
        else{
            $ofset = 0;
        }

        $orderType = $_GET['order'][0]['dir'];
        $nameOrder = $_GET['columns'][$_GET['order'][0]['column']]['name'];

        $total = Datatable::orWhere('username' , 'like' , '%' . $search.'%')
            ->orWhere('first_name' ,  'like' , '%'. $search.'%')
            ->orWhere('last_name' ,  'like' ,  '%' .$search.'%')->count();

        $datas = Datatable::orWhere('username' , 'like' , '%' . $search.'%')
            ->orWhere('first_name' ,  'like' , '%'. $search.'%')
            ->orWhere('last_name' ,  'like' ,  '%' .$search.'%')
            ->offset($ofset)->limit($limit)
            ->orderBy($nameOrder , $orderType)->get();

        $data = array();
        $i=0+$ofset;
        foreach ($datas  as $row)
        {
            if($row['status'] == 0){ $status =   ' <span class="btn btn-danger btn-sm tgl_change_post danger" data-status="1" id="post_'.$row['user_id'].'" data-id="'.$row['user_id'].'">Inactive</span>';}
            elseif($row['status'] == 1) { $status =  '<span class="btn btn-success btn-sm tgl_change_post" data-status="0" data-id="'.$row['user_id'].'" id="post_'.$row['user_id'].'">Active</span>';}

//            update_post_status('ci_ads' , $row['id']);
            $data[]= array(
                ++$i,
                $row['username'],
                $row['first_name']. ' '. $row['last_name'],
                $row['gender'],
                $status,
            );
        }

        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] =  $total;
        $records['data'] = $data;
        echo json_encode($records);

    }


}
