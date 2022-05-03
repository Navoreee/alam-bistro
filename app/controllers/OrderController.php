<?php

class OrderController extends Controller
{
    public function index()
    {
        Auth::authRole(array("admin", "user"));

        $data['order'] = $this->currentOrder();
        $data['details'] = $this->model('OrderDetail')->getDetailsItemWhere('d.order_id = ' . $data['order']['id']);

        $data['total_price'] = 0;

        foreach ($data['details'] as $detail) {
            $data['total_price'] += $detail['subtotal'];
        }

        $data['page_title'] = 'My Order';

        $this->view('templates/header', $data);
        $this->view('order/index', $data);
        $this->view('templates/footer');
    }

    public function detail_add($category_id, $item_id)
    {
        Auth::authRole(array("admin", "user"));

        $order = $this->currentOrder();

        $details = $this->model('OrderDetail')->getDetailsWhere('order_id', '=', $order['id']);
        $item = $this->model('MenuItem')->getItemById($item_id);

        $exists = false;

        foreach ($details as $detail) {
            if ($detail['item_id'] == $item_id) {
                $exists = true;
                break;
            }
        }

        if ($order['submitted'] == 1) {
            Flasher::setFlash('Cannot add item because you already have an ongoing order.', 'warning');
        } elseif ($exists) {
            Flasher::setFlash('Already exists in order. Go to My Orders to update the quantity.', 'warning');
        } else {
            $data['item_id'] = $item_id;
            $data['order_id'] = $order['id'];
            $data['quantity'] = 1;
            $data['subtotal'] = $item['price'];
            $this->model('OrderDetail')->addDetail($data);
            Flasher::setFlash('Added to order.', 'success');
        }

        header('Location: ' . BASEURL . '/menu/category/' . $category_id);
        exit;
    }

    public function detail_update($id)
    {
        Auth::authRole(array("admin", "user"));

        $detail = $this->model('OrderDetail')->getDetailById($id);
        $new_qty = $_POST['quantity'];
        $new_subtotal = ($detail['subtotal'] / $detail['quantity']) * $new_qty;

        $data['quantity'] = $new_qty;
        $data['subtotal'] = $new_subtotal;
        $data['id'] = $id;

        $this->model('OrderDetail')->updateDetail($data);

        header('Location: ' . BASEURL . '/order');
        exit;
    }

    public function detail_delete($id)
    {
        Auth::authRole(array("admin", "user"));

        $this->model('OrderDetail')->deleteDetail($id);
        header('Location: ' . BASEURL . '/order');
        exit;
    }

    public function currentOrder()
    {
        Auth::authRole(array("admin", "user"));

        $order = $this->model('Order')->getCurrentOrder(Auth::userId());

        if (empty($order)) {
            $this->model('Order')->addOrder();
            $order = $this->model('Order')->getCurrentOrder(Auth::userId());
        }

        return $order;
    }

    public function submitOrder($id)
    {
        Auth::authRole(array("admin", "user"));

        $data['time'] = date('Y-m-d H:i:s');
        $data['id'] = $id;
        $data['table_no'] = $_POST['table_no'];
        $data['total_price'] = 0;
        $data['submitted'] = 1;

        $details = $this->model('OrderDetail')->getDetailsWhere('order_id', '=', $id);

        foreach ($details as $detail) {
            $data['total_price'] += $detail['subtotal'];
        }

        if ($data['total_price'] > 0) {

            if ($this->model('Order')->updateOrder($data) > 0) {
                Flasher::setFlash('Your order has been submitted.', 'success');
                header('Location: ' . BASEURL . '/order');
                exit;
            } else {
                Flasher::setFlash('Failed to submit order.', 'danger');
                header('Location: ' . BASEURL . '/order');
                exit;
            }
        }
    }
}
