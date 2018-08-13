<?php
namespace Addons\CmbLottery\Model;
use Think\Model;


/**
* 员工模型
*/
class StaffModel extends Model
{
    var $tableName = 'park_staff';
    
    /**
     * 员工信息获取
     * @param  [type]  $id   [description]
     * @param  integer $type 1为停车员，2为取车员
     * @return [type]        [description]
     */
    public function getInfo($id, $type){
        $map = array ();
        
        if(is_numeric($id)){
            $map['id'] = $id;
        } else{
            $map['openid'] = $id;
        }
        if(!empty($type)){
            $map['type'] = $type;
        }
        return $this->where ( $map )->find();
    }

    public function getByMap($map){
        $data = $this->where ( $map )->find();
        return $data;
    }

    public function update($data){
        if(empty($data)){
            return false;
        }

        
        // 添加或更新数据
        return empty ( $data ['id'] ) ? $this->data($data)->add () : $this->data($data)->save ();
    }

    /**
     * 获取空闲状态员工列表
     * @param  [type] $place_id [description]
     * @param  [type] $type     1为停车员，2为取车员
     * @return [type]           [description]
     */
    public function getList($place_id, $type){
        $map = array();
        $map['status'] = 1; //空闲

        !empty($place_id) && $map['place_id'] = $place_id;
        !empty($type) && $map['type'] = $type;
        return $this->where ( $map )->select();
    }
}