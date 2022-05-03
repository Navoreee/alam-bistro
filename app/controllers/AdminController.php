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
        $this->view('templates/footer_empty');
    }

    public function orders_filter($filter)
    {
        Auth::authRole(array("admin"));

        $data['orders'] = $this->model('Order')->getOrdersWhere('submitted', '=', '1');

        $data['filter'] = $filter;

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/orders', $data);
        $this->view('templates/footer_empty');
    }

    public function order_details($id)
    {
        Auth::authRole(array("admin"));

        $data['order'] = $this->model('Order')->getOrderUserWhere('o.id = ' . $id);
        $data['details'] = $this->model('OrderDetail')->getDetailsItemWhere('d.order_id = ' . $id);

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/order_details', $data);
        $this->view('templates/footer_empty');
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
        $this->view('templates/footer_empty');
    }

    public function message_details($id)
    {
        Auth::authRole(array("admin"));

        $data['message'] = $this->model('Message')->getMessageById($id);
        $this->message_read($id);

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/message_details', $data);
        $this->view('templates/footer_empty');
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
        $cat_names = array();

        foreach ($data['categories'] as $cat) {
            $cat_names[$cat['id']] = $cat['name'];
        }
        $data['cat_names'] = $cat_names;

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu', $data);
        $this->view('templates/footer_empty');
    }

    public function menu_filter($id)
    {
        Auth::authRole(array("admin"));

        $data['categories'] = $this->model('Category')->getAllCategories();
        $data['items'] = $this->model('MenuItem')->getItemsWhere('category_id', '=', $id);
        $cat_names = array();

        foreach ($data['categories'] as $cat) {
            $cat_names[$cat['id']] = $cat['name'];
        }
        $data['cat_names'] = $cat_names;
        $data['filter'] = $cat_names[$id];

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu', $data);
        $this->view('templates/footer_empty');
    }

    public function menu_search()
    {
        Auth::authRole(array("admin"));

        $data['categories'] = $this->model('Category')->getAllCategories();

        $data['items'] = $this->model('MenuItem')->getItemsWhere('name', 'LIKE', '%' . $_POST['q'] . '%');
        $cat_names = array();

        foreach ($data['categories'] as $cat) {
            $cat_names[$cat['id']] = $cat['name'];
        }
        $data['cat_names'] = $cat_names;
        $data['query'] = $_POST['q'];

        $data['page_title'] = 'Admin Dashboard';

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu', $data);
        $this->view('templates/footer_empty');
    }

    public function menu_details($id)
    {
        Auth::authRole(array("admin"));

        $data['categories'] = $this->model('Category')->getAllCategories();
        $data['item'] = $this->model('MenuItem')->getItemById($id);

        $this->view('templates/admin/header', $data);
        $this->view('admin/menu_details', $data);
        $this->view('templates/footer_empty');
    }

    public function menu_add()
    {
        Auth::authRole(array("admin"));

        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $data = $_POST;
        $data['image'] = $file_ext;

        $valid = $this->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'price' => 'required|integer',
                'image' => 'required|image'
            ],
            $data
        );

        if ($valid == true) {
            $file_name = uniqid() . '.' . $file_ext;
            $data['img_name'] = $file_name;

            move_uploaded_file($file_tmp, SITE_ROOT . '/images/' . $file_name);

            if ($this->model('MenuItem')->addItem($data) > 0) {
                Flasher::setFlash('Successfully added.', 'success');
            } else {
                Flasher::setFlash('Failed to add.', 'danger');
            }
        }

        header('Location: ' . BASEURL . '/admin/menu');
        exit;
    }

    public function menu_delete($id)
    {
        Auth::authRole(array("admin"));

        $item = $this->model('MenuItem')->getItemById($id);

        if ($this->model('MenuItem')->deleteItem($id) > 0) {
            unlink(SITE_ROOT . '/images/' . $item['img_name']);
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
        Auth::authRole(array("admin"));

        $data = $_POST;
        $data['id'] = $id;

        $valid = $this->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'price' => 'required|integer',
            ],
            $data
        );

        if ($valid == true) {

            $item = $this->model('MenuItem')->getItemById($id);

            if (!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
                $data['img_name'] = $item['img_name'];
            } else {
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
                $data['image'] = $file_ext;
                $file_name = uniqid() . '.' . $file_ext;
                $data['img_name'] = $file_name;

                move_uploaded_file($file_tmp, SITE_ROOT . '/images/' . $file_name);

                unlink(SITE_ROOT . '/images/' . $item['img_name']);
            }

            if ($this->model('MenuItem')->updateItem($data) > 0) {
                Flasher::setFlash('Update success.', 'success');
            } else {
                Flasher::setFlash('Failed to update.', 'danger');
            }

            header('Location: ' . BASEURL . '/admin/menu');
            exit;
        } else {
            header('Location: ' . BASEURL . '/admin/menu_details/' . $id . '/');
            exit;
        }
    }
}
