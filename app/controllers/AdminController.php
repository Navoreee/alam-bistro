<?php

class AdminController extends Controller
{

    public function index()
    {
        header('Location: ' . BASEURL . '/admin/orders');
        exit;
    }

    public function orders()
    {
        Auth::authRole(array("admin"));

        $data['orders'] = $this->model('Order')->getOrdersWhere('submitted', '=', '1');

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/orders', $data);
        $this->view('templates/footer');
    }

    public function order_details($id)
    {
        Auth::authRole(array("admin"));

        $data['order'] = $this->model('Order')->getOrderUserWhere('o.id = ' . $id);
        $data['details'] = $this->model('OrderDetail')->getDetailsItemWhere('d.order_id = ' . $id);

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/order_details', $data);
        $this->view('templates/footer');
    }

    public function order_complete($id)
    {
        Auth::authRole(array("admin"));

        $this->model('Order')->updateCompleted($id);

        header('Location: ' . BASEURL . '/admin/orders');
        exit;
    }


    public function messages()
    {
        Auth::authRole(array("admin"));

        $data['messages'] = $this->model('Message')->getAllMessages();

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/messages', $data);
        $this->view('templates/footer');
    }

    public function message_details($id)
    {
        Auth::authRole(array("admin"));

        $data['message'] = $this->model('Message')->getMessageById($id);
        $this->message_read($id);

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/message_details', $data);
        $this->view('templates/footer');
    }

    public function message_read($id)
    {
        Auth::authRole(array("admin"));

        $this->model('Message')->updateReadReceipt($id);
    }



    public function menu()
    {
        Auth::authRole(array("admin"));

        $data['categories'] = $this->model('Category')->getAllCategories();
        $data['items'] = $this->model('MenuItem')->getAllItems();

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu', $data);
        $this->view('templates/footer');
    }

    public function menu_details($id)
    {
        Auth::authRole(array("admin"));

        $data['categories'] = $this->model('Category')->getAllCategories();
        $data['item'] = $this->model('MenuItem')->getItemById($id);

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu_details', $data);
        $this->view('templates/footer');
    }

    public function menu_add()
    {
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $file_name = uniqid() . '.' . $file_ext;

        $data = $_POST;
        $data['img_name'] = $file_name;

        move_uploaded_file($file_tmp, SITE_ROOT . '/images/' . $file_name);

        if ($this->model('MenuItem')->addItem($data) > 0) {
            Flasher::setFlash('Successfully added.', 'success');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        } else {
            Flasher::setFlash('Failed to add.', 'danger');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        }
    }

    public function menu_delete($id)
    {
        if ($this->model('MenuItem')->deleteItem($id) > 0) {
            Flasher::setFlash('Delete success.', 'success');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        } else {
            Flasher::setFlash('Failed to delete.', 'danger');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        }
    }

    public function menu_update($id)
    {
        $data = $_POST;
        $data['id'] = $id;

        if ($this->model('MenuItem')->updateItem($data) > 0) {
            Flasher::setFlash('Update success.', 'success');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        } else {
            Flasher::setFlash('Failed to update.', 'danger');
            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        }
    }
}
