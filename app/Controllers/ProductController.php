<?php


namespace Controllers;

use Core\Controller;
use Core\View;

/**
 * Class ProductController
 */
class ProductController extends Controller
{
    public function indexAction()
    {
        $this->forward('product/list');
    }

    /**
     *
     */
    public function listAction()
    {
        $this->set('title', "Товари");

        $products = $this->getModel('Product')
            ->initCollection()
            ->sort($this->getSortParams())
            ->getCollection()
            ->select();
        $this->set('products', $products);

        $this->renderLayout();
    }

    /**
     *
     */
    public function viewAction()
    {
        $this->set('title', "Картка товару");

        $product = $this->getModel('Product')
            ->initCollection()
            ->filter(['id' => $this->getId()])
            ->getCollection()
            ->selectFirst();
        $this->set('product', $product);

        $this->renderLayout();
    }

    /**
     *
     */
    public function editAction()
    {
        $model = $this->getModel('Product');
        $this->set('saved', 0);
        $this->set("title", "Редагування товару");
        $id = filter_input(INPUT_POST, 'id');
        if ($id) {
            $values = $model->getPostValues();
            $this->set('saved', 1);
            $model->saveItem($id, $values);
        }
        $this->set('product', $model->getItem($this->getId()));

        $this->renderLayout();
    }

    /**
     *
     */
    public function addAction()
    {
        $model = $this->getModel('Product');
        $this->set("title", "Додавання товару");

        if ($values = $model->getPostValues()) {
            $newProduct = $model->addItem($values);
            if ($newProduct){
               $this->redirect(sprintf("/product/edit?id=%d", $newProduct["id"]));
            }
        }
        $this->renderLayout();
    }

    /**
     *
     */
    public function deleteAction()
    {
        $model = $this->getModel('Product');
        $this->set("title", "Вилучення товару");
        $id = filter_input(INPUT_POST, 'id');
        if ($id) {
            $model->deleteItem($id);
            $this->redirect('/product/list');
            return;
        }
        $this->set('product', $model->getItem($this->getId()));

        $this->renderLayout();
    }

    /**
     *
     */
    public function deleteAction()
    {
        $model = $this->getModel('Product');
        $this->set("title", "Вилучення товару");
        $id = filter_input(INPUT_POST, 'id');
        if ($id) {
            $model->deleteItem($id);
            $this->redirect('/product/list');
            return;
        }
        $this->set('product', $model->getItem($this->getId()));

        $this->renderLayout();
    }

    /**
     * @return array
     */
    public function getSortParams()
    {
        $params = [];
        $sortfirst = filter_input(INPUT_POST, 'sortfirst');
        if ($sortfirst !== "price_NONE") {
            if ($sortfirst === "price_DESC") {
                $params['price'] = 'DESC';
            } else {
                $params['price'] = 'ASC';
            }
        if ($sortfirst !== "price_NONE") {
            if ($sortfirst === "price_DESC") {
                $params['price'] = 'DESC';
            } else {
                $params['price'] = 'ASC';
            }
        }
        $sortsecond = filter_input(INPUT_POST, 'sortsecond');
        if ($sortsecond !== "qty_NONE") {
            if ($sortsecond === "qty_DESC") {
                $params['qty'] = 'DESC';
            } else {
                $params['qty'] = 'ASC';
            }
        if ($sortsecond !== "qty_NONE") {
            if ($sortsecond === "qty_DESC") {
                $params['qty'] = 'DESC';
            } else {
                $params['qty'] = 'ASC';
            }
        }


        return $params;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return filter_input(INPUT_GET, 'id');
    }
}