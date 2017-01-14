<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// 上传
class upload extends MY_Controller
{

    protected $rules = array(
        "photo" => array(
            array(
                'field'   => 'upload_file',
                'label'   => '文件字段名',
                'rules'   => 'trim|required'
            )
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    }

    /**
     * 图片上传通用
     */
    // http://xxx/appuser/photo
    public function photo()
    {
        // 返回服务器时间以及预定义参数
        $vdata['timeline']   = time();
        $vdata['content']    = '';
        $vdata['secure']     = 0;
        // 附件上传
        if (is_post()) {
            logfile('UPLOAD: start ------------->', 'upload/Upload_');
            $config['upload_path'] = UPLOAD_PATH.date('Y/m/d', time()).'/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = '0';
            $config['max_width']  = '0';
            $config['max_height']  = '0';
            $config['file_name']  = date("YmdHis").rand_str().nanoSecond();

            // 创建目录
            $upload_dir = $config['upload_path'];
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $this->load->library('upload', $config);

            $post_data = $this->input->post();

            logfile('UPLOAD: $post_data ------------->:'.print_r($post_data, 1), 'upload/Upload_');

            if (!$this->upload->do_upload('upload_file')) {
                $error = array('error' => $this->upload->display_errors('', ''));
                //返回用户详细数据
                $vdata['returnCode']   = '0011';
                $vdata['returnInfo'] = '请求失败';
                $vdata['secure']     = 0;
                $vdata['content'] = $error['error'];
            } else {
                $data = array('upload_data' => $this->upload->data());
                logfile('UPLOAD: $data ------------->:'.print_r($data, 1), 'upload/Upload_');
                $files = array(
                    'name'=>$data['upload_data']['file_name'],
                    'size'=>$data['upload_data']['file_size'],
                    'type'=>$data['upload_data']['file_type'],
                    'upload_path'=>$config['upload_path'],
                    'url'=>date('Y/m/d', time()).'/'.$data['upload_data']['file_name'],
                    'thumbnailUrl'=>date('Y/m/d', time()).'/'.'thumbnail/'.$data['upload_data']['file_name'],
                    'timeline'=>time(),
                    );

                logfile('UPLOAD: $files ------------->:'.print_r($files, 1), 'upload/Upload_');
                $this->_scale($files);
                $file = $this->_db_isnert($files);

                //返回用户详细数据
                $vdata['returnCode']   = '200';
                $vdata['returnInfo'] = '操作成功';
                $vdata['secure']     = 0;
                $vdata['content']['res']    = $file['id'];
            }
        } else {
            //返回用户详细数据
            $vdata['returnCode'] = '200';
            $vdata['returnInfo'] = '请求失败';
            $vdata['secure']     = 0;
            $vdata['content']['res'] = null;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->_send_json_befor($vdata)));
    }

    protected function _send_json_befor($data)
    {
        // $vdata['timeline'] = time();

        $vdata['returnCode'] = $data['returnCode'];
        $vdata['msg'] = $data['returnInfo'];
        $vdata['secure'] = $data['secure'];
        if ($data['secure'] != '0') {
            //load_AES
            $this->load->library('AES');
            $vdata['data'] = AES::encrypt(json_encode($data['content']), KEY);
        } else {
            $vdata['data'] = $data['content'];
        }

        return $vdata;
    }

    protected function _scale($file)
    {
        $config['source_image'] = UPLOAD_PATH.$file['url'];
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = true;
        $config['new_image'] = $file['upload_path'].'thumbnail/'.$file['name'];
        $config['width']     = 75;
        $config['height']   = 50;
        $config['thumb_marker']   = '';

        // 创建目录
        $upload_dir = $file['upload_path'].'thumbnail/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $this->load->library('image_lib', $config);
        // $this->image_lib->initialize($config);

        //切片
        if (!$this->image_lib->resize()) {
            $vdata['status'] = 0;
            $vdata['returnInfo'] = $this->image_lib->display_errors();
        } else {
            $vdata['status'] = 1;
            $vdata['returnInfo'] = '已经裁剪图片！';
        }
    }

    // 插入数据库
    protected function _db_isnert($files)
    {
        $this->load->model('upload_model', 'mupload');
        // var_dump($files);
        if (isset($files->error) and $files->error != '') {
            exit;
        }
        $d = array();
        $d['name'] = $files['name'];
        $d['size'] = $files['size'];
        $d['type'] = $files['type'];
        $d['url'] = $files['url'];
        $d['thumb'] = $files['thumbnailUrl'];
        $d['deleteUrl'] = '?file='.rawurlencode($files['name']).'&dt='.date('Y/m/d', time());
        $d['timeline'] = time();

        $files['id'] = $this->mupload->create($d);
        $files['thumb'] = $d['thumb'];

        return $files;
    }

    // 压缩（未使用）
    protected function scale($fileUrl)
    {
        $this->load->library('image_lib');
        $config['quality'] = '100';
        $config['width'] = 100;
        $config['height'] = 100;
        $config['master_dim'] = 'width';
        if (preg_match('/(gif|png|jpg|jpeg)$/i', $fileUrl)) {
            $config['source_image'] =  UPLOAD_PATH.$fileUrl;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
        }
    }
}
