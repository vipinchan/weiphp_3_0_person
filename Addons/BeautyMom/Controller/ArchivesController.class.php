<?php
/**
 * 
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-09-09 20:49:39
 * @version $Id$
 */

namespace Addons\BeautyMom\Controller;

class ArchivesController extends BaseController
{
    public function lists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_archives_body');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $this->assign($list_data);

        // 新增档案时，选择人应该从对应公众号粉丝里选择
        $get_param = $GLOBALS ['get_param'];
        $get_param['token'] = get_token();
        $this->assign( 'get_param', $get_param );
        $this->display();

    }

    public function chestLists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_archives_chest');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $this->assign($list_data);
        $this->display('lists');

    }

    public function physiqueLists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_archives_physique');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $this->assign($list_data);
        $this->display('lists');

    }

    public function scarLists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_archives_scar');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $this->assign($list_data);
        $this->display('lists');

    }

    public function m_index() {
        $this->display();
    }

    public function m_lists($archivesType) {
        $uid      = get_mid();
        $modelName = 'mom_archives_' . $archivesType;

        $model = $this->getModel($modelName);
        $lists = M($modelName)->where(array('uid' => $uid))->select();
        // var_dump($lists);
        $this->assign('lists', $lists);
        $this->assign('modelId', $model['id']);
        $this->display();
    }

    public function m_view($model = null)
    {
    	defined ( '_ACTION' ) or define ( '_ACTION', 'mobileForm' );
		is_array ( $model ) || $model = $this->getModel ( $model );

		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $Model->save ()) {
				$this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			// 获取数据
			$id = I ( 'id' );
			$data = M ( get_table_name ( $model ['id'] ) )->find ( $id );

			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );

			$this->display ();
		}
    }
}