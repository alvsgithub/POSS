<?php

class ItemController extends Controller{

	public function itemsAction() {
        return View::make('item/items', array(
                    'items' => Item::getItems(),
                    'barcode' => Item::item()->barcode,
					'name' => Item::item->name,
					'price' => Item::item->price,
					'description' => Item::item->description,
					'size_or_weight' => Item::item->size_or_weight,
					'category_id' => Item::item->category_id
        ));
    }

    public function addItemAction() {
        if (Input::server('REQUEST_METHOD') == 'POST') {
            if ((Input::get('barcode') != null) && (Input::get('name') != null) && (Input::get('price') != null) && (Input::get('description') != null) && (Input::get('size_or_weight') != null) && (Input::get('category_id') != null)) {
                $itemData = [
                    'barcode' => Input::get('barcode'),
                    'name' => Input::get('name'),
                    'price' => Input::get('price'),
					'description' => Input::get('description'),
					'size_or_weight' => Input::get('size_or_weight'),
					'category_id' => Input::get('category_id')
                ];
                try {
                    Item::createItem($itemData);
                    return Redirect::route('items/add');
                } catch (ErrorException $exception) {
                    echo $exception->getMessage();
                }
            } else {
                throw new ErrorException("Do not leave any blank field!");
                return Redirect::route("items/add");
            }
        }
        return View::make('items/add');
    }

    
}