<?php

namespace App\Admin\Controllers;

use App\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->filter(function($filter){
            $filter->disableIdFilter();

            $filter->column(1/2, function ($filter) {
                $filter->like('id', 'ID');
                $filter->like('name', 'Name');
                $filter->like('sku', 'Sku');
            });

            $filter->column(1/2, function ($filter) {
                $filter->like('price', 'Price');
                $filter->like('qty', 'Qty');
            });

        });
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->id('ID')->sortable('desc');
        $grid->column('name');
        $grid->column('sku');
        $grid->column('description');
        $grid->column('photo')->image(null, 70);
        $grid->column('qty');
        $grid->column('price');
        $grid->paginate(10);

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('name');
        $form->textarea('description');
        $form->text('price');
        $form->number('qty')->max(100);
        $form->image('photo');

        return $form;
    }

}
