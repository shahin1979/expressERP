<div class="jumbotron" id="edit-product">
    <div class="row w-100">
        <div class="col-md-8">
            <div class="card border-info mx-sm-1 p-3">
                <div class="card border-info shadow text-info p-3 my-card" ><span class="fa fa-car" aria-hidden="true">Basic Data</span></div>

                <table class="table table-success table-responsive table-striped">
                    <tbody>
                        <tr>
                            <td><label for="category_id" class="control-label">Category<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('category_id', $categories , null , array('id' => 'category_id', 'class' => 'form-control')) !!}</td>
                            <td><label for="subcategory_id" class="control-label">Category<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('subcategory_id', $subcategories , null , array('id' => 'subcategory_id', 'class' => 'form-control')) !!}</td>
                        </tr>
                        <tr>
                            <td><label for="brand_id" class="control-label">Brand</label></td>
                            <td>{!! Form::select('brand_id', $brands , null , array('id' => 'brand_id', 'class' => 'form-control','placeholder' => 'Select Brand...')) !!}</td>
                            <td><label for="unit_name" class="control-label">Unit<span style="color: red">*</span></label></td>
                            <td>{!! Form::select('unit_name', $units , null , array('id' => 'unit_name', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="second_unit" class="control-label">Second Unit</label></td>
                            <td>{!! Form::select('second_unit', $units , null , array('id' => 'second_unit', 'class' => 'form-control','placeholder' => 'Second Unit...')) !!}</td>
                            <td><label for="third_unit" class="control-label">Third Unit</label></td>
                            <td>{!! Form::select('third_unit', $units , null , array('id' => 'third_unit', 'class' => 'form-control','placeholder' => 'Third Unit...')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="size_id" class="control-label">Size</label></td>
                            <td>{!! Form::select('size_id', $sizes , null , array('id' => 'size_id', 'class' => 'form-control','placeholder' => 'Select Size...')) !!}</td>
                            <td><label for="color_id" class="control-label">Color</label></td>
                            <td>{!! Form::select('color_id', $colors , null , array('id' => 'color_id', 'class' => 'form-control','placeholder' => 'Select Color...')) !!}</td>
                        </tr>
                    </tbody>

                </table>

{{--                <div class="text-info text-center mt-3"><label for="category_id" class="control-label">Category<span style="color: red">*</span></label></div>--}}
{{--                <div class="text-info text-center mt-2">{!! Form::select('category_id', $categories , null , array('id' => 'category_id', 'class' => 'form-control')) !!}</div>--}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success mx-sm-1 p-3" style="background: #3A92AF;background: -webkit-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);background: -moz-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);
                        background: -o-linear-gradient(bottom left, #3A92AF 0%, #5CA05A 100%);
                            background: linear-gradient(to top right, #3A92AF 0%, #5CA05A 100%);">
                <div class="card border-success shadow text-success p-3 my-card"><span class="fa fa-eye" aria-hidden="true">Image</span></div>

                <div class="imageupload">
                    <div class="file-tab">
                        <label class="btn btn-success btn-file">
                            <span>Upload Photo</span>
                            <!-- The file is stored here. -->
                            <input type="file" name="product-image" id="product-image">
                        </label>
                        <button type="button" class="btn btn-default">Remove</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="row w-100">

        <div class="col-md-6">
            <div class="card border-danger mx-sm-1 p-3">
                <div class="card border-danger shadow text-danger p-3 my-card" ><span class="fa fa-heart" aria-hidden="true">Numeric Data</span></div>
                <div class="text-danger text-center mt-3"><h4>Hearts</h4></div>
                <div class="text-danger text-center mt-2"><h1>346</h1></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-warning mx-sm-1 p-3">
                <div class="card border-warning shadow text-warning p-3 my-card" ><span class="fa fa-inbox" aria-hidden="true">Rest Info</span></div>
                <div class="text-warning text-center mt-3"><h4>Inbox</h4></div>
                <div class="text-warning text-center mt-2"><h1>346</h1></div>
            </div>
        </div>
    </div>

</div>
