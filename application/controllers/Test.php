<?php
// application/controllers/YourController.php
class Test extends CI_Controller {
    public function index() {
        $this->load->model('Test_model');
        $table_name = 'purchase';
        $data['columns'] = $this->Test_model->getTableColumns($table_name);
        $this->load->view('View', $data);
    }
}
