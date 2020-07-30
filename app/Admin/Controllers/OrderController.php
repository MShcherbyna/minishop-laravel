<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('billing_email', __('Billing email'));
        $grid->column('billing_name', __('Billing name'));
        $grid->column('billing_address', __('Billing address'));
        $grid->column('billing_city', __('Billing city'));
        $grid->column('billing_province', __('Billing province'));
        $grid->column('billing_postalcode', __('Billing postalcode'));
        $grid->column('billing_phone', __('Billing phone'));
        $grid->column('billing_name_on_card', __('Billing name on card'));
        $grid->column('billing_subtotal', __('Billing subtotal'));
        $grid->column('billing_tax', __('Billing tax'));
        $grid->column('billing_total', __('Billing total'));
        $grid->column('payment_gateway', __('Payment gateway'));
        $grid->column('shipped', __('Shipped'));
        $grid->column('error', __('Error'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('billing_email', __('Billing email'));
        $show->field('billing_name', __('Billing name'));
        $show->field('billing_address', __('Billing address'));
        $show->field('billing_city', __('Billing city'));
        $show->field('billing_province', __('Billing province'));
        $show->field('billing_postalcode', __('Billing postalcode'));
        $show->field('billing_phone', __('Billing phone'));
        $show->field('billing_name_on_card', __('Billing name on card'));
        $show->field('billing_subtotal', __('Billing subtotal'));
        $show->field('billing_tax', __('Billing tax'));
        $show->field('billing_total', __('Billing total'));
        $show->field('payment_gateway', __('Payment gateway'));
        $show->field('shipped', __('Shipped'));
        $show->field('error', __('Error'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->number('user_id', __('User id'));
        $form->text('billing_email', __('Billing email'));
        $form->text('billing_name', __('Billing name'));
        $form->text('billing_address', __('Billing address'));
        $form->text('billing_city', __('Billing city'));
        $form->text('billing_province', __('Billing province'));
        $form->text('billing_postalcode', __('Billing postalcode'));
        $form->text('billing_phone', __('Billing phone'));
        $form->text('billing_name_on_card', __('Billing name on card'));
        $form->decimal('billing_subtotal', __('Billing subtotal'));
        $form->decimal('billing_tax', __('Billing tax'));
        $form->decimal('billing_total', __('Billing total'));
        $form->text('payment_gateway', __('Payment gateway'))->default('stripe');
        $form->switch('shipped', __('Shipped'));
        $form->text('error', __('Error'));

        return $form;
    }
}
