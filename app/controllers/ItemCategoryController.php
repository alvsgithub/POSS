<?php

class ItemCategoryController extends Controller implements ResourceController{

    private $categories;

    public function __construct(ItemCategoryRepository $categories) {
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if(Request::ajax()){
            $paginator = $this->categories->paginate(8);

            $options = [];
            $categories = $paginator->getItems();
                    
            foreach($categories as $category){
                $view = View::make('entry.itemcategory_option', ['id' => $category['id'] ]);
                $contents = (string) $view;  
                array_push($options, $contents);
            }

            return Response::json([
                'categories' => $paginator->getCollection()->toJson(),
                'links' => $paginator->links()->render(),
                'options' => $options
            ]);
        }
        return View::make('itemcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('itemcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $categoryData = [
            'name' => Input::get('name'),
            'description' => Input::get('description'),
        ];
        $rules = array(
            'name' => 'required|unique:item_categories',
            'description' => 'required',
        );
        $validator = Validator::make($categoryData, $rules);

        if ($validator->fails()) {
            return Redirect::to('itemcategories/create')
                            ->withErrors($validator)
                            ->withInput(Input::all());
			echo'<script type="text/javascript">alert("Category name entered already exist!");</script>';
        }
        $this->categories->add($categoryData);
        Session::flash('message', 'Successfully added new item category!');
        return Redirect::route('itemcategories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $categoryData = $this->categories->find($id);
        return View::make('itemcategory.edit', $categoryData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $categoryData = [
            'name' => Input::get('name'),
            'description' => Input::get('description'),
        ];
        $rules = [
            'name' => 'Unique:item_categories',
            'description' => '',
        ];
        $validator = Validator::make($categoryData, $rules);
        if ($validator->fails()) {
            return Redirect::to('itemcategories/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput(Input::all());
							//echo'<script>';
        }
        $this->categories->edit($id, $categoryData);
        return Redirect::route('itemcategories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        try{
            $this->categories->delete($id);
        } catch(ErrorException $e){

        }

        if(Request::ajax()){
            echo 'true'; 
        }
        else{
            return Redirect::route('itemcategories.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $a = new ItemRepository;
        $b = $a->findItemByCategoryId($id);
        return View::make('itemcategory.show', [
                    'items' => $b
        ]);
    }
}
